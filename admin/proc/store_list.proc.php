<?php
/**
 * 상품처리 프로세싱
 * @author  이혜진(stickcandy81@nate.com)
 */

/*
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors", 1);
*/

$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/adminlib.php");
$adodb = adodb_connect();
$mode = $_POST['mode'];
$act = $_POST['act'];
$db = 'tblstore';
$today= date("YmdHis");

if($mode == 'list') {
    if ($act == 'batch_excel') { //엑셀간편업데이트
        if (!$_FILES['excel']) return_json(false, '엑셀파일을 업로드해주세요.');

        //파일업로드
        include_once $Dir . "lib/upload.class.php";
        $dir = DIRECTORY_SEPARATOR . DataDir . 'temporary/';

        $handle = new upload($_FILES['excel']);
        if ($handle->uploaded) {
            $handle->file_new_name_body = 'excelupload_' . date('YmdHis');
            $file_rs = $handle->process(DOC_ROOT . $dir);
            if ($handle->processed) {
                $path = $dir . $handle->file_dst_name;
                $handle->clean();
            } else {
                return_json(false, '');
            }
        }


        //엑셀파싱
        //$path = '/data/temporary/excelupload-20180615113648.xlsx'; //기본양식
        //$path = '/data/temporary/excelupload-20180615142659.xlsx'; //커스텀양식

        include $Dir . 'lib/PHPExcel/Classes/PHPExcel.php';
        $obj = PHPExcel_IOFactory::load(DOC_ROOT . $path);
        $sheet_data = $obj->getSheet(0)->toArray(null, true, true, true);
        $header = array_shift($sheet_data);
        $cnt_data = count($sheet_data);
        if ($cnt_data > 1000) {
            return_json(false, '한번에 업데이트할수 있는 상품수는 최대 1000개입니다.');
        }


        $format = array(
            '매장코드' => array('column' => 'store_code', 'tbl' => 'store'),
            '지역' => array('column' => 'area_code', 'tbl' => 'store'),
            '주소' => array('column' => 'address', 'tbl' => 'store'),
            '좌표' => array('column' => 'coordinate', 'tbl' => 'store'),
            '매장구분' => array('column' => 'category', 'tbl' => 'store'),
            '매장명' => array('column' => 'name', 'tbl' => 'store'),
            '전화번호' => array('column' => 'phone', 'tbl' => 'store'),
            '오픈시간' => array('column' => 'stime', 'tbl' => 'store'),
            '마감시간' => array('column' => 'etime', 'tbl' => 'store')
		);


        $success = true;

        $unique_key = array('매장코드');
        $diff = array_diff($unique_key, $header);
        if(count($diff)>0) {
            return_json(false, "필수값[".implode(',',$diff)."]이 누락되었습니다.", array());
        }

        $format_custom = array();
        foreach ($header as $k => $fname) {
            if (!$fname) continue;
            if (array_key_exists($fname, $format) === false) {
                $success = false;
                $msg = "[{$fname}]필드명이 유효하지 않습니다.";
                break;
            }

            $format_custom[$k] = $format[$fname];
            $format_custom[$k]['name'] = $fname;
        }

        $error = array();
        foreach ($sheet_data as $k => $v) {
            $record = array();
            foreach($v as $k2=>$v2) {
                if(array_key_exists($k2, $format_custom) === false) continue;
                extract($format_custom[$k2]);
                $v2 = trim($v2);


                if($column == 'area_code') {
                    $v2 = array_search($v2,$store_area);
                    if(!$v2){
                        $error[$k] = '지역명 오류';
                        continue 2;
                    }
                }
                if($column == 'category') {
                    $v2 = array_search($v2,$store_category);
                    if(!$v2){
                        $error[$k] = '매장구분 오류';
                        continue 2;
                    }
                }

                $record[$tbl][$column] = $v2;

            }
            $sql = "INSERT INTO {$db} (store_code, category, address, coordinate, area_code, name, phone, stime, etime, regdt) VALUES ('{$record[$tbl]["store_code"]}','{$record[$tbl]["category"]}','{$record[$tbl]["address"]}','{$record[$tbl]["coordinate"]}','{$record[$tbl]["area_code"]}','{$record[$tbl]["name"]}','{$record[$tbl]["phone"]}','{$record[$tbl]["stime"]}','{$record[$tbl]["etime"]}', '{$today}')
					ON CONFLICT (store_code) DO UPDATE SET category='{$record[$tbl]["category"]}', address='{$record[$tbl]["address"]}', coordinate='{$record[$tbl]["coordinate"]}', area_code='{$record[$tbl]["area_code"]}', name='{$record[$tbl]["name"]}' , phone='{$record[$tbl]["phone"]}', stime='{$record[$tbl]["stime"]}', etime='{$record[$tbl]["etime"]}', regdt= '{$today}'";
            $rs = $adodb->Execute($sql);
            if(!$rs) {
                $error[$k] = '업데이트 오류';
                continue;
            }

        }

        //@unlink(DOC_ROOT.$path);//EXCEL파일 삭제
        //파일로그작성

        if ($success) {
            $cnt_error = count($error);
            $cnt_success = $cnt_data - $cnt_error;

            $result = array('cnt' => array('total' => $cnt_data, 'success' => $cnt_success, 'error' => $cnt_error), 'error' => $error);
           return_json(true, '매장일괄수정이 완료되었습니다.', $result);
        } else {
            return_json(false, $msg);
        }
    }
}

?>
