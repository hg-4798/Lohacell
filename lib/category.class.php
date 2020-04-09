<?
class CATEGORYLIST extends Common{
	public function __construct() {
		parent::__construct();
		//$this->tbl = $this->tbls['basket']; //메인테이블
		//$this->adodb = adodb_connect();
	}

	/*
	function CATEGORYLIST(){

	}
	*/

	/**
	 * 프론트 노출용 카테고리 트리
	 * @return [type] [description]
	 */
	function getTree_front() {
		$widget = new widget();

		$sql = "SELECT * FROM tblproductcode WHERE code_a='001' AND code_b!='000' ORDER BY code_depth, cate_sort ASC";
		$res = pmysql_query($sql,get_db_conn());
		$cate = array();
		$cate_sort = array();
		while ($row=pmysql_fetch_object($res)) {

			if($row->code_c=="000") {
				// 대분류
				$cd = $row->code_a.$row->code_b;
				$cate_sort[$row->cate_sort] = $cd;

				$cate[$cd] = array();
				$cate[$cd]["name"]	  = $row->code_name;
				$cate[$cd]["code"]	  = str_pad($row->code_a.$row->code_b,12,'0');
				$cate[$cd]["sort"]	  = $row->cate_sort;
				$cate[$cd]["hidden"]	= $row->is_hidden;
				$cate[$cd]["code_type"]	= $row->code_type;
				$cate[$cd]["cate"]	  = array();
				$cate[$cd]['banner'] = $widget->getBanner('pc',$row->code_b);
			} else {
				if($row->code_d=="000") {
					// 중분류
					$cate_c = array();
					$cate_c["name"] = $row->code_name;
					$cate_c["code"] = str_pad($row->code_a.$row->code_b.$row->code_c,12,'0');
					$cate_c["sort"] = $row->cate_sort;
					$cate_c["hidden"] = $row->is_hidden;

					$cate[$row->code_a.$row->code_b]["cate"][$row->code_a.$row->code_b.$row->code_c] = $cate_c;
				} else {
					// 소분류
					$cate_d = array();
					$cate_d["name"] = $row->code_name;
					$cate_d["code"] = str_pad($row->code_a.$row->code_b.$row->code_c.$row->code_d,12,'0');
					$cate_d["sort"] = $row->cate_sort;
					$cate_d["hidden"] = $row->is_hidden;

					$cate[$row->code_a.$row->code_b]["cate"][$row->code_a.$row->code_b.$row->code_c]["cate"][$row->code_a.$row->code_b.$row->code_c.$row->code_d] = $cate_d;
				}
			}
		}
		@ksort($cate_sort);

		return array('sort'=>$cate_sort, 'cate'=>$cate);
	}

