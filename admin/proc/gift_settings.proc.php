<?php
/**
 * 사은품처리 프로세싱
 */
$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");

$marketgift = new Gift;
$mode = $_POST['mode'];
$tbl = $cfg_tbl['gift'];
$idx = $_POST['idx'];

if($mode == 'reg') { //사은품등록
	$record = array(
		'giftname'=>$_POST['giftname'],
		'gift_comment'=>$_POST['gift_comment'],
		'display' => $_POST['display'],
		'price_s' => $_POST['price_s'],
		'price_e' => $_POST['price_e']
	);

	if($idx) {
		//사은품정보수정
		$record['quantity']=($_POST['quantity']+$_POST['quantity_sale']);
		$where = array('idx'=>$idx);
		$sql = sqlUpdate($record, $tbl, $where);
		$rs = $marketgift->adodb->Execute($sql);

	}
	else { //사은품정보등록
		//사은품코드 중복체크
		$exist = $marketgift->get_gift_cnt("giftcode='".$_POST['giftcode']."'");
		if($exist > 0) {
			return_json(false,$_ALERT['P100']); //이미 존재하는 사은품코드입니다.
			exit;
		}

		$record['giftcode']=$_POST['giftcode'];
		$record['quantity']=$_POST['quantity'];
		$record['date_insert'] = NOW;

		$sql = sqlInsert($record, $tbl);
		$rs = $marketgift->adodb->Execute($sql);
		$idx = $marketgift->adodb->insert_id();
	}

	if($rs) {

		//이미지 업데이트
		if($_FILES['gift_image_path']) {
			include_once $Dir."lib/upload.class.php";
			$dir = DIRECTORY_SEPARATOR.ImageDir.'gift/'; //이미지저장디렉토리
			$handle = new upload($_FILES['gift_image_path']);
			if ($handle->uploaded) {
				$handle->file_new_name_body = $no.'_'.date('YmdHis');
				$file_rs = $handle->process(DOC_ROOT.$dir);

				if ($handle->processed) {
					$path = $dir.$handle->file_dst_name;
					$marketgift->adodb->Execute("UPDATE {$tbl} SET gift_image_path='$path' WHERE idx='{$idx}'");
					@unlink(DOC_ROOT.$old['gift_image_old']);
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
else if($mode == 'del') { //삭제
	//판매수량이 유무체크
	$old = $marketgift->getGiftRow($idx);
	if($old['quantity_sale']>0) { //판매수량이 0이상인경우 삭제불가
		return_json(false,$_ALERT['P101']);
	}

	

	$rs = $marketgift->remove_gift("idx='{$idx}'");

	if($rs) {
		//저장이미지 삭제
		if(is_file(DOC_ROOT.$old['gift_image_path'])) @unlink(DOC_ROOT.$old['gift_image_path']);
		return_json(true,'삭제되었습니다.');
	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}
?>
