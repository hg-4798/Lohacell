<?php
/**
 * 리뷰클래스 class
 * @author  stickcandy81@nate.com (hjlee)
 * 
 */
class Review extends Common{
	public function __construct() {

		parent::__construct();
		$this->tbl = $this->tbls['product_review']; //메인테이블
		$this->dir = '/data/shopimages/review/';
	}

	/**
	 * 후기리스트 페이징
	 * @param  [type] $where   [description]
	 * @param  [type] $offset  [description]
	 * @param  [type] $limit   [description]
	 * @param  string $orderby [description]
	 * @return [type]		  [description]
	 */
	public function getListPaging($where, $offset, $limit, $orderby='num DESC') {
		global $_ShopInfo;

		$member_id = $_ShopInfo->getMemid();
		$tbl = $this->tbl;
		$sql = "SELECT * FROM {$tbl} WHERE {$where} AND deleted='0'";

		$rs = $this->adodb->Execute($sql);
		$count_search = $rs->RecordCount();

		$sql .= " ORDER BY {$orderby} LIMIT {$limit} OFFSET {$offset}";
		$rs = $this->adodb->Execute($sql);
		$list = array();

		while($row = $rs->FetchRow()) {
			$row['mine'] = ($member_id == $row['id'])?true:false; //TODO 내가작성한글인가
			$upfile = $this->dir.$row['upfile'];
			if(is_file($_SERVER['DOCUMENT_ROOT'].$upfile)) $row['img_src']=$upfile;
			else $row['img_src'] = ''; 
			
			$list[] = $row; //$this->_product($row);
		}

		return array(
			'count'=> array(
				//'total'=>$count_total,
				'search'=>$count_search
			),
			'list'=>$list
		);
	}

	/**
	 * 평균별점
	 * @param  string $productcode 상품코드
	 * @return int			  [description]
	 */
	public function getAverageMarks($productcode) {
		$sql = "SELECT avg(marks) FROM ".$this->tbl." WHERE productcode='{$productcode}' AND deleted='0'";
		$average = round($this->adodb->getOne($sql),1); //소수점한자리까지만(정책)
		return $average;
	}

	/**
	 * 상품별 리뷰개수
	 * @param  string $productcode 상품코드
	 * @return [type]			  [description]
	 */
	public function getCount($productcode) {
		$sql = "SELECT COUNT(num) FROM ".$this->tbl." WHERE productcode='{$productcode}' AND deleted='0'";
		$count = $this->adodb->getOne($sql);
		return $count;
	}

	/**
	 * 리뷰작성권한체크
	 * @param  [type] $productcode [description]
	 * @param  string $mid 로그인회원아이디
	 * @return string 작성권한 있는경우 주문번호를 반환, 그 외는 오류코드 반환(1:미로그인상태, 2:작성권한없음(기작성), 3:작성권한없음(구매이력없음))
	 */
	public function getAuth_deprecated($productcode, $mid) {
		if(!$mid) return 1; //미로그인상태

		//작성이력여부체크
		$review_count = $this->adodb->getOne("SELECT COUNT(*) FROM ".$this->tbls['product_review']." WHERE id='{$mid}' AND deleted='0' AND productcode='{$productcode}'");
		if($review_count>0) return 2; //작성권한없음(이미 작성)

		//구매확정이력여부체크
		//구매확정기준 tblorderporduct > op_type = 5, order_conf_date 90일이내
		$order_conf_date_min = date('YmdHis',strtotime('-90 days'));
		$order_code = $this->adodb->getOne("SELECT oi.ordercode FROM ".$this->tbls['order_info']." AS oi LEFT JOIN ".$this->tbls['order_product']." AS op ON(oi.ordercode=op.ordercode) WHERE id='{$mid}' AND op.op_step='5' AND op.productcode='{$productcode}'");
		if(!$order_code) return 3; //작성권한없음(구매이력없음)
		else return $order_code; //권한있음
	}


