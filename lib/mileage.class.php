<?php
/**
 * 마일리지(적립금)클래스
 * @author  stickcandy81@nate.com (hjlee)
 * 
 */
class MILEAGE extends Common{
	
	public function __construct($place='front') {
		parent::__construct();
		$this->place = $place;
		$this->tbl = $this->tbls['mileage']; //메인테이블
		$this->cfg = $this->getConfig('mileage','section'); //마일리지 설정값

	}
	

	/**
	 * 등급별 적립률 반환
	 *
	 * @param [type] $grade
	 * @return void
	 */
	public function getRate($group_code) {
		if($group_code) {
			$Member = new MEMBER;
			$group_info = $Member->getGroupRow($group_code);
			return $group_info['group_addreserve'];
		}
		else return 0;
	}

	/**
	 * 마일리지 계산(관리자 설정 연동)
	 *
	 * @param [type] $price
	 * @param [type] $rate
	 * @return void
	 */
	public function calcMileage($price,$rate) {
		
		$cfg = $this->getConfig('mileage','section');
		$m = ($price*$rate)/100;

		switch($cfg['round_act']) {
			case 'B': //반올림
			default:
				$precision = log10($cfg['round_unit'])*-1;
				$m = round($m, $precision);
			break;
			case 'D': //내림
			$m = floor($m/$cfg['round_unit'])*$cfg['round_unit'];
			break;
			case 'U': //올림
				$m = ceil($m/$cfg['round_unit'])*$cfg['round_unit'];
			break;
		}

		return $m;

	}

	/**
	 * 마일리지 리스트반환 + 페이징
	 *
	 * @param [type] $where
	 * @param [type] $limit
	 * @param integer $page
	 * @return void
	 */
	public function getPaging($where, $limit, $page=1) {
		$tbl = $this->tbls['mileage'];

		if($page) {
			$offset = ($page-1)*$limit;
		}
		$sql = "SELECT * FROM {$tbl} WHERE {$where} ORDER BY idx DESC OFFSET {$offset} LIMIT {$limit}";
		$rs = $this->adodb->Execute($sql);

		$list = array();
		$count = $this->adodb->getOne("SELECT COUNT(*) FROM {$tbl} WHERE {$where}");
		$num = $count-$offset;
		while($row = $rs->FetchRow()) {
			$row['num'] = $num;
			$list[] = $row;
			$num--;
		}

		$sum = $this->adodb->getOne("SELECT SUM(mileage) FROM {$tbl} WHERE {$where}");

		return array(
			'list'=>$list,
			'sum'=>$sum,
			'count'=>$count
		);
	}

	public function sumUsable($member_id) {
		$tbl = $this->tbls['mileage'];
		$sql = "SELECT SUM(mileage) AS mileage FROM {$tbl} WHERE mem_id='{$member_id}'";
		
		$sum = $this->adodb->getOne($sql);
		if(!$sum) $sum = 0;

		$this->log_file(array('member_id'=>$member_id,'sum'=>$sum,'sql'=>$sql,'file'=>__FILE__));
		return $sum;
	}

	/**
	  * 마일리지 지급
	  *
	  * @param [type] $member_id
	  * @param [type] $mileage
	  * @param [type] $reason_flag
	  * @param [type] $reason
	  * @param integer $valid_day
	  * @return void
	  */
	public function give($record, $valid_day=0) {
		global $_ShopInfo;

		if($record['mileage'] == 0) { //지급마일리지가 0이면 처리하지 않음 
			return false;
		}

		if(!$valid_day) {
			$valid_day = $this->cfg['expire_year']*365;
		}
		$expire = strtotime("+{$valid_day} days");

		
		
		$record_default = array(
			'mem_id'=>'',
			'mileage'=>0,
			'mileage_remain'=>0,
			'mileage_reason_flag'=>'',
			'mileage_reason'=>'',
			'mileage_valid'=>'Y',
			'date_expire'=>date('Y-m-d 23:59:59',$expire),
			'order_num'=>'',
			'order_product_idx'=>0
		);

		$record = array_merge($record_default, $record);

		if($record['mileage_reason_flag'] == 'admin') {
			$record['reg_admin_id'] = $_ShopInfo->id;
		}


		$rs = $this->act($record);
		return $rs;
	}

