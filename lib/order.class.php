<?php
/**
 * 주문테이블
 */
class ORDER extends BASKET {
	var $_conts = array(
		'receiver_memo'=>array(
			'부재 시 경비실에 맡겨주세요.',
			'빠른 배송 부탁드립니다.',
			'배송 전 연락바랍니다.'
		)
	);


	public function __construct($place='front') {
		parent::__construct();
		$this->place = $place;
	}

	public function setPlace($palce) {
		$this->place = $place;
	}

	/**
	 * 주문상품 조건별 개수 추출
	 *
	 * @param string $where
	 * @return void
	 */
	public function countProduct($where) {
		$tbl_op = $this->tbls['order_product'];
		$sql = "SELECT COUNT(*) AS cnt FROM {$tbl_op} WHERE {$where}";
	
		$cnt = $this->adodb->getOne($sql);
		return $cnt;
	}

	/**
	 * 관리자 목록용
	 *
	 * @param [type] $record
	 * @return void
	 */
	public function adminSearch($search) {
		$where = '';
		if(is_array($search)) {
			foreach($search as $f=>$v) {
			
				if(in_array($f, array('sf','date_field','limit','sort'))) continue;
				if(is_array($v)) {
					if(empty($v) || $v[0] == 'all') continue;
				}
				else {
					if(!trim($v)|| $v=='all') continue;
				}
				
				switch($f) {
					case 'sv':
						$sf = $search['sf'];
						$sv = array_filter(explode("\n", $v));
						if(empty($sv)) continue;
						$tmp = array();
						foreach($sv as $vv) {
							$vv = trim($vv);
							$tmp[] = "{$sf} LIKE '%{$vv}%'";
						}

						$where.=' AND ('.implode(' OR ', $tmp).')';

						break;
					case 'pr_type': //상품유형(일반상품,임직원상품등..)
						$where.=" AND p.{$f} IN('".implode("','",$v)."')";

						break;
					case 'cs_type':
						$where.=" AND op.{$f}='{$v}'";
						break;
					case 'cs_flag':
						$where.=" AND op.{$f}='{$v}'";
						break;
					case 'step':
						$where.=" AND concat(op.order_status, op.cs_type, op.cs_status) IN('".implode("','",$v)."')";
						break;
					case 'date_s':
						$field = $search['date_field'];
						$where.=" AND {$field} >= '{$v} 00:00:00'";
						break;
					case 'date_e':
						$field = $search['date_field'];
						$where.=" AND {$field} <= '{$v} 23:59:59'";
						break;
					case 'buyer_viewport':
						$where.=" AND {$f} = '{$v}'";
						break;
					case 'pay_total_s':
						$where.=" AND ob.pay_total >= '{$v}'";
						break;
					case 'pay_total_e':
						$where.=" AND ob.pay_total <= '{$v}'";
						break;
					case 'pg_paymethod': //결제수단
						$where.=" AND ob.{$f} IN('".implode("','",$v)."')";
						break;
					case 'product_value':
						$where.=" AND p.".$search['product_field']." LIKE '%{$v}%'";
						break;
					case 'is_member':
						if($v == 'Y') $where.=" AND ob.member_id !=''";
						else $where.=" AND ob.guest_id !=''";
						break;
				}
			}
		}
		// echo $where;
		return $where;
	}

	/**
	 * 소유체크 조건절
	 *
	 * @param string $where_offset
	 * @return void
	*/
	final function _getWhere($where_offset='') {
		if($this->place == 'admin') {
			return $where_offset;
		}

		$member_id = MEMID;
		$guest_id = session_id(); //비회원용 세션ID
		if($member_id) { //회원로그인여부체크
			$where = "member_id='{$member_id}'";
		}
		else {
			$member_id = '';
			$where = "member_id='' AND guest_id='{$guest_id}'";
		}


		if($where_offset) $where.=' AND '.$where_offset;
		return $where;
	}

	public function checkAuth($order_info) {
		if(is_string($order_info)) {
			$order_info = $this->getBasicRow($order_info);
		}

		if(MEMID) {
			if($order_info['member_id']==MEMID) return true;
			else return false;
		}
		else {
			//세션체크
			if($_SESSION['GID'] && $order_info['guest_id'] == $_SESSION['GID']) return true;
			else return false;
		}
	}

	public function getBasicPaging($where, $limit, $page=1) {
		$tbl_ob = $this->tbls['order'];
		$tbl_op = $this->tbls['order_product'];

		$where = " op.order_status > 0 AND ".$where;

		$sql = "
			SELECT
				op.order_num,
				MAX(op.order_status) AS order_status,
				MAX(op.cs_type) AS cs_type,
				MAX(op.cs_status) AS cs_status,
				MAX(op.cs_flag) AS cs_flag,
				MAX(op.option_type) AS option_type,
				MAX(op.option_code) AS option_code,
				MAX(op.productcode) AS productcode,
				MAX(op.price_end) AS price_end,
				COUNT(*) AS cnt,
				MAX(ob.date_insert) AS date_insert
			FROM
				{$tbl_op} AS op
			LEFT JOIN {$tbl_ob} AS ob ON
				(ob.order_num = op.order_num)
			WHERE
				{$where}
			GROUP BY
				op.order_num,
				op.order_status, op.cs_type, op.cs_status,  op.productcode, op.option_type, op.option_code
		";

		$count = $this->adodb->getOne("SELECT COUNT(*) AS total FROM ({$sql}) x");

		if($page) {
			$offset = ($page-1)*$limit;
		}

		$sql .= "ORDER BY op.order_num DESC, op.option_code DESC OFFSET {$offset} LIMIT {$limit}";
		//echo $sql;
		$rs = $this->adodb->Execute($sql);
		
		$Product = new PRODUCT;

		$list = array();
		
		$num = $count-$offset;
		$parent_key = '';
		while($row = $rs->FetchRow()) {
			$row['oid'] = $this->Enctypt_AES128CBC($row['order_num']); //주문번호 암호화
			
			
			//상품정보
			if($row['option_type'] =='option') {
				$product = $Product->getRowSimple($row['productcode'], false);
				$option = $Product->getOptionRow($row['option_code'],'option_num, option_name');
			}
			else {
				$product = $Product->getRowSimple($row['option_code'], false);
				$option = array();
			}
			
			$row['product_info'] = $product;
			$row['option_info'] = $option;

			//상태값
			$status_info = $this->getStep($row);
			$row['status_info'] = $status_info;

			if($parent_key != $row['order_num']) {
				$parent_key = $row['order_num'];
				$basic_info = $this->adodb->getRow("SELECT idx, member_id, buyer_name, sum_end, pay_delivery, pay_pg, pay_total, pg_paymethod, date_insert FROM {$tbl_ob} WHERE order_num='{$parent_key}'");
				$basic_info['num'] = $num--; //순번

				//주문자정보
				$list[$parent_key] = $basic_info;
				$list[$parent_key]['count'] = 1;
				$list[$parent_key]['children'][] = $row;
			}
			else {
				$list[$parent_key]['count']++;
				$list[$parent_key]['children'][] = $row;
			}
			
			$num--;
		}
		
		// pre($list);
		return array(
			'list'=>$list,
			'count'=>$count
		);
	}

