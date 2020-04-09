<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
########### 메모 구하기
if(!$memo) {
    if (!$mem_id) {
        $mem_id = $_POST['mem_id'];
    }

    $sql = "SELECT  memo 
        FROM    tblmember 
        WHERE   id = '" . $mem_id . "' 
        ORDER BY date DESC
        ";

    $member = new MEMBER();
    $row = $member->adodb->getRow($sql);
    $memo = htmlspecialchars_decode($row['memo']);
    $memo_len = strlen($memo);
}
if ($memo_len < 1) {
    $memo = '<div class = "ta-c" style="padding: 50px;"> 입력된 메모가 없습니다. </div>';
}


$assign = array(
    'memo' => $memo,
    'mem_id' => $mem_id,
    'memo_len' => $memo_len
);

if($_POST['type']=='inner') {
   echo nl2br($memo);
}else {
    _render("member/crm_view_mem_memo.html", $assign, 'admin/template');
}
?>
