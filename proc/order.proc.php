<?php
/**
 * 주문 프로세싱
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


$Order = new ORDER;//장바구니클래스
$Product = new PRODUCT; //상품클래스

$mode = $_POST['mode'];
$act = $_POST['act'];

$argu = $Order->xss_post();


if($mode == 'order') {
	if($act == 'temp') { //결제버튼 클릭시 주문서 등록(order_status=0:주문대기상태)

		//주문서유효시간체크(분)
		if(!$Order->validOrder()) {
			return_json(false,'주문서 유효시간이 초과되었습니다.<br>다시 작성해주세요.', array());
		}

		$member_id = MEMID;
		$guest_id = (MEMID)?'':session_id(); //비회원용 세션ID
		$order_num = $Order->getOrderNum(); //주문번호 생성


		$order_num_temp = $argu['order_num_temp']; //임시주문번호

		$pay_pg = 0; //pg결제금액
		$pay_total = 0;
		$success = true; //처리상태
		$failure_msg = '잠시 후에 다시 시도해주세요.';

		$Coupon = new COUPON;

		//장바구니쿠폰 유효성체크
		if($argu['coupon_basket']) {
			$valid_coupon_basket = $Coupon->getValid(MEMID, $argu['coupon_basket']);
			if(!$valid_coupon_basket) {
				return_json(false,'사용할 수 없는 장바구니쿠폰입니다.');
			}
		}
		
		//무료배송쿠폰 사용여부체크
		if($argu['coupon_delivery']) {
			$valid_coupon_delivery = $Coupon->getValid(MEMID, $argu['coupon_delivery']);
			if(!$valid_coupon_delivery) {
				return_json(false,'사용할 수 없는 무료배송쿠폰입니다.');
			}
		}

		if(MEMID) {
			$Member = new MEMBER; //회원클래스인스턴스
			$member_info = $Member->getMemberRow(MEMID);
			$use_point = ($argu['use_point'])?str_replace(',','',$argu['use_point']):'0';
			$use_mileage = ($argu['use_mileage'])?str_replace(',','',$argu['use_mileage']):'0';

			//사용가능포인트검증
			if($use_point>0 && $use_point > $member_info['act_point']) {
				return_json(false,'사용가능 포인트를 초과사용하였습니다.');
			}

			//사용가능 마일리지 체크
			if($use_mileage>0 && $use_mileage > $member_info['reserve']) {
				return_json(false,'사용가능 마일리지를 초과사용하였습니다.');
			}
		}
		else {
			$use_point = $use_mileage = 0;
		}

		$Order->transBegin();//트랜잭션 시작

		//기본주문서 등록
		$buyer_email = (is_array($argu['buyer_email']))?implode('@',$argu['buyer_email']):$argu['buyer_email']; //주문자이메일
		$buyer_mobile = (is_array($argu['buyer_mobile']))?implode('-',$argu['buyer_mobile']):$argu['buyer_mobile']; //주문자휴대폰번호
		$buyer_tel = (is_array($argu['buyer_tel']))?implode('-',$argu['buyer_tel']):$argu['buyer_tel']; //주문자이메일

		if(!MEMID && $argu['bank_code']) {
			$refund_account = serialize(array(
				'bank'=>$argu['bank_code'],
				'account'=>$argu['account_num'],
				'owner'=>$argu['depositor']
			));
		}
		else $refund_account = '';

		$record_basic = array(
			'order_num'=>$order_num,//주문번호
			'order_group'=>$order_num,//주문그룹
			'order_title'=>$argu['order_title'], //주문서타이틀

			'member_id'=>MEMID, //주문자 회원 아이디
			'guest_id'=>$guest_id, //주문자 아이디(비회원인경우 세션, 회원인경우 공백)

			'buyer_name'=>$argu['buyer_name'],
			'buyer_email'=>$buyer_email,
			'buyer_mobile'=>$buyer_mobile,
			'buyer_tel'=>$buyer_tel,
			'buyer_ip'=>$_SERVER['REMOTE_ADDR'], //구매자아이피
			'buyer_viewport'=>VIEWPORT, //구매위치(PC/MOBILE)

			'receiver_name'=>$argu['receiver_name'],
			'receiver_zipcode'=>$argu['receiver_zipcode'],
			'receiver_addr'=>$argu['receiver_addr'],
			'receiver_addr_detail'=>$argu['receiver_addr_detail'],
			'receiver_mobile'=>implode('-',$argu['receiver_mobile']),
			'receiver_tel'=>implode('-',$argu['receiver_tel']),
			'receiver_memo'=>($argu['receiver_memo']=='etc')?$argu['receiver_memo_etc']:$argu['receiver_memo'],

			'sum_consumer'=>$argu['sum_consumer'], //상품정상가 합계
			'sum_discount'=>$argu['sum_discount'], //상품할인금액 합계
			'sum_end'=>$argu['sum_end'], //상품최종판매가 합계
			'sum_mileage'=>$argu['sum_mileage'], //지급예정마일리지 합계

			'pr_type'=>$argu['pr_type']
		);
		$sql_basic = sqlInsert($record_basic, $Order->tbls['order']);
		$rs = $Order->adodb->Execute($sql_basic);
		if(!$rs) {
			echo $sql_basic;
			$Order->error('기본주문서 등록 실패');
			$success = false;
		}
	
		$temp_list = $Order->getTempList($order_num_temp); //임시주문상품목록
		foreach($temp_list as $row) {
		
			//상품별쿠폰사용가능여부체크
			$valid_coupon = true; //$Coupon->getValid($row['coupon_issue_no']);
			if(!$valid_coupon) {
				$success = false;
				$failure_msg = '사용불가 쿠폰이 적용되어 결제가 중단됩니다.';
				break;
			}

			$record_product = array(
				'order_num'=>$order_num, //주문번호
				'order_status'=>'0', //주문대기상태
				'cs_type'=>'0', //CS구분(0:대기)
				'cs_status'=>'0', //CS상태(0:대기)
				'productcode'=>$row['productcode'], //상품코드
				'option_type'=>$row['option_type'], //옵션유형(option:옵션상품, product:추가구매상품)
				'option_code'=>$row['option_code'], //옵션유형에 따른 코드
				'pr_type'=>$row['pr_type'], //상품유형(클래스변수참고)
				'price_consumer'=>$row['price_consumer'], //정상가
				'price_sell'=>$row['price_sell'], //할인가
				'price_end'=>$row['price_end'], //상품별최종판매가(기간할인적용금액)
				'mileage_expect'=>$row['mileage_expect'], //적립예정마일리지
				'coupon_issue_no'=>$row['coupon_issue_no'], //사용한 쿠폰번호
				'coupon_discount'=>$row['coupon_discount'], //쿠폰할인금액
				'date_insert'=>NOW,
				'date_update'=>NOW
			);

			$sql_product = sqlInsert($record_product, $Order->tbls['order_product']);
			$rs = $Order->adodb->Execute($sql_product);
			// $order_product_idx = $Order->adodb->insert_id();
			if(!$rs) {
				$success = false;
				break;
			}

			//장바구니 업데이트
			$sql_basket = sqlUpdate(array('order_num'=>$order_num), $Order->tbls['basket'], array('group_no'=>$row['basket_group_no']));
			$Order->adodb->Execute($sql_basket);


			$pay_total += $row['price_end']; //상품총합
			$pay_pg+=($row['price_end']-$row['coupon_discount']); //상품최종판매가에서 상품별 쿠폰할인금액 제외처리
		}

		//사은품등록
		$gift_no_arr = $argu['gift_no'];
		if(is_array($gift_no_arr)) {
			$Gift = new GIFT;
			$tbl_gift = $Order->tbls['order_gift'];
			foreach($gift_no_arr as $gift_no) {
				// pre($gift_no);
				$gift_info = $Gift->getGiftRow($gift_no,'giftcode,giftname,price_s,price_e,gift_comment');
				$record_gift = array(
					'order_num'=>$order_num,
					'gift_idx'=>$gift_no,
					'gift_info'=>serialize($gift_info),
					'date_insert'=>NOW,
					'date_update'=>NOW
				);

				$gift_sql = sqlInsert($record_gift, $tbl_gift);
				$Order->adodb->Execute($gift_sql);
			}
		}

		//결제금액 - 포인트사용액
		$pay_pg -= $use_point;

		//결제금액 - 마일리지사용액
		$pay_pg -= $use_mileage;

		//결제금액 - 장바구니쿠폰할인액
		$pay_pg -= $argu['coupon_basket_discount'];

		//배송비 = 기본배송비 - 무료배송쿠폰할인액
		$pay_delivery = $argu['pay_delivery'] - $argu['coupon_delivery_discount']; //배송비
		
		if($pay_delivery > 0) {
			$pay_pg += $pay_delivery; //결제금액에 배송비 합산
			$pay_total += $pay_delivery; //결제금액에 배송비 합산
		}

		//pre($argu);

		if(!$success) { //주문서상품등록 실패시
			$Order->transRollback();
			return_json(false, $failure_msg);
		}

		if($pay_pg != $argu['pay_total']) {
			$Order->transRollback();
			$failure_msg = '결제금액 오류로 결제를 중단합니다.'.$pay_pg.' = '.$argu['pay_total'];
			
			return_json(false, $failure_msg);
		}

		

		

		//기본주문서 수정
		$record = array(

			'pay_total'=>$pay_total,//총결제금액(endprice 합계 + 배송비)
			'pay_pg'=>$pay_pg,//pg사 결제금액(결제금액에서 포인트,마일리지사용금액을 제외한 금액, 실제 pg연동을 통해 결제한 금액)
			'pay_delivery'=>$argu['pay_delivery'], //배송비

			'use_point'=>$use_point,//사용포인트
			'use_mileage'=>$use_mileage,//사용마일리지

			'coupon_basket'=>$argu['coupon_basket'], //장바구니쿠폰 적용 할인금액
			'coupon_basket_discount'=>$argu['coupon_basket_discount'], //무료배송쿠폰 적용 할인금액
			
			'coupon_delivery'=>$argu['coupon_delivery'], //무료배송쿠폰번호
			'coupon_delivery_discount'=>$argu['coupon_delivery_discount'], //장바구니쿠폰번호

			'coupon_product_discount'=>$argu['coupon_product_discount'], //상품쿠폰 적용 할인금액

			'order_status'=>'0', //주문상태(0:주문대기, 1:주문완료)

			'pg'=>PG, //결제PG사
			'pg_paymethod'=>$argu['pg_paymethod'], //PG결제수단


			'refund_account'=>$refund_account, //비회원인경우 환불계좌
			'date_insert'=>NOW,
			'date_update'=>NOW
		);


		//주문서 등록
		$sql_basic_update = sqlUpdate($record, $Order->tbls['order'], array('order_num'=>$order_num));
		$rs = $Order->adodb->Execute($sql_basic_update);

		if($rs) {
			$Order->transCommit();
		
			return_json(true, '', array('return_url'=>'/order/order.pay.php','order_num'=>$order_num));
		}
		else {
			$Order->transRollback();
			return_json(false, '잠시 후에 다시 시도해주세요#1');
		}

	}
	else if($act == 'real') {

	}
}
else if($mode == 'address') {
	if($act == 'lastest') { //최근배송지
		$Order = new ORDER;
		$last_order = $Order->getBasicList("order_status!='0'",1);
		if($last_order) {
			$row = $last_order[0];
			//  pre($row);
			
			$mobile_arr = explode('-',$row['receiver_mobile']);
			$tel_arr = explode('-',$row['receiver_tel']);

			$address = array(
				'receiver_name'=>$row['receiver_name'],
				'receiver_zipcode'=>$row['receiver_zipcode'],
				'receiver_addr'=>$row['receiver_addr'],
				'receiver_addr_detail'=>$row['receiver_addr_detail'],
				'receiver_mobile[0]'=>$mobile_arr[0],
				'receiver_mobile[1]'=>$mobile_arr[1],
				'receiver_mobile[2]'=>$mobile_arr[2],
				'receiver_tel[0]'=>$tel_arr[0],
				'receiver_tel[1]'=>$tel_arr[1],
				'receiver_tel[2]'=>$tel_arr[2]
			);

			// pre($address);
			return_json(true,'', array('address'=>$address));
		}
		else {
			return_json(false,'주문내역이 없습니다.');
		}
	}
	else if($act == 'member') {
		$Member = new MEMBER;
		$member_info = $Member->getMemberRow(MEMID);
		// pre($member_info);
		$address = array(
			'receiver_name'=>$member_info['name'],
			'receiver_zipcode'=>$member_info['home_post'],
			'receiver_addr'=>$member_info['home_addr'],
			'receiver_addr_detail'=>$member_info['home_addr_detail'],
			'receiver_mobile[0]'=>$member_info['mobile_arr'][0],
			'receiver_mobile[1]'=>$member_info['mobile_arr'][1],
			'receiver_mobile[2]'=>$member_info['mobile_arr'][2],
			'receiver_tel[0]'=>$member_info['home_tel_arr'][0],
			'receiver_tel[1]'=>$member_info['home_tel_arr'][1],
			'receiver_tel[2]'=>$member_info['home_tel_arr'][2]
		);

		return_json(true,'', array('address'=>$address));
	}
	else if($act == 'delivery') { //내배송지목록
		$row = $Order->getDestinationRow($_POST['no']);
		$mobile_arr = explode('-',$row['mobile']);
		$address = array(
			'receiver_name'=>$row['get_name'],
			'receiver_zipcode'=>$row['postcode_new'],
			'receiver_addr'=>$row['addr1'],
			'receiver_addr_detail'=>$row['addr2'],
			'receiver_mobile[0]'=>$mobile_arr[0],
			'receiver_mobile[1]'=>$mobile_arr[1],
			'receiver_mobile[2]'=>$mobile_arr[2],
			'receiver_tel[0]'=>'',
			'receiver_tel[1]'=>'',
			'receiver_tel[2]'=>''
		);

		return_json(true,'', array('address'=>$address));
	}
	else if($act == 'fee') {
		$tbl = $Order->tbls['area_deli'];
		$zipcode = $_POST['zipcode'];
		$row = $Order->adodb->getRow("SELECT * FROM {$tbl} WHERE '{$zipcode}' BETWEEN st_zipcode AND en_zipcode ORDER BY deli_price DESC");
		if($row) {
			return_json(true,'',array('fee'=>$row['deli_price']));
		}
		else {
			return_json(false);
		}
	}
}
else if ($mode == 'coupon') {
	$Coupon = new COUPON;
	if($act == 'touse') { //쿠폰임시 사용처리

		$sum_discount = 0;
	
		$success = true;
		$tbl = $Order->tbls['order_temp'];
		
		//상품쿠폰
		$product_coupon = $_POST['product_coupon'];
		if(is_array($product_coupon)) {
			foreach($product_coupon as $temp_no=>$coupon_issue_no) {
				if($coupon_issue_no) {
					//쿠폰할인금액 계산(쿠폰발급번호만 전송받아 할인금액 재계산한다)
					$coupon_info = $Coupon->getIssueCouponRow($coupon_issue_no);
					if($coupon_info['sale_type'] == 'K') $coupon_discount = $coupon_info['sale_price']; //고정할인
					else {
						$temp_info = $Order->getTempRow($temp_no);
						$coupon_discount = $temp_info['price_end']*$coupon_info['sale_price']/100;
					}
				}
				else {
					$coupon_discount = 0; //쿠폰할인금액 리셋
				}

				$record = array(
					'coupon_issue_no'=>$coupon_issue_no,
					'coupon_discount'=>$coupon_discount,
				);
				$where = array('no'=>$temp_no);
				$sql = sqlUpdate($record, $tbl, $where);
				// echo $sql;
				$rs = $Order->adodb->Execute($sql);
				if(!$rs) $success = false;

				$sum_discount+=$coupon_discount;

				
			}

			$data['product'] = array(
				'discount'=>$sum_discount
			);
		}

		//장바구니쿠폰
		if($sum_discount == 0) { //상품쿠폰이 적용되지 않은 상태여야함
			if($argu['coupon_basket']>0) {
				//총주문금액
				$order_num_temp = $_POST['order_num_temp'];
				$sum = $Order->adodb->getOne("SELECT SUM(price_end) FROM {$tbl} WHERE order_num_temp='{$order_num_temp}'");
				
				//쿠폰정보
				$coupon_info = $Coupon->getIssueCouponRow($argu['coupon_basket']);
			
				if($coupon_info['sale_type'] == 'K') $coupon_discount = $coupon_info['sale_price']; //고정할인
				else {
					$coupon_discount = $sum*$coupon_info['sale_price']/100;
					if($coupon_info['sale_max_price'] > 0 && $coupon_discount > $coupon_info['sale_max_price']) { //최대할인금액 체크
						$coupon_discount = $coupon_info['sale_max_price'];
					}
				}

				$data['basket'] = array(
					'coupon_issue_no'=>$argu['coupon_basket'],
					'discount'=>$coupon_discount
				);
			}
		}

		//무료배송쿠폰
		if($argu['coupon_delivery'] > 0) {
			//총주문금액
			$order_num_temp = $_POST['order_num_temp'];
			$sum = $Order->adodb->getOne("SELECT SUM(price_end) FROM {$tbl} WHERE order_num_temp='{$order_num_temp}'");

			$Product = new PRODUCT;
			$delivery = $Product->getDeilvery(); //배송비설정
			if($delivery['deli_miniprice'] > $sum) {
				$coupon_discount = $delivery['deli_basefee'];
				$coupon_issue_no = $argu['coupon_delivery'];
			}
			

			$data['delivery'] = array(
				'coupon_issue_no'=>$argu['coupon_delivery'],
				'discount'=>$coupon_discount
			);
		}

		if($success) {
			return_json(true,'', $data);
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}
	else if($act == 'reset') { //상품쿠폰 리셋
		$order_num_temp = $Order->Dectypt_AES128CBC($_POST['toid']);
		$record = array(
			'coupon_issue_no'=>'',
			'coupon_discount'=>0
		);
		$where = array('order_num_temp'=>$order_num_temp);
		$tbl = $Order->tbls['order_temp'];
		$sql = sqlUpdate($record, $tbl, $where);
		$rs = $Order->adodb->Execute($sql);
		if($rs) {
			return_json(true, '');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
		

	}
}else if($mode=='order_cancel'){
	$Order = new ORDER;
	$oid = $Order->Enctypt_AES128CBC($argu['order_num']);
	$_CLEAN = $Order->xss_post();
	$record = array(
		'order_num'=>$argu['order_num'],
		'reason'=>($argu['cancel_reason']=='etc')?$_CLEAN['cancel_reason_etc']:$_CLEAN['cancel_reason'],
		'date_insert'=>NOW,
		'date_update'=>NOW
	);

	$tbl_order_cancel = $Order->tbls['order_cancel'];
	$sql = sqlInsert($record, $tbl_order_cancel);
	//pre($sql);exit;
	$rs_cancel = $Order->adodb->Execute($sql);
	if(!$rs_cancel) $success = false;
	$cancel_idx = $Order->adodb->insert_id();

	if($rs_cancel) {
		$rs = $Order->cancel($argu['order_num'], $cancel_idx); //주문취소

		//@TODO 환불로그쌓기
		/*
		if($argu['order_status'] < 2){
			$rs = $Order->cancel($argu['order_num'], $cancel_idx); //주문취소
		}else{
			$rs = $Order->refund($order_num, $cancel_idx); //환불
		}
		*/

		if($rs){
			//주문취소메일 발송 시작
			$Mail = new MAIL();
			$Mail->send_mail('order_cancel_end',$cancel_idx);
			//주문취소메일 발송 끝
			return_json(true, $oid);
		}else{
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}

}
else if($mode=='order_return') { //반품처리

	
	$Order = new ORDER;

	$Order->transBegin();

	try {

		$tbl_r = $Order->tbls['order_return'];
		$product = array_arrange($_POST['product']);
		$order_num = $_POST['order_num'];

		$success = true;
		$basic = $Order->getBasicRow($order_num);

		//반품환불금액 데이터 입력
		$pay_method = $basic['pg_paymethod'];
		$bank_info = (is_array($_POST['bank']))?serialize($_POST['bank']):''; //환불계좌정보

		$record_refund = array(
			'order_num'=>$order_num,
			'price_total'=>$_POST['refund']['end'], //환불총액
			'price_product'=>$_POST['refund']['product'], //환불상품금액
			'price_delivery'=>($basic['pay_delivery']-$basic['coupon_delivery_discount']), //환불배송비
			'refund_method'=>$basic['pg_paymethod'], //환불수단
			'refund_mileage'=>$_POST['refund']['mileage'], //마일리지 환불액
			'refund_point'=>$_POST['refund']['point'], //포인트 환불액
			'refund_cash'=>0, //현금 환불액
			'refund_'.$pay_method=>$_POST['refund']['end'], //결제수단별 환불액
			'bank_info'=>$bank_info,//환불계좌
			'pay_delivery'=>$_POST['pay_delivery'],
			'cancel_mileage'=>$_POST['cancel']['mileage'], //적립취소 마일리지
			'refund_status'=>'0' //대기상태, 반품접수상태가 되어야 환불신청이 접수됨
		);
		

		$tbl_refund = $Order->tbls['order_refund'];
		$sql_refund = sqlInsert($record_refund, $tbl_refund);
		$rs = $Order->adodb->Execute($sql_refund);
		if(!$rs) {
			$Order->error("환불정보등록 실패");
			// echo $sql_refund;
			$success = false;
		}




		$refund_idx = $Order->adodb->insert_id(); //환불테이블 PK

		//반품신청데이터 입력
		$receiver_mobile = implode("-",$_POST['receiver_mobile']);
		$receiver_tel = implode("-",$_POST['receiver_tel']);

		if(strlen($receiver_tel) < 10){
			$receiver_tel ="";
		}

		$reg_place = strtolower(VIEWPORT);
		$reg_id = MEMID?MEMID:'GUEST';

		$record = array(
			'order_num'=>$order_num,
			'receiver_name'=>$argu['receiver_name'],
			'receiver_zipcode'=>$argu['receiver_zipcode'],
			'receiver_addr'=>$argu['receiver_addr'],
			'receiver_addr_detail'=>$argu['receiver_addr_detail'],
			'receiver_mobile'=>$receiver_mobile,
			'receiver_tel'=>$receiver_tel,
			'reg_place'=>$reg_place,
			'reg_id'=>$reg_id,
			'reg_ip'=>$_SERVER['REMOTE_ADDR'],
			'valid_yn'=>'Y',
			'refund_idx'=>$refund_idx,
			'date_status_1'=>NOW, //반품신청일
			'date_update'=>NOW
		);

		$sql = sqlInsert($record, $tbl_r);
		
		$rs = $Order->adodb->Execute($sql);


		if(!$rs) {
			$Order->error("반품 기본정보 입력실패");
			$success = false;
		}


		

		//반품상품정보 입력
		$return_idx = $Order->adodb->insert_id(); //반품키
		$tbl_rp = $Order->tbls['order_return_product']; //반품상품테이블
		$tbl_op = $Order->tbls['order_product']; //주문상품테이블
		foreach($product as $v) {
			
			list($reason, $charger) = explode('|',$v['reason']);
			$reason_etc = pg_escape_string($v['reason_etc']);

			$product_rs = $Order->adodb->Execute("SELECT * FROM {$tbl_op} WHERE idx IN(".$v['checked'].") ORDER BY coupon_issue_no ASC OFFSET 0 LIMIT ".$v['count']); //쿠폰사용안한 구매목록부터 반품

			while($row = $product_rs->FetchRow()) {
				$record_rp = array(
					'order_num'=>$order_num,
					'return_idx'=>$return_idx,
					'order_product_idx'=>$row['idx'],
					'reason'=>$reason,
					'reason_etc'=>$reason_etc,
					'reason_charger'=>$charger,
					'date_status_1'=>NOW //반품신청일
				);



				
				$sql_rp = sqlInsert($record_rp, $tbl_rp);
				$rs_rp = $Order->adodb->Execute($sql_rp);
				if($rs_rp) {
					
					//상품상태변경
					$sql_op = sqlUpdate(array('cs_type'=>'R','cs_status'=>'1', 'cs_idx'=>$return_idx), $tbl_op, array('idx'=>$row['idx']));
					$rs_op = $Order->adodb->Execute($sql_op);
					if(!$rs_op) {
						$Order->error("상품정보 상태 업데이트실패");
						$success= false;
						break;
					}
				}
				else {
					echo $Order->err_msg;
					$Order->error("반품 상품정보 입력실패");
					$success= false;
					break;
				}
			}
		}


	
		if($success) {
			$Order->transCommit();
			//반품메일 발송 시작
			$Mail = new MAIL();
			$Mail->send_mail('refund',$return_idx);
			//반품메일 발송 끝
			$oid = $Order->Enctypt_AES128CBC($argu['order_num']);
			return_json(true,'', array('oid'=>$oid));
		}
		else {
			$Order->transRollback();
			return_json(false,$_ALERT['C003'].'<br>'.$Order->err_msg);
		}
	}
	catch (Exception $e){
		$Order->transRollback();
		$Order->error($e->getMessage());
		return_json(false,$_ALERT['C003'].'<br>'.$Order->err_msg);
	}
}
else if($mode == 'order_exchange') { //교환신청

	$Order = new ORDER;

	$Order->transBegin();

	$tbl_e = $Order->tbls['order_exchange'];
	$product = array_arrange($_POST['product']);
	$order_num = $_POST['order_num'];

	$receiver_mobile = implode("-",$_POST['receiver_mobile']);
	$receiver_tel = implode("-",$_POST['receiver_tel']);

	$success = true;

	if(strlen($receiver_tel) < 10){
		$receiver_tel ="";
	}

	$reg_place = strtolower(VIEWPORT);
	$reg_id = MEMID?MEMID:'GUEST';

	//교환신청데이터 입력
	$record = array(
		'order_num'=>$order_num,
		'receiver_name'=>$argu['receiver_name'],
		'receiver_zipcode'=>$argu['receiver_zipcode'],
		'receiver_addr'=>$argu['receiver_addr'],
		'receiver_addr_detail'=>$argu['receiver_addr_detail'],
		'receiver_mobile'=>$receiver_mobile,
		'receiver_tel'=>$receiver_tel,
		'delivery_pay'=>$argu['delivery_pay'], //교환배송비
		'delivery_pay_method'=>$argu['delivery_pay_method'], //교환배송비지불방법
		'reg_place'=>$reg_place,
		'reg_id'=>$reg_id,
		'reg_ip'=>$_SERVER['REMOTE_ADDR'],
		'valid_yn'=>'Y',
		'date_status_1'=>NOW, //교환신청일
		'date_update'=>NOW
	);

	$sql = sqlInsert($record, $tbl_e);
	$rs = $Order->adodb->Execute($sql);


	if(!$rs) {
		//echo $sql;
		if($success) $error_msg = "교환 기본정보 입력실패";
		$success= false;	
	}


	//교환상품정보 입력
	$exchange_idx = $Order->adodb->insert_id(); //교환키
	$tbl_ep = $Order->tbls['order_exchange_product']; //교환상품테이블
	$tbl_op = $Order->tbls['order_product']; //주문상품테이블

	foreach($product as $v) {
		list($reason, $charger) = explode('|',$v['reason']);
		$reason_etc = pg_escape_string($v['reason_etc']);
		$op_idx = $v['checked'];

		$record_ep = array(
			'order_num'=>$order_num,
			'exchange_idx'=>$exchange_idx,
			'order_product_idx'=>$op_idx,
			'reason'=>$reason,
			'reason_etc'=>$reason_etc,
			'reason_charger'=>$charger,
			'exchange_option_code'=>$v['option'],
			'date_status_1'=>NOW
		);
		
		$sql_ep = sqlInsert($record_ep, $tbl_ep);
		
		$rs_ep = $Order->adodb->Execute($sql_ep);
		if($rs_ep) {
			//상품상태변경
			$sql_op = sqlUpdate(array('cs_type'=>'E','cs_status'=>'1', 'cs_idx'=>$exchange_idx), $tbl_op, array('idx'=>$op_idx));
			$rs_op = $Order->adodb->Execute($sql_op);
			if(!$rs_op) {
				if($success) $error_msg = "상품정보 상태 업데이트실패";
				$success= false;
				break;
			}
		}
		else {
			//echo $sql_ep;
			if($success) $error_msg = "교환 상품정보 입력실패";
			$success= false;
			break;
		}
	}
	
	if($success) {
		$Order->transCommit();
		//교환메일 발송 시작
		$Mail = new MAIL();
		$Mail->send_mail('exchange',$exchange_idx);
		//교환메일 발송 끝
		$oid = $Order->Enctypt_AES128CBC($argu['order_num']);
		return_json(true,'', array('oid'=>$oid));
	}
	else {
		$Order->transRollback();
		return_json(false,$_ALERT['C003'].'<br>'.$error_msg);
	}
}

