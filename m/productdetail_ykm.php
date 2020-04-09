<?php
include_once('./outline/header_m.php');
include_once($Dir."lib/jungbo_code.php"); //정보고시 코드를 가져온다

$imgPath = 'http://'.$_SERVER['HTTP_HOST'].'/data/shopimages/product/';
$link_url   = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

# 시중가 대비 할인가 % 2016-02-02 유동혁
function get_price_percent( $consumerprice, $sellprice ){
	$per = round( ( ( $consumerprice - $sellprice ) / $consumerprice ) * 100 );
	return $per;
}


// 쿠폰의 할인 / 적립 text를 반환
function CouponText( $sale_type ){

    $text_arr = array(
        'text'=>'',
        'won'=>''
    );

    switch( $sale_type ){
        case '1' :
            $text_arr['text'] = '적립';
            $text_arr['won'] = '%';
            break;
        case '2' :
            $text_arr['text'] = '할인';
            $text_arr['won'] = '%';
            break;
        case '3' :
            $text_arr['text'] = '적립';
            $text_arr['won'] = '원';
            break;
        case '4' :
            $text_arr['text'] = '할인';
            $text_arr['won'] = '원';
            break;
        default :
            break;
    } //switch

    return $text_arr;
}

$prod_cate_code = $_REQUEST["code"];
$productcode    = $_REQUEST["productcode"];
$sort           = $_REQUEST["sort"];
$brandcode      = $_REQUEST["brandcode"]+0;
$_cdata         = "";
$_pdata         = "";
$staff_yn       = $_ShopInfo->staff_yn;
if( $staff_yn == '' ) $staff_yn = 'N';

if( strlen($productcode) > 0 ) {
	
	//ERP 상품을 쇼핑몰에 업데이트한다.
	getUpErpProductUpdate($productcode);

    $sql = "
        SELECT
        a.*,b.c_maincate,b.c_category
        FROM tblproductcode a
        ,tblproductlink b
        WHERE a.code_a||a.code_b||a.code_c||a.code_d = b.c_category
        AND c_maincate = 1
        AND group_code = ''
        AND c_productcode = '{$productcode}'
    ";
    //exdebug($sql);
    $result=pmysql_query($sql,get_db_conn());
    while($row=pmysql_fetch_object($result)){
        if($row->c_maincate == 1){
            $mainCate = $row;
        }
        $cateProduct[] = $row;
    }

    if($cateProduct) {
        if($mainCate) $_cdata=$mainCate;
        else $_cdata=$cateProduct[0];
        if(count($cateProduct)==0 || !$cateProduct){
            $group_sql = "
                SELECT
                a.group_code
                FROM tblproductcode a
                ,tblproductlink b
                WHERE a.code_a||a.code_b||a.code_c||a.code_d = b.c_category
                AND group_code != ''
                AND c_productcode = '{$productcode}'
                GROUP BY a.group_code
            ";
            $gruop_res = pmysql_query($group_sql,get_db_conn());
            while($gruop_row = pmysql_fetch_object($gruop_res)){
                if($row->group_code=="ALL" && strlen($_ShopInfo->getMemid())==0) {	//회원만 접근가능
                    Header("Location:/");
                    exit;
                }else if(ord($row->group_code) && $row->group_code!="ALL" && $row->group_code!=$_ShopInfo->getMemgroup()) {	//그룹회원만 접근
                    alert_go('해당 분류의 접근 권한이 없습니다.',-1);
                }
            }
            alert_go('판매가 종료된 상품입니다.',"/");
        }

        //Wishlist 담기
        if($mode=="wishlist") {
            if(strlen($_ShopInfo->getMemid())==0) {	//비회원
                alert_go('로그인을 하셔야 본 서비스를 이용하실 수 있습니다.',$Dir.FrontDir."login.php?chUrl=".getUrl());
            }
            $sql = "SELECT COUNT(*) as totcnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
            $result2=pmysql_query($sql,get_db_conn());
            $row2=pmysql_fetch_object($result2);
            $totcnt=$row2->totcnt;
            pmysql_free_result($result2);
            $maxcnt=20;
            if($totcnt>=$maxcnt) {
                $sql = "SELECT b.productcode ";
                $sql.= "FROM tblwishlist a, view_tblproduct b ";
                $sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
                $sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
                $sql.= "AND b.display='Y' ";
                $sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
                $sql.= "GROUP BY b.productcode ";

                $result2=pmysql_query($sql,get_db_conn());
                $i=0;
                $wishprcode="";
                while($row2=pmysql_fetch_object($result2)) {
                    $wishprcode.="'{$row2->productcode}',";
                    $i++;
                }
                pmysql_free_result($result2);
                $totcnt=$i;
                $wishprcode=substr($wishprcode,0,-1);
                if(ord($wishprcode)) {
                    $sql = "DELETE FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' AND productcode NOT IN ({$wishprcode}) ";
                    pmysql_query($sql,get_db_conn());
                }
            }
            if($totcnt<$maxcnt) {
                $sql = "SELECT COUNT(*) as cnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' AND productcode='{$productcode}' ";
                $result2=pmysql_query($sql,get_db_conn());
                $row2=pmysql_fetch_object($result2);
                $cnt=$row2->cnt;
                pmysql_free_result($result2);
                if($cnt>0) {
                    alert_go('WishList에 이미 등록된 상품입니다.',-1);
                } else {
                    $sql = "INSERT INTO tblwishlist (
                    id			,
                    productcode	,
                    date		) VALUES (
                    '".$_ShopInfo->getMemid()."',
                    '{$productcode}',
                    '".date("YmdHis")."')";
                    pmysql_query($sql,get_db_conn());
                    alert_go('WishList에 해당 상품을 등록하였습니다.',-1);
                }
            } else {
                alert_go("WishList에는 {$maxcnt}개 까지만 등록이 가능합니다.\\n\\nWishList에서 다른 상품을 삭제하신 후 등록하시기 바랍니다.",-1);
            }
        }
    } else {
        alert_go('해당 분류가 존재하지 않습니다.',"/");
    }
    pmysql_free_result($result);

    $sql = "SELECT * ";
    $sql.= "FROM tblproduct ";
    $sql.= "WHERE productcode='{$productcode}' ";
    $sql.= "AND display='Y' ";

    $result=pmysql_query($sql,get_db_conn());

    if($row=pmysql_fetch_object($result)) {
        $_pdata=$row;
        $_pdata->brand += 0;
        $sql = "SELECT * FROM tblproductbrand ";
        $sql.= "WHERE bridx='{$_pdata->brand}' ";
        $bresult=pmysql_query($sql,get_db_conn());
        $brow=pmysql_fetch_object($bresult);
        $_pdata->brandcode = $_pdata->brand;
        $_pdata->brand = $brow->brandname;

        pmysql_free_result($result);

        if($_pdata->assembleuse=="Y") {
            $sql = "SELECT * FROM tblassembleproduct ";
            $sql.= "WHERE productcode='{$productcode}' ";
            $result=pmysql_query($sql,get_db_conn());
            if($row=@pmysql_fetch_object($result)) {
                $_adata=$row;
                pmysql_free_result($result);
                $assemble_list_pridx = str_replace("","",$_adata->assemble_list);

                if(ord($assemble_list_pridx)) {
                    $sql = "SELECT pridx,productcode,productname,sellprice,quantity,tinyimage FROM tblproduct ";
                    $sql.= "WHERE pridx IN ('".str_replace(",","','",trim($assemble_list_pridx,','))."') ";
                    $sql.= "AND assembleuse!='Y' ";
                    $sql.= "AND display='Y' ";
                    $result=pmysql_query($sql,get_db_conn());
                    while($row=@pmysql_fetch_object($result)) {
                        $_acdata[$row->pridx] = $row;
                    }
                    pmysql_free_result($result);
                }
            }
        }
    } else {
        alert_go('해당 상품 정보가 존재하지 않습니다.',-1);
    }

} else {
    alert_go('해당 상품 정보가 존재하지 않습니다.',"/");
}
# 상품상세 뷰 카운트 update 2016-01-26 유동혁
$vcnt_sql = "UPDATE tblproduct SET vcnt = vcnt + 1 WHERE productcode = '".$productcode."'";
pmysql_query( $vcnt_sql, get_db_conn() );


$ref=$_REQUEST["ref"];
if (ord($ref)==0) {
    $ref=strtolower(str_replace("http://","",$_SERVER["HTTP_REFERER"]));
    if(strpos($ref,"/") != false) $ref=substr($ref,0,strpos($ref,"/"));
}

if(ord($ref) && strlen($_ShopInfo->getRefurl())==0) {
    $sql2="SELECT * FROM tblpartner WHERE url LIKE '%{$ref}%' ";
    $result2 = pmysql_query($sql2,get_db_conn());
    if ($row2=pmysql_fetch_object($result2)) {
        pmysql_query("UPDATE tblpartner SET hit_cnt = hit_cnt+1 WHERE url = '{$row2->url}'",get_db_conn());
        $_ShopInfo->setRefurl($row2->id);
        $_ShopInfo->Save();
    }
    pmysql_free_result($result2);
}

$miniq = 1;
if (ord($_pdata->etctype)) {
    $etctemp = explode("",$_pdata->etctype);
    for ($i=0;$i<count($etctemp);$i++) {
        if (strpos($etctemp[$i],"MINIQ=")===0)			$miniq=substr($etctemp[$i],6);
        if (strpos($etctemp[$i],"DELIINFONO=")===0)	$deliinfono=substr($etctemp[$i],11);
    }
}

//입점업체 정보 관련
if($_pdata->vender>0) {
    $sql = "SELECT a.vender, a.id, a.brand_name, a.deli_info, b.prdt_cnt ";
    $sql.= "FROM tblvenderstore a, tblvenderstorecount b ";
    $sql.= "WHERE a.vender='{$_pdata->vender}' AND a.vender=b.vender ";
    $result=pmysql_query($sql,get_db_conn());
    if(!$_vdata=pmysql_fetch_object($result)) {
        $_pdata->vender=0;
    }
    pmysql_free_result($result);
}

//배송/교환/환불정보 노출

$deli_info="";
if($deliinfono!="Y") {	//개별상품별 배송/교환/환불정보 노출일 경우
    $deli_info_data="";
    if( $_pdata->vender > 0 ) {	//입점업체 상품이면 입점업체 배송/교환/환불정보 누출
        $tempvdeli_info = explode( "=", stripslashes( $_vdata->deli_info ) );
        if ( $_vdata->deli_info && $tempvdeli_info[0] == "Y" ) {
            $deli_info_data  = $_vdata->deli_info;
            if( is_file( $Dir.DataDir."shopimages/vender/aboutdeliinfo_{$_vdata->vender}_m.gif" ) ){
                $aboutdeliinfofile = $Dir.DataDir."shopimages/vender/aboutdeliinfo_{$_vdata->vender}_m.gif";
            } else if( is_file( $Dir.DataDir."shopimages/vender/aboutdeliinfo_{$_vdata->vender}.gif" ) ) {
                $aboutdeliinfofile = $Dir.DataDir."shopimages/vender/aboutdeliinfo_{$_vdata->vender}.gif";
            }

        } else {
            $deli_info_data    = $_data->deli_info;
            if( is_file( $Dir.DataDir."shopimages/etc/aboutdeliinfo_m.gif" ) ){
                $aboutdeliinfofile = $Dir.DataDir."shopimages/etc/aboutdeliinfo_m.gif";
            } else if( is_file( $Dir.DataDir."shopimages/etc/aboutdeliinfo.gif" ) ) {
                $aboutdeliinfofile = $Dir.DataDir."shopimages/etc/aboutdeliinfo.gif";
            }
        }
    } else {
        $deli_info_data    = $_data->deli_info;
        if( is_file( $Dir.DataDir."shopimages/etc/aboutdeliinfo_m.gif" ) ){
            $aboutdeliinfofile = $Dir.DataDir."shopimages/etc/aboutdeliinfo_m.gif";
        } else if( is_file( $Dir.DataDir."shopimages/etc/aboutdeliinfo.gif" ) ) {
            $aboutdeliinfofile = $Dir.DataDir."shopimages/etc/aboutdeliinfo.gif";
        }
    }
    if( ord( $deli_info_data ) ) {
        $tempdeli_info = explode( "=", stripslashes( $deli_info_data ) );
        if( $tempdeli_info[0] == "Y" ) {
            if( $tempdeli_info[1] == "TEXT" ) {     //텍스트형
                $allowedTags = "<h1><b><i><a><ul><li><pre><hr><blockquote><u><img><br><font>";

                if( ord( $tempdeli_info[2] ) || ord( $tempdeli_info[3] ) ) {
                    if(ord( $tempdeli_info[2] ) ) { //배송정보 텍스트
                        $deli_info .= " <dl class='delivery_info'><dd>".nl2br(strip_tags($tempdeli_info[2],$allowedTags))."</dd></dl>\n";
                    }
                    if( ord( $tempdeli_info[3] ) ) { //교환/환불정보 텍스트
                        $deli_info .= "  <dl class='delivery_info'><dd>".nl2br(strip_tags($tempdeli_info[3],$allowedTags))."</dd></dl>\n";
                    }
                }
            } else if( $tempdeli_info[1] == "IMAGE" ) { //이미지형
                if( file_exists( $aboutdeliinfofile ) ) {
                    $deli_info = "<img src=\"{$aboutdeliinfofile}\" align=absmiddle border=0>\n";
                }
            } else if( $tempdeli_info[1] == "HTML" ) {  //HTML로 입력
                if( ord( $tempdeli_info[3] ) ) {
                    $deli_info = "{$tempdeli_info[3]}\n";
                } else if( ord( $tempdeli_info[2] ) ) {
                    $deli_info = "{$tempdeli_info[2]}\n";
                }
            }
        }
    }
}

//리뷰관련 환경 설정
$reviewlist=$_data->ETCTYPE["REVIEWLIST"];
$reviewdate=$_data->ETCTYPE["REVIEWDATE"];
if(ord($reviewlist)==0) $reviewlist="N";

//상품QNA 게시판 존재여부 확인 및 설정정보 확인
$prqnaboard=getEtcfield($_data->etcfield,"PRQNA");
if(ord($prqnaboard)) {
	$sql = "SELECT * FROM tblboardadmin WHERE board='{$prqnaboard}' ";
	$result=pmysql_query($sql,get_db_conn());
	$qnasetup=pmysql_fetch_object($result);
	pmysql_free_result($result);
	if($qnasetup->use_hidden=="Y") $qnasetup=null;
}

//상품다중이미지 확인
$multi_img="N";
$sql2 ="SELECT * FROM tblmultiimages WHERE productcode='{$productcode}' ";
$result2=pmysql_query($sql2,get_db_conn());
if($row2=pmysql_fetch_object($result2)) {
	if($_data->multi_distype=="0") {
		$multi_img="I";
	} else if($_data->multi_distype=="1") {
		$multi_img="Y";
		$multi_imgs=array(&$row2->primg01,&$row2->primg02,&$row2->primg03,&$row2->primg04,&$row2->primg05,&$row2->primg06,&$row2->primg07,&$row2->primg08,&$row2->primg09,&$row2->primg10);
		$thumbcnt=0;
		for($j=0;$j<10;$j++) {
			if(ord($multi_imgs[$j])) {
				$thumbcnt++;
			}
		}
		$multi_height=430;
		$thumbtype=1;
		if($thumbcnt>5) {
			$multi_height=490;
			$thumbtype=2;
		}
	}
}
pmysql_free_result($result2);

//멀티 이미지 관련()2013-12-23 멀티 이미지 기능만 추가함. 확대보기 없음.

if($multi_img=="Y") {

	//$imagepath=$Dir.DataDir."shopimages/multi/";
	$imagepath=$Dir.DataDir."shopimages/product/";
	//$dispos=$row->multi_dispos;
	// 멀티이미지 설정
	$changetype=$_data->multi_changetype;
	$bgcolor=$_data->multi_bgcolor;

	$sql = "SELECT * FROM tblmultiimages WHERE productcode='{$productcode}' ";
	$result=pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)) {
		$multi_imgs = array(
			&$row->primg01,
			&$row->primg02,
			&$row->primg03,
			&$row->primg04,
			&$row->primg05,
			&$row->primg06,
			&$row->primg07,
			&$row->primg08,
			&$row->primg09,
			&$row->primg10
		);

		$tmpsize=explode("",$row->size);
		$insize="";
		$updategbn="N";

		$y=0;
		for($i=0;$i<10;$i++) {
			if(ord($multi_imgs[$i])) {
				$yesimage[$y]=$multi_imgs[$i];
				if(ord($tmpsize[$i])==0) {
                    if ( strpos("http://", $multi_imgs[$i]) === false ) {
                        $size=getimagesize($Dir.DataDir."shopimages/multi/".$multi_imgs[$i]);
                    }
					$xsize[$y]=$size[0];
					$ysize[$y]=$size[1];
					$insize.="{$size[0]}X".$size[1];
					$updategbn="Y";
				} else {
					$insize.="".$tmpsize[$i];
					$tmp=explode("X",$tmpsize[$i]);
					$xsize[$y]=$tmp[0];
					$ysize[$y]=$tmp[1];
				}
				$y++;
			} else {
				$insize.="";
			}
		}

		$makesize=$maxsize;
		for($i=0;$i<$y;$i++){
			if($xsize[$i]>$makesize || $ysize[$i]>$makesize) {
				if($xsize[$i]>=$ysize[$i]) {
					$tempxsize=$makesize;
					$tempysize=($ysize[$i]*$makesize)/$xsize[$i];
				} else {
					$tempxsize=($xsize[$i]*$makesize)/$ysize[$i];
					$tempysize=$makesize;
				}
				$xsize[$i]=$tempxsize;
				$ysize[$i]=$tempysize;
			}
		}
		if($updategbn=="Y"){
			$sql = "UPDATE tblmultiimages SET size='".ltrim($insize,'')."' ";
			$sql.= "WHERE productcode='{$productcode}'";
			pmysql_query($sql,get_db_conn());
		}

		pmysql_free_result($result);
	}
}


