<?php
/**
 * 기타기능 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */

$Dir = "../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$Widget = new widget;
$mode = $_POST['mode'];
$act = $_POST['act'];


if($mode == 'like') {

	//로그인체크
	//TODO: 100개까지만 좋아요가능
	$like_id = MEMID; //$_ShopInfo->getMemid();
	if(!$like_id) return_json(false,'login'); //로그인컨펌

	$tbl = $Widget->tbls['like'];
	$hott_code = $_POST['hott_code'];
	$section = $_POST['section'];
	if(!$hott_code) return_json(false,'올바른 경로가 아닙니다.');

	if($act == 'like'){ //좋아요처리
		//TODO :좋아요 max count(100개까지)
		$like_count = $Widget->adodb->getOne("SELECT COUNT(*) FROM {$tbl} WHERE like_id='{$like_id}'");
		if($like_count >= 100) {
			return_json(false, '100개까지마아아안~'); //좋아요 처리한것으로 가정함
		}

		//기존 좋아요여부체크
		$exist = $Widget->adodb->getOne("SELECT COUNT(*) FROM {$tbl} WHERE like_id='{$like_id}' AND  section='{$section}' AND hott_code='{$hott_code}'");
		if($exist > 0) {
			//return_json(false,'이미 처리되었습니다');
			return_json(true); //좋아요 처리한것으로 가정함
		}

		$record = array(
			'like_id'=>$like_id,
			'section'=>$section,
			'hott_code'=>$hott_code,
			'date_insert'=>NOW
		);
		$sql = sqlInsert($record, $tbl);
	}
	else if($act == 'release') { //좋아요해지
		$sql = "DELETE FROM {$tbl} WHERE like_id='{$like_id}' AND  section='{$section}' AND hott_code='{$hott_code}'";
	}

	$rs = $Widget->adodb->Execute($sql);


	if($rs) {

		//좋아요개수 동기화
		if($section == 'product') {
			$Widget->sycnLike(); //@TODO trigger
		}

        //좋아요 개수 구하기
        $like_total_sql = "SELECT COUNT(*) as count FROM tblhott_like WHERE like_id = '{$like_id}'" ;
        $like_total = $Widget->adodb->getRow($like_total_sql);

		return_json(true, $like_total['count']);
	}
	else {
		return_json(false,'잠시 후에 다시 시도해주세요');
	}
}
else if($mode == 'search') {
	if($act == 'word_add'){ //최근검색어추가
		if($_POST['word']) $Widget->addSearchWord($_POST['word']);
	}
	else if($act == 'word_remove') { //최근검색어삭제
		$Widget->delSearchWord($_POST['word']);
	}
}



