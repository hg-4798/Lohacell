<?php
/**
 * 리뷰 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */


$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");

$Review = new REVIEW;
$mode = $_POST['mode'];
$act = $_POST['act'];

if($mode == 'insert'){ //리뷰등록
	$productcode = $_POST['productcode'];
	$id = $_POST['user_id'];
	$name = $_POST['user_name'];
	$regdt = str_replace("-", "", $_POST['regdt']).date("His");
	$marks = (int)$_POST['rate'];
	$subject = $_POST['subject'];
	$content = $_POST['content'];
	$date = date('Ymdhis');
	$review_size = $_POST['review_size'];
	$review_foot_width = $_POST['review_foot_width'];
	$review_color = $_POST['review_color'];
	$review_quality = $_POST['review_quality'];
	$option_code = $_POST['option_code'];


	$uploadFile = '';
    $uploadFile1 = '';
	$up_rfile = '';
    $up_rfile1 = '';
	$rv_type = 0;
	//print_r($_FILES);exit;
	if(!empty($_FILES['rfile'])) {
		include_once $Dir."lib/upload.class.php";

		$dir = DIRECTORY_SEPARATOR.ImageDir.'review/'.date('Ym').'/';
		$handle = new upload($_FILES['rfile']);
		if ($handle->uploaded) {
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
	if(!empty($_FILES['rfile1'])) {
        include_once $Dir."lib/upload.class.php";
        $dir = DIRECTORY_SEPARATOR.ImageDir.'review/'.date('Ym').'/';
        $handle = new upload($_FILES['rfile1']);
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

	####################리뷰등록하기##################
	$sql .= "INSERT INTO tblproductreview (
		upfile,
		up_rfile, 
		upfile2,
		productcode	,
		id		,
		name		,
		marks		,
		date		,
		type		,
		subject		,
		content     ,
		staff	,
		option_code)  ";
	$sql .= " VALUES (
		'{$uploadFile}',
		'{$up_rfile}',
		'{$uploadFile1}',
		'{$productcode}',
		'".$id."',
		'{$name}',
		'{$marks}',
		'{$regdt}',
		'{$rv_type}',
		'".$subject."',
		'{$content}',
		'Y',
		'{$option_code}')" ;
	//exdebug($sql);exit;
	$rs = $Review->adodb->Execute($sql);
	

	if($rs){
		//리뷰수 동기화
		//$Review->syncCount($productcode);
		
		
		//$pr_sql = "UPDATE tblproduct SET review_cnt = review_cnt + 1 WHERE productcode ='".$productcode."'";
		//pmysql_query( $pr_sql, get_db_conn() );
		return_json(true,'등록되었습니다.');
	}else{
		// @unlink();
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}
else if($mode=='review_banner'){
	$tbl =$Review->tbls['review_banner'];

	$record = array(
		'banner_title'=>$_POST['banner_title'],
		'banner_url'=>$_POST['banner_url'],
		'img_link'=>$_POST['img_link'],
		'img_m_link'=>$_POST['img_m_link'],
		'banner_hidden'=>$_POST['use_yn'],
		'banner_type'=>$_POST['banner_type']
	);

		if($_POST['idx']) { //수정
			$idx = $_POST['idx'];
			$record['date_update'] = NOW;
			$where = array('idx'=>$_POST['idx']);
			$sql = sqlUpdate($record, $tbl, $where);
			$rs = $Review->adodb->Execute($sql);
		}
		else { //신규등록
			$record['date_insert'] = NOW;
			$sort_sql = "SELECT MAX(sort)+1 AS sort FROM {$tbl} WHERE banner_type ='{$_POST['banner_type']}'";
			$rs = $Review->adodb->getRow($sort_sql);
			if(empty($rs['sort'])){
				$record['sort'] = 1;
			}else{
				$record['sort'] = $rs['sort'];
			}

			$sql = sqlInsert($record, $tbl);
			$rs = $Review->adodb->Execute($sql);
			$idx = $Review->adodb->insert_id();
		}

		if($rs) {

			//카테고리 PC 이미지 업로드
			if($_FILES['pc_img']) {
				include_once $Dir."lib/upload.class.php";
				$dir = DIRECTORY_SEPARATOR.ImageDir.'review/'; //이미지저장디렉토리
				$handle = new upload($_FILES['pc_img']);
				if ($handle->uploaded) {
					$handle->file_new_name_body = $idx.'_'.date('YmdHis');
					$file_rs = $handle->process(DOC_ROOT.$dir);

					if ($handle->processed) {
						$path = $dir.$handle->file_dst_name;
						$Review->adodb->Execute("UPDATE {$tbl} SET banner_img='$path' WHERE idx='{$idx}'");
						$handle->clean();
					} else {
					}
				}
			}
			//카테고리 MOBILE 이미지 업로드
			if($_FILES['mobile_img']) {
				include_once $Dir."lib/upload.class.php";
				$dir = DIRECTORY_SEPARATOR.ImageDir.'review/'; //이미지저장디렉토리
				$handle = new upload($_FILES['mobile_img']);
				if ($handle->uploaded) {
					$handle->file_new_name_body = $idx.'mobile_'.date('YmdHis');
					$file_rs = $handle->process(DOC_ROOT.$dir);

					if ($handle->processed) {
						$path = $dir.$handle->file_dst_name;
						$Review->adodb->Execute("UPDATE {$tbl} SET banner_img_m='$path' WHERE idx='{$idx}'");
						$handle->clean();
					} else {
					}
				}
			}

			return_json(true,'등록되었습니다.');
		}
		else {
			return_json(false,$_ALERT['C003']); //처리중오류
		}

}
else if($mode == 'list') {
	if($act == 'remove') {

		$tbl =$Review->tbls['review_banner'];
		$idx = $_POST['idx'];
		$sql = "DELETE FROM {$tbl} WHERE idx='{$idx}'";
		$rs = $Review->adodb->Execute($sql);
		if($rs) {
			return_json(true,'삭제되었습니다.');
		}
		else {
			return_json(false,$_ALERT['C003']); //처리중오류
		}
	}
	else if($act == 'batch_use') { //사용여부일괄변경
		$tbl =$Review->tbls['review_banner'];
		$use_yn = $_POST['use_yn'];
		$idx = $_POST['idx'];
		$sql = "UPDATE {$tbl} SET banner_hidden='{$use_yn}', date_update='".NOW."' WHERE idx IN ({$idx})";
		//echo $sql;exit; exit;
		$rs = $Review->adodb->Execute($sql);
		if($rs) {
			//if($use_yn == 'Y') //숨김처리

			return_json(true, '적용되었습니다.');
		}
		else {
			//echo $sql;
			return_json(false,$_ALERT['C003']); //처리중오류
		}
	}
	else if($act == 'sort'){
		$tbl =$Review->tbls['review_banner'];
		$type = $_POST['type'];
		$sort = $_POST['sort'];
		$idx = $_POST['idx'];
		$banner_type = $_POST['banner_type'];


		if($type== 'up'){
			if($sort > 1){
				$sql = "SELECT sort,idx FROM {$tbl} WHERE banner_type = '{$banner_type}' AND sort < {$sort} ORDER BY sort DESC LIMIT 1";
				$rs = $Review->adodb->getRow($sql);
				if($rs){
					$Review->adodb->Execute("UPDATE {$tbl} SET sort= {$rs['sort']} WHERE idx ='{$idx}'");
					$Review->adodb->Execute("UPDATE {$tbl} SET sort= {$sort} WHERE idx ='{$rs['idx']}'");
				}
			}
		}else{
			$sql = "SELECT sort,idx FROM {$tbl} WHERE banner_type = '{$banner_type}' AND sort > {$sort} ORDER BY sort ASC LIMIT 1";
			//pre($sql);
			$rs = $Review->adodb->getRow($sql);
			if($rs) {
				$Review->adodb->Execute("UPDATE {$tbl} SET sort= {$rs['sort']} WHERE idx ='{$idx}'");
				$Review->adodb->Execute("UPDATE {$tbl} SET sort= {$sort} WHERE idx ='{$rs['idx']}'");
			}

		}
		return_json(true, '적용되었습니다.');

	}
}
else if($mode=='review_blog'){
	$tbl =$Review->tbls['review_blog'];
	$record = array(
		'blog_title'=>$_POST['blog_title'],
		'blog_content'=>htmlspecialchars($_POST['blog_content']),
		'blog_content_m'=>htmlspecialchars($_POST['blog_content_m']),
		'blog_hidden'=>$_POST['use_yn']
	);

	if($_POST['idx']) { //수정
		$idx = $_POST['idx'];
		$record['date_update'] = NOW;
		$where = array('idx'=>$_POST['idx']);
		$sql = sqlUpdate($record, $tbl, $where);
		$rs = $Review->adodb->Execute($sql);
	}
	else { //신규등록
		$record['date_insert'] = NOW;
		$sort_sql = "SELECT MAX(sort)+1 AS sort FROM {$tbl}";
		$rs = $Review->adodb->getRow($sort_sql);
		if(empty($rs['sort'])){
			$record['sort'] = 1;
		}else{
			$record['sort'] = $rs['sort'];
		}

		$sql = sqlInsert($record, $tbl);
		//pre($sql);exit;
		$rs = $Review->adodb->Execute($sql);
		$idx = $Review->adodb->insert_id();
	}

	if($rs) {

		//카테고리 PC 이미지 업로드
		if($_FILES['pc_img']) {
			include_once $Dir."lib/upload.class.php";
			$dir = DIRECTORY_SEPARATOR.ImageDir.'review_blog/'; //이미지저장디렉토리
			$handle = new upload($_FILES['pc_img']);
			if ($handle->uploaded) {
				$handle->file_new_name_body = $idx.'_'.date('YmdHis');
				$file_rs = $handle->process(DOC_ROOT.$dir);

				if ($handle->processed) {
					$path = $dir.$handle->file_dst_name;
					$Review->adodb->Execute("UPDATE {$tbl} SET blog_img='$path' WHERE idx='{$idx}'");
					$handle->clean();
				} else {
				}
			}
		}
		//카테고리 MOBILE 이미지 업로드
		if($_FILES['mobile_img']) {
			include_once $Dir."lib/upload.class.php";
			$dir = DIRECTORY_SEPARATOR.ImageDir.'review_blog/'; //이미지저장디렉토리
			$handle = new upload($_FILES['mobile_img']);
			if ($handle->uploaded) {
				$handle->file_new_name_body = $idx.'mobile_'.date('YmdHis');
				$file_rs = $handle->process(DOC_ROOT.$dir);

				if ($handle->processed) {
					$path = $dir.$handle->file_dst_name;
					$Review->adodb->Execute("UPDATE {$tbl} SET blog_img_m='$path' WHERE idx='{$idx}'");
					$handle->clean();
				} else {
				}
			}
		}

		return_json(true,'등록되었습니다.');
	}
	else {
		return_json(false,$_ALERT['C003']); //처리중오류
	}

}
else if($mode == 'blog_list') {
	if($act == 'remove') {

		$tbl =$Review->tbls['review_blog'];
		$idx = $_POST['idx'];
		$sql = "DELETE FROM {$tbl} WHERE idx='{$idx}'";
		$rs = $Review->adodb->Execute($sql);
		if($rs) {
			return_json(true,'삭제되었습니다.');
		}
		else {
			return_json(false,$_ALERT['C003']); //처리중오류
		}
	}
	else if($act == 'batch_use') { //사용여부일괄변경
		$tbl =$Review->tbls['review_blog'];
		$use_yn = $_POST['use_yn'];
		$idx = $_POST['idx'];
		$sql = "UPDATE {$tbl} SET blog_hidden='{$use_yn}', date_update='".NOW."' WHERE idx IN ({$idx})";
		//echo $sql;exit; exit;
		$rs = $Review->adodb->Execute($sql);
		if($rs) {
			//if($use_yn == 'Y') //숨김처리

			return_json(true, '적용되었습니다.');
		}
		else {
			//echo $sql;
			return_json(false,$_ALERT['C003']); //처리중오류
		}
	}
	else if($act == 'sort'){
		$tbl =$Review->tbls['review_blog'];
		$type = $_POST['type'];
		$sort = $_POST['sort'];
		$idx = $_POST['idx'];


		if($type== 'up'){
			if($sort > 1){
				$sql = "SELECT sort,idx FROM {$tbl} WHERE sort < {$sort} ORDER BY sort DESC LIMIT 1";
				$rs = $Review->adodb->getRow($sql);
				if($rs){
					$Review->adodb->Execute("UPDATE {$tbl} SET sort= {$rs['sort']} WHERE idx ='{$idx}'");
					$Review->adodb->Execute("UPDATE {$tbl} SET sort= {$sort} WHERE idx ='{$rs['idx']}'");
				}
			}
		}else{
			$sql = "SELECT sort,idx FROM {$tbl} WHERE sort > {$sort} ORDER BY sort ASC LIMIT 1";
			//pre($sql);
			$rs = $Review->adodb->getRow($sql);
			if($rs) {
				$Review->adodb->Execute("UPDATE {$tbl} SET sort= {$rs['sort']} WHERE idx ='{$idx}'");
				$Review->adodb->Execute("UPDATE {$tbl} SET sort= {$sort} WHERE idx ='{$rs['idx']}'");
			}

		}
		return_json(true, '적용되었습니다.');

	}
}