# 상품 이미지 path
$imagepath_product = $Dir.DataDir.'shopimages/product/';
$imagepath_multi = $Dir.DataDir.'shopimages/product/';
if(strpos($_pdata->maximage, "http://") === false) {
    $width= GetImageSize( $imagepath_product.$_pdata->maximage );
}

# 해당 유저에 맞는 상품 메뉴를 가져옴
$cateSql = "
	SELECT code_a||code_b||code_c||code_d AS prcode, code_name
	FROM tblproductcode
	WHERE type = 'LMX'
	AND code_a = '".substr($_cdata->c_category, 0, 3)."'
	AND code_b = '".substr($_cdata->c_category, 3, 3)."'
	ORDER BY cate_sort ASC
";
$cateRes = pmysql_query( $cateSql, get_db_conn() );
while( $cateRow = pmysql_fetch_array( $cateRes ) ){
	$cateLoc[] = $cateRow;
}
pmysql_free_result( $cateRes );
//$thisCate = getCodeLoc3( $_cdata->c_category );
//$thisCate = getDecoCodeLoc( $_pdata->productcode );
$optionNames = explode( '@#', $_pdata->option1 );
$option_depth = count( $optionNames );

$addOptionNames = explode( '@#', $_pdata->option2 );
$addOption_tf = explode( '@#', $_pdata->option2_tf );
$addOption_maxlen = explode( '@#', $_pdata->option2_maxlen );

#연관상품
$r_sql =  " select productcode from tblproduct_related where productcode='{$productcode}' ";
list($chk_rproduct) = pmysql_fetch($r_sql);
if($chk_rproduct){//수동 등록된 연관상품을 노출시킵니다. 수동노출 연관상품의 개수가 10개가 안되면, 자동 노출상품을 더해서 10개를 출력합니다 ㅠㅠ

	//$related_sql = "WITH related AS ( SELECT c_productcode  FROM tblproductlink  WHERE c_category like '". substr($_cdata->c_category, 0, 9) ."%' ";
	//$related_sql.= " AND c_maincate = 1 GROUP BY c_productcode ) ";
	$related_sql.= "SELECT pr.productcode, pr.productname, pr.sellprice, ";
	$related_sql.= "pr.consumerprice, pr.buyprice, pr.brand, pr.maximage, ";
	$related_sql.= "pr.minimage, pr.tinyimage, pr.mdcomment, pr.review_cnt, ";
	$related_sql.= "pr.icon, pr.soldout, pr.quantity, pr.over_minimage FROM tblproduct pr ";
	$related_sql.= "LEFT JOIN (select r_productcode,sort from tblproduct_related where productcode='{$productcode}' ) r ON pr.productcode = r.r_productcode ";
	$related_sql.= "LEFT JOIN (SELECT c_productcode  FROM tblproductlink  WHERE c_category like '". substr($_cdata->c_category, 0, 9) ."%' AND c_maincate = 1 GROUP BY c_productcode ) r2 ON pr.productcode = r2.c_productcode ";
	$related_sql.= "WHERE pr.productcode <> '{$productcode}' "; // 현재 자신은 제외
	$related_sql.= "AND pr.display = 'Y' ";

	// ================================================================
	// 승인대기중인 브랜드에 속한 상품은 리스트에서 제외처리
	// ================================================================
	$sub_sql = "SELECT b.bridx FROM tblvenderinfo a JOIN tblproductbrand b ON a.vender = b.vender WHERE a.delflag='N' AND a.disabled='1' ";
	$sub_result = pmysql_query($sub_sql);

	$arrNotAllowedBrandList = array();
	while ( $sub_row = pmysql_fetch_object($sub_result) ) {
		array_push($arrNotAllowedBrandList, $sub_row->bridx);
	}
	pmysql_free_result($sub_result);

	if ( count($arrNotAllowedBrandList) >= 1 ) {
		$related_sql .= "AND pr.brand not in ( " . implode(",", $arrNotAllowedBrandList) . " ) ";
	}
	$related_sql .= " order by r.sort asc limit 6 ";
	//exdebug($related_sql);
}else{
	//상품의 조회순 , 등록날짜로 6개
	/*
	$related_sql = "WITH related AS ( SELECT c_productcode  FROM tblproductlink  WHERE c_category like '". substr($_cdata->c_category, 0, 9) ."%' ";
	$related_sql.= " AND c_maincate = 1 GROUP BY c_productcode ) ";
	$related_sql.= "SELECT pr.productcode, pr.productname, pr.sellprice, ";
	$related_sql.= "pr.consumerprice, pr.buyprice, pr.brand, pr.maximage, ";
	$related_sql.= "pr.minimage, pr.tinyimage, pr.mdcomment, pr.review_cnt, ";
	$related_sql.= "pr.icon, pr.soldout, pr.quantity, pr.over_minimage FROM tblproduct pr ";
	//$related_sql.= "JOIN related r ON pr.productcode = r.c_productcode ";
	$related_sql.= "WHERE pr.productcode <> '{$productcode}' "; // 현재 자신은 제외
	$related_sql.= "AND pr.display = 'Y' ";
	*/

	// ================================================================
	// 수정날짜 : 2016-08-12
	// 수정자 : daeyeob(김대엽)
	// 수정내용 : 추가 된 관련상품 Tag(relation_tag) 필드에서 관련상품을 가져온다.
	// ================================================================

	$prod_sql = "SELECT p.productcode, p.relation_tag FROM tblproduct p
						WHERE p.productcode = '{$productcode}'";
	$result = pmysql_query($prod_sql);
	$row = pmysql_fetch_object($result);
	$relation_tag = $row->relation_tag;
	$arr_relation = explode(",", $relation_tag);

	foreach( $arr_relation as $key=> $val ){
		if($key == 0){
			$or =  "AND (pr.keyword like '%".$val."%'" ;
		}else{
			$or .=  " OR pr.keyword like '%".$val."%'" ;
		}
	}
	$or .= ")";

	$related_sql = "WITH related AS ( SELECT c_productcode  FROM tblproductlink  WHERE c_category like '". substr($_cdata->c_category, 0, 9) ."%' ";
	$related_sql.= " AND c_maincate = 1 GROUP BY c_productcode ) ";
	$related_sql.= "SELECT pr.productcode, pr.productname, pr.sellprice, ";
	$related_sql.= "pr.consumerprice, pr.buyprice, pr.brand, pr.maximage, ";
	$related_sql.= "pr.minimage, pr.tinyimage, pr.mdcomment, pr.review_cnt, ";
	$related_sql.= "pr.icon, pr.soldout, pr.quantity, pr.over_minimage, pr.relation_tag FROM tblproduct pr ";
//	 $related_sql.= "JOIN related r ON pr.productcode = r.c_productcode ";
	$related_sql.= "WHERE pr.productcode <> '{$productcode}' "; // 현재 자신은 제외
	$related_sql.= "AND pr.display = 'Y' ";

	// ================================================================
	// 승인대기중인 브랜드에 속한 상품은 리스트에서 제외처리
	// ================================================================
	$sub_sql = "SELECT b.bridx FROM tblvenderinfo a JOIN tblproductbrand b ON a.vender = b.vender WHERE a.delflag='N' AND a.disabled='1' ";
	$sub_result = pmysql_query($sub_sql);

	$arrNotAllowedBrandList = array();
	while ( $sub_row = pmysql_fetch_object($sub_result) ) {
		array_push($arrNotAllowedBrandList, $sub_row->bridx);
	}
	pmysql_free_result($sub_result);

	if ( count($arrNotAllowedBrandList) >= 1 ) {
		$related_sql .= "AND pr.brand not in ( " . implode(",", $arrNotAllowedBrandList) . " ) ";
	}
	$related_sql .= $or;

	$related_sql.= " OFFSET (random()) LIMIT 4 ";
}
$related_html = productlist_print( $related_sql, 'W_016' );

#상품정보고시
// 2016 01 13 유동혁
$jungbo_option = explode( '||', $_pdata->sabangnet_prop_option );
$jungbo_val = explode( '||', $_pdata->sabangnet_prop_val );
//$jungbo_cnt = strlen( str_replace( '||', '',$_pdata->sabangnet_prop_val ) );
//정보고시 내용 없으면 노출안되도록 (앞에 3자리 코드 자르고 || 구분자로 배열변경) 2016-03-07
$jungbo_arr = explode("||",substr($_pdata->sabangnet_prop_val,'3'));
$jungbo_cnt=0;
//정보고시 내용이 빈값인지 체크 2016-03-07
foreach($jungbo_arr as $jk){
	if($jk) $jungbo_cnt++;
}
$jungbo_title = $jungbo_code[$jungbo_option[0]]['title'];

#상품의 메인 브랜드 정보
$brand_sql   = "SELECT bridx FROM tblbrandproduct WHERE productcode = '".$_pdata->productcode."' ORDER BY sort ASC LIMIT 1";
list($brand_code) = pmysql_fetch($brand_sql);

$brand_name = "";
if ( !empty($brand_code) ) {
    $brand_sql = " SELECT bridx, brandname, vender FROM tblproductbrand WHERE bridx = ";
    $brand_sql.= "( SELECT bridx FROM tblbrandproduct WHERE productcode = '".$_pdata->productcode."' ORDER BY sort ASC LIMIT 1 )";
    $brand_res = pmysql_query( $brand_sql, get_db_conn() );
    $brand_row = pmysql_fetch_object( $brand_res );
    $brand_code = $brand_row->bridx;
    //$brand_vender = $brand_row->vender;
    $brand_name = $brand_row->brandname;
    pmysql_free_result( $brand_res );
}

// exdebug($brand_sql);

// ======================================================================================
// 브랜드 정보 조회
// ======================================================================================

$brand_desc = "";
if ( !empty($brand_code) ) {
    $sql  = "SELECT a.*, b.brandname ";
    $sql .= "FROM tblvenderinfo_add a LEFT JOIN tblproductbrand b ON a.vender = b.vender ";
    $sql .= "WHERE a.vender = '".$_pdata->vender."' ";
    $row  = pmysql_fetch_object(pmysql_query($sql));

    $brand_desc = $row->description;
}

// 롤링할 이미지
$arrRollingBannerImg = array();
for ( $i = 1; $i <= 10; $i++ ) {
    $varName = "b_img" . $i;

    if ( !empty($row->$varName) ) {
        array_push($arrRollingBannerImg, $row->$varName);
    }
}

// ======================================================================================
// 찜한 리스트(로그인한 상태인 경우)
// ======================================================================================
$arrBrandWishList = array();
$onBrandWishClass = "";
if (strlen($_ShopInfo->getMemid()) > 0) {
    $sql  = "SELECT a.bridx, b.brandname ";
    $sql .= "FROM tblbrandwishlist a LEFT JOIN tblproductbrand b ON a.bridx = b.bridx ";
    $sql .= "WHERE id = '" . $_ShopInfo->getMemid() . "' ";
    $sql .= "ORDER BY wish_idx desc ";

    $result = pmysql_query($sql);
    while ($row = pmysql_fetch_array($result)) {
        $arrBrandWishList[$row['bridx']] = $row['brandname'];

        // 내가 찜한 브랜드인 경우
        if ( $row['bridx'] == $bridx ) {
            $onBrandWishClass = "on";
        }
    }
}

// ======================================================================================
// 관련 프로모션 정보
// ======================================================================================

// 기획전 중에서 현재 진행중인것들을 조회
$sql  = "SELECT a.special_list, c.idx, c.title ";
$sql .= "FROM tblspecialpromo a ";
$sql .= "   LEFT JOIN tblpromotion b ON a.special::integer = b.seq ";
$sql .= "   LEFT JOIN tblpromo c ON b.promo_idx = c.idx ";
$sql .= "WHERE c.display_type in ('A', 'P') and current_date <= c.end_date ";
$sql .= "ORDER BY c.rdate desc ";

$result = pmysql_query($sql);

$bLoopBreak = false;
$limitCount = 2;
$arrPromotionIdx = array();
$arrPromotionTitle = array();
while ($row = pmysql_fetch_array($result)) {
    $special_list   = str_replace(",", "','", $row['special_list']);
    $promo_idx      = $row['idx'];
    $promo_title    = $row['title'];

    // 해당 브랜드에 속한 상품 리스트 조회

    if ( !empty($brand_code) ) {
        $sub_sql  = "SELECT count(*) ";
        $sub_sql .= "FROM tblbrandproduct ";
        $sub_sql .= "WHERE bridx = {$brand_code} AND productcode in ( '{$special_list}' ) ";
        $sub_sql .= "LIMIT 1 ";

        $sub_row  = pmysql_fetch_object(pmysql_query($sub_sql));

        if ( $sub_row->count >= 1 ) {
            if ( !in_array($promo_idx, $arrPromotionIdx) ) {
                array_push($arrPromotionIdx, $promo_idx);
                array_push($arrPromotionTitle, $promo_title);
            }

            if (count($arrPromotionIdx) >= $limitCount) { break; }
        }
    }
}



#위시리스트 정보
$wish_row->cnt = 0;
if ( strlen( $_ShopInfo->getMemid() ) > 0 ) {
    $wish_sql = "SELECT COUNT(*) AS cnt FROM tblwishlist WHERE productcode = '".$_pdata->productcode."' AND id = '".$_ShopInfo->getMemid()."'";
    $wish_res = pmysql_query( $wish_sql, get_db_conn() );
    $wish_row = pmysql_fetch_object( $wish_res );
    pmysql_free_result( $wish_res );
}
//if( $wish_row->cnt > 0 ) $wishlist_class = 'on';
//else $wishlist_class = '';

# 최근 상품 프로모션
$promo_sql =" SELECT pm.idx, pm.title, pm.rdate, pmt.title AS subtitle FROM tblpromo pm ";
$promo_sql.=" JOIN tblpromotion pmt ON pmt.promo_idx = pm.idx ";
$promo_sql.=" JOIN tblspecialpromo sp ON sp.special::int = pmt.seq ";
$promo_sql.=" WHERE sp.special_list LIKE '%".$_pdata->productcode."%' ";
$promo_sql.=" ORDER BY pm.rdate DESC LIMIT 2";
$promo_res = pmysql_query( $promo_sql, get_db_conn() );
$promo_link = array();

$promo_target	 = "";
if($popup == "ok") $promo_target	 = " target='_parent'";

while( $promo_row = pmysql_fetch_object( $promo_res ) ) {
	$promo_link[] = "<a href='<?=$Dir.MDir?>promotion_detail.php?idx=".$promo_row->idx."'".$promo_target.">&gt; ".$promo_row->title."</a>";
}
pmysql_free_result( $promo_res );

#리뷰 베너
$review_banner = get_banner( 94 );

if( get_session( "ACCESS" ) == 'app' ) $couponType = 'T';
else $couponType = 'M';

#사용 가능한 쿠폰 정보
$dpc = DownPossibleCoupon( $_pdata->productcode );

if ($dpc) {
	foreach($dpc as $dpcKey => $dpcVal) {
		if ($dpcVal->sale_type == 2) { // % 할인
			$coupon_use['per']	= $dpcVal->sale_money;
			$coupon_use['price']	= round( ( (100 - $dpcVal->sale_money) / 100 ) * $_pdata->sellprice );
		} else if ($dpcVal->sale_type == 4) { // 금액 할인
			$coupon_use['per']	= round( ( ( $_pdata->sellprice - ($_pdata->sellprice - $dpcVal->sale_money) ) / $_pdata->sellprice ) * 100 );
			$coupon_use['price']	= $_pdata->sellprice - $dpcVal->sale_money;
		}
		$coupon_use['name']		= $dpcVal->coupon_name;
		$coupon_use['code']		= $dpcVal->coupon_code;
		$coupon_use['type']		= $dpcVal->coupon_type;
		$coupon_use['dn']			= $dpcVal->take_dn;
		$coupon_use['btn_yn']	= $dpcVal->detail_auto;
	}
}

//임직원가
$staff_use['per']	= $_pdata->staff_rate;
$staff_use['price']	= round( ( (100 - $_pdata->staff_rate) / 100 ) * $_pdata->sellprice );

