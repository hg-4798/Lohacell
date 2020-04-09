<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$productcode=$_POST["productcode"];
$optsize=$_POST["optsize"];
$quantity=$_POST["quantity"];
$mode=$_POST["page_type"];
$ordbasketid=$_POST["ordbasketid"];

if($mode=="detail"){
	$query="select * from tblproduct where productcode='".$productcode."'";
	$result=pmysql_query($query);
	$data=pmysql_fetch_object($result);

	$maket_stock=getErpPriceNStock($data->prodcode, $data->colorcode, $optsize, $sync_bon_code);

	if($quantity > $maket_stock[sumqty]){
		$subquery="select count(pridx) as num from tblproduct where productcode='".$productcode."' AND concat(season_year,season)!='2015K'";
		$subresult=pmysql_query($subquery);
		$subdata=pmysql_fetch_object($subresult);
		$chk_num = $subdata->num;
		if($chk_num < 1){
			echo $maket_stock[sumqty]?$maket_stock[sumqty]:"0";
		}else{
			echo "OK";
		}
	}else{
			echo "OK";
	}
}
?>