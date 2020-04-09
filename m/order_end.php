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

$order_num = $Order->Dectypt_AES128CBC($_GET['orn']); //주문번호 복호화
$order_info = $Order->getBasicRow($order_num);
$payment_info = $Order->getPaymentRow($order_num);

//통계용 주문완료 상품 정보
$order_product_info = $Order->getOrderProductForStats($order_num);

//주문완료페이지 확인가능 시각(10분)
if(time()-strtotime($order_info['date_insert']) > 60*10) {
	go('/');
}

$assign = array(
	'class'=>$Order,
	'order'=>$order_info,
	'payment'=>$payment_info,
    'order_product_info' => $order_product_info
);

_render('order/order_end.html', $assign, DIR_M.'/template' );

include('./include/bottom.php');

?>