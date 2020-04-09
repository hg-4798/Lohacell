<?
/**
 * 프로모션 클래스
 */
class PROMOTION extends COMMON{
	public function __construct() {
		parent::__construct();
	}

//	/**
//	 * 프로모션 idx별 프로모션정보
//	 * @param  [type]  $pidx [description]
//	 * @return [type]			   [description]
//	 */
//	public function getPromotionChildrenCount($pidx){
//		$sql = "SELECT count(*) FROM tblpromotion WHERE promo_idx = '{$pidx}' ";
//		$promotion_child_cnt = $this->adodb->getRow($sql);
//		return $promotion_child_cnt['count'];
//	}

//	/**
//	 * 프로모션 idx별 프로모션정보
//	 * @param  [type]  $pidx [description]
//	 * @return [type]			   [description]
//	 */
//	public function getPromotionChildrenList($pidx){
//		$sql = "SELECT * FROM tblpromotion WHERE promo_idx = '{$pidx}' ";
//		$sql .= " ORDER BY display_seq ASC ";
//		$promotion_rs = $this->adodb->Execute($sql);
//		while($promotion_row = $promotion_rs->FetchRow()){
//			if($promotion_row['seq']) {
//				$promotion_list[] = $promotion_row;
//			}
//		}
//		return $promotion_list;
//	}

	/**
	 * 프로모션별 상세프로모션정보(상품포함)
	 * @param  [type]  $pidx [description]
	 * @return [type]			   [description]
	 */
	public function getPromotionList($pidx, $use_yn='Y'){
		$Product = new PRODUCT;
		$use_yn_arr = explode(',',$use_yn);
		$use_yn = implode("','",$use_yn_arr);
		$sql = "SELECT * FROM tblpromotion WHERE promo_idx = '{$pidx}' AND use_yn IN ('{$use_yn}')";
		$sql .= " ORDER BY display_seq ASC ";

		$promotion_rs = $this->adodb->Execute($sql);
		$promotion_cnt = $promotion_rs->RecordCount(); //페이징용 전체 개수 가져오기
		$pi = 0;
		while($promotion_row = $promotion_rs->FetchRow()){
			if($promotion_row['seq']) {
				$promotion_list[$pi] = $promotion_row;
				$special_sql = "SELECT * FROM tblspecialpromo WHERE special = '{$promotion_row['seq']}' ";
				$special_rs = $this->adodb->Execute($special_sql);
				while($special_row = $special_rs->FetchRow()){
					$special_row['special_cnt'] = explode(",", $special_row['special_list']);
					for($i=0; $i< count($special_row['special_cnt']); $i++){
						$productinfo = $Product->getProductList("productcode = '".$special_row['special_cnt'][$i]."'");
						if($productinfo['goods'][0]) {
							$promotion_list[$pi]['product'][] = $productinfo['goods'][0];
						}
					}
				}
			}
			$pi++;
		}

		return array(
			'promotion_list' => $promotion_list,
			'promotion_cnt' => $promotion_cnt
		);
	}