	function getCateTree(){ //상품 카테고리 리스트

		$sql = "SELECT * FROM tblproductcode where code_b='000' ORDER BY cate_sort, code_name ";
		$result  = pmysql_query($sql,get_db_conn());

		$html='';
		while ($row = pmysql_fetch_object($result)) {
			$clz='';
			$div_class= (strstr($row->type,'X'))?'doc':'folder'; //상위 하위 결정

			if($row->group_code=='NO') {
				$vtype=1;		// 사용안함
			} elseif ($row->is_hidden=="Y" ) {
				$vtype=2;	// 숨기기
			} else {
				$vtype=0;
			}

			if(strstr($row->list_type,'BL'))$templet_type="(공구형)";
			else $templet_type="";

			$html.="<div class='".$div_class."' id='".$row->code_a."' vtype='".$vtype."'>";
			$html.=$row->code_name.$templet_type;

			if(strstr($row->type,'X')){
				$html.="</div>";
			}
			else{
				$sql_b = "SELECT * FROM tblproductcode where code_a='".$row->code_a."' and code_b!='000' and code_c='000' ORDER BY cate_sort, code_name ";

				$result_b  = pmysql_query($sql_b,get_db_conn());

				while ($row_b = pmysql_fetch_object($result_b)) {
					
					$div_class= (strstr($row_b->type,'X'))?'doc':'folder';
					if($row_b->group_code=='NO') {
						$vtype=1;	  // 사용안함
					} elseif ($row_b->is_hidden=="Y" ) {
						$vtype=2;	// 숨기기
					} else {
						$vtype=0;
					}

					if(strstr($row_b->list_type,'BL'))$templet_type="(공구형)";
					else $templet_type="";


					$html.="<div class='".$div_class."' id='".$row_b->code_a.$row_b->code_b."' vtype='".$vtype."'>";
					$html.=$row_b->code_name.$templet_type;
					//$html.="</div>";
					if(strstr($row_b->type,'X')){
						$html.="</div>";
					}else{


						$sql_c = "SELECT * FROM tblproductcode where code_a='".$row_b->code_a."' and code_b='".$row_b->code_b."' and code_c!='000' and code_d='000' ORDER BY cate_sort, code_name";

						$result_c  = pmysql_query($sql_c,get_db_conn());

						while ($row_c = pmysql_fetch_object($result_c)) {

							$div_class= (strstr($row_c->type,'X'))?'doc':'folder';
							if($row_c->group_code=='NO') {
								$vtype=1;	  // 사용안함
							} elseif ($row_c->is_hidden=="Y" ) {
								$vtype=2;	// 숨기기
							} else {
								$vtype=0;
							}

							if(strstr($row_c->list_type,'BL'))$templet_type="(공구형)";
							else $templet_type="";

							$html.="<div class='".$div_class."' id='".$row_c->code_a.$row_c->code_b.$row_c->code_c."' vtype='".$vtype."'>";
							$html.=$row_c->code_name.$templet_type;
							//$html.="</div>";

							if(strstr($row_c->type,'X')){
								$html.="</div>";
							}else{


								$sql_d = "SELECT * FROM tblproductcode where code_a='".$row_c->code_a."' and code_b='".$row_c->code_b."' and code_c='".$row_c->code_c."' and code_d!='000' ORDER BY cate_sort, code_name";

								$result_d  = pmysql_query($sql_d,get_db_conn());

								while ($row_d = pmysql_fetch_object($result_d)) {

									$div_class= (strstr($row_d->type,'X'))?'doc':'folder';
									if($row_d->group_code=='NO') {
										$vtype=1;	  // 사용안함
									} elseif ($row_d->is_hidden=="Y" ) {
										$vtype=2;	// 숨기기
									} else {
										$vtype=0;
									}

									if(strstr($row_d->list_type,'BL'))$templet_type="(공구형)";
									else $templet_type="";

									$html.="<div class='".$div_class."' id='".$row_d->code_a.$row_d->code_b.$row_d->code_c.$row_d->code_d."' vtype='".$vtype."'>";
									$html.=$row_d->code_name.$templet_type;
									$html.="</div>";
								}
								$html.="</div>";
							}

						}
						$html.="</div>";
					}

				}
				$html.="</div>";
			}

		}


		return $html;
	}



	function getDesignCateTree(){
		$fp = file(dirname(__FILE__)."/../admin/menu_design_u.txt");

		foreach($fp as $v){
			if(trim($v))$v = trim($v);
			if(substr($v,0,1) == "[" && substr($v,-1,1) == "]"){
				$menu['main_title'][] = str_replace(array('[',']'),"",$v);
			}else if(substr($v,0,1) == "<" && substr($v,-1,1) == ">"){
				$menu['title'][] = str_replace(array('<','>'),"",$v);
			}else{
				$k = count($menu[title]) - 1;
				$tmp = explode('= ',$v);
				if(trim($tmp[0])){
					$menu['subject'][$k][] = $tmp[0];
					$url = trim(str_replace('"','',$tmp[1]));
					if (preg_match("/^..\//", $url)) $menu['value'][$k][] = $url;
					else if (preg_match("/^javascript/i", $url)) $menu['value'][$k][] = $url;
					else $menu['value'][$k][] = $url;

				}
			}
		}

		$html='';
		for ($i=0,$m=sizeof($menu['title']);$i<$m;$i++) {
			if($menu['title'][$i] && count($menu['subject'][$i])){

				$div_class='folder';
				$html.="<div class='".$div_class."' vtpye=''>";
				$html.=$menu['title'][$i];

				for ($j=0;$j<count($menu['subject'][$i]);$j++){
					if($menu['subject'][$i][$j]){

						$div_class='doc';
						$html.="<div class='".$div_class."' vtpye=''>";
						$html.="<a href='".$menu['value'][$i][$j]."'>";
						$html.=trim($menu['subject'][$i][$j]);
						$html.="</a></div>";

					}
				}
				$html.="</div>";
			}
		}


		return $html;
	}


