<?

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/jungbo_code.php"); //정보고시 코드를 가져온다

@set_time_limit(0);

$conn = GetErpDBConn();

echo "START = ".date("Y-m-d H:i:s")."\r\n";

$sql = "SELECT *  
			FROM (
				SELECT 
				STYLE_NO AS PRODCD,
				SUBSTR(STYLE_NO,3,1) PRODCATE,
				SUBSTR(MAX(NVL(TAG_STYLE_NO,'')),3,2) PRODCATECODE,
				SEASON_YEAR,
				MAX(NVL(TAG_STYLE_NO,'')) TAG_STYLE_NO,
				SEASON,
				COLOR_CODE AS COLORCD,
				MAX(NVL(COLOR_NAME,'')) COLORCDNM,
				MAX(NVL(BRAND,'')) BRANDCD,
				MAX(NVL(BRAND_ENAME,'')) BRANDCDNM,
				MAX(NVL(BRAND_HNAME,'')) BRANDCDNMH,
				MAX(TAG_PRICE) TAGPRICE,
				MAX(NVL(PROD_NAME,'')) PRODNM
				FROM TA_OM001
				WHERE 1=1
				AND     TAG_PRICE > 0
				AND RCV_DATE is NULL
				GROUP BY STYLE_NO, SEASON_YEAR, SEASON, COLOR_CODE
			  ORDER BY STYLE_NO, SEASON_YEAR, SEASON, COLOR_CODE
			) a
			WHERE   a.tag_style_no  in ('VRBAH24140',
'VRBAH24130',
'VRBAH24160',
'VRJAH21130',
'VRJAH21120',
'VRJAH11890')
        ";

$smt = oci_parse($conn, $sql);
oci_execute($smt);
echo $sql."\r\n";

$brand_vender	= getAllBrand();			// 쇼핑몰 전체 EPR 브랜드코드별 쇼핑몰 브랜드코드, 벤더코드
$discountrate		= getDiscountRate();	// 브랜드 그룹별 할인율
echo $brand_vender."\r\n";

$cnt = 0;
$productcode = "";
$brand = "";
$vender = "";
$self_goods_code = "";
$sizeopt = array();
$sizestock = array();
$sizeprice = array();
$sizearr = "";
while($data = oci_fetch_array($smt, OCI_BOTH+OCI_RETURN_NULLS+OCI_RETURN_LOBS)) {
	echo $cnt."\r\n";

    foreach($data as $k => $v)
    {
        $data[$k] = utf8encode($v);
    }

    $product_ins = getProducCode($data[PRODCD], $data[COLORCD], $data[SEASON_YEAR], $data[SEASON], $data[BRANDCD], $data[PRODCATECODE]);    // 상품코드 및 카테고리
	$productcode					= $product_ins["productcode"];
	$category						= $product_ins["catecode"];
	
	list($chk_productcode) = pmysql_fetch("Select productcode From tblproduct Where prodcode = '".$data[PRODCD]."' And colorcode = '".$data[COLORCD]."' And season_year = '".$data[SEASON_YEAR]."' And season = '".$data[SEASON]."'");

	if (!$chk_productcode) {
		echo $data[PRODCD];
		echo "<br>";
	}

}

// 쇼핑몰 전용 상품 코드 구하기
function getProducCode($prodcd, $colorcd, $season_year, $season, $brandcd, $prodcate) {
	$prodcode_info	=	 array();
    list($productcode) = pmysql_fetch("Select productcode From tblproduct Where prodcode = '".$prodcd."' And colorcode = '".$colorcd."' And season_year = '".$season_year."' And season = '".$season."'");

    list($catecode) = pmysql_fetch("Select catecode From tblproduct_cate_erp Where brandcds LIKE '%".$brandcd."%' And bokjongs LIKE '%".$prodcate."%' ");

	if ($catecode =='') $catecode	= "001001001001";

    if($productcode == "") {
        $sql = "SELECT MAX(productcode) as maxproductcode FROM tblproduct WHERE productcode LIKE '".$catecode."%' LIMIT 1";
        $result = pmysql_query($sql,get_db_conn());
        if ($rows = pmysql_fetch_object($result)) {
            if (strlen($rows->maxproductcode)==18) {
                $productcode = ((int)$rows->maxproductcode)+1;
                $productcode = str_pad($productcode, 18, '0', STR_PAD_LEFT);
            } else if($rows->maxproductcode==NULL){
                $productcode = $catecode."000001";
            } 
            pmysql_free_result($result);
        } else {
            $productcode = $catecode."000001";
        }
    }
	$prodcode_info['productcode']	= $productcode;
	$prodcode_info['catecode']	= $catecode;
    return $prodcode_info;
}


?>