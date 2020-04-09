<?php
/**
 * 디자인관리 NEW ARRIVALS
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);

$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");

$adodb = adodb_connect();
$tbl = 'tblnewarrivals';

$mode = $_POST['mode'];
$act = $_POST['act'];
if($mode == 'newarrivals') { //수정


    $list['option_use'] = $_POST['option_use'];
    $list['tag_title'] = $_POST['tag_title'];
    $list['idx'] = $_POST['idx'];
    $list['productcode'] = $_POST['productcode'];
   

    foreach ($list['idx'] as $key=>$val){
        $productcode_arr = array_unique(array_filter(explode(',',$list['productcode'][$key])));
        $sql = "UPDATE {$tbl} SET tag_title='{$list['tag_title'][$key]}', use_yn='{$list['option_use'][$key]}', productcode='".implode(',',$productcode_arr)."',date_update= NOW() WHERE idx='{$list['idx'][$key]}'";
        $rs = $adodb->Execute($sql);
    }

    if($rs) {
    /**/
		return_json(true,'저장되었습니다.');
	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요.');
	}
}else if($mode == 'review_choose') { //메인 리뷰배너 임시 저장

    $num = $_POST['num'];
    $sql = "UPDATE tblproductreview SET main_display='T' WHERE num='{$num}'";
    //echo $sql;exit;
    $rs = $adodb->Execute($sql);

    if($rs) {
        /**/
        return_json(true,'저장되었습니다.');
    }
    else {
        return_json(false,'잠시 후에 다시 시도해주세요.');
    }
}else if($mode == 'review_remove') { //메인 리뷰배너 삭제

    $num = $_POST['num'];
    $sql = "UPDATE tblproductreview SET main_display='N', sort=0 WHERE num='{$num}'";
    //echo $sql;exit;
    $rs = $adodb->Execute($sql);

    if($rs) {
        /**/
        return_json(true,'저장되었습니다.');
    }
    else {
        return_json(false,'잠시 후에 다시 시도해주세요.');
    }
}else if($mode == 'review_update') { //메인 리뷰배너 등록

    $num = explode(",",$_POST['num']);
    //print_r($num);exit;
    for($i=0; $i < count($num); $i++){
        $sql = "UPDATE tblproductreview SET main_display='Y', sort={$i} WHERE num='{$num[$i]}'";
        $rs = $adodb->Execute($sql);
    }

    //echo $sql;exit;


    if($rs) {
        /**/
        return_json(true,'저장되었습니다.');
    }
    else {
        return_json(false,'잠시 후에 다시 시도해주세요.');
    }
}else if($mode == 'category_register') {
    $tbl ='tblcategory_banner';
    //print_r($_POST); exit;

    $record = array(
        'categorycode'=>$_POST['categorycode'],
        'productcode'=>$_POST['productcode'],
        'use_yn'=>$_POST['use_yn'],
        'date_update'=>NOW,
        'admin_id'=>$_ShopInfo->id
    );

    $categorycode = $_POST['categorycode'];


    if($_POST['category_idx']) { //수정
        $idx = $_POST['category_idx'];
        $where = array('idx'=>$_POST['category_idx']);
        $sql = sqlUpdate($record, $tbl, $where);
        $rs = $adodb->Execute($sql);
    }
    else { //신규등록

        $record['date_insert'] = NOW;

        $sort_sql = "SELECT MAX(sort)+1 AS sort FROM {$tbl} WHERE categorycode ='{$categorycode}'";
        $rs = $adodb->getRow($sort_sql);
        if(empty($rs['sort'])){
            $record['sort'] = 1;
        }else{
            $record['sort'] = $rs['sort'];
        }

        $sql = sqlInsert($record, $tbl);
        $rs = $adodb->Execute($sql);
        $idx = $adodb->insert_id();
    }

    if($rs) {

        //카테고리 PC 이미지 업로드
        if($_FILES['pc_img']) {
            include_once $Dir."lib/upload.class.php";
            $dir = DIRECTORY_SEPARATOR.ImageDir.'catecorybanner/'; //이미지저장디렉토리
            $handle = new upload($_FILES['pc_img']);
            if ($handle->uploaded) {
                $handle->file_new_name_body = $categorycode.'_'.date('YmdHis');
                $file_rs = $handle->process(DOC_ROOT.$dir);

                if ($handle->processed) {
                    $path = $dir.$handle->file_dst_name;
                    $adodb->Execute("UPDATE {$tbl} SET pc_img='$path' WHERE idx='{$idx}'");
                    $handle->clean();
                } else {
                }
            }
        }
        //카테고리 MOBILE 이미지 업로드
        if($_FILES['mobile_img']) {
            include_once $Dir."lib/upload.class.php";
            $dir = DIRECTORY_SEPARATOR.ImageDir.'catecorybanner/'; //이미지저장디렉토리
            $handle = new upload($_FILES['mobile_img']);
            if ($handle->uploaded) {
                $handle->file_new_name_body = $categorycode.'mobile_'.date('YmdHis');
                $file_rs = $handle->process(DOC_ROOT.$dir);

                if ($handle->processed) {
                    $path = $dir.$handle->file_dst_name;
                    $adodb->Execute("UPDATE {$tbl} SET mobile_img='$path' WHERE idx='{$idx}'");
                    $handle->clean();
                } else {
                }
            }
        }

        return_json(true,'저장되었습니다.');
    }
    else {
        return_json(false,$_ALERT['C003']); //처리중오류
    }
}
else if($mode == 'list') {
    if($act == 'remove') {

        $tbl ='tblcategory_banner';
        $idx = $_POST['idx'];
        $sql = "DELETE FROM {$tbl} WHERE idx='{$idx}'";
        $rs = $adodb->Execute($sql);
        if($rs) {
            return_json(true,'삭제되었습니다.');
        }
        else {
            return_json(false,$_ALERT['C003']); //처리중오류
        }
    }
    else if($act == 'batch_use') { //사용여부일괄변경
        $tbl ='tblcategory_banner';
        $use_yn = $_POST['use_yn'];
        $idx = $_POST['idx'];
        $sql = "UPDATE {$tbl} SET use_yn='{$use_yn}', date_update='".NOW."', admin_id='".$_ShopInfo->id."' WHERE idx IN ({$idx})";
        //echo $sql;exit;
        $rs = $adodb->Execute($sql);
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
        $tbl ='tblcategory_banner';
        $type = $_POST['type'];
        $sort = $_POST['sort'];
        $idx = $_POST['idx'];
        $categorycode = $_POST['categorycode'];

        if($type== 'up'){
            if($sort > 1){
                $sql = "SELECT sort,idx FROM {$tbl} WHERE categorycode = '{$categorycode}' AND sort < {$sort} ORDER BY sort DESC LIMIT 1";
                $rs = $adodb->getRow($sql);
                if($rs){
                    $adodb->Execute("UPDATE {$tbl} SET sort= {$rs['sort']} WHERE idx ='{$idx}'");
                    $adodb->Execute("UPDATE {$tbl} SET sort= {$sort} WHERE idx ='{$rs['idx']}'");               
                }
            }
        }else{
            $sql = "SELECT sort,idx FROM {$tbl} WHERE categorycode = '{$categorycode}' AND sort > {$sort} ORDER BY sort ASC LIMIT 1";
            $rs = $adodb->getRow($sql);
            if($rs) {
                $adodb->Execute("UPDATE {$tbl} SET sort= {$rs['sort']} WHERE idx ='{$idx}'");
                $adodb->Execute("UPDATE {$tbl} SET sort= {$sort} WHERE idx ='{$rs['idx']}'");
            }

        }
        return_json(true, '적용되었습니다.');

    }
}


?>