	/**
	 * 마일리지 구매 지불처리
	 * 상품주문시 지불처리 및 로그, 지불기록을 남긴다
	 *
	 * @return void
	 */
	public function pay($mileage,$order_num, $member_id='') {
		$tbl_order = $this->tbls['order'];
		$tbl_payment_etc = $this->tbls['order_payment_etc'];
		$tbl = $this->tbls['mileage'];
		
		if(!$member_id) {
			$order_row = $this->adodb->getRow("SELECT * FROM {$tbl_order} WHERE order_num='{$order_num}'");
			$member_id = $order_row['member_id'];
		}

		//보유마일리지 체크
		$remain_mileage = $this->sumUsable($member_id);
		if($remain_mileage < $mileage) return false;

		//마일리지 사용처리
		$used = $mileage;
		$used_idx = array();
		$mileage_arr = $this->adodb->getArray("SELECT * FROM {$tbl} WHERE mem_id='{$member_id}' AND mileage_remain>0 ORDER BY date_insert ASC");

		if(!is_array($mileage_arr)) {
			$this->log_file(array('order_num'=>$order_num,'sql'=>"SELECT * FROM {$tbl} WHERE mem_id='{$member_id}' AND mileage_remain>0 ORDER BY date_insert ASC",'file'=>__FILE__, 'line'=>__LINE__));
			return false;
		}
		$success = true;

		foreach($mileage_arr  as $mileage_row) {
			$mileage_idx = $mileage_row['idx'];
			if($used > 0) {
				
				if($mileage_row['mileage_remain']>=$used) {
					$mileage_remain = $mileage_row['mileage_remain'] - $used;
					$used = 0;
				}
				else {
					$mileage_remain = 0; 
					$used -= $mileage_row['mileage_remain'];
				}

				$used_idx[$mileage_idx] = array(
					'expire'=>$mileage_row['date_expire'],
					'price'=>$mileage_row['mileage_remain']-$mileage_remain
				);


				$rs = $this->adodb->Execute("UPDATE {$tbl} SET mileage_remain = '{$mileage_remain}' WHERE idx='{$mileage_idx}'");
				if(!$rs) {
					$success = false;
					$this->log_file(array('order_num'=>$order_num,'msg'=>"UPDATE {$tbl} SET mileage_remain = '{$mileage_remain}' WHERE idx='{$mileage_idx}'",'file'=>__FILE__,'line'=>__LINE__));
				}
			}
			else {
				break;
			}
		}


		if(!$success) return false;

		//주문서별 마일리지 지불기록
		foreach($used_idx as $mileage_idx => $used_row) {
			$record = array(
				'order_num'=>$order_num, 
				'etc_type'=>'mileage',
				'etc_idx'=>$mileage_idx,
				'etc_limit'=>$used_row['expire'], //만료일
				'etc_price'=>$used_row['price'], //사용금액
				'etc_price_origin'=>$used_row['price'], //사용금액
				'date_insert'=>NOW
			);
			$sql = sqlInsert($record, $tbl_payment_etc);
			$rs =$this->adodb->Execute($sql);
			if(!$rs) $success = false;
		}
		if(!$success) return false;



		//마일리지 사용기록
		$record = array(
			'mem_id'=>$member_id,
			'mileage'=>($mileage*-1),
			'mileage_remain'=>0,
			'mileage_reason'=>'주문결제시 사용',
			'mileage_reason_flag'=>'order',
			'date_expire'=>NOW,
			'order_num'=>$order_num
		);

		$rs = $this->act($record);

		if($rs) return true;
		else return false;
	}

