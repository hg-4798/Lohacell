<?php
/**
 * 메인리뷰설정 - 베스트리뷰목록
 *
 */
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Common = new COMMON;
$Design = new DESIGN;
$argu = $_POST;
parse_str($_POST['search'], $search);
$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:20;
$offset = ($page-1)*$limit;
//print_r($search);

//검색
$where_arr = array();
foreach($search as $field=>$v) {
    if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v)) continue;
    switch($field){
        case 'review_class': //상품종류
            if($v =="1"){
                $where_arr[] = "a.type='0'";
            }else{
                $where_arr[] = "a.type='1'";
            }

            break;
        case 'search': //검색어검색
            $sf = $search['s_check'];
            $sv = array_filter(explode("\n", $v));
            if(empty($sv)) continue;
            $tmp = array();
            foreach($sv as $vv) {
                $vv = trim($vv);
                if($sf=="0"){
                    $where_arr[] = "b.productname LIKE '%{$vv}%'";
                }else if($sf=="1"){
                    $where_arr[] = "a.name LIKE '%{$vv}%'";
                }

            }
            break;
        case 'date_start': //날짜검색(시작)
            $v = trim(str_replace('-','',$v));
            $where_arr[] = "a.date >= '{$v}000000'";
            break;
        case 'date_end': //날짜검색(종료)
            $v = trim(str_replace('-','',$v));
            $where_arr[] = "a.date <= '{$v}235959'";
            break;

    }
}

if(!empty($where_arr)) {
    $where = ' AND ';
    $where .= implode(' AND ', $where_arr);
}
$best_review_list = $Design->best_review_list($where,$limit,$offset);

//페이징
$paging_config = array(
    'total'=>$best_review_list['review_cnt'],
    'block_size'=>10,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'MainReview.loadPage'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();


//print_r($best_review_list);

$assign = array(
    'best_review_list'=>$best_review_list['review_list'],
    'paging'=>$paging
);
_render("design/main_review.list.html", $assign, 'admin/template');
