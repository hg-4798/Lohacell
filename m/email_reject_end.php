<?
if(strlen($Dir)==0) $Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");

include('./include/top.php');
include('./include/gnb.php');

$key = $_GET['key'];
$id = Common::Dectypt_AES128CBC($key,JayjunKey,JayjunIvKey);

$sql = "SELECT * FROM tblmember WHERE id ='".$id."'";
$row = pmysql_fetch_object(pmysql_query($sql));

$after_news_yn="N";
if($row->news_yn == "Y"){
    $after_news_yn = "S";
}else if($row->news_yn == "M") {
    $after_news_yn = "N";
}
$msg = '';
if($row->news_yn == "S" || $row->news_yn == "N") {
    $msg = '이미 수신거부 처리되었습니다';
}else {
    $sql = "UPDATE tblmember SET news_yn = '" . $after_news_yn . "' WHERE id = '" . $id . "'";
    $result = pmysql_query($sql, get_db_conn());

    if ($result) {
        $msg = '수신거부 처리가 완료되었습니다. <br>그 동안 i KNOW iONE 이메일을 구독해 주셔서 감사합니다.';
    } else {
        $msg = '수신거부 처리가 실패되었습니다';
    }
}

$assign = array(
    'msg' => $msg
);
_render('member/email_reject_end.html', $assign, DIR_M.'/template');
include('./include/bottom.php');
?>
