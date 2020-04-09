<?php
/**
 * 상품아이콘 일괄변경
 * @author 이혜진
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/pagination.class.php"); //pagination class
include_once($Dir."lib/product.class.php"); //pagination class

$product =  new PRODUCT;
$adodb = adodb_connect(); //db connector
$argu = $_POST;
parse_str($_POST['search'], $search);

$page = $argu['page'];
$limit = (isset($search['limit']))?$search['limit']:20;
$offset = ($page-1)*$limit;



//정렬
switch($search['sort']) {
	case 'sale_asc': //할인율 낮은순
		$orderby = 'p.sellprice_dc_rate ASC';
		break;
	case 'sale_desc': //할인율 높은순
		$orderby = 'p.sellprice_dc_rate DESC';
		break;
	default: //최근등록일순
		$orderby = 'p.regdate DESC';
		break;
}

//검색
$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v)) continue;
	switch($field){
		case 'category': //카테고리검색
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
		case 'date_start': //날짜검색(시작)
			$sf = trim($search['date_sf']);
			$v = trim($v);
			$where_arr[] = "p.{$sf} >= '{$v} 00:00:00'";
			break;
		case 'date_end': //날짜검색(종료)
			$sf = trim($search['date_sf']);
			$v = trim($v);
			$where_arr[] = "p.{$sf} <= '{$v} 23:59:59'";
			break;
		case 'sellprice_min':
			$where_arr[] = "p.sellprice >= '{$v}'";
			break;
		case 'sellprice_max':
			$where_arr[] = "p.sellprice<='{$v}'";
			break;
		case 'quantity_min': //재고최소값
			$where_arr[] = "p.quantity >= '{$v}'";
			break;
		case 'quantity_max': //재고최대값
			$where_arr[] = "p.quantity <= '{$v}'";
			break;
		case 'display': //승인상태
		case 'soldout': //판매상태
		case 'o2o_yn': //퀵배송
		case 'staff_dc_yn': //임직원할인
			$where_arr[] = "p.{$field}='{$v}'";
			break;
		case 'icon':

			$where_arr[] = "p.icon IN ('".implode("','", $v)."')";
			break;
	}
}


if(!empty($where_arr)) {
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}



//목록
$sql = "SELECT DISTINCT ON(p.productcode, p.regdate, p.sellprice_dc_rate) p.* FROM tblproduct AS p LEFT JOIN tblproductlink AS pl ON(p.productcode = pl.c_productcode) {$where} ORDER BY {$orderby} LIMIT {$limit} OFFSET {$offset}";

$rs = $adodb->Execute($sql);

$cnt = $adodb->getOne("SELECT COUNT(*) FROM tblproduct");//전체개수
$cnt_search = $adodb->getOne("SELECT COUNT(DISTINCT p.productcode) FROM tblproduct AS p LEFT JOIN tblproductlink AS pl ON(p.productcode = pl.c_productcode) {$where}");//검색개수

$no =  $cnt_search-$offset;

//공통변수
$imagepath=$Dir.DataDir."shopimages/product/";//이미지폴더
$likecodeExchange = $code_a."|".$code_b."|".$code_c."|".$code_d;

//등록아이콘정보
$icon = $product->getIconAll('*','N'); //자동노출아이콘은 제외하고 노출



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
	'url'=>'ProductBatchIcon.loadPage'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();




$assign = array(
	'class'=>array(
		'product'=>$product
	),
	'cfg'=>array(
		'path_img'=>$imagepath,
		'likecodeExchange'=>$likecodeExchange,
		'icon'=>$icon
	),
	'link'=>array(
		'view'=>""
	),
	'count'=>array('total'=>$cnt, 'search'=>$cnt_search),
	'list'=>$list,
	'paging'=>$paging
);

_render("product/product_batch_icon.inner.html", $assign, 'admin/template');

?>
