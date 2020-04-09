<?php

class ATTACH extends COMMON{
	public function __construct() {
		parent::__construct();
	}

	/**
	 * attach 그룹키값 생성반환
	 * @param
	 * @return void
	 */
	public function create_attach($attach_tbl) {
		$tbl = $this->tbls['attach'];
		$sql = "INSERT INTO {$tbl} (attach_tbl) VALUES(?)";
		//$this->adodb->debug  = true;
		$rs = $this->adodb->execute($sql, ARRAY($attach_tbl));
		$attach_idx = $this->adodb->insert_id();
		return $attach_idx;
	}

	public function insert_file($attach_idx, $parm) {
		$tbl = $this->tbls['attach_file'];
		$record = array(
			'attach_idx'=>$attach_idx,
			'save_path'=>$param['path'],
			'file_name'=>$param['original_filename'],
			'file_type'=>$param['mime'],
			'file_size'=>$param['size_in_bytes'],
			'date_insert'=>NOW
		);

		$sql = sqlInsert($record, $tbl);
		$rs = $this->adodb->Execute($sql);
		if($rs) {
			return $this->adodb->insert_id();
		}
		else return false;
	}

	/**
	 * 파일리스트 반환
	 * @param $idx int 첨부그룹키
	 * @return array 2depth
	 */
	public function get_attach_file($attach_idx) {
		
	}

	/**
	 * 파일정보 반환
	 * @return array 1deptch
	 */
	public function get_file($file_idx) {

	}

	/**
	 * 파일그룹정보삭제
	 */
	public function delete_attach($attach_idx) {

	}


	/**
	 * 파일정보삭제
	 */
	public function delete_file($file_idx) {

	}

	/**
	 * 파일삭제
	 *
	 * @param string $path 삭제할 파일경로
	 * @return void
	 */
	private function _remove_file($path) {
		@unlink(PATH_UPLOAD_ROOT.$path);
	} 

	
	/**
	 * 파일업로드
	 * @param $field 파일필드명
	 * @param $attach_tbl (저장테이블)
	 * @param $attach_idx 첨부아이티키
	 * @param $mb 업로드제한용량(MB)
	 */
	public function execUpload($field, $attach_tbl, $attach_idx=0, $mb=30) {
		global $_ALERT;
		$bytes = $mb*1024*1024;

		//용량체크
		$valid_size = false;
		if($valid_size) {
			foreach($_FILES[$field]['size'] as $size) {
				if($size>$bytes) {
					$this->msg = $_ALERT['F103']."<br />(업로드가능 최대용량:{$mb}MB)";
					return false;
				}
			}
		}

		//파일업로드처리
		foreach($_FILES[$field]['name'] as $key => $val){
			if ($_FILES[$field]['name'][$key] && is_uploaded_file($_FILES[$field]['tmp_name'][$key])) {
				$file = File::factory($type);
				$files['error'] = $_FILES[$field]['error'][$key];
				$files['name'] = $_FILES[$field]['name'][$key];
				$files['type'] = $_FILES[$field]['type'][$key];
				$files['tmp_name'] = $_FILES[$field]['tmp_name'][$key];
				$files['size'] = $_FILES[$field]['size'][$key];
				$file->file($files);
				$res = $file->upload();


				if ($res['status'] == false) {
					$this->msg = $_ALERT['F100'];
					return false;
				} else {
					//첨부그룹코드가 없는경우 생성
					if ($attach_idx < 1) {
						$attach_idx = $this->create_attach($attach_tbl);
					}

					//파일정보INERT 실패
					if (!$this->insert_file($attach_idx, $res)) {
						$this->_remove_file($res['path']); //등록파일삭제
						$this->msg = $_ALERT['F100'];
						return false;
					}
				}
			}
		}
		return $upload_idx;

	}
}
?>