<?
class PRODUCT extends COMMON{

	var $_conts = array(
		//승인상태
		'display'=>array(
			'R'=>'대기',
			'N'=>'승인보류',
			'Y'=>'승인완료'
		),
		//판매상태
		'soldout'=>array(
			'N'=>'판매중',
			'S'=>'판매중지',
			'Y'=>'품절'
		),
		//임직원할인
		'staff_dc_yn'=>array(
			'Y'=>'적용',
			'N'=>'미적용'
		),
		//네이버지식쇼핑
		'naver_display'=>array(
			'Y'=>'적용',
			'N'=>'미적용'
		),
		'delivery'=>array(
			'0'=>'기본배송비',
			'1'=>'기본배송비무료',
			'2'=>'개별배송비'
		),
		'delivery_select'=>array(
			'0'=>'고정배송비',
			'1'=>'수량별 배송비',
			'2'=>'수량별 비례 배송비'
		),
		'pr_type' => array(
			'1'=>'일반상품',
			'2'=>'바로구매상품',
			'3'=>'임직원상품',
			'4'=>'추가구매상품'
		)
	);

	var $error_msg;


	public function __construct() {
		parent::__construct();
		$this->ver = $this->getVersion();
	}


	public function getVersion() {
		$cfg = $this->getConfig('product','section');
		return $cfg['refresh'];
	}


	/**
	 * [deprecated]상품코드생성
	 * guess의 경우 관리자 상품등록기능이 없음
	 * @param  [type] $category [description]
	 * @return [type]		   [description]
	 */
	public function createProductcode($category) {
		$sql = "SELECT MAX(productcode) as maxproductcode FROM tblproduct WHERE productcode LIKE '{$category}%' ";
		$row = $this->adodb->getRow($sql);
		if(!$row['maxproductcode']){
			$productcatecode = str_pad($category,12,'0');
			$row['maxproductcode'] = $productcatecode.'000001';
		}else{
			$row['maxproductcode'] = str_pad($row['maxproductcode']+1,18,'0',STR_PAD_LEFT);
		}
		return $row['maxproductcode'];
		//pre($row);
	}


	function setSearch($param){
		if(is_array($param)){foreach($param as $f=>$v){
			$this->$f = $v;
		}}
	}

	/**
	 * 상품정보고시
	 *
	 * @param [type] $productcode
	 * @return void
	 */
	public function getProperty($productcode) {
		$sql = "SELECT name, contents FROM ".$this->tbls['product_property']." WHERE productcode='{$productcode}'";
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}

	/**
	 * 상품리스트
	 *
	 * @return void
	 */
	function getProductList($where='', $offset=0, $limit=20, $orderby='regdate DESC'){
		$tbl = $this->tbls['product'];
		$where_offset = "display='Y' AND soldout!='S'";
		if($where) $where = $where_offset.' AND '.$where;

		$sql = "SELECT * FROM  {$tbl} WHERE {$where}";

		//pre($sql);
		$rs = $this->adodb->Execute($sql);
		$count_search = $rs->RecordCount();

		$sql .=  "ORDER BY {$orderby} LIMIT {$limit} OFFSET {$offset}";
		$rs = $this->adodb->Execute($sql);
		$list = array();

		while($row = $rs->FetchRow()) {
			$list[] = $this->_product($row);
		}

		return array(
			'goods'=>$list,
			'count'=>array('search'=>$count_search),
			'page'=>array(
				'last'=>ceil($count_search/$limit)
			)
		);

	}


	/**
	 * 카테고리별 상품목록
	 * 기본정렬 = 신상품순
	 * @param  [type] $cate_code [description]
	 * @return [type]			[description]
	 *
	 * 
	 */
	public function getProductListByCate($cate_code='', $offset=0, $limit=20, $where='', $orderby='t.modifydate DESC') {
		global $_ShopInfo;
        if($cate_code!='') {
            $cate = rtrim($cate_code, '0');
        }else{
            $cate = '';
		}

		$where_offset = "pl.c_category LIKE '{$cate}%' AND display='Y' AND soldout!='S' AND pr_type=1"; //승인+품절아님+일반상품
		if($where) $where = $where_offset.' AND '.$where;
		else $where = $where_offset;
		$field = "productcode,productname,sellprice,phrase_etc,minimage,over_minimage,p.regdate,p.soldout,icon,pr_like_cnt,sellcount,season_year,selldate,option_type,modifydate, endprice, review_cnt"; 

		$sql = "SELECT * FROM (SELECT DISTINCT ON(p.productcode) {$field} FROM tblproduct AS p LEFT JOIN tblproductlink AS pl ON(p.productcode = pl.c_productcode) WHERE {$where}) t ";
		//echo $sql;
		$rs = $this->adodb->Execute($sql);
		$count_search = $rs->RecordCount();

		$sql .=  "ORDER BY {$orderby} LIMIT {$limit} OFFSET {$offset}";
	
		$rs = $this->adodb->Execute($sql);
		$list = array();
		//echo $sql;
		while($row = $rs->FetchRow()) {
			$list[] = $this->_product($row);
		}

		return array(
			'goods'=>$list,
			'count'=>array('search'=>$count_search),
			'page'=>array(
				'last'=>ceil($count_search/$limit)
			)
		);
		//return $list;
	}

	/**
	 * 좋아요여부
	 * @param  [type]  $productcode [description]
	 * @return boolean			  [description]
	 */
	private function _isLike($productcode,$uid) {
		if($uid) {
			if(!$this->liked)  {
				$liked_rs = $this->adodb->getArray("SELECT * FROM ".$this->tbls['like']." WHERE section='product' AND like_id='{$uid}'");
				$this->liked = array_column($liked_rs, 'hott_code');
			}
			return in_array($productcode, $this->liked)?true:false;

		}
		else return false;
	}

