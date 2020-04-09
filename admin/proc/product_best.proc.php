<?php
/**
 * 베스트/추천상품 프로세싱
 * @author  stickcandy81@nate.com
 */


$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");
include_once($Dir."lib/product.class.php");

$product = new PRODUCT;
$adodb = adodb_connect();
$mode = $_POST['mode'];
$act = $_POST['act'];

$tbl = 'tblproduct_best';

$updater_id = $_ShopInfo->id; //최종수정자아이디(관리자)
$updater_name = $_ShopInfo->name; //최종수정자이름(관리자)

if($mode == 'best') { //등록/수정
	if($act == 'save') {
		$category = $_POST['category_d2'];
		$productcode_arr = array_unique(array_filter(explode(',',$_POST['productcode'])));

		$success = true;
		$adodb->Execute("UPDATE {$tbl} SET is_delete='Y' WHERE kind='{$mode}' AND category='{$category}'"); //임시삭제플래그
		foreach($productcode_arr as $k=>$v) {
			$sort = $k+1;
			$sql = "INSERT INTO {$tbl} (category, productcode, kind, sort, updater_id, updater_name, date_update, is_delete) VALUES ('{$category}','{$v}','{$mode}','{$sort}','{$updater_id}','{$updater_name}',NOW(), 'N')
					ON CONFLICT (category, productcode, kind) DO UPDATE SET sort='{$sort}', updater_id='{$updater_id}', updater_name='{$updater_name}', date_update=NOW(), is_delete='N'";
			//echo $sql;exit;
			$rs = $adodb->Execute($sql);
			if(!$rs) $success = fasle;
		}


		

		if($success) {
			$adodb->Execute("DELETE FROM {$tbl} WHERE is_delete='Y' AND kind='{$mode}' AND category='{$category}'"); //미사용데이터삭제
			//ECHO "DELETE FROM {$tbl} WHERE is_delete='Y' AND category='{$category}'";
			return_json(true, '저장되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}

}
else if($mode == 'recommend') {
	if($act == 'save') {
		$category = $_POST['category_d3'];
		$productcode_arr = array_unique(array_filter(explode(',',$_POST['productcode'])));

		$success = true;
		$adodb->Execute("UPDATE {$tbl} SET is_delete='Y' WHERE kind='{$mode}' AND category='{$category}'"); //임시삭제플래그
		foreach($productcode_arr as $k=>$v) {
			$sort = $k+1;
			$sql = "INSERT INTO {$tbl} (category, productcode, kind, sort, updater_id, updater_name, date_update, is_delete) VALUES ('{$category}','{$v}','{$mode}','{$sort}','{$updater_id}','{$updater_name}',NOW(), 'N')
					ON CONFLICT (category, productcode, kind) DO UPDATE SET sort='{$sort}', updater_id='{$updater_id}', updater_name='{$updater_name}', date_update=NOW(), is_delete='N'";
			$rs = $adodb->Execute($sql);
			if(!$rs) $success = fasle;
		}


		

		if($success) {
			$adodb->Execute("DELETE FROM {$tbl} WHERE is_delete='Y' AND kind='{$mode}' AND category='{$category}'"); //미사용데이터삭제
			//ECHO "DELETE FROM {$tbl} WHERE is_delete='Y' AND category='{$category}'";
			return_json(true, '저장되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}

	}
}
else if($mode == 'mdchoice') {
    if($act == 'save') {
        $category = ($_POST['category_d3']=="")?$_POST['category_d2']:$_POST['category_d3'];
        $productcode_arr = array_unique(array_filter(explode(',',$_POST['productcode'])));

        $success = true;
        $adodb->Execute("UPDATE {$tbl} SET is_delete='Y' WHERE kind='{$mode}' AND category='{$category}'"); //임시삭제플래그
        foreach($productcode_arr as $k=>$v) {
            $sort = $k+1;
            $sql = "INSERT INTO {$tbl} (category, productcode, kind, sort, updater_id, updater_name, date_update, is_delete) VALUES ('{$category}','{$v}','{$mode}','{$sort}','{$updater_id}','{$updater_name}',NOW(), 'N')
					ON CONFLICT (category, productcode, kind) DO UPDATE SET sort='{$sort}', updater_id='{$updater_id}', updater_name='{$updater_name}', date_update=NOW(), is_delete='N'";
            $rs = $adodb->Execute($sql);
            if(!$rs) $success = fasle;
        }




        if($success) {
            $adodb->Execute("DELETE FROM {$tbl} WHERE is_delete='Y' AND kind='{$mode}' AND category='{$category}'"); //미사용데이터삭제
            //ECHO "DELETE FROM {$tbl} WHERE is_delete='Y' AND category='{$category}'";
            return_json(true, '저장되었습니다.');
        }
        else {
            return_json(false,'잠시 후에 다시 시도해주세요.');
        }

    }
}
