<?
/**
 * 디자인관리 클래스
 */
class DESIGN extends COMMON{
	public function __construct() {
		parent::__construct();
	}

	/**
	 * 관리자 메인디자인 상품목록
	 * @param  [type]  [description]
	 * @return [type]  배너정보, 상품리스트
	 */
	public function get_all() {
		$Product = new PRODUCT;

		$sql = "SELECT * FROM tblnewarrivals ";
		//echo $sql;exit;
		$rs = $this->adodb->Execute($sql);
        if (!is_object($rs)) {
            return FALSE;
        }
		$list = array();
		while($row = $rs->FetchRow()) {
			if($row['productcode']) {
				$row['productcode_cnt'] = explode(",", $row['productcode']);
				for($i=0; $i< count($row['productcode_cnt']); $i++){
					$row['info'][$row['productcode_cnt'][$i]] = $Product->getProductDetail($row['productcode_cnt'][$i]);
				}

			}
			$list[] = $row;
		}
		return $list;
	}

	/**
	 * 메인 베스트 배너,상품 목록
	 * @param  [type] $banner   배너 종류 베스트배너 87
	 * @return [type]		   [description]
	 */
	public function best_item($banner='') {
		$tbl = $this->tbls['banner_main'];

		$Product = new PRODUCT;
		$sql = "SELECT * FROM {$tbl} WHERE banner_no='{$banner}' AND banner_hidden = 1 ORDER BY banner_sort ASC";
		//echo $sql;exit;
		$rs = $this->adodb->getRow($sql);
		$list = array();
		$list['info'] = $rs;
		$sql_product = "SELECT * FROM tblmainbannerimg_product where tblmainbannerimg_no='{$rs['no']}' ";
		$rs_product = $this->adodb->Execute($sql_product);

        if (!is_object($rs_product)) {
            return FALSE;
        }
		$product_arr = array();


		while($row = $rs_product->FetchRow()) {
			$product_arr[]  = $Product->getProductDetail($row['productcode']);
		}
		$list['product']['first'] = array_slice($product_arr, 0,2);
		$list['product']['second'] = array_slice($product_arr, 2);
		$list['product']['mobile'] = $product_arr;
		// debug($list);
		return $list;
	}

	/**
	 * 메인 중간배너 목록
	 * @param  [type] $banner 배너번호 중간배너 85
	 * @return [type]		   [description]
	 */
	public function middle_banner($banner='') {
		$tbl = $this->tbls['banner_main'];

		$sql = "SELECT * FROM {$tbl} WHERE banner_no='{$banner}' AND banner_hidden = 1 ORDER BY banner_sort ASC ";
		$rs = $this->adodb->getArray($sql);

		return $rs;
	}
	/**
	 * 메인 하단배너 목록
	 * @param  [type] $banner 배너번호 하단배너 118
	 * @return [type]		   [description]
	 */
	public function bottom_banner($banner='') {
		$tbl = $this->tbls['banner_main'];

		$sql = "SELECT * FROM {$tbl} WHERE banner_no='{$banner}' AND banner_hidden = 1  ";
		$rs = $this->adodb->getRow($sql);

		return $rs;
	}

	/**
	 * 메인 new_arrivals 배너,상품 목록
	 * @param  [type]  [description]
	 * @return [type]  [description]
	 */
	public function new_arrivals_banner() {
		$Product = new PRODUCT;
        $Category = new CATEGORYLIST;
		$sql = "SELECT * FROM tblnewarrivals WHERE use_yn ='Y' ORDER BY idx ASC";
		//echo $sql;exit;
		$rs = $this->adodb->Execute($sql);

        if (!is_object($rs)) {
            return FALSE;
        }
		$list = array();
		$product_arr =array();

		$no = 0;
		while($row = $rs->FetchRow()) {
			$productcode_arr = explode(',',$row['productcode']);
			foreach ($productcode_arr AS $key => $val){
				$product_arr[$no][]  = $Product->getProductDetail($val);
            }
			$list[$no] = $row;
			$no++;
		}

		foreach ($product_arr AS $k => $v){

            $list[$k]['product'] = $v;
//            @TODO 작업자에게 확인필요
            //$list[$k]['product'][$k]['category']  = $Category->getCateRow($product_arr[$k][$k]['code_represent']);


		}
		//print_r($list);
		//exit;
		//print_r($list);
		return $list;
	}
	/**
	 * 메인 상단롤링 배너,상품 목록
	 * @param  [type] $banner 배너번호 하단배너 77
	 * @return [type]  [description]
	 */
	public function rolling_banner($banner='') {
		$tbl = $this->tbls['banner_main'];
		$sql = "SELECT * FROM {$tbl} WHERE banner_no='{$banner}' AND banner_hidden = 1 ORDER BY banner_sort ASC";
		//echo $sql;exit;
		$rs = $this->adodb->Execute($sql);
		$list = array();
        if (!is_object($rs)) {
            return FALSE;
        }
		$rolling_img_arr =array();
		$rolling_img_m_arr =array();
		$no = 0;
		while($row = $rs->FetchRow()) {
			$rolling_img_arr = explode('|',$row['banner_img']);
			$rolling_img_m_arr = explode('|',$row['banner_img_m']);
			$list[$no] = $row;
			$list[$no]['rolling_img'] = $rolling_img_arr;
			$list[$no]['rolling_img_m'] = $rolling_img_m_arr;
			$no++;

		}
		return $list;
	}

