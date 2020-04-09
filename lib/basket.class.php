<?php
/**
 * 장바구니 class
 * @author  stickcandy81@nate.com (hjlee)
 */
class Basket extends Common{
	public function __construct() {

		parent::__construct();
		$this->tbl = $this->tbls['basket']; //메인테이블
	}

	public function changeTbl($tbl_id) {
		$this->tbl = $this->tbls[$tbl_id];
	}

	/**
	 * 로그인상태에 따른 조건절 리턴
	 * @param  $where 조건(string query)
	 * @return [type] [description]
	 */
	protected function _getWhere($where_offset='') {
		global $_ShopInfo;
		$guest_id = session_id(); //비회원용 세션ID
		$member_id = $_ShopInfo->getMemid();
		if($member_id) { //회원로그인여부체크
			$where = "member_id='{$member_id}'";
		}
		else {
			$member_id = '';
			$where = "member_id='' AND guest_id='{$guest_id}'";
		}

		//바로구매 제외
		$where.=" AND direct_yn='N'"; 

		if($where_offset) $where.=' AND '.$where_offset;
		return $where;
	}

	public function getBuyerID() {
		if(MEMID) return MEMID;
		else return session_id();
	}

	/**
	 * 장바구니 상품 유효성체크
	 * 설정테이블 참조(tblconfig)
	 * - 가격0인상품제거
	 * - 품절상품제거
	 * - 장바구니 재고수량 조정(재고 및 상품별 최대구매수량기준)
	 * 
	 * @param string $type
	 * @return [type] [description]
	 */
	public function validator($type='normal') {
		global $_ShopInfo;

		//$config = $this->getConfig('basket_config','section');

		$where = $this->_getWhere();
		$tbl = $this->tbls['basket'];
	
		$Product = new PRODUCT;


		$sql = "SELECT productcode FROM tblbasket group by productcode;";
		$product_arr = $this->adodb->getArray($sql);

		if(is_array($product_arr)) {
			foreach($product_arr as $row) {
				$productcode = $row[productcode];

				//루트상품이 판매중지인경우 제거
				$product_info  = $Product->getRowSimple($productcode);
				if(!$product_info['visible']) {
					$this->adodb->Execute("DELETE FROM {$tbl} WHERE productcode='".$productcode."'"); //해당상품그룹 모두삭제
				}
			}
		}

		$sql = "SELECT * FROM tblbasket WHERE {$where}";
		$basket = $this->adodb->getAssoc($sql);
		if(!is_array($basket)) return false;

		$valid = array();
		foreach($basket as $idx=>$row) {
			//pre($row);
			if($row['option_type'] == 'option') { //옵션상품
				$option_info = $Product->getOptionRow($row['option_code']);
				//옵션이 숨김상태인경우 제거
				if($option_info['option_use'] == 'N') {
					
					$this->adodb->Execute("DELETE FROM {$tbl} WHERE no='{$basket_no}'"); //옵션삭제
					continue;
				}

				//최대구매수량 계산(재고 및 최대구매설정)
				$product_info  = $Product->getRowSimple($row['productcode']);
				if($product_info['max_quantity']>-1) { //최대구매수량 제한이 있는경우
					$max = ($option_info['option_quantity']>$product_info['max_quantity'])?$product_info['max_quantity']:$option_info['option_quantity'];
				}
				else $max = $option_info['option_quantity'];

				
				//장바구니수량이 재고 OR 최대구매수량 보다 큰경우 재설정
				if($row['qty'] > $max) { //옵션수량과 비교
					$this->adodb->Execute("UPDATE {$tbl} SET qty='".$max."' WHERE no='".$idx."'");
				}

				$valid[] = $row['group_no'];
			}
			else { //추가구매상품
				$add_product_info  = $Product->getRowSimple($row['option_code'], false); //추가구매상품정보
				
				if(!$add_product_info['visible']) { //추가구매상품이 미노출상태(승인대기,승인거부 or 판매중지)인경우 삭제
					$this->adodb->Execute("DELETE FROM {$tbl} WHERE option_code='".$row['option_code']."'");
				}

				//장바구니 수량이 재고보다 큰 경우 재설정
				if($row['qty'] > $add_product_info['quantity']) {
					$this->adodb->Execute("UPDATE {$tbl} SET qty='".$add_product_info['quantity']."' WHERE no='{$basket_no}'");
				}
			}
		}

		//선택옵션이 없는상품 제거(추가구매상품만 존재하는경우)
		$sql = "DELETE FROM {$tbl} WHERE {$where} AND group_no NOT IN (".implode(',',$valid).")";
		$this->adodb->Execute($sql);
	}

