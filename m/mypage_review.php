<?php
if(strlen($Dir)==0) $Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once(DOC_ROOT."/lib/paging.php");

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.MDir."login.php?chUrl=".getUrl());
	alert_go(null,$Dir.MDir."login.php?chUrl=".getUrl());
	exit;
}

$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
$result=pmysql_query($sql,get_db_conn());
if($row=pmysql_fetch_object($result)) {
	$_mdata=$row;
	if($row->member_out=="Y") {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		alert_go('회원 아이디가 존재하지 않습니다.',$Dir.MDir."login.php");
	}

	if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		alert_go('처음부터 다시 시작하시기 바랍니다.',$Dir.MDir."login.php");
	}
}
pmysql_free_result($result);

$listnum = 3;

#####날짜 셋팅 부분
$s_year=(int)$_GET["s_year"];
$s_month=(int)$_GET["s_month"];
$s_day=(int)$_GET["s_day"];

$e_year=(int)$_GET["e_year"];
$e_month=(int)$_GET["e_month"];
$e_day=(int)$_GET["e_day"];

$day_division = $_GET['day_division'];

$limitpage = $_GET['limitpage'];

$review_type=$_GET['review_type']?$_GET['review_type']:"reviewwrite";

$r_s_year= 0;
$r_s_month= 0;
$r_s_day= 0;

$r_e_year= 0;
$r_e_month= 0;
$r_e_day= 0;

$r_day_division = "";

if($e_year==0) $e_year=(int)date("Y");
if($e_month==0) $e_month=(int)date("m");
if($e_day==0) $e_day=(int)date("d");

$etime=strtotime("$e_year-$e_month-$e_day");

$stime=strtotime("$e_year-$e_month-$e_day -1 month");
if($s_year==0) $s_year=(int)date("Y",$stime);
if($s_month==0) $s_month=(int)date("m",$stime);
if($s_day==0) $s_day=(int)date("d",$stime);
$s_curdate=date("Ymd",$stime)."000000";
$e_curdate=date("Ymd",$etime)."999999";
$s_curdate1=date("Y-m-d",$stime)." 00:00:00";
$e_curdate1=date("Y-m-d",$etime)." 23:59:59";
$review_display[$review_type]="active";

# ====================================================================================================================================
# 작성하지 않은 리뷰 리스트
# 현재는 배송중부터 작성 가능하지만, 구매확정 이후 시점으로 변경해야 됨.2016-08-09 jhjeong
# ====================================================================================================================================

$sql ="SELECT oi.order_num, op.idx, op.productcode,op.option_code,op.option_type,op.date_order_1,op,price_end,vi.productorder_idx";
$sql .= " FROM tblorder_basic AS oi ";
$sql .= "LEFT JOIN tblorder_product AS op ON(oi.order_num=op.order_num)";
$sql .= "LEFT JOIN tblproductreview AS vi ON(op.idx=vi.productorder_idx) ";
$sql .= "WHERE oi.member_id='".MEMID."' ";
$sql .= "AND op.order_status ='6' ";
$sql .= "AND op.cs_type IN ('0','E') ";
$sql .= "AND op.cs_status ='0' ";
$sql .= "AND op.option_type ='option' ";
$sql .= "AND vi.productorder_idx is null ";
$sql .= "AND ( op.date_order_1 >= '{$s_curdate1}' AND op.date_order_1 <= '{$e_curdate1}' ) ";

$paging = new New_Templet_paging($sql, 3, 8, 'GoPage', true);

$t_count = $paging->t_count;
$gotopage = $paging->gotopage;
$sql = $paging->getSql($sql);
$result=pmysql_query($sql,get_db_conn());

# ====================================================================================================================================
# 작성한 리뷰 리스트
# ====================================================================================================================================

$sql2  = "SELECT vi.*,op.option_type  ";
$sql2 .= "FROM tblproductreview AS vi ";
$sql2 .= "LEFT JOIN  tblorder_product AS op ON(vi.productorder_idx=op.idx)";
$sql2 .= "WHERE vi.id = '" . MEMID . "' ";
$sql2 .= "AND ( vi.date >= '{$s_curdate}' AND vi.date <= '{$e_curdate}' ) ";
$sql2 .= "ORDER BY vi.num desc ";

//pre($sql2);
$r_paging = new New_Templet_paging($sql2, 3, $listnum, 'GoPage2', true);
$r_t_count = $r_paging->t_count;
$gotopage2 = $r_paging->gotopage;

$sql2 = $r_paging->getSql($sql2);
$result2 = pmysql_query($sql2,get_db_conn());

include('./include/top.php');
include('./include/gnb.php');
include(DOC_ROOT."/templet/review/mobile/myreview_TEM001.php");

include('./include/bottom.php');  ?>