	/**
	 * 상품정보 가공
	 * 공통프로세스
	 *
	 * @param array $row
	 * @return void
	 */
	private function _product($row) {
		global $_ShopInfo;

		$uid = $_ShopInfo->getMemid();
		$productcode = $row['productcode'];

		//품절/일시품절처리
		if($row['soldout'] == 'Y') { //관리자 임의 품절처리
			$row['status'] = 'soldout';
		}
		else {
			if($row['quantity']<1) { //재고수량이 0인경우 일시품절노출
				$row['status'] = 'soldout_temp';
			}
			else { $row['status'] = 'normal';}
		}

		$row['is_liked'] = $this->_isLike($row['productcode'], $uid); //좋아요상품

		if($row['pr_type'] == 4) { //추가구매상품
			
		}
		else {
			//추가상품정보
			$add_product_arr = explode(',',$row['add_product']);
			if(is_array($add_product_arr) && $row['add_product_use'] == 1) {
				foreach($add_product_arr as $add_product_code) {
					$v = $this->getRowSimple($add_product_code);
					if(!$v['visible']) continue; //판매중지된경우

					//품절체크
					if($v['status']!='normal') $v['disabled'] = 'Y'; //옵션상태가 품절이거나, 재고가 0이하인경우
					else $v['disabled'] = 'N';

					$v['buy_min'] = $v['min_quantity']; //최소구매수량
					//최대구매수량
					if($v['max_quantity']<0) { //제한없음
						$v['buy_max'] = $v['quantity']; //현재고수량이 최대구매수량이됨
					}
					else {
						$v['buy_max'] = ($v['quantity'] < $v['max_quantity'])?$v['quantity']:$v['max_quantity']; //최대구매수량, 현재고가 최대구매수량보다 적으면 현재고기준, 크면 최대구매수량기준
					}
					$add_product_info[] = $v;
					
				}
			}
			else {
				$add_product_info = false;
			}
			$row['add_product_info'] = $add_product_info;
		}

		//옵션정보
		$option_rs = $this->adodb->Execute("SELECT option_num, option_name, option_color,  option_quantity, option_quantity_sales, option_use, option_soldout  FROM ".$this->tbls['product_option']." WHERE productcode='{$productcode}'  ORDER BY sort ASC");  
		$option_info = array();  //전체옵션정보
		$option_valid = array(); //유효옵션
		while($v =  $option_rs->FetchRow()) {

			//옵션상태
			$v['option_quantity_remain'] = $v['option_quantity'] - $v['option_quantity_sales']; //잔여재고 = 총재고-판매수량
			

			if($v['option_use'] == 'Y') {
				if($v['option_soldout']=='Y') {
					$stock = 0; //임의품절처리
					$display = 'soldout'; //품절(노출)
				}
				else {
					if($v['option_quantity_remain'] > 0) $display = 'show'; //판매중(노출)
					else $display = 'soldout_temp'; //판매중(노출)
				}
			}
			else {
				$display='hide'; //숨김(비노출)
			}

			$v['display'] = $display;

			if($v['option_color']) {
				$v['colorchip'] = $this->getColorRow($v['option_color'], 'color_name, color_img, color_code, color_cls');
			}

			if($row['max_quantity']<0) { //최대구매수량제한
				$v['option_quantity_max'] = $v['option_quantity_remain'];
			}
			else {
				$v['option_quantity_max'] = ($row['max_quantity']>$v['option_quantity_remain'])?$v['option_quantity_remain']:$row['max_quantity'];
			}
			

			if($display != 'hide') { //프론트노출용 정보
				if($display=='show') $v['disabled'] = 'N'; //옵션상태가 품절이거나, 재고가 0이하인경우
				else $v['disabled'] = 'Y';

				$option_valid[] = $v;
			}

			$option_info[] = $v; //모든정보

			if($option_info) $row['option_info'] = $option_info;
			if($option_valid) $row['option_valid'] = $option_valid;

			//아이콘처리
			if(!$this->icon) $this->icon = $this->getIconAll('icon_pc, icon_mobile, icon_name');
			$icon_arr = explode(',',$row['icon']);
			$row['icon_arr'] = array_filter($this->icon, function ($key) use ($icon_arr) {return in_array($key, $icon_arr);}, ARRAY_FILTER_USE_KEY); //아이콘

		}

		foreach(array('maximage','minimage','tinyimage') as $col) {
			$row[$col] = $row[$col].'?'.$this->ver;
		}

		


		

		return $row;
	}

	/**
	 * 기간할인계산
	 *
	 * @param string $productcode
	 * @param integer $price
	 * @return array
	 */

	public function getTimesaleRate($productcode, $price) {
		$sql = "SELECT sale_rate FROM ".$this->tbls['product_timesale']." WHERE CONCAT(',',productcodes,',') LIKE '%{$productcode}%' AND NOW() BETWEEN date_start AND date_end ORDER BY sale_rate DESC";
		$timesale = $this->adodb->getOne($sql);
		$return = array();
		if($timesale > 0) {
			$sale_rate = $timesale; //할인율
			$sale_price = $price*(100-$timesale)/100; //할인율 적용 판매가
		}
		else {
			$sale_rate = 0;
			$sale_price = $price;
		}

		return array(
			'sale_rate'=>$sale_rate,
			'sale_price'=>$sale_price
		);
	}


	/**
	 * 카테고리별 베스트상품
	 * @param  [type]
	 * @return [type]
	 */
	public function getProductBestByCate($cate_code) {
		$where = "pb.category LIKE '{$cate_code}%' AND p.display='Y' AND p.soldout!='S'";
		$field = 'p.productcode,productname,sellprice,consumerprice,minimage, tinyimage,over_minimage,p.regdate,p.soldout,icon,pr_like_cnt';
		$orderby = "pb.sort ASC";
		$limit = 15;
		$offset = 0;
		$sql = "SELECT DISTINCT ON(p.productcode, pb.sort) {$field} FROM tblproduct AS p LEFT JOIN tblproduct_best AS pb ON(p.productcode = pb.productcode) WHERE {$where} ORDER BY {$orderby} LIMIT {$limit} OFFSET {$offset}";
		// echo $sql;

		$rs = $this->adodb->Execute($sql);
		$list = array();
		while($row = $rs->FetchRow()) {
			$list[] = $this->_product($row);
		}

		return $list;
	}

	/**
	 * 카테고리별 상품개수
	 * @param  [type] $cate_code [description]
	 * @return [type]			[description]
	 */
	public function countProductByCate($cate_code) {
		$cate = rtrim($cate_code,'0');
		$sql = "SELECT COUNT(*)
				FROM tblproduct a
					LEFT JOIN tblproductlink b ON (a.productcode=b.c_productcode)
				WHERE  b.c_category LIKE '{$cate}%'";
		//pre($sql);
		$cnt = $this->adodb->getOne($sql);
		return $cnt;
	}

	/**
	 * 카테고리별 권한체크
	 * @return [type] [description]
	 */
	public function checkGrant($cate_code) {
		global $_ShopInfo;

		$sql = "SELECT * FROM tblproductcode WHERE CONCAT(code_a, code_b, code_c, code_d) = '{$cate_code}'";
		$row = $this->adodb->getRow($sql);

		if(empty($row)){
			alert_go('','/');
			//Header("Location:/");
		}
		else {
			//접근가능권한그룹 체크
			if($row['group_code']=="NO") {
				Header("Location:/");
			}

			if(strlen($_ShopInfo->getMemid())==0) {
				if(ord($row['group_code'])) {
					$url = $Dir.FrontDir."login.php?chUrl=".getUrl();
					Header("Location:{$url}");
				}
			} else {
				if($row['group_code']!="ALL" && ord($row['group_code']) && $row['group_code']!=$_ShopInfo->getMemgroup()) {
					alert_go('해당 카테고리 접근권한이 없습니다.',$Dir.MainDir."main.php");
				}
			}
		}
	}

