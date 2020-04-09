<?php
/**
 * 휴면회원목록
 * @author bshan
 */


$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/pagination.class.php"); //pagination class

$Member =  new MEMBER;
$argu = $_POST;
parse_str($_POST['search'], $search);
$page = ($argu['page'])?$argu['page']:1;
$limit = (isset($search['limit']))?$search['limit']:20;
$offset = ($page-1)*$limit;

//정렬
switch($search['sort']) {
    case 'date_asc': //가입일 오름차순
        $orderby = 'date ASC';
        break;
	case 'date_desc': //가입일 내림차순
	default:
		$orderby = 'date DESC';
		break;
}

//검색
$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v)) continue;
	switch($field){
		case 'sw': //검색어검색
			$sf = $search['sf'];
			$sv = array_filter(explode("\n", $v));
			if(empty($sv)) continue;
			$tmp = array();
			foreach($sv as $vv) {
				$vv = trim($vv);
				if($sf == 'all'){
					$tmp[] = "id LIKE '%{$vv}%' OR name LIKE '%{$vv}%' ";
				}else {
					$tmp[] = "{$sf} LIKE '%{$vv}%'";
				}
			}

			$where_arr[] = '('.implode(' OR ', $tmp).')';
			break;
		case 'date_start': //가입일 시작
			$v = trim($v);
            if($v) {
                $v= date("YmdHis",strtotime($v));
                $where_arr[] = "date >= '{$v}'";
            }
			break;
		case 'date_end': //가입일 끝
			$v = trim($v);
            if($v) {
                $v= date("Ymd",strtotime($v))."235959";
                $where_arr[] = "date <= '{$v}'";
            }
			break;
		case 'logindate_start': //최근로그인 시작
			$v = trim($v);
			if($v) {
				$v= date("YmdHis",strtotime($v));
				$where_arr[] = "logindate >= '{$v}'";
			}
			break;
		case 'logindate_end': //최근로그인 끝
			$v = trim($v);
			if($v) {
				$v= date("Ymd",strtotime($v))."235959";
				$where_arr[] = "logindate <= '{$v}'";
			}
			break;
	}
}

if(!empty($where_arr)) {
    $where = ' AND ';
	$where .= implode(' AND ', $where_arr);
}

$sleep_list = $Member->getMemberSearch('WHERE member_out = \'S\' '.$where.'ORDER BY '.$orderby ,$limit, $offset);
//목록

$cnt_total = $Member->adodb->getOne("SELECT COUNT(id) FROM tblmember WHERE member_out = 'S' ");//전체개수
$cnt_search = $sleep_list['total']; //검색개수

$no =  $cnt_search-$offset;

$list = array();
foreach($sleep_list['list'] as $k => $v) {
	$sleep_list['list'][$k]['no'] = $no;
	// pre($row);
	$sleep_list['list'][$k]['mobile'] = Common::Dectypt_AES128CBC($sleep_list['list'][$k]['mobile']);
	$sleep_list['list'][$k]['email'] = Common::Dectypt_AES128CBC($sleep_list['list'][$k]['email']);

	
	$list[$k] = $sleep_list['list'][$k];
	$no--;
}

//페이징
$paging_config = array(
	'total'=>$cnt_search,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'MemberSleepList.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();


$assign = array(
	'count'=>array('total'=>$cnt_total, 'search'=>$cnt_search),
	'list'=>$list,
	'paging'=>$paging
);

_render("member/sleeplist.inner.html", $assign, 'admin/template');

?>
