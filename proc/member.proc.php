<?php
/**
 * 회원관련 프로세싱
 * 비동기처리
 * @author hjlee
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



$Member = new MEMBER;//회원클래스

$mode = $_POST['mode'];
$act = $_POST['act'];

$argu = $Member->xss_post();

if($mode == 'login') {
	if($act == 'guest') {

		//구매내역 체크
		$buyer_name = $_POST['order_name'];
		$order_num = $_POST['order_code'];

		$Order = new ORDER;
		$row = $Order->getBasicRow($order_num, '*', "member_id='' AND buyer_name='{$buyer_name}'");
		if($row['order_num']) {
			//주문번호 세션저장
			$_SESSION['GID'] = $row['guest_id']; //비회원세션저장
			$oid = $Order->Enctypt_AES128CBC($row['order_num']);
			return_json(true,'',array('url'=>DIR_VIEW.'/mypage_orderlist_view.php?oid='.$oid));
		}
		else {
			return_json(false,'일치하는 주문정보가 없습니다.');
		}
		
	
	}
}
?>