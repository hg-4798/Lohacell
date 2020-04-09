<?php
class WIDGET {

	public function __construct() {
		global $config, $cfg_tbl;

		$this->adodb = adodb_connect();
		$this->tbls = $cfg_tbl;
	}

	/**
	 * 좋아요
	 * @param string $section (product, lookbook etc.)
	 * @param string $hott_code
	 */
	public function getLikeList($section, $like_id, $hott_code='') {
        /*$section_arr = explode(',',$section);
        $section = implode("','",$section_arr);*/ //추가했다가 삭제 bshan
		//pre('aaa');exit;
		if(!$like_id) return false;
		$sql = "SELECT hott_code as k, * FROM ".$this->tbls['like']." WHERE section IN ('{$section}') ";
		if($hott_code) $sql .=" AND hott_code='{$hott_code}'";
		if($like_id) $sql .=" AND like_id='{$like_id}'";
		$sql .= " ORDER BY hno DESC";
		print_r($sql);
		$rs = $this->adodb->getAssoc($sql);
		return $rs;
	}

	public function sycnLike() {
		$sql = "UPDATE tblproduct AS p SET pr_like_cnt = (SELECT COUNT(*) FROM tblhott_like WHERE hott_code=p.productcode)";
		$this->adodb->Execute($sql);
	}

	/**
	 * 좋아요 목록
	 *
	 * @param string $where field=value&field=value....
	 * @return void
	 */
	public function getLikeAll($where, $offset='', $limit='', $orderby='hno DESC') {
		global $_ShopInfo;

/*		if(!is_object($product)) {
			include_once dirname(__FILE__)."/product.class.php";
			$product = new product;
		}*/
        $product = new product;

		$uid = $_ShopInfo->getMemid();
		$tbl= $this->tbls['like'];
		if($orderby !="" ) $where .= " ORDER BY {$orderby}";
		if($limit !="") $where .= " LIMIT {$limit} OFFSET {$offset}";
		$sql = "SELECT * FROM {$tbl} WHERE {$where}";
		$rs = $this->adodb->Execute($sql);

		//pre($sql);

		//$count_search = $rs->RecordCount();
		$list = array();
		while($row = $rs->FetchRow()) {
            $section = $row['section'];
			switch($section) {
				case 'product':
					$hott_code=$row['hott_code'];
					$section_info = $product->getProductDetail($hott_code);
                    //$section_info = $product->_product($section_info);
                    //$section_info = $product->getProductDetail($hott_code);
					if(!$section_info['productcode']) continue 2;
					$row = $section_info; //html 공통 형식 맞추기위해
//					$row['section_info'] = $section_info;
				break;
                case 'event':
				case 'promo': //@todo
                switch(DIR_VIEW){
                    case '/front':
                    default:
                        $display_type = array('A','P');
                        break;
                    case '/m':
                        $display_type = array('A','M');
                        break;
                }
                //임직원 체크
                $staff_yn = "'N'";
                if(STAFF_YN=='Y'){
                	$staff_yn = "'Y','N'";
				}
					$hott_code=$row['hott_code'];
					$section_info = $this->adodb->getRow("SELECT idx,title,start_date,end_date,event_type,thumb_img,thumb_img_m,display_type FROM tblpromo WHERE idx='{$hott_code}' AND '".NOW."' BETWEEN start_date AND end_date  AND executives_yn in ({$staff_yn}) AND display_type IN('".implode("','",$display_type)."') AND hidden=0 ");
					if(!$section_info) continue 2;
					$row = $section_info; //html 공통 형식 맞추기위해
					//$row['section_info'] = $section_info;
					$row['likeTotal'] = $this->countSection("hott_code='{$hott_code}' AND section='{$section}'");
					$row['isLiked'] = $this->isLike($section, $uid, $hott_code);
				break;
				case 'lookbook': //@todo
					$hott_code=$row['hott_code'];
					$section_info = $this->adodb->getRow("SELECT no, title,  img_file, img_m_file, img, img_m, display, brandcd, season, relation_product FROM tbllookbook WHERE display='Y' AND no='{$hott_code}'");
					if(!$section_info) continue 2;
					$row['section_info'] = $section_info;
					$row['likeTotal'] = $this->countSection("hott_code='{$hott_code}' AND section='lookbook'");
					$row['isLiked'] = $this->isLike('lookbook', $uid, $hott_code);
				break;
			}
			$list[] = $row;

		}
		return array(
			'list'=>$list
			//'last'=>$count_search

		);
	}