	/**
	 * 네비게이션 반환
	 * @param  string $code 카테고리코드
	 * @return array 네비게이션 배열반환
	 */
	public function getNav($code) {

		$cate_arr = str_split($code, 3);
		$nav = array();

		//pre($cate_arr);

		foreach($cate_arr as $k => $v) {
			$parent.=$v;
			if($k==0 || $v=='000') continue;

			$tmp = str_pad($parent,12,'0');

			$sql = "SELECT code_name, CONCAT(code_a, code_b, code_c, code_d) AS code_all FROM tblproductcode WHERE CONCAT(code_a, code_b, code_c, code_d) = '{$tmp}'";
			//pre($k.' : '.$sql);
			$row = $this->adodb->getRow($sql);
			if(!$row) continue;
			$nav[] = array('code'=>$row['code_all'],'name'=>$row['code_name']);

		}


		return $nav;
	}

	public function getMe($code) {
		$code = $this->formatCode($code);

		//$cate_arr = str_split($code, 3);
		list($code_a, $code_b, $code_c, $code_d) = str_split($code,3);
		$depth = $this->_getDepth($code);



		/*
		$code_b = substr($code, 0, 6); //B코드까지
		$code_c = substr($code, 0, 9); //C코드까지
		$code_d = substr($code, 0, d); //C코드까지
		*/

		$info_b = $this->getCateRow($code_a.$code_b);
		//$info_c = ($depth>2)?$this->getCateRow($code_c):array();
		$siblings_c = $this->getChildren($code_a.$code_b);

		if($code_c == '000') {
			$code_c = $siblings_c[0]['code_c'];
		}

		$info_c = $this->getCateRow($code_a.$code_b.$code_c);
		$siblings_d = $this->getChildren($code_a.$code_b.$code_c);

		if($code_d == '000') {
			$code_d = $siblings_d[0]['code_d'];
		}
		$info_d = $this->getCateRow($code_a.$code_b.$code_c.$code_d);



		//$siblings_d = $this->getSiblings($code_d);

		//$cate_3rd = $this->getChildren($cate_1st['code_all']);
		$cate_me = $this->getCateRow($code);
		$max_depth = $this->maxDepth($info_b['code_all']);


		return array(
			'me'=>$cate_me,
			'info'=>array(
				'code_b'=>$info_b,
				'code_c'=>$info_c,
				'code_d'=>$info_d
			),
			'siblings'=>array(
				'code_c'=>$siblings_c,
				'code_d'=>$siblings_d
			),
			'max_depth'=>$max_depth
		);
	}

	/**
	 * 현재카테고리 정보
	 * @param  string $code 카테고리코드
	 * @return array
	 */
	public function getCateRow($code) {
		$code = str_pad($code,12,'0');
		$row = $this->adodb->getRow("SELECT *, CONCAT(code_a, code_b, code_c, code_d) AS code_all FROM tblproductcode WHERE CONCAT(code_a, code_b, code_c, code_d)='{$code}'");
		if($row['code_img']) $row['code_banner'] = DIRECTORY_SEPARATOR.ImageDir.'mainbanner'.DIRECTORY_SEPARATOR.$row['code_img'];
		else $row['code_banner'] = "";
		if($row['code_img2']) $row['code_banner2'] = DIRECTORY_SEPARATOR.ImageDir.'mainbanner'.DIRECTORY_SEPARATOR.$row['code_img2'];
		else $row['code_banner2'] = "";
		return $row;
	}

