<?
    header("Content-type: text/xml;charset=utf-8");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");

    $Dir="../../";
    include_once($Dir."lib/init.php");
    include_once($Dir."lib/lib.php");
    include_once($Dir."lib/cache_main.php");

    //
    # 연결 해지 시작
    include_once('../../plugin/sns/facebook/fb.lib.php');

    $facebook = new FacebookOAuth();
    $data = $facebook->delete('https://graph.facebook.com/me/permissions', array('access_token'=>$_POST['access']));
    # $data : {"success":true}
    # 연결 해지 종료

    if($data->success =='1'){
        $sql_del = "DELETE FROM tblmember_sns WHERE id = '".$_POST['mem_id']."' and name = '".$_POST['mem_name']."' and sns_type = 'FACEBOOK' ";
        pmysql_query($sql_del,get_db_conn());
        $sql_update = "UPDATE tblmember SET sns_type = '' where sns_type like '%".$_POST['sns_id']."%'";
        pmysql_query($sql_update,get_db_conn());
        $responce['msgs'] = "success";
    }else{
        $responce['msgs'] = "fail";
    }

    echo json_encode($responce);

?>