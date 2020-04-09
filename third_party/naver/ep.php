<?php
/**
 * naver_ep
 * @author hjlee
 */

$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Product = new PRODUCT;

$ep = array();
$field = array(
	'id', //상품아이디
	'title', //상품명
	'price_pc', //가격
	'link', //링크
	'mobile_link', //모바일링크
	'image_link', //이미지
	'shipping' //배송료
	//'category_name1',
	//'category_name2',
	//'category_name3'
);

$tab = "\t";

//헤더
foreach($field as $f) {
	$col.=$f.$tab;
}

$ep[] = $col;

$tbl = $Product->tbls['product'];
$sql = "SELECT * FROM {$tbl} WHERE display = 'Y' AND pr_type = 1 AND soldout = 'N' AND naver_display = 'Y' ";

$rs = $Product->adodb->Execute($sql);

$cfg_delivery = $Product->getDeilvery(); //배송비설정

while($row = $rs->FetchRow()) {
    //기본배송비
    $shipping = $cfg_delivery['deli_basefee'];

    //상품코드
    $productcode = $row['productcode'];

    //상품가격
    $endprice = $row['endprice'];

    //상품가격에 따른 배송비 책정
    if($endprice>=$cfg_delivery['deli_miniprice']){
        $shipping= 0;
    }

    $code = $Product->getCategoryFirst($productcode); //대표카테고리

	$link = 'https://www.iknowione.co.kr/front/productdetail.php?productcode='.$productcode.'&code='.$code;
	$mobile_link = 'https://www.iknowione.co.kr/m/productdetail.php?productcode='.$productcode.'&code='.$code;
	$record = array(
		'id'=>$productcode,
		'title'=>$row['productname'],
		'price_pc'=>$endprice,
		'link'=>$link,
		'mobile_link'=>$mobile_link,
		'image_link'=>$row['tinyimage'],
		'shipping'=>$shipping //배송료
	);

	$col = '';
	foreach($field as $f) {
		$col.= $record[$f].$tab;
	}
	
	$ep[] = $col;
}

ob_start();
echo implode("\n",$ep);
$ob_msg = ob_get_contents();
ob_clean();

echo $ob_msg;
?>