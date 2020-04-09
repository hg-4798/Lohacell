<?php
/**
 * 마이페이지 좋아요 - 이벤트 
 *
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

$widget = new WIDGET();
//$promotion = new PROMOTION();

//$promo_type = array('1'=>"기획전",'2'=>"댓글이벤트",'3' =>"포토이벤트");

$like_id = MEMID;
$limit = "8";
$page = $_POST['page']?:1;
$offset = $limit*($page-1);

$section = 'promo,event';
$section_arr = explode(',',$section);
$section = implode("','",$section_arr);
$like_event = $widget->getLikeAll("like_id='{$like_id}' AND section IN ('{$section}')", $offset, $limit); //좋아요 리스트

$display_type = array();
switch(DIR_VIEW){
    case '/front':
    default:
        $display_type = array('A','P');
        break;
    case '/m':
        $display_type = array('A','M');
        break;
}
//임직원 체크
$staff_yn = 'N';
if(STAFF_YN=='Y'){
    $staff_yn = "'Y','N'";
}

list($PromotionTotal)=pmysql_fetch_array(pmysql_query("SELECT COUNT(*) FROM tblhott_like a JOIN tblpromo b ON a.hott_code=b.idx WHERE a.like_id='{$like_id}' AND a.section IN ('{$section}') AND '".NOW."' BETWEEN b.start_date AND b.end_date  AND b.executives_yn in ('{$staff_yn}') AND b.display_type IN('".implode("','",$display_type)."') AND b.hidden=0 "));
$like_event_list = $like_event['list'];

/*$like_event_code_arr = array();

foreach ($like_event_list['list'] as $key => $val){
    $like_event_code_arr[] = $val['hno'];
}

$like_event_code = implode("','",$like_event_code_arr);
$where = " idx IN ('{$like_event_code}') ";
//$like_event_list;

$promo_list = $promotion->getPromoListAndCnt('ing', '', '', $where);*/



//페이징
$paging_config = array(
    'total'=>$PromotionTotal,
    'block_size'=>10,
    'list_size'=>$limit,
    'page_current'=>$page,
    'url_type'=>'javascript',
    'url'=>'MypageGood.paging'
);
$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

$assign = array(
    'list' => $like_event_list,
    'paging' => $paging
);
_render('mypage/mypage_good.event.html', $assign);

?>