	/**
	 * 카테고리유효성체크
	 * @param  [type] $code		 카테고리코드
	 * @param  [type] $product_code 상품코드
	 * @return [type]			   [description]
	 */
	public function checkValid($product_code) {
		//바로구매상품인경우 검증제외
		if(strpos($product_code,DIRECTBUYCATEGORY)!==false) return true;

		$sql = "
			SELECT
				a.*,b.c_maincate,b.c_category
			FROM
				tblproductcode a,tblproductlink b
			WHERE
				a.code_a||a.code_b||a.code_c||a.code_d = b.c_category  AND group_code = '' AND c_productcode = '{$product_code}'"; //AND c_maincate = 1
		//echo $sql;exit;
		$row = $this->adodb->getRow($sql);

		if(empty($row)) return false;
		return true;
	}

	/**
	 * 상품구매권한체크
	 * 1. 임직원상품인경우 임직원만 구매가능
	 * 2. 추가구매상품인경우 상세페이지 미노출
	 * 3. 판매중지 or 미승인 상품인경우 상세페이지 미노출(관리자로그인인경우 확인가능)
	 *
	 * @param array $row 상품정보 row
	 * @return void
	 */
	public function authBuyer($row) {
		if(($row['soldout'] == 'S' || $row['display'] != 'Y') && !$_ShopInfo->id) {
			$this->error_msg="판매중지된 상품입니다.";
			return false;
		}

		switch($row['pr_type']) {
			case '3': //임직원상품
				if(STAFF_YN=='N' && $_ShopInfo->id) { //임직원아니고 관리자도 아닌경우
					$this->error_msg="잘못된 경로로 접근하였습니다.";
					return false;
				}
				
			break;
			case '4': //추가구매상품
				$this->error_msg="잘못된 경로로 접근하였습니다.";
				return false;
			break;
		}

		return true;
	}

	/**
	 * 상품코드별 상품정보
	 * @param  [type]  $productcode [description]
	 * @param  boolean $validation  [description]
	 * @return [type]			   [description]
	*/
	public function getRowSimple($productcode, $validation=true, $field='productcode, prodcode, productname, consumerprice,sellprice, endprice, tinyimage, minimage, maximage, over_minimage, icon, regdate, pr_like_cnt, endprice_dc_rate,soldout, display, pr_type, quantity,  max_quantity, min_quantity, option_type') {
		$sql = "SELECT {$field} FROM tblproduct WHERE productcode='{$productcode}'";
		$row = $this->adodb->getRow($sql);
		
		if($row['soldout'] == 'S' || $row['display'] != 'Y') {
			if($validation) return false;
			else $row['visible'] = false;
		}
		else $row['visible'] = true; //노출여부(노출중)
		
		//품절/일시품절처리
		if($row['soldout'] == 'Y') { //관리자 임의 품절처리
			$row['status'] = 'soldout';
		}
		else {
			if($row['quantity']<1)  $row['status'] = 'soldout_temp';  //재고수량이 0인경우 일시품절노출
			else  $row['status'] = 'normal';
		}

		$row['is_liked'] = $this->_isLike($row['productcode'], MEMID); //좋아요상품

		return $row;
	}



	/**
	 * 상품의 첫번째 연결카테고리 가져오기
	 * @param  [type] $productcode [description]
	 * @return [type]			  [description]
	 */
	public function getCategoryFirst($productcode) {
		$linked = $this->adodb->Execute("SELECT c_category FROM ".$this->tbls['product_link']." WHERE c_productcode='{$productcode}'");
		$linked_represent = '';
		while($v =  $linked->FetchRow()) {
			list($code_a, $code_b, $code_c, $code_d) = str_split($v['c_category'],3);
			if(!$code_c!='000') {
				$code_represent = $v['c_category']; //상품대표카테고리
				break;
			}
		}

		return $code_represent;

	}

	/**
	 * 상품정보 
	 * @param  [type] $productcode [description]
	 * @return [type]			  [description]
	 */
	public function getProductDetail($productcode, $field='*') {
		$sql = "
			SELECT {$field}
			FROM
				tblproduct 
			WHERE productcode='{$productcode}'";

		$row = $this->adodb->getRow($sql);


		//대표카테고리
		$linked = $this->adodb->Execute("SELECT c_category FROM ".$this->tbls['product_link']." WHERE c_productcode='{$productcode}'");
		$linked_represent = '';
		while($v =  $linked->FetchRow()) {
			list($code_a, $code_b, $code_c, $code_d) = str_split($v['c_category'],3);
			if(!$code_c!='000') {
				$row['code_represent'] = $v['c_category']; //상품대표카테고리
				break;
			}
		}

		//공통정보가공
		$row = $this->_product($row);

		return $row;
	}

	

	function getOptionForm($data,$idx){
		if(count($data[option1_data])>1){
			$str = "<select name='option[".$idx."]' class='option'>";
			for($i=1; $i<count($data[option1_data]); $i++){

				if(count($data[option2_data])>1){
					for($j=1; $j< count($data[option2_data]); $j++){
						$str.="<option value='".$data[option1_data][$i].",".$data[option2_data][$j]."'>".$data[option1_data][$i].",".$data[option2_data][$j]." ( ".number_format($data[option_price_data][($i-1)])."￦ )</option>";
					}
				}else{
					$str.="<option value='".$data[option1_data][$i]."'>".$data[option1_data][$i]." ( ".number_format($data[option_price_data][($i-1)])."￦ )</option>";
				}
			}
			$str.="</select>";
		}else{
			$str = "<input type='text' name='option[".$idx."]' value=''>";
		}
		return $str;
	}

	function isProductReview($productcode, $memid, $ordercode=''){
		$cnt = 0;
		$query = "select count(*) from tblproductreview where productcode = '".$productcode."' and id='".$memid."' ";
		if($ordercode){
		$query .="and ordercode='".$ordercode."'";
		}
		$result = pmysql_query($query, get_db_conn());
		list($cnt) = pmysql_fetch_array($result);
		return $cnt;
	}

	function getMemberDcRate(){
		global $_ShopInfo, $paymethod, $receipt_yn;

		$group_code=$_ShopInfo->memgroup;
		if(ord($group_code) && $group_code!=NULL) {
			$sql = "SELECT * FROM tblmembergroup WHERE group_code='{$group_code}' ";
			$result=pmysql_query($sql,get_db_conn());
			if($row=pmysql_fetch_object($result)){
//				$data[type] = substr($row->group_code,0,2);
				$data[price] = $row->group_addmoney;
				$data[reserve] = $row->group_addreserve;
			}
			pmysql_free_result($result);
		}
		return $data;
	}
/*
	function getProductDcRate_old($productcode){
		global $_ShopInfo, $paymethod, $receipt_yn;

		if($_ShopInfo->memid){
			$group_code=$_ShopInfo->memgroup;
			$query = "select * from tblproduct where productcode='".$productcode."'";
			$row = pmysql_fetch_object(pmysql_query($query,get_db_conn()));
			$grpdc_ex=explode(";",$row->membergrpdc);
			foreach($grpdc_ex as $v){
				$grpdc_data=explode("-",$v);
				$grpdc_arr[$grpdc_data[0]]=$grpdc_data[1];
			}
			$dc_per=0;
			$dc_per=$grpdc_arr['lv'.$_ShopInfo->memlevel];

			$data = $this->getMemberDcRate();

			if($dc_per){
				$data[price]=$dc_per."%";
			}

			$etc = strstr($data[price],"%");
			if(strlen($paymethod)>0 && $_ShopInfo->wsmember=="Y" && ( $paymethod!="B" || ( $paymethod=="B" && $receipt_yn!="N") ) && $etc){
				$data[price] = ($data[price]-7).$etc; ### 도매회원 현금결제가 아니거나 영수증 신청여부에 따라 회원할인율 -7% 처리함
			}
		}

		return $data;
	}
*/