	/**
	 * 오늘본상품
	 *
	 * @return void
	 */
	public function getTodayAll() {
		global $_ShopInfo;
		$prm = [];
		$prd = [];
		$product = new product();
		$vpc = array_reverse(explode(',', $_COOKIE['VPC']));
		
		$uid = $_ShopInfo->getMemid();
		foreach ($vpc as $k => $v) {
			$gubun = explode('_', $v);
			if($gubun[0] == 'prm') {
				$row['section'] = 'promotion';
				$hott_code = $gubun[1];
				$section_info = $this->adodb->getRow("SELECT idx,title,start_date,end_date,event_type,thumb_img,thumb_img_m FROM tblpromo WHERE hidden = 1 AND idx='{$hott_code}'");
				if(!$section_info) continue;
				$row['section_info'] = $section_info;
				$row['likeTotal'] = $this->countSection("hott_code='{$hott_code}' AND section='promotion'");
				$row['isLiked'] = $this->isLike('promotion', $uid, $hott_code);
				// array_push($prm, $gubun[1]);
			} elseif($gubun[0] == 'prd') {
				$row['section'] = 'product';
				$hott_code = $gubun[1];
				$section_info = $product->getRowSimple($hott_code);
				if(!$section_info['productcode']) continue;
				$row['section_info'] = $section_info;
				// array_push($prd, $gubun[1]);
			} elseif ($gubun[0] == 'look') {
				$row['section'] = 'lookbook';
				$hott_code = $gubun[1];
				$section_info = $this->adodb->getRow("SELECT no, title,  img_file, img_m_file, img, img_m, display, brandcd, season, relation_product FROM tbllookbook WHERE display='Y' AND no='{$hott_code}'");
				if(!$section_info) continue 2;
				$row['section_info'] = $section_info;
				$row['likeTotal'] = $this->countSection("hott_code='{$hott_code}' AND section='lookbook'");
				$row['isLiked'] = $this->isLike('lookbook', $uid, $hott_code);
			}
			$list[] = $row;
		}
		return array(
			'list'=>$list
		);
	}

	public function getConfig($value, $type='field') {
		$tbl = $this->tbls['config'];
		if($type == 'section') {
			$sql = "SELECT field, field_value FROM {$tbl} WHERE section='{$value}'";
			$rs = $this->adodb->getAssoc($sql);
		}
		else {
			$sql = "SELECT field_value FROM {$tbl} WHERE field='{$value}'";
			$rs = $this->adodb->getOne($sql);
		}

		return $rs;
	}

	/**
	 * 개별개수
	 * @param  [type] $type [description]
	 * @return [type]			[description]
	 */
	public function countSection($where) {
		$tbl= $this->tbls['like'];
		$sql = "SELECT COUNT(*) FROM {$tbl} WHERE {$where}";
		//pre($sql);
		$cnt = $this->adodb->getOne($sql);
		return $cnt;
	}

	/**
	 * 최근검색어 쿠키등록
	 * 중복제거
	 *
	 * @param $word 검색어
	 */
	public function addSearchWord($word) {
		if($_COOKIE['SPC']) $spc = explode(',',$_COOKIE['SPC']);
		else $spc = array();
		$spc[] = base64_encode(trim($word));
		
		$spc = array_slice(array_reverse(array_unique(array_reverse($spc))), -5); //최대 5개까지만 저장, 중복값이 있는경우 앞에 있는 중복값을 삭제
		setcookie('SPC', implode(',',$spc), time()+86400, '/');
	}

	/**
	 * 최근검색어삭제
	 * @param  [type] $word [description]
	 * @return [type]	   [description]
	 */
	public function delSearchWord($word='') {
		if($word) {
			$spc = explode(',',$_COOKIE['SPC']);
			$word = base64_encode($word);
			if (($key = array_search($word, $spc)) !== false) {
				unset($spc[$key]);
			}

			setcookie('SPC', implode(',',$spc), time()+86400, '/');
		}
		else {
			setcookie('SPC', '', time()-1, '/');
		}

	}

	/**
	 * 최근검색어조회
	 * @param  [type] $word [description]
	 * @return [type]	   [description]
	 */
	public function getRecentWord(){
		$spc_arr = array();
		if ($_COOKIE['SPC']) $spc = explode(',', $_COOKIE['SPC']);
		if(!is_array($spc)) return false;
		foreach ($spc as $k => $v) {
			$spc_arr[] = base64_decode($v);
		}
		return $spc_arr;
	}

	/**
	 * 배너정보반환
	 * @param  string $section 배너저장구분
	 * @param  string $type 배너코드(key) 
	 * @return array 배너정보
	 */
	public function getBanner($section, $type) {
		$tbl = $this->tbls['banner_'.$section];

		switch($section) {
			case 'pc':
				if($type == 'all') {
				}
				else {
					$row = $this->adodb->getRow("SELECT * FROM {$tbl} WHERE type='{$type}'");
					$row['img_src'] = DIRECTORY_SEPARATOR.ImageDir.'mainbanner'.DIRECTORY_SEPARATOR.$row['img_url'];
					return $row;
				}
				
				break;
			case 'lnb':
				$list = array();
				$row = $this->adodb->getRow("SELECT * FROM {$tbl} WHERE sdate<=NOW() AND edate>=NOW()");
				$list[$row["type"]] = $row;
				$list[$row["type"]]['img_src'] = DIRECTORY_SEPARATOR.ImageDir.'mainbanner'.DIRECTORY_SEPARATOR.$row['img_url'];
				return $list;

				break;
			case 'promotion':
				break;
		}
	}

