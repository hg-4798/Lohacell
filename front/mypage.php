<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

//로그인체크(로그인회원만 접근가능)
MEMBER::isMember();

include('./include/top.php');
include('./include/gnb.php');
$Common =  new Common();

$Member = new MEMBER;
$member_info = $Member->getMemberRow(MEMID,'name, group_code, reserve, act_point'); //회원정보

//최근주문/배송조회

//사용가능 쿠폰
$Coupon = new COUPON;
$coupon_rs = $Coupon->useCoupon(MEMID);
$coupon_rs['list'] = array_slice($coupon_rs['list'],0,5);


//1:1문의
$tbl_qna = $Member->tbls['qna'];
$qna = $Member->adodb->getArray("SELECT * FROM {$tbl_qna} WHERE id='".MEMID."' ORDER BY idx DESC LIMIT 5");

// 최근주문/배송조회
$Order = new ORDER;
$tbl_ob = $Order->tbls['order'];
$tbl_op = $Order->tbls['order_product'];
$date= date("Y-m-d H:i:s", strtotime(NOW.' - 6month'));
$where = "order_status > 0 and date_insert >= '".$date."' AND date_insert <= '".NOW."'";
$list = $Order->getBasicList($where);


$cs_type= $Order->adodb->getOne("SELECT count(*) FROM {$tbl_op} p JOIN {$tbl_ob} b ON p.order_num = b.order_num WHERE b.member_id = '".MEMID."' AND p.cs_type!='0'");
$order_status_4= $Order->adodb->getOne("SELECT count(*) FROM {$tbl_op} p JOIN {$tbl_ob} b ON p.order_num = b.order_num WHERE b.member_id = '".MEMID."' AND p.order_status='4' AND p.cs_type='0'");
$order_status_5= $Order->adodb->getOne("SELECT count(*) FROM {$tbl_op} p JOIN {$tbl_ob} b ON p.order_num = b.order_num WHERE b.member_id = '".MEMID."' AND p.order_status='5' AND p.cs_type='0'");
$assign = array(
	'member'=>$member_info,
	'coupon'=>$coupon_rs,
	'qna'=>$qna,
	'order_cnt'=>array(
		'order_status_4'=>$order_status_4,
		'order_status_5'=>$order_status_5,
		'cs_type'=>$cs_type
	),
	'class'=>array(
		'common'=>$Common
	),
	'order_list'=>$list
);
//pre($list);
_render('mypage/index.html', $assign);

include('./include/bottom.php');

exit;