<?php
class Common {
	public function __construct() {
		global $config, $cfg_tbl;
		$this->tbls = $cfg_tbl;
		$this->adodb = adodb_connect();
	}

	public function auto_viewport($url='') {

		$dir = (dirname($_SERVER['PHP_SELF']) == '/m')?'MOBILE':'PC';
		if($dir == VIEWPORT) return false;

		$url = ($url)?$url:$_SERVER['PHP_SELF'];
		if(VIEWPORT == 'MOBILE') {
			$location = str_replace(array('/main/','/front/'),'/m/', $url);
		}
		else {
			$location = str_replace(array('/m/'),'/front/', $url);
		}
		
		header("Location:{$location}");
		exit;
	}

	public function getBrowser() {
		$info = get_browser(null, true);
		$version = explode('.',$info['version']);
		$browser = array(
			'device'=>$info['device_type'],
			'browser'=>$info['browser'],
			'version'=>$version[0],
			'id'=>$info['browser'].'-'.$version[0]
		);
		return $browser;

	}


	public function getConfig($value, $type='field') {
		$tbl = $this->tbls['config'];
		if($type == 'section') {
			$sql = "SELECT field, field_value FROM {$tbl} WHERE section='{$value}'";
			$rs = $this->adodb->getAssoc($sql);
		}
		else {
			
			if(is_array($value)){
				$fiels = implode("','",$value);
				$sql = "SELECT field, field_value FROM {$tbl} WHERE field IN('{$fiels}')";
				$rs = $this->adodb->getAssoc($sql);
			}
			else {
				$sql = "SELECT field_value FROM {$tbl} WHERE field='{$value}'";
				$rs = $this->adodb->getOne($sql);
			}
		}

		return $rs;
	}

