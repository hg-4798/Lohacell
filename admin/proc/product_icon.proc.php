<?php
/**
 * 상품아이콘 프로세싱
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
$attach = new ATTACH;

$adodb = adodb_connect();
$mode = $_POST['mode'];

$tbl = 'tblproduct_icon';

if($mode == 'register') { //등록/수정

	$icon_name=$_POST['icon_name'];
	$icon_idx = $_POST['icon_idx'];

	if($icon_idx > 0) {
		//기존정보
		$old = $product->getIconRow($icon_idx);
		$sql = "UPDATE {$tbl} SET icon_name='{$icon_name}' WHERE idx='{$icon_idx}'";
		//echo $sql;exit;
		$rs = $adodb->Execute($sql);
	}
	else {
		$sql = "INSERT INTO {$tbl} (icon_name, is_fix, date_insert) VALUES('{$icon_name}','N','".NOW."')";
		//echo $sql;exit;
		$rs = $adodb->Execute($sql);
		$icon_idx = $adodb->insert_Id();
	}

	if($rs) {
		//아이콘업로드 및 데이터 업데이트  //파일업로드는 추후작업
		
		if(!empty($_FILES['icon'])) {
			include_once $Dir."lib/upload.class.php";

			$f = UTIL::array_arrange($_FILES['icon']);
			$dir = DIRECTORY_SEPARATOR.ImageDir.'icon/';
			foreach($f as $k=>$v) {
				$handle = new upload($v);
				if ($handle->uploaded) {
					$handle->file_new_name_body = $icon_idx.'_'.$k.'_'.date('YmdHis');
					$file_rs = $handle->process(DOC_ROOT.$dir);

					if ($handle->processed) {
						$path = $dir.$handle->file_dst_name;
						$adodb->Execute("UPDATE {$tbl} SET icon_{$k}='$path' WHERE idx='{$icon_idx}'");
						@unlink(DOC_ROOT.$old['icon_'.$k]);
						$handle->clean();
					} else {
					}
				}
			}
		}
		/**/
		return_json(true,'저장되었습니다.');
	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}
else if($mode == 'delete') { //삭제

	$icon_idx = $_POST['icon_idx'];
	if(!$icon_idx) return_json(false);
	$row = $product->getIconRow($icon_idx);

	$sql = "DELETE FROM {$tbl} WHERE idx='{$icon_idx}'";
	$rs = $adodb->Execute($sql);
	if($rs) {
		//저장아이콘삭제
		if(is_file(DOC_ROOT.$row['icon_pc'])) @unlink(DOC_ROOT.$row['icon_pc']);
		if(is_file(DOC_ROOT.$row['icon_mobile'])) @unlink(DOC_ROOT.$row['icon_mobile']);
		return_json(true,'삭제되었습니다.');
	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}


?>
