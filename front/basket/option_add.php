<?php
/**
 * 옵션추가
 * @author hjlee
 */
$Dir="../../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Basket = new BASKET;
$Product = new PRODUCT;



$group_no = $_POST['group_no'];
$group_list = $Basket->getListByGroup($group_no);
//선택된 옵션 추출

$checked_option = array();
foreach($group_list as $row) {
	if($row['option_type'] == 'product') continue;
	$checked_option[] = $row['no'];
}

//상품정보
$productcode = $group_list[0]['productcode'];

$product_info = $Product->getProductDetail($productcode);
$option = array();
foreach($product_info['option_valid'] as &$row) {
	$label = $row['option_name'];
	switch($row['display']) {
		case 'soldout':
			$label_add = '(Sold Out)';
		break;
		case 'soldout_temp':
			$label_add = '(일시품절)';
		break;
		case $label_add = '';
	}

	if($label_add) {
		$row['option_name'].=$label_add;
		continue;
	}
}

$assign = array(
	'group_no'=>$group_no,
	'product'=>$product_info,
	'checked'=>$checked_option
);



//렌더링
$tpl = 'option_add.html';
_render("basket/".$tpl, $assign);
return false;
?>
