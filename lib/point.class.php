<?php
/**
 * 포인트 class
 * @author  stickcandy81@nate.com (hjlee)
 * 
 */
class POINT extends Common{

	public function __construct() {

		parent::__construct();
		$this->cfg = $this->getConfig('point','section'); //포인트 설정값
	}

	public function getConfigPair($point_id='') {
		$tbl = $this->tbls['point_config'];
		$where = ($point_id)?"WHERE point_id='{$point_id}'":'';
		$sql = "SELECT point_id, point FROM {$tbl} {$where}";
		$rs = $this->adodb->getAssoc($sql);
		return $rs;
	}

	public function getConfigRow($point_id) {
		$tbl = $this->tbls['point_config'];
		$sql = "SELECT point_id, point, point_name FROM {$tbl} WHERE point_id='{$point_id}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	public function getConfigAll() {
		$tbl = $this->tbls['point_config'];
		$sql = "SELECT * FROM {$tbl} ORDER BY idx ASC";
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}

	public function getPaging($where, $limit, $page=1) {
		$tbl = $this->tbls['point'];

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

		$sum = $this->adodb->getOne("SELECT SUM(point) FROM {$tbl} WHERE {$where}");

		return array(
			'list'=>$list,
			'sum'=>$sum,
			'count'=>$count
		);
	}

	public function sumUsable($member_id) {
		$tbl = $this->tbls['point'];
		$sql = "SELECT SUM(point) AS point FROM  {$tbl} WHERE mem_id='{$member_id}'";
		$sum = $this->adodb->getOne($sql);
		return $sum;
	}


	/**
	 * 포인트 부여
	 *
	 * @param [type] $point_id
	 * @param string $mem_id
	 * @return void
	 */
	public function plus($point_id, $mem_id='') {
		if(!$mem_id) $mem_id = MEMID;
		$point_cfg  = $this->getConfigRow($point_id);
		if(!$point_cfg) return false;
	
		$point = $point_cfg['point'];
		if(!$point) return false;

		$expire = strtotime('+'.$this->cfg['expire_year'].' years');

		$record = array(
			'mem_id'=>$mem_id,
			'point'=>$point,
			'point_remain'=>$point,
			'point_reason'=>$point_cfg['point_name'],
			'point_reason_flag'=>$point_id,
			'date_expire'=>date('Y-m-d 23:59:59',$expire)
		);

		$rs = $this->act($record);
	}

	/**
	  * 포인트 지급
	  *
	  * @param [type] $member_id
	  * @param [type] $point
	  * @param [type] $reason_flag
	  * @param [type] $reason
	  * @param integer $valid_day
	  * @return void
	  */
	  public function give($record, $valid_day=0) {
		global $_ShopInfo;

		if(!$valid_day) {
			$valid_day = $this->cfg['expire_year']*365;
		}
		$expire = strtotime("+{$valid_day} days");

		
		
		$record_default = array(
			'mem_id'=>'',
			'point'=>0,
			'point_remain'=>0,
			'point_reason_flag'=>'',
			'point_reason'=>'',
			'point_valid'=>'Y',
			'date_expire'=>date('Y-m-d 23:59:59',$expire)
		);

		$record = array_merge($record_default, $record);

		if($record['point_reason_flag'] == 'admin') {
			$record['reg_admin_id'] = $_ShopInfo->id;
		}


		$rs = $this->act($record);
		return $rs;
	}


