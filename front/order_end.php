<?php
/**
 * 결제완료
 */

if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');

$Order = new ORDER;
/*
$Point = new Point;
$Point->pay(160,'M181007181853163');
echo $Order->Enctypt_AES128CBC('M181011223813932');
*/

$order_num = $Order->Dectypt_AES128CBC($_GET['orn']); //주문번호 복호화
$order_info = $Order->getBasicRow($order_num);
$payment_info = $Order->getPaymentRow($order_num);

//통계용 주문완료 상품 정보
$order_product_info = $Order->getOrderProductForStats($order_num);

//주문완료페이지 확인가능 시각 (주문서 유효시각에서 10분 추가)
if(time()-strtotime($order_info['date_insert']) > 60*(ORDER_TIME_LIMIT+10)) {
	go('/');
}

//pre($order_info);

$assign = array(
	'class'=>$Order,
	'order'=>$order_info,
	'payment'=>$payment_info,
    'order_product_info' => $order_product_info
);

_render('order/order_end.html', $assign);

include('./include/bottom.php');

?>