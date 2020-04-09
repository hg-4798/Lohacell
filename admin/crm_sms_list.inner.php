<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Sms = new SMS();

//검색
parse_str($_POST['search'], $search);

$id = $search['id']?$search['id']:null;

$mem_info = $Sms->getMemInfo($id);
$to_tel = str_replace('-','',$mem_info['to_tel']);
$where = "WHERE to_tel = '".$to_tel."' ";

if($search['date_s']){
    $where.=" AND send_date >= '{$search['date_s']} 00:00:00'";
}
if($search['date_e']) {
    $where.=" AND send_date <= '{$search['date_e']} 23:59:59'";
}

$where.=$where_add;
$page = ($_POST['page'])?$_POST['page']:'1';
$limit = ($search['limit'])?$search['limit']:'5';
//print_r($where);
if($page) {
	$offset = ($page-1)*$limit;
}

$t_count = $Sms->adodb->getOne("
        SELECT count(idx) 
        FROM tblsms_log
        {$where}"
		);

$sql = "SELECT msg, send_date, from_tel, to_tel, etc_msg, res_msg, msg_type, book_date  FROM tblsms_log
		{$where} ORDER BY idx DESC OFFSET {$offset} LIMIT {$limit}";
$rs = $Sms->adodb->Execute($sql);
$list = array();
$no=0;

while($row = $rs->FetchRow()) {
	$number = ($t_count-($limit * ($page-1))-$no);
	$no++;
    $row['no'] = $number;
    $row['send_date'] = date("Y-m-d H:i:s",strtotime($row['send_date']));;
    $list[] = $row;
}

//페이징
$paging_config = array(
	'total'=>$t_count,
	'block_size'=>10,
	'list_size'=>$limit,
	'page_current'=>$page,
	'url_type'=>'javascript',
	'url'=>'CrmSmsList.load'
);

$pagination = new Pagination($paging_config);
$paging = $pagination->getPageSet();

//print_r($list);
$assign = array(
	'list'=>$list,
	'paging'=>$paging
);
_render("member/crm_sms_list.inner.html", $assign, 'admin/template');

?>