//카드혜택 베너
$card_banner = get_banner( '111' );

// 상품 썸네일 옆에 작은 이미지들을 배열에 저장해서 한번에 그려준다.
$arrMiniThumbList = array();
#카카오 이미지
$tmp_kakao_img = '';
if( strpos( $_pdata->maximage, "http://" ) !== false ){
    $tmp_kakao_img = $_pdata->maximage;
} else if( is_file( $imagepath_product.$_pdata->maximage ) ) {
    $tmp_kakao_img = 'http://'.$_SERVER['HTTP_HOST'].'/front/'.$imagepath_product.$_pdata->maximage;
}

# 상품 큰 이미지
if( is_file( $imagepath_product.$_pdata->maximage ) || strpos($_pdata->maximage, "http://") !== false ) {
    $tmp_imgCont = getProductImage($imagepath_product, $_pdata->maximage);
    array_push($arrMiniThumbList, $tmp_imgCont);
}

if ( $multi_img=="Y" && $yesimage[0] ) {

    $arrMultiImg = array(); // 상품 상세 설명이 없는 경우 노출하기 위해 배열에 저장
    foreach( $yesimage as $mImgKey=>$mImgVal ){
        $multiImg = getProductImage($imagepath_multi, $mImgVal);
        array_push($arrMultiImg, $multiImg);

        $tmp_imgCont = $multiImg;
        array_push($arrMiniThumbList, $tmp_imgCont);
    }
}

$thisCate = getDecoCodeLoc( $_pdata->productcode, $prod_cate_code );


// 전체 리뷰갯수 및 별점별 갯수를 가져온다.
$rc_sql = "select productcode,
			marks1 as marks1_cnt,
			marks2 as marks2_cnt,
			marks3 as marks3_cnt,
			marks4 as marks4_cnt,
			marks5 as marks5_cnt,
			marks_total_cnt,
			marks1*1+marks2*2+marks3*3+marks4*4+marks5*5 as marks_sum_cnt,
			TRUNC(5.00 * (marks1*1+marks2*2+marks3*3+marks4*4+marks5*5) / (marks_total_cnt * 5),1) as marks_ever_cnt,
			ROUND(100.00 * marks1 / marks_total_cnt,2) as marks1_per,
			ROUND(100.00 * marks2 / marks_total_cnt,2) as marks2_per,
			ROUND(100.00 * marks3 / marks_total_cnt,2) as marks3_per,
			ROUND(100.00 * marks4 / marks_total_cnt,2) as marks4_per,
			ROUND(100.00 * marks5 / marks_total_cnt,2) as marks5_per
			from (SELECT productcode,
			sum(case when marks=1 then 1 else 0 end) as marks1,
			sum(case when marks=2 then 1 else 0 end) as marks2,
			sum(case when marks=3 then 1 else 0 end) as marks3,
			sum(case when marks=4 then 1 else 0 end) as marks4,
			sum(case when marks=5 then 1 else 0 end) as marks5,
			count(productcode) as marks_total_cnt
			FROM tblproductreview group by productcode) a WHERE productcode='{$productcode}' ";
$rc_result=pmysql_query($rc_sql,get_db_conn());
$rc_row=pmysql_fetch_object($rc_result);
$review_info['marks_total_cnt']	= $rc_row->marks_total_cnt?$rc_row->marks_total_cnt:'0';
$review_info['marks1_cnt']	= $rc_row->marks1_cnt?$rc_row->marks1_cnt:'0';
$review_info['marks2_cnt']	= $rc_row->marks2_cnt?$rc_row->marks2_cnt:'0';
$review_info['marks3_cnt']	= $rc_row->marks3_cnt?$rc_row->marks3_cnt:'0';
$review_info['marks4_cnt']	= $rc_row->marks4_cnt?$rc_row->marks4_cnt:'0';
$review_info['marks5_cnt']	= $rc_row->marks5_cnt?$rc_row->marks5_cnt:'0';
$review_info['marks1_per']	= $rc_row->marks1_per?$rc_row->marks1_per:'0';
$review_info['marks2_per']	= $rc_row->marks2_per?$rc_row->marks2_per:'0';
$review_info['marks3_per']	= $rc_row->marks3_per?$rc_row->marks3_per:'0';
$review_info['marks4_per']	= $rc_row->marks4_per?$rc_row->marks4_per:'0';
$review_info['marks5_per']	= $rc_row->marks5_per?$rc_row->marks5_per:'0';
$review_info['marks_ever_cnt']	= $rc_row->marks_ever_cnt?$rc_row->marks_ever_cnt:'0.0';
$review_info['marks_ever_width']	= $rc_row->marks_ever_cnt?substr($rc_row->marks_ever_cnt,0,1)*20:'0';
pmysql_free_result($rc_result);

#좋아요
$like_sql = "SELECT p.productcode, li.section,
						COALESCE((select COUNT( tl.hott_code )AS hott_cnt from tblhott_like tl WHERE tl.section = 'product' AND p.productcode = tl.hott_code),0) AS hott_cnt
			FROM tblproduct p
			LEFT JOIN ( SELECT hott_code, section FROM tblhott_like WHERE section = 'product' AND like_id = '".$_ShopInfo->getMemid()."' GROUP BY hott_code, section ) li on p.productcode = li.hott_code";
$like_sql .= " WHERE p.productcode = '".$productcode."' AND p.display = 'Y'";
$result = pmysql_query( $like_sql, get_db_conn() );
$like_row = pmysql_fetch_object( $result );
$like_info = $like_row;
?>

<div id="contents">
	<!-- goods #container -->
	<main id="contents">
		<div class="goods-detail-hero">
			<!-- 상세 상단 비쥬얼 -->
			<div class="detail_visual with-btn-rolling">
				<ul class="image_list">
<?php
    for( $i=0; $i < count( $arrMiniThumbList ); $i++ ){
?>
					<li><a href="javascript:;"><img src="<?=$arrMiniThumbList[$i]?>" alt=""></a></li>
<?php
    }
?>
				</ul>
			</div>
			<!-- // 상세 상단 비쥬얼 -->
<?
	if ($_pdata->sex =='M') $_pdata_sex	= "MEN";
	else if ($_pdata->sex =='F') $_pdata_sex	= "WOMEN";
	else if ($_pdata->sex =='U') $_pdata_sex	= "UNISEX";
?>
			<!-- 상품 정보 -->
			<div class="hero-info">
				<div class="inner">
					<h2>
						<p><span class="brand">[ <?=$_pdata->brand?> ]</span><span class="cate">[ <?=$_pdata_sex?> ]</span> <?=getIconHtml( $_pdata->icon, 'W_015' )?></p><!-- [D] 카테고리 추가(2016-09-13) -->
						<p class="name_en"><?=$_pdata->productname?></p>
						<p class="name_kr"><?=$_pdata->productname_kor?></p><!-- [D] 한글 제품명 추가(2016-09-13) -->
					</h2>
					
					<table class="tbl_price"><!-- [D] 가격정보 수정(2016-09-21) -->
						<colgroup>
							<col style="width:16%;">
							<col style="width:84%;">
						</colgroup>
						<tbody>
						<?if( $_pdata->consumerprice > 0 && $_pdata->consumerprice > $_pdata->sellprice ){?>
							<tr>
								<th>판매가</th>
								<td><del><?=number_format( $_pdata->consumerprice )?></del></td>
							</tr>
							<tr>
								<th>혜택가</th>
								<td>
									<strong class="big"><?=number_format( $_pdata->sellprice )?></strong>
									<span class="tag_sale"><?=get_price_percent( $_pdata->consumerprice, $_pdata->sellprice )?><span class="sm">%</span></span>
								</td>
							</tr>
						<?} else {?>
							<tr>
								<th>혜택가</th>
								<td>
									<strong class="big"><?=number_format( $_pdata->sellprice )?></strong>
								</td>
							</tr>
						<?}?>
						<?if ($coupon_use['code'] !='' && $coupon_use['btn_yn']=='Y') {?>
							<tr>
								<th>쿠폰가</th>
								<td>
									<strong class="point-color"><?=number_format($coupon_use['price']) ?></strong>
									<button type="button" class="btn_coupon CLS_coupon_download" data-coupon='<?=encrypt_md5("COUPON|".$coupon_use['type']."|".$coupon_use['code'],"*ghkddnjsrl*")?>'>쿠폰</button>
									<div class="help_area">
										<button type="button" class="btn_help">?</button>
										<div class="txt_bubble">
											<?=$coupon_use['name']?>
											<button type="button" class="btn_close">닫기</button>
										</div>
									</div>
								</td>
							</tr>
						<?}?>
						<?if($_ShopInfo->getStaffYn() == 'Y' && $_pdata->hotdealyn=='N'){?>
							<tr>
								<th>임직원가</th>
								<td><strong class="point-color"><?=number_format($staff_use['price']) ?></strong></td>
							</tr>
						<?}?>
						</tbody>
					</table>

					<?
					$colorProdHtml = getColorProduct($_pdata->productcode, $_pdata->prodcode, "detail" );
					$colorProdText = getColorProductText($_pdata->productcode, $_pdata->prodcode );
					?>
					<div class="hero-info-color<?=!$colorProdHtml?' hide':''?>"><!-- [D] 썸네일이 없을 경우 hide(2016-09-13) -->
						<!-- <p><?=$colorProdText ?></p> --><!-- [D] 삭제(2016-09-13) -->
						<ul class="clear"><!-- [D] .clear 클래스 추가, 슬라이드 스크립트 수정(2016-09-13) -->
						<?=$colorProdHtml?>
						</ul>
					</div>
					<div class="hero-info-community">
						<a class="btn-star" href="javascript:;"><span class="comp-star star-score"><strong style="width:<?=$review_info['marks_ever_width']?>%;">5점만점에 <?=$review_info['marks_ever_cnt']?>점</strong></span>(<?=number_format($review_info['marks_total_cnt'])?>)</a>
						<a class="btn-posting" href="javascript:;"><strong>관련 포스팅</strong>(0)</a>
						<button class="comp-like btn-like like_p<?=$like_info->productcode?> <?=$like_info->section?' on':''?>" onclick="detailSaveLike('<?=$like_info->productcode?>','<?=$like_info->section?'on':'off'?>','product','<?=$_ShopInfo->getMemid()?>','<?=$_pdata->brand?>')" id="like_<?=$like_info->productcode ?>" title="<?=$like_info->section?'선택됨':'선택 안됨'?>"><span class="like_count_<?=$like_info->productcode ?>"><strong>좋아요</strong><?=number_format($like_info->hott_cnt) ?></span></button>
						<a class="btn_share btn-sns_share" href="javascript:;"><img src="./static/img/btn/btn_sns_share.png" alt="sns공유하기"></a>
					</div>
					<div class="hero-info-tag">
						<!-- (D) 선택된 li에 class="on" title="선택됨"을 추가합니다. -->
						<ul>
							<?foreach($arrTag as $key=>$val ){?>
							<li><a href="javascript:;"><?=$val ?></a></li>
							<?} ?>
						</ul>
					</div>
				</div>
			</div>
			<!-- // 상품 정보 -->
			
			<div class="offline_inventory"><a href="<?=$Dir.MDir?>offline_inventory.php?productcode=<?=$_pdata->productcode?>" class="btn-line">오프라인 매장 재고 조회</a></div><!-- [D] 버튼추가(2016-09-21) -->

<?php
if( $related_html ) {
?>
			<!-- 관련 상품 -->
			<section class="goods-detail-related">
				<h3>관련 상품<span class="plus"></span></h3>
				<div class="related_product">
<?php
					foreach( $related_html as $key=>$related ){
						echo $related;
					} // related foreach
?>
				</div>
			</section>
			<!-- // 관련 상품 -->
<?
}
?>
			<!-- 상품 정보 및 게시판 -->
			<article class="product_detail_wrap">
				<div class="inner">
					<div class="sorting">
						<!-- 상세정보 -->
						<section class="product_info">
							<h3>상세정보</h3>
							<div class="content_area">
<?php
    // ================================================================================
    // PRODUCT INFO // 모바일용으로 변경해야함
    // ================================================================================
    if( strlen( trim( preg_replace( array("/<br\/>/", "/<br>/"), "", $_pdata->content_m ) ) ) > 0 ) {
        $_pdata_content = stripslashes($_pdata->content_m);
    } else {
        $_pdata_content = stripslashes($_pdata->content);
    }

    // 상품상세의 내용중 이미지의 스타일일 제거한다. (2016-03-31 김재수 추가)-------------------------------
	preg_match_all("/<IMG[^>]*style=[\"']?([^>\"']+)[\"']?[^>]*>/i",$_pdata_content,$_pdata_content_img);
	if ($_pdata_content_img) {
		foreach($_pdata_content_img[0] as $con_img_arr => $con_img) {
			$tem_con_img=$con_img;
			$tem_con_img=preg_replace("/ zzstyle=([^\"\']+) /"," ",$tem_con_img);
			$tem_con_img=preg_replace("/ style=(\"|\')?([^\"\']+)(\"|\')?/","",$tem_con_img);
			$_pdata_content = str_replace($con_img, $tem_con_img, $_pdata_content);
		}
	}
	// ---------------------------------------------------------------------------------------------------
	if( strlen($detail_filter) > 0 ) {
		$_pdata_content = preg_replace($filterpattern,$filterreplace,$_pdata_content);
	}

    // <br>태그 제거
    $arrList = array("/<br\/>/", "/<br>/");
	$_pdata_content_tmp = trim(preg_replace($arrList, "", $_pdata_content));

    if ( empty($_pdata_content_tmp) ) {
        echo "<ul class=\"detail-thumb\">";
        foreach ( $arrMultiImg as $key => $val ) {
            echo "<li><img src=\"{$val}\" alt=\"\"></li>";
        }
        echo "</ul>";
    } else {
        if ( strpos($_pdata_content,"table>")!=false || strpos($_pdata_content,"TABLE>")!=false)
            echo "<pre>".$_pdata_content."</pre>";
        else if(strpos($_pdata_content,"</")!=false)
            echo nl2br($_pdata_content);
        else if(strpos($_pdata_content,"img")!=false || strpos($_pdata_content,"IMG")!=false)
            echo nl2br($_pdata_content);
        else
            echo str_replace(" ","&nbsp;",nl2br($_pdata_content));
    }
?>
							</div>
							<a class="btn-toggle" href="javascript:void(0);" title="펼쳐보기"><span>SIZE 정렬</span></a>
						</section>
						<!-- // 상세정보 -->
<?php
    // ================================================================================
    // SIZE INFO // 모바일용으로 변경해야함
    // ================================================================================
    if( strlen( trim( preg_replace( array("/<br\/>/", "/<br>/"), "", $_pdata->pr_sizecon_m ) ) ) > 0 ) {
        $_pdata_sizecon = stripslashes($_pdata->pr_sizecon_m);
    } else if( strlen( trim( preg_replace( array("/<br\/>/", "/<br>/"), "", $_pdata->pr_sizecon ) ) ) > 0 ) {
        $_pdata_sizecon = stripslashes($_pdata->pr_sizecon);
    } else {
		$_pdata_sizecon = "";
	}
?>
						<!-- 사이즈 정보 -->
						<section class="product_size<?=$_pdata_sizecon==''?' hide':''?>">
							<h3>사이즈 정보</h3>
							<div class="content_area">
<?
    // 상품상세의 내용중 이미지의 스타일일 제거한다. (2016-03-31 김재수 추가)-------------------------------
	preg_match_all("/<IMG[^>]*style=[\"']?([^>\"']+)[\"']?[^>]*>/i",$_pdata_sizecon,$_pdata_sizecon_img);
	if ($_pdata_sizecon_img) {
		foreach($_pdata_sizecon_img[0] as $con_img_arr => $con_img) {
			$tem_con_img=$con_img;
			$tem_con_img=preg_replace("/ zzstyle=([^\"\']+) /"," ",$tem_con_img);
			$tem_con_img=preg_replace("/ style=(\"|\')?([^\"\']+)(\"|\')?/","",$tem_con_img);
			$_pdata_sizecon = str_replace($con_img, $tem_con_img, $_pdata_sizecon);
		}
	}
	// ---------------------------------------------------------------------------------------------------
	if( strlen($detail_filter) > 0 ) {
		$_pdata_sizecon = preg_replace($filterpattern,$filterreplace,$_pdata_sizecon);
	}

    // <br>태그 제거
    $arrList_sizecon = array("/<br\/>/", "/<br>/");
	$_pdata_sizecon_tmp = trim(preg_replace($arrList_sizecon, "", $_pdata_sizecon));

    if ( empty($_pdata_sizecon_tmp) ) {
        echo "<ul class=\"detail-thumb\">";
        foreach ( $arrMultiImg as $key => $val ) {
            echo "<li><img src=\"{$val}\" alt=\"\"></li>";
        }
        echo "</ul>";
    } else {
        if ( strpos($_pdata_sizecon,"table>")!=false || strpos($_pdata_sizecon,"TABLE>")!=false)
            echo "<pre>".$_pdata_sizecon."</pre>";
        else if(strpos($_pdata_sizecon,"</")!=false)
            echo nl2br($_pdata_sizecon);
        else if(strpos($_pdata_sizecon,"img")!=false || strpos($_pdata_sizecon,"IMG")!=false)
            echo nl2br($_pdata_sizecon);
        else
            echo str_replace(" ","&nbsp;",nl2br($_pdata_sizecon));
    }
