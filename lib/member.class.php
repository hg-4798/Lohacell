<?
class MEMBER extends COMMON{
	var $_conts = array(
		'password_type'=>'sha1',
		'fail_passwod'=>10 //로그인 시도 실패제한 수
	);

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 비밀번호 암호화설정에 따른 처리
	 *
	 * @param [type] $str
	 * @return void
	 */
	public function _password($str) {
		$password_type = $this->conts->password_type;

		switch ($password_type) {
			case 'password':
				break;
			case 'sha1':
			default:
				$rtn = "*".strtoupper(SHA1(unhex(SHA1($str))));
				break;

		}

		return $rtn;
	}

	function setSearch(){
		if(is_array($param)){foreach($param as $f=>$v){
			$this->$f = $v;
		}}
	}

	/**
	 * 회원여부체크
	 *
	 * @param string 리턴url, 빈값인경우 로그인 페이지로 이동
	 * @return boolean
	 */

	public static function isMember($url='') {
		if(!MEMID) {
			$url = ($url)?$url:DIR_VIEW.'/login.php';
			$url.="?chUrl=".urlencode($_SERVER['REQUEST_URI']);
			go($url);
			exit;
		}

	}
	#### member ####
	function getMemberList(){
		$field[] = "*";
		$table[] = "tblmember";
		$query = "select * from tblmember ";
	}

	/**
	 * 회원기본정보
	 * 주요정보 복호화
	 *
	 * @param [type] $member_id
	 * @return void
	 */
	public function getMemberRow($member_id, $field='id, name, email, mobile, home_post, home_addr, home_addr_detail, home_tel, act_point, reserve, group_code') {
		$tbl = $this->tbls['member'];
		$sql = "SELECT {$field} FROM {$tbl} WHERE id='{$member_id}'";
		$row = $this->adodb->getRow($sql);
		if(!$row) return false;

		if(isset($row['mobile'])) {
			$row['mobile'] = $this->Dectypt_AES128CBC($row['mobile']); //데이터 복호화(휴대폰번호)
			$row['mobile_arr'] = explode('-',$row['mobile']);
		}

		if($row['email']) {
			$row['email'] = $this->Dectypt_AES128CBC($row['email']); //데이터 복호화(이메일)
		}

		if($row['home_tel']) {
			$row['home_tel'] = $this->Dectypt_AES128CBC($row['home_tel']); //데이터 복호화(전화번호)
			$row['home_tel_arr'] = explode('-',$row['home_tel']);
		}

		if($row['home_addr_detail']) {
			$row['home_addr_detail'] = $this->Dectypt_AES128CBC($row['home_addr_detail']); //데이터 복호화(상세주소)
		}

		//그룹코드가 있는경우 그룹명
		if($row['group_code']) {
			$group_info = $this->getGroupRow($row['group_code'],'group_name, group_code, group_addreserve');
			$row['group_name'] = $group_info['group_name'];
			$row['group_info'] = $group_info;
		}


		return $row;
	}



