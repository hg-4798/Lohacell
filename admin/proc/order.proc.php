<?php
/**
 * 관리자용 주문정보 프로세싱
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


if($mode == 'change') { //주문단계
	if($act == 'order_status') { 
	
		$order_status = $_POST['order_status'];

		if($_POST['field'] == 'order_num') {
			$order_num = $_POST['order_num'];
			if($order_status == '2') $rs = $Order->setPaid($order_num, '관리자 > 주문상세');
			else {
				$idx = $Order->adodb->getOne("SELECT array_to_string(array_agg(idx::int),',') AS idxs FROM ".$Order->tbls['order_product']." WHERE order_num='{$order_num}'");
				$rs = $Order->changeOrderStatus($idx,$order_status);
			}
		}
		else {
			$idx = $_POST['idx'];
			$rs = $Order->changeOrderStatus($idx,$order_status);
		}
		
		if($rs) {
			return_json(true,'처리되었습니다.');
		}
		else {
			return_json(false,$_ALERT['C003']);
		}
	}
	else if($act == 'batch') { //일괄변경
		$order_num_arr = explode(',',$_POST['order_num']);
		if(!is_array($order_num_arr)) {
			return_json(false,'선택된 주문서가 없습니다.');
		}


		$order_status = $_POST['order_status'];
		$success = true;
		foreach($order_num_arr as $order_num) {
			if($order_status == '4') { //배송중처리
				//에스크로+배송중으로 변경하는 경우 에스크로 상태변경요청
				$CS = new CS;
				$rs = $CS->escrow($order_num, 'STE1');
				if(!$rs) {
					$success = false;
					return_json(false,'<b>'.$order_num.'</b><br>에스크로 상태변경(배송시작)에 실패하였습니다.<br>'.$CS->err_msg);
				}
			}

			
			$idx = $Order->adodb->getOne("SELECT array_to_string(array_agg(idx::int),',') AS idxs FROM ".$Order->tbls['order_product']." WHERE order_num='{$order_num}'");
			$rs = $Order->changeOrderStatus($idx,$order_status);
			if(!$rs) {
				$success = false;
				break;
			}
		}

		if($success) {
			return_json(true,'처리되었습니다.');
		}
		else {
			return_json(false,$_ALERT['C003']);
		}
	}
}
else if($mode == 'delivery') { //배송관련
	if($act == 'set') { //송장번호입력
		$idx_arr = explode(',',$_POST['idx']);

		$record = array(
			'delivery_company'=>$_POST['delivery_company'],
			'delivery_no'=>str_replace("-","",$_POST['delivery_no'])
		);

		$success = true;
		foreach($idx_arr as $idx) {
			$where = array('idx'=>$idx);
			$rs = $Order->updateProduct($record, $where);
			if(!$rs) $success = false;
		}

		if($success) {
			return_json(true,'적용되었습니다.');
		}
		else {
			return_json(false,$_ALERT['C003']);
		}
	}
}
else if($mode == 'detail') { //주문상세 일괄처리
	if($act == 'batch') {
		// pre($_POST);EXIT;
		$success = true;
		$product_idx = $_POST['idx'];

		$error_msg = $_ALERT['C003'];
		$order_num = $_POST['order_num'];

		if($_POST['delivery_company'] && $_POST['delivery_no']) { //택배사정보 변경시
			$record = array(
				'delivery_company'=>$_POST['delivery_company'],
				'delivery_no'=>str_replace("-","",$_POST['delivery_no'])
			);
			$product_idx_arr = explode(',',$product_idx);

			foreach($product_idx_arr as $idx) {
				$where = array('idx'=>$idx);
				$rs = $Order->updateProduct($record, $where);
				if(!$rs) $success = false;
			}
		}

		//에스크로+배송중으로 변경하는 경우 에스크로 상태변경요청
		if($_POST['order_status'] == '4') {
			$CS = new CS;
			$rs = $CS->escrow($order_num, 'STE1');
			if(!$rs) {
				$success = false;
				return_json(false,'에스크로 상태변경(배송시작)에 실패하였습니다.<br>'.$CS->err_msg);
			}
		}
		

		if($_POST['order_status'] > 0) { //처리상태 변경시
			$order_status = $_POST['order_status'];
			$rs = $Order->changeOrderStatus($product_idx,$order_status);
			if(!$rs) {
				$success = false;
			}
		}

		

		if($success) {
			return_json(true,'적용되었습니다.');
		}
		else {
			return_json(false,$Order->err_msg);
		}
		
	}
}
else if($mode == 'order_update'){ //주문서 상세정보 업데이트
	$tbl = $cfg_tbl['order'];
	// parse_str($_POST['data'], $row);
	$row = $_POST;
	$where = "order_num='{$row['order_num']}'";
	$record=array();
	foreach($row as $f=>$v) {
		if($f=='mode' || $f=='order_num' || $f=='email_domain_custom') continue;
		switch($f) {
			case 'buyer_email':
				if(trim($row[$f][1])!="") {
					$record[$f] = implode('@', $v);
				}else{
					$record[$f] = $row[$f][0].'@'.$row['email_domain_custom'];
				}
				break;
			case 'buyer_mobile':
				$record[$f]= implode('-',$v);
				break;
			case 'buyer_tel':
				if(trim($row[$f][1])!=""){
					$record[$f]= implode('-',$v);
				}else{
					$record[$f]= "";
				}
				break;
			case 'receiver_mobile':
				$record[$f]= implode('-',$v);
				break;
			case 'receiver_tel':
				if(trim($row[$f][1])!=""){
					$record[$f]= implode('-',$v);
				}else{
					$record[$f]= "";
				}
				break;
			default:
				$record[$f] = $v;
				break;
		}

	}
	$sql = sqlUpdate($record, $tbl, $where);
	//pre($record);exit;
	$rs = $Order->adodb->Execute($sql);

	if($rs){
		return_json(true,'저장되었습니다.');
	}else{
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}
else if($mode == 'cancel') { //취소처리(주문완료단계)
	if($act == 'each') { //부분취소
		$_CLEAN = $Order->xss_post();
		// pre($_POST);exit;
		$success = true;
		$order_num = $_POST['order_num'];

		$basic_info = $Order->getBasicRow($order_num);
		$basic_record = array();
		
		$must_cancel = array();

		$Order->transBegin(); //트랜잭션 시작

		$coupon_basket_discount = str_replace(',','',$_POST['discount']['basket']);
		$basic_record['coupon_basket_discount'] = is_numeric($coupon_basket_discount)?$coupon_basket_discount:'0'; //장바구니 할인금액 재조정
		
		if(is_array($_POST['coupon'])) {
			$coupon = array_filter($_POST['coupon']); //복원요청 쿠폰목록 array(주문상품pk => 쿠폰이슈pk)

			if(is_array($coupon)) {
				//쿠폰 복원처리
				$Coupon = new COUPON;
				foreach($coupon as $product_idx => $ci_no) {
					if(!$ci_no) {
						unset($coupon[$product_idx]);
						continue;
					}
					$Coupon->restore($ci_no); //쿠폰미사용처리로 복원

					switch($product_idx) {
						case 'basket': //장바구니쿠폰 
							$basic_record['coupon_basket'] = '';
							$basic_record['coupon_basket_discount'] = '0';  //장바구니 쿠폰 복원시 할인금액 0으로 재조정
							break;
						case 'delivery': //무료배송쿠폰
							$basic_record['coupon_delivery'] = '';
							$basic_record['coupon_delivery_discount'] = '0'; 
							break;
						default: //상품쿠폰
							$must_cancel[] = $product_idx; //상품쿠폰복원요청이 있는경우 복원된 상품을 우선취소하기위해 배열에 쌓는다.
							break;
					}
				}
			}
		}
		else {
			$coupon = array();
		}

		//포인트 복원처리
		$refund_point = str_replace(',','',$_POST['refund']['point']);
		if($refund_point> 0) {
			$Point = new POINT;
			$rs_point = $Point->restore($order_num,$refund_point);
			if(!$rs_point) {
				$success = false;
				if($success) $error_msg = "포인트 복원실패";
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
				if($success) $error_msg = "마일리지 복원실패";
			} 

			$basic_record['use_mileage']=$basic_info['use_mileage']-$refund_mileage;
		}
		else $refund_mileage = 0;

		
		//상품별 취소상품 추출
		$checked = unserialize($_POST['checked']);
		$cancel_idx = array(); //취소주문상품pk
		foreach($checked as $v) {
			$product_arr = explode(',',$v['product']);
			$cnt = $v['count'];

			foreach($product_arr as $k=>$pidx) {
				if(in_array($pidx, $must_cancel)) {
					$cancel_idx[] = $pidx;
					$cnt--;
					unset($product_arr[$k]);
				}
			}
			if($cnt>0) {
				$cancel_idx = array_merge($cancel_idx,array_slice($product_arr, 0, $cnt));
			}
		}

		//환불금액정보입력
		
		$refund_product = $_POST['refund']['product']; //취소상품금액
		$pay_delivery = '';//환불배송비
		$refund_end = $refund_product - $refund_mileage - $refund_point; //주문완료단계에서의 취소처리이므로 실제 환불금액 없음 but 취소총액을 넣어주자
		
		$pay_method = $basic_info['pg_paymethod'];
		$record_refund = array(
			'order_num'=>$order_num,
			'price_total'=>$refund_end, //취소총액
			'price_product'=>$refund_product, //취소상품금액
			'price_delivery'=>0, //환불배송비
			'refund_method'=>'cancel', //환불수단없음(실제환불금액이 없음)
			'refund_mileage'=>$refund_mileage, //마일리지 환불액
			'refund_point'=>$refund_point, //포인트 환불액
			'cancel_coupon'=>implode(',',$coupon),
			'bank_info'=>'',//환불계좌
			'date_rr'=>NOW, //환불신청일
			'date_rc'=>NOW, //환불완료일
			'refund_status'=>'1' //환불완료
		);

		$tbl_or = $Order->tbls['order_refund'];
		$sql_refund = sqlInsert($record_refund, $tbl_or);
		$rs = $Order->adodb->Execute($sql_refund);
		if(!$rs) {
			if($success) $error_msg = "환불정보등록 실패";
			$success = false;
		}

		$refund_idx = $Order->adodb->insert_id(); //환불테이블 PK

		if($_POST['pay_delivery_add'] > 0) $basic_record['pay_delivery'] = $_POST['pay_delivery_add'];
		

		

		//취소정보 등록
		$reason = ($_POST['cancel_resaon']=='etc')?$_CLEAN['cancel_reason_etc']:$_CLEAN['cancel_resaon'];
		$record = array(
			'order_num'=>$order_num,
			'refund_idx'=>$refund_idx,
			// 'pay_delivery'=>str_replace(',','',$_POST['pay_delivery']),
			'reason'=>$reason,
			'memo'=>$_CLEAN['memo'],
			'reg_place'=>'admin',
			'reg_id'=>$_ShopInfo->id,
			'reg_ip'=>$_SERVER['REMOTE_ADDR'],
			'date_insert'=>NOW,
			'date_status_4'=>NOW,
			'date_update'=>NOW
		);
		$tbl_order_cancel = $Order->tbls['order_cancel'];
		$sql = sqlInsert($record, $tbl_order_cancel);

		$rs_cancel = $Order->adodb->Execute($sql);
		if(!$rs_cancel) {
			if($success) $error_msg = "취소정보 등록실패";
			$success = false;
		}
		$order_cancel_idx = $Order->adodb->insert_id();


		
		//상품별취소처리
		$tbl_op = $Order->tbls['order_product'];
		foreach($cancel_idx as $pidx) {
			$product_row = $Order->getProductRow($pidx);

			$record = array(
				'mileage_expect'=>0, //지급예정마일리지
				'cs_type'=>'C',
				'cs_status'=>'4', //즉시취소완료
				'cs_idx'=>$order_cancel_idx //취소데이터pk
			);

			if(in_array($pidx, $coupon)) { //쿠폰복원인경우 사용 취소처리
				$record['coupon_issue_no'] = '';
				$record['coupon_discount'] = '0';
			}

			$sql_product = sqlUpdate($record, $tbl_op, array('idx'=>$pidx));
			$rs_product = $Order->adodb->Execute($sql_product);
			if(!$rs_product) {
				if($success) $error_msg = "상품별 취소처리 실패";
				$success = false;
			}
			else {
				$record_log = array(
					'order_num'=>$order_num,
					'order_product_idx'=>$pidx,
					'type'=>'cs_status',
					'value'=>$product_row['order_status'].'C4',
					'value_pre'=>$product_row['order_status'].$product_row['cs_type'].$product_row['cs_status'],
					'msg'=>'관리자 > 주문상세'
				);
				$Order->_log($record_log);
			}
		}

		//주문서 정보 업데이트
		$rs = $Order->syncBasic($order_num, $basic_record);
		
		if(!$rs) {
			if($success) $error_msg = "주문서 정보 업데이트 실패";
			$success = false;
		}


		if($success) {
			$Order->transCommit();
			//주문취소메일 발송 시작
			$Mail = new MAIL();
			$Mail->send_mail('order_cancel_end',$order_cancel_idx);
			//주문취소메일 발송 끝
			return_json(true,'취소되었습니다.');
		}
		else {
			$Order->transRollback();
			return_json(false,$_ALERT['C003']."<br>".$error_msg);
		}
	}
	else if($act == 'list') { //목록선택취소(주문번호 일괄취소)
		$order_num = explode(',',$_POST['order_num']);
		$tbl_oc = $Order->tbls['order_cancel'];
		$success = true;
		foreach($order_num as $orn) {
			
			//취소데이터 입력
			$record = array(
				'order_num'=>$orn,
				'reason'=>'관리자 취소'
			);
			$sql = sqlInsert($record, $tbl_oc);
			$Order->adodb->Execute($sql);
			$cancel_idx = $Order->adodb->Insert_id();
			if($cancel_idx > 0) {
				$rs = $Order->cancel($orn, $cancel_idx);
				if(!$rs) {
					$success = false;
					$error_msg = $Order->error_msg;
					break;
				}
			}
			else {
				$error_msg = '취소데이터 입력실패';
				break;
			}
		}

		if($success) {
			//주문취소메일 발송 시작
			$Mail = new MAIL();
			$Mail->send_mail('order_cancel_end',$cancel_idx);
			//주문취소메일 발송 끝
			return_json(true,'취소처리되었습니다.');
		}
		else {
			return_json(false,$_ALERT['C003'].'<br>'.$error_msg);
		}
	}
}
else if($mode == 'refund') { //환불(결제완료step 취소)

	$refund_delivery = ($_POST['price']['delivery'])?($_POST['price']['delivery']):0;

	if($act == 'each') {
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
						$basic_record['coupon_basket'] = 0;
						$basic_record['coupon_basket_discount'] = 0;
					}
				}
				else if($k=='delivery') {//무료배송쿠폰 복원
					if($v > 0) $Coupon->restore($v); 
				}
			}
		}
		
		//포인트 복원처리
		$refund_point = str_replace(',','',$_POST['refund']['point']); //소멸+복원합계
		// $restore_point = $_POST['point']['restore']; //복원가능포인트
		if($refund_point> 0) {
			$Point = new POINT;
			$rs_point = $Point->restore($order_num,$refund_point);
			if(!$rs_point) {
				$success = false;
				if($success) $error_msg = "포인트 복원실패";
			}

			$basic_record['use_point']=$basic_info['use_point']-$refund_point;
		}
		else $refund_point = 0;
		

		//마일리지 복원처리
		$refund_mileage = str_replace(',','',$_POST['refund']['mileage']);//소멸+복원합계
		// $restore_mileage = $_POST['mileage']['restore']; //복원가능마일리지
		if($refund_mileage > 0) {
			$Mileage = new MILEAGE;
			$rs_mileage = $Mileage->restore($order_num,$refund_mileage);
			if(!$rs_mileage){
				$success = false;
				if($success) $error_msg = "마일리지 복원실패";
			} 

			$basic_record['use_mileage']=$basic_info['use_mileage']-$refund_mileage;
		}
		else $refund_mileage = 0;


		//환불정보 등록
		$bank_info = serialize($_POST['bank']); //환불계좌
		
		//환불금액정보
		$refund_field = array('cash','card','vcnt','acnt','mileage','point');
		$refund = array();
		foreach($refund_field as $f) {
			if($_POST['refund'][$f]) $refund[$f] = str_replace(',','',$_POST['refund'][$f]);
			else $refund[$f] = 0;
		}
		
		$record_refund = array(
			'order_num'=>$order_num,
			'price_total'=>$_POST['price']['total'], //환불총액
			'price_product'=>$_POST['price']['product'], //환불상품금액
			'price_delivery'=>$refund_delivery, //환불배송비
			
			'refund_method'=>$_POST['refund_method'], //환불수단
			'refund_mileage'=>$refund['mileage'], //마일리지 환불액
			'refund_point'=>$refund['point'], //포인트 환불액
			'refund_cash'=>$refund['cash'], //현금 환불액
			'refund_card'=>$refund['card'], //카드 환불액
			'refund_vcnt'=>$refund['vcnt'], //가상계좌 환불액
			'refund_acnt'=>$refund['acnt'], //실시간계좌이체 환불액
			'refund_status'=>'1', //환불완료

			'bank_info'=>$bank_info,//환불계좌
			'date_rr'=>NOW //환불신청일
		);
	
		$record_refund['date_rc'] = NOW; 

		$tbl_or = $Order->tbls['order_refund'];
		$sql_refund = sqlInsert($record_refund, $tbl_or);
		$rs = $Order->adodb->Execute($sql_refund);
		if(!$rs) {
			if($success) $error_msg = "환불정보등록 실패";
			$success = false;
		}

		$refund_idx = $Order->adodb->insert_id(); //환불테이블 PK

		//취소정보 접수
		$reg_place = 'admin';
		$reg_id = $_ShopInfo->id;

		$reason = ($_POST['refund_resaon']=='etc')?$_CLEAN['refund_reason_etc']:$_CLEAN['refund_resaon']; //취소사유
		$record_cancel = array(
			'order_num'=>$order_num,
			'reason'=>$reason,
			'memo'=>$_CLEAN['memo'],
			'refund_idx'=>$refund_idx,
			'valid_yn'=>'Y',
			'reg_place'=>$reg_place,
			'reg_id'=>$reg_id,
			'reg_ip'=>$_SERVER['REMOTE_ADDR'],
			'date_insert'=>NOW,//취소신청일
			'date_status_2'=>NOW, //취소접수일(관리자 환불신청은 신청과 동시에 접수처리함)
			'date_status_4'=>NOW, //취소완료일(관리자 환불신청은 신청과 동시에 완료처리함)
			'date_update'=>NOW
		);

		$tbl_oc = $Order->tbls['order_cancel'];
		$sql_cancel = sqlInsert($record_cancel, $tbl_oc);
		
		$rs = $Order->adodb->Execute($sql_cancel);
		if(!$rs) {
			if($success) $error_msg = "취소 정보등록 실패";
			$success = false;
		}

		$cancel_idx = $Order->adodb->insert_id();

		$cs_flag = 'RC'; //환불완료
		$cs_status = '4'; //취소완료


		//상품별 상태업데이트 (취소+접수+환불접수)
		$record_product = array(
			'cs_type'=>'C',
			'cs_status'=>$cs_status,
			'cs_flag'=>$cs_flag,
			'cs_idx'=>$cancel_idx,
			'coupon_issue_no'=>'',
			'coupon_discount'=>'0'
		);
		$tbl_op = $Order->tbls['order_product'];

		$product_idx_arr = explode(',',$_POST['product_idx']);
		foreach($product_idx_arr as $pidx) {
			$where = array('idx'=>$pidx);
			$sql = sqlUpdate($record_product, $tbl_op, $where);
			$rs = $Order->adodb->Execute($sql);
			if(!$rs) {
				if($success) $error_msg = "상품정보 업데이트 실패";
				$success = false;
				break;
			}
			else {
				$product_row = $Order->getProductRow($pidx);
				$record_log = array(
					'order_num'=>$order_num,
					'order_product_idx'=>$pidx,
					'type'=>'cs_status',
					'value'=>$product_row['order_status'].'C'.$cs_status,
					'value_pre'=>$product_row['order_status'].$product_row['cs_type'].$product_row['cs_status'],
					'msg'=>'관리자 > 주문상세'
				);
				$Order->_log($record_log); //취소로그기록
			}
		}

		//배송비를 환불할 경우
		if($refund_delivery > 0){
			$basic_record['pay_delivery'] = $basic_info['pay_delivery'] - $refund_delivery;
		}

		//주문서 기본정보 업데이트
		if($_POST['pay_delivery_add'] > 0) { //배송비추가인경우
			//$basic_record['pay_delivery'] = $basic_info['pay_delivery'] - str_replace(',','',$_POST['pay_delivery_add']);
			$basic_record['pay_delivery'] = str_replace(',','',$_POST['pay_delivery_add']);
		}

		$rs = $Order->syncBasic($order_num, $basic_record);
		if(!$rs) {
			if($success) $error_msg = "주문서 정보 업데이트 실패";
			$success = false;
		}

		if($success) {
			$Order->transCommit();
			return_json(true,'환불접수가 처리되었습니다.');
		}
		else {
			$Order->transRollback();
			return_json(false,$_ALERT['C003']."<br>".$error_msg);
		}
	}
}
else if($mode == 'return') { //반품(결제완료 이후 단계)
	// pre($_POST);exit;

	$order_num = $_POST['order_num'];
	$product = array_arrange($_POST['product']); //반품상품
	
	$success = true;
	$_CLEAN = $Order->xss_post();

	$Order->transBegin(); //트랜잭션시작

	//주문서 기본정보
	$basic_info = $Order->getBasicRow($order_num);

	//반품정보 접수
	$reg_place = 'admin';
	$reg_id = $_ShopInfo->id;
	$receiver_tel_arr = array_filter($_POST['receiver_tel']);
	if(empty($receiver_tel_arr)) $receiver_tel = '';
	else $receiver_tel = implode('-',$receiver_tel_arr);

	$record_return = array(
		'order_num'=>$order_num,
		'receiver_name'=>$_CLEAN['receiver_name'],
		'receiver_zipcode'=>$_CLEAN['receiver_zipcode'],
		'receiver_addr'=>$_CLEAN['receiver_addr'],
		'receiver_addr_detail'=>$_CLEAN['receiver_addr_detail'],
		'receiver_mobile'=>implode('-',$_CLEAN['receiver_mobile']),
		'receiver_tel'=>$receiver_tel,
		'memo'=>$_CLEAN['memo'],
		//'refund_idx'=>$refund_idx, //뒤에 업데이트
		'valid_yn'=>'Y',
		'reg_place'=>$reg_place,
		'reg_id'=>$reg_id,
		'reg_ip'=>$_SERVER['REMOTE_ADDR'],
		'date_status_1'=>NOW,//반품신청일
		'date_status_2'=>NOW, //반품접수일(관리자 반품신청은 신청과 동시에 접수처리함)
		'date_update'=>NOW
	);

	$tbl_return = $Order->tbls['order_return'];
	$sql_return = sqlInsert($record_return, $tbl_return);
	
	$rs = $Order->adodb->Execute($sql_return);
	if(!$rs) {
		
		$Order->error("반품 기본정보등록 실패");
		$success = false;
	}
	$return_idx = $Order->adodb->insert_id();

	//반품상품정보
	
	$tbl_rp = $Order->tbls['order_return_product']; //반품신청상품테이블

	//상품별 상태업데이트 (반품+접수+환불접수)

	$record_product = array(
		'cs_type'=>'R',
		'cs_status'=>'2',
		'cs_idx'=>$return_idx
	);
	$tbl_op = $Order->tbls['order_product']; //주문상품테이블

	$delivery_charger = 'buyer'; //반품책임자
	$return_total = 0; //반품상품총액
	foreach($product as $prow) {
		$pidx = $prow['idx'];
		$op_row = $Order->getProductRow($pidx); //반품상품정보

		$return_total+=$op_row['price_end'];

		//반품신청상품등록
		list($reason, $reason_charger) = explode('|', $prow['reason']);
		$record_rp = array(
			'order_num'=>$order_num,
			'order_product_idx'=>$pidx,
			'return_idx'=>$return_idx,
			'reason'=>$reason,
			'reason_etc'=>$prow['reason_etc'],
			'reason_charger'=>$reason_charger
		);

		if($reason_charger == 'seller') $delivery_charger = 'seller'; //판매자책임우선

		$sql_rp = sqlInsert($record_rp, $tbl_rp);
		$rs_rp = $Order->adodb->Execute($sql_rp);
		if(!$rs_rp) {
			$Order->error("반품 상품등록 실패");
			$success = false;
			break;
		}

		//반품주문상품업데이트
		$sql = sqlUpdate($record_product, $tbl_op, array('idx'=>$pidx, 'order_num'=>$order_num));
		$rs = $Order->adodb->Execute($sql);
		if(!$rs) {
			$Order->error("반품 상품정보 업데이트 실패");
			$success = false;
			break;
		}
		else {
			$record_log = array(
				'order_num'=>$order_num,
				'order_product_idx'=>$pidx,
				'type'=>'cs_status',
				'value'=>$op_row['order_status'].'R'.$record_product['cs_status'],
				'value_pre'=>$op_row['order_status'].$op_row['cs_type'].$op_row['cs_status'],
				'msg'=>'관리자 > 주문상세'
			);
			$Order->_log($record_log); //반품로그기록
		}
	}


	//반품환불금액 데이터 입력
	//배송비계산-S
	$Product = new PRODUCT;
	$cfg_delivery = $Product->getDeilvery(); //배송비설정
	$remain_total = $order_basic['pay_total'] - $return_total; //반품처리후 잔여 주문총액
	$paid_delivery = $order_basic['pay_delivery'] - $order_basic['coupon_delivery_discount']; //기 지불 배송비

	//지역별 배송비 계산
	$tbl = $Order->tbls['area_deli'];
	$zipcode = $_CLEAN['receiver_zipcode'];
	$row = $Order->adodb->getRow("SELECT * FROM {$tbl} WHERE '{$zipcode}' BETWEEN st_zipcode AND en_zipcode ORDER BY deli_price DESC");
	if($row) {
		$calc_deliprice = $row['deli_price'];
	}
	else {
		$calc_deliprice = $cfg_delivery['deli_basefee'];
	}

	if($delivery_charger == 'buyer') { //구매자 귀책사유
		if($paid_delivery > 0) {
			$pay_delivery = $calc_deliprice*2;
		}
		else {
			$deli_miniprice = ($order_basic['pr_type']=='1')?$cfg_delivery['deli_miniprice']:$cfg_delivery['deli_miniprice_staff'];;
			if($remain_total >= $deli_miniprice) {
				$pay_delivery = $calc_deliprice;
			}
			else {
				$pay_delivery = $calc_deliprice*2;
			}
		}
	}
	else {
		$pay_delivery = '0';
	}

	$record_refund = array(
		'order_num'=>$order_num,
		'price_total'=>$return_total, //환불총액
		'price_product'=>$return_total, //환불상품금액
		'price_delivery'=>'0', //환불배송비
		'refund_method'=>$order_basic['pg_paymenthod'], //환불수단
		'refund_mileage'=>0, //마일리지 환불액
		'refund_point'=>0, //포인트 환불액
		'refund_cash'=>0, //현금 환불액
		'refund_status'=>'0', //대기상태
		'pay_delivery'=>$pay_delivery
	);

	$tbl_refund = $Order->tbls['order_refund'];
	$sql_refund = sqlInsert($record_refund, $tbl_refund);
	$rs = $Order->adodb->Execute($sql_refund);
	if(!$rs) {
		$Order->error("환불정보등록 실패");
		$success = false;
	}

	$refund_idx = $Order->adodb->insert_id(); //환불테이블 PK

	//환불키 업데이트
	$sql_return_update = sqlUpdate(array('refund_idx'=>$refund_idx), $tbl_return, array('idx'=>$return_idx));
	$rs = $Order->adodb->Execute($sql_return_update);
	if(!$rs) {
		$Order->error("반품정보 환불 키 업데이트 실패");
		$success = false;
	}


	//사은품회수/배송취소  @todo
	if($_POST['gift']) {
		foreach($_POST['gift'] as $order_gift_no) {

		}
	}


	if($success) {
		$Order->transCommit();
		//반품메일 발송 시작
		$Mail = new MAIL();
		$Mail->send_mail('refund',$return_idx);
		//반품메일 발송 끝
		return_json(true,'반품이 접수되었습니다.');
	}
	else {
		$Order->transRollback();
		return_json(false,$_ALERT['C003']."<br>".$Order->err_msg);
	}
	
}
else if($mode == 'exchange') { //교환
	if($act == 'batch') { //일괄처리

		// pre($_POST);EXIT;
		$success = true;
		$product_idx = $_POST['checked_exchange'];

		$error_msg = $_ALERT['C003'];
		if($_POST['exchange_status'] > 0) { //처리상태 변경시
			$order_status = $_POST['exchange_status'];
			$rs = $Order->changeOrderStatus($product_idx,$order_status);
			if(!$rs) {
				$success = false;
				$error_msg = $Order->err_msg;
			}
		}

		if($_POST['delivery_company'] && $_POST['delivery_no']) { //택배사정보 변경시
			$record = array(
				'delivery_company'=>$_POST['delivery_company'],
				'delivery_no'=>str_replace("-","",$_POST['delivery_no'])
			);

			foreach($product_idx as $idx) {
				$where = array('idx'=>$idx);
				$rs = $Order->updateProduct($record, $where);
				if(!$rs) $success = false;
			}
		}

		if($success) {
			return_json(true,'적용되었습니다.');
		}
		else {
			return_json(false,$error_msg);
		}

	}
	else { //교환접수처리
		$order_num = $_POST['order_num'];

		$success = true;
		$_CLEAN = $Order->xss_post();

		$Order->transBegin(); //트랜잭션시작
		$basic_info = $Order->getBasicRow($order_num); //주문서 기본정보

		//교환은 환불데이터 없음
		
		//교환정보 접수
		$reg_place = 'admin';
		$reg_id = $_ShopInfo->id;
		$receiver_tel_arr = array_filter($_POST['receiver_tel']);
		if(empty($receiver_tel_arr)) $receiver_tel = '';
		else $receiver_tel = implode('-',$receiver_tel_arr);

		$record_exchange = array(
			'order_num'=>$order_num,
			'receiver_name'=>$_CLEAN['receiver_name'],
			'receiver_zipcode'=>$_CLEAN['receiver_zipcode'],
			'receiver_addr'=>$_CLEAN['receiver_addr'],
			'receiver_addr_detail'=>$_CLEAN['receiver_addr_detail'],
			'receiver_mobile'=>implode('-',$_CLEAN['receiver_mobile']),
			'receiver_tel'=>$receiver_tel,
			'memo'=>$_CLEAN['memo'],
			'delivery_pay'=>str_replace(',','',$_CLEAN['delivery_pay']),
			'delivery_pay_method'=>$_CLEAN['delivery_pay_method'],
			'valid_yn'=>'Y',
			'reg_place'=>$reg_place,
			'reg_id'=>$reg_id,
			'reg_ip'=>$_SERVER['REMOTE_ADDR'],
			'date_status_1'=>NOW,//교환신청일
			'date_status_2'=>NOW, //교환접수일(관리자 반품신청은 신청과 동시에 접수처리함)
			'date_update'=>NOW
		);

		$tbl_exchange = $Order->tbls['order_exchange'];
		$sql_exchange = sqlInsert($record_exchange, $tbl_exchange);
		
		$rs = $Order->adodb->Execute($sql_exchange);
		if(!$rs) {
			$Order->error("교환 기본정보등록 실패");
			$success = false;
		}
		$exchange_idx = $Order->adodb->insert_id();

		//교환상품정보
		$product = array_arrange($_POST['product']);
		$tbl_ep = $Order->tbls['order_exchange_product']; //교환신청상품테이블

		//상품별 상태업데이트 (교환+접수)
		$record_product = array(
			'cs_type'=>'E',
			'cs_status'=>'2',
			'cs_idx'=>$exchange_idx
		);
		$tbl_op = $Order->tbls['order_product']; //주문상품테이블

		foreach($product as $prow) {
			$pidx = $prow['idx'];
			$product_row = $Order->getProductRow($pidx);
			//교환신청상품등록
			list($reason, $reason_charger) = explode('|', $prow['reason']);
			$record_ep = array(
				'order_num'=>$order_num,
				'order_product_idx'=>$pidx,
				'exchange_idx'=>$exchange_idx,
				'reason'=>$reason,
				'reason_etc'=>$prow['reason_etc'],
				'reason_charger'=>$reason_charger,
				'exchange_option_code'=>$prow['option'],
				'date_status_1'=>NOW,
				'date_status_2'=>NOW

			);
			$sql_ep = sqlInsert($record_ep, $tbl_ep);
			$rs_ep = $Order->adodb->Execute($sql_ep);
			if(!$rs_ep) {
				$Order->error("교환 상품등록 실패");
				$success = false;
				break;
			}

			//교환주문상품업데이트
			$sql = sqlUpdate($record_product, $tbl_op, array('idx'=>$pidx));
			$rs = $Order->adodb->Execute($sql);
			if(!$rs) {
				$Order->error("교환 상품정보 업데이트 실패");
				$success = false;
				break;
			}
			else {
				
				$record_log = array(
					'order_num'=>$order_num,
					'order_product_idx'=>$pidx,
					'type'=>'cs_status',
					'value'=>$product_row['order_status'].'E2',
					'value_pre'=>$product_row['order_status'].$product_row['cs_type'].$product_row['cs_status'],
					'msg'=>'관리자 > 주문상세'
				);
				$Order->_log($record_log); //교환로그기록
			}
		}

		if($success) {
			$Order->transCommit();
			//교환메일 발송 시작
			$Mail = new MAIL();
			$Mail->send_mail('exchange',$exchange_idx);
			//교환메일 발송 끝
			return_json(true,'교환이 접수되었습니다.');
		}
		else {
			$Order->transRollback();
			return_json(false,$_ALERT['C003']."<br>".$Order->err_msg);
		}
	}
	
}
else if($mode == 'memo') {
	$_CLEAN = $Order->xss_post();
	$order_num = $_CLEAN['order_num'];
	$record = array(
		'order_num'=>$_CLEAN['order_num'],
		'memo'=>$_CLEAN['admin_memo']
	);
	$rs = $Order->saveMemo($order_num,$record);
	if($rs) {
		return_json(true,'저장되었습니다.');
	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}
else if($mode == 'gift'){
	$tbl_order_gift = $Order->tbls['order_gift'];//주문사은품테이블
	$idx_arr = explode(',',$_POST['idx']);

	$gift_status = $_POST['gift_status'];
	$order_num = $_POST['order_num'];
	$success = true;
	foreach($idx_arr as $idx) {
		if($gift_status=='0' || $gift_status=='3'){
			$rs = $Order->giftMinusStock($idx,$order_num,$gift_status);
		}
		else if($gift_status=='1' ){
			$rs = $Order->giftStock($idx,$order_num,$gift_status);
		}else if($gift_status=='2'){
			$sql_order_gift = "UPDATE {$tbl_order_gift} SET status = '{$gift_status}', date_update='".NOW."' WHERE idx='{$idx}'";
			$rs = $Order->adodb->Execute($sql_order_gift);
		}

		if(!$rs) {
			$success = false;
			break;
		}
	}

	if($success) {
		return_json(true,'처리되었습니다.');
	}
	else {
		return_json(false,$_ALERT['C003']);
	}
}