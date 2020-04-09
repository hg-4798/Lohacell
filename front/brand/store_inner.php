<?php
/**
 * 매장정보
 */

if(strlen($Dir)==0) $Dir="../../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$Store = new STORE;
parse_str($_POST['search'], $search);

if($search['sort']=='date'){
    $sort = "ORDER BY regdt DESC";
}else{
    $sort = "ORDER BY name ASC ";
}

//검색
$where_arr = array();
foreach($search as $field=>$v) {
    if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v)) continue;
    switch($field){
        case 'store': //검색어검색
            $where_arr[] = "name LIKE '%{$v}%'";
            break;
        case 'category': //매장구분
            $where_arr[] = "category = '{$v}'";
            break;
        case 'area_code': //시/도
            $where_arr[] = "area_code = {$v}";
            break;
            break;
     }
}
if(!empty($where_arr)) {
    $where = ' WHERE ';
    $where .= implode(' AND ', $where_arr);
}

$storelist = $Store->getStoreList($where, $sort);

$assign = array(
    'storelist'=>$storelist['list'],
    'total'=>$storelist['total'],
    'store_category'=>$store_category
);
_render('brand/store_inner.html', $assign);


?>