	 public function getAuth($productcode, $option_code='', $order_num='', $type='') {
		//pre($option_code);
	 	if(!MEMID) return 2; //미로그인상태
		
		 if($type=='detail'){ //상품상세 작성시
			 $product_option = $this->adodb->getArray("SELECT * FROM tblproduct_option WHERE productcode='{$productcode}'");
			 $review_auth = $this->adodb->getArray("SELECT option_code FROM tblproductreview WHERE productcode='{$productcode}' AND id='".MEMID."'");
			 $review_option = array_column($review_auth,'option_code');

			 if(is_array($product_option)) {
				 foreach($product_option as $row) {
					 if(in_array($row['option_num'],$review_option)) continue;
					 //if($review_valid) continue;
					 $review_valid []= $row;
				 }
			 }

			 if(!count($review_valid)){
				return 3 ; //작성권한없음(구매이력없음)
			 }else{
				return $review_valid ; //작성권한없음(구매이력없음)
			}
		}


		if(!empty($option_code)){
			$where_review = " AND option_code='{$option_code}'";
			$where_order = " AND op.option_code='{$option_code}'";
		}
		 if(!empty($order_num)){
			 $where_review .= " AND ordercode='{$order_num}'";
			 $where_order .= " AND oi.order_num='{$order_num}'";
		 }
		$review_log = $this->adodb->getArray("SELECT productorder_idx FROM ".$this->tbls['product_review']." WHERE id='".MEMID."'  AND productcode='{$productcode}' {$where_review}");

		//구매확정이력여부체크
		//구매확정기준 tblorderporduct > op_type = 5, order_conf_date 90일이내
		$order_log = $this->adodb->getArray("SELECT oi.order_num, op.idx, op.productcode,op.option_code,o.option_name FROM ".$this->tbls['order']." AS oi LEFT JOIN ".$this->tbls['order_product']." AS op ON(oi.order_num=op.order_num) LEFT JOIN tblproduct_option o ON (op.option_code::integer = o.option_num) WHERE oi.member_id='".MEMID."' AND op.order_status ='6' AND op.cs_type IN('0','E')  AND op.cs_status ='0' AND op.option_type ='option' AND op.productcode='{$productcode}' {$where_order}");
		//pre("SELECT oi.order_num, op.idx, op.productcode,op.option_code,o.option_name FROM ".$this->tbls['order']." AS oi LEFT JOIN ".$this->tbls['order_product']." AS op ON(oi.order_num=op.order_num) LEFT JOIN tblproduct_option o ON (op.option_code::integer = o.option_num) WHERE oi.member_id='".MEMID."' AND op.order_status ='6' AND op.cs_type ='0'  AND op.cs_status ='0' AND op.option_type ='option' AND productcode='{$productcode}' {$where}");
		$review_valid = array();
		if(is_array($review_log)) {
			$order_log_idx = array_column($review_log,'productorder_idx');
		}
		else $order_log_idx = '';
		
		if(is_array($order_log)) {
			foreach($order_log as $row) {
				if(in_array($row['idx'],$order_log_idx)) continue;
				//if($review_valid) continue;
				$review_valid []= $row;
			}
		}

		if(is_array($review_valid)){
			 foreach ($review_valid AS $k=>$v){
				if(!isset($temp[$v['option_code']])){
					$temp[$v['option_code']] = $v;
				}
			}
		}

		if(!count($order_log)){
			return 3; //작성권한없음(구매이력없음)
		}
		else if(!count($review_valid) && $order_log_idx !=''){
			return 4; //이미 작성내역이 있는경우
		}else{
			return $temp; //권한있음
		}
	}

	/**
	 * 리뷰카운트수 동기화
	 * @TODO
	 *
	 * @param [type] $productcode
	 * @return void
	 */
	public function syncCount($productcode) {
		//$sql = "UPDATE tblproduct SET review_cnt = review_cnt + 1 WHERE productcode ='".$productcode."'";
	}


