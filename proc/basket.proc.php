<?php
/**
 * 장바구니 프로세싱
 * 비동기처리
 * @author 이혜진(stickcandy81@nate.com)
 */

 //실행파일 직접접근 방지
if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { 
	header("HTTP/1.0 404 Not Found");
	exit;
}

$Dir = "../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");


$Basket = new BASKET;//장바구니클래스
$Product = new PRODUCT; //상품클래스

$mode = $_POST['mode'];
$act = $_POST['act'];


if($mode == 'order') {
	$tbl = $Basket->tbls['basket'];

	//POST변수
	$productcode = $_POST['productcode'];
	$guest_id = session_id(); //비회원용 세션ID
	$member_id = MEMID;

	$record_default = array(
		'member_id'=>$member_id,
		'guest_id'=>$guest_id,
		'productcode'=>$productcode,
		'pr_type'=>$_POST['pr_type'],
		'date_insert'=>TIMESTAMP,
		'date_update'=>TIMESTAMP
	);

	if($act == 'buy') { //상세페이지에서 바로구매
		/**
		 * 1. 장바구니 테이블에 담는다 (상품/옵션별 1row)
		 * 2. 임시 주문테이블에 담는다
		 */

		
		$option = json_decode($_POST['option'],true);
		if(!is_array($option) || empty($option)) { //옵션미선택체크
			return_json(false,'옵션을 선택하세요'); 
		}

		$record_default['direct_yn'] = 'Y'; //바로구매여부

		foreach($option as $k=>$v) {
			$record = $record_default;
			$record['option_code']=$v['num']; //옵션코드
			$record['qty']=$v['qty'];//수량

			$record['option_type']=$v['type'];
			$sql = sqlInsert($record, $tbl);
			$rs = $Basket->adodb->Execute($sql);
			if(!$group_no) { //장바구니 그룹번호(옵션, 추가상품연결을 위함)
				$group_no = $Basket->adodb->insert_id();
				$Basket->adodb->Execute("UPDATE {$tbl} SET group_no='{$group_no}' WHERE no='{$group_no}'");
				$record_default['group_no'] = $group_no;
			}
		}

		$order_num_temp = $Basket->transTemp($group_no); //임시주문테이블에 저장

		if($order_num_temp) {
			$toid = $Basket->Enctypt_AES128CBC($order_num_temp); //임시주문번호 암호화리턴
			$url = "order.php?pr_type=".$_POST['pr_type']."&toid=".$toid;
			if(!$member_id) {
				$url = "login.php?chUrl=".urlencode($url);
			}
			return_json(true,'', array('url'=>$url));
		}
		else {
			return_json(false,'error#'.$act);
		}
	}
	else if($act == 'buy_basket') { //장바구니에서 주문

		$group_no = $_POST['no'];
		$order_num_temp = $Basket->transTemp($group_no); //임시주문테이블에 저장
		
		if($order_num_temp) {
			$toid = $Basket->Enctypt_AES128CBC($order_num_temp); //임시주문번호 암호화리턴
			$url = "order.php?pr_type=".$_POST['pr_type']."&toid=".$toid;
			if(!$member_id) {
				$url = "login.php?chUrl=".urlencode($url);
			}
			return_json(true,'', array('url'=>$url));
		}
		else {
			return_json(false,'error#'.$act);
		}
		

	}
	else { //상세페이지에서 장바구니담기


		$option = json_decode($_POST['option'],true);
		if(!is_array($option) || empty($option)) { //옵션미선택체크
			return_json(false,'옵션을 선택하세요'); 
		}

		//해당상품 최대구매수량체크
		$product_info = $Product->getRowSimple($productcode);
		$max_quantity = $product_info['max_quantity'];
		if($max_quantity>0) {
			//회원,비회원체크
			if(!MEMID) {
				return_json(false,'해당 상품은 회원만 구매가능합니다.<br>로그인하시겠습니까?', array('error_code'=>'guest', 'url'=>urlencode(DIR_VIEW.'/productdetail.php?productcode='.$productcode)));
			}

			//구매수량(option:기본상품의 옵션, product: 추가구매상품)
			$sum = array();
			foreach($option as $v) {
				if($v['type'] == 'option') {
					$sum['option']+=$v['qty'];
				}
				else {
					$sum['product'][] = $v;
				}
			}


			//기본상품 최대구매수량체크(주문취소완료/반품완료 제외)
			$tbl_op = $Product->tbls['order_product'];
			$tbl_ob = $Product->tbls['order'];
			$valid_rs = $Basket->validator_buy($productcode, 'bought');
			if($valid_rs['remain']-$sum['option'] < 0) {
				return_json(false,'<b>'.$valid_rs['productname'].'</b><br><br>이미 구매한 이력이 있습니다.<br>해당 상품은 1인 '.$valid_rs['max'].'개(옵션)만 구매 가능합니다.<br>주문 내역을 확인하시겠습니까?', array('error_code'=>'bought', 'url'=>DIR_VIEW.'/mypage_orderlist.php'));
			}

			//기본상품 장바구니 담긴 개수체크
			$valid_rs = $Basket->validator_buy($productcode, 'basket');
			if($valid_rs['remain']-$sum['option'] < 0) {
				return_json(false,'<b>'.$valid_rs['productname'].'</b><br><br>이미 장바구니에 담겨 있습니다.<br>해당 상품은 1인 '.$valid_rs['max'].'개(옵션)만 구매 가능합니다.<br>장바구니 확인하시겠습니까?', array('error_code'=>'basket', 'url'=>DIR_VIEW.'/basket.php'));
			}

			//추가구매상품 기존 구매여부 체크 @todo
			if(is_array($sum['product'])) {
				foreach($sum['product'] as $v) {
					//상품별 최대구매수량
					$valid_rs = $Basket->validator_buy($v['num'], 'bought');
				
					if($valid_rs['remain']-$v['qty'] < 0) {
						return_json(false,'<b>'.$valid_rs['productname'].'</b><br><br>이미 구매한 이력이 있습니다.<br>해당 상품은 1인 '.$valid_rs['max'].'개(옵션)만 구매 가능합니다.<br>주문 내역을 확인하시겠습니까?', array('error_code'=>'bought', 'url'=>DIR_VIEW.'/mypage_orderlist.php'));
						break;
					}

					$valid_rs = $Basket->validator_buy($v['num'], 'basket');
					// pre($valid_rs);
					if($valid_rs['remain']-$v['qty'] < 0) {
						return_json(false,'<b>'.$valid_rs['productname'].'</b><br><br>이미 장바구니에 담겨 있습니다.<br>해당 상품은 1인 '.$valid_rs['max'].'개(옵션)만 구매 가능합니다.<br>장바구니 확인하시겠습니까?', array('error_code'=>'bought', 'url'=>DIR_VIEW.'/basket.php'));
						break;
					}
				}
			}
		}


		$record_default['direct_yn'] = 'N'; //바로구매여부

		//장바구니 중복체크
		$exist = $Basket->getCount("productcode='{$productcode}'");
		if($exist['total']>0) {
			return_json(false,'이미 장바구니에 담긴 상품입니다.');
		}

		$group_no = 0;
		foreach($option as $k=>$v) {
			$record = $record_default;
			$record['option_code']=$v['num']; //옵션코드
			$record['qty']=$v['qty'];//수량

			$record['option_type']=$v['type'];
			$sql = sqlInsert($record, $tbl);
			$rs = $Basket->adodb->Execute($sql);
			if(!$group_no) {
				$group_no = $Basket->adodb->insert_id();
				$Basket->adodb->Execute("UPDATE {$tbl} SET group_no='{$group_no}' WHERE no='{$group_no}'");
				$record_default['group_no'] = $group_no;
			}
		}

		if($rs) {
			$basket_count = $Basket->getCount(); //장바구니 담긴 개수 반환(일반상품+임직원상품)
			$type = ($_POST['pr_type'] == 3)?'staff':'normal';
			return_json(true,'', array('count'=>$basket_count,'type'=>$type));
		}
		else {
			return_json(false,'error#'.$act);
		}
	}
}
else if($mode == 'order_basket') { //장바구니에서 주문

}
else if($mode == 'remove') {
	$rs = $Basket->deleteBasket($_POST['no'], $_POST['act']);
	if($rs) {
		return_json(true,'삭제되었습니다.');
	}
	else {
		return_json(false);
	}
}
else if($mode == 'option') {
	$tbl = $Basket->tbls['basket'];
	if($act == 'qty') { //옵션수량변경
		
		//최대구매수량체크:S
		$tbl_product = $Basket->tbls['product'];
		$tbl_basket = $Basket->tbls['basket'];

		//$basket_info = $Basket->adodb->getRow("SELECT p.productcode, p.max_quantity, p.pr_type, b.qty FROM {$tbl_basket} AS b LEFT JOIN {$tbl_product} AS p ON(b.productcode=p.productcode) WHERE b.no='".$_POST['no']."'"); //상품의 최대구매수량
		$basket_info = $Basket->adodb->getRow("SELECT * FROM {$tbl_basket} WHERE no='".$_POST['no']."'"); //장바구니정보

		//수량증가인경우만 처리
		if($_POST['qty'] > $basket_info['qty']) {
			
			//장바구니 담긴수량
			if($basket_info['option_type'] == 'product') { //추가구매상품
				$valid_rs = $Basket->validator_buy($basket_info['option_code']);
				if($valid_rs['remain']-$_POST['qty']<0) {
					return_json(false,'해당 상품은 1인 '.$valid_rs['max'].'개(옵션)만 구매 가능합니다.<br>장바구니 및 구매이력을 확인하세요.');
				}

				//$sum_basket = $Basket->adodb->getOne("SELECT COALESCE(SUM(qty),0) AS total FROM {$tbl_basket} AS b LEFT JOIN {$tbl_product} AS p ON(b.productcode=p.productcode) WHERE b.no='".$_POST['no']."'"); //상품의 최대구매수량
			}
			else {
				$valid_rs = $Basket->validator_buy($basket_info['productcode']);
				if($valid_rs['remain']-$_POST['qty']<0) {
					return_json(false,'해당 상품은 1인 '.$valid_rs['max'].'개(옵션)만 구매 가능합니다.<br>장바구니 및 구매이력을 확인하세요.');
				}
			}
		}

		$record = array(
			'qty'=>$_POST['qty']
		);
		$where = array(
			'no'=>$_POST['no']
		);

		$sql = sqlUpdate($record, $tbl, $where);
		$rs = $Basket->adodb->Execute($sql);
		if($rs) {
			return_json(true,'변경되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요');
		}
	}
	else if($act == 'add') {

		//추가가능여부체크(최대구매수량)
		$valid_rs = $Basket->validator_buy($_POST['productcode']);
		if($valid_rs['remain']<1) {
			return_json(false,'해당 상품은 1인 '.$valid_rs['max'].'개(옵션)만 구매 가능합니다.');
		}

		//옵션정보
		$option_num = $_POST['select_option'];
		$option_info = $Product->getOptionRow($option_num);
		$option_stock = $option_info['option_quantity'] - $option_info['option_quantity_sales']; //구매가능옵션

		

		//기존장바구니에 담긴옵션인지 체크
		$basket_info = $Basket->getRow("option_code='{$option_num}'");
		if($basket_info) {
			//재고체크
			if($option_stock < $basket_info['qty']+1) {
				return_json(false,'선택하신 옵션의 재고가 부족합니다.');
			}

			$record = array(
				'qty'=>$basket_info['qty']+1
			);
			$where = array(
				'no'=>$basket_info['no']
			);

			$sql = sqlUpdate($record, $tbl, $where);

		}
		else {

			//재고체크
			if($option_stock<1) {
				return_json(false,'선택하신 옵션의 재고가 부족합니다.');
			}

			$guest_id = session_id(); //비회원용 세션ID
			$member_id = MEMID;

			$record = array(
				'member_id'=>$member_id,
				'guest_id'=>$guest_id,
				'group_no'=>$_POST['group_no'],
				'productcode'=>$_POST['productcode'],
				'option_type'=>'option',
				'option_code'=>$option_num,
				'qty'=>1,
				'pr_type'=>$_POST['pr_type'],
				'date_insert'=>TIMESTAMP,
				'date_update'=>TIMESTAMP
			);

			$sql = sqlInsert($record, $tbl);
		}

		$rs = $Basket->adodb->Execute($sql);
		// echo $sql;
		if($rs) {
			return_json(true, '옵션이 추가되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요');
		}

	}
}




