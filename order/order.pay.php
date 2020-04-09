<?php
/**
 * 결제모듈 호출
 * @author hjlee
 */
$Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Order = new ORDER;
$_CLEAN = $Order->xss_post();
$order_num = $_CLEAN['order_num'];

if(!$order_num) {
	exit; //주문번호가 없는경우 무조건 중지
}


$order = $Order->getBasicRow($order_num); //주문정보

//주문상품정보
$product_info = $Order->getProductList($order_num);
if(!is_array($product_info['list'])) {
	go('/');
}

$chr_30 = chr(30);
$chr_31 = chr(31);
$basket_list = array(); //에스크로용 장바구니상품정보
$basket_count = 0; //장바구니 상품개수
$basket_idx = 1;
// pre($product_info);
foreach($product_info['list'] as $row) {
	$record = array(
		"seq=".$basket_idx,
		"ordr_numb=".$row['order_num'],
		"good_name=".$row['detail']['product_name']."_".$row['detail']['option_name'],
		"good_cntx=".$row['count'],
		"good_amtx=".($row['price_end']*$row['count'])
	);
	$basket_idx++;
	$basket_list[] = implode($chr_31,$record);
	$basket_count++;
}

$good_info = implode($chr_30,$basket_list);

//에스크로관련정보
$escrow = array(
	'cnt'=>$basket_count,
	'good_info'=>$good_info //implode($chr_30,$basket_list)
);

switch(PG) {
	case 'NHNKCP':
	default:
		$tmpl = "order/order.kcp.html";
		//include('/home/jayjun/_pg/NHNKCP/cfg/conf.php'); //변수설정파일
		include(DOC_ROOT.'/../_pg/NHNKCP/cfg/conf.php'); //변수설정파일
		$pg = array(
			'gw_url'=>$g_conf_gw_url,
			'js_url'=>$g_conf_js_url,
			'site_code'=>$g_conf_site_cd,
			'site_name'=>$g_conf_site_name,
			'pay_method'=>$Order->getPaymentCode($order['pg_paymethod'])
		);
	break;
}

$assign = array(
	'uri'=>array(
		'back'=>HOST.DIRECTORY_SEPARATOR.$_POST['refer']
	),
	'path'=>'/third_party/pg/NHNKCP',
	'order'=>$order,
	'escrow'=>$escrow,
	'pg'=>$pg
);

// _render($tmpl, $assign);
// exit;
if(VIEWPORT == 'MOBILE') {
	_render($tmpl, $assign, DIR_M.'/template');
}
else {
	_render($tmpl, $assign);
}

?>