	/**
	 * 주문상품번호
	 * 차후 order class로 옮기는것이 좋겠음
	 * @param  [type] $ordercode   [description]
	 * @param  [type] $productcode [description]
	 * @return [type]			  [description]
	 */
	public function getOrderProduct($ordercode, $productcode) {
		$sql = "SELECT op.*, p.tinyimage FROM ".$this->tbls['order_product']." AS op LEFT JOIN ".$this->tbls['product']." AS p ON(p.productcode=op.productcode) WHERE ordercode='{$ordercode}' AND op.productcode='{$productcode}'";
		$row = $this->adodb->getRow($sql);
		return $row;
	}
    /**
     * 타입별 리뷰 리스트
     * @param  [type] $type : 포토,일반, $limit 5개 , $offset,$search :상품명 ,$sort : 정렬 최신순,별점
	 * @return [type]			  [description]
     */
    public function getTypeReview($limit, $offset, $search, $sort, $type='') {
    	//$tmp = ($type=="photo")?"1":"0";  // 타입 상관없이 노출로 변경 요청
		//$tmp = ($type=="photo")?"1":"0";
		$WHERE ="";
		if(!empty($type)){
			$WHERE .= "AND a.best_type = 0 ";
		}

		if(!empty($search)){
            $WHERE .= "AND p.productname LIKE '%{$search}%' ";
		}

        $cnt = $this->adodb->getOne("SELECT COUNT(*) FROM  tblproductreview a LEFT JOIN tblproduct p ON a.productcode = p.productcode WHERE p.pr_type !=3 {$WHERE}");//전체개수

        $sql = "SELECT a.productcode,a.id, a.marks,a.date,a.content,a.subject,a.upfile,a.upfile2,a.type, p.maximage,p.minimage,p.tinyimage,p.productname,a.num,a.best_type,p.phrase_ad FROM tblproductreview a LEFT JOIN tblproduct p ON a.productcode = p.productcode WHERE p.pr_type !=3  {$WHERE} {$sort} LIMIT {$limit} OFFSET {$offset}";
        //echo $sql;
		$rs = $this->adodb->Execute($sql);
       	$no =  $cnt-$offset;

        while ($row = $rs->FetchRow()) {
            $row['no'] = $no;
			if(!empty($row['upfile'])){
				$row['image'][]= $row['upfile'];
			}
			if(!empty($row['upfile2'])){
				$row['image'][]= $row['upfile2'];
			}
            $list[]= $row;
            $no--;
        }
        $total = $cnt;
        //print_r($list);exit;
        return array(
        	'list'=>$list,
			'total'=>$total,
		);
    }
    public function getReviewDetail($idx) {
		$tbl_color = $this->tbls['product_color'];
		$sql = "SELECT a.productcode,
						a.id, 
						a.marks,
						a.date,
						a.content,
						a.subject,
						a.upfile,
						a.type,
						a.num,
						a.upfile2, 
						p.maximage,
						p.minimage,
						p.tinyimage,
						p.productname,
						p.consumerprice,
						p.phrase_ad,
						o.option_num,
						o.option_name, 
						o.option_color
				FROM tblproductreview a 
				LEFT JOIN tblproduct p ON a.productcode = p.productcode
				LEFT JOIN tblproduct_option o ON a.option_code::integer = o.option_num
				WHERE a.num = '{$idx}'";
        //echo $sql;
        $rs = $this->adodb->Execute($sql);

        while ($row = $rs->FetchRow()) {
            if(!empty($row['upfile'])){
                $row['image'][]= $row['upfile'];
            }
            if(!empty($row['upfile2'])){
                $row['image'][]= $row['upfile2'];
            }
            if($row['option_num'] && $row['option_color']){
            	$row['option_info'] = $this->adodb->getRow("SELECT * FROM {$tbl_color} WHERE color_code = '{$row['option_color']}'") ;
			}
			$row['content'] = nl2br($row['content']);
            $list = $row;
		}

        return $list;
    }
    public function productReview($productcode,$type,$limit,$offset) {
        $tmp = ($type=="photo")?"1":"0";
        $cnt = $this->adodb->getOne("SELECT COUNT(*) FROM  tblproductreview a LEFT JOIN tblproduct p ON a.productcode = p.productcode WHERE a.type='{$tmp}' AND p.productcode='{$productcode}'");//전체개수

        $sql = "SELECT a.productcode,a.id, a.marks,a.date,a.content,a.subject,a.upfile,a.upfile2,a.type, p.maximage,p.minimage,p.tinyimage,p.productname,a.num,p.phrase_ad FROM tblproductreview a LEFT JOIN tblproduct p ON a.productcode = p.productcode WHERE a.type='{$tmp}' AND p.productcode='{$productcode}' ORDER BY a.date DESC LIMIT $limit OFFSET $offset";
        //echo $sql;
        $rs = $this->adodb->Execute($sql);

        if (!is_object($rs)) {
            return FALSE;
        }

        while ($row = $rs->FetchRow()) {
            $list[]= $row;
        }
        $total = $cnt;
        //print_r($list);exit;
        return array(
            'list'=>$list,
            'total'=>$total,
        );
    }
	/**
 * 리뷰 동영상관리 리스트
 *
 * @param  [type]   [description]
 * @return [type]	  [description]
 */
	public function getVideoList($limit, $offset) {
		$tbl = $this->tbls['review_banner'];
		$cnt = $this->adodb->getOne("SELECT count(*) FROM {$tbl} WHERE banner_type='T' ");
		$rs = $this->adodb->getArray("SELECT * FROM {$tbl} WHERE banner_type='T' ORDER BY sort ASC LIMIT {$limit} OFFSET {$offset}");
		//pre("SELECT * FROM {$tbl} WHERE banner_type='T' ORDER BY sort ASC LIMIT {$limit} OFFSET {$offset}");
		return array(
			'list'=>$rs,
			'total'=>$cnt
		);
	}
	/**
	 * 리뷰 서브배너 관리 리스트
	 *
	 * @param  [type]   [description]
	 * @return [type]	  [description]
	 */
	public function getbannerList($limit, $offset) {
		$tbl = $this->tbls['review_banner'];
		$cnt = $this->adodb->getOne("SELECT count(*) FROM {$tbl} WHERE banner_type='S' ");
		$rs = $this->adodb->getArray("SELECT * FROM {$tbl} WHERE banner_type='S' ORDER BY sort ASC LIMIT {$limit} OFFSET {$offset}");
		//pre("SELECT * FROM {$tbl} WHERE banner_type='T' ORDER BY sort ASC LIMIT {$limit} OFFSET {$offset}");
		return array(
			'list'=>$rs,
			'total'=>$cnt
		);
	}
	/**
	 * 리뷰 blog 관리 리스트
	 *
	 * @param  [type]   [description]
	 * @return [type]	  [description]
	 */
	public function getblogList($limit, $offset,$where='') {
		$tbl = $this->tbls['review_blog'];
		$cnt = $this->adodb->getOne("SELECT count(*) FROM {$tbl} {$where}");
		$rs = $this->adodb->getArray("SELECT * FROM {$tbl} {$where} ORDER BY sort ASC LIMIT {$limit} OFFSET {$offset}");
		//pre("SELECT * FROM {$tbl} WHERE banner_type='T' ORDER BY sort ASC LIMIT {$limit} OFFSET {$offset}");
		return array(
			'list'=>$rs,
			'total'=>$cnt
		);
	}
}
?>
