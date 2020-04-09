<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

/**
 * 상품아이콘목록
 * @author 이혜진
 */

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/pagination.class.php"); //pagination class

$product = new Product();
//pre($_POST);
$adodb = adodb_connect(); //db connector
$argu = $_POST;
parse_str($_POST['search'], $search);

$page = $argu['page'];
$limit = (isset($search['limit']))?$search['limit']:20;
$offset = ($page-1)*$limit;

$orderby = 'regdate DESC';

$where_arr = array();

foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf'))) continue;
	switch($field){
		case 'category':
			$cate = array_pop(array_filter($v));
			if(empty($cate)) continue;
			$productcode = rtrim($cate,'0');
			$where_arr[] = "pl.c_category LIKE '%{$productcode}%'"; 
			break;
		case 'sv': //검색어검색
			$sf = $search['sf'];
			$sv = array_filter(explode("\n", $v));
			if(empty($sv)) continue;
			$tmp = array();
			foreach($sv as $vv) {
				$vv = trim($vv);
				$tmp[] = "p.{$sf} LIKE '%{$vv}%'";
			}

			$where_arr[] = '('.implode(' OR ', $tmp).')';
			break;
		case 'pr_type': //상품타입
			$where_arr[] = "p.pr_type IN({$v})"; //1:일반상품, 2:바로구매상품, 3:임직원상품, 4:추가구매상품
		break;
	}
}


if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}

$cnt = $adodb->getOne("SELECT COUNT(*) FROM tblproduct");//전체개수
$cnt_search = $adodb->getOne("SELECT COUNT(DISTINCT p.productcode) FROM tblproduct AS p LEFT JOIN tblproductlink AS pl ON(p.productcode = pl.c_productcode) {$where}");//검색개수

//목록
$sql = "SELECT DISTINCT ON(p.productcode, p.regdate, p.sellprice_dc_rate) p.* FROM tblproduct AS p LEFT JOIN tblproductlink AS pl ON(p.productcode = pl.c_productcode) {$where} ORDER BY {$orderby} LIMIT {$limit} OFFSET {$offset}";
$rs = $adodb->Execute($sql);

$no =  $cnt_search-$offset;

//공통변수
$imagepath=$Dir.DataDir."shopimages/product/";//이미지폴더
$likecodeExchange = $code_a."|".$code_b."|".$code_c."|".$code_d;

$list = array();
while($row = $rs->FetchRow()) {
	$row['no'] = $no;

	//pre($row);

	$row['thumbnail'] = getProductImage($imagepath, $row['minimage']);
	//echo $min_img;
	//이미지
	if(ord($row['minimage']) && file_exists($imagepath.$row['minimage'])) {
		//$row['thumbnail'] = $imagepath.$row['minimage'];
	}
	$list[] = $row;
	$no--;
}

//페이징
$paging_config = array(
	'total'=>$cnt_search,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'ProductChoice.load'
);
$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

$assign = array(
	'class'=>array(
		'product'=>$product
	),
	'cfg'=>array(
		'path_img'=>$imagepath,
		'likecodeExchange'=>$likecodeExchange
	),
	'link'=>array(
		'view'=>"/front/productdetail.php"
	),
	'count'=>array('total'=>$cnt, 'search'=>$cnt_search),
	'list'=>$list,
	'paging'=>$paging
);

_render("product/product_choice.inner.html", $assign, 'admin/template');
?>
