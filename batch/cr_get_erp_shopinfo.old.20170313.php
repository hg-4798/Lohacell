#!/usr/local/php/bin/php
<?php
#######################################################################################
# FileName          : cr_get_erp_shopinfo.php
# Desc              : 매일 자정에 실행되어 ERP로부터 매장정보 가져오기
# Last Updated      : 2016-09-01
# By                : JeongHo,Jeong
##!/usr/local/php/bin/php
# [deco@deco1 batch]$ ./run_get_erp_shopinfo.sh 
#######################################################################################

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

@set_time_limit(0);

#$conn = oci_connect("SMK_ONLINE", "SMK_ONLINE_0987", "1.209.88.42/ORA11", "KO16KSC5601");
//$conn = oci_connect("SMK_ONLINE", "SMK_ONLINE_0987", "1.209.88.42/ORA11", "AL32UTF8");
$conn = GetErpDBConn();

echo "START = ".date("Y-m-d H:i:s")."\r\n";

$sql = "SELECT * 
        FROM 
        (
            SELECT	ROW_NUMBER() OVER(PARTITION BY shopcd ORDER BY insertdt desc) rn, 
                    shopcd, shopnm, telno1, telno2, telno3, faxno1, faxno2, faxno3, 
                    hpno1, hpno2, hpno3, zipcd1, zipcd2, addr1, addr2, circulation, circulationnm, 
                    form, formnm, channel, channelnm, path, pathnm, areacd, areacdnm, commercialcd, commercialcdnm, 
                    progressgb, progressgbnm, opendt, shopstopdt, shopclosedt 
            FROM	".$erp_account.".IF_ONLINE_SHOPINFO 
            WHERE	1=1 
            AND		pathnm = 'HOT-T' 
            AND		useyn = 'Y' 
        ) a 
        WHERE	rn = 1 
        ";
$smt = oci_parse($conn, $sql);
oci_execute($smt);
//echo $sql."\r\n";
//exit;

$cnt = 0;
while($data = oci_fetch_array($smt, OCI_BOTH+OCI_RETURN_NULLS+OCI_RETURN_LOBS)) {

    foreach($data as $k => $v)
    {
        $data[$k] = pmysql_escape_string($v);
    }

    echo "shopcd = ".$data[SHOPCD]." / shopnm = ".$data[SHOPNM]." / areacdnm = ".$data[AREACDNM]."\r\n";

    $sql = "
            WITH upsert as (
                update  smk_erp.if_online_shopinfo sp 
                set 	shopnm      = '".$data[SHOPNM]."', 
                        telno1      = '".$data[TELNO1]."', 
                        telno2      = '".$data[TELNO2]."', 
                        telno3      = '".$data[TELNO3]."', 
                        faxno1      = '".$data[FAXNO1]."', 
                        faxno2      = '".$data[FAXNO2]."', 
                        faxno3      = '".$data[FAXNO3]."', 
                        hpno1       = '".$data[HPNO1]."', 
                        hpno2       = '".$data[HPNO2]."', 
                        hpno3       = '".$data[HPNO3]."', 
                        zipcd1      = '".$data[ZIPCD1]."', 
                        zipcd2      = '".$data[ZIPCD2]."', 
                        addr1       = '".$data[ADDR1]."', 
                        addr2       = '".$data[ADDR2]."', 
                        circulation = '".$data[CIRCULATION]."', 
                        circulationnm = '".$data[CIRCULATIONNM]."', 
                        form        = '".$data[FORM]."', 
                        formnm      = '".$data[FORMNM]."', 
                        channel     = '".$data[CHANNEL]."', 
                        channelnm   = '".$data[CHANNELNM]."', 
                        path        = '".$data[PATH]."', 
                        pathnm      = '".$data[PATHNM]."', 
                        areacd      = '".$data[AREACD]."', 
                        areacdnm    = '".$data[AREACDNM]."', 
                        commercialcd = '".$data[COMMERCIALCD]."', 
                        commercialcdnm = '".$data[COMMERCIALCDNM]."', 
                        progressgb  = '".$data[PROGRESSGB]."', 
                        progressgbnm = '".$data[PROGRESSGBNM]."', 
                        opendt      = '".$data[OPENDT]."', 
                        shopstopdt  = '".$data[SHOPSTOPDT]."', 
                        shopclosedt = '".$data[SHOPCLOSEDT]."'
                where	shopcd = '".$data[SHOPCD]."'
                RETURNING * 
            )
            insert into smk_erp.if_online_shopinfo 
            (shopcd, shopnm, telno1, telno2, telno3, faxno1, faxno2, faxno3, 
             hpno1, hpno2, hpno3, zipcd1, zipcd2, addr1, addr2, circulation, circulationnm, 
             form, formnm, channel, channelnm, path, pathnm, areacd, areacdnm, commercialcd, commercialcdnm, 
             progressgb, progressgbnm, opendt, shopstopdt, shopclosedt  )
            Select  '".$data[SHOPCD]."','".$data[SHOPNM]."', '".$data[TELNO1]."', '".$data[TELNO2]."', '".$data[TELNO3]."', '".$data[FAXNO1]."', '".$data[FAXNO2]."', '".$data[FAXNO3]."', 
                    '".$data[HPNO1]."','".$data[HPNO2]."','".$data[HPNO3]."','".$data[ZIPCD1]."', '".$data[ZIPCD2]."', '".$data[ADDR1]."','".$data[ADDR2]."','".$data[CIRCULATION]."','".$data[CIRCULATIONNM]."',
                    '".$data[FORM]."', '".$data[FORMNM]."', '".$data[CHANNEL]."','".$data[CHANNELNM]."','".$data[PATH]."', '".$data[PATHNM]."', '".$data[AREACD]."', '".$data[AREACDNM]."','".$data[COMMERCIALCD]."','".$data[COMMERCIALCDNM]."',
                    '".$data[PROGRESSGB]."','".$data[PROGRESSGBNM]."','".$data[OPENDT]."', '".$data[SHOPSTOPDT]."','".$data[SHOPCLOSEDT]."'
            WHERE NOT EXISTS ( SELECT * FROM upsert )
            ";
    $ret = pmysql_query($sql, get_db_conn());
    #exdebug($sql);
    if($err=pmysql_error()) echo $err."\r\n";

    $cnt++;

    if( ($cnt%1000) == 0) echo "cnt = ".$cnt."\r\n";
}

oci_free_statement($smt);
oci_close($conn);

echo "END = ".date("Y-m-d H:i:s")."\r\n";
?>