	function _getMemberInfo(){

		global $_ShopInfo;

		$id = $_ShopInfo->memid;
		$temkey = $_ShopInfo->tempkey;
		$group_code = $_ShopInfo->memgroup;

		if(!$temkey) $temkey = $_ShopInfo->getTempkey();
		if(!$temkeySelect) $temkeySelect = $_ShopInfo->getTempkeySelectItem();
		if($_ShopInfo->memid){
			//회원정보
			$where=" where id='".$id."' ";
			$query = "select id,name,reserve from tblmember ".$where." ";
			$result = pmysql_query($query);

			while($row = pmysql_fetch_array($result)){
				$data=$row;
			}

			//보유쿠폰수량
			list($coupon_cnt) =pmysql_fetch("SELECT COUNT(*) as cnt FROM tblcouponissue WHERE id='".$id."' AND used='N' AND (date_end>=cast(current_date as varchar) OR date_end='')");
			$data[coupon_cnt]=$coupon_cnt;


			//장바구니수량
			if($temkey && $temkeySelect){
				$cart_qry="select COUNT(*) as cnt from tblbasket where tempkey in ('".$temkey."', '".$temkeySelect."')";

			}else if($temkey && !$temkeySelect){
				$cart_qry="select COUNT(*) as cnt from tblbasket where tempkey in ('".$temkey."')";
			}else{
				$cart_qry="select COUNT(*) as cnt from tblbasket where id='".$id."'";
			}
			list($cart_cnt) =pmysql_fetch($cart_qry);
			$data[cart_cnt]=$cart_cnt;
			//위시리스트 수량
			list($wish_cnt) =pmysql_fetch("select COUNT(*) as cnt from tblwishlist where id='duo135'");
			$data[wish_cnt]=$wish_cnt;
			//그룹정보
			$query = "select * from tblmembergroup where group_code='".$group_code."' order by group_level asc ";
			$result = pmysql_query($query, get_db_conn());
			while($row = pmysql_fetch_array($result)){
				$data[group] = $row;
			}

		}else{
			$data[coupon_cnt]=0;
			$data[cart_cnt]=0;
			$data[wish_cnt]=0;
		}
		return $data;
	}


	function getMemberGroupList(){
		$query = "select * from tblmembergroup order by group_level asc ";
		$data = $this->adodb->getArray($query);
		/*
		$result = pmysql_query($query, get_db_conn());
		while($row = pmysql_fetch_array($result)){
			$data[] = $row;
		}
		*/
		return $data;
	}

	public function getGroupPair() {
		$tbl = $this->tbls['member_group'];
		$query = "select group_code, group_name from {$tbl} order by group_level asc ";
		$data = $this->adodb->getAssoc($query);
		return $data;
	}

	function getMemberGroupInfo(){
		$query = "select * from tblmembergroup order by group_level asc ";
		$result = pmysql_query($query, get_db_conn());
		while($row = pmysql_fetch_array($result)){
			$data[$row[group_code]] = $row;
		}
		return $data;
	}