	/**
	 * 장바구니 및 상품구매 가능수량체크
	 * 상품정보에 최대구매수량이 설정된경우(제한없음(-1)이 아닌경우)
	 *
	 * @param [type] $productcode
	 * @return void
	 */
	public function validator_buy($productcode, $mode='all') {
		$tbl_product = $this->tbls['product'];
		$tbl_basket = $this->tbls['basket'];
		$tbl_op = $this->tbls['order_product'];
		$tbl_ob = $this->tbls['order'];

		$product_info = $this->adodb->getRow("SELECT max_quantity, pr_type, productname FROM {$tbl_product} WHERE productcode='{$productcode}'"); //상품정보
		$max_quantity = $product_info['max_quantity'];
		if($max_quantity < 0) {
			$rtn = array(
				'productname'=>$product_info['productname'],
				'remain'=>9999999,
				'max'=>$max_quantity
			);
		}
		else {
			if($mode !='bought') {
				if($product_info['pr_type'] == '4') { //추가구매상품
					$sum_basket = $this->adodb->getOne("SELECT COALESCE(SUM(qty),0) AS total FROM {$tbl_basket} WHERE option_code='{$productcode}' AND member_id='".MEMID."' AND option_type='product' AND direct_yn='N'"); //장바구니 담긴 수량
				}
				else {
					$sum_basket = $this->adodb->getOne("SELECT COALESCE(SUM(qty),0) AS total FROM {$tbl_basket} WHERE productcode='{$productcode}' AND member_id='".MEMID."' AND option_type='option' AND direct_yn='N'"); //장바구니 담긴 수량
				}
	
			}
	
			if($mode !='basket') {
				if($product_info['pr_type'] == '4') { //추가구매상품
					$sum_bought = $this->adodb->getOne("SELECT COALESCE(COUNT(*),0) AS cnt FROM {$tbl_op} AS op LEFT JOIN {$tbl_ob} AS ob ON(op.order_num=ob.order_num) WHERE ob.member_id='".MEMID."' AND op.option_code='{$productcode}' AND op.option_type='product' AND op.order_status>0 AND concat(op.cs_type, op.cs_status)!='R4' AND concat(op.cs_type, op.cs_status)!='C4'"); //기존구매수량
				}
				else {
					$sum_bought = $this->adodb->getOne("SELECT COALESCE(COUNT(*),0) AS cnt FROM {$tbl_op} AS op LEFT JOIN {$tbl_ob} AS ob ON(op.order_num=ob.order_num) WHERE ob.member_id='".MEMID."' AND op.productcode='{$productcode}' AND op.option_type='option' AND op.order_status>0 AND concat(op.cs_type, op.cs_status)!='R4' AND concat(op.cs_type, op.cs_status)!='C4'"); //기존구매수량
				}
				
			}

			switch($mode){
				case 'basket':
				$valid_count = $max_quantity - $sum_basket;
					break;
				case 'bought':
					$valid_count = $max_quantity - $sum_bought;
					break;
				case 'all':
				default:
					$valid_count = $max_quantity - ($sum_basket+$sum_bought);
					break;
			}

			$rtn = array(
				'productname'=>$product_info['productname'],
				'remain'=>($valid_count>0)?$valid_count:0,
				'max'=>$max_quantity
			);
		}

		

		
		

		return $rtn;
	}

