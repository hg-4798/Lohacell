<?php
/**
 * 관리자용 CS 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */

//실행파일 직접접근 방지
if($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') { 
	header("HTTP/1.0 404 Not Found");
	exit;
}


$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Order = new ORDER('admin');

$mode = $_POST['mode'];
$act = $_POST['act'];


if($mode == 'exchange') { //교환
	$_CLEAN = $Order->xss_post();
	if($act == 'cs') { //교환기본정보 저장
		$tbl_oe = $Order->tbls['order_exchange'];

		$receiver_tel_arr = array_filter($_POST['receiver_tel']);
		if(empty($receiver_tel_arr)) $receiver_tel = '';
		else $receiver_tel = implode('-',$receiver_tel_arr);
		
		$record = array(
			
			'receiver_name'=>$_CLEAN['receiver_name'],
			'receiver_zipcode'=>$_CLEAN['receiver_zipcode'],
			'receiver_addr'=>$_CLEAN['receiver_addr'],
			'receiver_addr_detail'=>$_CLEAN['receiver_addr_detail'],
			'receiver_mobile'=>implode('-',$_CLEAN['receiver_mobile']),
			'receiver_tel'=>$receiver_tel,
			'memo'=>$_CLEAN['memo'],
			'delivery_pay'=>str_replace(',','',$_CLEAN['delivery_pay']),
			'delivery_pay_method'=>$_CLEAN['delivery_pay_method'],
			'date_update'=>NOW
		);

		$sql = sqlUpdate($record, $tbl_oe, array('idx'=>$_POST['cs_idx']));
		$rs = $Order->adodb->Execute($sql);
		if($rs) {
			return_json(true,$_ALERT['C000']);
		}
		else {
			return_json(false,$_ALERT['C003']);
		}

	}
	else if($act == 'status') { //교환상태변경

		$checked = $_POST['checked'];
		$tbl_ob = $Order->tbls['order'];
		$tbl_op = $Order->tbls['order_product'];
		$tbl_oe = $Order->tbls['order_exchange']; //교환신청서
		$tbl_oep = $Order->tbls['order_exchange_product'];


		$order_num = $_POST['order_num'];
		$cs_idx = $_POST['cs_idx'];
		$status_4 = array(); //교환승인건
		$success = true;


		$Order->transBegin(); //트랜잭션 시작

		foreach($checked as $order_product_idx) {
			list($cs_type, $cs_status, $cs_flag) = explode('|',$_POST['cs_status']);

			$product_row = $Order->getProductRow($order_product_idx);


			$record = array(
				'cs_type'=>$cs_type,
				'cs_status'=>$cs_status,
				'cs_flag'=>$cs_flag,
				'date_update'=>NOW
			);

			$sql = sqlUpdate($record, $tbl_op, array('idx'=>$order_product_idx));
			$rs = $Order->adodb->Execute($sql);
			if($rs) {
				if($cs_status == '4') { //교환완료인경우 
					$status_4[] = $order_product_idx;

					//교환승인 날짜 업데이트
					$sql_oep_update = sqlUpdate(array('date_status_4'=>NOW), $tbl_oep, array('order_num'=>$order_num, 'exchange_idx'=>$cs_idx, 'order_product_idx'=>$order_product_idx));
					$Order->adodb->Execute($sql_oep_update);
				}

				//상품로그
				$record_log = array(
					'order_num'=>$order_num,
					'order_product_idx'=>$order_product_idx,
					'type'=>'cs_status',
					'value'=>$product_row['order_status'].$cs_type.$cs_status.'_'.$cs_flag,
					'value_pre'=>$product_row['order_status'].$product_row['cs_type'].$product_row['cs_status'].'_'.$product_row['cs_flag'],
					'msg'=>'관리자 > 교환요청서'
				);
				$Order->_log($record_log); //교환로그기록
			}
			else {
				$Order->error('교환요청서 상태변경실패');
				$success = false;
			}
		}

		if($success) {
			//교환완료건에 대한 처리 : 기존옵션에 대한 재고+1
			if(count($status_4)) {
				
				$basic_info = $Order->adodb->getRow("SELECT * FROM {$tbl_ob} WHERE order_num='{$order_num}'");//기존주문서 정보
				$cs_info = $Order->adodb->getRow("SELECT * FROM {$tbl_oe} WHERE idx='{$cs_idx}'"); 
				
				
				//교환상품등록 & 교환요청상품 취소처리
				foreach($status_4 as $op_idx) {
					$op_info = $Order->adodb->getRow("SELECT * FROM {$tbl_op} WHERE idx='{$op_idx}'"); //기존주문상품정보
					$oep_info = $Order->adodb->getRow("SELECT * FROM {$tbl_oep} WHERE order_product_idx='{$op_idx}'");//교환정보

					$op_info_new = array(
						'order_num'=>$basic_info['order_num'],
						'order_status'=>'2',
						'cs_type'=>'E',
						'cs_status'=>'0',
						'cs_flag'=>'',
						'option_code'=>$oep_info['exchange_option_code'],
						'delivery_company'=>'',
						'delivery_no'=>'',
						'date_insert'=>NOW,
						'date_update'=>NOW,
						'date_order_1'=>NOW,
						'date_order_2'=>NOW,
						'cs_idx'=>'0',
						'cs_product_idx'=>$op_idx
					);
					$record_op = array_merge($op_info, $op_info_new);
					unset($record_op['idx']);
					unset($record_op['date_order_3']);
					unset($record_op['date_order_4']);
					unset($record_op['date_order_5']);
					unset($record_op['date_order_6']);
					$sql_op = sqlInsert($record_op, $tbl_op);
					// echo $sql_op;

					$rs_op = $Order->adodb->Execute($sql_op);
					if(!$rs_op) {
						$success = false;
						$Order->error('교환상품등록 실패');
						
					}
					else {
						$cs_product_idx = $Order->adodb->insert_id();

						//교환상품로그
						$record_log = array(
							'order_num'=>$order_num,
							'order_product_idx'=>$cs_product_idx,
							'type'=>'cs_status',
							'value'=>$record_op['order_status'].$record_op['cs_type'].$record_op['cs_status'],
							'value_pre'=>'',
							'msg'=>'관리자 > 교환요청서'
						);
						$Order->_log($record_log); //교환로그기록

						//재고복원
						$Order->resetStock($op_idx);
					}
				}
			}	
			
		}

		if($success) {
			$Order->transCommit();
			return_json(true,$_ALERT['C000']);
		}
		else {
			$Order->transRollback();
			$Order->log_file(array(
				'FILE'=>__FILE__,
				'order_num'=>$order_num,
				'msg'=>$Order->err_msg
			));
			return_json(false,$_ALERT['C003'].$Order->err_msg);
		}

	}
	else if($act == 'withdraw') { //교환철회
		$idx_arr = explode(',',$_POST['order_product_idx']);
		$tbl_op = $Order->tbls['order_product'];

		$success = true;

		$Order->transBegin(); //트랜잭션 시작

		foreach($idx_arr as $idx) {
			$product_row = $Order->getProductRow($idx);
			$record = array(
				'cs_type'=>$product_row['cs_type'],
				'cs_status'=>$product_row['cs_status'],
				'cs_flag'=>'WD',
				'date_update'=>NOW
			);

			$sql = sqlUpdate($record, $tbl_op, array('idx'=>$idx));
			$rs = $Order->adodb->Execute($sql);
			if(!$rs) {
				$success = false;
				$Order->error('교환철회실패');
			}
			else {
				$record_log = array(
					'order_num'=>$order_num,
					'order_product_idx'=>$idx,
					'type'=>'cs_status',
					'value'=>$product_row['order_status'].$record['cs_type'].$record['cs_status'].'_'.$record['cs_flag'],
					'value_pre'=>$product_row['order_status'].$product_row['cs_type'].$product_row['cs_status'].'_'.$product_row['cs_flag'],
					'msg'=>'관리자 > 교환요청서'
				);
				$Order->_log($record_log); //교환철회로그기록
			}
		}

		if($success) {
			$Order->transCommit();
			return_json(true,$_ALERT['C000']);
		}
		else {
			$Order->transRollback();
			$Order->log_file(array(
				'FILE'=>__FILE__,
				'order_num'=>$order_num,
				'msg'=>$Order->err_msg
			));
			//echo 'aaa : '.$Order->err_msg;
			return_json(false,$_ALERT['C003'].$Order->err_msg);
		}
	}
}
else if($mode == 'return') { //반품
	if($act =='status') {
		
		$checked = $_POST['checked'];
		$tbl_ob = $Order->tbls['order'];
		$tbl_op = $Order->tbls['order_product'];
		$tbl_orb = $Order->tbls['order_return']; //반품신청서
		$tbl_orp = $Order->tbls['order_return_product'];


		$order_num = $_POST['order_num'];

		// $rs = $Order->syncBasic($order_num);exit;

	

		$cs_idx = $_POST['cs_idx'];
		// $status_4 = array(); 
		$success = true;

		$Order->transBegin(); //트랜잭션 시작

		foreach($checked as $order_product_idx) {
			list($cs_type, $cs_status, $cs_flag) = explode('|',$_POST['cs_status']);

			$product_row = $Order->getProductRow($order_product_idx);


			$record = array(
				'cs_type'=>$cs_type,
				'cs_status'=>$cs_status,
				'cs_flag'=>$cs_flag,
				'date_update'=>NOW
			);

			$sql = sqlUpdate($record, $tbl_op, array('idx'=>$order_product_idx));
			$rs = $Order->adodb->Execute($sql);
			if($rs) {

				//상품로그
				$record_log = array(
					'order_num'=>$order_num,
					'order_product_idx'=>$order_product_idx,
					'type'=>'cs_status',
					'value'=>$product_row['order_status'].$cs_type.$cs_status.'_'.$cs_flag,
					'value_pre'=>$product_row['order_status'].$product_row['cs_type'].$product_row['cs_status'].'_'.$product_row['cs_flag'],
					'msg'=>'관리자 > 반품요청서'
				);
				$Order->_log($record_log); //교환로그기록
			}
			else {
				$Order->error('반품요청서 상태변경실패');
				$success = false;
			}
		}


		if($success) {
			$Order->transCommit();
			return_json(true,$_ALERT['C000']);
		}
		else {
			$Order->transRollback();
			$Order->log_file(array(
				'FILE'=>__FILE__,
				'order_num'=>$order_num,
				'msg'=>$Order->err_msg
			));
			return_json(false,$_ALERT['C003'].$Order->err_msg);
		}
	}
	else if($act == 'refund') { //환불처리
		
		// pre($_POST);exit;
		
		$order_num = $_POST['order_num'];
		

		$success = true;
		$_CLEAN = $Order->xss_post();

		$Order->transBegin(); //트랜잭션시작

		//주문서 기본정보
		$basic_info = $Order->getBasicRow($order_num);
		$basic_record = array();
		//쿠폰복원
		if(is_array($_POST['coupon'])) {
			$Coupon = new COUPON;
			foreach($_POST['coupon'] as $k=>$v) {
				if($k == 'product') { //상품쿠폰
					foreach($v as $ci_no) {
						if($ci_no > 0)$Coupon->restore($ci_no); //쿠폰미사용처리로 복원
					}
				}
				else if($k=='basket') { //장바구니 쿠폰복원
					if($v > 0) {
						$Coupon->restore($v); 
						$basic_record['coupon_basket'] = 0; //사용쿠폰 번호 제거
						$basic_record['coupon_basket_discount'] = 0; //쿠폰할인액변경
					}
				}
				else if($k=='delivery') {//무료배송쿠폰 복원
					if($v > 0) $Coupon->restore($v); 
				}
			}
		}
		
		//포인트 복원처리
		$refund_point = str_replace(',','',$_POST['refund']['point']);
		if($refund_point> 0) {
			$Point = new POINT;
			$rs_point = $Point->restore($order_num,$refund_point);
			if(!$rs_point) {
				$success = false;
				$Order->error("포인트 복원실패");
			}

			$basic_record['use_point']=$basic_info['use_point']-$refund_point;
		}
		else $refund_point = 0;
		

		//마일리지 복원처리
		$refund_mileage = str_replace(',','',$_POST['refund']['mileage']);
		if($refund_mileage > 0) {
			$Mileage = new MILEAGE;
			$rs_mileage = $Mileage->restore($order_num,$refund_mileage);
			if(!$rs_mileage){
				$success = false;
				$Order->error("마일리지 복원실패");
			} 

			$basic_record['use_mileage']=$basic_info['use_mileage']-$refund_mileage;
		}
		else $refund_mileage = 0;


		//환불로그 등록
		$bank_info = (is_array($_POST['bank']))?serialize($_POST['bank']):''; //환불계좌
		
		//환불금액정보
		$refund_field = array('cash','card','vcnt','acnt','mileage','point');
		$refund = array();
		foreach($refund_field as $f) {
			if($_POST['refund'][$f]) $refund[$f] = str_replace(',','',$_POST['refund'][$f]);
			else $refund[$f] = 0;
		}

		//가격정보
		$price_field = array('total','product','delivery');
		$price = array();
		foreach($price_field as $f) {
			if($_POST['price'][$f]) $price[$f] = str_replace(',','',$_POST['price'][$f]);
			else $price[$f] = 0;
		}

		//환불차감배송비
		$pay_delivery_add = ($_POST['pay_delivery_add'])?str_replace(',','',$_POST['pay_delivery_add']):0;
		
		
		$record_refund_log = array(
			'order_num'=>$order_num,
			'price_total'=>$price['total'], //환불총액
			'price_product'=>$price['product'], //환불상품금액
			'price_delivery'=>$price['delivery'], //환불배송비
			'refund_method'=>$_POST['refund_method'], //환불수단
			'refund_mileage'=>$refund['mileage'], //마일리지 환불액
			'refund_point'=>$refund['point'], //포인트 환불액
			'refund_cash'=>$refund['cash'], //현금 환불액
			'refund_card'=>$refund['card'], //카드 환불액
			'refund_vcnt'=>$refund['vcnt'], //가상계좌 환불액
			'refund_acnt'=>$refund['acnt'], //실시간계좌이체 환불액
			'pay_delivery'=>$pay_delivery_add, //배송비
			'bank_info'=>$bank_info,//환불계좌
			'date_insert'=>NOW //처리일
		);

		// pre($record_refund_log);
	

		$tbl_of_log = $Order->tbls['order_refund_log'];
		$sql_refund_log = sqlInsert($record_refund_log, $tbl_of_log);
		$rs = $Order->adodb->Execute($sql_refund_log);
		if(!$rs) {
			$Order->error("환불로그등록 실패");
			$success = false;
		}

		$refund_idx = $Order->adodb->insert_id(); //환불테이블 PK

		//정보 접수
		$reg_place = 'admin';
		$reg_id = $_ShopInfo->id;

		
		$cs_flag = 'RC'; //환불완료
		$cs_status = '4'; //반품완료


		//상품별 상태업데이트 (환불완료)
		$record_product = array(
			'cs_type'=>'R',
			'cs_status'=>$cs_status,
			'cs_flag'=>$cs_flag,
			'cs_idx'=>$_POST['return_idx'],
			'coupon_issue_no'=>'',
			'coupon_discount'=>'0'
		);
		$tbl_op = $Order->tbls['order_product'];

		$product_idx_arr = explode(',',$_POST['op_idx']);
		foreach($product_idx_arr as $op_idx) {
			$product_row = $Order->getProductRow($op_idx);


			$where = array('idx'=>$op_idx);
			$sql = sqlUpdate($record_product, $tbl_op, $where);
			$rs = $Order->adodb->Execute($sql);
			if(!$rs) {
				$Order->error("상품정보 업데이트 실패");
				$success = false;
				break;
			}
			else {

				//재고복원
				$Order->resetStock($op_idx);
				
				$record_log = array(
					'order_num'=>$order_num,
					'order_product_idx'=>$op_idx,
					'type'=>'cs_status',
					'value'=>$product_row['order_status'].$product_row['cs_type'].$cs_status.'_'.$cs_flag,
					'value_pre'=>$product_row['order_status'].$product_row['cs_type'].$product_row['cs_status'].'_'.$product_row['cs_flag'],
					'msg'=>'관리자 > 주문상세'
				);
				$Order->_log($record_log); //취소로그기록
			}
		}

		

		//주문서 기본정보 업데이트
		if($price['basket'] > 0) {
			$basic_record['coupon_basket_discount'] = $basic_info['coupon_basket_discount']-$price['basket']; //장바구니 할인액 조정
		}

		// pre($basic_record);

		$rs = $Order->syncBasic($order_num, $basic_record);
		if(!$rs) {
			$Order->error("주문서 정보 업데이트 실패");
			$success = false;
		}

		// $success = false;



		if($success) {
			$Order->transCommit();
			return_json(true,'환불처리가 완료되었습니다.');
		}
		else {
			$Order->transRollback();
			return_json(false,$_ALERT['C003']."<br>".$Order->err_msg);
		}
	}
	else if($act == 'delivery') { //반품수거지 정보 변경

	}
	else if($act == 'memo') { //반품메모 변경

	}

}
?>