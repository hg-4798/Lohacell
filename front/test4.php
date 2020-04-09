<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");
include_once($Dir."lib/shopdata.php");

$suc_cnt = 0;
$err_cnt = 0;
$_POST['query_idx']="18348,18108,17966,18438,18446,18444,18432,18429,18427,18425,18422,18421,18418,18419,18420,18413,18405,18403,18397,18391,18390,18387,18386,18388,18376,18372,18370,18365,18363,18351,18353,18346,18296,18288,18284,18281,18280,18277,18274,18268,18265,18266,18267,18255,18252,18248,18249,18235,18227,18226,18216,18215,18212,18206,18207,18205,18203,18198,18199,18197,18194,18193,18191,18188,18187,18165,18167,18164,18157,18142,18120,18118,18114,18112,18107,18106,18099,18097,18096,18095,18090,18087,18085,18086,18084,18066,18032,18019,18015,18014,18011,17982,17983,17984,17979,17975,17969,17948,17949,17843,17844,18116,18434,18426,18407,18392,18383,18352,18291,18264,18220,18149,18070,18069,18031,18018,18012,18183,18251,18186,18172,18027,18016,18275,18091";


$suc_cnt = 0;
$err_cnt = 0;
if(count($_POST['query_idx']) > 0){
	$arrIdx = implode("','", array_filter(explode(",", $_POST['query_idx'])));
	# 선택한 아이템중 배송주체 변경이 가능한 상품만 조회해서 처리
	$selItemRes = pmysql_query("SELECT idx, ordercode, store_code FROM tblorderproduct WHERE idx in ('".$arrIdx."') AND (delivery_type = '0' OR delivery_type = '2') AND op_step in ('1','2')");
	while ($selItemRow=pmysql_fetch_object($selItemRes)) {
		
		$ordercode = $selItemRow->ordercode;
		$idxs = $selItemRow->idx;
		$null_storecode="";
		
		list($store_code)=pmysql_fetch("select store_code from tblorderproduct where ordercode='".$ordercode."' and idx='".$idxs."'");

		if($store_code==$sync_bon_code) $null_storecode="store_code='', ";
		$Sync = new Sync();
		$sync_idx = $idxs;
		$arrayDatax=array('ordercode'=>$ordercode,'sync_idx'=>'AND idx='.$sync_idx);
		$sql = "UPDATE tblorderproduct SET ".$null_storecode."delivery_type='2' WHERE ordercode='{$ordercode}' and idx={$idxs} ";
		pmysql_query($sql, get_db_conn());

		$sql = "INSERT INTO tblorderproduct_store_change(ordercode, idx, regdt) VALUES ('{$ordercode}','{$idxs}','".date('YmdHis')."')";
		pmysql_query($sql, get_db_conn());

		$srtn=$Sync->OrderInsert($arrayDatax);
		#싱크커머스 API호출
		if($srtn != 'fail') {

			//변경전 erp로 전송
			sendErpChangeShop($ordercode, $idxs, '', '2');

			#주문정보 update
			$sql = "UPDATE tblorderproduct SET delivery_type='2' WHERE ordercode='{$ordercode}' and idx={$idxs} ";

			pmysql_query($sql, get_db_conn());
			#배송준비중으로 변경
			$exe_id		= $_ShopInfo->getId()."|".$_ShopInfo->getName()."|admin";	// 실행자 아이디|이름|타입
			orderProductStepUpdate($exe_id, $ordercode, $idxs, '2');

			//현재 주문의 상태값을 가져온다.
			list($old_step1, $old_step2) = pmysql_fetch_array(pmysql_query("select oi_step1, oi_step2 from tblorderinfo WHERE ordercode='" . trim($ordercode) . "'"));
			if (($old_step1 == '1' || $old_step1 == '2') && $old_step2 == '0') {
				//주문을 배송 준비중으로 변경한다.
				$sql2 = "UPDATE tblorderinfo SET oi_step1 = '2', oi_step2 = '0', deli_gbn='S' WHERE ordercode='" . $ordercode . "'";
				pmysql_query($sql2, get_db_conn());
			}
			$suc_cnt++;
		}else{
			$sql = "UPDATE tblorderproduct SET store_code='".$selItemRow->store_code."', delivery_type='0' WHERE ordercode='{$ordercode}' and idx={$idxs} ";
			pmysql_query($sql, get_db_conn());
			$err_cnt++;
		}
	
	}
}

$msg = "배송주체 일괄 수정 : 성공 {$suc_cnt}건, 실패 {$err_cnt}건";

echo $msg;
//alert_go($msg, "/admin/order_list_all.php?".$_POST['query_string']);

?>