	/**
	 * 마일리지 복원처리(주문취소/반품시)
	 *
	 * @param [type] $order_num 주문번호
	 * @param [type] $mileage 복원마일리지
	 * @return void
	 */
	public function restore($order_num, $mileage) {
		global $_ShopInfo;

		$tbl_order = $this->tbls['order']; //주문기본테이블
		$tbl_payment_etc = $this->tbls['order_payment_etc']; //마일리지 결제정보 테이블
		
		//지불마일리지 정보 - 복원가능 마일리지 처리
		$payment_rs = $this->adodb->Execute("SELECT * FROM {$tbl_payment_etc}  WHERE order_num='{$order_num}' AND etc_type='mileage' AND etc_limit >= NOW() ORDER BY etc_limit ASC"); //복원가능한 지불 마일리지 정보

		$restore_mileage = $mileage;
		$restore_list = array();
		while($row = $payment_rs->FetchRow()) { //지불데이터 업데이트
			if($restore_mileage > 0) {
				if($row['etc_price'] <= $restore_mileage) { //해당 로우 전체 복원가능
					$restore_rs = $this->adodb->Execute("UPDATE {$tbl_payment_etc} SET etc_price='0', restore_yn='Y' WHERE no='".$row['no']."'");
					if($restore_rs) {
						$restore_list[$row['etc_limit']]+=$row['etc_price'];
						$restore_mileage-=$row['etc_price'];
					}
				}
				else { //부분복원
					$etc_price = $row['etc_price']-$restore_mileage;
					$restore_rs = $this->adodb->Execute("UPDATE {$tbl_payment_etc} SET etc_price='{$etc_price}' WHERE no='".$row['no']."'");
					if($restore_rs) {
						$restore_list[$row['etc_limit']]+=$restore_mileage;
						$restore_mileage=0;
					}
				}
			}
			else break;
		}

		//복원불가 마일리지 처리 - 복원가능한 마일리지 처리후에 잔여 마일리지가 있는경우 유효성 업데이트
		if($restore_mileage > 0) {
			$payment_rs = $this->adodb->Execute("SELECT * FROM {$tbl_payment_etc}  WHERE order_num='{$order_num}' AND etc_type='mileage' AND etc_limit < NOW() ORDER BY etc_limit ASC"); //복원가능한 지불 마일리지 정보
			while($row = $payment_rs->FetchRow()) { //지불데이터 업데이트
				if($restore_mileage > 0) {
					if($row['etc_price'] <= $restore_mileage) { //해당 로우 전체 복원가능
						$restore_rs = $this->adodb->Execute("UPDATE {$tbl_payment_etc} SET etc_price='0', restore_yn='Y' WHERE no='".$row['no']."'");
						
					}
					else { //부분복원
						$etc_price = $row['etc_price']-$restore_mileage;
						$restore_rs = $this->adodb->Execute("UPDATE {$tbl_payment_etc} SET etc_price='{$etc_price}' WHERE no='".$row['no']."'");
						
					}
				}
				else break;
			}
		}


		
		$basic_info = $this->adodb->getRow("SELECT member_id FROM {$tbl_order} WHERE order_num='{$order_num}'"); //주문기본정보

		//복원마일리지 지급
		$success = true;
		foreach($restore_list as $etc_limit => $mileage) {
			$record = array(
				'mem_id'=>$basic_info['member_id'],
				'mileage'=>$mileage,
				'mileage_remain'=>$mileage,
				'mileage_reason'=>'주문취소로 인한 마일리지 복원',
				'mileage_reason_flag'=>'admin',
				'order_num'=>$order_num,
				'date_expire'=>$etc_limit
			);

			$rs = $this->act($record);
			if(!$rs) {
				$success = false;
				break;
			}
		}

		return $success;
	}


	/**
	 * 지급/차감시 필수 공통정보 세팅
	 *
	 * @param [type] $record
	 * @return void
	 */
	public function assistor($record) {
		global $_ShopInfo;

		//처리위치 업데이트
		if ($record['reg_place']) {
			$reg_place = $record['reg_place'];
		} else {
			if ($this->place == 'admin') {
				$reg_place = 'admin';
				$reg_admin_id = $_ShopInfo->id;
			} else {
				$reg_place = strtolower(VIEWPORT);
				$reg_admin_id = MEMID;
			}
		}

		$record['reg_admin_id'] = $reg_admin_id;
		$record['reg_place'] = $reg_place;
		$record['reg_ip'] = $_SERVER['REMOTE_ADDR'];
		$record['date_insert'] = NOW;
		$record['date_update'] = NOW;

		return $record;
	}


