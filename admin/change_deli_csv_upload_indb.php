<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."/admin/_config/excel_format.php");
include DOC_ROOT.'/third_party/PHPExcel/Classes/PHPExcel.php';
include("access.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

$Excel = new PHPExcel();

$filename = $_FILES['csv_file']['tmp_name'];

// 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.

// 업로드한 PHP 파일을 읽어온다.

$objPHPExcel = PHPExcel_IOFactory::load($filename);

$sheetsCount = $objPHPExcel -> getSheetCount();




$objPHPExcel -> setActiveSheetIndex(0);

$sheet = $objPHPExcel -> getActiveSheet();

$highestRow = $sheet -> getHighestRow();   			           // 마지막 행

$highestColumn = $sheet -> getHighestColumn();	// 마지막 컬럼

$update_arr = array();


$delicomlist=array();
$sql="SELECT company_name, code FROM tbldelicompany ORDER BY company_name ";
$result=pmysql_query($sql,get_db_conn());

while($row=pmysql_fetch_array($result)) {
	$delicomlist[$row['company_name']]=$row['code'];
}

// 한줄읽기
for($row = 1; $row <= $highestRow; $row++) {

	// $rowData가 한줄의 데이터를 셀별로 배열처리 된다.

	$rowData = $sheet->rangeToArray("A" . $row . ":" . $highestColumn . $row, NULL, TRUE, FALSE);

	if($row == 1) {
		$column_matching = array("상품별주문번호(송장번호 일괄수정용 필수값)" => "idx", "택배사" => "delivery_company", "송장번호" => "delivery_no");

		$idx_key = array_search("상품별주문번호(송장번호 일괄수정용 필수값)", $rowData[0]);
		$deli_no_key = array_search("택배사", $rowData[0]);
		$deli_com_key = array_search("송장번호", $rowData[0]);

	}else {
		$update_arr[$row]['idx'] = $rowData[0][$idx_key];
		$update_arr[$row]['delivery_company'] = $delicomlist[$rowData[0][$deli_no_key]];
		$update_arr[$row]['delivery_no'] = $rowData[0][$deli_com_key];
	}

	// $rowData에 들어가는 값은 계속 초기화 되기때문에 값을 담을 새로운 배열을 선안하고 담는다.

	//$allData[$row] = $rowData[0];

}
$success_productcnt = 0;
$fail_productcnt = 0;
$fail_product = "";
$fail_msg = "";
$success_msg = "";
$return_msg = "";
foreach($update_arr as $k => $v){
	if(!isNull(trim($v['idx']))){
		$record = array('delivery_company'=>$v['delivery_company'],'delivery_no'=>$v['delivery_no']);
		$where = array('idx'=>$v['idx']);
		$update_sql = sqlUpdate($record, "tblorder_product", $where);
		//echo "<br>".$update_sql;
		if (!pmysql_query($update_sql)) {
			$fail_product .= $v['idx'].", ";
		} else {
			$success_productcnt++;
		}
	}else{
		$fail_msg .= $k."번 ";
	}
}

if($fail_productcnt > 0){
	$fail_product .= "송장번호 수정 실패.<br>";
	$log_dir = "excel.txt";
	Common::log_file($fail_product,$log_dir);
}

if($success_productcnt>0) {
	$success_msg .= "송장번호수정 성공<br>";
}
if($fail_msg != "") {
	$fail_msg .= "줄의 필수값이 없습니다.";
}
$return_msg = $success_msg.$fail_msg;
return_json(true, $return_msg);
exit;

$log_dir = "excel.txt";
Common::log_file($result,$log_dir);
return_json(true, $allData);


exit;
//setlocale(LC_CTYPE, 'ko_KR.UTF-8');
setlocale(LC_CTYPE, 'ko_KR.eucKR');

header( 'Content-type: application/vnd.ms-excel' );
header( 'Content-Disposition: attachment; filename='.'['. strftime( '%y년%m월%d일' ) .'] 송장번호수정 결과'.'.xls' );
header( 'Content-Description: PHP4 Generated Data' );

echo '
		<html>
		<head>
		<title>list</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<style>.xl31{mso-number-format:"0_\)\;\\\(0\\\)";}</style>
		</head>
		<body>
		<table border="1">
		';
print '<tr>';
print '<td>No.</td>';
print '<td>비고</td>';
print '</tr>';

$mode = $_POST['mode'];
$success_productcnt = 0;
$success_categorycnt = 0;

if($_FILES['csv_file']['tmp_name']) {
	$fp = fopen($_FILES['csv_file']['tmp_name'], 'r');

	$column_matching = array("No" => "No", "관리자 상품코드" => "productcode", "ERP코드(필수)" => "prodcode", "컬러코드(필수)" => "colorcode", "상품명(한글)" => "productname_kor"
	, "카테고리" => "c_category", "원산지" => "madein", "성별" => "sex", "판매상태" => "soldout", "진열유무" => "display");

	$FieldNm = array();
	$fieldList = array();
	$i = 0;
	$productsql_array = array();
	$categorysql_array = array();
	$must_val = true;       //필수값 체크 변수

	while ($fields = fgetcsv($fp, 135000, ',')) {
		if ($must_val) {
			$j = 0;
			foreach ($fields as $fieldnum => $column) {
				$column = mb_convert_encoding($column, "UTF-8", "EUC-KR");

				echo $column;
				if ($i == 0) {
					if (isset($column_matching[$column])) {
						$fieldList[$j] = $column_matching[$column];
					}
				} else if ($i == 1) {
					continue;
				} else {
					//필수값 체크
					if(isset($fieldList[$j])) {
						if ($fieldList[$j] == "colorcode" || $fieldList[$j] == "prodcode") {
							if (trim($column) == '') {
								print '<tr>';
								print '<td colspan="2">' . ($i + 1) . '번째 줄 ' . $fieldList[$j] . '을 입력해주세요</td>';
								print '</tr>';
								//echo ($i+1)."번째 줄 ".$fieldList[$j] . "을 입력해주세요";
								$must_val = false;
								break;
							}
						}
						if ($fieldList[$j]) {
							//내용이 없으면 제외
							if (trim($column) != "") {
								$FieldNm[$i][$fieldList[$j]] = $column;
								//No, c_category등 tblproduct에서 수정하는 항목이 아닌 것 제외
								if ($fieldList[$j] != "No" && $fieldList[$j] != "c_category" && $fieldList[$j] != "prodcode" && $fieldList[$j] != "colorcode" && $fieldList[$j] != "productcode" && $fieldList[$j] != "productname_kor") {
									$productsql_array[$i][] = $fieldList[$j] . "='" . pmysql_escape_string($column) . "'";
								} else if ($fieldList[$j] == "c_category") {
									$categorysql_array[$i][] = $fieldList[$j] . "='" . str_replace("'","",pmysql_escape_string($column)) . "'";
								} else if ($fieldList[$j] == "productname_kor"){
									$productsql_array[$i][] = $fieldList[$j] . "='" . pmysql_escape_string($column) . "', productname = '".pmysql_escape_string($column)."'";
								}
							}
						}
					}
				}
				$j++;
			}
			$i++;
		}
	}

	if ($must_val) {
		foreach ($productsql_array as $k => $v) {
			$productsql_array[$k] = implode(', ', $v);
		}
		foreach ($categorysql_array as $k => $v) {
			$categorysql_array[$k] = implode(', ', $v);
		}

		//상품테이블 수정 쿼리
		foreach ($productsql_array as $k => $v) {
			$failfield = "";
			if(!in_array($FieldNm[$k]['sex'],array('U','F','M'))){
				$failfield .= " 성별";
			}
			if(!in_array($FieldNm[$k]['soldout'],array('Y','N'))){
				$failfield .= " 판매상태";
			}
			if(!in_array($FieldNm[$k]['display'],array('Y','N'))){
				$failfield .= " 진열유무";
			}

			if($k!="0" && $k!="1") {
				if ($failfield == "") {
					$productsql = " UPDATE tblproduct SET " . $v . " WHERE prodcode = '" . $FieldNm[$k]['prodcode'] . "' AND colorcode = '" . $FieldNm[$k]['colorcode'] . "' ";
					//echo $productsql;
					if (!pmysql_query($productsql)) {
						print '<tr>';
						print '<td>' . $FieldNm[$k]['No'] . '</td>';
						print '<td>상품 수정 실패</td>';
						print '</tr>';
						//echo $FieldNm[$k]['No'] . "상품 수정 실패";
					} else {
						$success_productcnt++;
					}
				} else {
					print '<tr>';
					print '<td>' . $FieldNm[$k]['No'] . '</td>';
					print '<td>상품 수정 실패' . $failfield . ' 잘못 입력되었습니다.</td>';
					print '</tr>';
				}
			}
		}

		//카테고리테이블 수정 쿼리
		foreach ($categorysql_array as $k => $v) {
			$where_category_val = str_replace("'","",$FieldNm[$k]['c_category']);
			$iscategory = 0;
			$iscategory_sql = "SELECT count(code_a)
                                FROM tblproductcode
                                WHERE code_a||code_b||code_c||code_d = '".$where_category_val."'";
			//echo $iscategory_sql;
			list($iscategory)=pmysql_fetch_array(pmysql_query($iscategory_sql));

			if($iscategory == 0) {
				print '<tr>';
				print '<td>' . $FieldNm[$k]['No'] . '</td>';
				print '<td>카테고리 수정 실패 해당 카테고리가 존재하지 않습니다.</td>';
				print '</tr>';
			}else {
				$categorysql = " UPDATE tblproductlink SET " . $v . " WHERE c_maincate = 1 AND c_productcode = (SELECT productcode FROM tblproduct WHERE prodcode = '" . $FieldNm[$k]['prodcode'] . "' AND colorcode = '" . $FieldNm[$k]['colorcode'] . "') ";
				//echo $categorysql;
				if (!pmysql_query($categorysql)) {
					print '<tr>';
					print '<td>' . $FieldNm[$k]['No'] . '</td>';
					print '<td>카테고리 수정 실패</td>';
					print '</tr>';
					//echo $FieldNm[$k]['No'] . "카테고리 수정 실패";
				} else {
					$success_categorycnt++;
				}
			}
		}

		print '<tr>';
		print '<td colspan="2">'.$success_productcnt.'개의 상품 수정이 완료되었습니다.</td>';
		print '</tr>';
		//echo $success_productcnt . "개의 상품 수정이 완료되었습니다.";

		print '<tr>';
		print '<td colspan="2">'.$success_categorycnt.'개의 상품의 카테고리 수정이 완료되었습니다.</td>';
		print '</tr>';
		//echo $success_categorycnt . "개의 상품의 카테고리 수정이 완료되었습니다.";


		/*        echo "<br>productsql : ";
				debug($productsql_array);
				echo "<br>productsql끝";

				echo "<br>categorysql : ";
				debug($categorysql_array);
				echo "<br>categorysql끝";

				debug($fieldList);
				debug($FieldNm);*/

	}
}else{
	print '<tr>';
	print '<td colspan = "2">데이터가 없습니다.</td>';
	print '</tr>';
}
echo '
		</table>
		</body>
		</html>
		';
exit;
?>