?>
							</div>
							<a class="btn-toggle" href="javascript:void(0);" title="펼쳐보기"><span>SIZE 정렬</span></a>
						</section>
						<!-- // 사이즈 정보 -->

<?php
// 리뷰 작성 가능 리스트 조회
$sql  = "SELECT tblResult.ordercode, tblResult.idx ";
$sql .= "FROM ";
$sql .= "   ( ";
$sql .= "       SELECT a.*, b.regdt  ";
$sql .= "       FROM tblorderproduct a LEFT JOIN tblorderinfo b ON a.ordercode = b.ordercode ";
$sql .= "       WHERE a.productcode = '" . $productcode . "' AND b.id = '" . $_ShopInfo->getMemid()  . "' and ( (b.oi_step1 = 3 AND b.oi_step2 = 0) OR (b.oi_step1 = 4 AND b.oi_step2 = 0) ) ";
$sql .= "       ORDER BY a.idx DESC ";
$sql .= "   ) AS tblResult LEFT ";
$sql .= "   OUTER JOIN tblproductreview tpr ON tblResult.productcode = tpr.productcode and tblResult.ordercode = tpr.ordercode and tblResult.idx = tpr.productorder_idx ";
$sql .= "WHERE tpr.productcode is null ";
$sql .= "ORDER BY tblResult.idx asc ";
$sql .= "LIMIT 1 ";

$result = pmysql_query($sql);
list($review_ordercode, $review_order_idx) = pmysql_fetch($sql);
pmysql_free_result($result);

$qry = "WHERE a.productcode='{$productcode}' ";
$sql = "SELECT COUNT(*) as t_count, SUM(a.marks) as totmarks FROM tblproductreview a ";
$sql.= $qry;
$result=pmysql_query($sql,get_db_conn());
$row=pmysql_fetch_object($result);
$t_count_review = (int)$row->t_count;
$totmarks = (int)$row->totmarks;
$marks=@ceil($totmarks/$t_count_review);
pmysql_free_result($result);
$paging = new New_Templet_mobile_paging($t_count_review,10,4,'GoPageAjax');
$gotopage = $paging->gotopage;

# 리뷰 리스트를 불러온다
//$reviewlist = 'Y';
$sql  = "SELECT a.*, b.productname FROM tblproductreview a LEFT JOIN tblproduct b ON a.productcode = b.productcode ";
$sql .= "{$qry} ORDER BY a.date DESC, a.num DESC ";

$sql = $paging->getSql($sql);
$result=pmysql_query($sql,get_db_conn());
$j=0;
$reviewList = array();
while($row=pmysql_fetch_object($result)) {

	$reviewComment = array();

	$reviewList[$j]['idx'] = $row->num;
	$reviewList[$j]['num'] = $row->num;
	$reviewList[$j]['number'] = ($t_count_review-($setup['list_num'] * ($gotopage-1))-$j);
	$reviewList[$j]['id'] = $row->id;
	$reviewList[$j]['name'] = $row->name;
	$reviewList[$j]['subject'] = $row->subject;
	$reviewList[$j]['productcode'] = $row->productcode;
	$reviewList[$j]['productname'] = $row->productname;
	$reviewList[$j]['ordercode'] = $row->ordercode;
	$reviewList[$j]['productorder_idx'] = $row->productorder_idx;
	$reviewList[$j]['marks'] = $row->marks;
	$reviewList[$j]['hit'] = $row->hit;
	$reviewList[$j]['type'] = $row->type;

    // 별표시하기
    $reviewList[$j]['marks_sp'] = '';
    for ( $i = 0; $i < $row->marks; $i++ ) {
        $reviewList[$j]['marks_sp'] .= '<img src="./static/img/icon/icon_star.png">';
    }

	$reviewList[$j]['best_type'] = $row->best_type;

	$reviewList[$j]['upfile'] = $row->upfile;       // 첨부파일1
	$reviewList[$j]['upfile2'] = $row->upfile2;     // 첨부파일2
	$reviewList[$j]['upfile3'] = $row->upfile3;     // 첨부파일3
	$reviewList[$j]['upfile4'] = $row->upfile4;     // 첨부파일4

	$reviewList[$j]['up_rfile'] = $row->up_rfile;   // 첨부파일1(실제 업로드한 파일명)
	$reviewList[$j]['up_rfile2'] = $row->up_rfile2; // 첨부파일2(실제 업로드한 파일명)
	$reviewList[$j]['up_rfile3'] = $row->up_rfile3; // 첨부파일3(실제 업로드한 파일명)
	$reviewList[$j]['up_rfile4'] = $row->up_rfile4; // 첨부파일4(실제 업로드한 파일명)

	//exdebug($reviewList);
	$reviewList[$j]['date'] = substr($row->date,0,4).".".substr($row->date,4,2).".".substr($row->date,6,2);
	$reviewList[$j]['date'].= '&nbsp;'.substr($row->date,8,2).":".substr($row->date,10,2).":".substr($row->date,12,2);
	$reviewList[$j]['content'] = explode("=",$row->content);

	# 코멘트 가져오기
	$comment_sql  = "SELECT no, id, name, content, regdt, pnum ";
    $comment_sql .= "FROM tblproductreview_comment ";
    $comment_sql .= "WHERE pnum = '".$row->num."' ";
    $comment_sql .= "ORDER BY no desc ";

	$comment_res = pmysql_query( $comment_sql, get_db_conn() );
	while( $comment_row = pmysql_fetch_object( $comment_res ) ){
		$reviewComment[] = $comment_row;
	}
	pmysql_free_result( $comment_res );
	$reviewList[$j]['comment'] = $reviewComment;
	$j++;
}
pmysql_free_result($result);

//1:1문의를 불러온다.
$sql = "SELECT * FROM tblboard WHERE board='qna' AND pridx='".$_pdata->pridx."'  "; //AND is_secret = '0'
if ($qnasetup->use_reply != "Y") $sql.= "AND pos = 0 AND depth = 0 ";
$sql.= "ORDER BY thread,pos";
$qna_paging = new New_Templet_mobile_paging($sql,10,4,'GoPageAjax2');
$qna_t_count = $qna_paging->t_count;
$qna_gotopage = $qna_paging->gotopage;

$sql = $qna_paging->getSql($sql);
$result=pmysql_query($sql,get_db_conn());

$qnaList = array();
while($row=pmysql_fetch_object($result)) {
	$qnaList[] = $row;
}
pmysql_free_result($result);

//exdebug( $_SERVER );

// 최근 본 상품 DB에 저장(hott 는 로긴기반이라 저장하기로 했음.) 2016-08-04 jhjeong
if(strlen($_ShopInfo->getMemid()) > 0) {

    $sql = "Select count(*) as cnt From tblproduct_recent Where productcode = '".$productcode."' and mem_id = '".$_ShopInfo->getMemid()."' ";
    list($cnt_recent) = pmysql_fetch($sql, get_db_conn());

    $current_date = date("YmdHis");
    if($cnt_recent == 0) {
        $sql = "Insert into tblproduct_recent
                (productcode, mem_id, regdt)
                Values
                ('".$productcode."', '".$_ShopInfo->getMemid()."', '".$current_date."')
                ";
        pmysql_query($sql, get_db_conn());
    } else {
        $sql = "Update tblproduct_recent Set regdt = '".$current_date."' Where productcode = '".$productcode."' and mem_id = '".$_ShopInfo->getMemid()."' ";
        pmysql_query($sql, get_db_conn());
    }

    // 30개 넘으면 삭제..
    $rno_arr = Get_Over_Recent_Product($_ShopInfo->getMemid(), 30);
    //exdebug($rno_arr);
    if(count($rno_arr) > 0) {

        $rno_in = array();
        foreach($rno_arr as $k => $v) {
            //exdebug($v->rno);
            $rno_in[] = $v->rno;
        }

        $sql = "Delete from tblproduct_recent Where rno in (".implode($rno_in, ",").") ";
        pmysql_query($sql, get_db_conn());
        //exdebug($sql);
    }
}
?>
<script type="text/javascript">
    var listnum_comment = "<?=$listnum_comment?>";

    function goLogin() {
        <?php $url = $Dir.FrontDir."login.php?chUrl="; ?>
        if ( confirm("로그인이 필요합니다.") ) {
            location.href = "<?=$url?>" + encodeURIComponent('<?=$_SERVER['REQUEST_URI']?>');
        }
    }

    function delete_review_comment(obj) {
        var review_comment_num = $(obj).attr("ids");
        var review_num = $(obj).attr("ids2");

        if ( review_comment_num != "" ) {
            if ( confirm("댓글을 삭제하시겠습니까?") ) {
                $.ajax({
                    type        : "GET",
                    url         : "<?=$Dir.FrontDir?>ajax_delete_review_comment.php",
                    data        : { review_comment_num : review_comment_num, review_num : review_num }
                }).done(function ( result ) {
                    if ( result == "SUCCESS" ) {
                        alert("댓글이 삭제되었습니다.");
                        $(obj).parent().parent().remove();
                        $("#"+review_comment_num).remove();
                    } else {
                        alert("댓글이 삭제가 실패했습니다.");
                    }
                });
            }
        }
    }

	// 리뷰에 댓글달기
	function review_comment_write(obj) {
		var frm = $(obj).parent().parent();            // form
		var obj_comment = $(frm).find("input[name=review_comment]");      // textarea
		var pnum = $(frm).find("input[name=pnum]").val();      // pnum
		var mem_id = $(frm).find("input[name=mem_id]").val();
		var now_date = $(frm).find("input[name=now_date]").val();
		var inElement = frm.parent().parent().find('.admin_answer_list');
		var mem_id = '<?=$_ShopInfo->getMemid()?>';
		var review_comment = $(obj_comment).val().trim();

		if ( review_comment == "" ) {
			alert("댓글을 입력해 주세요.");
			$(obj_comment).val("").focus();
			return false;
		}

		var fd = new FormData($(frm)[0]);

		$.ajax({
			url: "<?=$Dir.FrontDir?>ajax_insert_review_comment.php",
			type: "POST",
			data: fd,
			async: false,
			cache: false,
			contentType: false,
			processData: false,
		}).success(function(data){
				data_arr	= data.split("|");
			if ( data_arr[0] === "SUCCESS" ) {
				alert("댓글이 등록되었습니다.");
				$(obj_comment).val("");
				console.log(inElement);
				if(inElement.length == "0"){
					$("#reply_comment_"+pnum).html( '<div class="admin_answer"><span class="admin_name">'+mem_id+' ('+now_date+')</span><p>'+review_comment+'</p><div class="buttonset"><a class="btn-delete" href="javascript:;" onClick="javascript:delete_review_comment(this);" ids="'+data_arr[1]+'" ids2="'+pnum+'">삭제</a></div></div><div class="btn-feeling mb-10 ml-20" id="'+data_arr[1]+'"><a class="btn-good-feeling" href="javascript:select_feeling(\''+data_arr[1]+'\',\'product_review_comment\',\'good\',\''+mem_id+'\');" id="feeling_good_comment_'+data_arr[1]+'"><?=totalFeeling($commentVal->no, 'product_review_comment', 'good') ?></a><a class="btn-bad-feeling" href="javascript:select_feeling(\''+data_arr[1]+'\',\'product_review_comment\',\'bad\',\''+mem_id+'\');" id="feeling_bad_comment_'+data_arr[1]+'"><?=totalFeeling($commentVal->no, 'product_review_comment', 'bad') ?></a></div>');
				}else{
					inElement.prepend( '<div class="admin_answer"><span class="admin_name">'+mem_id+' ('+now_date+')</span><p>'+review_comment+'</p><div class="buttonset"><a class="btn-delete" href="javascript:;" onClick="javascript:delete_review_comment(this);" ids="'+data_arr[1]+'" ids2="'+pnum+'">삭제</a></div></div><div class="btn-feeling mb-10 ml-20" id="'+data_arr[1]+'"><a class="btn-good-feeling" href="javascript:select_feeling(\''+data_arr[1]+'\',\'product_review_comment\',\'good\',\''+mem_id+'\');" id="feeling_good_comment_'+data_arr[1]+'"><?=totalFeeling($commentVal->no, 'product_review_comment', 'good') ?></a><a class="btn-bad-feeling" href="javascript:select_feeling(\''+data_arr[1]+'\',\'product_review_comment\',\'bad\',\''+mem_id+'\');" id="feeling_bad_comment_'+data_arr[1]+'"><?=totalFeeling($commentVal->no, 'product_review_comment', 'bad') ?></a></div>');
				}		
				
			} else {
				alert("댓글 등록이 실패하였습니다.");
			}
		}).error(function () {
			alert("다시 시도해 주세요.");
		});
	}

	//리뷰 paging ajax
	function GoPageAjax(block,gotopage) {
		gBlock = block;
		gGotopage = gotopage;
		$.ajax({
			type: "GET",
			url: "<?=$Dir.MDir?>ajax_get_review_list.php",
			contentType: "application/x-www-form-urlencoded; charset=UTF-8",
			data: "productcode="+$("input[name='productcode']").val()+"&block="+block+"&gotopage="+gotopage
		}).done(function ( data ) {
			$(".review-list").html(data);
		});
	}

	//리뷰 수정
    function send_review_write_page(
        productcode,
        ordercode,
        productorder_idx,
        review_num) {

        if ( review_num == undefined ) {
            review_num = 0;
        }

        var frm = document.reviewForm;

        frm.productcode.value = productcode;
        frm.ordercode.value = ordercode;
        frm.productorder_idx.value = productorder_idx;
        frm.review_num.value = review_num;
        frm.mode.value = "modify";
		frm.submit();
    }

	// 리뷰삭제
    function delete_review(review_num) {
        if ( confirm("삭제하시겠습니까?") ) {
            $.ajax({
                type        : "GET",
                url         : "<?=$Dir.FrontDir?>ajax_delete_review.php",
                contentType : "application/x-www-form-urlencoded; charset=UTF-8",
                data        : { review_num : review_num }
            }).done(function ( data ) {
                if ( data === "SUCCESS" ) {
                    alert("리뷰가 삭제되었습니다.");
                    location.reload();
                }
            });
        }
    }

	//Q&A 삭제
	function PerDel(obj, num) {
		if( confirm('삭제하시겠습니까?') ){
			var passwd  =  $("input[name=modify_passwd]").val();

			$.ajax({
				type: "POST",
				url: "../board/board.php",
				data: {
					'pagetype' : 'delete',
					'exec' : 'delete',
					'board' : 'qna',
					mode : 'delete_ajax',
					up_passwd : passwd,
					num : num
				}
			}).done( function( data ){
				alert('Q&A가 삭제되었습니다.');
				location.reload();
			});

		} else {
			return;
		}
	}

	//Q&A 페이징
	function GoPageAjax2(block,gotopage) {
		gBlock = block;
		gGotopage = gotopage;
		$.ajax({
			type: "GET",
			url: "<?=$Dir.MDir?>ajax_get_qna_list.php",
			contentType: "application/x-www-form-urlencoded; charset=UTF-8",
			data: "productcode=<?=$productcode?>&pridx=<?=$_pdata->pridx?>&block="+block+"&gotopage="+gotopage
		}).done(function ( data ) {
			$(".qna-list").html(data);
		});
	}

	//Q&A 작성
	function chkLoginWriteLink() {
		var mem_id = '<?=$_ShopInfo->getMemid()?>';

		if ( mem_id === "" ) {
			alert("로그인이 필요합니다.");
			location.href = '/m/login.php?chUrl=<?=urlencode("/m/product_qna_write.php?productcode={$productcode}&pridx={$_pdata->pridx}")?>';
			return false;
		} else {
			location.href = '/m/product_qna_write.php?productcode=<?=$productcode?>&pridx=<?=$_pdata->pridx?>';
			return true;
		}
	}