	/**
	 * 자식카테고리
	 * @param  [type] $parent_code [description]
	 * @return [type]			  [description]
	 */
	public function getChildren($parent_code) {
		$parent_code = $this->formatCode($parent_code);
		$parent_like = rtrim($parent_code,'0');
		$child_depth = $this->_getDepth($parent_code)+1; //자식depth

		$sql = "SELECT *, CONCAT(code_a, code_b, code_c, code_d) AS code_all FROM tblproductcode WHERE CONCAT(code_a, code_b, code_c, code_d) LIKE '{$parent_like}%' AND CONCAT(code_a, code_b, code_c, code_d)!='{$parent_code}' AND is_hidden='N' AND code_depth='{$child_depth}' ORDER BY cate_sort ASC";
		//ECHO $sql;
		$children = $this->adodb->getArray($sql);

		return $children;
	}

	/**
	 * 동일depth(형제)카테고리정보
	 *@param  string $code 카테고리코드
	 * @return [type]	   [description]
	 */
	public function getSiblings($code) {
		$code = rtrim($code,'0');
		$parent_code = substr($code, 0, -3);
		$depth = $this->_getDepth($code);

		$sql = "SELECT *, CONCAT(code_a, code_b, code_c, code_d) AS code_all FROM tblproductcode WHERE CONCAT(code_a, code_b, code_c, code_d) LIKE '{$parent_code}%' and code_depth='{$depth}' ORDER BY cate_sort ASC";
		$siblings = $this->adodb->getArray($sql);
		return $siblings;

	}

	/**
	 * 카테고리리스트
	 * @param  String $where [description]
	 * @return [type]		[description]
	 */
	public function getAll($where='') {
		$sql = "SELECT *, CONCAT(code_a, code_b, code_c, code_d) AS code_all FROM tblproductcode";
		if($where) $sql.=" WHERE {$where}";
		$sql.=" ORDER BY code_depth ASC";
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}


	/**
	 * 보유한 총 depth
	 * @param  [type] $first_code [description]
	 * @return [type]			 [description]
	 */
	public function maxDepth($first_code) {
		$first_code = rtrim($first_code,'0');
		$cnt = $this->adodb->getOne("SELECT COUNT(*) FROM tblproductcode WHERE CONCAT(code_a, code_b, code_c, code_d) LIKE '{$first_code}%' AND code_d!='000'");

		return $cnt?4:3;
	}



	/**
	 * 카테고리별 옵션집합
	 * @param  [type] $code  [description]
	 * @param  string $field [description]
	 * @return [type]		[description]
	 */
	public function getOptionColor($code, $field = 'option_code') {
		return false;
		$code = rtrim($code,'0');
		$tbl_product = $this->tbls['product'];
		$tbl_link = $this->tbls['product_link'];
		$tbl_option = $this->tbls['product_option'];


		$sql = "SELECT DISTINCT po.{$field} FROM {$tbl_link} AS pl LEFT JOIN {$tbl_option} AS po ON(pl.c_productcode=po.productcode) LEFT JOIN {$tbl_product} as p on(p.productcode=po.productcode) WHERE c_category LIKE '{$code}%' AND p.display='Y' AND p.soldout!='S' ORDER BY {$field} ASC";
		if(WIFI === true) {
		   // pre($sql);
		}
		$rs = $this->adodb->getArray($sql);
		return array_values(array_column($rs,$field));
	}


	/**
	 * 카테고리 깊이계산
	 * @param  string $code 카테고리코드
	 * @return int	   [description]
	 */
	private function _getDepth($code) {
		return (strlen(rtrim($code,'0'))/3);
	}


	public function formatCode($code, $depth='') {
		if($depth) {
			$length = $depth*3;
			$code = substr($code,0,$length);
		}
		return str_pad($code,12,'0');
	}

	//라인관련함수
	/**
	 * 라인유효함수
	 *
	 * @return void
	 */
	public function getLineAll($where="display='Y'") {
		$tbl = $this->tbls['product_line'];
		$where = ($where)?' WHERE '.$where:'';
		$sql = "SELECT * FROM {$tbl} {$where}";
		$rs = $this->adodb->getArray($sql);
		return $rs;

	}

}
?>
