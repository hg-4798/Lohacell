<?php
/**
 * 이벤트,기획전
 */

$Dir = "../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

$promotion = NEW PROMOTION;

$mode = $_POST['mode'];
$act = $_POST['act'];

if($mode='comment'){ 
    $ip = $_SERVER['REMOTE_ADDR'];
    Common::escapeData();
    $comment_type = $_POST['comment_type'];
    $board = $_POST['board'];
    $parent = $_POST['parent'];
    $comment = htmlspecialchars($_POST['comment']);
    $num = $_POST['comment_num']?$_POST['comment_num']:0;
    $act = $_POST['act'];
    $mem_name = $_ShopInfo->getMemname(); //등록자 이름
    $c_mem_id = MEMID; //등록자 ID
    $title = $_POST['title']; //제목

    
    if($act=='modify') {
        $where = array('num'=>$num, 'c_mem_id'=>$c_mem_id);
        $record = array(
            'title'=>$title,
            'comment' => $comment
        );
        $sql = sqlUpdate($record,'tblboardcomment_promo', $where);
        $msg = "수정 되었습니다. ";

        $rs = $promotion->adodb->Execute($sql);

        if($rs) {
            return_json(true, "댓글이 ".$msg);
        }else{
            return_json(false, '오류가 발생 되었습니다. ');
        }
    }
    else if($act=='insert'){

        //포토이미지 업로드
        if(!empty($_FILES['photo_img'])) {
            include_once $Dir."lib/upload.class.php";

            $f = UTIL::array_arrange($_FILES['photo_img']);
            $dir = DIRECTORY_SEPARATOR.ImageDir.'promotion/'.date('Ym').'/';
            $photo_img = array();
            foreach($f as $k=>$v) {
                $handle = new upload($v);
                if ($handle->uploaded) {
                    $handle->file_new_name_body = $parent.'_'.date('YmdHis').'_'.$k;
                    $file_rs = $handle->process(DOC_ROOT.$dir);

                    if ($handle->processed) {
                        $path = $dir.$handle->file_dst_name;
                        $photo_img[] = array(
                            'path'=>$path,
                            'origin_name'=>$handle->file_src_name
                        );
                        $handle->clean();
                    } else {
                    }
                }
            }
        }

        $record = array(
            'board' => $board,
            'parent' => $parent,
            'name' => $mem_name,
            'ip' => $ip,
            'writetime' => NOW,
            'title'=>$title,
            'photo_img'=>serialize($photo_img),
            'comment' => $comment,
            'c_mem_id' => $c_mem_id
        );


        $sql = sqlInsert($record, 'tblboardcomment_promo');
        $msg = "등록 되었습니다. ";

        $rs = $promotion->adodb->Execute($sql);

        if($rs) {
            return_json(true, "댓글이 ".$msg);
        }else{
            return_json(false, '오류가 발생 되었습니다. ');
        }


    }
    else if($act=='delete'){
        $where = " WHERE num = {$num} AND c_mem_id = '{$c_mem_id}' ";

        $row = $promotion->adodb->getRow("SELECT photo_img FROM tblboardcomment_promo {$where} ");

//        @TODO 서버가 추가되면 파일 관련 처리를 묶어서 해야함
        if(is_array($row)){
            if(strlen($row['photo_img'])>0) {
                $photo_img = unserialize($row['photo_img']);
                if(is_array($photo_img)) {
                    foreach ($photo_img as $key => $val) {
                        if (DOC_ROOT . $val['path']) {
                            unlink(DOC_ROOT . $val['path']);
                        }
                    }
                }
            }
        }

        $sql = "DELETE FROM tblboardcomment_promo {$where} ";
        $msg = "삭제되었습니다. ";

        $rs = $promotion->adodb->Execute($sql);

        if($rs) {
            return_json(true, "댓글이 ".$msg);
        }else{
            return_json(false, '오류가 발생 되었습니다. ');
        }

    }

}
/*
else if($comment_type=='photo') {
    if ($act == 'modify') {
        $where = array('num' => $num, 'c_mem_id' => $c_mem_id);
        $record = array(
            'title' => $title,
            'comment' => $comment,
            'photo_img'=> $photo_img
        );
        $sql = sqlUpdate($record, 'tblboardcomment_promo', $where);
        $msg = "수정 되었습니다. ";
    } else if ($act == 'insert') {
        $record = array(
            'board' => $board,
            'parent' => $parent,
            'name' => $mem_name,
            'ip' => $ip,
            'writetime' => NOW,
            'comment' => $comment,
            'c_mem_id' => $c_mem_id,
            'title' => $title,
            'photo_img' => $photo_img
        );
        $sql = sqlInsert($record, 'tblboardcomment_promo');
        $msg = "등록 되었습니다. ";
    } else if ($act == 'delete') {
        $sql = "DELETE FROM tblboardcomment_promo WHERE num = {$num} AND c_mem_id = '{$c_mem_id}' ";
        $msg = "삭제되었습니다. ";
    }
    $rs = $promotion->adodb->Execute($sql);

    if ($rs) {
        return_json(true, "댓글이 " . $msg);
    } else {
        return_json(false, '오류가 발생 되었습니다. ');
    }
}
*/
?>