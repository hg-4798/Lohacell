<?php
/**
 * 리뷰(상품후기) 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

$Dir = "../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$review = new review;
$mode = $_POST['mode'];
$act = $_POST['act'];
$argu = $_POST;

//pre($_POST);exit;
if($mode == 'write') {
	$review_auth = $review->getAuth($argu['productcode'], $argu['option_code'],'', ''); //리뷰작성권한체크

	//리뷰작성 가능여부
	if(is_array($review_auth)){
		$review_auth = 1;
	}

	//작성권한이 없으면
	if($review_auth != 1){
		return_json(false,'리뷰가 이미 등록 되었거나 작성 가능한 리뷰가 없습니다.');
	}

	//TODO 파일용량체크
	//alert 파일 용량을 확인해주세요.

	$tbl = $review->tbls['product_review'];
	$record = array(
		'id'=>MEMID,
		'name'=>MEMNAME,
		'ordercode'=>$argu['ordercode'],
		'productcode'=>$argu['productcode'],
		'marks'=>$_POST['marks'],
		'subject'=>$_POST['subject'],
		'content'=>htmlspecialchars($_POST['content'], ENT_QUOTES),
		'date'=>date('YmdHis'),
		'option_code'=>$argu['option_code']
	);
	if($argu['productorder_idx']){
		$record['productorder_idx']=$argu['productorder_idx'];
	}
	$sql = sqlInsert($record, $tbl);

    //echo  $sql;exit;
	$rs = $review->adodb->Execute($sql);
	if($rs) {
        $uploadFile = '';
        $uploadFile1 = '';
        $up_rfile = '';
        $up_rfile1 = '';
        $rv_type = 0;
		$review_num = $review->adodb->insert_Id();
		//이미지 첨부
		if($_FILES['upfile1']) {
            $dir = DIRECTORY_SEPARATOR.ImageDir.'review/'.date('Ym').'/';
			$handle = new upload($_FILES['upfile1']);
			if ($handle->uploaded) {
				//$handle->file_name_body_add = '_'.date('YmdHis');
                $handle->file_new_name_body = $productcode.'_'.date('YmdHis');
				$file_rs = $handle->process(DOC_ROOT.$dir);

				
				if ($handle->processed) {
					$path = $dir.$handle->file_dst_name;
                    $uploadFile = $path;
                    $rv_type = 1;
                    $up_rfile = $handle->file_src_name;
					$handle->clean();
				} else {
				}
			}
		}
        if($_FILES['upfile2']) {
            $dir = DIRECTORY_SEPARATOR.ImageDir.'review/'.date('Ym').'/';
            $handle = new upload($_FILES['upfile2']);
            if ($handle->uploaded) {
                $handle->file_new_name_body = $productcode.'_'.date('YmdHis');
                $file_rs = $handle->process(DOC_ROOT.$dir);

                if ($handle->processed) {
                    $path = $dir.$handle->file_dst_name;
                    $uploadFile1 = $path;
                    $rv_type = 1;
                    $up_rfile1 = $handle->file_src_name;
                    if(!empty($up_rfile)){
                        $up_rfile = $up_rfile."||".$up_rfile1;
                    }
                    $handle->clean();
                } else {
                }
            }
        }
		$Point = new POINT;
		if($rv_type == 1){
            $review->adodb->Execute("UPDATE {$tbl} SET upfile='{$uploadFile}', up_rfile='{$up_rfile}', upfile2='{$uploadFile1}',type='{$rv_type}' WHERE num='{$review_num}'");
			$Point->plus('review_photo', MEMID);
		}else{
			if(strlen(str_replace(' ', '', $_POST['content'])) >= 30){
				$Point->plus('review_text_long', MEMID);
			}else{
				$Point->plus('review_text_short', MEMID);
			}
		}

		//$review_average = $review->getAverageMarks($productcode); //평균별점
		//$review_average_rate = ($review_average*100/5); //평균별점(%, 5점만점)
		//$review_count = $review->getCount($productcode); //리뷰수
		
		return_json(true,'등록되었습니다.', array('count'=>$review_count,'average'=>$review_average,'average_rate'=>$review_average_rate));
		
	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}else if($mode == 'modify') {

	$tbl = $review->tbls['product_review'];
	$num = $_POST['productorder_idx'];

	$record = array(
		'marks'=>$_POST['marks'],
		'subject'=>$_POST['subject'],
		'content'=>htmlspecialchars($_POST['content'], ENT_QUOTES),
		'date'=>date('YmdHis'),
	);
	$where = array(
		'num'=>$num
	);
	if($_POST['delchk1']=='Y'){
		@unlink(DOC_ROOT.$_POST['old_upfile1']);
		$record['upfile']='';
	}
	if($_POST['delchk2']=='Y'){
		@unlink(DOC_ROOT.$_POST['old_upfile2']);
		$record['upfile2']='';
	}
	$sql = sqlUpdate($record, $tbl, $where);
	$rs = $review->adodb->Execute($sql);

	if($rs) {
		$uploadFile = '';
		$uploadFile1 = '';
		$up_rfile = '';
		$up_rfile1 = '';
		$rv_type = 0;
		//이미지 첨부
		if($_FILES['upfile1']) {
			$dir = DIRECTORY_SEPARATOR.ImageDir.'review/'.date('Ym').'/';
			$handle = new upload($_FILES['upfile1']);
			if ($handle->uploaded) {
				//$handle->file_name_body_add = '_'.date('YmdHis');
				$handle->file_new_name_body = $productcode.'_'.date('YmdHis');
				$file_rs = $handle->process(DOC_ROOT.$dir);


				if ($handle->processed) {
					$path = $dir.$handle->file_dst_name;
					$uploadFile = $path;
					$rv_type = 1;
					$up_rfile = $handle->file_src_name;
					@unlink(DOC_ROOT.$_POST['old_upfile1']);
					$handle->clean();
					$file1 = ",upfile='{$uploadFile}'";
				} else {
				}
			}
		}
		if($_FILES['upfile2']) {
			$dir = DIRECTORY_SEPARATOR.ImageDir.'review/'.date('Ym').'/';
			$handle = new upload($_FILES['upfile2']);
			if ($handle->uploaded) {
				$handle->file_new_name_body = $productcode.'_'.date('YmdHis');
				$file_rs = $handle->process(DOC_ROOT.$dir);

				if ($handle->processed) {
					$path = $dir.$handle->file_dst_name;
					$uploadFile1 = $path;
					$rv_type = 1;
					$up_rfile1 = $handle->file_src_name;
					@unlink(DOC_ROOT.$_POST['old_upfile2']);
					if(!empty($up_rfile)){
						$up_rfile = $up_rfile."||".$up_rfile1;
					}
					$file2 = ",upfile2='{$uploadFile1}'";
					$handle->clean();
				} else {
				}
			}
		}
		if($rv_type == 1){
			$review->adodb->Execute("UPDATE {$tbl} SET up_rfile='{$up_rfile}' {$file1} {$file2},type='{$rv_type}' WHERE num='{$num}'");
		}

		//$review_average = $review->getAverageMarks($productcode); //평균별점
		//$review_average_rate = ($review_average*100/5); //평균별점(%, 5점만점)
		//$review_count = $review->getCount($productcode); //리뷰수

		return_json(true,'수정되었습니다.', array('count'=>$review_count,'average'=>$review_average,'average_rate'=>$review_average_rate));

	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}
else if($mode == 'remove') {
	$tbl = $review->tbls['product_review'];
	$productcode = $_POST['productcode'];

	//삭제플래그 업데이트
	$record = array('deleted'=>1);
	$where = array('num'=>$_POST['no']);
	$sql = sqlUpdate($record, $tbl, $where);
	$rs = $review->adodb->Execute($sql);

	if($rs) {

		$review_average = $review->getAverageMarks($productcode); //평균별점
		$review_average_rate = ($review_average*100/5); //평균별점(%, 5점만점)
		$review_count = $review->getCount($productcode); //리뷰수
		
		return_json(true,'', array('count'=>$review_count,'average'=>$review_average,'average_rate'=>$review_average_rate));
		
	}
	else {
		return_json(false,'');
	}
}