	public function setConfig($section, $field, $field_value) {
		$tbl = $this->tbls['config'];
		$sql = "INSERT INTO {$tbl} (section, field, field_value, date_update) VALUES ('{$section}','{$field}','{$field_value}',NOW()) ON CONFLICT (section, field) DO UPDATE SET field_value='{$field_value}', date_update=NOW()";
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

	/**
	 * [_sqlWhere description]
	 * @param  array  $where [description]
	 * @return [type]		[description]
	 */
	protected  function _sqlWhere($where=array(), $include=false, $glue=' AND ') {
		$tmp = array();
		foreach($where as $field=>$value) {
			$tmp[] = "{$field}='{$value}'";
		}

		$where = implode($glue, $tmp);

		if($include) return $where;
		else return ' WHERE '.$where;
	}

	/**
	 * 
	 * @param  [type] $path [description]
	 * @return [type]	   [description]
	 */
	public function ftpToServer($path) {
		$ftp = ftp_connect( $_SERVER["SERVER_ADDR"]== "10.0.0.4"?'10.0.0.5':'10.0.0.4', 21 );
		$login_result = ftp_login($ftp, 'guess', 'GueSS@@1*0725!');
		ftp_pasv($ftp, true);

		return $ftp;
	}

	/**
	 * 암호화처리
	 * @param  [type] $value [description]
	 * @return [type]	   [description]
	 */
	public static function Enctypt_AES128CBC($value,$key='CommerceLabDefaultPassWordKey123',$iv='CommerceLabIvKey') {
		if(is_null($value)) $value = "" ;
		$padSize = 16 - (strlen ($value) % 16) ;
		$value = $value . str_repeat (chr ($padSize), $padSize);
		return bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC, $iv));
	}

	/**
	 * 복호화처리
	 * @param  [type] $value [description]
	 * @return [type]	   [description]
	 */
	public static function Dectypt_AES128CBC($value,$key='CommerceLabDefaultPassWordKey123',$iv='CommerceLabIvKey') {
		$value = str_replace("(","",$value);
		$value = str_replace(")","",$value);
		$value = pack("H*", $value);
		$value = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $value, MCRYPT_MODE_CBC,$iv);
		$valueLen = strlen ($value) ;
		if($valueLen % 16 > 0 ) $value = "";
		$padSize = ord ($value{$valueLen - 1});
		if ( ($padSize < 1) or ($padSize > 16) ) $value = "";
		// Check padding.
		for ($i = 0; $i < $padSize; $i++) {
			if(ord ($value{$valueLen - $i - 1}) != $padSize ) $value = "";
		}
		return substr ($value, 0, $valueLen - $padSize);
	}

	function decrypt_md5($hex_buf,$key="") {
		if(ord($key)==0) $key=enckey;
		$len = strlen($hex_buf);
		$buf = '';
		$ret_buf = '';
		$buf = pack("H*",$hex_buf);
		$key1 = pack("H*", md5($key));
		while($buf) {
				$m = substr($buf, 0, 16);
				$buf = substr($buf, 16);

				$c = "";
				$len_m = strlen($m);
				$len_key1 = strlen($key1);
				for($i=0;$i<16;$i++) {
						$m1 = ($len_m>$i) ? $m{$i} : 0;
						$m2 = ($len_key1>$i) ? $key1{$i} : 0;
						if($len_m>$i)
						$c .= $m1^$m2;
				}
				$ret_buf .= $m = $c;
				$key1 = pack("H*",md5($key.$key1.$m));
		}
		$ret_buf=rtrim($ret_buf,'0');
		return($ret_buf);
	}

	function encrypt_md5($buf,$key="") {
		if(ord($key)==0) $key=enckey;
		$key1 = pack("H*",md5($key));
		while($buf) {
				$m = substr($buf, 0, 16);
				$buf = substr($buf, 16);

				$c = "";
				$len_m = strlen($m);
				for($i=0;$i < 16 ;$i++) {
						if($len_m>$i)
						$c .= $m{$i}^$key1{$i};
				}
				$ret_buf .= $c;
				$key1 = pack("H*",md5($key.$key1.$m));
		}
		$len = strlen($ret_buf);
		for($i=0; $i<$len; $i++)
				$hex_data .= sprintf("%02x", ord(substr($ret_buf, $i, 1)));
		return($hex_data);
	}


	
	/** escape처리
	 *
	 * @param  [type] array혹은 ,로 구분되는 문자열 [description]
	 * @return [type]	   [description]
	 */
	function escapeData($data='')
	{
		global $_POST;
		if($data){
			$data_array = explode(',',$data);
			foreach ($data_array as $data_key => $data_val){
				$_POST[$data_val] = pg_escape_string($_POST[$data_val]);
			}
		}else{
			foreach ($_POST as $key => $val) {
				if (!is_numeric($val) && $val) {
					if (is_array($val)) {
						foreach ($val as $arr_key => $arr_val) {
							if (is_array($arr_val)) {
								foreach ($arr_val as $arr_key2 => $arr_val2) {
									if (!is_numeric($arr_val2) && $arr_val2) {
										$_POST[$key][$arr_key][$arr_key2] = pmysql_escape_string($arr_val2);
									}
								}
							} else {
								if (!is_numeric($arr_val) && $arr_val) {
									$_POST[$key][$arr_key] = pmysql_escape_string($arr_val);
								}
							}
						}
					} else {
						$_POST[$key] = pmysql_escape_string($val);
					}
				}
			}
		}
	}


	/**
	 * 할인율계산
	 *
	 * @param integer $num1 원금액
	 * @param integer $num2 할인금액
	 * @param integer $precision 소수점이하 자릿수
	 * @return void
	 */
	public function saleRate($num1, $num2, $precision=0) {
		if($num2<=0) return 100; //할인된 금액이 0이거나 0보다 작은경우 할인율 100으로 반환
		return round(($num1-$num2)*100/$num1, $precision);
	}


	public function xss_post() {
		global $_POST;
		$_CLEANED = $_POST;
		$Xss = new xss_filter;

		foreach($_CLEANED AS $k1=>&$v1) {
			if (is_array($v1)){
				foreach ($v1 as $k2 => &$v2){
					$v2 = $Xss->filter_it($v2);
				}
			}
			else {
				$v1 = $Xss->filter_it($v1);
			}
		}

		return $_CLEANED;
	}

	/**
	 * 트랜잭션 시작
	 *
	 * @return void
	 */
	public function transBegin() {
		$this->adodb->Execute("BEGIN");
	}

	/**
	 * 트랜잭션 커밋
	 *
	 * @return void
	 */
	public function transCommit() {
		$this->adodb->Execute("COMMIT");
	}

	/**
	 * 트랜잭션 롤백
	 *
	 * @return void
	 */
	public function transRollback() {
		$this->adodb->Execute("ROLLBACK");
	}

	public static function format($v, $format) {
		switch($format) {
			case 'mobile':
				$text = str_replace('-','',$v);
				$rtn = preg_replace("/(0(?:2|[0-9]{2}))([0-9]+)([0-9]{4}$)/", "\\1-\\2-\\3", $v);
				break;
			case 'biz':
				$text = str_replace('-','',$v);
				$rtn = preg_replace("/([0-9]{3})([0-9]{2})([0-9]{4}$)/", "\\1-\\2-\\3", $v);
				break;
			case 'price':
				if(is_numeric($v)) $rtn = number_format($v);
				else $rtn = '';
				break;
			case 'id':
				$rtn = mb_substr($v ,0,-3,"UTF-8")."***";
				break;
			case 'today':
				list($date, $time) = exlode(' ',$v);
				if($date == date('Y-m-d')) {
					$rtn = $time;
				}
				else $rtn = $date;
				break;
			default:
				if(substr($v,0,19) == '1970-01-01 00:00:00' || !$v) $rtn = '-';
				else $rtn = date($format, strtotime($v));
				break;
		}
		return $rtn;
	}

	public function log_file($log_content, $log_path='') {
		//$this->log_db($log_content);
		$dir = DOC_ROOT.'/log/'.date('Ym'); //dirname(.DIRECTORY_SEPARATOR.$log_path);

		if(!is_dir($dir)) {
			mkdir($dir,0777);
		}

		if(!$log_path) $log_path = date('d').".txt";
		if(!($fp = fopen($dir.DIRECTORY_SEPARATOR.$log_path, "a+"))) return 0;

		ob_start();
		echo "\n\n--------------------------------\n";
		echo "DATE :".date('Y-m-d H:i:s')."\n";
		echo "IP :".$_SERVER['REMOTE_ADDR']."\n";
		echo "--------------------------------\n";
		print_r($log_content);
		$ob_msg = ob_get_contents();
		ob_clean();

		if(fwrite($fp, " ".$ob_msg."\n") === FALSE) {
			fclose($fp);
			return 0;
		}
		fclose($fp);
		return 1;
	}

	public function log_db($log_content) {
		$record = array(
			'order_num'=>$log_content['order_num'],
			'contents'=>htmlspecialchars(serialize($log_content), ENT_QUOTES),
			'log_msg'=>htmlspecialchars($log_content['msg'], ENT_QUOTES),
			'log_file'=>$log_content['file'],
			'log_file_line'=>$log_content['line'],
			'reg_ip'=>$_SERVER['REMOTE_ADDR'],
			'date_insert'=>NOW
		);
		$sql = sqlInsert($record, 'tblorder_log_proc');
		$this->adodb->Execute($sql);
	}

	public function error($msg) {
		if(!$this->err_msg) $this->err_msg = $msg;
	}

	/**
	 * curl_json
	 *
	 * @param [type] $url
	 * @param [type] $post_data
	 * @param [type] $http_status
	 * @param [type] $header
	 * @return void
	 */
	function curl($url, $param) {
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$contents = curl_exec($ch);
		curl_close($ch);

		return $contents;
	}

	function insert($record, $tbl) {
		$tbl = $this->tbls[$tbl];
		$sql = sqlInsert($record, $tbl);
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

	function select_all($where, $tbl, $field='*') {
		$tbl = $this->tbls[$tbl];
		$where = $this->_sqlWhere($where);
		$sql = "SELECT * FROM ".$tbl." {$where}";
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}

	function select_row($where, $tbl, $field='*') {
		$tbl = $this->tbls[$tbl];
		$where = $this->_sqlWhere($where);
		$sql = "SELECT * FROM ".$tbl." {$where}";
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	

	public function delete($where, $tbl) {
		if(!$where) return false;
		$tbl = $this->tbls[$tbl];
		$where = $this->_sqlWhere($where);
		$sql = "DELETE FROM {$tbl} {$where}";
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}

}