	function getProductDcRate($productcode){
		global $_ShopInfo, $paymethod, $receipt_yn;
		if($_ShopInfo->memid){
			$group_code=$_ShopInfo->memgroup;
			$query = "select * from tblproduct where productcode='".$productcode."'";
			$row = pmysql_fetch_object(pmysql_query($query,get_db_conn()));
			$grpdc_ex=explode(";",$row->membergrpdc);
			foreach($grpdc_ex as $v){
				$grpdc_data=explode("-",$v);
				$grpdc_arr[$grpdc_data[0]]=$grpdc_data[1];
			}
			$dc_per=0;
			$dc_per=$grpdc_arr['lv'.$_ShopInfo->memlevel];

			$data = $this->getMemberDcRate();

			//설정이 없으면 0을 넣어줌.
			if($dc_per<0 || $dc_per=='') $dc_per=0;

			//개별옵션을 선택한 상품
			if($row->dctype=='1'){
				$data[price]=$dc_per."%";
			}

			$etc = strstr($data[price],"%");
			if(strlen($paymethod)>0 && $_ShopInfo->wsmember=="Y" && ( $paymethod!="B" || ( $paymethod=="B" && $receipt_yn!="N") ) && $etc){

				$wsdc=$data[price]-7;
				if($wsdc<0) $wsdc=0;

				$data[price] = ($wsdc).$etc; ### 도매회원 현금결제가 아니거나 영수증 신청여부에 따라 회원할인율 -7% 처리함
			}
		}

		return $data;
	}

	/*
		상품의 배송비 정보를 리턴 합니다.
	*/
	function getDeliState($_pdata){
		global $_ShopInfo, $_data;
		$resultData = Array();

		$itemState = "0";
		$msg = "";
		if (($_pdata->deli=="Y" || $_pdata->deli=="N") && $_pdata->deli_price>0) {
			if($_pdata->deli=="Y") {
				$msg = "개별배송비 상품당 :".number_format($_pdata->deli_price)."원";
				$itemState = "1";

			} else {
				$deli_productprice += $_pdata->deli_price;
				$msg = "개별배송비 총 :".number_format($_pdata->deli_price)."원";
				$itemState = "2";
			}
		} else if($_pdata->deli=="F" || $_pdata->deli=="G") {
			if($_pdata->deli=="F") {
				$msg = "무료";
				$itemState = "3";
			} else {
				$msg = "착불";
				$itemState = "4";
			}
		} else {
			$msg = '';

			if ($_data->deli_type == "T" && $_data->deli_basefeetype == "Y" && $_data->deli_basefee < 1) {//무료
				if ($_data->deli_after == "Y") {
					$msg .= " 착불";
					$itemState = "5";
				} else {
					$msg .= " 무료";
					$itemState = "6";
				}
			} else if ($_data->deli_type == "T" && $_data->deli_basefee > 0) { // 유료
				if ($_data->deli_basefeetype == "N") {
					$msg .= "";
					$itemState = "7";
				} else { // 단일 유료 배송료
					if( $_pdata->sellprice >= $_data->deli_miniprice  ){
						$msg .= "무료";
					} else {
						$msg .= "";
					}
					$itemState = "8";
				}
			} else {
				$msg .= " 에러";
				$itemState = "0";
			}
		}
		$res = array("itemState"=>$itemState, "msg" => $msg );
		$resultData = array_merge($res, $resultData);
		return $resultData;
	}

	/**
	 * 아이콘row
	 * @param  [type] $icon_idx [description]
	 * @return [type]		   [description]
	 */
	public function getIconRow($icon_idx) {
		$sql = "SELECT * FROM tblproduct_icon WHERE idx='{$icon_idx}'";
		return $this->adodb->getRow($sql);
	}