	public function getStep($row) {

		if(is_array($row)) {
			$step_code = $row['order_status'].$row['cs_type'].$row['cs_status'];
			if($row['cs_flag']) $cs_flag = $row['cs_flag'];
			
		}
		else {
			list($step_code, $cs_flag) = explode('_',$row);
		}

		$step_code = str_pad($step_code, '3','0');

		list($order_status, $cs_type, $cs_status) = str_split($step_code);

		$enable = array();
		$msg = '';

		$step_code = '_'.$step_code;

		switch($step_code) {
			case "_000":
				$msg = '주문대기';
				break;
			case '_100':
				$msg = '입금대기중';
				$enable = array('cancel'); //취소신청가능
				break;
			case '_1C1':
				$msg = '취소신청';
				$enable = array();
				break;
			case '_1C2':  //입금전 취소 -접수 (실제로 존재하지 않는 step)
				$msg = '취소접수';
				$enable = array();
				break;
			case '_1C3': //입금전 취소 - 처리중 (실제로 존재하지 않는 step)
				$msg = '취소처리중';
				$enable = array();
				break;
			case '_1C4': //입금전 취소 - 완료
				$msg = '취소완료';
				$enable = array();
				break;
			case '_200': //결제완료
				$msg = '결제완료';
				$enable = array('cancel');
				break;
			case '_2C1': //입금후 취소 - 신청
				$msg = '취소신청';
				break;
			case '_2C2': //입금후 취소 - 접수
				$msg = '취소접수';
				break;
			case '_2C3': //입금후 취소 - 처리중
				$msg = '취소처리중';
				break;
			case '_2C4': //입금후 취소 - 완료
				$msg = '취소완료';
				break;
			case '_2E0':
				$msg = '교환결제완료';
				break;
			case '_300':
				$msg = '배송준비중';
				$enable = array('cancel');
				break;
			case '_3R1':
				$msg = '반품신청';
				break;
			case '_3R2':
				$msg = '반품접수';
				break;
			case '_3R3':
				$msg = '반품처리중';
				break;
			case '_3R4':
				$msg = '반품완료';
				break;
			case '_3E0':
				$msg = '교환배송준비중';
				break;
			case '_3E2':
				$msg = '교환접수';
				break;
			case '_3E3':
				$msg = '교환처리중';
				break;
			case '_3E4':
				$msg = '교환완료';
				break;
			case '_400':
				$msg = '배송중';
				$enable = array('delivery');
				break;
			case '_4R1': //반품 - 신청
				$msg = '반품신청';
				break;
			case '_4R2': //반품 - 접수
				$msg = '반품접수';
				break;
			case '_4R3': //반품 - 처리중
				$msg = '반품처리중';
				break;
			case '_4R4': //반품 - 완료
				$msg = '반품완료';
				break;
			case '_4E0': //교환 - 배송중
				$msg = '교환배송중';
				break;
			case '_4E1': //교환 - 신청
				$msg = '교환신청';
				$enable = array('exchange_cancel'); //교환철회
				break;
			case '_4E2': //교환 - 접수
				$msg = '교환접수';
				break;
			case '_4E3': //교환 - 처리중
				$msg = '교환처리중';
				break;
			case '_4E4': //교환 - 승인
				$msg = '교환완료(승인)';
				break;
			case "_500":
				// echo $step_code;
				$msg = '배송완료';
				$enable = array('return','exchange','confirm');
				break;
			case '_5R1':
				$msg = '반품신청';
				$enable = array('return_cancel');//반품철회
				
				break;
			case '_5R2':
				$msg = '반품접수';
				break;
			case '_5R3':
				$msg = '반품처리중';
				break;
			case '_5R4':
				$msg = '반품완료';
				break;
			case '_5E0':
				$msg = '교환배송완료';
				$enable = array('confirm'); //교환배송완료
				break;
			case '_5E1':
				$msg = '교환신청';
				$enable = array('exchange_cancel');  //교환철회
				break;
			case '_5E2':
				$msg = '교환접수';//교환철회
				break;
			case '_5E3':
				$msg = '교환처리중';
				break;
			case '_5E4':
				$msg = '교환취소완료';
				break;
			case '_600':
				$msg = '구매확정';
				$enable = array('review');
				break;
			case '_6E0':
				$msg = '교환구매확정';
				$enable = array('review');
				break;
			default:
				$msg = '';
				break;
		}

		if($cs_flag) {
			switch($cs_flag) {
				case 'WD' :
					switch($cs_type) {
						case 'R':
							$msg = '반품철회';
							$msg_flag = '';
							break;
						case 'E':
							$msg = '교환철회';
							$msg_flag = '';
							break;
						default:
							$msg_flag = '철회';
							break;
					}

					break;
				case 'HD' :
					$msg_flag = '보류';
					break;
				case 'AG' :
					$msg_flag = '제품도착';
					break;
				case 'AP' :
					$msg_flag = '승인';
					break;
				case 'RR':
					$msg_flag = '환불접수';
					break;
				case 'RC':
					$msg_flag = '환불완료';
					break;
			}
		}
		else $msg_flag = '';


		$rs = array(
			'msg'=>$msg,
			'msg_flag'=>$msg_flag,
			'enable'=>$enable
		);
		

		return $rs;
	}

	/**
	 * 주문상품목록, 상품idx 기준
	 *
	 * @param string $idxs
	 * @return void
	 */
	public function getOrderProduct($idxs) {
		$tbl = $this->tbls['order_product'];
		$sql = "SELECT * FROM {$tbl} WHERE idx IN($idxs)";
		$rs = $this->adodb->Execute($sql);
		$total = $this->adodb->getOne("SELECT COUNT(*) AS cnt FROM ($sql) x");
		$Product = new PRODUCT;

		$deli_company = $this->getDeliveryCompanyPair();//택배사정보


		while($row = $rs->FetchRow()) {
			$row['num'] = $total--;
			if($row['option_type'] =='option') {
				$product = $Product->getRowSimple($row['productcode'], false);
				$option = $Product->getOptionRow($row['option_code'],'option_num, option_name');
			}
			else {
				$product = $Product->getRowSimple($row['option_code'], false);
				$option = array();
			}

			$row['product_info'] = $product;
			$row['option_info'] = $option;

			$status_info = $this->getStep($row);
			$row['status_info'] = $status_info;

			$row['delivery_company_name'] = $deli_company[$row['delivery_company']]['company_name'];
			$row['delivery_url'] = $deli_company[$row['delivery_company']]['deli_url'].$row['delivery_no'];

			$list[] = $row;
		}



		return $list;
	}

	/**
	 * 주문서 유효시간
	 *
	 * @return void
	 */
	public function validOrder() {
		if( (time()-$_SESSION['order_time']) > 60*30) {
			return false;
		}
		else return true;
	}


	public function getGroupNo($order_num_temp) {
		$tbl = $this->tbls['order_temp'];
		$sql = "SELECT order_num_temp, string_agg(DISTINCT basket_group_no, ',') AS group_no FROM {$tbl} where order_num_temp='{$order_num_temp}' GROUP BY order_num_temp";
		$row = $this->adodb->getRow($sql);
		return $row['group_no'];
	}

