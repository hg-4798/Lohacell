<?php
include_once('./outline/header_m.php');
include_once($Dir."lib/jungbo_code.php"); //정보고시 코드를 가져온다

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

	$imagepath=$Dir.DataDir."shopimages/multi/";
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
$imagepath_multi = $Dir.DataDir.'shopimages/multi/';
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
//상품의 조회순 , 등록날짜로 10개
$related_sql = "WITH related AS ( SELECT c_productcode  FROM tblproductlink  WHERE c_category like '". substr($_cdata->c_category, 0, 9) ."%' ";
$related_sql.= " AND c_maincate = 1 GROUP BY c_productcode ) ";
$related_sql.= "SELECT pr.productcode, pr.productname, pr.sellprice, ";
$related_sql.= "pr.consumerprice, pr.buyprice, pr.brand, pr.maximage, ";
$related_sql.= "pr.minimage, pr.tinyimage, pr.mdcomment, pr.review_cnt, ";
$related_sql.= "pr.icon, pr.soldout, pr.quantity, pr.over_minimage FROM tblproduct pr ";
$related_sql.= "JOIN related r ON pr.productcode = r.c_productcode ";
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

$related_sql.= "ORDER BY pr.vcnt DESC, date DESC LIMIT 6 ";

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
	$promo_link[] = "<a href='../front/promotion_detail.php?idx=".$promo_row->idx."'".$promo_target.">&gt; ".$promo_row->title."</a>";
}
pmysql_free_result( $promo_res );

#리뷰 베너
$review_banner = get_banner( 94 );

if( get_session( "ACCESS" ) == 'app' ) $couponType = 'T';
else $couponType = 'M';
#회원 쿠폰정보
$member_coupon = MemberCoupon( 1, $couponType );
#사용 가능한 쿠폰 정보
$possible_coupon = PossibleCoupon( $_pdata->productcode, $couponType );

//쿠폰 레이어팝업 내용
function CouponLayer( $member_coupon, $possible_coupon ){
	$member_layerHtml = array();
	$possible_layerHtml = array();
	$layerHtml = array();
	$mem_layerText = '';
	$possible_layerText = '';
	$tmpPossibelCoupon = $possible_coupon;
	$coupons = array();
    
	foreach( $member_coupon as $mcKey=>$mcVal ){
        if( !in_array( $mcVal->coupon_code, $coupons ) ){
            $coupons[] = $mcVal->coupon_code;
            $pricetype_text = CouponText( $mcVal->sale_type );
            $mem_layerText = "<tr name='TR_memcoupon' data-code='".$mcVal->coupon_code."' >";
            $mem_layerText.= "	<td>".$mcVal->coupon_name."</td>";
            $mem_layerText.= "	<td>".$mcVal->sale_money.' '.$pricetype_text['won']."</td>";
            $mem_layerText.= "	<td>";
            $mem_layerText.= "		".toDate( $mcVal->date_start, '-' )."<br>";
            $mem_layerText.= "		~ ".toDate( $mcVal->date_end, '-' );
            $mem_layerText.= "		</td>";
            $mem_layerText.= "</tr>";
            $member_layerHtml[] = $mem_layerText;
        }
	}

	foreach( $possible_coupon as $pcKey=>$pcVal ){
		$pricetype_text = CouponText( $pcVal->sale_type );
		$possible_layerText = "<tr>";
		$possible_layerText.= "	<td>".$pcVal->coupon_name."</td>";
		$possible_layerText.= "	<td>".$pcVal->sale_money.' '.$pricetype_text['won']."</td>";
		$possible_layerText.= "	<td>";
		$possible_layerText.= "	<button type='button' class='btn-dib-function CLS_coupon_download' data-coupon='".$pcVal->coupon_code."' >";
		$possible_layerText.= "		<span>쿠폰받기</span>";
		$possible_layerText.= "	</button>";
		$possible_layerText.= "		</td>";
		$possible_layerText.= "</tr>";
		$possible_layerHtml[] = $possible_layerText;
	}

	$layerHtml[] = $member_layerHtml;
	$layerHtml[] = $possible_layerHtml;

	return $layerHtml;

}
//쿠폰 레이어팝업 내용
$coupon_layer = CouponLayer( $member_coupon, $possible_coupon );

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
?>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<style>
	.share-layer {
		padding-top:10px;
		list-style:none;
		margin:0;
	}
	.share-layer li {
		border : 0;
		float: left;
	}
	.share-layer li img{
		width:40px;
		height:40px;
		padding: 0 5px 0 5px;
	}
</style>
<script src="/plugin/sns/shareJs/sns.js"></script>
<ul class = 'share-layer'>
	<input type = 'hidden' value = '<?="http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]?>' class = 'share-url'>
	<input type = 'hidden' value = '<?=$_pdata->productname?>' class = 'share-title'>
	<input type = 'hidden' value = '<?="[".$_pdata->productname."] 상품 본문 내용"?>' class = 'share-text'>
	<input type = 'hidden' value = '<?=$_pdata->maximage?>' class = 'share-img'>
	<input type = 'hidden' value = '76972bcac1a176814d7a18701f75defc' class = 'share-kakao-script'>


	<li><a href="javascript:;" class = 'facebook-btn'><img src="../images/share/share-facebook.png"/></a></li>
	<li><a href="javascript:;" class = 'twitter-btn'><img src="../images/share/share-twitter.png"/></a></li>
	<li><a href="javascript:;" class = 'instgram-btn'><img src="../images/share/share-instgram.png"/></a></li>
	<li><a href="javascript:;" class = 'kakao-story-btn'><img src="../images/share/share-kakao-story.png"/></a></li>
	<li><a href="javascript:;" class = 'kakao-talk-btn'><img src="../images/share/share-kakao-talk.png"/></a></li>
</ul>
<br><br><br><br><br>


<?php
include_once('./outline/footer_m.php')
?>