else if($mode == 'order_confirm') {
	
	//$Order->syncMember('hjlee');exit;

	$order_num = $Order->Dectypt_AES128CBC($_POST['order_num']);
	$order_product_idx = $_POST['order_product_idx'];

	
	//본인체크
	$order_basic = $Order->getBasicRow($order_num);
	if(MEMID) {
		if($order_basic['member_id'] != MEMID) {
			return_json(false,'잘못된 경로로 접근하셨습니다.');
		}
	}
	else {
		if($order_basic['member_id'] || $order_basic['guess_id']) {
			return_json(false,'잘못된 경로로 접근하셨습니다.');
		}
	}


	//구매확정처리
	$rs = $Order->changeOrderStatus($order_product_idx, '6','사용자 구매확정처리');
	if($rs) {
		return_json(true, '처리되었습니다');
	}
	else {
		return_json(false, $_ALERT['C003']);
	}

}
else if($mode == 'validator') {
	if($act == 'cancel') { //취소가능여부체크 (개별취소 방지 및 에스크로 취소불가, 에스크로취소는 후 개발 예정 by hjlee)
		$oid = $_POST['order_num'];
		$order_num = $Order->Dectypt_AES128CBC($oid);

		$count = $Order->countProduct("order_num = '{$order_num}' AND  (cs_type!='0' OR order_status >2)");
		if($count > 0) {
			return_json(false,'부분취소는 고객센터로 문의하세요.');
		}
		else {
			return_json(true,'',array('url'=>DIR_VIEW.'/mypage_cancellist.php?oid='.$oid));
			/*
			$payment_info = $Order->getPaymentRow($order_num);
			
			if($payment_info['escrow_yn'] == 'Y') { //에스크로 취소인경우
				return_json(false,'고객센터로 문의하세요.');
			}
			else {
				return_json(true,'',array('url'=>DIR_VIEW.'/mypage_cancellist.php?oid='.$oid));
			}
			*/
		}
	}
	else if($act == 'refund') { //부분반품 가능여부체크(장바구니 쿠폰 사용 및 사은품 지급시에는 프론트에서 부분반품 불가)
		$oid = $_POST['order_num'];
		$order_num = $Order->Dectypt_AES128CBC($oid);
		

		$count = $Order->countProduct("order_num = '{$order_num}' AND  (cs_type!='0' OR order_status !=5)");
		if($count > 0) { //반품이 불가한 주문상품이 있는경우
			$basic_info = $Order->getBasicRow($order_num);
			if($basic_info['coupon_basket_discount'] > 0) {
				return_json(false, '장바구니 쿠폰사용시 전체반품만 가능합니다.<br>고객센터로 문의해주세요.');
			}

			//사은품지급여부
			$gift_info = $Order->getGiftList($order_num, "status=1"); //발송된 사은품 유무체크
			if($gift_info) {
				return_json(false, '사은품 포함 주문의 경우, 부분 반품은 불가능합니다.<br>고객센터로 문의해주세요.');
			}

			return_json(true,'',array('url'=>DIR_VIEW.'/mypage_refundlist.php?oid='.$oid));
		}
		else {
			return_json(true,'',array('url'=>DIR_VIEW.'/mypage_refundlist.php?oid='.$oid));
		}
	}
}
else if($mode == 'delivery_change') {
	$Order = new ORDER;
	$tbl = $Order->tbls['order'];
	$receiver_mobile = implode("-",$argu['receiver_mobile']);
	$receiver_tel = implode("-",$argu['receiver_tel']);
	if(strlen($receiver_tel) < 10){
		$receiver_tel ="";
	}
	$record = array(
		'receiver_name'=>$argu['receiver_name'],
		'receiver_zipcode'=>$argu['receiver_zipcode'],
		'receiver_addr'=>$argu['receiver_addr'],
		'receiver_addr_detail'=>$argu['receiver_addr_detail'],
		'receiver_mobile'=>$receiver_mobile,
		'receiver_tel'=>$receiver_tel
	);
	$where = array(
		'order_num'=>$argu['order_num']
	);
	$sql = sqlUpdate($record, $tbl, $where);
	$rs = $Order->adodb->Execute($sql);

	if($rs) {
		return_json(true, '변경되었습니다.');
	}
	else {
		return_json(false, '잠시 후에 다시 시도해주세요#1');
	}
}
else if($mode == 'return_cancel') { //반품철회
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
			$Order->error('반품철회실패');
		}
		else {
			$record_log = array(
				'order_num'=>$order_num,
				'order_product_idx'=>$idx,
				'type'=>'cs_status',
				'value'=>$product_row['order_status'].$record['cs_type'].$record['cs_status'].'_'.$record['cs_flag'],
				'value_pre'=>$product_row['order_status'].$product_row['cs_type'].$product_row['cs_status'].'_'.$product_row['cs_flag'],
				'msg'=>'사용자 > 주문배송조회'
			);
			$Order->_log($record_log); //반품철회로그기록
		}
	}

	if($success) {
		$Order->transCommit();
		return_json(true,'철회처리되었습니다.');
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
else if($mode == 'exchange_cancel') { //교환철회
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
				'msg'=>'사용자 > 주문배송조회'
			);
			$Order->_log($record_log); //교환철회로그기록
		}
	}

	if($success) {
		$Order->transCommit();
		return_json(true,'철회처리되었습니다.');
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