	/**
	 * 취소/환불/반품 제외한 주문유효 동기화
	 *
	 * @param [type] $order_num
	 * @return void
	 */
	public function syncBasic($order_num, $record = array()) {
		$sum = $this->adodb->getRow("SELECT 
				COALESCE(SUM(price_consumer),0) AS price_consumer, 
				COALESCE(SUM(price_sell),0) AS price_sell, 
				COALESCE(SUM(price_end),0) AS price_end, 
				COALESCE(SUM(coupon_discount),0) AS coupon_discount, 
				COALESCE(SUM(mileage_expect),0) AS mileage_expect 
			FROM tblorder_product 
			WHERE order_num='{$order_num}' AND (cs_type='0' OR (cs_type='R' AND cs_status!='4'))");

		if(!is_array($sum)) {
			$sum = array(
				'price_consumer'=>'0',
				'price_sell'=>'0',
				'price_end'=>'0',
				'coupon_discount'=>'0',
				'mileage_expect'=>'0'
			);
		}

		$record['sum_consumer'] = $sum['price_consumer'];
		$record['sum_end'] = $sum['price_end'];
		$record['sum_discount'] = $sum['price_consumer']-$sum['price_end'];
		$record['coupon_product_discount'] = $sum['coupon_discount'];
		$record['sum_mileage'] = $sum['mileage_expect'];

		$tbl_basic = $this->tbls['order'];
		$sql_basic = sqlUpdate($record,$tbl_basic,array('order_num'=>$order_num));
		$rs = $this->adodb->Execute($sql_basic);
		if($rs) {
			$sql = "UPDATE {$tbl_basic} SET pay_total=(sum_end+pay_delivery) WHERE order_num='{$order_num}'";
			$rs_sum = $this->adodb->Execute($sql);

			

			if($rs_sum) {
				$sql = "UPDATE {$tbl_basic} SET pay_pg=(pay_total-(use_mileage+use_point+coupon_basket_discount+coupon_delivery_discount+coupon_product_discount)) WHERE order_num='{$order_num}'";

				$this->adodb->Execute($sql);
				return true;
			}
			else return false;
		}
		else {
			return false;
		}
	}

	/**
	 * 주문정보 리턴
	 *
	 * @param [type] $order_num
	 * @return void
	 */
	public function getBasicRow($order_num, $field='*', $where_str='') {
		$tbl = $this->tbls['order'];
		// $where = $this->_getWhere("order_num ='{$order_num}'");
		$where = "order_num='{$order_num}'";
		if($where_str) $where.=" AND ".$where_str;
		$sql = "SELECT {$field} FROM {$tbl} WHERE $where";

		$row = $this->adodb->getRow($sql);
		if($row['pg_paymethod']) {
			$payment_info = $this->getPaymentRow($order_num);
			$row['payment_info'] = $this->setPayInfo($row['pg_paymethod'],$payment_info['res_info'],'all');
		}

		return $row;
	}

	public function getBasicList($where='', $limit='') {
		$tbl = $this->tbls['order'];
		$where = $this->_getWhere($where);
		$sql = "SELECT * FROM {$tbl} WHERE $where ORDER BY idx DESC";
	
		if($limit) {
			$sql .= " OFFSET 0 LIMIT {$limit}";
		}
		
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}


	/**
	 * 임시구매상품목록 리턴
	 *
	 * @param [type] $temp_num
	 * @return void
	 */
	public function getTempList($order_num_temp) {
		$tbl = $this->tbls['order_temp'];
		$sql = "SELECT * FROM {$tbl} WHERE order_num_temp='{$order_num_temp}'";
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}

	/**
	 * 구매상품총합
	 */
	public function getTempSum($order_num_temp) {
		$tbl = $this->tbls['order_temp'];
		$sql = "SELECT SUM(price_end) FROM {$tbl} WHERE order_num_temp='{$order_num_temp}'";
		$row = $this->adodb->getOne($sql);
		return $row;
	}

	public function getTempCount($order_num_temp) {
		$tbl = $this->tbls['order_temp'];
		$sql = "SELECT COUNT(*) FROM {$tbl} WHERE order_num_temp='{$order_num_temp}'";
		$row = $this->adodb->getOne($sql);
		return $row;
	}

	 /**
	  * 임시 구매정보 row 리턴
	  *
	  * @param [type] $temp_no
	  * @param string $where
	  * @return void
	  */
	public function getTempRow($temp_no, $where = '') {
		$tbl = $this->tbls['order_temp'];
		$sql = "SELECT * FROM {$tbl} WHERE no='{$temp_no}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}


	/**
	 * 주문상품목록, 주문번호 기준
	 *
	 * @param string $order_num
	 * @return void
	 */
	public function getProductPaging($order_num) {
		$tbl = $this->tbls['order_product'];
		$Product = new PRODUCT;
		$sql = "SELECT * FROM {$tbl} WHERE order_num='{$order_num}'";
		$rs = $this->adodb->Execute($sql);
		$list = array();
		while($row = $rs->FetchRow()) {
			$list[] = $row;
		}
		$count = count($list);
		$row = array();

		foreach($list as $v) {
			$k = $v['option_type'].'_'.$v['option_code'];

			if(array_key_exists($k, $row) === true) {
				$row[$k]['count']++;
				continue;
			}


			if($v['option_type'] == 'option') { //옵션
				$product_info = $Product->getRowSimple($v['productcode']);//상품정보
				$option_info = $Product->getOptionRow($v['option_code'],'option_name'); //옵션정보

				$children_info = array(
					'product_code'=>$product_info['prodcode'], //품번
					'product_name'=>$product_info['productname'], //상품명
					'product_thumbnail'=>$product_info['tinyimage'], //이미지
					'option_name'=>$option_info['option_name']
				);

			}
			else { //추가상품구매
				$product_info = $Product->getRowSimple($v['option_code']);
				$children_info = array(
					'product_code'=>$product_info['prodcode'], //품번
					'product_name'=>$product_info['productname'], //상품명
					'product_thumbnail'=>$product_info['tinyimage'], //이미지
					'option_name'=>$option_info['option_name']
				);
			}

			$v['step'] = $this->getStep($v);

			$v['count'] = 1;
			$v['detail'] = $children_info;

			$row[$k] = $v;

		}
		//pre($row);

		return array(
			'list'=>$row,
			'count'=>$count
		);
	}

	/**
	 * 주문상품정보
	 *
	 * @param [type] $where
	 * @return void
	 */
	public function getProductAll($where) {
		$tbl_op = $this->tbls['order_product'];
		$Product = new PRODUCT;
		$sql = "SELECT op.* FROM {$tbl_op} AS op  WHERE {$where}";
		$rs = $this->adodb->Execute($sql);
		$list = array();
	
		while($row = $rs->FetchRow()) {
			
			$row['step'] = $this->getStep($row);

			//상품정보
			if($row['option_type'] == 'option') { //옵션
				$product_info = $Product->getRowSimple($row['productcode']);//상품정보
				$option_info = $Product->getOptionRow($row['option_code'],'option_name'); //옵션정보

				$children_info = array(
					'product_code'=>$product_info['prodcode'], //품번
					'product_name'=>$product_info['productname'], //상품명
					'product_thumbnail'=>$product_info['tinyimage'], //이미지
					'option_name'=>$option_info['option_name']
				);

			}
			else { //추가상품구매
				$product_info = $Product->getRowSimple($row['option_code']);
				$children_info = array(
					'product_code'=>$product_info['prodcode'], //품번
					'product_name'=>$product_info['productname'], //상품명
					'product_thumbnail'=>$product_info['tinyimage'], //이미지
					'option_name'=>$option_info['option_name']
				);
			}

			$row['product_info'] = $children_info;
			
			$list[] = $row;
		}

		

		return $list;
	}

	/**
	 * 주문상품목록, 주문번호 기준
	 *
	 * @param string $order_num
	 * @return void
	 */
	public function getProductList($order_num, $where='') {
		$tbl = $this->tbls['order_product'];
		$Product = new PRODUCT;
		$sql = "SELECT * FROM {$tbl} WHERE order_num='{$order_num}'";
		if($where) $sql.=" AND ".$where;
		$rs = $this->adodb->Execute($sql);
		if(!$rs) {
			$this->log_file(array('msg'=>$sql,'file'=>__FILE__, 'line'=>__LINE__));

			return false;
		}
		$list = array();
	
		while($row = $rs->FetchRow()) {
			$list[] = $row;
		}
		$count = count($list);
		$row = array();

		foreach($list as $v) {
			$k = $v['option_type'].'_'.$v['option_code'];

			if(array_key_exists($k, $row) === true) {
				$row[$k]['count']++;
				continue;
			}


			if($v['option_type'] == 'option') { //옵션
				$product_info = $Product->getRowSimple($v['productcode']);//상품정보
				$option_info = $Product->getOptionRow($v['option_code'],'option_name'); //옵션정보

				$children_info = array(
					'product_code'=>$product_info['prodcode'], //품번
					'product_name'=>$product_info['productname'], //상품명
					'product_thumbnail'=>$product_info['tinyimage'], //이미지
					'option_name'=>$option_info['option_name']
				);

			}
			else { //추가상품구매
				$product_info = $Product->getRowSimple($v['option_code']);
				$children_info = array(
					'product_code'=>$product_info['prodcode'], //품번
					'product_name'=>$product_info['productname'], //상품명
					'product_thumbnail'=>$product_info['tinyimage'], //이미지
					'option_name'=>$option_info['option_name']
				);
			}

			$v['step'] = $this->getStep($v);

			$v['count'] = 1;
			$v['detail'] = $children_info;


			$row[$k] = $v;

		}
		//pre($row);

		return array(
			'list'=>$row,
			'count'=>$count
		);
	}

	/**
	 * 주문상품개별모록, 주문번호 기준
	 *
	 * @param string $order_num
	 * @return void
	 */
	public function getOrderProductList($order_num, $where='') {
		$tbl = $this->tbls['order_product'];
		if($where) $where=' AND '.$where;
		$sql = "SELECT * FROM {$tbl} WHERE order_num='{$order_num}' {$where} ORDER BY productcode ASC";
		$rs = $this->adodb->Execute($sql);

		$list = array();
		$Product = new PRODUCT;
		while($v = $rs->FetchRow()) {
			$product_info = $Product->getProductDetail($v['productcode'], 'productcode, productname, tinyimage, soldout, productcode, quantity, pr_type');
			$v['product_info'] = $product_info;
			$v['option_name'] = $Product->getOptionRow($v['option_code'],'option_name'); //옵션정보
			$v['step'] = $this->getStep($v);
			$list[] = $v;

		}
		return $list;
	}


	public function getProductGroup($where, $group_by='') {
		$tbl_op = $this->tbls['order_product'];

		$sql = "
			SELECT
				array_to_string(array_agg(idx::int),',') AS idxs,
				MAX(productcode) as productcode, 
				MAX(order_status) as order_status,
				MAX(cs_type) as cs_type,
				MAX(cs_status) as cs_status,
				MAX(cs_flag) as cs_flag,
				MAX(option_type) AS option_type,
				MAX(option_code) AS option_code,
				MAX(price_sell) AS price_sell,
				MAX(delivery_company) AS delivery_company,
				MAX(delivery_no) AS delivery_no,
				SUM(coupon_discount) AS coupon_discount,
				SUM(mileage_expect) AS mileage_expect,
				COUNT(*) AS cnt,
				MAX(cs_idx) AS cs_idx
			FROM
				{$tbl_op}
			WHERE
				{$where}
			GROUP BY
				order_status,
				cs_type,
				cs_status,
				option_type,
				option_code,
				order_num,
				delivery_no {$group_by}
			ORDER BY order_num DESC, option_type ASC, productcode ASC
			";
		//echo $sql;
		$rs = $this->adodb->Execute($sql);
		$total = $this->adodb->getOne("SELECT COUNT(*) AS cnt FROM ($sql) x");
		$Product = new PRODUCT;

		$deli_company = $this->getDeliveryCompanyPair();//택배사정보


		while($row = $rs->FetchRow()) {
			$row['num'] = $total--;
			if($row['option_type'] =='option') {
				$product = $Product->getRowSimple($row['productcode'], false);
				$option = $Product->getOptionRow($row['option_code'],'option_num, option_name, option_quantity, option_quantity_sales');
				//재고체크
				$option['stock'] = $option['option_quantity'] - $option['option_quantity_sales']; //관리자 임의 품절처리여부는 체크하지 않는다.
				
			}
			else {
				$product = $Product->getRowSimple($row['option_code'], false);
				$option = array(
					'stock'=>$product['quantity']
				);
			}

			$row['product_info'] = $product;
			$row['option_info'] = $option;

			$status_info = $this->getStep($row);
			$row['status_info'] = $status_info;

			$row['delivery_company_name'] = $deli_company[$row['delivery_company']]['company_name'];
			$row['delivery_url'] = $deli_company[$row['delivery_company']]['deli_url'].str_replace("-","",$row['delivery_no']);

			$list[] = $row;
		}

		return $list;

	}

	/**
	 * 결제정보
	 */
	public function getPaymentRow($order_num) {
		$tbl = $this->tbls['order_payment'];
		$sql = "SELECT * FROM {$tbl} WHERE order_num='{$order_num}'";
		$row = $this->adodb->getRow($sql);
		$row['res_info'] = unserialize($row['res_info']);
		return $row;
	}

	/**
	 * 주문결제기타수단
	 *
	 * @param string $benefit_type coupon|mileage|point
	 * @param int $benefit_no ci_no|
	 * @return array 결제수단
	 */
	public function getPaymentEtc($etc_type, $etc_no) {
		$tbl = $this->tbls['order_payment_etc'];
		$sql = "SELECT * FROM {$tbl} WHERE etc_type='{$etc_type}' AND etc_idx='{$etc_no}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	/**
	 * 결제수단별 정보
	 * kcp기준
	 */
	public function setPayInfo($paymethod, $res=array(), $return='quota') {
		switch($paymethod) {
			case 'card':
				$name = '카드결제';
				if($res['quota'] == '00') {
					$quota = '일시불';
				}
				else {
					if($res['noinf'] == 'N'){
						$quota = '일반할부 ';
						//일반할부
					}
					else {
						$quota = '무이자 ';
						//무이자
					}
					$quota.=$res['quota'].'개월';
				}
				
				break;
			case 'vcnt':
				$name = '가상계좌';
				$quota = $res['bankname'].' / '.$res['account'];
				break;
			case 'acnt':
				$name = '실시간계좌이체';
				break;
		}

		switch($return) {
			case 'quota':
			default:
				$rtn =  $quota;
				break;
			case 'all':
				$rtn = array(
					'name'=>$name,
					'quota'=>$quota
				);
				break;
		}

		return $rtn;
		
	}

	/**
	 * 배송지정보목록
	 *
	 * @return void
	 */
	public function getDestinationList($member_id) {
		$tbl = $this->tbls['destination'];
		$sql = "SELECT * FROM {$tbl} WHERE mem_id='{$member_id}' ORDER BY base_chk DESC";
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}

	public function getDestinationRow($no) {
		$tbl = $this->tbls['destination'];
		$sql = "SELECT * FROM {$tbl} WHERE no='{$no}'";
		
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	/**
	 * 결제수단 PG사별 코드 반환
	 *
	 * @param [type] $paymethod
	 * @return void
	 */
	public function getPaymentCode($paymethod) {
		switch(PG) {
			case 'NHNKCP':
			default:
				if(VIEWPORT == 'MOBILE') {
					$payment_arr = array(
						'card'=>'CARD', //카드결제
						'acnt'=>'BANK', //실시간계좌이체
						'vcnt'=>'VCNT' //가상계좌
					);
				}
				else {
					$payment_arr = array(
						'card'=>'100000000000', //카드결제
						'acnt'=>'010000000000', //실시간계좌이체
						'vcnt'=>'001000000000' //가상계좌
					);
				}
				
				break;
		}
		return $payment_arr[$paymethod];
	}


	public function getDeliveryCompanyRow($company_no) {
		$tbl = $this->tbls['delicompany'];
	}

	public function getDeliveryCompanyPair() {
		$tbl = $this->tbls['delicompany'];
		$rs = $this->adodb->getAssoc("SELECT code, company_name, deli_url FROM {$tbl}");
		return $rs;
	}

	/**
	 * 주문시 증정 사은품
	 *
	 * @param [type] $order_num
	 * @return void
	 */
	public function getGiftList($order_num, $where='') {
		$tbl = $this->tbls['order_gift'];
		$sql = "SELECT * FROM {$tbl} WHERE order_num='{$order_num}'";
		$rs = $this->adodb->Execute($sql);
		$list = array();
		while($row = $rs->FetchRow()) {
			$row['gift_info'] = unserialize($row['gift_info']);
			$giftcode = $row['gift_info']['giftcode'];
			$info = $this->adodb->getRow("SELECT * FROM ".$this->tbls['gift']." WHERE giftcode='{$giftcode}'");
			$row['quantity_remain'] = $info['quantity']-$info['quantity_sale']; //현재 잔여수량
			$list[] = $row;
		}

		return $list;
	}

	/**
	 * 주문상품
	 *
	 * @param [type] $idx
	 * @return void
	 */
	public function getProductRow($idx) {
		$tbl = $this->tbls['order_product'];

		$sql = "SELECT * FROM {$tbl}";
		if(is_numeric($idx)) {
			$sql .= " WHERE idx='{$idx}'";
		}
		else {
			$sql .= " WHERE {$idx}";
		}
		
		$row = $this->adodb->getRow($sql);
		return $row;
	}


	/**
	 * 주문번호기준 입금확인처리
	 * 최초 입금처리만 담당함
	 *
	 * @param string $order_num 주문번호
	 * @param string $log 로그메세지
	 * @return void
	 */
	public function setPaid($order_num, $log='') {
		$tbl = $this->tbls['order_product'];
		$sql = "SELECT idx, order_num FROM {$tbl} WHERE order_num='{$order_num}' AND order_status='1'";
		$rs = $this->adodb->Execute($sql);
		$success = true;
		while($row = $rs->FetchRow()) {
			$rs_status = $this->changeOrderStatus($row['idx'],'2',$log);
			if(!$rs_status) $success = false;
		}

		if($success) {
			$tbl_ob = $this->tbls['order'];
			$this->adodb->Execute("UPDATE {$tbl_ob} SET order_status='2' WHERE order_num='".$order_num."'");
		}

		return $success;
	}

	/**
	 * 주문상태변경처리
	 * 주문상태 변경, 주문상태별 처리일, 로그기록, 메일/SMS발송
	 * 
	 * @ string $product_idx 주문상품pk, 주문번호 기준으로 묶어서 보낼것.(문자 중복발송문제가 생김)
	 * @return void
	 */
	public function changeOrderStatus($product_idx, $order_status, $log='관리자 > 주문상세') {

		$tbl = $this->tbls['order_product'];

		if(is_array($product_idx)) {
			$idx_arr = $product_idx;
		}
		else {
			$idx_arr = explode(',',$product_idx);
		}
		
		if(!is_array($idx_arr)) return false;

		$Mileage = new MILEAGE;
		
		$success = true;
		$product_valid_arr = array(); //변경유효상품idx
		$this->transBegin();

		foreach($idx_arr as $idx) {

			//기존정보
			$row = $this->getProductRow($idx);

			if(!$order_num) $order_num = $row['order_num'];

			if($row['order_status']==$order_status) continue; //기존상태값과 동인한경우 제외

			$product_valid_arr[] = $idx; //중복처리를 제외한 유효 주문상품pk

			$record = array(
				'order_status'=>$order_status,
				'date_order_'.$order_status=>NOW
			);
			$sql = sqlUpdate($record, $tbl, array('idx'=>$idx));

			$rs = $this->adodb->Execute($sql);
			if($rs) {

				if($order_status < 4 && $row['order_status'] == '4') { //배송중에서 그 이하 단계로 변경되는경우 재고복원처리
					$stock_rs = $this->resetStock($idx); //재고복원
					if(!$stock_rs) {
						$this->error('재고복원이이 비정상적으로 종료되었습니다.');
						$success = false;
					}
				}

				if($order_status >= 4 && $row['order_status'] < 4) { //배송중 이하 단계에서 그 이상단계로 변경되는 경우 재고차감처리
					$stock_rs = $this->minusStock($idx); //재고차감
					if(!$stock_rs) {
						$this->error('재고차감이 비정상적으로 종료되었습니다.');
						$success = false;
					}
				}

				if($order_status == '3') {//배송준비중

				}
				else if($order_status == '4') { //배송중
					//송장번호 입력체크
					if(!$row['delivery_company'] || !$row['delivery_company']) {
						$success = false;
						$this->error('송장번호를 입력하세요.');
					}
				}
				else if($order_status == '6') { //구매확정

					//주문서 기본정보
					$basic_row = $this->getBasicRow($row['order_num']);

					if($basic_row['member_id']) { // 회원주문인경우에만 처리
						//마일리지 지급
						$record_mileage = array(
							'mem_id'=>$basic_row['member_id'],
							'mileage'=>$row['mileage_expect'],
							'mileage_remain'=>$row['mileage_expect'],
							'mileage_reason_flag'=>'order_confirm',
							'mileage_reason'=>'구매확정 마일리지 적립',
							'order_num'=>$row['order_num'],
							'order_product_idx'=>$idx
						);

						$rs_mileage = $Mileage->give($record_mileage);
						if(!$rs_mileage) {
							//echo 'mileage error';
							$success = false;
						}

						$rs_member = $this->syncMember($basic_row['member_id']);//회원 구매인경우 누적구매금액, 구매횟수 업데이트
						if(!$rs_member) {
							//echo 'member error';
							$success = false;
						}
					}
				}

				//로그처리
				$record_log = array(
					'order_num'=>$row['order_num'],
					'order_product_idx'=>$idx,
					'type'=>'order_status',
					'value'=>$order_status,
					'value_pre'=>$row['order_status'],
					'msg'=>$log
				);
				$this->_log($record_log);
			}
			else {
				$success = false;
			}

			if(!$success) break;
		}

		if($success) {

			$this->transCommit();
			//@TODO 변경상태별로 메일,sms등 처리
			switch($order_status) {
				case '1': //주문완료(입금대기)
				break;
				case '2': //결제완료(입금확인)
				break;
				case '3': //배송준비중
				break;
				case '4': //배송중
					//SMS 발송
					$sms = new SMS;
					$sms->send_sms("ORDER_003", $order_num, $product_valid_arr);

					//메일 발송
					$mail = new MAIL();
					$mail->send_mail("shipment", $order_num, $product_valid_arr);
				break;
				case '5': //배송완료
					//SMS 발송 //20181017 bshan 주문건별 idx 모아서 실행해야 문자 중복발송안되는데 자동 배송완료 처리시 idx별로 호출되어 보내져서 중복문자 여러건 발송되어 일단 제외
					/*$sms = new SMS;
					$sms->send_sms("ORDER_005", $order_num, $product_valid_arr);*/
				break;
				case '6': //구매확정
				break;
			}

			return true;
		}
		else {
			$this->transRollback();
			return false;
		}
	}


	/**
	 * 누적구매금액, 누적구매횟수(주문번호 기준) 업데이트
	 *
	 * @param string $member_id 회원아이디
	 * @return void
	 */

	public function syncMember($member_id) {

		$tbl_ob = $this->tbls['order'];
		$tbl_op = $this->tbls['order_product'];
		$tbl_member = $this->tbls['member'];

		//구매누적금액
		$sum_price = $this->adodb->getOne("SELECT COALESCE(SUM(op.price_end),0) AS price FROM {$tbl_op} AS op INNER JOIN {$tbl_ob} AS ob ON(ob.order_num = op.order_num) WHERE op.order_status='6' AND ob.member_id='{$member_id}'");

		//구매횟수
		$sum_count= $this->adodb->getOne("SELECT COALESCE(COUNT(distinct ob.order_num),0) AS cnt FROM {$tbl_op} as op INNER join {$tbl_ob} AS ob ON(ob.order_num = op.order_num) WHERE op.order_status='6' AND ob.member_id='{$member_id}'");

		$record =  array(
			'sum_buy_price'=>$sum_price,
			'sum_buy_count'=>$sum_count
		);
		

		$sql = sqlUpdate($record, $tbl_member, array('id'=>$member_id));
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

	//
	/**
	 * 상품재고차감처리
	 *
	 * @param integer $op_idx 주문상품 pk
	 * @return boolean
	 */
	public function minusStock($op_idx) {
		global $_ShopInfo;

		
		$tbl_log = $this->tbls['order_log_stock'];//주문재고로그
		$tbl_order_product =  $this->tbls['order_product']; //주문상품테이블
		$tbl_option = $this->tbls['product_option']; //상품옵션테이블
		
		//차감기록 유무체크(중복차감방지)
		$cnt = $this->adodb->getOne("SELECT SUM(cnt) FROM {$tbl_log} WHERE type='product' AND type_idx='{$op_idx}'");
		if($cnt>0) return true; //이미 차감된경우 차감하지 않는다.

		//주문상품정보
		$order_product = $this->adodb->getRow("SELECT order_num, productcode, option_type, option_code FROM {$tbl_order_product} WHERE idx='{$op_idx}'");
		$order_num = $order_product['order_num'];

		//재고체크 후 재고차감처리(옵션판매수량+1)
		if($order_product['option_type'] == 'option') { //옵션주문인경우

			//현재고체크
			$valid_stock = $this->adodb->getOne("SELECT (option_quantity-option_quantity_sales) AS stock FROM {$tbl_option} WHERE productcode='".$order_product['productcode']."' AND option_num='".$order_product['option_code']."'");
			if($valid_stock < 1) { //재고가 없는경우 처리하지 않는다
				$this->error('재고부족으로 처리할 수 없습니다.');
				return false;
			}

			$sql = "UPDATE {$tbl_option} SET option_quantity_sales=option_quantity_sales+1 WHERE productcode='".$order_product['productcode']."' AND option_num='".$order_product['option_code']."'";
		}
		else { //추가구매상품옵션인경우
			$sql = "UPDATE {$tbl_option} SET option_quantity_sales=option_quantity_sales+1 WHERE productcode='".$order_product['option_code']."'";
		}
		
		$rs = $this->adodb->Execute($sql);
		if(!$rs) return;

		//재고차감 로그
		$record = array(
			'order_num'=>$order_num,
			'type'=>'product',
			'type_idx'=>$op_idx,
			'cnt'=>1,
			'reg_id'=>$_ShopInfo->id,
			'reg_ip'=>$_SERVER['REMOTE_ADDR'],
			'date_insert'=>NOW
		);

		$sql = sqlInsert($record, $tbl_log);
		$rs = $this->adodb->Execute($sql);
		
		return $rs;
	}

	/**
	 * 재고복원처리
	 *
	 * @param integer $op_idx 주문상품 pk
	 * @return boolean
	 */
	public function resetStock($op_idx) {
		global $_ShopInfo;
		

		$tbl_log = $this->tbls['order_log_stock'];//주문재고로그
		$tbl_order_product =  $this->tbls['order_product']; //주문상품테이블
		$tbl_option = $this->tbls['product_option']; //상품옵션테이블
		
		//차감기록 유무체크(중복복원방지)
		$cnt = $this->adodb->getOne("SELECT COALESCE(SUM(cnt),0) FROM {$tbl_log} WHERE type='product' AND type_idx='{$op_idx}'");
		if($cnt<=0) return false; //차감된 재고수량이 없는경우 복원하지 않는다.

		//주문상품정보
		$order_product = $this->adodb->getRow("SELECT order_num, productcode, option_type, option_code FROM {$tbl_order_product} WHERE idx='{$op_idx}'");
		$order_num = $order_product['order_num'];


		//재고복원처리(옵션판매수량-1)
		if($order_product['option_type'] == 'option') { //옵션주문인경우
			$sql = "UPDATE {$tbl_option} SET option_quantity_sales=option_quantity_sales-1 WHERE productcode='".$order_product['productcode']."' AND option_num='".$order_product['option_code']."'";
		}
		else { //추가구매상품옵션인경우
			$sql = "UPDATE {$tbl_option} SET option_quantity_sales=option_quantity_sales-1 WHERE productcode='".$order_product['option_code']."'";
		}
		$rs = $this->adodb->Execute($sql);
		if(!$rs) return;

		$record = array(
			'order_num'=>$order_num,
			'type'=>'product',
			'type_idx'=>$op_idx,
			'cnt'=>-1,
			'reg_id'=>$_ShopInfo->id,
			'reg_ip'=>$_SERVER['REMOTE_ADDR'],
			'date_insert'=>NOW
		);

		$sql = sqlInsert($record, $tbl_log);
		$rs = $this->adodb->Execute($sql);
		return $rs;
	
	}


	public function updateProduct($record, $where) {
		$tbl = $this->tbls['order_product'];
		$sql = sqlUpdate($record, $tbl, $where);
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}
	

	/**
	 * 주문취소
	 * 입금대기(주문완료)상태인경우 주문취소처리
	 *
	 * @param mixed $order_num 주문번호
	 * @return boolean
	 */
	public function cancel($order_num, $cancel_idx='') {
		global $_ShopInfo;
		if(!is_array($order_num)) {
			$order_num = explode(',',$order_num);
		}

		$order_num = array_filter(array_unique($order_num));
		$tbl_ope = $this->tbls['order_payment_etc'];
		$success = true;
		foreach($order_num as $orn) {

			$this->transBegin(); //트랜잭션 시작

			$basic = $this->getBasicRow($orn);

			//사용쿠폰복원
			$payment_etc_rs = $this->adodb->Execute("SELECT * FROM {$tbl_ope} WHERE order_num='{$orn}' AND etc_type='coupon' AND etc_limit >= NOW()");
			$refund_coupon = array();
			$Coupon = new COUPON;
			while($row = $payment_etc_rs->FetchRow()) {
				//$refund_coupon =
				$Coupon->restore($row['etc_idx']); //쿠폰복원처리
			}

			//포인트 복원처리
			$refund_point = $basic['use_point'];
			if($refund_point> 0) {
				$Point = new POINT;
				$rs_point = $Point->restore($orn,$refund_point);
				if(!$rs_point) {
					if($success) $error_msg = "포인트 복원실패";
					$success = false;
				}
			}
			else $refund_point = 0;
			

			//마일리지 복원처리
			$refund_mileage = $basic['use_mileage'];
			if($refund_mileage > 0) {
				$Mileage = new MILEAGE;
				$rs_mileage = $Mileage->restore($orn,$refund_mileage);
				if(!$rs_mileage){
					if($success) $error_msg = "마일리지 복원실패";
					$success = false;
				}
			}
			else $refund_mileage = 0;

			//환불정보입력
			//$bank_info = serialize($_POST['bank']);
			$pay_method = $basic['pg_paymethod'];
			$record_refund = array(
				'order_num'=>$orn,
				'price_total'=>$basic['pay_total'], //환불총액
				'price_product'=>$basic['sum_end'], //환불상품금액
				'price_delivery'=>($basic['pay_delivery']-$basic['coupon_delivery_discount']), //환불배송비
				'refund_method'=>$basic['pg_paymethod'], //환불수단
				'refund_mileage'=>$refund_mileage, //마일리지 환불액
				'refund_point'=>$refund_point, //포인트 환불액
				'refund_cash'=>0, //현금 환불액
				'refund_'.$pay_method=>$basic['pay_pg'], //결제수단별 환불액
				//'bank_info'=>$bank_info,//환불계좌
				'date_rr'=>NOW, //환불신청일
				'date_rc'=>NOW, //환불완료일
				'refund_status'=>'1' //환불완료
			);
	
			$tbl_or = $this->tbls['order_refund'];
			$sql_refund = sqlInsert($record_refund, $tbl_or);
			$rs = $this->adodb->Execute($sql_refund);
			if(!$rs) {
				if($success) $error_msg = "환불정보등록 실패";
				$success = false;
			}
	
			$refund_idx = $this->adodb->insert_id(); //환불테이블 PK
			
			//취소정보 업데이트
			if($this->place == 'admin') {
				$reg_place = 'admin';
				$reg_id = $_ShopInfo->id;
			}
			else if($this->place == 'server') {
				$reg_place = 'server';
			}
			else {
				$reg_place = strtolower(VIEWPORT);
				$reg_id = MEMID;
			}

			$record = array(
				'order_num'=>$orn,
				'refund_idx'=>$refund_idx, //환불정보pk
				'valid_yn'=>'Y',
				'reg_place'=>$reg_place,
				'reg_id'=>$reg_id,
				'reg_ip'=>$_SERVER['REMOTE_ADDR'],
				'date_insert'=>NOW,
				'date_status_4'=>NOW,
				'date_update'=>NOW
			);

			$tbl_oc = $this->tbls['order_cancel'];
			if($cancel_idx) {
				$sql = sqlUpdate($record, $tbl_oc, array('idx'=>$cancel_idx));
				$rs_cancel = $this->adodb->Execute($sql);
			}
			else {
				$sql = sqlInsert($record, $tbl_oc);
				$rs_cancel = $this->adodb->Execute($sql);
				$cancel_idx = $this->adodb->insert_id();
			}
			

			
			if(!$rs_cancel) {
				if($success) $error_msg = "취소정보 업데이트실패";
				$success = false;
			}

			//상품별취소처리
			$tbl_op = $this->tbls['order_product'];
			$order_status = $basic['order_status'];
			$product_rs = $this->adodb->Execute("SELECT * FROM {$tbl_op} WHERE order_num='{$orn}' AND cs_type='0' AND order_status='{$order_status}'");
		

			$cs_flag = ($basic['order_status'] == '2')?'RC':'0'; //결제완료인경우 환불완료처리
			while($row = $product_rs->FetchRow()) {
				$record = array(
					'mileage_expect'=>0, //지급예정마일리지
					'cs_type'=>'C',
					'cs_status'=>'4', //즉시취소완료
					'cs_flag'=>$cs_flag, 
					'cs_idx'=>$cancel_idx, //취소데이터pk
					'coupon_issue_no'=>'',
					'coupon_discount'=>'0'
				);

				$sql_product = sqlUpdate($record, $tbl_op, array('idx'=>$row['idx']));
				$rs_product = $this->adodb->Execute($sql_product);
				if(!$rs_product) {
					if($success) $error_msg = "상품별 취소처리 실패";
					$success = false;
				}
				else {
					if($this->place == 'admin') {
						$log_msg = '관리자 > 주문목록';
					}
					else {
						$log_msg = '사용자';
					}
					$record_log = array(
						'order_num'=>$orn,
						'order_product_idx'=>$row['idx'],
						'type'=>'cs_status',
						'value'=>$row['order_status'].'C4',
						'value_pre'=>$row['order_status'].$row['cs_type'].$row['cs_status'],
						'msg'=>$log_msg
					);
					$this->_log($record_log);
				}
			}

			$basic_record = array(
				'sum_consumer'=>'0', //정상가합계
				'sum_end'=>'0', //상품최종판매가 합계
				'sum_discount'=>'0', //상품할인액합계(정상가-판매가 + 기간할인액)
				'sum_mileage'=>'0', //적립마일리지 합계
				'use_point'=>'0', //사용 포인트
				'use_mileage'=>'0', //사용 마일리지
				'pay_delivery'=>'0', //배송비
				'pay_total'=>'0', //결제액
				'coupon_basket'=>'',//장바구니쿠폰사용번호
				'coupon_delivery'=>'',//무료배송쿠폰사용번호
				'coupon_product_discount'=>'0', //상품쿠폰할인합계
				'coupon_basket_discount'=>'0', //장바구니쿠폰할인합계
				'coupon_delivery_discount'=>'0', //장바구니쿠폰할인합계
				'pay_pg'=>'0' //pg사 결제액
			);

			$tbl_basic = $this->tbls['order'];
			$sql_basic = sqlUpdate($basic_record,$tbl_basic,array('order_num'=>$orn));
			$rs = $this->adodb->Execute($sql_basic);
			if(!$rs) {
				if($success) $error_msg = "주문서 정보 업데이트 실패";
				$success = false;
			}
			
			if($success) {
				$this->transCommit();
				return true;
			}
			else {
				$this->transRollback();
				$this->error_msg = $error_msg;
				return false;
			}
		}
	}

	public function getReturnRow($return_idx, $field='*') {
		$tbl = $this->tbls['order_return'];
		$sql = "SELECT {$field} FROM {$tbl} WHERE idx='{$return_idx}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	/**
	 * 교환정보
	 *
	 * @param [type] $return_idx
	 * @param string $field
	 * @return void
	 *
	 */
	public function getExchangeInfo($op_idx) {
		$tbl = $this->tbls['order_exchange'];
		$tbl_product = $this->tbls['order_exchange_product'];

		$sql = "SELECT e.*, ep.reason, ep.reason_etc, ep.exchange_option_code, ep.date_status_1, ep.date_status_4 FROM {$tbl_product} AS ep LEFT JOIN {$tbl} AS e ON(ep.exchange_idx=e.idx) WHERE ep.order_product_idx='{$op_idx}'";
		
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	/**
	 * 교환상품목록
	 *
	 * @param [type] $cs_idx
	 * @return void
	 */
	public function getExchangeProduct($cs_idx) {
		$tbl_p = $this->tbls['product'];
		$tbl_op = $this->tbls['order_product'];
		$tbl_ep = $this->tbls['order_exchange_product'];
		$sql = "SELECT ep.*, p.productname, p.tinyimage, op.option_type, op.option_code FROM  {$tbl_ep} AS ep LEFT JOIN {$tbl_op} AS op ON(ep.order_product_idx=op.idx) LEFT JOIN {$tbl_p} AS p ON(op.productcode = p.productcode) WHERE ep.exchange_idx='{$cs_idx}'";
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}

	/**
	 * 반품정보
	 *
	 * @param [type] $return_idx
	 * @param string $field
	 * @return void
	 */
	public function getReturnInfo($op_idx) {
		$tbl = $this->tbls['order_return'];
		$tbl_product = $this->tbls['order_return_product'];

		$sql = "SELECT r.*, rp.reason, rp.reason_etc FROM {$tbl_product} AS rp LEFT JOIN {$tbl} AS r ON(rp.return_idx=r.idx) WHERE rp.order_product_idx='{$op_idx}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}




	public function getCancelRow($cancel_idx, $field='*') {
		$tbl = $this->tbls['order_cancel'];
		$sql = "SELECT {$field} FROM {$tbl} WHERE idx='{$cancel_idx}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}


	/**
	 * 환불금액계산
	 *
	 * @return void
	 */
	public function calcRefund($order_num, $refund_idx) {
		
		$refund_idx_arr = explode(',',$refund_idx);

		foreach($refund_idx_arr as $idx) {
			$refund_info = $this->getRefundRow($idx, 'order_num, order_product_idx');
			
		}
	}

	public function getRefundRow($refund_idx, $field='*') {
		$tbl = $this->tbls['order_refund'];
		$sql = "SELECT * FROM {$tbl} WHERE idx='{$refund_idx}'";
		$row = $this->adodb->getRow($sql);
		$row['bank_info'] = unserialize($row['bank_info']);
		return $row;
	}

	/**
	 * 주문서별 메모등록
	 *
	 * @param [type] $order_num
	 * @param [type] $record
	 * @return void
	 */
	public function saveMemo($order_num, $record) {
		global $_ShopInfo;
		$tbl = $this->tbls['order_memo'];

		$row = $this->adodb->getRow("SELECT * FROM {$tbl} WHERE order_num='{$order_num}'");

		$record['reg_id'] = $_ShopInfo->id;
		$record['reg_ip'] = $_SERVER['REMOTE_ADDR'];
		$record['date_update'] = NOW;

		if($row) {
			$sql = sqlUpdate($record, $tbl, array('order_num'=>$order_num));
		}
		else {
			$record['date_insert'] = NOW;
			$sql = sqlInsert($record, $tbl);
		}


		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

	public function getMemoRow($order_num) {
		$tbl = $this->tbls['order_memo'];
		$sql = "SELECT * FROM {$tbl} WHERE order_num='{$order_num}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}



	/**
	 * 관리자용로그
	 *
	 * @param [type] $order_num
	 * @param [type] $status_type
	 * @param [type] $status_value
	 * @return void
	 */
	public function log_admin($order_num, $status_type, $status_value, $order_product_idx='', $msg=''){
		global $_ShopInfo;
		$record = array(
			'order_num'=>$order_num,
			'status_type'=>$status_type,
			'status_value'=>$status_value,
			'reg_type'=>'admin',
			'reg_id'=>$_ShopInfo['id'],
		);
		//$this->_log($record);
	}

	//주문로그
	public function log_order($record) {
		
		if($record['order_product_idx'] == 'all') {
			$tbl = $this->tbls['order_product'];
			$order_num = $record['order_num'];
			$product_list = $this->adodb->getArray("SELECT * FROM {$tbl} WHERE order_num='{$order_num}'");
			
		}
		else {
			$product_list = $this->adodb->getArray("SELECT * FROM {$tbl} WHERE idx='".$record['order_product_idx']."'");
		}

		foreach($product_list as $row) {
			if($row['order_status'] == $record['value']) continue;
			if(array_key_exists('value_pre',$record)===false) $record['value_pre'] = $row['order_status']; //이전상태
			$record['order_product_idx'] = $row['idx'];
			
			$this->_log($record);
		}
	}

	//로그저장
	public function _log($record) {
		global $_ShopInfo;
		if($this->place == 'admin') {
			$reg_place = 'admin';
			$reg_id = $_ShopInfo->id;
		}
		else {
			$reg_place = strtolower(VIEWPORT);
			$reg_id = MEMID;
		}
		
		$record_default = array(
			'reg_place'=>$this->place,
			'reg_id'=>$reg_id,
			'reg_ip'=>$_SERVER['REMOTE_ADDR'],
			'date_insert'=>NOW
		);

		$record = array_merge($record_default, $record);


		$sql = sqlInsert($record,$this->tbls['order_log']);
		// echo $sql;
		$this->adodb->Execute($sql);
	}

	
	/**
	* 관리자용 상품로그 리스트
	*
	* @param [type] $order_product_idx
	* @return void
	*/
	public function log_product($order_product_idx){
		global $_CONFIG;
		$tbl = $this->tbls['order_log'];
		$sql ="
			SELECT 
				MAX(value) AS value,
				MAX(value_pre) AS value_pre,
				MAX(reg_place) AS reg_place,
				MAX(reg_id) AS reg_id,
				MAX(reg_ip) AS reg_ip,
				MAX(type) as type,
				MAX(msg) AS msg,
				MAX(date_insert) AS date_insert,
				count(*)
			FROM 
				{$tbl} 
			WHERE 
				order_product_idx in ({$order_product_idx}) 
			GROUP BY 
				date_insert 
			ORDER BY 
				date_insert DESC";

		$rs = $this->adodb->Execute($sql);
		$list = array();
		while($row = $rs->FetchRow()) {
			switch($row['type']) {
				case 'cs_status':
					$row['type_txt'] = 'CS';
					$step_pre = $this->getStep($row['value_pre']);
					$step_pre_txt = $step_pre['msg'];
					if($step_pre['msg_flag']) $step_pre_txt.='('.$step_pre['msg_flag'].')';

					$step = $this->getStep($row['value']);
					$step_txt = $step['msg'];
					if($step['msg_flag']) $step_txt.='('.$step['msg_flag'].')';
					
					if($step_pre_txt) $status_txt = $step_pre_txt.' → '.$step_txt;
					else $status_txt = $step_txt;
					$row['status_txt'] = $status_txt;
				break;
				case 'order_status':
				default:
					$row['type_txt'] = '주문';
					$row['status_txt'] = $_CONFIG['order_status'][$row['value_pre']].' → '.$_CONFIG['order_status'][$row['value']];
				break;
			}
			$list[] = $row;
		}
		return $list;
	}

	/**
	 * 주문서 자동취소처리
	 * - 입금일 지난 가상계좌 자동취소처리
	 *
	 * @return void
	 */
	public function auto_cancel() {
		$tbl_payment = $this->tbls['order_payment'];
		$tbl_product = $this->tbls['order_product'];
		$tbl_cancel = $this->tbls['order_cancel'];
		$sql = "SELECT MAX(opd.order_num) AS order_num FROM {$tbl_payment} AS opm LEFT JOIN {$tbl_product} AS opd ON(opm.order_num = opd.order_num) WHERE opm.pay_method='vcnt' AND date_vcnt IS NOT NULL and date_vcnt < NOW() AND opd.order_status='1' AND opd.cs_type!='C' GROUP BY opd.order_num";
	
		$rs = $this->adodb->Execute($sql);
		while($row = $rs->FetchRow()) {
				
			$order_num = $row['order_num']; //주문번호
			
			//취소테이블 저장
			$this->place = 'server';
			$record = array(
				'order_num'=>$order_num,
				'reason'=>'자동취소',
				'memo'=>'가상계좌 입금기한 만료',
				'date_insert'=>NOW
			);
			$sql = sqlInsert($record, $tbl_cancel);
			$this->adodb->Execute($sql);
			$cancel_idx = $this->adodb->insert_id();

			if($cancel_idx) {
				$rs_cancel = $this->cancel($order_num,$cancel_idx);
				if(!$rs_cancel) {
					$this->log_file(array('FILE'=>__FILE__, 'msg'=>'가상계좌 자동취소 실패 #1','order_num'=>$order_num));
				}
			}
			else {
				$this->log_file(array('FILE'=>__FILE__, 'msg'=>'가상계좌 자동취소 실패 #2','order_num'=>$order_num));
			}
			
		}
	}

	/**
	 * 주문서 자동배송완료
	 * - 주문정책 > 자동배송완료 설정에 따라 처리
	 *
	 * @return void
	 */
	public function auto_status_5() {
		
		$tbl_product = $this->tbls['order_product'];
		$cfg_order = $this->getConfig('order','section'); //주문관련 기능설정

		$success = true;
		if($cfg_order['auto_5'] == 'Y') { //자동상태변경 설정시에만 사용
			$sql = "SELECT max(order_num) AS order_num, array_to_string(array_agg(idx::int),',') AS idxs FROM {$tbl_product} WHERE order_status='4' AND cs_type='0' AND DATE_PART('day', NOW() - date_order_4) >= ".$cfg_order['auto_5_day'];
		
			$rs = $this->adodb->Execute($sql);
			$this->setPlace('server');

			while($row = $rs->FetchRow()) {
				if(!$row['idxs']) continue;
				$rs_change = $this->changeOrderStatus($row['idxs'], '5', $log='자동배송완료처리');
				if(!$rs_change) $success = false;
			}

		}
		else return false;
	}



	/**
	 * 사은품 배송완료 처리
	 *
	 * @param integer $idx 주문사은품 px
	 * @param string $order_num 주문번호
	 * @return boolean
	 */
	public function giftStock($idx, $order_num,$status) {
		global $_ShopInfo;

		$tbl_log = $this->tbls['order_log_stock'];//주문재고로그
		$tbl_order_gift = $this->tbls['order_gift'];//주문사은품테이블
		$tbl_gift = $this->tbls['gift']; //사은품테이블

		//차감기록 유무체크(중복복원방지)
		$cnt = $this->adodb->getOne("SELECT COALESCE(SUM(cnt),0) FROM {$tbl_log} WHERE type='gift' AND type_idx='{$idx}'");

		if($cnt > 0) return false; //차감된 재고수량이 없는경우 복원하지 않는다.

		//주문상품정보
		$order_gift = $this->adodb->getRow("SELECT * FROM {$tbl_order_gift} WHERE idx='{$idx}'");
		$gift_idx = $order_gift['gift_idx'];

		$sql_order_gift = "UPDATE {$tbl_order_gift} SET status = '{$status}', date_update='".NOW."' WHERE idx='{$idx}'";
		$rs_order_gift = $this->adodb->Execute($sql_order_gift);

		$sql_gift = "UPDATE {$tbl_gift} SET quantity_sale = quantity_sale+1  WHERE idx='{$gift_idx}'";
		$rs_gift = $this->adodb->Execute($sql_gift);

		if(!$rs_order_gift && !$rs_gift) return;

		$record = array(
			'order_num'=>$order_num,
			'type'=>'gift',
			'type_idx'=>$idx,
			'cnt'=>1,
			'reg_id'=>$_ShopInfo->id,
			'reg_ip'=>$_SERVER['REMOTE_ADDR'],
			'date_insert'=>NOW
		);

		$sql = sqlInsert($record, $tbl_log);
		$rs = $this->adodb->Execute($sql);

		return $rs;

	}

	/**
	 * 사은품 배송대기 처리
	 *
	 * @param integer $idx 주문사은품 px
	 * @param string $order_num 주문번호
	 * @return boolean
	 */
	public function giftMinusStock($idx, $order_num,$status) {
		global $_ShopInfo;

		$tbl_log = $this->tbls['order_log_stock'];//주문재고로그
		$tbl_order_gift = $this->tbls['order_gift'];//주문사은품테이블
		$tbl_gift = $this->tbls['gift']; //사은품테이블
		//차감기록 유무체크(중복복원방지)
		$cnt = $this->adodb->getOne("SELECT COALESCE(SUM(cnt),0) FROM {$tbl_log} WHERE type='gift' AND type_idx='{$idx}'");

		if($cnt <= 0) return false; //차감된 재고수량이 없는경우 복원하지 않는다.

		//주문상품정보
		$order_gift = $this->adodb->getRow("SELECT * FROM {$tbl_order_gift} WHERE idx='{$idx}'");
		$gift_idx = $order_gift['gift_idx'];

		$sql_order_gift = "UPDATE {$tbl_order_gift} SET status = '{$status}', date_update='".NOW."' WHERE idx='{$idx}'";
		$rs_order_gift = $this->adodb->Execute($sql_order_gift);

		$sql_gift = "UPDATE {$tbl_gift} SET quantity_sale = quantity_sale-1  WHERE idx='{$gift_idx}'";
		$rs_gift = $this->adodb->Execute($sql_gift);

		if(!$rs_order_gift && !$rs_gift) return;

		$record = array(
			'order_num'=>$order_num,
			'type'=>'gift',
			'type_idx'=>$idx,
			'cnt'=>-1,
			'reg_id'=>$_ShopInfo->id,
			'reg_ip'=>$_SERVER['REMOTE_ADDR'],
			'date_insert'=>NOW
		);

		$sql = sqlInsert($record, $tbl_log);
		$rs = $this->adodb->Execute($sql);

		return $rs;

	}

    /**
     * 주문서 자동구매확정
     * - 주문정책 > 자동구매확정 14일
     *
     * @return void
     */
    public function auto_status_6()
    {
        $tbl_product = $this->tbls['order_product'];

        $success = true;
        $sql = "SELECT max(order_num) AS order_num, array_to_string(array_agg(idx::int),',') AS idxs FROM {$tbl_product} WHERE order_status='5' AND cs_type='0' AND DATE_PART('day', NOW() - date_order_5) >= 14";

        $rs = $this->adodb->Execute($sql);
        $this->setPlace('server');

        while ($row = $rs->FetchRow()) {
            if (!$row['idxs']) continue;
            $rs_change = $this->changeOrderStatus($row['idxs'], '6', $log = '자동구매확정');
            if (!$rs_change) $success = false;
        }
    }



    /**
     * 통계 스크립트용 주문상품 함수
     * @param  string $where [description]
     * @return array         [description]
     */
    public function getOrderProductForStats($order_num) {
        $tbl = $this->tbls['order_product'];

        $sql = "SELECT productcode, option_type, price_end, option_code FROM {$tbl} WHERE order_num = '$order_num' ";

        $Product = new PRODUCT();
        $rs = $this->adodb->Execute($sql);
        $list = array();

        while($row = $rs->FetchRow()) {
            if($row['option_type'] == 'product') {
                $option_product = $Product->getRowSimple($row['option_code']); //getRowSimple 내부에서 품절프로세스 처리
                $row['productname'] = addslashes($option_product['productname']);
                $row['productcode'] = $row['option_code'];
            }else {
                $option_product = $Product->getRowSimple($row['productcode']);
                $row['productname'] = addslashes($option_product['productname']);
            }
            $category_name = $Product->getCategoryName($row['productcode']);
            $row['category']=$category_name;
            $row['qty']=1;
            /*foreach ($link as $key=>$val) {
                $row['category']=array_pop(array_column($val, 'name')); //제일 마지막 카테고리 이름 가져오기
            }*/
            if(isset($list[$row['productcode']])) {
                $list[$row['productcode']]['qty'] += $row['qty'];
                $list[$row['productcode']]['price_end'] += $row['price_end'];
            }else{
                $list[$row['productcode']] = $row;
            }
        }

        return $list;

    }


	/**
	 * 지역별 배송비 조회 함수
	 * @param  string $zipcode [description]
	 * @return int deli_price         [description]
	 */
	public function getLocalDeliveryFee($zipcode)
	{
		$tbl = $this->tbls['area_deli'];
		$row = $this->adodb->getRow("SELECT * FROM {$tbl} WHERE '{$zipcode}' BETWEEN st_zipcode AND en_zipcode ORDER BY deli_price DESC");
		if ($row) {
			return $row['deli_price'];
		}
		else {
			return 0;
		}
	}

}


?>