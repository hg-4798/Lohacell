<?php

class STORE extends COMMON{
	public function __construct() {
		global $config, $cfg_tbl;

		$this->adodb = adodb_connect();
		$this->tbls = $cfg_tbl;
	}

	/**
	 * 매장정보
	 * @param string
	 *
	 */
	public function getStoreList($where, $sort) {
        $tbl = $this->tbls['store']; //메인테이블

        $cnt = $this->adodb->getOne("SELECT COUNT(*) FROM  {$tbl} {$where}");//전체개수
		$sql = "SELECT * FROM {$tbl} {$where} {$sort}";
		//echo $sql;
        $rs = $this->adodb->getArray($sql);

        return array(
		'list'=>$rs,
		'total'=>$cnt
		);
	}


}


?>
