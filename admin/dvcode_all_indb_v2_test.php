<?
// =========================================================================================
// FileName         : dvcode_all_indb.php
// Desc             : csv파일 업로드해서 일괄 송장정보 업데이트
// By               : moondding2
// Last Updated     : 2016.03.13
// =========================================================================================

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$uploaddir = $Dir . "uploads/";
$uploadfile = $uploaddir . "_20170623.csv";

$type       = $_POST["mode"];
$delimailok = $_POST["delimailtype"]?$_POST["delimailtype"]:"Y";	//배송완료에 따른 메일/SMS발송 여부 (Y:발송, N:발송안함)

$exe_id		= $_ShopInfo->getId()."|".$_ShopInfo->getName()."|admin";	// 실행자 아이디|이름|타입
//exdebug(move_uploaded_file($_FILES['csv_file']['tmp_name'], $uploadfile));
//if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $uploadfile)) {

    $sql = "select code, lower(company_name) as company_name from tbldelicompany";
    $result = pmysql_query($sql);
 
    $arrDeliCompany = array();   
    while ( $row = pmysql_fetch_object($result) ) {
        $arrDeliCompany[$row->company_name] = $row->code;
    }
    pmysql_free_result($result);

    // 필드 0 : idx
    // 필드 1 : order code
    // 필드 2 : 입금일
    // 필드 3 : 상품명
    // 필드 4 : 상품코드
    // 필드 5 : 창고품목코드
    // 필드 6 : 품번
    // 필드 7 : 색상
    // 필드 8 : 사이즈
    // 필드 9 : 결제수단
    // 필드 10 : 판매가
    // 필드 11 : 수량
    // 필드 12 : 수령자
    // 필드 13 : 우편번호
    // 필드 14 : 주소
    // 필드 15 : 전화번호
    // 필드 16 : 비상전화
    // 필드 17 : 비고
    // 필드 18 : 주문자
    // 필드 19 : 주문자ID
    // 필드 20 : 주문자우편번호
    // 필드 21 : 주문자주소
    // 필드 22 : 주문자전화번호
    // 필드 23 : 주문자핸드폰
    // 필드 24 : 배송업체명
    // 필드 25 : 송장번호
    // 필드 26 : O2O구분
    // 필드 27 : 매장정보
    // 필드 28 : 우선창고코드
    // 필드 29 : 재고
    // 필드 30 : A1801
    // 필드 31 : A1770
    // 필드 32 : A1771

    $handle = fopen($uploadfile,  "r"); 

    $fieldCount = 33;

    $arrResultMsg = array();
    $tmp_arr_deli = array();
    $arr_deli_idxs = array();
    $rowCount = 0; 
    while (($data = fgetcsv($handle, 135000, ",")) !== FALSE) {
        if ( $rowCount == 0 ) {
            // 첫번째 라인은 pass
            $rowCount++;
            continue;
        }

        //echo "cnt = ".count($data)."<br>";
 
        if ( $data && count($data) == $fieldCount || true ) {
            $idx            = trim($data[0]);
            $ordercode      = trim($data[1]);
            $productcode    = trim($data[4]);
            $option         = iconv("euc-kr", "utf-8", trim($data[8]));
            /*$temp = explode("/", $option);
            $temp = array_notnull($temp);

            $opt1_name = $opt2_name = "";
            foreach($temp as $k => $v) {

                $opt1_tmp = explode(":", $v);

                $opt1_name .= trim($opt1_tmp[0])."@#";
                $opt2_name .= trim($opt1_tmp[1])."@#";
            }*/
            $opt1_name = 'SIZE';
            $opt2_name = $option;

            $self_goods_code= trim($data[5]);
            //$deli_name      = iconv("euc-kr", "utf-8", strtolower(trim($data[22])));
            $deli_name      = iconv("euc-kr", "utf-8", trim($data[24]));
            $deli_num       = trim($data[25]);

            // 택배업체 코드번호 조회
            /*
            $deli_com   = "";
            if ( isset($arrDeliCompany[$deli_name]) ) { 
                $deli_com = $arrDeliCompany[$deli_name];
            }
            */
            list($deli_com) = pmysql_fetch("select code from tbldelicompany where lower(company_name) = lower('".$deli_name."')");

            /*
            // order_idx 값 구하기
            $subsql  = "SELECT idx FROM tblorderproduct ";
            $subsql .= "WHERE ordercode = '{$ordercode}' AND productcode = '{$productcode}' ";
            $subsql .= "AND opt1_name = '{$opt1_name}' AND opt2_name = '{$new_opt2_name}' ";
            list($idx) = pmysql_fetch($subsql);
            */

            //$arrResult = array($ordercode, $productcode, $opt1_name, $opt2_name, $deli_name, $deli_num);
            $arrResult = $data;

            $type = "delivery";

            //exdebug($delimailok);

            //$sql = "SELECT * FROM tblorderinfo WHERE ordercode='{$ordercode}'";
            $sql = "select  a.ordercode, b.deli_gbn, a.paymethod, a.sender_tel, a.sender_name, 
                            b.price, b.quantity, b.coupon_price, b.use_point, b.deli_price,
                            (b.price*b.quantity)-b.coupon_price-b.use_point+b.deli_price as act_price, b.store_code	
                    from    tblorderinfo a 
                    join    tblorderproduct b on a.ordercode = b.ordercode 
                    where   a.ordercode = '{$ordercode}' 
                    and     b.idx = ".$idx."
                    ";
			exdebug($sql);
            $result = pmysql_query($sql,get_db_conn());
            $_ord = pmysql_fetch_object($result);
            pmysql_free_result($result);

			$pgid_info="";
			$pg_type="";
			switch ($_ord->paymethod[0]) {
				case "B":
					break;
				case "V":
					$pgid_info=GetEscrowType($_shopdata->trans_id);
					$pg_type=$pgid_info["PG"];
					break;
				case "O":
					$pgid_info=GetEscrowType($_shopdata->virtual_id);
					$pg_type=$pgid_info["PG"];
					break;
				case "Q":
					$pgid_info=GetEscrowType($_shopdata->escrow_id);
					$pg_type=$pgid_info["PG"];
					break;
				case "C":
					$pgid_info=GetEscrowType($_shopdata->card_id);
					$pg_type=$pgid_info["PG"];
					break;
				case "P":
					$pgid_info=GetEscrowType($_shopdata->card_id);
					$pg_type=$pgid_info["PG"];
					break;
				case "M":
					$pgid_info=GetEscrowType($_shopdata->mobile_id);
					$pg_type=$pgid_info["PG"];
					break;
			}
			$pg_type=trim($pg_type);

            if($type=="delivery" && ord($ordercode)) {
                
                if(strstr("NXS",$_ord->deli_gbn)) {

                    $patterns = array(" ","_","-");
                    $replace = array("","","");
                    $deli_num = str_replace($patterns,$replace,$deli_num);
                    $deli_num = sprintf("%.0f", $deli_num); // 지수형태일 경우 숫자로 변환.

                    ###에스크로 서버에 배송정보 전달 - 에스크로 결제일 경우에만.....

					//배송한 상품의 수를 체크한다.
					list($op_deli_cnt)=pmysql_fetch_array(pmysql_query("select count(idx) as op_idx_cnt from tblorderproduct WHERE ordercode='{$ordercode}' AND deli_gbn = 'Y' "));
					list($deli_name)=pmysql_fetch_array(pmysql_query("SELECT company_name FROM tbldelicompany WHERE code='{$deli_com}' "));
					list($pro_count)=pmysql_fetch_array(pmysql_query("select count(idx) from tblorderproduct WHERE ordercode='{$ordercode}' AND idx != '".$idx."' "));

					if ($op_deli_cnt==0) { // 처음 배송된 상품일 경우
						if(ord($deli_name)==0) {
							$deli_name="자가배송";
							$deli_num="0000";
						}
					}
					

                    $deliQry = "";
                    if( strlen( $deli_num ) > 0 && strlen( $deli_com ) > 0 ){
                        $deliQry = ", deli_com = '".$deli_com."', deli_num = '".$deli_num."' ";
                    }
                    
                    $sql = "UPDATE tblorderproduct SET deli_gbn='Y', deli_date='".date("YmdHis")."' ".$deliQry;
                    $sql.= "WHERE ordercode='{$ordercode}' ";
                    $sql.= "AND idx = {$idx} ";
					$sql.= "AND op_step < 40 ";
					exdebug($sql);

					$sql = "UPDATE  tblorderinfo SET deli_gbn='Y', deli_date='".date("YmdHis")."' ";
					$sql.= "WHERE   ordercode='{$ordercode}' ";
					exdebug($sql);

                    $isupdate=true;

                    array_push($arrResult, iconv('UTF-8', 'EUC-KR', '성공'));
                    array_push($arrResultMsg, $arrResult);
                } elseif(!strstr("NXS",$_ord->deli_gbn)) {
                    $errmsg = "이미 취소되거나 발송된 물품입니다. 다시 확인하시기 바랍니다.";
                    array_push($arrResult, iconv('UTF-8', 'EUC-KR', $errmsg));
                    array_push($arrResultMsg, $arrResult);
                }
            }

        }

        $rowCount++;
    } 
    fclose($handle); 

    if ( count($arrResultMsg) > 0 ) {
?>

<html>
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head> 
<body>  

<table border="1">
    <tr align="center">
        <th>고유번호</th>
        <th>주문코드</th>
        <th>입금일</th>
        <th>상품명</th>
        <th>상품코드</th>
        <th>창고품목코드</th>
        <th>품번</th>
        <th>색상</th>
        <th>사이즈</th>
        <th>결제수단</th>
        <th>판매가</th>
        <th>수량</th>
        <th>수령자</th>
        <th>우편번호</th>
        <th>주소</th>
        <th>전화번호</th>
        <th>비상전화</th>
        <th>비고</th>
        <th>주문자</th>
        <th>주문자ID</th>
        <th>주문자우편번호</th>
        <th>주문자주소</th>
        <th>주문자전화번호</th>
        <th>주문자핸드폰</th>
        <th>배송업체명</th>
        <th>송장번호</th>
        <th>O2O구분</th>
        <th>매장번호</th>
        <th>우선창고코드</th>
        <th>재고</th>
        <th>A1801</th>
        <th>A1770</th>
        <th>A1771</th>
        <th>결과</th>
    </tr>
<?php 
        foreach ( $arrResultMsg as $arrData ) {
            echo "<tr>";
            foreach ( $arrData as $data ) {
                echo "<td>".iconv('EUC-KR', 'UTF-8', "'".$data."'")."</td>";
                //echo "<td>{$data}</td>";
            }
            echo "</tr>";
        }
?>
</table>
</body>
</html>

<?php
    }
//}
?>