	public function getGroupRow($group_code, $field='*') {
		$tbl = $this->tbls['member_group'];
		$sql = "SELECT {$field} FROM {$tbl} WHERE group_code='{$group_code}' ";
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	/**
	 * 회원 로그아웃처리
	 *
	 * @return void
	 */
	function logout() {
		global $_ShopInfo;

		$tbl = $this->tbls['member'];
		$mid = $_ShopInfo->getMemid();
		$sql = "UPDATE {$tbl} SET authidkey='logout' WHERE id='{$mid}'";
		$rs = $this->adodb->Execute($sql);
		$this->_log(MEMID, 'logout');
		if($rs) {
			$_ShopInfo->SetMemNULL();
			$_ShopInfo->Save();

			return true;
		}
		else return false;

	}

	/**
	 * 회원 검색리스트
	 * @param string $where
	 * @return array
	 */
	function getMemberSearch($where='', $limit, $offset) {
		$tbl = $this->tbls['member'];

		$cnt = $this->adodb->getOne("SELECT count(*) FROM {$tbl} {$where}");//전체개수

		$sql = "SELECT * FROM {$tbl} {$where} LIMIT $limit offset $offset ";
		//pre($sql);
		$rs = $this->adodb->Execute($sql);
		if (!is_object($rs)) {
			return false;
		}
		$list = Array();
		while ($row = $rs->FetchRow()) {
			$list[]= $row;
		}

		$total = $cnt;
		//print_r($list);exit;
		return array(
			'list'=>$list,
			'total'=>$total
		);
	}

	public function getMemberSimple($where='') {
		$tbl = $this->tbls['member'];
		$sql = "SELECT id FROM {$tbl} {$where} ORDER BY name ASC  ";
		$list = Array();
		$rs = $this->adodb->Execute($sql);
		while ($row = $rs->FetchRow()) {
			$list[]= $row['id'];
		}
		return $list;
	}
	#### member group ####

	/**
	 * 회원로그
	 *
	 * @param sting $member_id
	 * @param string $type (login:로그인, join:회원가입, out:탈퇴)
	 * @return void
	 */
	public function _log($member_id, $type) {
		$tbl = $this->tbls['member_log'];

		$sql = sqlInsert(array(
			'id'=>$member_id,
			'type'=>$type,
			'access_type'=>VIEWPORT,
			'date_insert'=>NOW
		), $tbl);
		//pre($sql);exit;
		$rs = $this->adodb->Execute($sql);
		return $rs;
	}


	/**
	 * 회원 휴면처리
	 *
	 * @return void
	 */
	public function memberSleep() {
		echo "
		===========================================================================
		휴면회원처리 Batch Start (".date("Y-m-d H:i:s").")
		===========================================================================
		";
		if(IS_DEV) {
			$db_name = 'dev_jayjun';
		}else{
			$db_name = 'jayjun';
		}
		$table_name = 'tblmember';
		$table_sleep_name = 'tblmember_sleep';
		$skip_field = array('id','passwd','idx','mem_seq'); // tblmember 에 남겨둬야 할 데이터(ID,PW 등등 설정)
		// 컬럼 동기화를 자동으로 하자..
		// 누군가는 혹시... 컬럼을.. member에만 추가하고 sleep 에는 추가 하지 않을 수 있으니까~
		$sql = "SELECT *
		  FROM INFORMATION_SCHEMA.COLUMNS
		 WHERE TABLE_CATALOG = '$db_name'
		   AND TABLE_NAME    = '$table_name'
		";
		$rs = pmysql_query($sql);
		$column = array();
		$column_info = array();
		while($row = pmysql_fetch($rs)) {
			$column[] = $row['column_name'];
			$temp_default = '';
			$temp_default = explode("::",$row['column_default']);
			if(strlen($temp_default[0]) < 1) $temp_default[0] = "NULL";
			$row['default_value'] = $temp_default[0];
			$column_info[$row['column_name']] = $row;
		}
		$sql = "SELECT *
		  FROM INFORMATION_SCHEMA.COLUMNS
		 WHERE TABLE_CATALOG = '$db_name'
		   AND TABLE_NAME    = '$table_sleep_name'
		";
		$rs = pmysql_query($sql);
		$column_sleep = array();
		$column_info_sleep = array();
		while($row = pmysql_fetch($rs)) {
			$column_sleep[] = $row['column_name'];
			$column_info_sleep[$row['column_name']] = $row;
		}
		$logindate = date("YmdHis",strtotime("-12 month"));
		//$logindate = '20180420';
		//$logindate = "20180426192716' or id like 'viviooi";
		$UpdateSql = array();
		foreach($column as $col) {
			if(in_array($col,$column_sleep) == false) {
				// 컬럼이 없다면 추가 한다.
				$AddSql = "";
				$AddSql = "alter table $table_sleep_name add column ".$col." ".$column_info[$col]['data_type'];
				if($column_info[$col]['character_maximum_length'] > 0) $AddSql .= "(".$column_info[$col]['character_maximum_length'].")";
				if($column_info[$col]['column_default']) $AddSql .= " default ".$column_info[$col]['column_default'];
				$result = pmysql_query($AddSql);
				if(!$result) {
					echo "컬럼 동기화 실패";
				} else {
					$column_sleep[] = $col;
					$column_info_sleep[$col] = $column_info[$col];
				}
			}
			if(in_array($col,$column_sleep)) {
				if(in_array($col,$skip_field) == false) {
					if($col == 'member_out') $UpdateSql[] = $col." = 'S' ";
					else $UpdateSql[] = $col." = ".$column_info[$col]['default_value']." ";
				}
			}
		}

		//$sql = "select * from $table_name where logindate like '".$logindate."%'";
		$sql = "select * from $table_name where member_out = 'N' AND logindate < '".$logindate."%' AND date < '".$logindate."%' ";
		$rs = pmysql_query($sql);
		$columns = implode(",",$column_sleep);
		echo $columns."
		";
		while($mem = pmysql_fetch($rs)) {
			$update_sql = "update $table_name set ".implode(",",$UpdateSql)." where id='".$mem['id']."'
						  ";
			$sql = "insert into $table_sleep_name (".$columns.") select ".$columns." from tblmember where id='".$mem['id']."'";
			$result = pmysql_query($sql);
			if($result) {
				//pre($UpdateSql);
				pmysql_query($update_sql);
				pmysql_query("insert into ".$table_name."log (id,type,access_type,date) values ('".$mem['id']."','sleep','system','".date("YmdHis")."')");
				echo "insert into ".$table_name."log (id,type,access_type,date) values ('".$mem['id']."','sleep','system','".date("YmdHis")."')";
				echo "
		휴면처리 : ".implode(",",$mem);
			} else {
				echo "
		===========================================================================
									휴면회원 처리 실패 
		";
				print_r($mem);
				echo "
		$sql
		$update_sql
		";
				echo "
		===========================================================================";
			}
		}
		echo "
		===========================================================================
		휴면회원처리 Batch End (".date("Y-m-d H:i:s").")
		===========================================================================
		";
	}


	/**
	 * 회원 휴면해제
	 *
	 * @return void
	 */
	public function MemberWakeUp($dupinfo,$news_yn) {
		if(IS_DEV) {
			$db_name = 'dev_jayjun';
		}else{
			$db_name = 'jayjun';
		}
		$table_name = 'tblmember';
		$table_sleep_name = 'tblmember_sleep';
		$skip_field = array('id','passwd','idx','mem_seq'); // tblmember 에 남겨둬야 할 데이터(ID,PW 등등 설정)
		// 컬럼 동기화를 자동으로 하자..
		// 누군가는 혹시... 컬럼을.. member에만 추가하고 sleep 에는 추가 하지 않을 수 있으니까~
		$sql = "SELECT *
	  FROM INFORMATION_SCHEMA.COLUMNS
	 WHERE TABLE_CATALOG = '".$db_name."'
	   AND TABLE_NAME    = '$table_name'
	";
		$rs = pmysql_query($sql);
		$column = array();
		$column_info = array();
		while($row = pmysql_fetch($rs)) {
			$column[] = $row['column_name'];
			$temp_default = '';
			$temp_default = explode("::",$row['column_default']);
			if(strlen($temp_default[0]) < 1) $temp_default[0] = "NULL";
			$row['default_value'] = $temp_default[0];
			$column_info[$row['column_name']] = $row;
		}
		$sleep_info = pmysql_fetch(pmysql_query("select * from $table_sleep_name where dupinfo='{$dupinfo}' or id='test'"));
		if(!$sleep_info) return false;
		foreach($column as $col) {
			if(in_array($col,$skip_field) == false and $sleep_info[$col]) {
				if($col == 'member_out') $UpdateSql[] = $col." = 'N' ";
				else if($col == 'news_yn') $UpdateSql[] = $col." = '$news_yn' ";
				else $UpdateSql[] = $col." = '".$sleep_info[$col]."' ";
			}
		}
		$UpdateSql = "update $table_name set ".implode(",",$UpdateSql)." where id='".$sleep_info['id']."'";
		$rs = pmysql_query($UpdateSql);
		if(!$rs) return false;
		$sql = "delete from $table_sleep_name where dupinfo='{$dupinfo}' or id='test'";
		pmysql_query($sql);
/*		//휴면 해제에 대한 로그를 남긴다.
		$textDir = DOCUMENT_ROOT.'/data/backup/member_wakeup_'.date("Ym").'/';
		$outText = '========================='.date("Y-m-d H:i:s")."=============================\n";
		$outText.= " update sql   \n";
		$outText.= $UpdateSql."\n";
		$outText.= " delete sql   \n";
		$outText.= $sql."\n";

		if(!is_dir($textDir)){
			mkdir($textDir, 0700);
			chmod($textDir, 0777);
		}
		$outText.= "\n";
		$upQrt_f = fopen($textDir.'member_wakeup_'.date("Ymd").'.txt','a');
		fwrite($upQrt_f, $outText );
		fclose($upQrt_f);
		chmod($textDir."member_wakeup_".date("Ymd").".txt",0777);*/
		//session_destroy();
		return true;

	}

}
?>