	/**
	 * 프로모션리스트
	 * @param  [type]  $type		 진행여부
	 * @param  string  $display_type 노출위치(pc/mobile)
	 * @param  integer $page		 [description]
	 * @param  integer $listnum	  [description]
	 * @return [type]				[description]
	 */
	public function getPromoListAndCnt($type, $page=1, $listnum=12, $where='', $executives_yn=''){
		$where = ($where)?' AND '.$where:'';

		switch(DIR_VIEW){
			case '/front':
			default:
				$display_type = array('A','P');
				break;
			case '/m':
				$display_type = array('A','M');
				break;
		}

		if($executives_yn){
			$excurives_arr = explode(',',$executives_yn);
			$executives_yn = implode("','",$excurives_arr);
		}else{
			$executives_yn = 'N';
		}

		$promotion_list = array();

		$sql_where = " WHERE hidden = 0 AND executives_yn IN ('{$executives_yn}') AND display_type IN('".implode("','",$display_type)."') {$where}"; //임직원 기획전은 리스트가 필요없음
		switch ($type){
			case 'close':
				$sql_where .= " AND end_date < '".NOW."'";
				break;
			default:
				$sql_where .= " AND '".NOW."' BETWEEN start_date AND end_date ";
				break;
		}
		$sql = "SELECT * FROM tblpromo  ";
		$sql .= $sql_where;
		$sql .= " ORDER BY idx DESC, display_seq ASC ";
		$promo_cnt_rs = $this->adodb->Execute($sql); //페이징용 쿼리

		//print_r($sql);
		$promo_cnt = $promo_cnt_rs->RecordCount(); //페이징용 전체 개수 가져오기

		if($executives_yn!='Y') {
			$sql .= " LIMIT " . $listnum . " OFFSET " . $listnum * ($page - 1);
		}
		$promotion_rs = $this->adodb->Execute($sql);
		while($promotion_row = $promotion_rs->FetchRow()){
			if($promotion_row['idx']) {
				$promotion_list[] = $promotion_row;
			}
		}
		return array(
			'list' => $promotion_list,
			'cnt' => $promo_cnt
		);
	}

	/**
	 * 프로모션 정보
	 * @param  [type]  $pidx [description]
	 * @return [type]			   [description]
	 */
	public function getPromoInfo($pidx){
		$sql = "SELECT * FROM tblpromo WHERE idx = '{$pidx}' ";
		$row = $this->adodb->getRow($sql);
		//종료이벤트 체크
		$row['end_yn'] = (NOW > $row['end_date'])?true:false;
		//좋아요 체크
		$row = $this->_promotion($row);
		return $row;
	}