	/**
	 * 만료일 체크
	 *
	 * @return void
	 */
	public function expire() {
		$tbl = $this->tbls['mileage'];

		$sql = "SELECT * FROM {$tbl} WHERE date_expire <= '".NOW."' AND (mileage_valid != 'N' OR mileage_valid IS NULL) AND mileage_remain > 0";
		$rs = $this->adodb->Execute($sql);

		while($row = $rs->FetchRow()) {

			$expire_rs = $this->adodb->Execute("UPDATE {$tbl} SET mileage_valid='N' WHERE idx=".$row['idx']);  //회원포인트 업데이트
			//만료포인트 유효성 n로 업데이트
			//만료차감 등록
			if($expire_rs) {
				$record = array(
					'mem_id' => $row['mem_id'],
					'mileage' => $row['mileage'] * -1,
					'mileage_remain' => 0,
					'mileage_reason_flag' => 'expire',
					'mileage_reason' => '기간만료',
					'reg_place' => 'server',
					'date_expire' => NOW,
				);

				$this->act($record);
			}
		}
	}

	/**
	 * 마일리지 실행(지급/차감)
	 *
	 * @param [type] $record
	 * @return void
	 */
	public function act($record) {
		$tbl = $this->tbls['mileage'];
		$record = $this->assistor($record);
		$sql = sqlInsert($record, $tbl);

		$this->transBegin(); //트랜잭션시작
	
		$rs = $this->adodb->Execute($sql);
		if($rs) {
			//누적마일리지 동기화
			$mem_id = $record['mem_id'];
			$rs_member = $this->sync($mem_id);

			if($rs_member) {
				$this->transCommit(); //트랜잭션정상종료
				return true;
			}
			else {
				$this->transRollback(); //트랜잭션롤백
				return false;
			}
			
		}
	}

	/**
	 * 회원 보유 마일리지 정보 회원테이블 동기화
	 *
	 * @param [type] $mem_id
	 * @return void
	 */
	public function sync($mem_id) {
		$tbl = $this->tbls['mileage'];
		$tbl_member = $this->tbls['member'];

		$sum_mileage = $this->adodb->getOne("SELECT SUM(mileage) as sum FROM {$tbl} WHERE mem_id='{$mem_id}'");

		if($sum_mileage < 0) $sum_mileage = 0; //마이너스 마일리지 방지
		$rs = $this->adodb->Execute("UPDATE {$tbl_member} SET reserve={$sum_mileage} WHERE id='{$mem_id}'");  //회원마일리지 업데이트
		return $rs;
	}

	/**
	 * 마이페이지 마일리지 리스트
	 *
	 * @param string $mem_id
	 * @return array
	 */
	public function useMileage($mem_id,$limit,$offset,$where) {
		$tbl = $this->tbls['mileage'];

		$cnt = $this->adodb->getOne("SELECT COUNT(*) FROM  {$tbl} WHERE mem_id='{$mem_id}'");//전체개수

		$sql = "SELECT * FROM {$tbl} WHERE mem_id='{$mem_id}' {$where} ORDER BY date_insert DESC LIMIT {$limit} OFFSET {$offset} ";

		$rs = $this->adodb->getArray($sql);

		$total = $cnt;
		return array(
			'list'=>$rs,
			'total'=>$total
		);
	}

	/**
	 * 마일리지 로그 삭제
	 *
	 * @param [type] $where
	 * @return void
	 */
	public function delete($where) {
		if(!$where) return false;
		$tbl = $this->tbls['mileage'];
		$sql = "DELETE FROM {$tbl} WHERE {$where}";
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}
}
?>