	/**
	 * 좋아요여부
	 * @param  [type]  $section [description]
	 * @return boolean		  [description]
	 */
	public function isLike($section, $uid, $hott_code = '') {
		$where = "";
		if($uid) {
			if(!empty($hott_code)) {
				$where .= " AND hott_code = '{$hott_code}'";
			}
			$liked_rs = $this->adodb->getArray("SELECT * FROM ".$this->tbls['like']." WHERE section='{$section}' AND like_id='{$uid}' {$where}");
			return (!empty($liked_rs)) ? true : false;

		} else return false;
	}


	/**
	 * 좋아요 상품 검색
	 *
	 * @param string $where field=value&field=value....
	 * @return void
	 */
	public function getLikesSearch($id, $offset='', $limit='', $orderby='', $search='')
	{

		$result = array();

		$search_sql = "select a.hott_code from tblhott_like a JOIN tblproduct b ON a.hott_code=b.productcode where a.like_id ='{$id}' AND b.soldout='{$search}'ORDER BY a.regdt DESC";

		print_r($search_sql);
		$search_rs = $this->adodb->Execute($search_sql);
		$count_search = $search_rs->RecordCount();
		while ($search_row = $search_rs->FetchRow()) {
			$code_arr[]= $search_row['hott_code'];
		}
		$hott_code = "'".implode("','",$code_arr)."'";
		$where = " like_id ='{$id}' AND hott_code in({$hott_code})";
		$result = $this->getLikeAll($where, $offset, $limit, $orderby);
		$result['total'] = $count_search;
		return $result;
	}


	/**
	 * 진행중인 기획전
	 * @param  string  $where [description]
	 * @param  integer $limit [description]
	 * @return [type]		 [description]
	 */
	public function getPromotion($where='', $limit=2) {
		$where = ($where)?' AND '.$where:'';
		$sql = "SELECT idx,title,event_type FROM ".$this->tbls['promo']." WHERE hidden='1' AND NOW()  BETWEEN start_date AND end_date {$where} ORDER BY idx DESC LIMIT {$limit}";
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}


	/**
	 * 기본검색어, 인기검색어 조회
	 * @param  string  $where [description]
	 * @param  integer $limit [description]
	 * @return [type]		 [description]
	 */
	public function getDefaultAndBestKeyword() {
		// 인기검색어, 기본검색어 조회
		$sql = "SELECT search_info, search_default_keyword FROM tblshopinfo";
		$result=pmysql_query($sql,get_db_conn());
		if($data=pmysql_fetch_object($result)) {

		}
		pmysql_free_result($result);

		$search_info			=$data->search_info;

		$bestkeyword		= "";
		$keyword			= "";											// 인기검색어
		$defaultkeyword 	= $data-> search_default_keyword;	// 기본검색
		if(ord($search_info)) {
			$temp=explode("=",$search_info);
			$cnt = count($temp);
			for ($i=0;$i<$cnt;$i++) {
				if (substr($temp[$i],0,12)=="BESTKEYWORD=") $bestkeyword=substr($temp[$i],12);	#인기검색어기능 사용여부(Y/N)
				else if (substr($temp[$i],0,8)=="KEYWORD=") $keyword=substr($temp[$i],8);	#인기검색어 수동등록 리스트
			}
		}
		if(ord($bestkeyword)==0) $bestkeyword="Y";
		${"check_bestkeyword".$bestkeyword}="checked";
		$best_keyword_list = explode(',',$keyword);
		$best_keyword_list_cnt = count($best_keyword_list);
		// 인기검색어, 기본검색어 조회 끝
		return array(
			'default_keyword' => $defaultkeyword,
			'best_keyword_list' => $best_keyword_list,
			'best_keyword_list_cnt' => $best_keyword_list_cnt
		);
	}


    /**
     * 마이페이지 좋아요 목록 가져오기
     * @param  [type] $word [description]
     * @return [type]	   [description]
     */
    public function getMyLikeList($section, $like_id){
        $search_arr = array();
    	switch ($section){
			case 'product' :
                $search_sql = "select hl.hott_code from tblhott_like hl JOIN tblproduct p ON hl.hott_code=p.productcode where hl.like_id ='{$like_id}' AND section = '{$section}' ORDER BY hl.hno DESC";
				break;
			default :
                $search_sql = "select hl.hott_code from tblhott_like hl JOIN tblproduct p ON hl.hott_code=p.productcode where hl.like_id ='{$like_id}' AND section = '{$section}' ORDER BY hl.hno DESC";
				break;
		}

        $search_rs = $this->adodb->Execute($search_sql);
        while ($search_row = $search_rs->FetchRow()) {
            $search_arr[]= $search_row['hott_code'];
        }
        return $search_arr;
    }
}


?>
