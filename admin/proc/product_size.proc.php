<?php
/**
 * 사이즈조견표 프로세싱
 * @author  stickcandy81@nate.com
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");
include_once($Dir."lib/product.class.php");

$product = new PRODUCT;
$adodb = adodb_connect();
$mode = $_POST['mode'];
$act = $_POST['act'];



if($mode == 'size') {
	$tbl = 'tblproduct_size';
	if($act == 'register') { //사이즈등록/수정
		$size_idx=$_POST['size_idx'];
		$size_grp=$_POST['size_grp'];
		$size_name = $_POST['size_name'];
		$info = array_arrange($_POST['info']);
		$size_info = serialize($info);

		$updater_id = $_ShopInfo->id; //최종수정자아이디(관리자)
		$updater_name = $_ShopInfo->name; //최종수정자이름(관리자)

		if($size_idx > 0) {
			//기존정보(for 첨부이미지 삭제)
			$old = $product->getSizeRow($size_idx);
			$sql = "UPDATE {$tbl} SET size_grp='{$size_grp}', size_name='{$size_name}', size_info='{$size_info}', date_update=NOW(), updater_id='{$updater_id}', updater_name='{$updater_name}' WHERE idx='{$size_idx}'";
			$rs = $adodb->Execute($sql);
		}
		else {
			$sql = "INSERT INTO {$tbl} (size_grp, size_name, size_info, date_insert, date_update, updater_id, updater_name) VALUES('{$size_grp}','{$size_name}','{$size_info}', NOW(), NOW(),'{$updater_id}','{$updater_name}')";
			$rs = $adodb->Execute($sql);
			$size_idx = $adodb->insert_Id();
		}

		if($rs) {
			//이미지업로드 및 이디미경로데이터 업데이트
			if(!empty($_FILES['size_image'])) {
				include_once $Dir."lib/upload.class.php";
				$dir = DIRECTORY_SEPARATOR.ImageDir.'product_size/';

				$handle = new upload($_FILES['size_image']);
				if ($handle->uploaded) {
					$handle->file_name_body_add = '_'.date('YmdHis');
					$file_rs = $handle->process(DOC_ROOT.$dir);
					
					if ($handle->processed) {
						$path = $dir.$handle->file_dst_name;
						$adodb->Execute("UPDATE {$tbl} SET size_image='$path' WHERE idx='{$size_idx}'");
						@unlink(DOC_ROOT.$old['size_image']);
						$handle->clean();
					} else {
					}
				}
			}
			return_json(true,'저장되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}
	else if($act == 'delete') { //사이즈삭제 @todo 사이즈 삭제시 조견표 노출데이터도 삭제
		$size_idx = $_POST['size_idx'];
		$old = $product->getSizeRow($size_idx);
		$sql = "DELETE FROM {$tbl} WHERE idx='{$size_idx}'";
		$rs = $adodb->Execute($sql);
		if($rs) {
			//첨부이미지 삭제
			if($old['size_image']) {
				@unlink(DOC_ROOT.$old['size_image']);
			}
			return_json(true,'삭제되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}
	else if($act == 'get_sizename') {
		$size_grp = $_POST['size_grp'];
		$rs = $adodb->getArray("SELECT * FROM tblproduct_size WHERE size_grp='{$size_grp}'");

		return_json(true, '', $rs);
	}
	else if($act == 'duplicate') { //사이즈조견표 중복체크
		$size_grp = $_POST['size_grp'];
		$size_name = trim($_POST['size_name']);
		$sql = "SELECT idx FROM {$tbl} WHERE size_grp='{$size_grp}' AND size_name='{$size_name}'";
		$idx = $adodb->getOne($sql);
		return_json(true, '', array('idx'=>$idx));
	}
}
else if($mode == 'size_show') {
	$tbl = "tblproduct_size_show";
	if($act == 'register') {
		
		$size_idx = $_POST['size_idx'];
		$show_idx = $_POST['show_idx'];

		if($show_idx > 0) {
			$sql = "UPDATE {$tbl} SET size_idx='{$size_idx}', date_update=NOW() WHERE idx='{$show_idx}'";
			$rs = $adodb->Execute($sql);
		}
		else {
			$category_arr = array_filter($_POST['category']);
			$product_category = array_pop($category_arr);

			//노출카테고리 중복체크
			$exist = $adodb->getOne("SELECT COUNT(idx) FROM {$tbl} WHERE product_category='{$product_category}'");
			if($exist > 0) {
				return_json(false,'이미 등록한 노출카테고리입니다.');
			}
			$sql = "INSERT INTO {$tbl} (size_idx, product_category, date_insert, date_update) VALUES('{$size_idx}','{$product_category}',NOW(), NOW())";
			$rs = $adodb->Execute($sql);
			$size_idx = $adodb->insert_Id();
		}

		if($rs) {
			return_json(true,'저장되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
		
	}
	else if($act == 'delete') {
		$show_idx = $_POST['show_idx'];
		$sql = "DELETE FROM {$tbl} WHERE idx='{$show_idx}'";
		//echo $sql;
		$rs = $adodb->Execute($sql);
		if($rs) {
			return_json(true,'삭제되었습니다.');
		}
		else {
			return_json(false,'잠시 후에 다시 시도해주세요.');
		}
	}
}