	/**
	 * 포인트 구매 지불처리
	 * 상품주문시 지불처리 및 로그, 지불기록을 남긴다
	 *
	 * @return void
	 */
	public function pay($point,$order_num) {

		$tbl_order = $this->tbls['order'];
		$tbl_payment_etc = $this->tbls['order_payment_etc'];
		$tbl = $this->tbls['point'];

		$order_sql = "SELECT * FROM {$tbl_order} WHERE order_num='{$order_num}'";
		$order_row = $this->adodb->getRow($order_sql);
		$member_id = $order_row['member_id'];

		//보유포인트 체크
		$remain_point = $this->sumUsable($member_id);
		if($remain_point < $point) return false;

		//포인트 사용처리
		$used = $point;
		$used_idx = array();
		$point_arr = $this->adodb->getArray("SELECT * FROM {$tbl} WHERE mem_id='{$member_id}' AND point_remain>0 ORDER BY date_insert ASC");
		$success = true;
		foreach($point_arr  as $point_row) {
			$point_idx = $point_row['idx'];
			if($used > 0) {
				
				if($point_row['point_remain']>=$used) {
					$point_remain = $point_row['point_remain'] - $used;
					$used = 0;
				}
				else {
					$point_remain = 0; 
					$used -= $point_row['point_remain'];
				}

				$used_idx[$point_idx] = array(
					'expire'=>$point_row['date_expire'],
					'price'=>$point_row['point_remain']-$point_remain
				);

				$rs = $this->adodb->Execute("UPDATE {$tbl} SET point_remain = '{$point_remain}' WHERE idx='{$point_idx}'");
				if(!$rs) $success = false;
			}
			else {
				break;
			}
		}


		if(!$success) return false;

		//주문서별 포인트 지불기록
		foreach($used_idx as $point_idx => $used_row) {
			$record = array(
				'order_num'=>$order_num, 
				'etc_type'=>'point',
				'etc_idx'=>$point_idx,
				'etc_limit'=>$used_row['expire'],
				'etc_price'=>$used_row['price'],
				'etc_price_origin'=>$used_row['price'],
				'date_insert'=>NOW
			);
			$sql = sqlInsert($record, $tbl_payment_etc);
			$rs =$this->adodb->Execute($sql);
			if(!$rs) $success = false;
		}
		if(!$success) return false;

		//포인트 사용기록
		$record = array(
			'mem_id'=>$order_row['member_id'],
			'point'=>($point*-1),
			'point_remain'=>0,
			'point_reason'=>'주문결제시 사용',
			'point_reason_flag'=>'order',
			'date_expire'=>NOW,
			'order_num'=>$order_num
		);

		$rs = $this->act($record);

		if($rs) return true;
		else return false;

	}

