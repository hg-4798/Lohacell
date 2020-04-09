<?php
/**
 * 쿠폰 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */



$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
$Member = new MEMBER();
$Coupon = new COUPON();
$adodb = adodb_connect();
$tbl = "tblcouponinfo";
$mode = $_POST['mode'];
//print_r($_POST);
if($mode == 'register') {

    $group_code =($_POST['download_target']=='grade')?$_POST['download_target'] : 'ALL';
    $part_detail =($_POST['part_detail']=='')?'ALL':$_POST['part_detail'];
    $use_period_limit = ($_POST['use_period_limit']=='')?'0':$_POST['use_period_limit'];
    $sale_value = ($_POST['sale_value']=='')?'0': $_POST['sale_value'];
    $sale_max_price = ($_POST['sale_max']=='')?'0':$_POST['sale_max'];
    $sale_min_price = ($_POST['sale_min_price']=='')?'0':$_POST['sale_min_price'];

    if($_POST['type_publish']=='A'){
		$group_code = $_POST['type_publish_kind'];
	}
	if($_POST['type_publish']=='A' && $_POST['type_publish_kind']=='birthday'){
		$type_publish_change_grade = $_POST['change_grade'];
	}else{
		$type_publish_change_grade = $_POST['type_publish_change_grade'];
	}
    //pre($_POST);exit;
	$record = array(
		'coupon_name'=>$_POST['coupon_name'],
        'coupon_description'=>$_POST['coupon_desc'],
        'type_use'=>$_POST['type_use'],
        'type_publish'=>$_POST['type_publish'],
        'group_code'=>$type_publish_change_grade,
        'type_publish_kind'=>$group_code,
        'use_device'=>$_POST['use_device'],
        'publish_date_type'=>$_POST['publish_period_type'],
        'use_period_type'=>$_POST['use_period_type'],
        'use_period_limit'=>$use_period_limit,
        'sale_price'=>$sale_value,
        'sale_type'=>$_POST['sale_unit'],
        'sale_max_price'=>$sale_max_price,
        'sale_min_price'=>$sale_min_price,
        'use_part'=>$_POST['target'],
        'part_detail'=>$part_detail,
        'limit_count'=>$_POST['download_count'],
        'limit_per'=>$_POST['download_per'],
        'insert_date'=>NOW,
        'insert_id'=>ADMINID,
        'issue_status'=>'Y'

	);
    if($_POST['publish_during_start']){
        $record['publish_date_start']=$_POST['publish_during_start']." 00:00:00";
    }
    if($_POST['use_during_start']){
        $record['use_period_start']=$_POST['use_during_start']." 00:00:00";
    }
    if($_POST['publish_during_end']){
        $record['publish_date_end']=$_POST['publish_during_end']." 23:59:59";
    }
    if($_POST['use_during_end']){
        $record['use_period_end']=$_POST['use_during_end']." 23:59:59";
    }

    //print_r($record);exit;
    $sql = sqlInsert($record, $tbl);
//echo $sql;
	$rs = $adodb->Execute($sql);



	if($rs) {
		return_json(true,'저장되었습니다.');
	}
	else {
		return_json(false,$_ALERT['C003']); //처리중오류
	}

}
else if($mode == 'issue_status'){
    if($_POST['type']=='Y'){
        $record = array('issue_status'=>'S');
        $where = array('idx'=>$_POST['idx']);
    }else{
        $record = array('issue_status'=>'Y');
        $where = array('idx'=>$_POST['idx']);
    }
        $sql = sqlUpdate($record, $tbl, $where);
        $rs = $adodb->Execute($sql);
        if($rs) {
            return_json(true,'저장되었습니다.');
        }
        else {
            return_json(false,$_ALERT['C003']); //처리중오류
        }

}
else if($mode == 'issue'){

    $member_data = $_POST['member_data'];
    $coupon_idx =  $_POST['coupon_idx'];
    $coupon_info = $Coupon->getCouponRow($coupon_idx);
    $mode = "Instant";
    $type=$_POST['type'];
    if($type=='S'){
        $member_list = $Member->getMemberSimple($member_data);
        foreach ($member_list AS $key=>$val){
            $rs = $Coupon->issueInstant($coupon_info,$val);
        }
        $data = $member_data."||".implode(',', $member_list);
    }else{
        foreach ($member_data AS $key=>$val){
            $rs = $Coupon->issueInstant($coupon_info,$val);
            $data = $member_data."||".implode(',', $member_data);
        }
    }

    if($rs=='success') {
        $Coupon->log($coupon_idx,$mode,$type,$data);
        return_json(true,'저장되었습니다.');
    }
    else {
        return_json(false,$rs); //처리중오류
    }


}