	/**
	 * 장바구니목록
	 * @param  string $where [description]
	 * @return array         [description]
	 */
	public function getBasket($pr_type='1', $where='', $checked_no='all') {
		global $_ShopInfo;
		$where = $this->_getWhere($where);


		$tbl = $this->tbls['basket'];
		$checked_no_arr = explode(',',$checked_no); //선택된상품no 배열
		
		$sql = "SELECT * FROM {$tbl} WHERE {$where} ORDER BY group_no DESC, option_type ASC, no ASC";

		$Product = new PRODUCT();
		$rs = $this->adodb->Execute($sql);
		$list = array();
		$list_children = array();
		
		$pre_group_no = 0;
		$idx=0;

		$price_consumer = $price_sale = $price_mileage =  0;

		//마일리지
		$Mileage = new MILEAGE;
		$mileage_rate = $Mileage->getRate($_ShopInfo->getMemgroup());

		$free_deli_price_yn = true;
		$free_deli_price = 0;
		$deli_field = 'deli, deli_select, deli_price';

		$option_list = array();
		while($row = $rs->FetchRow()) {
			$product_deli_type = $Product->getRowSimple($row['productcode'],false, $deli_field);
			if($product_deli_type['deli']==1 && $free_deli_price_yn){
				$free_deli_price_yn = true;
			}else{
				$free_deli_price_yn = false;
			}

			if($row['option_type'] == 'product') {
				$option_product = $Product->getRowSimple($row['option_code']); //getRowSimple 내부에서 품절프로세스 처리
				$row['option_info'] = $option_product;
			}
			else {
				$option_info = $Product->getOptionRow($row['option_code']);

				//품절여부
				if($option_info['option_soldout'] == 'Y') $option_info['status'] = 'soldout'; //강제품절처리
				else {
					if($option_info['option_quantity'] < 1 ) $option_info['status'] = 'soldout_temp'; //재고0이하인경우 일시품절처리
					else $option_info['status'] = 'normal';
				}
				$row['option_info'] = $option_info;
			}

			if(!array_key_exists($row['group_no'], $option_list)) {
				$option_list[$row['group_no']] = $Product->getRowSimple($row['productcode']);
				
				
			}
			if($option_list[$row['group_no']]['soldout'] == 'Y' && $row['option_type'] == 'option') {
				$row['option_info']['status'] = 'soldout';
			}
			$option_list[$row['group_no']]['children'][] = $row;
		}


		if(!is_array($option_list)) return false;

		
		foreach($option_list as $group_no => &$row) {
			if($checked_no == 'all') $checked = 'Y'; //초기로드시 전체선택
			else {
				if(in_array($group_no, $checked_no_arr)) $checked = 'Y';
				else $checked = 'N';
			}

		
			$valid = 0;
			$cnt_option = $cnt_product = 0;
			foreach($row['children'] as $k => &$child) {
				if($child['option_info']['status'] == 'normal' && $child['option_type'] == 'option') $valid++; //유효구매체크(옵션선택, 품절/일시품절아닌경우)
				if($child['option_type'] == 'option') {
					$child['option_info']['endprice'] = $row['endprice']; //옵션최종판매가격
					$child['option_info']['consumerprice'] = $row['consumerprice']; //옵션정상가격
					$child['option_info']['endprice_dc_rate'] = $row['endprice_dc_rate']; //옵션최종판매할인율

					$min_quantity = $row['min_quantity']; //최소구매수량
					$quantity = $child['option_info']['option_quantity']; //옵션의 현재고
					$max_quantity = ($row['max_quantity']<0 ||  $row['max_quantity'] > $quantity)?$quantity:$row['max_quantity']; //최대구매수량

					$cnt_option++;
				}
				else {
					$min_quantity = $child['option_info']['min_quantity']; //최소구매수량
					$quantity = $child['option_info']['quantity']; //추가구매상품의 현재고
					$max_quantity = ($child['option_info']['max_quantity']<0 ||  $child['option_info']['max_quantity'] > $quantity)?$quantity:$child['option_info']['max_quantity']; //최대구매수량
					$cnt_product++;
				}

				

				$child['stock']['min'] = $min_quantity;
				$child['stock']['max'] = $max_quantity;


				$child['option_info']['sumprice'] = $child['option_info']['consumerprice']*$child['qty'];
				$child['option_info']['mileage'] = $Mileage->calcMileage($child['option_info']['endprice']*$child['qty'],$mileage_rate);

				$row['checked'] = $checked;
				if($checked == 'N') continue;
				$price_mileage+=$child['option_info']['mileage']; //마일리지누적
				$price_consumer += $child['option_info']['sumprice']; //상품금액누적
				$price_sale += ($child['option_info']['consumerprice']-$child['option_info']['endprice'])*$child['qty']; //할인금액누적
			}
			$option_list[$group_no]['valid'] = $valid;
			$option_list[$group_no]['count'] = array(
				'option'=>$cnt_option,
				'product'=>$cnt_product
			); 
		}

		//배송비계산
		$price_buy = $price_consumer-$price_sale;

		$deli = $Product->getDeilvery(); //배송비설정
		$free_min = ($pr_type == '3')?$deli['deli_miniprice_staff']:$deli['deli_miniprice']; //임직원상품인경우 임직원배송비
		if($free_deli_price_yn){
			$price_delivery = $free_deli_price;
		}else{
			$price_delivery = ($price_buy < $free_min)?$deli['deli_basefee']:0;
		}
		$price_total = $price_buy+$price_delivery; //최종주문금액


		return array(
			'list'=>$option_list,
			'total'=>array(
				'price_consumer'=>$price_consumer, //총상품금액(정상가 합계)
				'price_end'=>$price_buy, //총상품금액(할인가 합계)
				'price_sale'=>$price_sale, //총할인금액
				'price_delivery'=>$price_delivery, //배송비
				'price_total'=>$price_total, //최종가
				'price_mileage'=>$price_mileage //마일리지
			)
		);

	}

