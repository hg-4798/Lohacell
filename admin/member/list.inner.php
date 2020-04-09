<?php
/**
 * 회원목록
 * @author hjlee
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
$where_arr[] = "member_out!='Y'";
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
        case 'group_code': //회원등급
            $v = trim($v);
            if($v == 'all'){

            }else {
                $where_arr[] = "group_code = '{$v}'";
            }

            break;
        case 'staff_yn': //회원구분
            $v = trim($v);
            if($v == 'all'){

            }else {
                $where_arr[] = "staff_yn = '{$v}'";
            }

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
	$where = ' WHERE ';
	$where .= implode(' AND ', $where_arr);
}

$tbl = $Member->tbls['member'];

//목록
$sql = "SELECT * FROM {$tbl} {$where} ORDER BY {$orderby} LIMIT {$limit} OFFSET {$offset}";

//print_r($sql);
$rs = $Member->adodb->Execute($sql);

$cnt_total = $Member->adodb->getOne("SELECT COUNT(*) FROM {$tbl}");//전체개수
$cnt_search = $Member->adodb->getOne("SELECT COUNT(*) FROM {$tbl}  {$where}");//검색개수

$no =  $cnt_search-$offset;


$list = array();
$group_list = $Member->getGroupPair();

while($row = $rs->FetchRow()) {
	$row['no'] = $no;
	// pre($row);
	$row['group_name'] = $group_list[$row['group_code']];
    $row['mobile'] = Common::Dectypt_AES128CBC($row['mobile']);

	
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
	'url'=>'MemberList.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();


$assign = array(
	'count'=>array('total'=>$cnt_total, 'search'=>$cnt_search),
	'list'=>$list,
	'paging'=>$paging
);

_render("member/list.inner.html", $assign, 'admin/template');

?>
