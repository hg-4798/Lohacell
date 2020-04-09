<?
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include('./include/top.php');
include('./include/gnb.php');


$Order = new ORDER; //주문클래스
$Product = new PRODUCT;


$assign = array(
    'search'=>array(
        'date_s'=>date('Y-m-d', strtotime('-1 month')),
        'date_e'=>date('Y-m-d')
    )
);

_render('member/login_guest.html', $assign, DIR_M.'/template');

include('../m/include/bottom.php');
?>