	/**
	*
	* 함수명 : ProductThumbnail
	* 이미지 썸네일 생성
	* parameter :
	* 	- string prcode : 상품코드 ( 이미지 폴더 )
	*   - string fileName : 업로드 이미지명
	*   - string upFile : 파일명
	*	- makeWidth : 편집될 넓이
	*	- makeHeight : 편집될 높이
	*   - image_dir : 상품코드 (하단에 소스에서 prcode 입력 변수에 날짜를 포함시키게 변경되어 순수 상품코드가 별도로 필요함)
	* 2015 10 30 유동혁
	*/
	function ProductThumbnail ( $prcode, $fileName, $upFile, $makeWidth, $makeHeight, $imgborder, $setcolor='', $image_dir='' ){
		//$imagepath = DirPath.DataDir."shopimages/product/".$prcode."/";
		$imagepath = DirPath.DataDir."shopimages/product/".$image_dir."/";
		$quality = "90";
		if(ord($setcolor)==0) $setcolor="000000";
		$rcolor=HexDec(substr($setcolor,0,2));
		$gcolor=HexDec(substr($setcolor,2,2));
		$bcolor=HexDec(substr($setcolor,4,2));
		$quality = "90";
		if ( ord($fileName) && file_exists( $imagepath.$upFile ) ) {
			$imgname=$imagepath.$upFile; // 위치 + 파일명
			$size=getimageSize($imgname); //파일 사이즈 array ( 0=>width, 1=>height, 2=>imgtype )
			$width=$size[0];
			$height=$size[1];
			$imgtype=$size[2];

			if ($width >= $makeWidth || $height >= $makeHeight) {
				# 파일 타입별 이미지생성
				if($imgtype==1)	  $im = ImageCreateFromGif($imgname);
				else if($imgtype==2) $im = ImageCreateFromJpeg($imgname);
				else if($imgtype==3) $im = ImageCreateFromPng($imgname);
				 // 파일의 넓이나 높이가 큰 부분을 기준으로 파일을 자른다
				 $small_width = $makeWidth;
				 $small_height = $makeHeight;

				 # 타입별로 파일 색상, 크기 리사이즈
				if ($imgtype==1) {
					$im2=ImageCreate($small_width,$small_height); // GIF일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					$color =ImageColorAllocate($im2,$rcolor,$gcolor,$bcolor);
					ImageCopyResized($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					if($imgborder=="Y") imagerectangle ($im2, 0, 0, $small_width-1, $small_height-1,$color );
					imageGIF($im2,$imgname);
				} else if ($imgtype==2) {
					$im2=ImageCreateTrueColor($small_width,$small_height); // JPG일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					$color =ImageColorAllocate($im2,$rcolor,$gcolor,$bcolor);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					if($imgborder=="Y") imagerectangle ($im2, 0, 0, $small_width-1, $small_height-1,$color );
					imageJPEG($im2,$imgname,$quality);
				} else {
					$im2=ImageCreateTrueColor($small_width,$small_height); // PNG일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					$color =ImageColorAllocate($im2,$rcolor,$gcolor,$bcolor);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					if($imgborder=="Y") imagerectangle ($im2, 0, 0, $small_width-1, $small_height-1,$color );
					imagePNG($im2,$imgname);
				}

				ImageDestroy($im);
				ImageDestroy($im2);
				chmod($imgname,0777);
			}
		}
	}

	#파일 로그 function 추가 2015 10 30 유동혁
	function Product_textLog ( $prcode , &$text, $type ){
		$sql = "SELECT sellprice, consumerprice, buyprice, reserve, reservetype, quantity, min_quantity, max_quantity, option1, option2, supply_subject ";
		$sql.= "FROM tblproduct WHERE productcode = '".$prcode."'";
		$res = pmysql_query( $sql, get_db_conn() );
		$row = pmysql_fetch_object( $res );
		// 상품로그
		$text.= "\n* ".$type." ----------------------------------------------------------\n";
		$text.= " productcode	   : ".$prcode."\n";
		$text.= " sellprice		 : ".$row->sellprice."\n";
		$text.= " consumerprice	 : ".$row->consumerprice."\n";
		$text.= " buyprice		  : ".$row->buyprice."\n";
		$text.= " reserve		   : ".$row->reserve."\n";
		$text.= " reservetype	   : ".$row->reservetype."\n";
		$text.= " quantity		  : ".$row->quantity."\n";
		$text.= " min_quantity	  : ".$row->min_quantity."\n";
		$text.= " max_quantity	  : ".$row->max_quantity."\n";
		$text.= " option1		   : ".$row->option1."\n";
		$text.= " option2		   : ".$row->option2."\n";
		$text.= " supply_subject	: ".$row->supply_subject."\n";
		pmysql_free_result( $res );
		// 상품옵션 로그
		$sql2 = "SELECT option_code, option_price, option_quantity, option_type, option_use ";
		$sql2.= "FROM tblproduct_option WHERE productcode = '".$prcode."' ORDER BY option_num ASC ";
		$res2 = pmysql_query( $sql2, get_db_conn() );
		$optionCnt = 1;
		while( $row2 = pmysql_fetch_object( $res2 ) ){
			$text.= "\n [ProductOptions] No. ".$optionCnt."\n";
			$text .= " option_code	   : ".$row2->option_code."\n";
			$text .= " option_price	  : ".$row2->option_price."\n";
			$text .= " option_quantity   : ".$row2->option_quantity."\n";
			$text .= " option_type	   : ".$row2->option_type."\n";
			$text .= " option_use		: ".$row2->option_use."\n";
			$optionCnt++;
		}
		$text.= "\n";
		pmysql_free_result( $res2 );

	}



	function product_related($rmode,$prcode,$prname){ //관련 상품 함수 06 29 원재 ㅠㅠ

		if($rmode == "list"){//해당상품의 관련상품리스트 가져오긔

			$r_sql =" select pr.productname,pr.productcode,pr.tinyimage
					from tblproduct pr
					join tblproduct_related r on pr.productcode = r.r_productcode
					where r.productcode='{$prcode}'
			";
			$r_result = pmysql_query($r_sql);
			while( $r_row = pmysql_fetch_object($r_result) ){
				$r_list[] = $r_row;
			}
			return $r_list;
		}

		if($rmode == "update"){
			$r_product = $_POST['relationProduct'];

			if(!$r_product && $prcode){
				$r_sql3 = " delete from tblproduct_related where productcode='{$prcode}' ";
				pmysql_query($r_sql3);

			}

			if($r_product && $prcode){
				//기존 관련상품 삭제
				$r_sql1 = " delete from tblproduct_related where productcode='{$prcode}' ";
				pmysql_query($r_sql1);

				//새로 등록할 관련상품 동작
				$r_sql2 = " insert into tblproduct_related (productcode,productname,r_productcode,sort) values ";
				foreach($r_product as $r_sort=>$r_val){
					$r_sql_values[] = " ('{$prcode}','{$prname}','{$r_val}',{$r_sort}) ";
				}
				$r_qry = implode(",",$r_sql_values);
				$r_sql2 .= $r_qry;
				pmysql_query($r_sql2);
			}
		}
	}

	/**
	 * 상품상세 기타이미지(2~20)
	 * @param  [type] $prcode [description]
	 * @return [type]		 [description]
	 */
	public function getImageEtc($prcode) {
		$tbl = 'tblmultiimages';
		$sql = "SELECT * FROM {$tbl} WHERE productcode='{$prcode}'";
		$row = $this->adodb->getRow($sql);
		$image = array();
		for($i=1;$i<=10;$i++) {
			$field = 'primg'.str_pad($i, 2, '0', STR_PAD_LEFT);
			$image[$field] = ($row[$field])?$row[$field]:'';
		}

		return $image;
	}

	/**
	 * 상품카테고리
	 * @param  [type] $prcode [description]
	 * @return [type]		 [description]
	 */
	public function getLink($prcode) {
		global $category;

		if(!is_object($category)) {
			include_once dirname(__FILE__)."/category.class.php";
			$category = new CATEGORYLIST;
		}

		$link = array();
		$sql="SELECT * FROM tblproductlink WHERE c_productcode='".$prcode."' AND c_productcode!=''";
		$rs = $this->adodb->Execute($sql);
		while($row = $rs->FetchRow()) {
			$cate_arr = str_split($row['c_category'], 3);
			// pre($cate_arr);
			$nav = $category->getNav($row['c_category']);
			if(!$nav) continue;
			$link[] = $nav;
		}

		return $link;
	}

	/**
	 * 조견표정보 row
	 *
	 * @param [type] $size_idx
	 * @return void
	 */
	public function getSizeRow($size_idx) {
		$sql = "SELECT * FROM tblproduct_size WHERE idx='{$size_idx}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	/**
	 * 카테고리별 노출 조견표
	 *
	 * @param [type] $productcode
	 * @return void
	 */
	public function getSizeByCategory($category) {
		$sql = "SELECT s.size_grp, s.size_name, s.size_info, s.size_image FROM tblproduct_size_show AS ss LEFT JOIN tblproduct_size AS s ON(ss.size_idx=s.idx) WHERE product_category='{$category}'";
		$row = $this->adodb->getRow($sql);

		$size_info = unserialize($row['size_info']);
		if(is_array($size_info)) {
			foreach($size_info as $k => $v) {

				$v['value'] = array_filter(explode(',',$v['value']));
				$size_info[$k] = $v;
			}
		}
		else $size_info = array();
		$row['size_info'] = $size_info;
		return $row;
		//pre($sql);
	}

	public function _size($size_text) {
		$size_info = unserialize($size_text);
		$size = array();
		if(is_array($size_info)) {
			foreach($size_info as $k => $v) {
				if(!trim($v['label'])) continue;
				$v['value'] = array_filter(explode(',',$v['value']));
				$size[$k] = $v;
			}
		}
		else $size = array();

		return $size;
	}

	/**
	 * 매핑되어 사용중인 조견표
	 * @return [type] [description]
	 */
	public function getSizeAll() {
		$sql = "SELECT size_idx, s.size_grp, s.size_name, s.size_info, s.size_image FROM tblproduct_size_show AS ss LEFT JOIN tblproduct_size AS s ON(ss.size_idx=s.idx)";
		$rs = $this->adodb->Execute($sql);
		$list = array();
		while($row = $rs->FetchRow()) {
			$size_grp = strtolower($row['size_grp']);
			$size_info = unserialize($row['size_info']);
			foreach($size_info as $k => $v) {
				$v['value'] = array_filter(explode(',',$v['value']));
				$size_info[$k] = $v;
			}

			$row['size_header'] = array_shift($size_info);
			$row['size_body'] = $size_info;
			$list[$size_grp][$row['size_idx']] = $row;
		}
		return $list;
	}

	/**
	 * 사이즈조견표 노출정보
	 * @param  [type] $show_idx [description]
	 * @return [type]		   [description]
	 */
	public function getSizeShowRow($show_idx) {
		$sql = "SELECT * FROM tblproduct_size_show WHERE idx='{$show_idx}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	/**
	 * 베스트상품목록
	 * @param  [type] $category [description]
	 * @return [type]		   [description]
	 */
	public function getBestByCategory($category) {
		$sql = "SELECT pb.sort, pb.updater_name, pb.updater_id, pb.date_update, p.* FROM tblproduct_best AS pb LEFT JOIN tblproduct AS p ON(pb.productcode=p.productcode) WHERE pb.category='{$category}' AND kind='best' ORDER BY pb.sort ASC";
		//echo $sql;
		$rs = $this->adodb->Execute($sql);
		$list = array();
		$imagepath=$Dir.DataDir."shopimages/product/";//이미지폴더
		while($row = $rs->FetchRow()) {
			if(!$row['productcode']) continue; //존재하지 않는 상품인경우
			$row['thumbnail'] = getProductImage($imagepath, $row['minimage']);
			$list[] = $row;
		}

		return $list;

	}


	/**
	 * 카테고리별 추천상품
	 * @param  [type] $category [description]
	 * @return [type]		   [description]
	 */
	public function getRecommendByCategory($category) {
		$sql = "SELECT pb.sort, pb.updater_name, pb.updater_id, pb.date_update, p.* FROM tblproduct_best AS pb LEFT JOIN tblproduct AS p ON(pb.productcode=p.productcode) WHERE pb.category='{$category}' AND kind='recommend' AND (p.display='Y' AND p.soldout!='S') ORDER BY pb.sort ASC";
		//echo $sql;
		$rs = $this->adodb->Execute($sql);
		$list = array();
		$imagepath=$Dir.DataDir."shopimages/product/";//이미지폴더
		while($row = $rs->FetchRow()) {
			if(!$row['productcode']) continue; //존재하지 않는 상품인경우
			$row['thumbnail'] = getProductImage($imagepath, $row['minimage']);
			$row = $this->_product($row);
			$list[] = $row;
		}

		return $list;
	}

	//노출필드값
	public function trans($field, $value) {
		switch($field) {
			case 'display': //승인상태
			case 'soldout':
			case 'pr_type':
			case 'delivery':
			case 'delivery_select':
				$txt = $this->_conts[$field][$value];
			break;
		}
		return $txt;
	}

	/**
	 * 아이콘목록
	 * @return [type] [description]
	 */
	public function getIconAll($field='*', $is_fix='') {
		$tbl = $this->tbls['product_icon'];

		$sql = "SELECT idx as k, {$field} FROM {$tbl}";
		if($is_fix) $sql.=" WHERE is_fix='{$is_fix}'";

		$rs = $this->adodb->getAssoc($sql);
		if(!$rs) $rs = array();
		return $rs;
	}

	/**
	 * 설정값 리턴
	 * @param  string $field [description]
	 * @return string		[description]
	 * deprecated
	 */
	public function getConfig_deprecated($field) {
		$tbl = 'tblconfig';
		$sql = "SELECT field_value FROM {$tbl} WHERE field='{$field}'";
		$rs = $this->adodb->getOne($sql);
		return $rs;
	}



	/**
	 * 상품옵션별재고
	 * 사이즈 정렬 프로세스 추가
	 *
	 * @param [type] $productcode
	 * @return void
	 */
	public function getStock($productcode, $option_color='') {
		$tbl = $this->tbls['product_option'];
		if($option_color) {
			$where_color = " AND option_color='{$option_color}'";
		}
		$sql = "SELECT CONCAT(option_color,'_',option_code) AS k, * From {$tbl} WHERE productcode='{$productcode}' {$where_color} ORDER BY option_color ASC";

		$rs = $this->adodb->getAssoc($sql);
		if(!is_array($rs)) return false;
		$option_size = $this->sortOption(array_unique(array_column($rs, 'option_code')));
		$option_color = array_unique(array_column($rs, 'option_color'));

		$list = array();

		foreach($option_color as $color) {
			foreach($option_size as $size) {
				$list[] = $rs[$color.'_'.$size];
			}
		}
		return $list;
	}



	/**
	 * 상품옵션(색상)별재고
	 * 키필드 기준으로 재고 수량만을 리턴한다
	 *
	 * @param text $keyfield
	 * @param array $where
	 * @param text $delivery_type
	 * @version jayjun
	 * @return void
	 */
	public function getOptionStock($option_code, $where='') {
		$tbl = $this->tbls['product_option'];

		$where_default = " WHERE option_num='{$option_code}'";
		
		$sql = "SELECT * From {$tbl} {$where_default} ORDER BY option_num ASC";
		$row = $this->adodb->getRow($sql);

		$stock = 0;
		if($row['option_soldout'] == 'Y') $stock = 0; //관리자 임의 품절처리
		else {
			$stock = $row['option_quantity'];
		}

		if($stock<0) $stock=0; //0이하인경우 0으로 처리
		return $stock;
	
	}



	/**
	 * 오늘본상품
	 * 쿠키
	 *
	 * @param $productcode 상품코드
	 */
	public function setToday($productcode) {
		if($_COOKIE['VPC']) $vpc = explode(',',$_COOKIE['VPC']);
		else $vpc = array();
		$vpc[] = $productcode;
		$vpc = array_slice(array_reverse(array_unique(array_reverse($vpc))), -20); //최대 20개까지만 저장, 중복값이 있는경우 앞에 있는 중복값을 삭제
		@setcookie('VPC', implode(',',$vpc), time()+86400, '/');
	}

	/**
	 * 배송비정보
	 */

	public function getDeilvery() {
		$tbl = $this->tbls['shopinfo'];
		$sql = "SELECT deli_basefee,deli_basefeetype,deli_miniprice, deli_miniprice_staff FROM {$tbl}";
		$row = $this->adodb->getRow($sql);
		return $row;
	}


	/**
	 * 옵션순서
	 * @param  [type] $opt  [description]
	 * @param  string $type [description]
	 * @return [type]	   [description]
	 */
	public function sortOption($opt, $type='size') {
		//$opt = array('13','15','3','31','S','XXL','L','4'); //테스트용
		if(!is_array($opt)) return $opt;

		$opt_color = array(); //색상순서
		$opt_size = array('XS','S','M','L','XL','XXL','FF'); //사이즈 순서


		

		sort($opt); //기본정렬

		$opt_numeric = array(); //숫자옵션
		$opt_string = array(); //문자옵션

		foreach($opt as $k=>$v) {
			if(is_numeric($v)) {
				$opt_numeric[] = $v;
				unset($opt[$k]);
				continue;
			} 
			else {
				$idx = array_search($v, $opt_size);
				if($idx !== false) {
					$opt_string[$idx] = $v;
					unset($opt[$k]);
				}
			}
		}


		ksort($opt_string);
		sort($opt_numeric);
		$opt = array_merge($opt_numeric,$opt,$opt_string);

		return $opt;
	}

	/**
	 * 패키지적용상품 동기화
	 * @return [type] [description]
	 */
	public function sync_package() {
		//유효패키지리스트
		$this->adodb->Execute("UPDATE ".$this->tbls['product']." SET package_no='0'");//패키지 리셋
		$sql = "SELECT pp.promo_idx, pp.productcodes FROM ".$this->tbls['promo']." AS p LEFT JOIN ".$this->tbls['package_promo']." AS pp ON(p.idx=pp.promo_idx) WHERE p.hidden='1' AND p.event_type='6' AND NOW() BETWEEN p.start_date AND p.end_date";
		$rs = $this->adodb->Execute($sql);
		while($row = $rs->FetchRow()) {
			$promo_idx = $row['promo_idx'];
			$product_arr = explode(',',$row['productcodes']);
			$this->adodb->Execute("UPDATE ".$this->tbls['product']." SET package_no='{$promo_idx}' WHERE productcode IN ('".implode("','",$product_arr)."')");
		}
	}

	/**
	 * 기간할인 상품동기화
	 * 판매가(sellprice)에서 할인
	 * 한 상품이 여러개의 기간할인에 적용된경우 할인율이 큰것으로 적용
	 *
	 * @param string $productcode 상품코드
	 * @return void
	 */

	public function sync_timesale($productcode='') {
		$tbl = $this->tbls['product_timesale']; //기간할인 설정테이블
		$tbl_product = $this->tbls['product']; //상품테이블
		$w = date('w'); //요일코드

		$sale_info = array();
		if($productcode) { //상품단일처리
			$sale_row = $this->adodb->getRow("SELECT * FROM {$tbl} WHERE '".TIMESTAMP."' BETWEEN date_start AND date_end AND '{$w}' = any(STRING_TO_ARRAY(sale_week,',')) AND '{$productcode}' = ANY(STRING_TO_ARRAY(productcodes,',')) AND display='Y' ORDER BY sale_rate DESC");
			if($sale_row) {
				$sale_info[$productcode] = array(
					'type'=>$sale_row['sale_rate_type'],
					'value'=>$sale_row['sale_rate']
				);
			}
		}
		else {
			$sale_rs = $this->adodb->Execute("SELECT * FROM {$tbl} WHERE '".TIMESTAMP."' BETWEEN date_start AND date_end AND '{$w}' = any(STRING_TO_ARRAY(sale_week,',')) AND display='Y' ORDER BY date_update DESC");
			
			while($row = $sale_rs->FetchRow()){
			
				$productcode_arr = explode(',',$row['productcodes']);
				foreach($productcode_arr as $code) {
					if(array_key_exists($code, $sale_info)) continue;
					$sale_info[$code] = array('type'=>$row['sale_rate_type'], 'value'=>$row['sale_rate']);
				};
			}
		}


		BeginTrans();
		$success = true;

		if(!$productcode) {
			$this->adodb->Execute("UPDATE {$tbl_product} set endprice=sellprice, endprice_dc_rate=sellprice_dc_rate"); //기간할인상품가 초기화 
		}
		foreach($sale_info as $prcode=>$sale) {
			$product_row = $this->getRowSimple($prcode);
			// pre($product_row);
			if($product_row) {
				if($sale['type'] == 'PERCENT') {
					$endprice = $product_row['sellprice']*(100-$sale['value'])/100; //정률할인금액처리
				}
				else {
					$endprice = $product_row['sellprice']-$sale['value']; //정액할인금액처리
				}

				if($endprice < 0) $endprice = 0;
				
				if($endprice!=$product_row['sellprice']) { //판매가와 최종할인금액이 다른경우 최종할인률계산
					$endprice_dc_rate = Common::saleRate($product_row['consumerprice'], $endprice); //정가대비 최종할인율 계산
				}
				else $endprice_dc_rate = $product_row['sellprice_dc_rate'];

				$rs = $this->adodb->Execute("UPDATE {$tbl_product} SET endprice='{$endprice}', endprice_dc_rate='{$endprice_dc_rate}' WHERE productcode='{$prcode}'"); //할인금액적용
				if(!$rs) $success=false;
			}
		}


		if($success) {
			CommitTrans();
		}
		else {
			RollbackTrans();
		}

	}



	/**
	 * 상품erp업데이트시 동기화처리
	 * @return void [description]
	 */
	public function sync() {
		$tbl = $this->tbls['product'];

		//$this->adodb->Execute("UPDATE {$tbl} set endprice=sellprice"); //최종상품가 업데이트 처리

		//상품할인율처리
		//$this->adodb->Execute("UPDATE {$tbl} set sellprice_dc_rate=((consumerprice-endprice)*100/consumerprice)");

		$this->sync_timesale();
		$this->sync_package();

	}

	public function log($act, $act_result, $referer='', $attach='') {
		global $_ShopInfo, $cfg_tbl;
		$record = array(
			'act'=>$act,
			'act_result'=>$act_result,
			'referer'=>$referer,
			'attach'=>$attach,
			'reg_id'=>$_ShopInfo->id,
			'reg_name'=>$_ShopInfo->name,
			'reg_date'=>TIMESTAMP
		);

		$sql = sqlInsert($record,$cfg_tbl['product_log']);
		$this->adodb->Execute($sql);
	}


	/**
	 * 베스트상품목록
	 * @param  [type] $category [description]
	 * @return [type]		   [description]
	 */
	public function getChoiceByCategory($category, $type) {
		if($type=='detail'){
			$WHERE = "WHERE pb.category like '{$category}%' ";
		}else{
			$WHERE = "WHERE pb.category='{$category}'";
		}
		$sql = "SELECT pb.sort, pb.updater_name, pb.updater_id, pb.date_update, p.* FROM tblproduct_best AS pb LEFT JOIN tblproduct AS p ON(pb.productcode=p.productcode) {$WHERE} AND kind='mdchoice' ORDER BY pb.sort ASC";
		//echo $sql;
		$rs = $this->adodb->Execute($sql);
		$list = array();
		$imagepath=$Dir.DataDir."shopimages/product/";//이미지폴더
		while($row = $rs->FetchRow()) {
			if(!$row['productcode']) continue; //존재하지 않는 상품인경우
			$row['thumbnail'] = getProductImage($imagepath, $row['minimage']);
			$list[] = $row;
		}

		return $list;

	}

	/**
	 * PRODCODE 중복체크
	 * @param  [type] $prodcode [description]
	 * @return [type]		   [description]
	 */
	public function getDupProdCode($prodcode) {
		$sql = "SELECT prodcode FROM tblproduct WHERE prodcode='{$prodcode}'";
		//echo $sql;
		$rs = $this->adodb->Execute($sql);
		$prodcode_cnt=$rs->RecordCount();
		return $prodcode_cnt;
	}

	/**
	 * 추가상품조회
	 * @param  [type] $productcode [description]
	 * @return [type]		   [description]
	 */
	public function getAddProductInfo($productcode) {
		$productcode_sql = "	SELECT productcode[i]
					FROM( 
						SELECT generate_series(1, array_upper(productcode,1)) AS i, productcode
						FROM (
							SELECT string_to_array(add_product,',') as productcode
							FROM tblproduct 
							WHERE productcode = '{$productcode}'
						)t
					)t";
		//echo $sql;
		$productcode_rs = $this->adodb->Execute($productcode_sql);
		$add_productcode_list = array();
		$add_product_list = array();
		while($productcode_row = $productcode_rs->FetchRow()) {
			$add_productcode_list[] = $productcode_row;
		}

		foreach ($add_productcode_list as $key => $val){
			$product_sql = "SELECT productcode, productname, soldout
					FROM tblproduct 
					WHERE productcode = '{$val['productcode']}'
					AND pr_type = 4
					";
			//print_r($val);
			//echo $sql;
			$product_rs = $this->adodb->Execute($product_sql);
			while($product_row = $product_rs->FetchRow()){
				if($product_row['productcode']) {
					$add_product_list[] = $product_row;
				}
			}
		}
		return $add_product_list;
	}
	/**
	* PRODCODE 라인 검색
	* @param  [type] $line_code [description]
	* @return [type]		   [description]
	*/
	public function getLineCode($line_code) {
		$sql = "SELECT COUNT(*) FROM tblproduct WHERE line_code='{$line_code}'";
		//echo $sql;
		$cnt = $this->adodb->getOne($sql);
		return $cnt;
	}

	/**
	 * 상품라인리스트
	 * @param  [type]  [description]
	 * @return [type]		   [description]
	 */
	public function getLineList() {
		$product_line_sql = "SELECT * FROM tblproduct_line WHERE display = 'Y'";
		//echo $sql;
		$product_line_rs = $this->adodb->Execute($product_line_sql);
		$product_line_list = array();
		while($product_line_row = $product_line_rs->FetchRow()) {
			$product_line_list[] = $product_line_row;
		}

		return $product_line_list;
	}
	/**
	 * 상품라인검색
	 * @param  [type] $lineidx 라인 idx
	 * @return [type]		   [description]
	 */
	public function getLineDetail($lineidx) {
		$row = $this->adodb->getRow("SELECT * FROM tblproduct_line WHERE idx='{$lineidx}'");

		return $row;
	}

	public function getOptionRow($option_num, $field='*') {
		$sql = "SELECT {$field} FROM ".$this->tbls['product_option']." WHERE option_num='{$option_num}'";
		$row = $this->adodb->getRow($sql);
		if($row['option_type'] == 'C') {
			
		}
		return $row;
	}

	function getOptionList($where, $field='*') {
		$tbl = $this->tbls['product_option'];
		$sql = "SELECT {$field} FROM {$tbl} WHERE {$where}";
		$rs = $this->adodb->getArray($sql);
		return $rs;
	}


	/**
	 * 컬러칩 그룹
	 *
	 * @return void
	 */
	public function getColorGroup() {
		$tbl = $this->tbls['product_color'];
		$sql = "select distinct color_group from {$tbl} WHERE color_group IS NOT NULL";
		$rs = $this->adodb->getArray($sql);
		return array_values($rs);
	}

	/**
	 * 컬러칩 정보
	 *
	 * @return void
	 */
	public function getColorRow($color_code, $field='*') {
		$tbl = $this->tbls['product_color'];
		$sql = "SELECT {$field} FROM {$tbl} WHERE color_code='{$color_code}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	public function setColorTree() {
		$tbl = $this->tbls['product_color'];
		$sql = "select color_name, color_code, color_img, color_group from {$tbl} WHERE use_yn='Y' ORDER BY color_group ASC, color_code ASC";
		$rs = $this->adodb->Execute($sql);
		$tree = array();
		$tree_children = array();
		$color_group = '';
		$idx=0;
		while($row = $rs->FetchRow()) {
			if($color_group != $row['color_group']) {
				$tree_idx = $idx++;
				$tree[$tree_idx] = array(
					'text'=>$row['color_group']
				);
				$color_group = $row['color_group'];
				
			}

			$tree[$tree_idx]['children'][] = array('id'=>$row['color_code'], 'text'=>$row['color_name'],'image'=>$row['color_img']);
		
		}

		$fp = fopen(DOC_ROOT.DIR_ADMIN.DIRECTORY_SEPARATOR.'_config/colorchip.json', 'w+');
		fwrite($fp, "var colorchip=".json_encode($tree).';');
		fclose($fp);
	}


	public function getColorPair() {
		$tbl = $this->tbls['product_color'];
		$sql = "select color_code, color_name from {$tbl} WHERE use_yn='Y'";
		$rs = $this->adodb->getAssoc($sql);
		return $rs;
	}


	/**
	 * 색상코드 사용여부에 따른 옵션처리
	 * 색상코드 사용 - 상품옵션중사용중인 컬러코드 disabled_yn = 'N';
	 * 색상코드 사용안함 - 상품옵션중 사용중인 컬러코드값 disabled_yn='Y', option_use='N', option_soldout='N'
	 *
	 * @param [type] $color_code
	 * @param [type] $use_yn
	 * @return void
	 */
	public function syncColorUse($color_code, $use_yn) {
	}

	/**
	 * 상품기준 옵션리스트
	 * @param  [type]
	 * @return [type]
	 */
	public function getProductOption($productcode) {

		$tbl = $this->tbls['product_option'];

		$rs = $this->adodb->getArray("SELECT * FROM {$tbl} WHERE productcode='{$productcode}'");

		return $rs;
	}



    /**
     * 상품의 카테고리 명 가져오기
     * @return [type] [description]
     */
    public function getCategoryName($productcode)
    {
        $cate_code = $this->getCategoryFirst($productcode);
        $sql = "SELECT code_name FROM tblproductcode WHERE CONCAT(code_a, code_b, code_c, code_d) = '{$cate_code}'";
        $cate_name = $this->adodb->getOne($sql);

        return $cate_name;
    }
}

?>