</script>
						<!-- 상품리뷰 -->
						<section id="pdReview" class="product_review">
							<h3>리뷰 <em>(<?=$t_count_review?>)</em></h3>
							<div class="content_area">
								<div class="review-score">
									<div class="star"><span class="comp-star star-score"><strong style="width:<?=$review_info['marks_ever_width']?>%;">5점만점에 <?=$review_info['marks_ever_cnt']?>점</strong></span><?=$review_info['marks_ever_cnt']?></div>
									<ol class="hide"><!-- [D] 기존 별점 그래프 임시 주석처리 -->
										<li>
											<dl>
												<dt>5<span>☆</span></dt>
												<dd><span class="meter"><strong style="width:<?=$review_info['marks5_per']?>%">총 참여수 <?=$t_count_review?> 중에</strong></span><?=$review_info['marks5_cnt']?></dd>
											</dl>
										</li>
										<li>
											<dl>
												<dt>4<span>☆</span></dt>
												<dd><span class="meter"><strong style="width:<?=$review_info['marks4_per']?>%;">총 참여수 <?=$t_count_review?> 중에</strong></span><?=$review_info['marks4_cnt']?></dd>
											</dl>
										</li>
										<li>
											<dl>
												<dt>3<span>☆</span></dt>
												<dd><span class="meter"><strong style="width:<?=$review_info['marks3_per']?>%;">총 참여수 <?=$t_count_review?> 중에</strong></span><?=$review_info['marks3_cnt']?></dd>
											</dl>
										</li>
										<li>
											<dl>
												<dt>2<span>☆</span></dt>
												<dd><span class="meter"><strong style="width:<?=$review_info['marks2_per']?>%;">총 참여수 <?=$t_count_review?> 중에</strong></span><?=$review_info['marks2_cnt']?></dd>
											</dl>
										</li>
										<li>
											<dl>
												<dt>1<span>☆</span></dt>
												<dd><span class="meter"><strong style="width:<?=$review_info['marks1_per']?>%;">총 참여수 <?=$t_count_review?> 중에</strong></span><?=$review_info['marks1_cnt']?></dd>
											</dl>
										</li>
									</ol>
								</div>
						<?php
							if((strlen($_ShopInfo->getMemid())==0) ) { //&& $_data->review_memtype=="Y"
						?>
								<a class="btn-write" href="javascript:;" onclick='javascript:location.href="<?=$Dir.MDir."login.php?chUrl=".$_SERVER["REQUEST_URI"]?>";' >상품리뷰 작성하기</a>
						<?php
							} else if( ( (!empty($review_ordercode) && !empty($review_order_idx)) || $_ShopInfo->getStaffType() == 1) && strlen($_ShopInfo->getMemid()) > 0 ){ // && $_data->review_memtype=="Y"
						?>
								<a class="btn-write" href="javascript:;" onClick="javascript:document.reviewForm.mode.value='write';document.reviewForm.submit();">상품리뷰 작성하기</a>
						<?php
							} else{
						?>
								<a class="btn-write" href="javascript:;" onclick="javascript:alert('상품을 주문하신후에 후기 등록이 가능합니다. 마이페이지->주문상세내역에서 확인해주세요.');">상품리뷰 작성하기</a>
						<?php
							}
						?>
								<section class="wrap_select_rating"><!-- [D] 2016-09-21 추가-->
									<div class="select_rating">
										<label>사이즈</label>
										<ul>
											<li>
												<span>작다</span>
												<input type="radio" value="-2" name="review_size">
											</li>
											<li>
												<span></span>
												<input type="radio" value="-1" name="review_size">
											</li>
											<li>
												<span>적당함</span>
												<input type="radio" value="0" name="review_size">
											</li>
											<li>
												<span></span>
												<input type="radio" value="1" name="review_size">
											</li>
											<li>
												<span>크다</span>
												<input type="radio" value="2" name="review_size">
											</li>
										</ul>
									</div>
									<div class="select_rating">
										<label>발볼 넓이</label>
										<ul>
											<li>
												<span>작다</span>
												<input type="radio" value="-2" name="review_foot_width">
											</li>
											<li>
												<span></span>
												<input type="radio" value="-1" name="review_foot_width">
											</li>
											<li>
												<span>적당함</span>
												<input type="radio" value="0" name="review_foot_width">
											</li>
											<li>
												<span></span>
												<input type="radio" value="1" name="review_foot_width">
											</li>
											<li>
												<span>크다</span>
												<input type="radio" value="2" name="review_foot_width">
											</li>
										</ul>
									</div>
									<div class="select_rating">
										<label>색상</label>
										<ul>
											<li>
												<span>어둡다</span>
												<input type="radio" value="-2" name="review_color">
											</li>
											<li>
												<span></span>
												<input type="radio" value="-1" name="review_color">
											</li>
											<li>
												<span>화면과 같다</span>
												<input type="radio" value="0" name="review_color">
											</li>
											<li>
												<span></span>
												<input type="radio" value="1" name="review_color">
											</li>
											<li>
												<span>밝다</span>
												<input type="radio" value="2" name="review_color">
											</li>
										</ul>
									</div>
									<div class="select_rating">
										<label>품질/만족도</label>
										<ul>
											<li>
												<span>불만</span>
												<input type="radio" value="-2" name="review_quality">
											</li>
											<li>
												<span></span>
												<input type="radio" value="-1" name="review_quality">
											</li>
											<li>
												<span>보통</span>
												<input type="radio" value="0" name="review_quality">
											</li>
											<li>
												<span></span>
												<input type="radio" value="1" name="review_quality">
											</li>
											<li>
												<span>만족</span>
												<input type="radio" value="2" name="review_quality">
											</li>
										</ul>
									</div>
								</section>
								
								<p class="tit_txt">리뷰를 작성해 주시면 핫티 온/오프라인 매장에서 사용가능한 포인트를 지급해 드립니다!!</p>
								<ul class="board_list_wrap">
<?php
	if( count( $reviewList ) > 0 ) {
		foreach( $reviewList as $rKey=>$rVal ) {
			$number = ( $paging->t_count - ( $setup['list_num'] * ( $gotopage - 1 ) ) - $rKey );
?>
									<li>
										<div>
											<span class="comp-star star-score"><strong style="width:<?=$rVal['marks_width']?>%;">5점만점에 <?=$rVal['marks_sp']?>점</strong></span>
											<i><?=setIDEncryp($rVal['id'])?> (<?=substr($rVal['date'],0,4)."-".substr($rVal['date'],4,2)."-".substr($rVal['date'],6,2)?>)</i>
										</div>
										<p class="title" ids="<?=$rVal['idx']?>"><?=$rVal['subject']?></p>
										<div class="cont_txt">
										<?php
											echo nl2br($rVal['content'][0]) . "<br>";
											if ( !empty($rVal['upfile']) ) { echo "<br><img src='" . $Dir.DataDir."shopimages/review/" . $rVal['upfile'] . "' />"; }
											if ( !empty($rVal['upfile2']) ) { echo "<br><img src='" . $Dir.DataDir."shopimages/review/" . $rVal['upfile2'] . "' />"; }
											if ( !empty($rVal['upfile3']) ) { echo "<br><img src='" . $Dir.DataDir."shopimages/review/" . $rVal['upfile3'] . "' />"; }
											if ( !empty($rVal['upfile4']) ) { echo "<br><img src='" . $Dir.DataDir."shopimages/review/" . $rVal['upfile4'] . "' />"; }
											if ( !empty($rVal['upfile5']) ) { echo "<br><img src='" . $Dir.DataDir."shopimages/review/" . $rVal['upfile5'] . "' />"; }
										?>
										</div>
										<?if ( $_ShopInfo->getMemid() == $rVal['id'] ) {?>
										<div class="buttonset">
											<a href="javascript:;" onclick="javascript:send_review_write_page(
											'<?=$rVal['productcode']?>',
											'<?=$rVal['ordercode']?>',
											'<?=$rVal['productorder_idx']?>',
											'<?=$rVal['num']?>');">수정</a>
											<a href="javascript:;" onclick="javascript:delete_review('<?=$rVal['num']?>');">삭제</a>
										</div>
										<?}?>
										<!-- [D] 20160905호감/비호감 버튼 영역 -->
										<!-- 
										<div class="btn-feeling mb-10">
											<a class="btn-good-feeling" href="javascript:select_feeling('<?=$rVal['idx']?>','review','good');" id="feeling_good_review_<?=$rVal['idx']?>"><?=totalFeeling($rVal['idx'], 'review','good')?></a>
											<a class="btn-bad-feeling" href="javascript:select_feeling('<?=$rVal['idx']?>','review','bad');" id="feeling_bad_review_<?=$rVal['idx']?>"><?=totalFeeling($rVal['idx'], 'review','bad')?></a>
										</div> -->
										<!-- // [D] 20160905호감/비호감 버튼 영역 -->
										<!-- 댓글입력폼 -->
										<div class="answer_area">
										<form onsubmit="return false;">
										<input type="hidden" name="pnum" value="<?=$rVal['idx']?>">
										<input type="hidden" name="mem_id" value="<?=$_ShopInfo->getMemid()?>">
										<input type="hidden" name="now_date" value="<?=date("Y.m.d")?>">
										<input type="hidden" name="return" value="OK">
											<div><input type="text" name="review_comment" style="width:100%;"></div>
											<?php if(strlen($_ShopInfo->getMemid())==0) { ?>
											<span><a class="btn_answer btn-type1" href="javascript:;" onClick="javascript:goLogin();">입력</a></span>
											<?php } else { ?>
											<span><a class="btn_answer btn-type1" href="javascript:;" onClick="javascript:review_comment_write(this);">입력</a></span>
											<?php } ?>
										</form>
										</div>
										<!-- // 댓글입력폼 -->
										<?php
										if( count( $rVal['comment'] ) > 0 ){
										?>
										<!-- 답변글 노출 -->
										<div class="admin_answer_list" id="reply_comment_<?=$rVal['idx']?>">
										<?
										foreach( $rVal['comment'] as $commentKey=>$commentVal ){
										?>
											<div class="admin_answer">
										<?
											echo '<span class="admin_name">' . setIDEncryp($commentVal->id) . ' (' . substr($commentVal->regdt,0,4)."-".substr($commentVal->regdt,4,2)."-".substr($commentVal->regdt,6,2) . ')</span>';

											echo '<p>' .$commentVal->content.'</p>';

											if ( $commentVal->id == $_ShopInfo->getMemid() ) {
												echo ' <div class="buttonset"><a class="btn-delete" href="javascript:;" onClick="javascript:delete_review_comment(this);" ids="' . $commentVal->no . '" ids2="' . $commentVal->pnum . '">삭제</a></div>';
											}
										?>
											</div>
											<!-- [D] 20160905호감/비호감 버튼 영역 -->
											<div class="btn-feeling mb-10 ml-20" id="<?=$commentVal->no ?>" >
												<a class="btn-good-feeling" href="javascript:select_feeling('<?= $commentVal->no?>','product_review_comment','good','<?=$_ShopInfo->getMemid() ?>');" id="feeling_good_comment_<?= $commentVal->no?>"><?=totalFeeling($commentVal->no, 'product_review_comment', 'good') ?></a>
												<a class="btn-bad-feeling" href="javascript:select_feeling('<?= $commentVal->no?>','product_review_comment','bad','<?=$_ShopInfo->getMemid() ?>');" id="feeling_bad_comment_<?= $commentVal->no?>"><?=totalFeeling($commentVal->no, 'product_review_comment', 'bad') ?></a>
											</div>
											<!-- // [D] 20160905호감/비호감 버튼 영역 -->
										<?
										} // comment foreach
										?>

										</div>

										<!-- // 답변글 노출 -->
										<?
										} // comment if
										?>
									</li>

<?php
		} // reviewList foreach
	}
?>
								</ul>
<?
if( count( $reviewList ) > 0 ) {
?>
								<!-- 페이징 -->
								<div class="list-paginate mt-20">
									<?=$paging->a_prev_page.' '.$paging->print_page.' '.$paging->a_next_page?>
								</div>
								<!-- //페이징 -->
<?
}
?>
							</div> <!-- // .content_area -->
							<a class="btn-toggle" href="javascript:void(0);" title="펼쳐보기"><span>SIZE 정렬</span></a>
						</section>
						<form name=reviewForm method="POST" action="mypage_review_write.php">
						<input type="hidden" name="productcode" id="productcode" value="<?=$productcode?>" />
						<input type="hidden" name="ordercode" id="ordercode" value="<?=$review_ordercode?>" />
						<input type="hidden" name="productorder_idx" id="productorder_idx" value="<?=$review_order_idx?>" />
						<input type="hidden" name="review_num" id="review_num" value="0" />
						<input type="hidden" name="mode" id="mode" value="" />
						</form>
						<!-- // 상품리뷰 -->

						<!-- 상품문의 -->
						<section class="product_qna">
							<h3>Q&A <em>(<?=number_format($qna_t_count)?>)</em></h3>
							<div class="content_area qna-list">
								<a class="btn-write" href="javascript:chkLoginWriteLink();">문의글 작성하기</a>
								<p class="tit_txt">상품과 관련된 문의사항이 있으신 분은 게시글을 남겨주시기 바랍니다.</p>
								<ul class="board_list_wrap">
<?php
	if( count( $qnaList ) > 0 ) {
		foreach( $qnaList as $rKey=>$rVal ) {
			$qna_date = date( "Y-m-d" , $rVal->writetime);

			list($qnaCount)=pmysql_fetch("SELECT count(num) FROM tblboardcomment WHERE board = 'qna' and parent = '".$row->num."'");
			$countStr = "";
			if($qnaCount > 0){
				$a_status	= "답변완료";
			} else {
				$a_status	= "답변대기";
			}

			$qna_reply_sql = "SELECT * FROM tblboardcomment WHERE board = 'qna' and parent = '".$rVal->num."' order by num desc";
			$qna_reply_res = pmysql_query($qna_reply_sql,get_db_conn());
?>
									<li>
										<div class="qna_write">
											<i><?=setIDEncryp($rVal->mem_id)?> (<?=$qna_date?>)</i>
											<span><?=$a_status?></span>
										</div>
										<p class="title"><?=$rVal->title?>
										<?if($rVal->is_secret == '1') {?>
										<span><img src="../static/img/icon/icon_lock.png" alt="비밀글"></span>
										<?}?>
										</p>
										<div class="cont_txt">
										<?if( $rVal->is_secret == '0' ||  $_ShopInfo->getmemid() == $rVal->mem_id ) {?>
										<?=nl2br($rVal->content)?>
										<?} else {?>
										비밀글입니다.
										<?}?>
										</div>
										<?if( $_ShopInfo->getmemid() == $rVal->mem_id ) {?>
										<div class="buttonset">
										<?if($qnaCount == 0){?>
											<a href="javascript:location.href='product_qna_write.php?productcode=<?=$productcode?>&pridx=<?=$rVal->pridx?>&qna_num=<?=$rVal->num?>'">수정</a>
										<?}?>
											<a href="javascript:PerDel(this,'<?=$rVal->num?>');">삭제</a>
											<input type='hidden' name='modify_passwd' value='<?=$rVal->passwd?>' >
										</div>
										<?}?>
										<?if( $rVal->is_secret == '0' ||  $_ShopInfo->getmemid() == $rVal->mem_id ) {?>
										<?
											while($qna_reply_row = pmysql_fetch_object($qna_reply_res)){
												$qna_reply_date = date( "Y-m-d" , $qna_reply_row->writetime);
										?>
										<!-- 답변글 노출 -->
										<div class="admin_answer">
											<span class="admin_name"><?=$qna_reply_row->name?> (<?=$qna_reply_date?>)</span>
											<p><?=nl2br($qna_reply_row->comment)?></p>
										</div>
										<!-- // 답변글 노출 -->
											<?}?>
										<?}?>
									</li>
<?
		}
	}
?>
								</ul>
<?
	if( count( $qnaList ) > 0 ) {
?>
								<!-- 페이징 -->
								<div class="list-paginate mt-20">
									<?=$qna_paging->a_prev_page.' '.$qna_paging->print_page.' '.$qna_paging->a_next_page?>
								</div>
								<!-- //페이징 -->
<?
	}