	/**
	 * 포인트 복원처리(주문취소/반품시)
	 *
	 * @param [type] $order_num 주문번호
	 * @param [type] $point 복원포인트
	 * @return void
	 */
	public function restore($order_num, $point) {
		global $_ShopInfo;

		$tbl_order = $this->tbls['order']; //주문기본테이블
		$tbl_payment_etc = $this->tbls['order_payment_etc']; //포인트 결제정보 테이블
		
		//지불포인트 정보
		$payment_rs = $this->adodb->Execute("SELECT * FROM {$tbl_payment_etc}  WHERE order_num='{$order_num}' AND etc_type='point' AND etc_limit > NOW() ORDER BY etc_limit ASC"); //복원가능한 지불 포인트 정보

		$restore_point = $point;
		$restore_list = array();
		while($row = $payment_rs->FetchRow()) { //지불데이터 업데이트
			if($restore_point > 0) {
				if($row['etc_price'] <= $restore_point) { //해당 로우 전체 복원가능
					$restore_rs = $this->adodb->Execute("UPDATE {$tbl_payment_etc} SET etc_price='0', restore_yn='Y' WHERE no='".$row['no']."'");
					if($restore_rs) {
						$restore_list[$row['etc_limit']]+=$row['etc_price'];
						$restore_point-=$row['etc_price'];
					}
				}
				else { //부분복원
					$etc_price = $row['etc_price']-$restore_point;
					$restore_rs = $this->adodb->Execute("UPDATE {$tbl_payment_etc} SET etc_price='{$etc_price}' WHERE no='".$row['no']."'");
					if($restore_rs) {
						$restore_list[$row['etc_limit']]+=$restore_point;
						$restore_point=0;
					}
				}
			}
			else break;
		}

		if($restore_point > 0) {
			$payment_rs = $this->adodb->Execute("SELECT * FROM {$tbl_payment_etc}  WHERE order_num='{$order_num}' AND etc_type='point' AND etc_limit < NOW() AND restore_yn='N' ORDER BY etc_limit ASC"); //복원가능한 지불 마일리지 정보
			while($row = $payment_rs->FetchRow()) { //지불데이터 업데이트
				if($restore_point > 0) {
					if($row['etc_price'] <= $restore_point) { //해당 로우 전체 복원가능
						$restore_rs = $this->adodb->Execute("UPDATE {$tbl_payment_etc} SET etc_price='0', restore_yn='Y' WHERE no='".$row['no']."'");
						
					}
					else { //부분복원
						$etc_price = $row['etc_price']-$restore_point;
						$restore_rs = $this->adodb->Execute("UPDATE {$tbl_payment_etc} SET etc_price='{$etc_price}' WHERE no='".$row['no']."'");
					}
				}
				else break;
			}
		}

		
		$basic_info = $this->adodb->getRow("SELECT member_id FROM {$tbl_order} WHERE order_num='{$order_num}'"); //주문기본정보

		//복원포인트 지급
		$success = true;
		foreach($restore_list as $etc_limit => $point) {
			$record = array(
				'mem_id'=>$basic_info['member_id'],
				'point'=>$point,
				'point_remain'=>$point,
				'point_reason'=>'주문취소로 인한 포인트 복원',
				'point_reason_flag'=>'admin',
				'date_expire'=>$etc_limit,
				'reg_admin_id'=>$_ShopInfo->id,
				'order_num'=>$order_num
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
	 * Undocumented function
	 *
	 * @param [type] $record
	 * @return void
	 */
	public function assistor($record){
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
		$tbl = $this->tbls['point'];
        //$expire = strtotime("+366 days");
        //$exdate = date('Y-m-d 23:59:59',$expire);
		$sql = "SELECT * FROM {$tbl} WHERE date_expire <= '".NOW."' AND (point_valid != 'N' OR point_valid IS NULL) AND point_remain > 0";
		$rs = $this->adodb->Execute($sql);

		while($row = $rs->FetchRow()) {

            $expire_rs = $this->adodb->Execute("UPDATE {$tbl} SET point_valid='N' WHERE idx=".$row['idx']);  //회원포인트 업데이트
			//만료포인트 유효성 n로 업데이트

			//만료차감 등록
            if($expire_rs) {
                $record = array(
                    'mem_id' => $row['mem_id'],
                    'point' => $row['point'] * -1,
                    'point_remain' => 0,
                    'point_reason_flag' => 'expire',
                    'point_reason' => '기간만료',
                    'reg_place' => 'server',
                    'date_expire' => NOW,
                );

                $this->act($record);
            }
		}
	}

	/**
	 * 포인트 act insert
	 * 포인트 합계를 회원테이블에 업데이트
	 *
	 * @param [type] $record
	 * @return void
	 */
	public function act($record) {
		$tbl = $this->tbls['point'];
		$record = $this->assistor($record);
		$sql = sqlInsert($record, $tbl);

		$this->transBegin(); //트랜잭션시작

		$rs = $this->adodb->Execute($sql);
		if($rs) {
			
			//누적포인트
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
	 * 회원 보유 포인트 정보 회원테이블 동기화
	 *
	 * @param [type] $mem_id
	 * @return void
	 */
	public function sync($mem_id) {
		$tbl = $this->tbls['point'];
		$tbl_member = $this->tbls['member'];

		$sum_point = $this->adodb->getOne("SELECT SUM(point) as sum FROM {$tbl} WHERE mem_id='{$mem_id}'");
		if($sum_point < 0) $sum_point = 0; //마이너스 포인트 방지
		
		$rs = $this->adodb->Execute("UPDATE {$tbl_member} SET act_point={$sum_point} WHERE id='{$mem_id}'");  //회원포인트 업데이트
		return $rs;
	}

	/**
	 * 마이페이지 포인트 리스트
	 *
	 * @param string $mem_id
	 * @return array
	 */
	public function usePoint($mem_id,$limit,$offset,$where) {
		$tbl = $this->tbls['point'];

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
		$tbl = $this->tbls['point'];
		$sql = "DELETE FROM {$tbl} WHERE {$where}";
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}
}
?>
