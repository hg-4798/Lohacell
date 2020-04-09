<?php
/**
 * 상품컬러 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */



$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Product = new PRODUCT;
$mode = $_POST['mode'];
$act = $_POST['act'];
$tbl = $Product->tbls['product_color'];

if($mode == 'list') {
	if($act == 'remove') {
		
		
		$tbl_option = $Product->tbls['product_option'];

		//컬러정보
		//$color = 

		//컬러정보삭제
		$idx = $_POST['idx'];
		$sql = "DELETE FROM {$tbl} WHERE idx='{$idx}'";
		$rs = $Product->adodb->Execute($sql);
		if($rs) {
			return_json(true,'삭제되었습니다.');
			//상품옵션삭제 @todo
			//$Product->adodb->Execute("DELETE FROM {$tbl_option} WHERE option_code='{$idx}'");


			$Product->setColorTree(); //컬러칩 json생성
		}
		else {
			return_json(false,$_ALERT['C003']); //처리중오류
		}
		//옵션에 등록된 컬러 정보 삭제

	}
	else if($act == 'batch_use') { //사용여부일괄변경
		// pre($_POST);
		$use_yn = $_POST['use_yn'];
		$idx = $_POST['idx'];
		$sql = "UPDATE {$tbl} SET use_yn='{$use_yn}', date_update='".NOW."', admin_id='".$_ShopInfo->id."' WHERE idx IN ({$idx})";
		
		$rs = $Product->adodb->Execute($sql);
		if($rs) {
			//if($use_yn == 'Y') //숨김처리
			
			$Product->setColorTree(); //컬러칩 json생성
			return_json(true, '적용되었습니다.');
		}
		else {
			//echo $sql;
			return_json(false,$_ALERT['C003']); //처리중오류
		}
	}
}
else if($mode == 'register') {

	if($_POST['color_group'] == 'etc') {
		$color_group = $_POST['color_group_etc'];
	}
	else $color_group = $_POST['color_group'];

	$record = array(
		'color_group'=>$color_group,
		'color_name'=>$_POST['color_name'],
		'use_yn'=>$_POST['use_yn'],
		'color_cls'=>$_POST['color_cls'],
		'date_update'=>NOW,
		'admin_id'=>$_ShopInfo->id
	);

	$color_code = $_POST['color_code'];
	

	if($_POST['color_idx']) { //수정
		$idx = $_POST['color_idx'];
		$where = array('idx'=>$_POST['color_idx']);
		$sql = sqlUpdate($record, $tbl, $where);
		//echo $sql;exit;
		$rs = $Product->adodb->Execute($sql);
	}
	else { //신규등록

		//컬러코드 중복여부체크
		$color_code = trim($_POST['color_code']);
		$exist = $Product->adodb->getOne("SELECT COUNT(*) FROM {$tbl} WHERE color_code='{$color_code}'");
		if($exist > 0) {
			return_json(false,'이미 등록된 컬러코드입니다.');
		}

		$record['color_code'] = $color_code;
		$record['date_insert'] = NOW;

		$sql = sqlInsert($record, $tbl);
		$rs = $Product->adodb->Execute($sql);
		$idx = $Product->adodb->insert_id();
	}

	if($rs) {
		//컬러칩 이미지 업로드
		if($_FILES['color_img']) {
			include_once $Dir."lib/upload.class.php";
			$dir = DIRECTORY_SEPARATOR.ImageDir.'colorchip/'; //이미지저장디렉토리
			$handle = new upload($_FILES['color_img']);
			if ($handle->uploaded) {
				$handle->file_new_name_body = $color_code.'_'.date('YmdHis');
				$file_rs = $handle->process(DOC_ROOT.$dir);

				if ($handle->processed) {
					$path = $dir.$handle->file_dst_name;
					$Product->adodb->Execute("UPDATE {$tbl} SET color_img='$path' WHERE idx='{$idx}'");
					$handle->clean();
				} else {
				}
			}
		}

		$Product->setColorTree(); //컬러칩 json생성
		return_json(true,'저장되었습니다.');
	}
	else {
		return_json(false,$_ALERT['C003']); //처리중오류
	}
}