?>
							</div> <!-- // .content_area -->
							<a class="btn-toggle" href="javascript:void(0);" title="펼쳐보기"><span>SIZE 정렬</span></a>
						</section>
						<!-- // 상품문의 -->

						<!-- 배송/반품 -->
						<section class="product_return">
							<h3>SHOPPING & RETURNS INFO</h3>
							<div class="content_area">
								<?=$deli_info?>
							</div> <!-- // .content_area -->
							<a class="btn-toggle" href="javascript:void(0);" title="펼쳐보기"><span>SIZE 정렬</span></a>
						</section>
						<!-- // 배송/반품 -->
					</div>
				</div>
			</article>
			<!-- // 상품 정보 및 게시판 -->

			<!-- 상품상세 - 관련포스팅 -->
			<section id="relPosting" class="goods-detail-posting">
            <h3>관련 포스팅</h3>
            <div class="main-community-content on">
                <!-- (D) 좋아요를 선택하면 버튼에 class="on" title="선택됨"을 추가합니다. -->
                <ul class="comp-posting posting-list">
					<li class="grid-sizer"></li>
				</ul>
				<div class="btn_list_more posting-more">
				</div>
            </div>
        </section>
        <!-- // 상품상세 - 관련포스팅 -->
		</div>

		<input type="hidden" name="stock_prodcd" value="<?=$_pdata->prodcode?>">
		<input type="hidden" name="stock_colorcd" value="<?=$_pdata->colorcode?>">
		<input type = 'hidden' class = 'CLS_set_quantity' value = "">


		<!-- 상픔상세 구매하기 버튼영역 -->
		<section class="buying">
            <div class="inner">
                <div class="buy_level1">
                    <div class="alone">
                        <a href="javascript:;">구매하기</a>
                    </div>
                </div>
                <div class="buy_level2">
                    <div class="top">
                        <a href="javascript:;"><img src="./static/img/btn/btn_buying_close.png" alt="구매하기 닫기" /></a>
                    </div>
					


					<!-- 배송 방법 선택 -->
					<style>
						.CLS_store_selection_done_layer{
							display:none; position: absolute; background: #FFFFFF; padding: 8px; margin-left: -200px;margin-top: -10px; border: 1px solid #999; 
						}
						.hero-info-delivery-type .sorting-size {margin:10px 0;}
						.hero-info-delivery-type .sorting-size ul {margin:0px auto;}
						.hero-info-delivery-type .sorting-size li {float:left; width:32%; height:28px; line-height:28px; border:1px solid #696969; margin-right:5px; text-align:center; color:#000;}
						.hero-info-delivery-type .sorting-size label{display:block; overflow:hidden; position:relative; cursor:pointer;}
						.hero-info-delivery-type .sorting-size label input[type="radio"]{position:absolute; z-index:-1; left:-9999px; margin:0;}
						.hero-info-delivery-type .sorting-size label span{display:block; height:28px; color:000; font-size:1.2rem; line-height:28px; text-align:center;}
						.hero-info-delivery-type .sorting-size label :checked + span{background:#000; color:#fff; border:1px solid #000;}
					</style>
					<div class="hero-info-delivery-type">
						<div class="sorting-size">
							<ul class="clear">
								<?foreach($arrDeliveryType as $k => $v){?>
									<li><label><input type="radio" class="CLS_delivery_type" name="delivery_type" value="<?=$k?>" <?if($k=='0'){?>checked<?}?>><span><?=$v?></span></label></li>
								<?}?>
							</ul>
						</div>
					</div>



                    <div class="mid goods-detail-info-option">
<?php
		if( strlen( $_pdata->option1 ) > 0 || strlen( $_pdata->option2 ) > 0 ){ // 옵션정보 확인
            if( strlen( $_pdata->option1 ) > 0 ){
                $opt1_subject = option_slice( $_pdata->option1, '1' );
                //$opt1_content = option_slice( $_pdata->option1, $_pdata->option_type );
                $opt_tf       = option_slice( $_pdata->option1_tf, '1' );
                $select_option_code = array();
                $option_depth = count( $opt1_subject ); // 옵션 길이
                foreach( $opt1_subject as $subjectKey=>$subjectVal ){
?>
						<div>
							<h4><?=$subjectVal?></h4>
							<span name='opt'>
							<select name='opt_value' data-type='<?=$_pdata->option_type?>' data-prcode='<?=$_pdata->productcode?>' data-depth='<?=($subjectKey + 1)?>' data-qty='<?=$_pdata->quantity?>' data-tf='<?=$opt_tf[$subjectKey]?>' class = 'CLS_option_value'>
							<option value='' data-price='0' data-qty='' data-code=''>선택</option>
<?php
                    if( ( $subjectKey == 0 && $_pdata->option_type == '0' ) || $_pdata->option_type == '1' ){
                        //옵션정보를 가져온다
                        if( $_pdata->option_type == '0' ){
                            $options = get_option( $_pdata->productcode );
                        } else if( $_pdata->option_type == '1' ){
                            $options = mobile_get_alone_option( $_pdata->productcode, $subjectVal );
                        } else {
                            $options = array();
                        }
                        foreach( $options as $contentKey=>$contentVal ) { //옵션내용
                            $option_qty = $contentVal['qty']; // 수량
                            $option_text = ''; // 품절 text
                            $priceText = ''; // 가격
                            $option_desabled = false;
                            $alone_opt = array();

                            if( $_pdata->option_type == '0' && $subjectKey == 0 ) {
                                $select_code = $contentVal['code']; //조합형 옵션 코드형태 + 1depth 일때
                            } else if( $_pdata->option_type == '1' ) {
                                $select_code = $contentVal['option_code']; // 독립형 옵션일때
                                //$alone_opt = explode( chr( 30 ), $opt1_content[$subjectKey] );
                            } else {
                                $select_code = '';
                            }

                            //상품가격 text 처리 ( 조합형일 경우 마지막 depth의 옵션만 적용, 독립형일경우 전부다 적용 )
                            if(
                                (
                                  ( $_pdata->option_type == '0' && $subjectKey + 1 == $option_depth ) ||
                                  ( $_pdata->option_type == '1' )
                                ) && $contentVal['price'] > 0
                            ) {
                                $priceText = ' ( + '.number_format($contentVal['price']).' 원 )';
                            } else if(
                                (
                                  ( $_pdata->option_type == '0' && $subjectKey + 1 == $option_depth ) ||
                                  ( $_pdata->option_type == '1' )
                                ) && $contentVal['price'] < 0
                            ) {
                                $priceText = ' ( - '.number_format($contentVal['price']).' 원 )';
                            } // 상품가격 if

                            //품절 text 처리
                            if(
                                ( $option_qty !== null && $option_qty <= 0 ) &&
                                $_pdata->option_type == '0' &&
                                $_pdata->quantity < 999999999 &&
                                $subjectKey + 1 == $option_depth
                            ){
                                $option_text = '[품절]&nbsp;';
                                $option_desabled = true;
                            } //품절 id
?>
                        <option  data-qty='<?=$option_qty?>' data-code='<?=$contentVal["code"]?>' value="<?=$select_code?>"
                            <? if( $contentVal['code'] == $opt1_content[$subjectKey] && $_pdata->option_type == '0' ){ echo ' selected '; } ?>
                            <?// if( $contentVal['code'] == $alone_opt[1] && $_pdata->option_type == '1' ){ echo ' selected '; } ?>
                            <? if( $option_desabled ) { echo ' disabled '; } ?>
                            <? if( $_pdata->option_type == '0' && $subjectKey + 1 == $option_depth ) { echo 'data-qty="'.$option_qty.'" '; } ?>
                            <? echo 'data-price="'.$contentVal['price'].'" '; ?>
                        >
                            <?=$option_text.$contentVal['code'].$priceText?>
                        </option>
<?php
                        } // get_option if
                    }
?>
							</select>
							</span>
						</div>

<?php
                } // opt_subject foreach
            } // opt1_name if

            if( strlen( $_pdata->option2 ) > 0 ){ // 텍스트 옵션
                $text_opt_subject = option_slice( $_pdata->option2, '1' );
                //$text_opt_content = option_slice( $_pdata->text_opt_content, '1' );
                $text_opt_tf      = option_slice( $_pdata->option2_tf, '1' );
                $test_opt_maxln   = option_slice( $_pdata->option2_maxlen, '1' );
                foreach( $text_opt_subject as $textOptKey=>$textOptVal ){
                    $text_opt_tf_msg = '';
                    if( $text_opt_tf[$textOptKey] == 'T' ) $text_opt_tf_msg = '(필수)';

?>
						<div>
							<h4>사이즈</h4>
							<span name='text-opt'>
							<input type='text' name='text_opt_value' value='<?=$text_opt_content[$textOptKey]?>' maxlength='<?=$test_opt_maxln[$textOptKey]?>' data-tf="<?=$text_opt_tf[$textOptKey]?>" >
							<span class="byte">(<strong><?=strlen($text_opt_content[$textOptKey])?></strong>/<?=$test_opt_maxln[$textOptKey]?>)</span>
							</span>
						</div>
<?php
                } // text_opt_subject foreach
            } // text_opt_subject if
        }// option if
	if( $_pdata->quantity <= 0 || $_pdata->soldout == 'Y' ){  // 품절
?>

						<div class='hide' name='sc_quantity'>
							<h4>수량</h4>
							<span class="quantity">
							<input type="hidden" name='quantity' id='quantity' value="0">
							</span>
						</div>
                    </div>
                    <div class="bot ea3"><!-- [D] 버튼이 세개인 경우 .ea3 클래스 추가(2016-09-13) -->
                        <?if ($_pdata->hotdealyn=='N') {?><span><a class="btn-shoppingbag" href="javascript:alert('품절된 상품입니다.');">장바구니</a></span><?}?>
                        <span><a class="btn-buy" href="javascript:alert('품절된 상품입니다.');">바로구매</a></span>
					<?if( $staff_yn == 'Y' && $_pdata->hotdealyn=='N' ) {?>
                        <span><a href="javascript:alert('품절된 상품입니다.');">임직원구매</a></span><!-- [D] 임직원 구매버튼 추가(2016-09-13) -->
					<?}?>
                    </div>
                </div>
<?
	} else {
		$mem_auth_type	= getAuthType($_ShopInfo->getMemid());
		if($mem_auth_type!='sns') {
?>

						<!-- 
						<div name='sc_quantity'>
							<h4>수량</h4>
							<span class="quantity qty">
								<button class="minus btn-qty-subtract" type="button">감소</button>
								<input type="number" value="1" name='quantity' title="수량" readonly>
								<button class="plus btn-qty-add" type="button">증가</button>
							</span>
						</div>
						-->
						<div name='sc_quantity'>
							<h4>&nbsp;</h4>
							<span class="quantity qty">&nbsp;</span>
						</div>


						<style>
							.hero-info-option{
								padding-top:30px;
							}							
							.hero-info-option .btn_option_delete {float:left;bottom:3px; right:0; margin:0px auto; text-align:center; width:100%; }
							.hero-info-option .btn_option_delete a {width:114px; height:35px; line-height:35px; background:#a6a6a6; font-size:1.3rem;}

							.hero-info-option .btn_option_delete_type {float:left;bottom:3px; right:0; margin:0px auto; text-align:center; width:100%; }
							.hero-info-option .btn_option_delete_type a {width:90%; height:35px; line-height:35px; background:#a6a6a6; font-size:1.3rem; }
						</style>
						<div name='sc_quantity' class = 'hero-info-option'>
							<table width = '100%' cellpadding="5" cellspacing="0" border="1" align="center" style="border-collapse:collapse; border:1px gray solid;">
								<col width = '15%'><col width = '*%'><col width = '20%'><col width = '20%'><col width = '15%'>
								<tbody class = 'hero-info-option-table'>
								</tbody>
							</table>
						</div>

                    </div>




                    <div class="bot ea3"><!-- [D] 버튼이 세개인 경우 .ea3 클래스 추가(2016-09-13) -->
                        <?if ($_pdata->hotdealyn=='N') {?><span><a class="btn-buy" href="javascript:basket_insert(0);">장바구니</a></span><?}?>
                       <span> <a class="btn-shoppingbag" href="javascript:order_check(0,'N');">바로구매</a></span>
					<?if( $staff_yn == 'Y' && $_pdata->hotdealyn=='N' ) {?>
                        <span><a href="javascript:order_check(0,'Y');">임직원구매</a></span><!-- [D] 임직원 구매버튼 추가(2016-09-13) -->
					<?}?>
                    </div>
                </div>
<?
		} else {
?>

						<div class='hide' name='sc_quantity'>
							<h4>수량</h4>
							<span class="quantity">
							<input type="hidden" name='quantity' id='quantity' value="0">
							</span>
						</div>
                    </div>
                    <div class="bot ea3"><!-- [D] 버튼이 세개인 경우 .ea3 클래스 추가(2016-09-13) -->
                        <?if ($_pdata->hotdealyn=='N') {?><span><a class="btn-shoppingbag" href="javascript:chkAuthMemLoc('','mobile');">장바구니</a></span><?}?>
                        <span><a class="btn-buy" href="javascript:chkAuthMemLoc('','mobile');">바로구매</a></span>
                    </div>
                </div>
<?php
		}
	}
?>

            </div>
        </section>
		<!-- // 상픔상세 구매하기 버튼영역 -->

    </main>
    <!-- goods #container -->
</div><!-- //#contents -->

<!-- 스크롤 버튼 영역 -->
<div class="quick_btn_wrap">
	<a href="#" class="prev_btn"><img src="./static/img/btn/btn_prev.png" alt="페이지 이전 바로가기"></a>
</div>
<!-- // 스크롤 버튼 영역 -->

<!-- sns공유 팝업 -->
<div class="layer-dimm-wrap pop-sns_share">
	<div class="dimm-bg"></div>
	<div class="layer-content">
		<div class="sns_area">
			<a href="javascript:sendSns('facebook','<?=$link_url ?>','<?=$_pdata->brand?> <?=$_pdata->productname?>');"><img src="./static/img/btn/btn_sns_facebook.png" alt="facebook"></a>
			<a href="javascript:sendSns('twitter','<?=$link_url ?>','<?=$_pdata->brand?> <?=$_pdata->productname?>');" ><img src="./static/img/btn/btn_sns_twitter.png" alt="twitter"></a>
			<a href="javascript:sendSns('band','<?=$link_url ?>','<?=$_pdata->brand?> <?=$_pdata->productname?>');"><img src="./static/img/btn/btn_sns_band.png" alt="band"></a>
			<a href="javascript:;" id="kakao-link"><img src="./static/img/btn/btn_sns_kakao.png" alt="kakaotalk"></a>
			<a href="javascript:sendSns('kakaostory','<?=$link_url ?>','<?=$_pdata->brand?> <?=$_pdata->productname?>');"  id="kakaostory-link"><img src="./static/img/btn/btn_sns_kakaostory.png" alt="kakaostory"></a>
		</div>
	</div>
		<input type="hidden" id="link-label" value="HOTT 온라인 매장">
		<input type="hidden" id="link-title" value="<?=$_pdata->brand?> <?=$_pdata->productname?>">
		<input type="hidden" id="link-image" value="<?=$_pdata->maximage?>" data-width='200' data-height='300'>
		<input type="hidden" id="link-url" value="<?=$link_url ?>">
		<input type="hidden" id="link-img-path"value="<?=$imgPath ?>">

</div>
<!-- // sns공유 팝업 -->

<!-- 상품 주문관련 스크립트 -->
<script>
    // 상품 품절정보
    var _qty            = 0 //상품 수량
    var _soldout        = 'N' //품절 유무
    var _productname    = '<?=$_pdata->productname?>';
    var _productcode    = '<?=$_pdata->productcode?>';
    var _price          = '<?=$_pdata->sellprice?>';
    var _opt_type       = '<?=$_pdata->option_type?>'; // 옵션 type ( 0 - 조합형, 1 - 독립형 )
    var _memchk         = '<?=strlen( $_ShopInfo->getMemid() )?>'; // 회원 체크

    $(document).ready( function( ) {
        _qty     = <?=$_pdata->quantity?> //상품 수량
        _soldout = '<?=$_pdata->soldout?>' //품절 유무

    });
    // 옵션 체크
    function check_option( list_index, op_type ) {
        var product_area   = $('div.goods-detail-info-option').eq( list_index );
        var opt_target     = $(product_area).find('select[name="opt_value"]');
        var txt_opt_target = $(product_area).find('input[name="text_opt_value"]');
        var err_type = true;

        if( $( txt_opt_target ).length > 0 ){ // text 옵션이 존재할 경우
            $( txt_opt_target ).each( function(){
                if( $(this).data('tf') == 'T' && $(this).val() == '' ){
                    alert( '필수 옵션이 존재합니다.' );
                    $(this).focus();
                    err_type = false;
                    return false;
                }
            });
        }

        if( err_type === false ) return err_type;

        if( $( opt_target ).length > 0 ){ // 옵션이 존재할 경우
            if( op_type == '0' ){ // 조합형 옵션
                if(  $(opt_target).last().val() == '' ){
                    alert( '옵션을 선택하셔야 합니다.' );
                    err_type = false;
                    return err_type;
                }
            } else { // 독립형 옵션
                $(opt_target).each( function(){
                    if( $(this).data('tf') == 'T' && $(this).val() == '' ){
                        alert( '옵션을 선택하셔야 합니다.' );
                        err_type = false;
                        return false;
                    }
                });
            }
        }

        return err_type;

    }
   //수량 체크
    function chk_quantity( list_index, op_type ){
        var product_area   = $('div.goods-detail-info-option').eq( list_index );
        var opt_target     = $(product_area).find('select[name="opt_value"]');
        var product_qty    = _qty;
        var qty            = 0;
        var option_qty     = 0;

        if( $( opt_target ).length > 0 ){
            if( op_type == '0' ){ // 조합형 옵션
                var last_option = $(opt_target).last();
                $( last_option ).find('option').each( function(){
                    if( $(this).prop( 'selected' ) ) {
                        option_qty = $(this).data('qty');
                    }
                });
                qty = option_qty;
            } else {
                qty = product_qty;
            }
        } else {
            qty = product_qty;
        }

        return qty;

    }
    //옵션코드
    function select_opt( list_index, op_type ){
        var product_area   = $('.goods-detail-info-option').eq( list_index );
        var opt_target     = $(product_area).find('select[name="opt_value"]');
        var txt_opt_target = $(product_area).find('input[name="text_opt_value"]');
        var tmp_op_code = [];
        var op_code = '';
        var tmp_txt_op_code = [];
        var txt_op_code = '';
        var obj = {};

        if( $( txt_opt_target ).length > 0 ){ // text 옵션이 존재할 경우
            $( txt_opt_target ).each( function(){
                tmp_txt_op_code.push( $(this).val() );
            });
            txt_op_code = tmp_txt_op_code.join('@#');
        }

        if( $( opt_target ).length > 0 ){ // 옵션이 존재할 경우
            if( _opt_type == '0' ){ // 조합형 옵션
                op_code = $(opt_target).last().val();
            } else { // 독립형 옵션
                $(opt_target).each( function(){
                    tmp_op_code.push( $(this).val() );
                });
                op_code = tmp_op_code.join('@#');
            }
        }

        obj = { "op_code" : op_code, "txt_op_code" : txt_op_code };

        return obj;

    }

    // 텍스트 옵션 문자열 증가
    $(document).on( 'keyup', 'input[name="text_opt_value"]', function( event ) {
        var event_target = $(this).next().find('strong');
        event_target.html( $(this).val().length );
        var product_area  = $(this).parent().parent().parent();
        var list_index    = $('.goods-detail-info-option').index( product_area );
    });

    //수량변경 +
    $(document).on( 'click', '.btn-qty-add', function( event ){
        var product_area  = $(this).parent().parent().parent();
        var list_index    = $('.goods-detail-info-option').index( product_area );
        var input_target  = $(this).prev();
        var option_type   = _opt_type;
        var qty           = 0;

        if( check_option( list_index, option_type ) === false ) return;
        qty = chk_quantity( list_index, option_type );

        if( qty < parseInt( $(input_target).val() ) + 1 ){
            alert('재고가 부족합니다.');
            $(input_target).val( qty );
            return;
        } else {
            $(input_target).val( parseInt( $(input_target).val() ) + 1 );
        }

    });
    //수량변경 -
    $(document).on( 'click', '.btn-qty-subtract', function( event ){
        var product_area  = $(this).parent().parent().parent();
        var list_index    = $('.goods-detail-info-option').index( product_area );
        var input_target  = $(this).next();
        var option_type   = _opt_type;
        var qty           = 0;

        if( check_option( list_index, option_type ) === false ) return;
        qty = chk_quantity( list_index, option_type );

        if( parseInt( $(input_target).val() ) - 1 < 1 ) {
            alert('상품수량을 1개 이상 선택하셔야 합니다.');
            $(input_target).val( 1 );
            return;
        } else {
            $(input_target).val( parseInt( $(input_target).val() ) - 1 );
        }
    });
    //수량변경 직접입력
    $(document).on( 'keyup', 'input[name="quantity"]', function( event ){
        var product_area  = $(this).parent().parent().parent();
        var list_index    = $('.goods-detail-info-option').index( product_area );
        var input_target  = $(this);
        var option_type   = _opt_type
        var qty           = 0;

        if( check_option( list_index, option_type ) === false ) return;
        qty = chk_quantity( list_index, option_type );

        if( qty < 1 ) {
            alert('상품수량을 1개 이상 선택하셔야 합니다.');
            $(input_target).val( 1 );
            return;
        } else if( qty < parseInt( $(input_target).val() ) ){
            alert('재고가 부족합니다.');
            $(input_target).val( qty );
            return;
        } else if( $(input_target).val() == '' ){
            $(input_target).val( 1 );

        }
    });
    //숫자키 이외의 것을 막음
    $(document).on( 'keydown', 'input[name="quantity"]', function( event ) {
        if( !isNumKey( event ) ) event.preventDefault();
    });

    //옵션변경
    $(document).on( 'change', 'select[name="opt_value"]', function( event ){
        var product_area    = $(this).parent().parent().parent();
        var list_index      = $('.goods-detail-info-option').index( product_area );
        var productcode     = $(this).data('prcode');
        var product_qty     = $(this).data('qty');
        var option_type     = $(this).data('type');
        var option_code     = '';
        var idx             = $(this).data('depth');
        var next_select_box = $( product_area ).find('select[name="opt_value"]').eq( idx );

        // 독립형 옵션일 경우에는 작동을 안한다 ( 값을 이미 다 불러왔기 때문 )

        if( option_type == '1' ) {
            return;
        }
        // 선택된 옵션코드를 가져온다
        $(this).find('option').each( function(){
            if( $(this).prop( 'selected' ) ){
                option_code = $(this).val();
            }
        });
        // 선택된 옵션 이후에 것들을 초기화
        $( product_area ).find('select[name="opt_value"]').each( function( i, obj ){
            if( i >= idx) {
                $(this).html( '<option value="" > 선택 </option>' );
                $(this).attr( 'disabled', 'true' );
            }
        });
        // 옵션 코드가 없으면 다음 옵션을 지정 못한다
        if( option_code == '' ) {
            return;
        }

        // 다음 옵션값을 가져온다
        $.ajax({
            type : "POST",
            url : "<?=$Dir.FrontDir?>ajax_option_select.php",
            data : { productcode : productcode, option_code : option_code, idx : idx },
            dataType : "json"
        }).done( function( data ){
            var html = '<option value="" > 선택 </option>';
            if( !jQuery.isEmptyObject( data ) ){
                $.each( data , function( i, obj ){
                    var price_text = '';
                    var soldout = '';
                    var disabled_text = '';
                    var data_code = [];
                    var tmp_option_code = obj.option_code.split( chr( 30 ) );
                    for( var i=0; i < idx + 1; i++ ){
                        data_code.push( tmp_option_code[i] );
                    }
                    // 옵션 추가 가격 text
                    if( idx == $( product_area ).find('select[name="opt_value"]').length - 1 ){
                        if( obj.price != '' && obj.price > 0 ){
                            price_text = ' ( + ' + comma( obj.price ) + ' 원 )';
                        } else if( obj.price != '' && obj.price < 0 ) {
                            price_text = ' ( - ' + comma( obj.price ) + ' 원 )';
                        }
                    }
                    // 수량
                    if( obj.soldout == "1" && product_qty < 999999999 ) {
                        soldout = '[품절]&nbsp;';
                        disabled_text = 'disabled';
                    }

                    html += '<option value="' + data_code.join( chr( 30 ) ) + '" data-price="' + obj.price + '" data-qty="' + obj.qty + '" ' + disabled_text + ' >' + soldout + obj.code + price_text +'</option>';
                });
                next_select_box.removeAttr( 'disabled' );
                next_select_box.html( html );
            }
        });

    });

    //장바구니
    function basket_insert( list_index ){
        var product_area  = $('.goods-detail-info-option').eq( list_index );
        var input_target  = $( product_area ).find('input[name="quantity"]');
        var option_type   = _opt_type;
        var quantity      = 0;
        var qty           = 0;
        var opt_obj       = {};
        var opt_code      = '';
        var txt_op_code   = '';
        var text_subject  = '<?=$_pdata->option2?>';

		/*
        if( check_option( list_index, option_type ) === false ) return;
        qty = chk_quantity( list_index, option_type );
        if( qty < 1 ) {
            alert('상품수량을 1개 이상 선택하셔야 합니다.');
            $(input_target).val( 1 );
            return;
        } else if( qty < parseInt( $(input_target).val() ) ){
            alert('재고가 부족합니다.');
            $(input_target).val( qty );
            return;
        }
		*/

		var totalQuantitySum = 0;
		$("input[name='add_quantity[]']").each( function(){
			totalQuantitySum += parseInt( $( this ).val() );
		});
		if($("input[name='add_quantity[]']").length < 1 && $("input[name='add_quantity[]']").length > totalQuantitySum){
			alert('주문 수량을 확인하세요.');
			return;
		}

		if($("input[name='add_option[]']").length < 1){
			alert('옵션을 선택해 주시기 바랍니다.');
			return;
		}


		//quantity = $(input_target).val();
        //해당 옵션정보를 가져옴
        opt_obj = select_opt( list_index, option_type );
        //opt_code = opt_obj.op_code;
        txt_op_code = opt_obj.txt_op_code;

		var dataBasketFlag = false;
		var basketMsg = "";
		// 옵션 수량 만큼 배열을 돌려서 장바구니에 담음
		$("input[name='add_option[]']").each(function(){
			option_code = $(this).val();
			quantity = $(this).parent().parent().find("input[name='add_quantity[]']").val();

			var reservation_date = "";
			var post_code = "";
			var address1 = "";
			var address2 = "";

			var store_code = $(this).parent().parent().find("input[name='store_code[]']").val();

			if($(this).parent().parent().find("input[name='reservation_date[]']").length > 0){
				reservation_date = $(this).parent().parent().find("input[name='reservation_date[]']").val();
			}

			if($(this).parent().parent().find("input[name='post_code[]']").length > 0){
				post_code = $(this).parent().parent().find("input[name='post_code[]']").val();
			}

			if($(this).parent().parent().find("input[name='address1[]']").length > 0){
				address1 = $(this).parent().parent().find("input[name='address1[]']").val();
			}

			if($(this).parent().parent().find("input[name='address2[]']").length > 0){
				address2 = $(this).parent().parent().find("input[name='address2[]']").val();
			}

			$.ajax({
				method : 'POST',
				url : '<?=$Dir.FrontDir?>confirm_basket_proc.php',
				data : {
					productcode : _productcode, option_code : option_code, quantity : quantity,
					option_type : option_type, mode : 'insert',
					text_opt_subject : text_subject, text_opt_content : txt_op_code, 
					delivery_type : global_delivery_type, store_code : store_code, reservation_date : reservation_date,
					post_code : post_code, address1 : address1, address2 : address2
				},
				async: false,
				dataType : 'json'
			}).done( function( data ) {
				if( data ){
					dataBasketFlag = true;
				} else {
					basketMsg = '장바구니 등록이 실패되었습니다.';
				}
			});
		})
			
		if( dataBasketFlag ){
			if ( confirm("장바구니에 추가되었습니다.\n장바구니로 이동하시겠습니까?") ) {
				location.href="<?=$Dir.MDir?>basket.php";
			}
		}else{
			alert(basketMsg);
		}
    }

    function order_check( list_index, staffchk ){

        var product_area  = $('.goods-detail-info-option').eq( list_index );
        var input_target  = $( product_area ).find('input[name="quantity"]');
        var option_type   = _opt_type;
        var quantity      = 0;
        var qty           = 0;
        var opt_obj       = {};
        var opt_code      = '';
        var txt_op_code   = '';
        var text_subject  = '<?=$_pdata->option2?>';

		/*
        if( check_option( list_index, option_type ) === false ) return;
        qty = chk_quantity( list_index, option_type );

        if( qty < 1 ) {
            alert('상품수량을 1개 이상 선택하셔야 합니다.');
            $(input_target).val( 1 );
            return;
        } else if( qty < parseInt( $(input_target).val() ) ){
            alert('재고가 부족합니다.');
            $(input_target).val( qty );
            return;
        }
		*/
		var totalQuantitySum = 0;
		$("input[name='add_quantity[]']").each( function(){
			totalQuantitySum += parseInt( $( this ).val() );
		});
		if($("input[name='add_quantity[]']").length < 1 && $("input[name='add_quantity[]']").length > totalQuantitySum){
			alert('주문 수량을 확인하세요.');
			return;
		}

		if($("input[name='add_option[]']").length < 1){
			alert('옵션을 선택해 주시기 바랍니다.');
			return;
		}


        //quantity = $(input_target).val();
        //해당 옵션정보를 가져옴
        opt_obj = select_opt( list_index, option_type );
        //opt_code = opt_obj.op_code;
        txt_op_code = opt_obj.txt_op_code;

		var dataBasketidx = [];
		var basketMsg = "";
		// 옵션 수량 만큼 배열을 돌려서 장바구니에 담음
		$("input[name='add_option[]']").each(function(){
			option_code = $(this).val();
			quantity = $(this).parent().parent().find("input[name='add_quantity[]']").val();

			var reservation_date = "";
			var post_code = "";
			var address1 = "";
			var address2 = "";

			var store_code = $(this).parent().parent().find("input[name='store_code[]']").val();

			if($(this).parent().parent().find("input[name='reservation_date[]']").length > 0){
				reservation_date = $(this).parent().parent().find("input[name='reservation_date[]']").val();
			}

			if($(this).parent().parent().find("input[name='post_code[]']").length > 0){
				post_code = $(this).parent().parent().find("input[name='post_code[]']").val();
			}

			if($(this).parent().parent().find("input[name='address1[]']").length > 0){
				address1 = $(this).parent().parent().find("input[name='address1[]']").val();
			}

			if($(this).parent().parent().find("input[name='address2[]']").length > 0){
				address2 = $(this).parent().parent().find("input[name='address2[]']").val();
			}

			$.ajax({
				method : 'POST',
				url : '<?=$Dir.FrontDir?>confirm_basket_proc.php',
				data : {
					productcode : _productcode, option_code : option_code, quantity : quantity,
					option_type : option_type, mode : 'order',
					text_opt_subject : text_subject, text_opt_content : txt_op_code, 
					delivery_type : global_delivery_type, store_code : store_code, reservation_date : reservation_date,
					post_code : post_code, address1 : address1, address2 : address2
				},
				async: false,
				dataType : 'json'
			}).done( function( data ) {
				if( data.basketidx ){
					dataBasketidx.push( data.basketidx );
				} else {
					basketMsg = '장바구니 등록이 실패되었습니다.';
				}
			});
		});

		if( dataBasketidx.length > 0 ){
			var dataBasketidxStr = dataBasketidx.join( '|' );
			if( _memchk == 0 ){
				location.href = "login.php?chUrl=/m/order.php?basketidxs=" + dataBasketidxStr;
			} else {
				location.href = "order.php?"+"basketidxs=" + dataBasketidxStr + "&staff_order=" + staffchk;
			}
		}else{
			alert(basketMsg);
		}

    }

    // php chr() 대응
    function chr(code)
    {
        return String.fromCharCode(code);
    }

</script>
<!-- //상품 주문관련 스크립트 -->
<!-- 배너관련 스크립트 -->
<!-- <script src="//developers.kakao.com/sdk/js/kakao.min.js"></script> -->
<script>
	var global_delivery_type = 0;
	var global_selection_store_idx = "";

    // 대엽 카카오 key
    Kakao.init('914d0b932f83f00a9693fefb9155ff76');

    $(document).ready( function(){
        var banner_img   = $('#card-banner-img').val();
        if( banner_img != '' ) {
            $('#popup-card > div.popup-layer-content').html('<img src="' + banner_img + '" >');
        }

        Kakao.Link.createTalkLinkButton({
            container: '#kakao-link',
            label: "<?=$_data->shoptitle?>",
            image: {
                src: '<?=$tmp_kakao_img?>',
                width: '300',
                height: '300'
            },
            webButton: {
                text: '<?=$_pdata->brand?> <?=addslashes($_pdata->productname)?>',
                url: "http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>" // 앱 설정의 웹 플랫폼에 등록한 도메인의 URL이어야 합니다.
            }
        });










		/////////////////////////////////////////////////////////
		//////////////////// 상품 옵션 추가 ///////////////////
		/////////////////////////////////////////////////////////
		$(".CLS_option_value").change(function(){
			var selectQty = 1;
			var optionQty = $(this).find(':selected').data("qty");
			var optionCode = $(this).find(':selected').data("code");
			var stockProdcd = $("input[name='stock_prodcd']").val();
			var stockColorcd = $("input[name='stock_colorcd']").val();

			// 추가된 객체에서 추가할 옵션이 있는지 체크한다.
			var optionCheck = true;
			$(".hero-info-option-table input[name='add_option[]']").each(function(){
				if($(this).val() == optionCode){
					optionCheck = false;
				}
			})

			var deliveyTypeCheck = true;
			if(global_delivery_type == 2 && $(".hero-info-option-table tr").length > 0){
				deliveyTypeCheck = false;
			}

			if(optionCheck){
				var innerHTML = "";
				innerHTML += "<tr style = 'display:none;'>";

				innerHTML += "	<td>";
				innerHTML += "		" + optionCode + " <input type = 'hidden' name = 'add_option[]' value = '" + optionCode + "'>";
				innerHTML += "	</td>";
				// 당일 발송이 아니면 수량 덧셈 뺄셈
				if(global_delivery_type == 2){

					innerHTML += "	<td style = 'padding:7px 0px;'>";
					innerHTML += "		<span class='quantity qty' style = 'margin:0px auto;'>";
					innerHTML += "			<input type='number' value='1' name='add_quantity[]' title='수량' readonly>";
					innerHTML += "		</span>";
					innerHTML += "	</td>";

				}else{

					innerHTML += "	<td style = 'padding:7px 0px;'>";
					innerHTML += "		<span class='quantity qty' style = 'margin:0px auto;'>";
					innerHTML += "			<button class='minus addOption-btn-minus' type='button'>감소</button>";
					innerHTML += "			<input type='number' value='" + selectQty + "' name='add_quantity[]' title='수량' readonly>";
					innerHTML += "			<button class='plus addOption-btn-plus' type='button'>증가</button>";
					innerHTML += "		</span>";
					innerHTML += "	</td>";

				}



				if(global_delivery_type == 0){

					// 택배발송일 경우 제거 버튼만 노출
					innerHTML += "	<td colspan = '3'>";
					innerHTML += "		<div class='btn_option_delete'>";
					innerHTML += "			<a class='btn-type1 addOptionDelete' href='javascript:;'>제거</a>";
					innerHTML += "		</div>";
					innerHTML += "	</td>";

				}else{
					// 택배발송이 아닐 경우 매장 선택 버튼 노출
					innerHTML += "	<td>";
					innerHTML += "		<div class='btn_option_delete_type'>";
					innerHTML += "			<a class='btn-type1 addOptionDelete' href='javascript:;'>제거</a>";
					innerHTML += "		</div>";
					innerHTML += "	</td>";

					innerHTML += "	<td>";
					innerHTML += "		<div class='btn_option_delete_type'>";
					innerHTML += "			<a class='btn-type1 addOptionStore' data-prodcode = '"+stockProdcd+"' data-colorcode = '"+stockColorcd+"' data-size = '"+optionCode+"' data-delivery_type = '" + global_delivery_type + "' href=\"javascript:;\">매장선택</a>";
					innerHTML += "		</div>";
					innerHTML += "	</td>";

					innerHTML += "	<td>";
					innerHTML += "		<div class='store_selection_area'>";
					innerHTML += "			[미선택]";
					innerHTML += "		</div>";
					innerHTML += "	</td>";
				}


				innerHTML += "</tr>";


				if(deliveyTypeCheck){
					if(optionQty > 0){
						$(".hero-info-option-table").append(innerHTML);
						$(".hero-info-option-table tr:last").fadeIn('800');

						reSettingEqIndex();
					}else{
						alert("해당 옵션의 재고가 없습니다.");
					}
				}else{
					alert("당일 수령 상품은 한번에 하나만 주문 가능합니다.");
				}


			}else{
				alert("해당 옵션이 이미 추가되어 있습니다.");
			}
		})
    });



	/////////////////////////////////////////////////////////
	//////////////// 매장 선택 iFrame 출력 //////////////
	/////////////////////////////////////////////////////////	

    function closeStoreLayer() {
        $("#storeDataLayer").hide();
    }

	$(document).on('click', '.addOptionStore', function(){
		var optionAppendObj = $(this);
		$.ajax({
			type: "POST",
			url: "../front/ajax_get.reserve.date.php",
			data : { mode : 'dateFlag', delivery_type : global_delivery_type },
			dataType : 'html'
		}).done( function( flag ){
			if((global_delivery_type == '2' && flag == '1') || (global_delivery_type == '1')){

				var sizechk = $(optionAppendObj).data('size');
				var prodcd = $(optionAppendObj).data('prodcode');
				var colorcd = $(optionAppendObj).data('colorcode');
				var delivery_type = $(optionAppendObj).data('delivery_type');
				var optionQuantity = $(optionAppendObj).parent().parent().parent().find("input[name='add_quantity[]']").val();
				var global_selection_store_idx = $(optionAppendObj).data('eqindex');

				var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
				$(".storeDataLayerFrame").attr("src", "/m/offline_inventory_new.php?mode=result&productcode=<?=$productcode?>&prodcd="+prodcd+"&colorcd="+colorcd+"&sizechk="+sizechk+"&delivery_type="+delivery_type+"&option_quantity="+optionQuantity+"&eqindex="+global_selection_store_idx);
				$("#storeDataLayer").show();
				initLayerPosition();

			}else{
				// 배송타입 1은 매장 선택시 새로 불러오도록 되어 있음
				if(global_delivery_type == '2'){
					alert("주문 가능한 시간이 지났습니다.( 매일 15시 )");
					$(".hero-info-option-table").html('');
				}
			}
		});
	})
	/*
	function openStoreLayer(prodcd, colorcd, sizechk, delivery_type, obj) {
        // 현재 scroll 위치를 저장해놓는다.
		var optionQuantity = $(obj).parent().parent().parent().html();
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
		$(".storeDataLayerFrame").attr("src", "/m/offline_inventory_new.php?mode=result&productcode=<?=$productcode?>&prodcd="+prodcd+"&colorcd="+colorcd+"&sizechk="+sizechk+"&delivery_type="+delivery_type+"&option_quantity="+optionQuantity);
        $("#storeDataLayer").show();
        initLayerPosition();
    }
	*/

    function initLayerPosition(){
        var width = (window.innerWidth || document.documentElement.clientWidth)-20; //우편번호서비스가 들어갈 element의 width
        var height = (window.innerHeight || document.documentElement.clientHeight)-200; //우편번호서비스가 들어갈 element의 height
        var borderWidth = 1; //샘플에서 사용하는 border의 두께

        // 위에서 선언한 값들을 실제 element에 넣는다.
        $("#storeDataLayer").css('width', width + 'px');
        $("#storeDataLayer").css('height', height + 'px');
        $("#storeDataLayer").css('border', borderWidth + 'px solid');
        // 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
        $("#storeDataLayer").css('left', (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px');
        $("#storeDataLayer").css('top', (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px');
    }



	/////////////////////////////////////////////////////////
	///////////////// 옵션의 인덱스값 셋팅 ///////////////
	/////////////////////////////////////////////////////////	
	function reSettingEqIndex(){
		var reIdx = 0;
		$(".hero-info-option-table tr").find(".addOptionStore").each(function(){
			$(this).attr("data-eqindex", reIdx);
			reIdx++;
		})
	}


	/////////////////////////////////////////////////////////
	//////////////////// 배송 타입 선택 ///////////////////
	/////////////////////////////////////////////////////////
	$(document).on('click', '.CLS_delivery_type', function(){
		if(global_delivery_type != $(this).val()){
			$(".CLS_option_value").val('');
			$("#quantity").val('1');
			allDeleteOptionValues();
		}
		global_delivery_type = $(this).val();
	})


	/////////////////////////////////////////////////////////
	//////////////////// 상품 옵션 삭제 ///////////////////
	/////////////////////////////////////////////////////////
	$(document).on('click', '.addOptionDelete', function(){
		$(this).parent().parent().parent().remove();
		
		reSettingEqIndex();
	})

	////////////////////////////////////////////////////////
	///////////////// 상품 옵션  전체 삭제 ///////////////
	////////////////////////////////////////////////////////
	function allDeleteOptionValues(){
		$(".hero-info-option-table").html('');
	}


	////////////////////////////////////////////////////////
	///////////////// 상품 옵션 재고 수정 ////////////////
	////////////////////////////////////////////////////////
	function quantityChange(flag, obj){
		var quantityObj = $(obj).parent().find("input[name='add_quantity[]']");

		var currQty = $(quantityObj).val();

		var stockSize = $(obj).parent().parent().parent().parent().find("input[name='add_option[]']").val();
		var stockStorecode = $(obj).parent().parent().parent().parent().find("input[name='store_code[]']").val();
		var stockProdcd = $("input[name='stock_prodcd']").val();
		var stockColorcd = $("input[name='stock_colorcd']").val();
		var stockQuantity = currQty;

		if(flag == '+'){
			stockQuantity = parseInt(currQty, 10) + 1;

		}else if(flag == '-'){
			if(currQty > 1){
				stockQuantity = parseInt(currQty, 10) - 1;
			}
		}

		$.ajax({
			type: "POST",
			url: "../front/ajax_get.reserve.date.php",
			data : { mode : 'stockChk', quantity : stockQuantity, prodcode : stockProdcd, colorcode : stockColorcd, size : stockSize, storecode : stockStorecode, flag : flag },
			dataType : 'json'
		}).done( function( data ){
			if(data.flag){
				$(quantityObj).val( data.quan );
			}else{
				alert(data.str);
				$(quantityObj).val( data.quan );
			}
		});
	}
	$(document).on('click', '.addOption-btn-plus', function(){
		quantityChange("+", $(this));
	})

	$(document).on('click', '.addOption-btn-minus', function(){
		quantityChange("-", $(this));
	})


	/////////////////////////////////////////////////////////////////////////////////
	///////// 구매 / 장바구니 액션시 픽업, 수령일시 필수값 추가 체크 /////////
	/////////////////////////////////////////////////////////////////////////////////
	function chkOptionFlag(){
		var msg = "";
		if(global_delivery_type == '1'){
			if($(".required_reservation_date").val()){
				msg = "succ";
			}else{
				msg = "수령일을 선택하지 않으셨습니다.";
			}
		}else if(global_delivery_type == '2'){
			if($("select[name='get_address']").val()){
				msg = "succ";
			}else{
				msg = "수령 주소를 입력하지 않으셨습니다.";
			}
		}else{
			msg = "succ";
		}

		return msg;
	}



	////////////////////////////////////////////////////
	///////// 선택 완료 매장명 레이어 출력 /////////
	///////////////////////////////////////////////////
	$(document).on('click', '.CLS_store_selection_done', function(){
		//$(this).prev().css("left", (parseInt($(".hero-info-option-table").offset().left, 10) + ($(this).prev().width() / 2)) + "px").show();
		$(this).prev().css("margin-left", "-"+ (parseInt($(this).prev().width(), 10)+25) + "px");
		$(this).prev().toggle();
	})
	/*
	$(document).on('mouseout', '.CLS_store_selection_done', function(){
		$(this).prev().hide();
	})*/





	// 쿠폰 다운로드
	$(document).on( 'click', '.CLS_coupon_download', function( event ) {
		var coupon = $(this).attr('data-coupon');
		var coupon_button = $(this);

		$.ajax({
			type: "POST",
			url: "../front/ajax_coupon_download_proc.php",
			data : { coupon : coupon },
			dataType : 'json'
		}).done( function( data ){
			alert(data.msg);
		});
	});

    /*function card_banner_pop(){
        $('.popup-layer').show();
        $('#popup-card').fadeIn();
    }

    function prcoupon_pop(){
        $.ajax({
            method : "POST",
            url : "download_coupon_layer.php",
            data : { productcode : _productcode },
            dataType : "html"
        }).done( function( msg ){
            $('#popup-coupon .popup-layer-content').html( msg );
            $('.popup-layer').show();
            $('#popup-coupon').fadeIn();
        });
        //$('.popup-layer').show();
        //$('#popup-coupon').fadeIn();
    }

    // 쿠폰 다운로드
    $(document).on( 'click', '.CLS_coupon_download', function( event ) {
        var coupon_code = $(this).attr('data-coupon');
        var coupon_button = $(this);
        var buttonHtml_target = coupon_button.parent();
        var buttonHtml = $(this)[0].outerHTML;
        var mem_coupon = true;

        coupon_button.remove();
        $('tr[name="TR_memcoupon"]').each( function( i, obj ) { // 같은 종류의 쿠폰이 존재 하는지 확인
            if( $(this).attr('data-code') == coupon_code ) {
                mem_coupon = false;
            }
        });

        if( coupon_code.length > 0 ) {
            $.ajax({
                type: "POST",
                url: "<?=$Dir.FrontDir?>ajax_coupon_download.php",
                data : { coupon_code : coupon_code },
                dataType : 'json'
            }).done( function( data ){
                if( data.success === true ){
                    alert('쿠폰이 발급 되었습니다.');
                    if( $('#ID_coupon_no').length > 0  ) $('#ID_coupon_no').remove();
                    if( mem_coupon ) $('#ID_coupon_layer').append( data.html );
                    if( data.next_down === true ) {
                        buttonHtml_target.html( buttonHtml );
                    } else {
                        buttonHtml_target.html( '최대 수량 보유' );
                    }
                } else {
                    alert('발급 가능한 쿠폰이 아닙니다.');
                }
            });
        } else {
            alert('발급 가능한 쿠폰이 아닙니다.');
        }
    });

    function sns_pop(){
        $('.popup-layer').show();
        $('#popup-sns').fadeIn();
    }*/

    function sns(select){

        var Link_url = "http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>";

        if(select =='facebook'){//페이스북
            var sns_url = "http://www.facebook.com/sharer.php?u="+encodeURIComponent(Link_url);
        }
        if(select =='twitter'){//트위터
            var text = "<?=$_data->shoptitle?>";
            var sns_url = "http://twitter.com/intent/tweet?text="+encodeURIComponent(text)+"&url="+ Link_url + "&img" ;
        }
        if( select == 'kakaostory' ){

            Kakao.Story.share({
              url: Link_url,
              text: "<?=addslashes($_pdata->productname)?>"
            });

        } else {
            var popup= window.open(sns_url,"_snsPopupWindow", "width=500, height=500");
            popup.focus();
        }
    }

//관련 포스팅
var $grid =  $('.posting-list').masonry({
	itemSelector: '.grid-item',
	columnWidth: '.grid-sizer',
	percentPosition: false
});
function postingList(page){
	var productcode = "<?=$productcode?>";
	var brand = "<?=$brand_name?>";	
	$(".posting-more").hide();
	$.ajax({
		type: "POST",
		url: "../front/ajax_posting_list.php",
		data: { productcode : productcode, brand : brand, view_type : 'm', page : page },
		dataType : "json",
		async: false,
		cache: false,
		error:function(request,status,error){
			alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		}
	}).done(function(data){
		if( !jQuery.isEmptyObject( data ) ){
			if(page == '1') $(".btn-posting").html("<strong>관련 포스팅</strong> ("+data.posting_total+")");
			//$(".posting-list").append(data.posting_html);
			var $elems = $( data.posting_html );
			$grid.append( $elems ).masonry( 'appended', $elems );
			if(data.posting_next_page == 'E')
			{
				$(".posting-more").hide();
			} else {
				$(".posting-more").html('<a href="javascript:postingList(\''+data.posting_next_page+'\')">더보기</a>');
				$(".posting-more").show();
			}
		}
	});
}

postingList('1');
</script>


<!-- 
<tr>
	<td>
		235 <input type="hidden" name="add_option[]" value="235">
	</td>
	<td style = 'padding:7px 0px;'>
		<div class="qty">
			<span class="quantity qty" style = 'margin:0px auto;'>
				<button class="minus btn-qty-subtract" type="button">감소</button>
				<input type="number" value="1" name='add_quantity[]' title="수량" readonly>
				<button class="plus btn-qty-add" type="button">증가</button>
			</span>
		</div>
	</td>
	<td>
		<div class="btn_option_delete_type">
			<a class="btn-type1 addOptionDelete" href="javascript:;">제거</a>
		</div>
	</td>
	<td>
		<div class="btn_option_delete_type">
			<a class="btn-type1 addOptionStore" data-delivery_type="2" href="javascript:;" data-eqindex="0">매장선택</a>
		</div>
	</td>
	<td>
		<div class="store_selection_area">
			<div class="CLS_store_selection_done_layer">매장명 : 핫티 여의도점<br>우편번호 : 01014<br>주소 : 서울 강북구 4.19로11길 7  12313123</div>
			<span class="CLS_store_selection_done" style="cursor:pointer;">[완료]</span>
			<input type="hidden" name="store_code[]" value="004350">
			<input type="hidden" name="post_code[]" value="01014">
			<input type="hidden" name="address1[]" value="서울 강북구 4.19로11길 7">
			<input type="hidden" name="address2[]" value="12313123">
		</div>
	</td>
</tr>


<tr>
	<td>
		235 <input type="hidden" name="add_option[]" value="235">
	</td>
	<td style = 'padding:7px 0px;'>
		<div class="qty">
			<span class="quantity qty" style = 'margin:0px auto;'>
				<input type="number" value="1" name='add_quantity[]' title="수량" readonly>
			</span>
		</div>
	</td>
	<td>
		<div class="btn_option_delete_type">
			<a class="btn-type1 addOptionDelete" href="javascript:;">제거</a>
		</div>
	</td>
	<!-- 
	<td>
		<div class="btn_option_delete_type">
			<a class="btn-type1 addOptionStore" data-delivery_type="2" href="javascript:;" data-eqindex="0">매장선택</a>
		</div>
	</td>
	<td>
		<div class="store_selection_area">
			<div class="CLS_store_selection_done_layer">매장명 : 핫티 여의도점<br>우편번호 : 01014<br>주소 : 서울 강북구 4.19로11길 7  12313123</div>
			<span class="CLS_store_selection_done" style="cursor:pointer;">[완료]</span>
			<input type="hidden" name="store_code[]" value="004350">
			<input type="hidden" name="post_code[]" value="01014">
			<input type="hidden" name="address1[]" value="서울 강북구 4.19로11길 7">
			<input type="hidden" name="address2[]" value="12313123">
		</div>
	</td>
</tr>
-->


<div id="storeDataLayer" style="display:none;position:fixed;overflow:hidden;z-index:9999;-webkit-overflow-scrolling:touch;">
	<img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;width:20px;right:0px;top:-1px;z-index:9999" onclick="closeStoreLayer()" alt="접기 버튼">
	<iframe frameborder="0" src="about:blank" style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%; border: 0px none; margin: 0px; padding: 0px; overflow: hidden; min-width: 300px;" class = "storeDataLayerFrame"></iframe>
</div>

<?php
include_once('./outline/footer_m.php')
?>