	/**
	 * 프로모션 이전글 다음글
	 * @param  [type]  $type, $pidx [description]
	 * @return [type]			   [description]
	 */
	public function getPromoPrevAndNext($type, $pidx){
		switch(DIR_VIEW){
			case '/front':
			default:
				$display_type = array('A','P');
				break;
			case '/m':
				$display_type = array('A','M');
				break;
		}

		$where = " WHERE hidden = 0 AND executives_yn='N' AND display_type IN('".implode("','",$display_type)."') "; //임직원 기획전은 이전글 다음글이 필요없음
		switch ($type){
			case 'close':
				$where .= " AND end_date < '".NOW."'";
				break;
			default:
				$where .= " AND '".NOW."' BETWEEN start_date AND end_date ";
				break;
		}
		$sql = "SELECT *
					  FROM (
						   SELECT idx,
							lead(idx) over (ORDER BY idx) as prev_idx,
							lead(title) over (ORDER BY idx) as prev_title,
							lead(event_type) over (ORDER BY idx) as prev_event_type,
														
							lag(idx) over (ORDER BY idx) as next_idx,
							lag(title) over (ORDER BY idx) as next_title,
							lag(event_type) over (ORDER BY idx) as next_event_type
							
							FROM tblpromo
							{$where}
							)as base
					  WHERE base.idx = '{$pidx}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	/**
	 * 프로모션관련상품 존재 확인
	 * @param  [type]  $pidx [description]
	 * @return [type]			   [description]
	 */
	public function getSpecialRowCount($seq){
		$sql = "SELECT count(special) FROM tblspecialpromo WHERE special = '{$seq}' ";
		$special_row_cnt = $this->adodb->getRow($sql);
		return $special_row_cnt['count'];
	}

	/**
	 * 프로모션 idx별 프로모션관련상품정보
	 * @param  [type]  $pidx [description]
	 * @return [type]			   [description]
	 */
	public function getPromotionProduct($pidx){
		$sql = "SELECT product_list FROM tblpromo WHERE idx = '{$pidx}' ";
		$row = $this->adodb->getRow($sql);
		return $row;
	}

	/**
	 * 추가상품조회
	 * @param  [type] $productcode [description]
	 * @return [type]		   [description]
	 */
	public function getPromoProductInfo($productcode) {
		$productcode_array = explode(',',$productcode);
		$add_productcode_list = array();
		$add_product_list = array();
		foreach($productcode_array as $key=>$val) {
			$add_productcode_list[] = $val;
		}
		foreach ($add_productcode_list as $key => $val){
			$product_sql = "SELECT tinyimage, productcode, productname, prodcode, soldout, consumerprice, sellprice, sellprice_dc_rate, quantity
					FROM tblproduct 
					WHERE productcode = '{$val}'
					";
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
	 * 프로모션 배너 이미지 가져오기
	 * @param  [type]  $pidx [description]
	 * @return [type]			   [description]
	 */
	public function getPromotionBanner(){
		$sql = "SELECT banner_img, banner_img_m, banner_link, banner_mlink FROM tblmainbannerimg WHERE banner_no = 127 AND banner_hidden = 1 ORDER BY banner_sort ASC ";
		$rs = $this->adodb->Execute($sql);
		while($row = $rs->FetchRow()){
			$banner_list[] = $row;
		}
		return $banner_list;
	}

	/**
	 * 프로모션 댓글 가져오기
	 * @param  [type]  $pidx [description]
	 * @return [type]			   [description]
	 */
	public function getPromotionComment($pidx, $page=1,$listnum=1){

		$sql = "select num, writetime, name, comment, c_mem_id, title, photo_img from tblboardcomment_promo where parent={$pidx} order by num desc ";

		//echo $sql;

		$cnt_rs = $this->adodb->Execute($sql); //페이징용 쿼리
		$cnt = $cnt_rs->RecordCount(); //페이징용 전체 개수 가져오기

		$sql .= " LIMIT ".$listnum." OFFSET ".$listnum*($page-1);
		$rs = $this->adodb->Execute($sql);
		while($row = $rs->FetchRow()){
			//본인글체크
			$row['owner'] = (MEMID == $row['c_mem_id'] && MEMID)?true:false;
			if($row['photo_img']){
				$row['photo_img'] = unserialize($row['photo_img']);
			}
			$comment_list[] = $row;
		}
		return array(
			'comment_list' => $comment_list,
			'cnt' => $cnt
		);
	}

	/**
	 * 프로모션 포토댓글 상세 정보가져오기
	 * @param  [type]  $bcnum [description]
	 * @return [type]			   [description]
	 */
	public function getPhotoCommentDetail($bcnum){

		$sql = "select num, writetime, name, comment, c_mem_id, title, photo_img from tblboardcomment_promo where num = {$bcnum} ";
		$rs = $this->adodb->Execute($sql);
		while($row = $rs->FetchRow()){
			//본인글체크
			$row['owner'] = (MEMID == $row['c_mem_id'] && MEMID)?true:false;
			if($row['photo_img']){
				$row['photo_img'] = unserialize($row['photo_img']);
			}
			$comment_list = $row;
		}
		return $comment_list;
	}


	/**
	 * 좋아요여부
	 * @param  [type]  $pidx [description]
	 * @return boolean			  [description]
	 */
	private function _isLike($pidx, $promo_type, $uid) {
		if($uid) {
			if(!$this->liked)  {
				$liked_rs = $this->adodb->getArray("SELECT * FROM ".$this->tbls['like']." WHERE section='{$promo_type}' AND like_id='{$uid}'");
				$this->liked = array_column($liked_rs, 'hott_code');
			}
			return in_array($pidx, $this->liked)?true:false;

		}
		else return false;
	}

	/**
	 * 프로모션정보 가공
	 * 공통프로세스
	 *
	 * @param array $row
	 * @return void
	 */

	private function _promotion($row)
	{
		global $_ShopInfo;

		$uid = $_ShopInfo->getMemid();
		$pidx = $row['idx'];
		$promo_type = $row['event_type'];

		//이벤트 타입이 1일경우 promo, 2,3일경우 event
		switch ($promo_type){
			case '1' : $promo_type = 'promo';
			break;
			default : $promo_type = 'event';
			break;
		}

		$row['is_liked'] = $this->_isLike($pidx, $promo_type, $uid); //좋아요상품

		return $row;
	}
}
