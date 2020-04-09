<?php
/**
 * 기획전 프로모션 프로세싱
 */
//@TODO 일반기획전 -> 임직원기획전, 임직원기획전 -> 일반기획전 변경시 상품 삭제 처리해야할듯
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");
$adodb = adodb_connect();

$Promotion = new PROMOTION;
$mode = $_POST['mode'];
$act = $_POST['act'];
if ($mode == 'package') {
	include_once($Dir."lib/product.class.php");
	$product = new PRODUCT;
	$tbl = 'tblpackage_promo';
	if($act == 'register') {
		$idx = $_POST['idx'];
		$productcode_arr = array_unique(array_filter(explode(',',$_POST['productcode']))); //상품중복제거
		$productcodes = implode(',', $productcode_arr);
		if(!empty($idx)) {
			$sql = "UPDATE {$tbl} SET productcodes='{$productcodes}' WHERE idx = '{$idx}'";
		}
		try{
			$rs = $adodb->Execute($sql);
			if($rs) {
				$product->sync_package();
				return_json(true, '저장되었습니다.');
			}
			else {
				return_json(false,'잠시 후에 다시 시도해주세요.');
			}
		} catch (Exception $e){
			return_json(false, $e);
		}
	}
} else if($mode == 'promotion_detail') { //기획전
	$promotion = new PROMOTION();
	$tbl = 'tblpromotion';
	if ($act == 'register') {
		$num = $_POST['num']; //넘어온 기획전 개수
		$pidx = $_POST['pidx']; //넘어온 기획전 idx
		$promotion_arr = $_POST['promotion'];
		Common::escapeData();
		$clean_promotion_arr = array();
		$i = 1; //등록희망하는 기획전 개수만큼만 데이터 가져오기
		$del_except = array();
		foreach ($promotion_arr as $key=>$val) {
			if($i<=$num) {
				$clean_promotion_arr[$key] = $val;
				$i++;
			}
		}
		foreach ($clean_promotion_arr as $k_seq=>$val){
			if(is_numeric($k_seq)) { //기존에 있는 프로모션일 경우 수정
				try {
					$rs = $adodb->Execute("UPDATE {$tbl} SET title = '{$val['title']}', info = '{$val['info']}', display_seq = {$val['display_seq']}, display_tem = {$val['display_tem']}, use_yn = '{$val['use_yn']}' WHERE seq = {$k_seq} ");
					if ($rs) {
						$del_except[] = $k_seq;
						if($val['product_list']) { //기획전 상품없으면 데이터 삭제
							$cnt = $promotion->getSpecialRowCount($k_seq); //기존 special 존재 여부 확인
							if ($cnt > 0) {
								$special_sql = "UPDATE tblspecialpromo SET special_list = '{$val['product_list']}' WHERE special = '{$k_seq}'";
							} else {
								$special_sql = "INSERT INTO tblspecialpromo (special, special_list) VALUES ('{$k_seq}','{$val['product_list']}')";
							}
						}else{
							$special_sql = "DELETE FROM tblspecialpromo WHERE special = '{$k_seq}'";
						}
						$rs = $adodb->Execute($special_sql);
					} else {
						return_json(false, 'update 잠시 후에 다시 시도해주세요.');
					}
				} catch (Exception $e) {
					return_json(false, $e);
				}
			}else{ //새로운 프로모션일 경우 삽입
				$cres = $adodb->Execute( "SELECT count(*) FROM tblpromotion where  promo_idx='{$pidx}' ");
				$isql = "INSERT INTO tblpromotion (	
																title,
																info,
																display_seq,
																display_tem,
																rdate,
																promo_idx,
																use_yn
																) ";
				$isql.= "values (  
								'{$val['title']}',
								'{$val['info']}',
								{$val['display_seq']},
								{$val['display_tem']},
								current_date,
								'{$pidx}',
								'{$val['use_yn']}'
								) RETURNING seq";
				try {
					$row = pmysql_fetch_array(pmysql_query($isql,get_db_conn()));
					$del_except[] = $row['seq'];
					if($row['seq'] && $val['product_list']){ //삽입됐고 기획전상품이 있을경우 기획전상품추가
						$rs = $adodb->Execute("INSERT INTO tblspecialpromo (special, special_list) VALUES ('{$row['seq']}','{$val['product_list']}')");
					}
				} catch (Exception $e) {
					return_json(false, $e);
				}
			}
		}
		if($del_except) {
			$del_except = implode(',', $del_except);
			try {
				$rs = $adodb->Execute("DELETE FROM {$tbl} WHERE promo_idx = '{$pidx}' AND seq NOT IN ({$del_except}) ");
				if ($rs) {
					return_json(true, '저장되었습니다.');
				} else {
					return_json(false, '잠시 후에 다시 시도해주세요.');
				}
			} catch (Exception $e) {
				return_json(false, $e);
			}
		}
	}
}
else if($mode == 'comment') {
	if($act == 'delete') {
		$tbl = $Promotion->tbls['promo_comment'];

		$num = $_POST['num'];
		$sql = "DELETE FROM {$tbl} WHERE num = '{$num}'";
		$rs = $Promotion->adodb->Execute($sql);
		if($rs) {
			return_json(true);
		}
		else {
			return_json(false,$_ALERT['C003']);
		}
	}
}
?>