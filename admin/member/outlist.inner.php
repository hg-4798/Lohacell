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
    case 'outdate_asc': //탈퇴일 오름차순
        $orderby = 'b.date ASC';
        break;
    case 'outdate_desc': //탈퇴일 내림차순
        $orderby = 'b.date DESC';
        break;
    case 'date_asc': //가입일 오름차순
        $orderby = 'a.date ASC';
        break;
	case 'date_desc': //가입일 내림차순
	default:
		$orderby = 'a.date DESC';
		break;
}

//검색
$where_arr = array();
foreach($search as $field=>$v) {
	if(!$v || in_array($field, array('sf', 'date_sf')) || empty($v)) continue;
	switch($field){
		case 'date_start': //가입일 시작
			$v = trim($v);
            if($v) {
                $v= date("YmdHis",strtotime($v));
                $where_arr[] = "a.date >= '{$v}'";
            }
			break;
		case 'date_end': //가입일 끝
			$v = trim($v);
            if($v) {
                $v= date("Ymd",strtotime($v))."235959";
                $where_arr[] = "a.date <= '{$v}'";
            }
			break;
        case 'outdate_start': //최근로그인 시작
            $v = trim($v);
            if($v) {
                $v= date("YmdHis",strtotime($v));
                $where_arr[] = "b.date >= '{$v}'";
            }
            break;
        case 'outdate_end': //최근로그인 끝
            $v = trim($v);
            if($v) {
                $v= date("Ymd",strtotime($v))."235959";
                $where_arr[] = "b.date <= '{$v}'";
            }
            break;
	}
}

if(!empty($where_arr)) {
    $where = ' AND ';
	$where .= implode(' AND ', $where_arr);
}

//목록
$sql = "SELECT a.id,
									   a.date,
									   b.name,
									   b.email,
									   b.tel,
									   b.date AS outdate,
									   b.out_reason,
									   b.out_reason_content
								FROM tblmember a
								LEFT JOIN tblmemberout b ON b.id=a.id
								WHERE a.member_out='Y' and (b.name is not null)
								{$where}
                                ORDER BY {$orderby} LIMIT {$limit} OFFSET {$offset}";

//print_r($sql);
$rs = $Member->adodb->Execute($sql);

$cnt_total = $Member->adodb->getOne("SELECT COUNT(a.id) FROM tblmember a
								LEFT JOIN tblmemberout b ON b.id=a.id
								WHERE a.member_out='Y' and (b.name is not null)
								");//전체개수
$cnt_search = $Member->adodb->getOne("SELECT COUNT(a.id) FROM tblmember a
								LEFT JOIN tblmemberout b ON b.id=a.id
								WHERE a.member_out='Y' and (b.name is not null)
								{$where}");//검색개수

$no =  $cnt_search-$offset;


$list = array();
$group_list = $Member->getGroupPair();

while($row = $rs->FetchRow()) {
	$row['no'] = $no;
	// pre($row);
    $row['mobile'] = Common::Dectypt_AES128CBC($row['tel']);
    $row['email'] = Common::Dectypt_AES128CBC($row['email']);
    $row['out_reason'] = $arrMemberOutReason[$row['out_reason']];

	
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
	'url'=>'MemberOutList.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();


$assign = array(
	'count'=>array('total'=>$cnt_total, 'search'=>$cnt_search),
	'list'=>$list,
	'paging'=>$paging
);

_render("member/outlist.inner.html", $assign, 'admin/template');

?>
