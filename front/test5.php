<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");
include_once($Dir."lib/shopdata.php");
include("access.php");

//exdebug($_POST);

$ordercodes = "";
$err_ordercodes = "";
//exdebug($idxs);

$exe_id		= "autoadmin|수동변경|admin";	// 실행자 아이디|이름|타입

$_POST["idx"]="17910,18279,18369,18128,18204,18195,18127,17939,18439,18174,18239,18225,18189,17956,18231,18209,18294,18276,18234,18228,18208,18131,18129,18126,17990,17867,17813,18368,18349,18293,18278,18270,18166,18146,18130,17841,18200,17976,18067,18136,18137,18182,18073,18115,17869,18297,18435,18045,18105,17961,18442,18424,18030,18257,18230,18292,18100,18143,18224,18202,18201,18068,17964,18451,18287,18223,18171,18192,18181,18072,18229,18109,18077,17978,18441,18377,18361,18236,18173,18162,18044,18034,17974,18433,18347,18122,18017,18002,18001,18440,18445,18437,18431,18428,18406,18385,18384,18366,18364,18295,18283,18233,18222,18185,18163,18160,18156,18152,18123,18113,18075,18074,18029,18036,17989,17981,17980,18159,18375,18374,18262,18145,18409,18430,18282,17819,17818,18025,18411,18065,17911,18210,17772,18092,18028,18093,18410,18121,18232,18289,18221,17823,18423,18389,18161,18083,18196,18013,18125,18082,18399,18398,18298,17999,18436,18408,18269,18261,18139,18124,18117,18098,18079,18078,18076,18448,18443,18371,18373,18367,18362,18350,18253,18247,18219,18190,18184,18170,18158,18150,18151,18138,18071,18033,18035,18026,18020,18360";

if($_POST["idx"]) {
    $idxs = rtrim($_POST["idx"],",");
    //$sql = "Select ordercode From tblorderproduct Where idx in (".$idxs.") Group by ordercode";
	$sql = "Select ordercode, idx From tblorderproduct Where idx in (".$idxs.")";

} elseif($_POST["ordercodes"]) {
    $ordercodes = str_replace(",", "','", rtrim($_POST["ordercodes"],','));
    $sql = "select ordercode from tblorderinfo where ordercode in ('".$ordercodes."')";
}
$ret = pmysql_query($sql,get_db_conn());


#exdebug($sql);
#exit;

// 해당 주문건 배송준비중 처리


while($roword = pmysql_fetch_object($ret)) {

	$sql = "SELECT * FROM tblorderinfo WHERE ordercode='{$roword->ordercode}'";
	$result = pmysql_query($sql,get_db_conn());
	//exdebug($sql);
	$_ord = pmysql_fetch_object($result);
	pmysql_free_result($result);

	//if($_ord->deli_gbn=="N") {

		// 입금확인을 거치지 않고, 바로 배송준비중으로 넘어올 경우 bank_date 값이 없다.2016-03-21 jhjeong
		if($_ord->bank_date == "") {
			pmysql_query("UPDATE tblorderinfo SET bank_date='".date("YmdHis")."' WHERE ordercode='{$_ord->ordercode}' ",get_db_conn());
		}

		$sql = "UPDATE tblorderinfo SET deli_gbn='S' WHERE ordercode='{$_ord->ordercode}' AND deli_gbn='N' ";
		if(pmysql_query($sql,get_db_conn())) {
			$sql = "UPDATE tblorderproduct SET deli_gbn='S' WHERE ordercode='{$_ord->ordercode}' ";
			//$sql.= "AND idx = {$idx} ";
			$sql.= "AND idx in ($roword->idx) ";
			$sql.= "AND deli_gbn='N' ";
			pmysql_query($sql,get_db_conn());
			//exdebug($sql);

			// 상태변경 호출
			 orderProductStepUpdate($exe_id, $_ord->ordercode, $roword->idx, 2);
		}
		$ordercodes .=  $_ord->ordercode.",";
	//}
}
pmysql_free_result($ret);

$log_content = "## 주문내역 배송준비중 처리 ## - 주문번호 : ".$ordercodes;
//exdebug($log_content);
ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
//echo "<script>alert('선택하신 주문내역을 배송준비중 처리하였습니다.'); parent.location.reload();</script>";
exit;


?>