	/**
	 * 장바구니 one row 반환
	 * @param  string $where [description]
	 * @return [type]        [description]
	 */
	public function getRow($where='') {
		$where = $this->_getWhere($where);
		$sql = "SELECT * FROM ".$this->tbl." WHERE {$where}";
		
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	/**
	 * 그룹기준 장바구니 목록가져오기
	 *
	 * @param [type] $group_no
	 * @return void
	 */
	public function getListByGroup($group_no) {
		$tbl = $this->tbls['basket'];

		$sql = "SELECT * FROM {$tbl} ";
	
		if(is_array($group_no)) {
			$sql.="WHERE group_no IN (".implode(',',$group_no).")";
		}
		else {
			$sql.="WHERE group_no='{$group_no}'";
		}

		$rs = $this->adodb->getArray($sql);
		return $rs;
	}

	

	/**
	 * 장바구니 개수리턴(배송유형별, total)
	 * @param  [type] $where 조건
	 * @return int        [description]
	 */
	public function getCount($where='') {
		$tbl = $this->tbls['basket'];
		$where = $this->_getWhere($where);
		$sql = "SELECT group_no, COUNT(*) FROM {$tbl} WHERE {$where} GROUP BY group_no";
		$cnt = $this->adodb->getAssoc($sql);
		if(is_array($cnt)) {
			$cnt = count($cnt);
		}
		else $cnt = 0;
		return $cnt;
	}

	/**
	 * 장바구니 업데이트
	 * @param  array $record 변경데이터(name=>value)
	 * @param  array $where 조건데이터(name=>value, glue=AND)
	 * @return boolean         [description]
	 */
	public function update($record, $where) {
		$sql = sqlUpdate($record, $this->tbl, $where);
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

	/**
	 * 장바구니 기존선택옵션 수량 증가
	 * @param [type] $qty [description]
	 * @param [type] $idx [description]
	 */
	public function addQty($qty, $idx) {
		$tbl = $this->tbls['basket'];
		$sql = "UPDATE {$tbl} SET qty=qty+{$qty} WHERE idx='{$idx}'";
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

	/**
	 * 장바구니 개별 삭제
	 * @param  [type] $basket_no [description]
	 * @return [type]            [description]
	 */
	public function deleteBasket($no, $type='option') {
		$column = ($type == 'group')?'group_no':'no';
		$tbl = $this->tbls['basket'];
		if(is_array($no)) {
			$sql= "DELETE FROM ".$this->tbl." WHERE {$column} IN (".implode(',',$no).")";
		}
		else {
			$sql= "DELETE FROM ".$this->tbl." WHERE {$column}='{$no}'";
		}
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

	/**
	 * 장바구니비우기
	 * TODO
	 *
	 * @return [type] [description]
	 */
	public function truncateBasket($where='') {
		$tbl = $this->tbls['basket'];
		$where = $this->_getWhere($where);
		$sql = "DELETE FROM {$tbl} WHERE {$where}";
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

	/**
	 * 장바구니 구매완료처리
	 *
	 * @param [type] $where
	 * @return void
	 */
	public function buyBasket($order_num) {
		$tbl = $this->tbls['basket'];
		$sql = "UPDATE {$tbl} SET buy_yn='Y', buy_date=NOW() WHERE order_num='{$order_num}'";
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

	/**
	 * 비회원장바구니 데이터 로그인후 동기화 처리
	 * @return void
	 */
	public function sync_login($member_id = '') {
		global $_ShopInfo;

		$guest_id = session_id(); //비회원용 세션ID
		if($member_id == '') $member_id = $_ShopInfo->getMemid();

		//장바구니 동기화
		$sql = "UPDATE ".$this->tbl." SET member_id='{$member_id}' WHERE guest_id='{$guest_id}'";
		$this->adodb->Execute($sql);

		//바로구매 장바구니 동기화
		$sql = "UPDATE ".$this->tbls['basket_bridge']." SET member_id='{$member_id}' WHERE guest_id='{$guest_id}'";
		$this->adodb->Execute($sql);
	}

	/*
	배송지 정보 가져오기
	*/
	public function GetDeleveryList($member_id='') {
		if($member_id == '') return false;

		$sql ="SELECT receiver_name,max(receiver_tel1) as receiver_tel1,receiver_tel2,receiver_addr,max(regdt) as regdt FROM tblorderinfo WHERE id = '".$member_id."' group by receiver_name,receiver_tel2,receiver_addr ORDER BY regdt DESC limit 5";
		$rs = $this->adodb->Execute($sql);

		// 기본 배송지 변수 설정
		$dn_info['selected']['dn_name'] = '';
		$dn_info['selected']['dn_mobile'] = '';
		$dn_info['selected']['dn_post_code'] = '';
		$dn_info['selected']['dn_post_zonecode'] = '';
		$dn_info['selected']['dn_addr1'] = '';
		$dn_info['selected']['dn_addr2'] = '';
		$dn_info['selected']['reg_date'] = '';
		$i = 1;
		while($list = $rs->FetchRow()){
			$temp = GetOrderinfoAddrParsing($list['receiver_addr']);
			$dn_row['no'] = $i;
			$dn_row['get_name'] = $list['receiver_name'];
			$dn_row['mobile'] = $list['receiver_tel2'];
			$dn_row['phone'] = $list['receiver_tel1'];
			$dn_row['reg_date'] = date("Y-m-d",strtotime($list['regdt']));
			$dn_row['postcode_new'] = $temp['receiver_post'];
			$dn_row['postcode'] = $temp['receiver_post'];
			$dn_row['addr1'] = $temp['receiver_addr1'];
			$dn_row['addr2'] = $temp['receiver_addr2'];
			$dn_info['list'][] = $dn_row;
			if($i == 1) {
				$dn_info['selected']['dn_name']					= $list['receiver_name'];
				$dn_info['selected']['dn_mobile']				= $list['receiver_tel2'];
				$dn_info['selected']['phone'] = $list['receiver_tel1'];
				$dn_info['selected']['dn_post_code']			= $temp['receiver_post'];
				$dn_info['selected']['dn_post_zonecode']	= $temp['receiver_post'];
				$dn_info['selected']['dn_addr1']					= $temp['receiver_addr1'];
				$dn_info['selected']['dn_addr2']					= $temp['receiver_addr2'];
			}
			$i++;
		}

		$sql ="SELECT home_addr,home_post,name,mobile,same_paymethod FROM tblmember WHERE id = '".$member_id."'";
		$rs = $this->adodb->Execute($sql);//06108
		$list = $rs->FetchRow();
		if(count($list) > 0 and $list['addr'] != '↑=↑' and $list['home_post'] != '') {
			$list['addr'] = explode('↑=↑',$list['home_addr']);
			$dn_info['selected']['dn_name']					= $list['name'];
			$dn_info['selected']['dn_mobile']				= $list['mobile'];
			$dn_info['selected']['dn_post_code']			= $list['home_post'];
			$dn_info['selected']['dn_post_zonecode']	= $list['home_post'];
			$dn_info['selected']['dn_addr1']					= $list['addr'][0];
			$dn_info['selected']['dn_addr2']					= $list['addr'][1];
			$dn_info['selected']['phone']					= '';
			$dn_info['selected']['reg_date']					= '';
			$dn_info['selected']['same_paymethod']					= $list['same_paymethod'];
		}

		return $dn_info;
	}
	/*
	배송지 추가 배송비 가져오기
	*/
	public function GetAddDeleveryPrice($zipcode) {
		$sql="select deli_price from tbldeliarea where st_zipcode::integer<=? and en_zipcode::integer>=? order by deli_price desc limit 1";
		$rs = $this->adodb->Execute($sql,array($zipcode,$zipcode));                     
		$data = $rs->FetchRow();
		if(!$data) $data['deli_price'] = 0;
		return $data['deli_price'];
	}

	/**
	 * 주문번호 생성
	 *
	 * @param string $prefix
	 * @return void
	 */
	public function getOrderNum($prefix = '') {
		if(!$prefix) {
			if(MEMID) $prefix = 'M';
			else $prefix = 'G';
		}
		$now = array_sum(explode(' ',microtime()));
		list($usec, $sec) = explode(" ", microtime());
		
		$order_num = strtoupper($prefix).date('ymdHis').floor($usec*1000);
		$order_num = str_pad($order_num, 16, '0'); //16자리
		return $order_num;
	}
	
	/**
	 * 장바구니 상품을 임시주문테이블로 이전
	 * 구매 수량대로 row를 쌓는다
	 *
	 * @param [type] $group_no
	 * @return void
	 */
	public function transTemp($group_no) {
		global $_ShopInfo;
		if(!is_array($group_no)) $group_no = array($group_no);

		
		$Product = new PRODUCT; //상품클래스
		
		$Mileage = new MILEAGE; //마일리지
	
		$mileage_rate = $Mileage->getRate($_ShopInfo->getMemgroup()); //구매회원마일리지 적립률
		$order_num_temp = $this->getOrderNum('T'); //바로구매 임시주문번호

		$success = true;
		foreach($group_no as $gno) {					
			$buy_list = $this->getListByGroup($gno);

			//최대구매가능수량 초과여부체크
			$cnt_by_product = array();
			if(is_array($buy_list)) {
				foreach($buy_list as $v) {
					if($v['option_type'] == 'product') {
						$cnt_by_product[$v['option_code']]+=$v['qty'];
					}
					else {
						$cnt_by_product[$v['productcode']]+=$v['qty'];
					}
				}

				if(is_array($cnt_by_product)) {
					foreach($cnt_by_product as $k=>$v) {
						$valid_rs = $this->validator_buy($k, 'bought');
						
						
						if($valid_rs['remain']-$v < 0) {
							return_json(false,$valid_rs['productname'].'<br><br>해당 상품은 1인 '.$valid_rs['max'].'개(옵션)만 구매 가능합니다.<br>주문 내역을 확인하시겠습니까?', array('error_code'=>'bought', 'url'=>DIR_VIEW.'/mypage_orderlist.php'));
						}
					}
				}
			}

			
		
			foreach($buy_list as $row) {
				if($row['option_type'] == 'option') $product_info = $Product->getRowSimple($row['productcode']);
				else $product_info = $Product->getRowSimple($row['option_code']);

				
				$mileage_expect = $Mileage->calcMileage($product_info['endprice'],$mileage_rate);
				$record = array(
					'order_num_temp'=>$order_num_temp,
					'member_id'=>$row['member_id'],
					'guest_id'=>$row['guest_id'],
					'productcode'=>$row['productcode'],
					'option_type'=>$row['option_type'],
					'option_code'=>$row['option_code'],
					'basket_group_no'=>$row['group_no'], //장바구니그룹코드(상품기준으로 묶음)
					'pr_type'=>$row['pr_type'], //상품유형(클래스변수참고)
					'direct_yn'=>$row['direct_yn'], //바로구매여부

					'price_consumer'=>$product_info['consumerprice'], //정상가
					'price_sell'=>$product_info['sellprice'], //할인가
					'price_end'=>$product_info['endprice'], //상품별최종판매가(기간할인적용금액)

					'mileage_expect'=>$mileage_expect, //적립예정마일리지
					'date_insert'=>NOW,
					'date_update'=>NOW,
				);

				$sql = sqlInsert($record, $this->tbls['order_temp']);
				for($i=1;$i<=$row['qty'];$i++) {
					$rs = $this->adodb->Execute($sql);
					if(!$rs) {
						// echo $sql;
						$success=false;
					}
				}
			}
		}

		if($success) {
			//주문서생성 세션
			$_SESSION['order_time'] = time();
			return $order_num_temp;
		}
		else return false;
	}


    /**
     * 통계 스크립트용 장바구니목록 함수
     * @param  string $where [description]
     * @return array         [description]
     */
    public function getBasketForStats($pr_type='1', $where='') {
        global $_ShopInfo;
        $where = $this->_getWhere($where);


        $tbl = $this->tbls['basket'];

        $sql = "SELECT * FROM {$tbl} WHERE {$where} ORDER BY group_no DESC, option_type ASC, no ASC";

        $Product = new PRODUCT();
        $rs = $this->adodb->Execute($sql);
        $list = array();

        while($row = $rs->FetchRow()) {
            if($row['option_type'] == 'product') {
                $option_product = $Product->getRowSimple($row['option_code']); //getRowSimple 내부에서 품절프로세스 처리
                $row['endprice'] = $option_product['endprice'];
                $row['productname'] = addslashes($option_product['productname']);
                $row['productcode'] = $row['option_code'];
            }else {
                $option_product = $Product->getRowSimple($row['productcode']);
                $row['productname'] = addslashes($option_product['productname']);
                $row['endprice'] = $option_product['endprice'];
            }
            $category_name = $Product->getCategoryName($row['productcode']);
            $row['category']=$category_name;
            /*foreach ($link as $key=>$val) {
                $row['category']=array_pop(array_column($val, 'name')); //제일 마지막 카테고리 이름 가져오기
            }*/
            if(isset($list[$row['productcode']])) {
                $list[$row['productcode']]['qty'] += $row['qty'];
            }else{
                $list[$row['productcode']] = $row;
			}
        }

        return $list;

    }

}
?>
