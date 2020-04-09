<?php
/**
 * 사이트접속 분석 class
 * @author  hjlee
 * @version jayjun
 */
class Analytics extends Common{
	public function __construct() {
		parent::__construct();
	}

	public function insert() {
		$page = $_SERVER['REQUEST_URI'];
		$page_referer = $_SERVER['HTTP_REFERER'];
	
		$browser = $this->getBrowser();
		$record = array(
			'mem_id'=>MEMID,
			'session_id'=>session_id(),
			'page'=>$page,
			'page_referer'=>$page_referer,
			'device'=>$browser['device'],
			'browser'=>$browser['browser'],
			'browser_version'=>$browser['version'],
			'date_insert'=>NOW
		);
		$tbl = $this->tbls['analytics'];

		$sql = sqlInsert($record, $tbl);
		$this->adodb->Execute($sql);
	}
}