	/**
	 * 메인 인스타 목록
	 * @param  [type]	  $limit   노출 갯수
	 * @return [type] 
	 */
	public function instagram($limit='') {
		$tbl = $this->tbls['instagram'];

		$sql = "SELECT link_url,link_m_url,img_file,img_m_file FROM {$tbl} ORDER BY idx DESC limit {$limit} ";
		$rs = $this->adodb->getArray($sql);
		
		return $rs;
	}
	/**
	 * 메인 GNB 배너 목록
	 * @param  [type]		   [description]
	 * @return [type]		   [description]
	 */
	public function gnb_banner($banner='') {
		global $Dir;
		$tbl = $this->tbls['banner_main'];

		$sql = "SELECT * FROM {$tbl} WHERE banner_no='{$banner}' AND banner_hidden = 1 ORDER BY banner_sort ASC ";
		$rs = $this->adodb->Execute($sql);
		//echo $sql;exit;
        if (!is_object($rs)) {
            return FALSE;
        }
		$no = 0;

		$img_dir = $Dir.DataDir."shopimages/mainbanner/";
		while($row = $rs->FetchRow()) {
			switch($row['menu_category']) {
				case 'BRAND':
					if(count($list['brand_banner']) > 1) continue 2;
					$list['brand_banner'][$no]['img'] = $img_dir.$row['banner_img'];
					$list['brand_banner'][$no]['url'] = $row['banner_link'];
					break;
				case 'SHOPPING':
					if(count($list['shopping_banner']) > 1) continue 2;
					$list['shopping_banner'][$no]['img'] = $img_dir.$row['banner_img'];
					$list['shopping_banner'][$no]['url'] = $row['banner_link'];
					break;
				case 'EVENT':
					if(count($list['event_banner']) > 1) continue 2;
					$list['event_banner'][$no]['img'] = $img_dir.$row['banner_img'];
					$list['event_banner'][$no]['url'] = $row['banner_link'];
					break;
				case 'REVIEW':
					if(count($list['review_banner']) > 1) continue 2;
					$list['review_banner'][$no]['img'] = $img_dir.$row['banner_img'];
					$list['review_banner'][$no]['url'] = $row['banner_link'];
					break;
			}
			$no++;
		}
		//print_r($list);exit;
		return $list;
	}
    /**
     *디자인관리 베스트 리뷰리스트 목록
     * @param  [type] $where 검색조건
     * @return [type]  [description]
     */
    public function best_review_list($where='',$limit='20',$offset='0') {

        $sql = "SELECT b.minimage, a.id,a.name,a.reserve,a.display,a.subject,a.content,a.date,a.productcode,b.productname,b.tinyimage,b.selfcode, a.best_type, a.marks, a.type, a.num, a.staff, b.pr_type FROM tblproductreview a, tblproduct b WHERE a.productcode = b.productcode AND a.main_display='N' {$where}";
        $cnt_rs = $this->adodb->Execute($sql);
        //echo $sql;
        $review_cnt = $cnt_rs->RecordCount();
        $sql .= "ORDER BY a.best_type desc, a.date DESC  LIMIT {$limit} OFFSET {$offset}";
        //echo $sql;
        $rs = $this->adodb->getArray($sql);
        return array(
            'review_cnt'=>$review_cnt,
            'review_list'=>$rs
        );
    }
    /**
     *디자인관리 메인리뷰리스트 목록
     * @param  [type] $tmp 임시저장 조건
     * @return [type]  [description]
     */
    public function main_review_list($tem='Y') {
        if($tem =="Y"){
            $where = "AND a.main_display = 'Y'";
            $sql_update = "UPDATE tblproductreview SET main_display ='N' WHERE main_display='T' ";
            $rs_update = $this->adodb->Execute($sql_update);
        }else{
            $where = "AND a.main_display in('Y','T')";
        }
        $sql = "SELECT b.minimage, a.id,a.name,a.reserve,a.display,a.subject,a.content,a.date,a.productcode,b.productname,b.tinyimage,b.selfcode, a.best_type, a.marks, a.type, a.num, a.staff, b.pr_type FROM tblproductreview a, tblproduct b WHERE a.productcode = b.productcode {$where}  ORDER BY a.sort ASC ";
        //echo $sql;
        $rs = $this->adodb->getArray($sql);
        return $rs;
    }
    /**
     * 메인리뷰배너 리스트 목록
     * @param  [type] [description]
     * @return [type]  [description]
     */
    public function review_banner_list() {

        $sql = "SELECT b.minimage,b.maximage,a.type,a.upfile, a.id,a.name,a.reserve,a.display,a.subject,a.content,a.date,a.productcode,b.productname,b.tinyimage,b.selfcode, a.best_type, a.marks, a.type, a.num, a.staff, b.pr_type FROM tblproductreview a, tblproduct b WHERE a.productcode = b.productcode AND a.main_display ='Y'  ORDER BY a.sort ASC limit 4";
        //echo $sql;
        $rs = $this->adodb->getArray($sql);
        return $rs;
    }

