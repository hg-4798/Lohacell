<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");


$prodcd				= "FQI88170";
$colorcd			= "WHS";
$sizecd				= "105";
$delivery_type	= "";

//$res = getErpProdShopStock($prodcd, $colorcd, $sizecd, $delivery_type);

$res=getErpPriceNStock($prodcd, $colorcd, $sizecd, $sync_bon_code);



exdebug($res);
?>