    /**
     * 카테고리 배너 리스트 목록
     * @param  [type] [description]
     * @return [type]  [description]
     */
    public function category_banner_list($categorycode,$type) {
        $Product = new PRODUCT;
        $tbl = $this->tbls['category_banner'];

        if($type == 'main'){ // 프론트 노출
          $WHERE =  "WHERE use_yn = 'Y' AND categorycode like '{$categorycode}%' ";
        }else{ // 관리자 노출
            $WHERE =  "WHERE categorycode = '{$categorycode}' ";
        }
        $sql = "SELECT * FROM {$tbl} {$WHERE} ORDER BY sort ASC";
        //echo $sql;
        $rs = $this->adodb->Execute($sql);

        if (!is_object($rs)) {
            return FALSE;
        }
        $list = array();
        $product_arr =array();

        $no = 0;
        while($row = $rs->FetchRow()) {
            $productcode_arr = explode(',',$row['productcode']);
            foreach ($productcode_arr AS $key => $val){
                $product_arr[$no]  = $Product->getProductDetail($val);
            }
            $list[$no] = $row;
            $no++;
        }
        foreach ($product_arr AS $k => $v){
            $list[$k]['product'] = $v;
        }
        return $list;
    }
    /**
     * 카테고리배너 정보
     *
     * @return void
     */
    public function getCategoryBannerRow($idx) {
        $Product = new PRODUCT;
        $tbl = $this->tbls['category_banner'];
        $sql = "SELECT * FROM {$tbl} WHERE idx='{$idx}' ";
        $rs = $this->adodb->getRow($sql);

        $list = array();

        $list = $rs;
        $list['product'] = $Product->getProductDetail($rs['productcode']);

        //print_r($list);
        return $list;
    }

	/**
	 *
	 * @param  [type]  [description]
	 * @return [type]  [description]
	 */
	public function get_row($where) {

	}

	/**
	 * 팝업조회
	 * @param  [type] $banner 배너번호 하단배너 118
	 * @return [type]		   [description]
	 */
		public function getEventPopupList($type='')
		{
			if($type == '') {
				$type = 'ALL';
			}else{
				$type = "'ALL'".",'$type'";
			}

			$sql = "SELECT * FROM tbleventpopup WHERE is_mobile IN (".$type.") AND start_date<='" . date("Ymd", time()) . "' AND end_date>='" . date("Ymd", time()) . "' ORDER BY num";
			$rs = $this->adodb->Execute($sql);

			$layers = array();
			while ($row = $rs->FetchRow()) {
				if (date("Ymd", time()) > $_COOKIE["layertime" . $row['num']]) {
					$layers[$row['num']] = $row;
				}
			}
			return $layers;
		}
}
?>