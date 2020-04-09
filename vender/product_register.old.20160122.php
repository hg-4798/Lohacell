<?php
/********************************************************************* 
// 파 일 명    : product_register.php 
// 설     명   : 입점업체 상품관리
// 상세설명    : 검증된 입점업체가 상품을 등록
// 작 성 자    : hspark
// 수 정 자    : 2015.10.26 - 유동혁
//
*********************************************************************/ 
?>
<?php
#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include("access.php");

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
* 2015 10 30 유동혁
*/
function ProductThumbnail ( $prcode, $fileName, $upFile, $makeWidth, $makeHeight ){
	$imagepath = DirPath.DataDir."shopimages/product/".$prcode."/";
	$quality = "90";
	
	//exdebug( $prcode ); exdebug( $fileName ); exdebug( $upFile );
	if ( ord($fileName) && file_exists( $imagepath.$upFile ) ) {
		$imgname=$imagepath.$upFile; // 위치 + 파일명
		$size=getimageSize($imgname); //파일 사이즈 array ( 0=>width, 1=>height, 2=>imgtype )
		$width=$size[0];
		$height=$size[1];
		$imgtype=$size[2];

		if ($width > $makeWidth || $height > $makeHeight) {
			# 파일 타입별 이미지생성
			if($imgtype==1)      $im = ImageCreateFromGif($imgname);
			else if($imgtype==2) $im = ImageCreateFromJpeg($imgname);
			else if($imgtype==3) $im = ImageCreateFromPng($imgname);
			 # 파일의 넓이나 높이가 큰 부분을 기준으로 파일을 자른다
			 $small_width = $makeWidth;
			 $small_height = $makeHeight;

			 # 타입별로 파일 색상, 크기 리사이즈
			if ($imgtype==1) {
				$im2=ImageCreate($small_width,$small_height); // GIF일경우
				$white = ImageColorAllocate($im2, 255,255,255);
				imagefill($im2,1,1,$white);
				ImageCopyResized($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
				imageGIF($im2,$imgname);
			} else if ($imgtype==2) {
				$im2=ImageCreateTrueColor($small_width,$small_height); // JPG일경우
				$white = ImageColorAllocate($im2, 255,255,255);
				imagefill($im2,1,1,$white);
				imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
				imageJPEG($im2,$imgname,$quality);
			} else {
				$im2=ImageCreateTrueColor($small_width,$small_height); // PNG일경우
				$white = ImageColorAllocate($im2, 255,255,255);
				imagefill($im2,1,1,$white);
				imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
				imagePNG($im2,$imgname);
			}

			ImageDestroy($im);
			ImageDestroy($im2);
			chmod($imgname,0777);
		}
	}
}

#---------------------------------------------------------------
# 권한 및 등록갯수를 체크한다
#---------------------------------------------------------------
if($_venderdata->grant_product[0]!="Y") {
	alert_go("상품 등록 권한이 없습니다.\\n\\n쇼핑몰에 문의하시기 바랍니다.",-1);
}

if($_venderdata->product_max!=0) {
	$sql = "SELECT prdt_allcnt FROM tblvenderstorecount WHERE vender='".$_VenderInfo->getVidx()."' ";
	$result=pmysql_query($sql,get_db_conn());
	$row=pmysql_fetch_object($result);
	pmysql_free_result($result);
	$prdt_allcnt=$row->prdt_allcnt;

	if($_venderdata->product_max<=$prdt_allcnt) {
		alert_go("해당 미니샵에서 등록할 수 있는 상품갯수는 ".$_venderdata->product_max."개 입니다.\\n\\n다른상품을 삭제 후 등록하시거나 쇼핑몰에 문의하시기 바랍니다.",-1);
	}
}
#---------------------------------------------------------------
# 파일 및 초기설정
#---------------------------------------------------------------

$userspec_cnt=5;
# 파일 용량 3K -> 3M 변경
$maxfilesize="3072000";
$mode=$_POST["mode"];
$code=$_POST["code"];
$maxsize=130;
$makesize=130;

$sql = "SELECT predit_type,etctype FROM tblshopinfo ";
$result = pmysql_query($sql,get_db_conn());
if ($row=pmysql_fetch_object($result)) {
	$predit_type=$row->predit_type;
	if(strpos(" ".$row->etctype,"IMGSERO=Y")) {
		$imgsero="Y";
	}
} 
pmysql_free_result($result);

if(strlen($_POST["setcolor"])==0){
	$setcolor=$_COOKIE["setcolor"];
} else if($_COOKIE["setcolor"]!=$_POST["setcolor"]){
	SetCookie("setcolor",$setcolor,0,"/".RootPath.VenderDir);
	$setcolor=$_POST["setcolor"];
} else {
	$setcolor=$_COOKIE["setcolor"];
}

if(strlen($setcolor)==0) $setcolor="000000";
$rcolor=HexDec(substr($setcolor,0,2));
$gcolor=HexDec(substr($setcolor,2,2));
$bcolor=HexDec(substr($setcolor,4,2));
$quality = "90";

// 테두리 설정에 대한 부분을 쿠키로 고정시킨다.
if ($_POST["imgborder"]=="Y" && $_COOKIE["imgborder"]!="Y") {
	SetCookie("imgborder","Y",0,"/".RootPath.VenderDir);
} else if ($_POST["imgborder"]!="Y" && $_COOKIE["imgborder"]=="Y" && $mode=="insert") {
	SetCookie("imgborder","",time()-3600,"/".RootPath.VenderDir);
	$imgborder="";
} else {
	$imgborder=$_COOKIE["imgborder"];
}
// 쿠키 끝

#---------------------------------------------------------------
# 상품등록 INSERT
#---------------------------------------------------------------

if($mode=="insert") {
	
	# 진열 코드 추가 2015 10 23 유동혁
	list($code_a,$code_b,$code_c,$code_d) = sscanf($code,'%3s%3s%3s%3s');
	if(strlen($code_a)!=3) $code_a="000";
	if(strlen($code_b)!=3) $code_b="000";
	if(strlen($code_c)!=3) $code_c="000";
	if(strlen($code_d)!=3) $code_d="000";
	$code = $code_a.$code_b.$code_c.$code_d;

	//분류 확인
	$sql = "SELECT type FROM tblproductcode WHERE code_a='".substr($code,0,3)."' AND code_b='".substr($code,3,3)."' ";
	$sql.= "AND code_c='".substr($code,6,3)."' AND code_d='".substr($code,9,3)."' ";
	
	$result=pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)) {
		if(substr($row->type,-1)!="X") {
			echo "<html></head><body onload=\"alert('상품을 등록할 분류 선택이 잘못되었습니다.')\"></body></html>";exit;
		}
	} else {
		echo "<html></head><body onload=\"alert('상품을 등록할 분류 선택이 잘못되었습니다.')\"></body></html>";exit;
	}
	pmysql_free_result($result);

	$productname=$_POST["productname"];

	
	# 정보고시 2015 10 23 유동혁
	$sabangnet_prop_val=$_POST["sabangnet_prop_val"];
	$sabangnet_prop_option = $_POST['sabangnet_prop_option'];
	# 옵션 컬럼 변경 2015-10-23 유동혁
	$opt1_subject = $_POST["opt1_subject"];
	$opt1 = $_POST["opt1"];
	$opt2_subject = $_POST["opt2_subject"];
	$opt2 = $_POST["opt2"];
	$opt_id = $_POST["opt_id"];
	$opt_price = $_POST["opt_price"];
	$opt_stock_qty = $_POST["opt_stock_qty"];
	$opt_use = $_POST["opt_use"];

	/*
	$option1=$_POST["option1"];
	$option1_name=$_POST["option1_name"];
	$option2=$_POST["option2"];
	$option2_name=$_POST["option2_name"];
	*/

	# 옵션 컬럼 변경
	$option1 = $opt1;
	$option1_name = $opt1_subject;
	$option2 = $opt2;
	$option2_name = $opt2_subject;

	# 추가옵션 추가 2015 10 28 유동혁
	$spl_option_code = $_POST['spl_id'];
	$spl_option_price = $_POST['spl_price'];
	$spl_option_quantity = $_POST['spl_stock_qty'];
	$spl_option_use = $_POST['spl_use'];
	$spl_option_subject = $_POST['spl_subject'];
	$spl_option_name = $_POST['spl'];

	# 카테고리 추가
	$category=$_POST["category"];

	$consumerprice=$_POST["consumerprice"];
	$buyprice=$_POST["buyprice"];
	$sellprice=$_POST["sellprice"];
	$production=$_POST["production"];
	$keyword=$_POST["keyword"];
	$quantity=$_POST["quantity"];
	$checkquantity=$_POST["checkquantity"];
	$reserve=$_POST["reserve"];
	# rserve not-null
	if( is_null($reserve) || $reserve == '' ) $reserve = 0;
	$reservetype=$_POST["reservetype"];
	$deli=$_POST["deli"];
	#수량별 배송비 증가 추가 2015 12 04 유동혁
	$deli_qty = (int)$_POST['deli_qty'];
	if( $deli_qty < 0 || is_null($deli_qty) ) $deli_qty = 0;

	if($deli=="Y"){
		$deli_price=(int)$_POST["deli_price_value2"];
	} else if ( $deli=="Z" ) { // 수량별 배송비 추가 2015 12 04 유동혁
		$deli_price=(int)$_POST["deli_price_value3"];
	} else {
		$deli_price=(int)$_POST["deli_price_value1"];
	}
	if($deli=="H" || $deli=="F" || $deli=="G") $deli_price=0;
	if($deli!="Y" && $deli!="F" && $deli!="G" && $deli!="Z" ) $deli="N";
	/*
	if($deli=="Y")
		$deli_price=(int)$_POST["deli_price_value2"];
	else
		$deli_price=(int)$_POST["deli_price_value1"];
	if(in_array($deli,array('H','F','G'))) $deli_price=0;
	if(!in_array($deli,array('Y','F','G'))) $deli="N";
	*/
	$display=$_POST["display"];
	$addcode=$_POST["addcode"];
	//$option_price=str_replace(" ","",$_POST["option_price"]);
	//$option_price=rtrim($option_price,',');
	$madein=$_POST["madein"];
	$model=$_POST["model"];
	$brandname=$_POST["brandname"];
	$opendate=$_POST["opendate"];
	$selfcode=$_POST["selfcode"];
	$imgcheck=$_POST["imgcheck"];
	$deliinfono=$_POST["deliinfono"];	// 배송/교환/환불정보 노출안함 (Y)
	$checkmaxq=$_POST["checkmaxq"]; // 최대주문수량 무제한 / 수량제한
	$miniq=$_POST["miniq"];			// 최소주문가능
	$maxq=$_POST["maxq"];			// 최대주문가능
	$content=$_POST["content"];

	$userspec=$_POST["userspec"];
	$specname=$_POST["specname"];
	$specvalue=$_POST["specvalue"];

	$group_check=$_POST["group_check"];
	$group_code=$_POST["group_code"];

	if($group_check=="Y" && count($group_code)>0) {
		$group_check="Y";
	} else {
		$group_check="N";
		$group_code="";
	}

	$specarray=array();
	if($userspec == "Y") {
		for($i=0; $i<$userspec_cnt; $i++) {
			$specarray[$i]=$specname[$i]."".$specvalue[$i];
		}
		$userspec = implode("=",$specarray);
	} else {
		$userspec = "";
	}

	if(strlen($display)==0) $display="Y";
	
	if((int)$opendate<1) $opendate="";
	/* 옵션 변경 2015 10 23 유동혁
	$searchtype=$_POST["searchtype"];
	if(strlen($searchtype)==0) $searchtype=0;
	*/
	$userfile = $_FILES["userfile"];
	$userfile2 = $_FILES["userfile2"];
	$userfile3 = $_FILES["userfile3"];

	$etctype = "";
	# etctype 초기화
	$up_bankonly = 'N';
	$up_deliinfono = 'N';
	$up_setquota = 'N';
	$up_icon = '';
	$up_dicker = '';
	$up_miniq = 0;
	$up_maxq = 0;
	if ($bankonly=="Y") {
		$etctype .= "BANKONLY";
		$up_bankonly = 'Y';
	}
	if ($deliinfono=="Y") {
		$etctype .= "DELIINFONO=Y";
		$up_deliinfono = 'Y';
	}
	if ($setquota=="Y") {
		$etctype .= "SETQUOTA";
		$up_setquota = 'Y';
	}
	if (strlen(substr($iconvalue,0,3))>0) {
		$etctype .= "ICON=".$iconvalue."";
		$up_icon = $iconvalue;
	}
	if ($dicker=="Y" && strlen($dicker_text)>0) {
		$etctype .= "DICKER=".$dicker_text."";
		$up_dicker = $dicker_text;
	}

	if ($miniq>1) {
		$etctype .= "MINIQ=".$miniq."";
		$up_miniq = $miniq;
	} else if ($miniq<1) {
		echo "<html></head><body onload=\"alert('최소주문한도 수량은 1개 보다 커야 합니다.')\"></body></html>";exit;
	}
	if ($checkmaxq=="B" && $maxq>=1) {
		$etctype .= "MAXQ=".$maxq."";
		$up_maxq = $maxq;
	} else if ($checkmaxq=="B" && $maxq<1) {
		echo "<html></head><body onload=\"alert('최대주문한도 수량은 1개 보다 커야 합니다.')\"></body></html>";exit;
	}

	$imagepath=$Dir.DataDir."shopimages/product/";

	
	if (strlen($option1)>0 && strlen($option1_name)>0) {
		$option1 = $option1_name.",".rtrim($option1,',');
	} else {
		$option1="";
	}
	if (strlen($option2)>0 && strlen($option2_name)>0) {
		$option2 = $option2_name.",".rtrim($option2,',');
	} else {
		$option2="";
	}
	
	// 추가옵션의 이름을 합친다 2015 10 28 유동혁

	if( count($spl_option_subject) > 0 ){
		$supply_subject = implode(',', $spl_option_subject );
	} else {
		$supply_subject = '';
	}

	$sql = "SELECT MAX(productcode) as maxproductcode FROM tblproduct ";
	$sql.= "WHERE productcode LIKE '".$code."%' ";
	$result = pmysql_query($sql,get_db_conn());
	if ($rows = pmysql_fetch_object($result)) {
		if (strlen($rows->maxproductcode)==18) {
			$productcode = ((int)substr($rows->maxproductcode,12))+1;
			$productcode = sprintf("%06d",$productcode);
		} else if($rows->maxproductcode==NULL){
			$productcode = "000001";
		} else {
			echo "<html></head><body onload=\"alert('상품코드를 생성하는데 실패했습니다. 잠시후 다시 시도하세요.')\"></body></html>";exit;
		}
		pmysql_free_result($result);
	}else {
		$productcode = "000001";
	}

	$image_name = $code.$productcode;

	$file_size = $userfile[size]+$userfile2[size]+$userfile3[size];

	if($file_size < $maxfilesize) {
		if (strlen($reserve)==0) {
			$reserve=0;
		} else {
			$reserve=$reserve*1;
		}

		if ($reservetype!="Y") {
			$reservetype=="N";
		}

		$curdate = date("YmdHis");

		$productname = str_replace("\\\\'","''",$productname);
		$addcode = str_replace("\\\\'","''",$addcode);
		$content = str_replace("\\\\'","''",$content);

		$message="";

		if($imgcheck=="Y") $filename = array (&$userfile[name],&$userfile[name],&$userfile[name]); 
		else $filename = array (&$userfile[name],&$userfile2[name],&$userfile3[name]);
		$file = array (&$userfile[tmp_name],&$userfile2[tmp_name],&$userfile3[tmp_name]);
		$vimagear = array (&$vimage,&$vimage2,&$vimage3);
		$imgnum = array ("","2","3");
		
		#DB에 올릴 이미지 경로
		$up_ImagePath = array ( '', '', '', '' );
		# 이미지 지정사이즈 ( 유동혁 2015 10 29 );
		$thumbnailArr = array( 
			1=>array('width'=>390,'height'=>390),
			2=>array('width'=>205,'height'=>168), 
			3=>array('width'=>83,'height'=>68)
		);

		# 이미지 폴더 생성
		if( !is_dir( $imagepath.$image_name ) ){
			mkdir( $imagepath.$image_name, 0700 );
			chmod( $imagepath.$image_name, 0777 );
		}
		// 경로지정
		$imagepath2=$Dir.DataDir."shopimages/product/".$image_name."/";

		# 대이미지 수정일경우 원본이미지를 삭제 후 올림
		/*
		if ($mode=="modify" && ord($vimagear[0]) && ord($filename[0]) && file_exists($imagepath.$vimagear[0]) && file_exists() ) {
			unlink( $Dir.DataDir."shopimages/product/".$vimagear[0] );
		}
		*/
		# 원본 이미지 업로드
		if (ord($filename[0]) && file_exists($file[0])) {
			$ext = strtolower(pathinfo($filename[0],PATHINFO_EXTENSION));
			if(in_array($ext,array('gif','jpg'))) {
				$image[0] = $image_name.".".$ext;
				move_uploaded_file($file[0],$imagepath2.$image[0]);
				chmod($imagepath2.$image[0],0777);
			} else {
				$image[0]="";
			}
		} else {
			$image[0] = $vimagear[0];
		}
		
		#썸네일 생성
		if( $imgcheck=="Y" && ord( $image[0] ) ){
			for( $i = 1; $i < 4; $i++ ){
				if (ord($filename[($i-1)]) && file_exists($file[($i-1)])) {	//사용자 이미지 넣기
					# 기존 이미지가 존재할경우 삭제하고 넣는다
					if ($mode=="modify" && ord($vimagear[($i-1)]) && file_exists($imagepath.$vimagear[($i-1)])) { 
						unlink( $Dir.DataDir."shopimages/product/".$vimagear[($i-1)] );
					}
					$ext = strtolower(pathinfo($filename[($i-1)],PATHINFO_EXTENSION));
					if(in_array($ext,array('gif','jpg'))) {
						$image[$i] = $image_name."_thum_".$thumbnailArr[$i]['width']."X".$thumbnailArr[$i]['height'].".".$ext;
						move_uploaded_file($file[($i-1)],$imagepath2.$image[$i]);
						chmod($imagepath2.$image[$i],0777);
						$up_ImagePath[$i] = $image_name."/"; //DB에 업로드한 경로를 같이 넣어준다
					} else {
						$image[$i]="";
					}
				} else { // 썸네일 생성
					$image[$i] = $image_name."_thum_".$thumbnailArr[$i]['width']."X".$thumbnailArr[$i]['height'].".".$ext;
					copy($imagepath2.$image[0],$imagepath2.$image[$i]);
					# 썸네일 이미지 크기 리사이징
					ProductThumbnail ( $image_name, $filename[0], $image[$i], $thumbnailArr[$i]['width'],  $thumbnailArr[$i]['height'] );
					$up_ImagePath[$i] = $image_name."/"; //DB에 업로드한 경로를 같이 넣어준다
				}
			}
		} else { // 개별 업로드
			for( $i = 1; $i < 4; $i++ ){
				if (ord($filename[($i-1)]) && file_exists($file[($i-1)])) {	//사용자 이미지 넣기
					# 기존 이미지가 존재할경우 삭제하고 넣는다
					if ($mode=="modify" && ord($vimagear[($i-1)]) && file_exists($imagepath.$vimagear[($i-1)])) { 
						unlink( $Dir.DataDir."shopimages/product/".$vimagear[($i-1)] );
					}
					$ext = strtolower(pathinfo($filename[($i-1)],PATHINFO_EXTENSION));
					if(in_array($ext,array('gif','jpg'))) {
						$image[$i] = $image_name."_thum_".$i.".".$ext;
						move_uploaded_file($file[($i-1)],$imagepath2.$image[$i]);
						chmod($imagepath2.$image[$i],0777);
						$up_ImagePath[$i] = $image_name."/"; //DB에 업로드한 경로를 같이 넣어준다
					} else {
						$image[$i]="";
					}
				} else {
					$image[$i] = $vimagear[($i-1)];
				}
			}
		}

		/*
		for($i=0;$i<3;$i++){
			if (strlen($filename[$i])>0 && file_exists($file[$i])) {
				$ext = strtolower(pathinfo($filename[$i],PATHINFO_EXTENSION));
				if(in_array($ext,array('gif','jpg'))) {
					$image[$i] = $image_name.$imgnum[$i].".".$ext;
					move_uploaded_file($file[$i],$imagepath.$image[$i]);
					chmod($imagepath.$image[$i],0664);
				} else {
					$image[$i]="";
				}
			} else if($imgcheck=="Y" && strlen($filename[$i])>0) {
				$image[$i] =$image_name.$imgnum[$i].".".$ext;
				copy($imagepath.$image[0],$imagepath.$image[$i]);
			} else {
				$image[$i] = $vimagear[$i];
			}
		}
		if ($imgcheck=="Y" && strlen($filename[1])>0 && file_exists($imagepath.$image[1])) {
			$imgname=$imagepath.$image[1];
			$size=getimageSize($imgname);
			$width=$size[0];
			$height=$size[1];
			$imgtype=$size[2];
			$makesize1=300;
			if ($width>$makesize1 || $height>$makesize1) {
				if($imgtype==1)      $im = ImageCreateFromGif($imgname);
				else if($imgtype==2) $im = ImageCreateFromJpeg($imgname);
				else if($imgtype==3) $im = ImageCreateFromPng($imgname);
				if ($width>=$height) {
					$small_width=$makesize1;
					$small_height=($height*$makesize1)/$width;
				} else if($width<$height) {
					$small_width=($width*$makesize1)/$height;
					$small_height=$makesize1;
				}

				if ($imgtype==1) {
					$im2=ImageCreate($small_width,$small_height); // GIF일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					ImageCopyResized($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imageGIF($im2,$imgname);
				} else if ($imgtype==2) {
					$im2=ImageCreateTrueColor($small_width,$small_height); // JPG일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imageJPEG($im2,$imgname,$quality);
				} else {
					$im2=ImageCreateTrueColor($small_width,$small_height); // PNG일경우
					$white = ImageColorAllocate($im2, 255,255,255);
					imagefill($im2,1,1,$white);
					imagecopyresampled($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);
					imagePNG($im2,$imgname);
				}

				ImageDestroy($im);
				ImageDestroy($im2);
			}
		}
		if (strlen($filename[2])>0 && file_exists($imagepath.$image[2])) {
			$imgname=$imagepath.$image[2];
			$size=getimageSize($imgname);
			$width=$size[0];
			$height=$size[1];
			$imgtype=$size[2];
			$makesize2=200;
			$changefile="Y";
			if($imgsero=="Y") $leftmax=$makesize2;
			else $leftmax=$maxsize;
			if ($width>$maxsize || $height>$leftmax) {
				if($imgtype==1)      $im = ImageCreateFromGif($imgname);
				else if($imgtype==2) $im = ImageCreateFromJpeg($imgname);
				else if($imgtype==3) $im = ImageCreateFromPng($imgname);
				if ($width>=$height) {
					$small_width=$makesize;
					$small_height=($height*$makesize)/$width;
				} else if ($width<$height) {
					if ($imgsero=="Y") {
						$temwidth=$width;$temheight=$height;
						if ($temwidth>$makesize) {
							$temheight=($temheight*$makesize)/$temwidth;
							$temwidth=$makesize;
						}
						if ($temheight>$makesize2) {
							$temwidth=($temwidth*$makesize2)/$temheight;
							$temheight=$makesize2;
						}
						$small_width=$temwidth; $small_height=$temheight;
					} else {
						$small_width=($width*$makesize)/$height; $small_height=$makesize;
					}
				}

				if ($imgtype==1) {
					$im2=ImageCreate($small_width,$small_height); // GIF일경우
					// 홀수픽셀의 경우 검은줄을 흰색으로 바꾸기위해.
					$white = ImageColorAllocate($im2, 255,255,255); 
					imagefill($im2,1,1,$white);
					//$color = ImageColorAllocate ($im2, 0, 0, 0);
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
			} else if($imgborder=="Y") {
				if($imgtype==1)      $im = ImageCreateFromGif($imgname);
				else if($imgtype==2) $im = ImageCreateFromJpeg($imgname);
				else if($imgtype==3) $im = ImageCreateFromPng($imgname);
				if ($imgtype==1) {
					$color = ImageColorAllocate($im,$rcolor,$gcolor,$bcolor);
					//$color = ImageColorAllocate ($im, 0, 0, 0);
					imagerectangle ($im, 0, 0, $width-1, $height-1,$color );
					imageGIF($im,$imgname);
				} else if ($imgtype==2) {
					$color = ImageColorAllocate($im,$rcolor,$gcolor,$bcolor);
					imagerectangle ($im, 0, 0, $width-1, $height-1,$color );
					imageJPEG($im,$imgname,$quality);
				} else {
					$color = ImageColorAllocate($im,$rcolor,$gcolor,$bcolor);
					imagerectangle ($im, 0, 0, $width-1, $height-1,$color );
					imagePNG($im,$imgname);
				}
				ImageDestroy($im);
			}
		}
		*/
		if($checkquantity=="F") $quantity=999999999;
		else if($checkquantity=="E") $quantity=0;
		else if($checkquantity=="A") $quantity=-9999;
		if($optiongroup>0) {
			$option1="[OPTG".$optiongroup."]";
			$option2="";
			$option_price="";
		}

		if(strlen($buyprice) < 1 ) $buyprice = 0 ;
		$result = pmysql_query("SELECT COUNT(*) as cnt FROM tblproduct",get_db_conn());
		if ($row=pmysql_fetch_object($result)) $cnt = $row->cnt;
		else $cnt=0;
		pmysql_free_result($result);

		// productlink 테이블 입력 추가
		$date1=date("Ym");
		$date=date("dHis");

		$in=0;
		foreach($category as $k){
			if($in==0){
				$maincate="1";
			}else{
				$maincate="0";
			}
			$query="insert into tblproductlink (c_productcode,c_category,c_maincate,c_date,c_date_1,c_date_2,c_date_3,c_date_4) values ('".$code.$productcode."','".$k."','".$maincate."','".$date1.$date."','".$date1.$date."','".$date1.$date."','".$date1.$date."','".$date1.$date."')";

			pmysql_query($query);
			$in++;
		}

		$sql = "INSERT INTO tblproduct(productcode) VALUES ('".$code.$productcode."')";
		pmysql_query($sql,get_db_conn());

		$sql = "UPDATE tblproduct SET ";
		$sql.= "assembleuse		= 'N', ";
		$sql.= "assembleproduct	= '', ";
		$sql.= "productname		= '".$productname."', ";
		$sql.= "sellprice		= ".$sellprice.", ";
		$sql.= "consumerprice	= ".$consumerprice.", ";
		$sql.= "buyprice		= ".$buyprice.", ";
		$sql.= "reserve			= '".$reserve."', ";
		$sql.= "reservetype		= '".$reservetype."', ";
		$sql.= "production		= '".$production."', ";
		$sql.= "madein			= '".$madein."', ";
		$sql.= "model			= '".$model."', ";
		$sql.= "opendate		= '".$opendate."', ";
		$sql.= "selfcode		= '".$selfcode."', ";
		$sql.= "quantity		= ".$quantity.", ";
		$sql.= "group_check		= '".$group_check."', ";
		$sql.= "keyword			= '".$keyword."', ";
		$sql.= "addcode			= '".$addcode."', ";
		$sql.= "userspec		= '".$userspec."', ";
		$sql.= "maximage		= '".$up_ImagePath[1].$image[1]."', ";
		$sql.= "minimage		= '".$up_ImagePath[2].$image[2]."', ";
		$sql.= "tinyimage		= '".$up_ImagePath[3].$image[3]."', ";
		# 옵션관련 내용 변경 2015 10 23 유동혁
		$sql.= "option_quantity	= '".$optcnt."', ";
		$sql.= "option1			= '".$option1."', ";
		$sql.= "option2			= '".$option2."', ";
		# 추가옵션 추가 2015 10 28 유동혁
		$sql.= "supply_subject  = '".$supply_subject."', ";
		# 정보고시 추가
		$sql.= "sabangnet_prop_val		= '".$sabangnet_prop_val."', ";
		$sql.= "sabangnet_prop_option	= '".$sabangnet_prop_option."', ";

		$sql.= "etctype			= '".$etctype."', ";
		# etctype 내용 추가 2015 10 28 유동혁
		$sql.= "bankonly	= '{$up_bankonly}', ";
		$sql.= "deliinfono = '{$up_deliinfono}', ";
		$sql.= "icon =  '{$up_icon}', ";
		$sql.= "dicker = '{$up_dicker}', ";
		$sql.= "min_quantity = '{$up_miniq}', ";
		$sql.= "max_quantity = '{$up_maxq}', ";
		$sql.= "setquota = '{$up_setquota}', ";

		$sql.= "deli_price		= '".$deli_price."', ";
		$sql.= "deli			= '".$deli."', ";
		$sql.= "deli_qty		= '".$deli_qty."', ";
		if($_venderdata->grant_product[3]=="N") {
			$sql.= "display		= '".$display."', ";
		} else {
			$display="N";
			$sql.= "display		= 'N', ";	
		}
		$sql.= "date			= '".$curdate."', ";
		$sql.= "vender			= '".$_VenderInfo->getVidx()."', ";
		$sql.= "regdate			= now(), ";
		$sql.= "modifydate		= now(), ";
		$sql.= "content			= '".$content."' WHERE productcode='".$code.$productcode."' ";
		
		if($insert = pmysql_query($sql,get_db_conn())) {
			if(strlen($brandname)>0) { // 브랜드 관련 처리
				$result = pmysql_query("SELECT bridx FROM tblproductbrand WHERE brandname = '".$brandname."' ",get_db_conn());
				if ($row=pmysql_fetch_object($result)) {
					@pmysql_query("UPDATE tblproduct SET brand = '".$row->bridx."' WHERE productcode = '".$code.$productcode."'",get_db_conn());
				} else {
					$sql = "INSERT INTO tblproductbrand(brandname) VALUES ('".$brandname."') RETURNING bridx";
					if($row = @pmysql_fetch_array(pmysql_query($sql,get_db_conn()))) {
						$bridx = $row[0];
						if($bridx>0) {
							@pmysql_query("UPDATE tblproduct SET brand = '".$bridx."' WHERE productcode = '".$code.$productcode."'",get_db_conn());
						}
					}
				}
				pmysql_free_result($result);
			}

			if($group_check=="Y" && count($group_code)>0) {
				for($i=0; $i<count($group_code); $i++) {
					$sql = "INSERT INTO tblproductgroupcode(productcode,group_code) VALUES (
					'".$code.$productcode."', 
					'".$group_code[$i]."')";
					pmysql_query($sql,get_db_conn());
				}
			}

			$sql = "UPDATE tblvenderstorecount SET prdt_allcnt=prdt_allcnt+1 ";
			if($display=="Y") {
				$sql.= ",prdt_cnt=prdt_cnt+1 ";
			}
			$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
			pmysql_query($sql,get_db_conn());
/*			필요없는 기능 2015 10 23 유동혁
			$sql = "INSERT INTO tblvendercodedesign(
			vender		,
			code		,
			tgbn		,
			hot_used	,
			hot_dispseq	) VALUES (
			'".$_VenderInfo->getVidx()."', 
			'".substr($code,0,3)."', 
			'10', 
			'1', 
			'118')";
			@pmysql_query($sql,get_db_conn());
*/

			##### 옵션 테이블 추가 2015 - 10 - 23 유동혁
			$upOptQty = 0;
			if( count( $opt_id ) > 0 ){
				foreach( $opt_id as $optKey=>$optVal ){
					if( $opt_price[$optKey] == "" || is_null($opt_price[$optKey]) ) $opt_price[$optKey] = 0;
					if( $opt_stock_qty[$optKey] == "" || is_null($opt_stock_qty[$optKey]) ) $opt_stock_qty[$optKey] = 0;
					$optInsertSql = "INSERT INTO tblproduct_option ";
					$optInsertSql.= "( option_code, productcode, option_price, option_quantity, option_use ) ";
					$optInsertSql.= "VALUES ( '".$optVal."', '".$code.$productcode."', '".$opt_price[$optKey]."', '".$opt_stock_qty[$optKey]."', '".$opt_use[$optKey]."' ) ";
					pmysql_query($optInsertSql, get_db_conn());

					$upOptQty += $opt_stock_qty[$optKey];
				}
				// 제고 기준을 option quantity에 맞춘다
				if($checkquantity=="C") {
					pmysql_query( "UPDATE tblproduct SET quantity = '".$upOptQty."' WHERE productcode='".$code.$productcode."'", get_db_conn() );
				}
			}

			# 추가옵션 2015 10 28 유동혁
			if( count( $spl_option_code ) > 0 ){
				foreach( $spl_option_code as $splKey=>$splVal ) {
					if( $spl_option_price[$splKey] == "" || is_null($spl_option_price[$splKey]) ) $spl_option_price[$splKey] = 0;
					if( $spl_option_quantity[$splKey] == "" || is_null($spl_option_quantity[$splKey]) ) $spl_option_quantity[$splKey] = 0;					
					$spl_optInsertSql = "INSERT INTO tblproduct_option ";
					$spl_optInsertSql.= "( option_code, productcode, option_price, option_quantity, option_use, option_type ) ";
					$spl_optInsertSql.= "VALUES ( '".$splVal."', '".$code.$productcode."', '".$spl_option_price[$splKey]."', '".$spl_option_quantity[$splKey]."', '".$spl_option_use[$splKey]."', '1' ) ";
					pmysql_query($spl_optInsertSql, get_db_conn());
				}
			}

			#### 기타 파일 업로드 STR 2015 - 10 - 23 유동혁 ####
			for($i=1;$i<=10;$i++){
				$img_new="mulimg".sprintf("%02d",$i);
				$img_old="oldimg".sprintf("%02d",$i);

				${$img_new} = $_FILES["$img_new"];
				${$img_old} = $_POST["$img_old"];
			}
			$multiimagepath=$Dir.DataDir."shopimages/multi/";

			if ($mode=="insert" || $mode=="modify") {
				if(strlen($productcode)<18) $productcode = $code.$productcode;
				$query = " select count(*) from tblmultiimages where productcode='".$productcode."' ";
				$result = pmysql_query($query,get_db_conn());
				list($multiimage_cnt) = pmysql_fetch_array($result);
				$mode=!$multiimage_cnt?"insert":$mode;



				$oldfile = array ("01"=>&$oldimg01,"02"=>&$oldimg02,"03"=>&$oldimg03,"04"=>&$oldimg04,"05"=>&$oldimg05,"06"=>&$oldimg06,"07"=>&$oldimg07,"08"=>&$oldimg08,"09"=>&$oldimg09,"10"=>&$oldimg10);
				$filearray = array ("01"=>&$mulimg01["name"],"02"=>&$mulimg02["name"],"03"=>&$mulimg03["name"],"04"=>&$mulimg04["name"],"05"=>&$mulimg05["name"],"06"=>&$mulimg06["name"],"07"=>&$mulimg07["name"],"08"=>&$mulimg08["name"],"09"=>&$mulimg09["name"],"10"=>&$mulimg10["name"]);
				$filen = array ("01"=>&$mulimg01["tmp_name"],"02"=>&$mulimg02["tmp_name"],"03"=>&$mulimg03["tmp_name"],"04"=>&$mulimg04["tmp_name"],"05"=>&$mulimg05["tmp_name"],"06"=>&$mulimg06["tmp_name"],"07"=>&$mulimg07["tmp_name"],"08"=>&$mulimg08["tmp_name"],"09"=>&$mulimg09["tmp_name"],"10"=>&$mulimg10["tmp_name"]);
				if ($mode=="insert") {
					$sql = "INSERT INTO tblmultiimages(productcode) VALUES ('{$productcode}')";
					pmysql_query($sql,get_db_conn());
				} else {
					$sql = "SELECT size FROM tblmultiimages WHERE productcode = '{$productcode}' ";
					$result = pmysql_query($sql,get_db_conn());
					if ($row = pmysql_fetch_object($result)){
						if (strlen($row->size)!=0){
							$tmpsize=explode("",$row->size);
							$delsize = array("01"=>&$tmpsize[0],"02"=>&$tmpsize[1],"03"=>&$tmpsize[2],"04"=>&$tmpsize[3],"05"=>&$tmpsize[4],"06"=>&$tmpsize[5],"07"=>&$tmpsize[6],"08"=>&$tmpsize[7],"09"=>&$tmpsize[8],"10"=>&$tmpsize[9]);
						}
					}
				}
				$sql = "UPDATE tblmultiimages SET ";
				$file_size=0;
				for($i=1;$i<=10;$i++){
					$gbn=sprintf("%02d",$i);
					$image="";
					if (ord($filearray[$gbn])) {
						if (ord($filearray[$gbn]) && file_exists($filen[$gbn])) {
							$ext = strtolower(pathinfo($filearray[$gbn],PATHINFO_EXTENSION));
							$image = $gbn."_{$productcode}.".$ext;
							$imgname=$multiimagepath."s".$image;
							$file_size += filesize($filen[$gbn]);
							if($mode=="modify" && ord($oldfile[$gbn])) {
								proc_matchfiledel($multiimagepath."*".$oldfile[$gbn]);
							}
							move_uploaded_file($filen[$gbn],$multiimagepath.$image);
							chmod($multiimagepath.$image, 0604);
							copy($multiimagepath.$image,$imgname);
							chmod($imgname, 0604);
							$size=getimageSize($imgname);
							$width=$size[0];
							$height=$size[1];
							$imgtype=$size[2];
							$maxsize=90;
							if ($width>$maxsize || $height>$maxsize) {
								if ($imgtype==1) $im = ImageCreateFromGif($imgname);
								else if ($imgtype==2) $im = ImageCreateFromJpeg($imgname);
								else if( $imgtype==3) $im = ImageCreateFromPng($imgname);
								if ($width>=$height) {
									$small_width=$maxsize;
									$small_height=($height*$maxsize)/$width;
								} else if ($width<$height) {
									$small_width=($width*$maxsize)/$height;
									$small_height=$maxsize;
								}

								// GIF일경우
								if ($imgtype==1) $im2=ImageCreate($small_width,$small_height);
								// JPG일경우
								else $im2=ImageCreateTrueColor($small_width,$small_height);

								// 홀수픽셀의 경우 검은줄을 흰색으로 바꾸기위해.
								$white = ImageColorAllocate($im2, 255,255,255);
								imagefill($im2,1,1,$white);



								ImageCopyResized($im2,$im,0,0,0,0,$small_width,$small_height,$width,$height);

								if($imgtype==1) imageGIF($im2,$imgname);
								else if($imgtype==2) imageJPEG($im2,$imgname);
								else if($imgtype==3) imagePNG($im2,$imgname);
								ImageDestroy($im);
								ImageDestroy($im2);
							}
						}
					} else if (ord($oldfile[$gbn])) {
						$image=$oldfile[$gbn];
					}
					if (ord($image)) {
						if ($mode=="insert") {
							$sql.= "primg{$gbn} = '{$image}',";
							$imgsize.="{$width}X".$height;
						} else {
							$sql.= "primg{$gbn} = '{$image}',";
							if (ord($filearray[$gbn])) $imgsize.="{$width}X".$height;
							else $imgsize.="".$delsize[$gbn];
						}
					} else {
						$sql.= "primg{$gbn} = '',";
						$imgsize.="";
					}
				}
				$imgsize=substr($imgsize,1);
				$sql.= "size = '{$imgsize}' ";
				$sql.= " WHERE productcode = '{$productcode}' ";
				pmysql_query($sql,get_db_conn());
			}
			#### 기타 파일 업로드 END ####


            // 2016-01-04 jhjeong 사방넷 정옥정씨 요청으로 onload 태그 body 사이에 상품코드 추가 처리.
			$onload="<html></head><body onload=\"alert('상품 등록이 완료되었습니다.');parent.location.href='".$_SERVER[PHP_SELF]."'\">".$productcode."</body></html>";

			$log_content = "## 상품입력 ## - 코드 $code$productcode - 상품 : $productname 가격 : $sellprice 수량 : $quantity 기타 : $etctype 적립금: $reserve $display";
			$_VenderInfo->ShopVenderLog($_VenderInfo->getVidx(),$connect_ip,$log_content);
		} else {
			$onload="<html></head><body onload=\"alert('상품 등록중 오류가 발생하였습니다.')\"></body></html>";
		}
		$prcode=$code.$productcode;
	} else {
		$onload="<html></head><body onload=\"alert('상품이미지의 총 용량이 ".ceil($file_size/1024)
		."Mbyte로 3M가 넘습니다.\\n\\n한번에 올릴 수 있는 최대 용량은 3M입니다.\\n\\n"
		."이미지가 gif가 아니면 이미지 포맷을 바꾸어 올리시면 용량이 줄어듭니다.')\"></body></html>";
	}

	echo $onload; exit;
}

include("header.php"); 

#---------------------------------------------------------------
# 카테고리 리스트 script 작성
#---------------------------------------------------------------

$sql = "SELECT code_a, code_b, code_c, code_d, type, code_name FROM tblproductcode WHERE group_code!='NO' ";
$sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') ORDER BY sequence DESC ";
$i=0;
$ii=0;
$iii=0;
$iiii=0;
$strcodelist = "";
$strcodelist.= "<script>\n";
$result = pmysql_query($sql,get_db_conn());
$selcode_name="";

while($row=pmysql_fetch_object($result)) {
	$strcodelist.= "var clist=new CodeList();\n";
	$strcodelist.= "clist.code_a='{$row->code_a}';\n";
	$strcodelist.= "clist.code_b='{$row->code_b}';\n";
	$strcodelist.= "clist.code_c='{$row->code_c}';\n";
	$strcodelist.= "clist.code_d='{$row->code_d}';\n";
	$strcodelist.= "clist.type='{$row->type}';\n";
	$strcodelist.= "clist.code_name='{$row->code_name}';\n";
	if($row->type=="L" || $row->type=="T" || $row->type=="LX" || $row->type=="TX") {
		$strcodelist.= "lista[{$i}]=clist;\n";
		$i++;
	}
	if($row->type=="LM" || $row->type=="TM" || $row->type=="LMX" || $row->type=="TMX") {
		if ($row->code_c=="000" && $row->code_d=="000") {
			$strcodelist.= "listb[{$ii}]=clist;\n";
			$ii++;
		} else if ($row->code_d=="000") {
			$strcodelist.= "listc[{$iii}]=clist;\n";
			$iii++;
		} else if ($row->code_d!="000") {
			$strcodelist.= "listd[{$iiii}]=clist;\n";
			$iiii++;
		}
	}
	$strcodelist.= "clist=null;\n\n";
}
pmysql_free_result($result);
$strcodelist.= "CodeInit();\n";
$strcodelist.= "</script>\n";


$codeA_list = "<select name=code_a id=code_a style=\"width:150px; height:150px\" onchange=\"SearchChangeCate(this,1)\" {$disabled} Multiple>\n";
$codeA_list.= "<option value=\"\">〓〓 1차 카테고리 〓〓</option>\n";
$codeA_list.= "</select>\n";

$codeB_list = "<select name=code_b id=code_b style=\"width:150px; height:150px\" onchange=\"SearchChangeCate(this,2)\" {$disabled} Multiple>\n";
$codeB_list.= "<option value=\"\">〓〓 2차 카테고리 〓〓</option>\n";
$codeB_list.= "</select>\n";

$codeC_list = "<select name=code_c id=code_c style=\"width:150px; height:150px\" onchange=\"SearchChangeCate(this,3)\" {$disabled} Multiple>\n";
$codeC_list.= "<option value=\"\">〓〓 3차 카테고리 〓〓</option>\n";
$codeC_list.= "</select>\n";

$codeD_list = "<select name=code_d id=code_d style=\"width:150px; height:150px\" {$disabled} Multiple>\n";
$codeD_list.= "<option value=\"\">〓〓 4차 카테고리 〓〓</option>\n";
$codeD_list.= "</select>\n";

$codeSelect = "<span style=\"display:\" name=\"changebutton\"><input type=\"button\" value=\"선택\" style=\"height : 20px;\" onclick=\"javascript:exec_add()\"></span>";

// 스크립트 작성완료

?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="PrdtRegist.js.php"></script>
<script type="text/javascript" src="../js/jquery-1.10.1.js" ></script>
<script type="text/javascript" src="<?=$Dir?>lib/DropDown.admin.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>LH.add("parent_resizeIframe('AddFrame')");</script>
<script language="JavaScript">

// 카테고리 선택 추가 2015 10 26 유동혁
function exec_add()
{

	var ret;
	var str = new Array();
	var code_a=document.form1.code_a.value;
	var code_b=document.form1.code_b.value;
	var code_c=document.form1.code_c.value;
	var code_d=document.form1.code_d.value;

	if(!code_a) code_a="000";
	if(!code_b) code_b="000";
	if(!code_c) code_c="000";
	if(!code_d) code_d="000";
	sumcode=code_a+code_b+code_c+code_d;
	$.ajax({
		type: "POST",
		url: "../admin/product_register.ajax.php",
		data: "code_a="+code_a+"&code_b="+code_b+"&code_c="+code_c+"&code_d="+code_d
	}).done(function(msg) {
	if(msg=='nocate'){
		alert("상품카테고리 선택이 잘못되었습니다.");
//		$("#catenm").html(msg);

	}else if(msg=='nolowcate'){
		alert("하위카테고리가 존재합니다.");
	//	$("#catenm").html("상품카테고리 선택이 잘못되었습니다.");
	}else{
	document.form1.code.value=sumcode;
	var code_a=document.getElementById("code_a");
	var code_b=document.getElementById("code_b");
	var code_c=document.getElementById("code_c");
	var code_d=document.getElementById("code_d");

	if(code_a.value){
		str[0]=code_a.options[code_a.selectedIndex].text;
	}
	if(code_b.value){
		str[1]=code_b.options[code_b.selectedIndex].text;
	}
	if(code_c.value){
		str[2]=code_c.options[code_c.selectedIndex].text;
	}
	if(code_d.value){
		str[3]=code_d.options[code_d.selectedIndex].text;
	}
	var obj = document.getElementById('Category_table');
	oTr = obj.insertRow();

	oTd = oTr.insertCell(0);
	oTd.id = "cate_name";
	oTd.innerHTML = str.join(" > ");
	oTd = oTr.insertCell(1);
	oTd.innerHTML = "\
	<input type=text name=category[] value='" + sumcode + "' style='display:none'>\
	";
	oTd = oTr.insertCell(2);
	oTd.innerHTML = "<a href='javascript:void(0)' onClick='cate_del(this.parentNode.parentNode)'><img src='../admin/img/btn/btn_cate_del01.gif' align=absmiddle></a>";


	}

	});
}
// 카테고리 삭제 추가 2015 10 26 유동혁
function cate_del(el)
{
	idx = el.rowIndex;
	var obj = document.getElementById('Category_table');
	obj.deleteRow(idx);
}


function formSubmit(mode) {
	var sHTML = oEditors.getById["ir1"].getIR();
	document.form1.content.value=sHTML;
	if( document.form1.code.value.length < 3 ) {
		codelen=document.form1.code.value.length;
		if(codelen==0) {
			alert("상품을 등록할 대분류를 선택하세요.");
			document.form1.code1.focus();
		} 
		return;
	}
	if (document.form1.productname.value.length==0) {
		alert("상품명을 입력하세요.");
		document.form1.productname.focus();
		return;
	}
	if (CheckLength(document.form1.productname)>100) {
		alert('총 입력가능한 길이가 한글 50자까지입니다. 다시한번 확인하시기 바랍니다.');
		document.form1.productname.focus();
		return;
	}
	if (document.form1.consumerprice.value.length==0) {
		alert("소비자가격을 입력하세요.");
		document.form1.consumerprice.focus();
		return;
	}
	if (isNaN(document.form1.consumerprice.value)) {
		alert("소비자가격을 숫자로만 입력하세요.(콤마제외)");
		document.form1.consumerprice.focus();
		return;
	}
	if (document.form1.sellprice.value.length==0) {
		alert("판매가격을 입력하세요.");
		document.form1.sellprice.focus();
		return;
	}
	if (isNaN(document.form1.sellprice.value)) {
		alert("판매가격을 숫자로만 입력하세요.(콤마제외)");
		document.form1.consumerprice.focus();
		return;
	}
	if (document.form1.reserve.value.length>0) {
		if(document.form1.reservetype.value=="Y") {
			if(isDigitSpecial(document.form1.reserve.value,".")) {
				alert("적립률은 숫자와 특수문자 소수점\(.\)으로만 입력하세요.");
				document.form1.reserve.focus();
				return;
			}
			
			if(getSplitCount(document.form1.reserve.value,".")>2) {
				alert("적립률 소수점\(.\)은 한번만 사용가능합니다.");
				document.form1.reserve.focus();
				return;
			}

			if(getPointCount(document.form1.reserve.value,".",2)) {
				alert("적립률은 소수점 이하 둘째자리까지만 입력 가능합니다.");
				document.form1.reserve.focus();
				return;
			}

			if(Number(document.form1.reserve.value)>100 || Number(document.form1.reserve.value)<0) {
				alert("적립률은 0 보다 크고 100 보다 작은 수를 입력해 주세요.");
				document.form1.reserve.focus();
				return;
			}
		} else {
			if(isDigitSpecial(document.form1.reserve.value,"")) {
				alert("적립금은 숫자로만 입력하세요.");
				document.form1.reserve.focus();
				return;
			}
		}
	}
	if (document.form1.checkquantity[2].checked) {
		if (document.form1.quantity.value.length==0) {
			alert("수량을 입력하세요.");
			document.form1.quantity.focus();
			return;
		} else if (isNaN(document.form1.quantity.value)) {
			alert("수량을 숫자로만 입력하세요.");
			document.form1.quantity.focus();
			return;
		} else if (parseInt(document.form1.quantity.value)<=0) {
			alert("수량은 0개이상이여야 합니다.");
			document.form1.quantity.focus();
			return;
		}
	}
	miniq_obj=document.form1.miniq;
	maxq_obj=document.form1.maxq;
	if (miniq_obj.value.length>0) {
		if (isNaN(miniq_obj.value)) {
			alert ("최소주문한도는 숫자로만 입력해 주세요.");
			miniq_obj.focus();
			return;
		}
	}
	if (document.form1.checkmaxq[1].checked) {
		if (maxq_obj.value.length==0) {
			alert ("최대주문한도의 수량을 입력해 주세요.");
			maxq_obj.focus();
			return;
		} else if (isNaN(maxq_obj.value)) {
			alert ("최대주문한도의 수량을 숫자로만 입력해 주세요.");
			maxq_obj.focus();
			return;
		}
	}
	if (miniq_obj.value.length>0 && document.form1.checkmaxq[1].checked && maxq_obj.value.length>0) {
		if (parseInt(miniq_obj.value) > parseInt(maxq_obj.value)) {
			alert ("최소주문한도는 최대주문한도 보다 작아야 합니다.");
			miniq_obj.focus();
			return;
		}
	}
	if(document.form1.deli[3].checked || document.form1.deli[4].checked) {
		if(document.form1.deli[3].checked)
		{
			if (document.form1.deli_price_value1.value.length==0) {
				alert("개별배송비를 입력하세요.");
				document.form1.deli_price_value1.focus();
				return;
			} else if (isNaN(document.form1.deli_price_value1.value)) {
				alert("개별배송비는 숫자로만 입력하세요.");
				document.form1.deli_price_value1.focus();
				return;
			} else if (parseInt(document.form1.deli_price_value1.value)<=0) {
				alert("개별배송비는 0원 이상 입력하셔야 합니다.");
				document.form1.deli_price_value1.focus();
				return;
			}
		}
		else
		{
			if (document.form1.deli_price_value2.value.length==0) {
				alert("개별배송비를 입력하세요.");
				document.form1.deli_price_value2.focus();
				return;
			} else if (isNaN(document.form1.deli_price_value2.value)) {
				alert("개별배송비는 숫자로만 입력하세요.");
				document.form1.deli_price_value2.focus();
				return;
			} else if (parseInt(document.form1.deli_price_value2.value)<=0) {
				alert("개별배송비는 0원 이상 입력하셔야 합니다.");
				document.form1.deli_price_value2.focus();
				return;
			}
		}
	}

	//정보고지
	if(document.form1.prop_type.value != ""){

		var prop_val = document.form1.prop_type.value;
		if(document.form1.prop_type.value == "001"){
			for(var ix=1; ix<13; ix++){
				prop_val += "||" + $('#prop001'+ix).val();
			}
		}
		var prop_opt_val = document.form1.prop_type.value;
		if(document.form1.prop_type.value == "001"){
			for(var ix=1; ix<13; ix++){
				prop_opt_val += "||" + $('#prop_opt001'+ix).val();
			}
		}
		//alert(prop_val);
		document.form1.sabangnet_prop_option.value = prop_opt_val;
		document.form1.sabangnet_prop_val.value = prop_val;
	}

	tempcontent = document.form1.content.value;
	document.form1.iconvalue.value="";
	num = document.form1.iconnum.value;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked) document.form1.iconvalue.value+=document.form1.icon[i].value;
	}
	if(mode=="preview") {
		alert("미리보기 준비중....");
	} else {
		if(confirm("상품을 등록하시겠습니까?")) {
			document.form1.mode.value=mode;
			document.form1.action="<?=$_SERVER['PHP_SELF']?>";
			//document.form1.target="processFrame";
			document.form1.submit();
		}
	}
}
</script>

<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckChoiceIcon(no){
	num = document.form1.iconnum.value;
	iconnum=0;
	for(i=0;i<num;i++){
		if(document.form1.icon[i].checked) iconnum++;
	}
	if(iconnum>3){
		alert('아이콘 꾸미기는 한상품에 3개까지 등록 가능합니다.');
		document.form1.icon[no].checked=false;
	}
}

function PrdtAutoImgMsg(){
	if(document.form1.imgcheck.checked) alert('상품 중간/작은 이미지가 큰이미지에서 자동 생성됩니다.\n\n기존의 중간/작은 이미지는 삭제됩니다.');
}


function SelectColor(){
	setcolor = document.form1.setcolor.value;
	var newcolor = showModalDialog("select_color.php?color="+setcolor, "oldcolor", "resizable: no; help: no; status: no; scroll: no;");
	if(newcolor){
		document.form1.setcolor.value=newcolor;
		document.all.ColorPreview.style.backgroundColor = '#' + newcolor;
	}
}

function userspec_change(val) {
	if(document.getElementById("userspecidx")) {
		if(val == "Y") {
			document.getElementById("userspecidx").style.display ="";
		} else {
			document.getElementById("userspecidx").style.display ="none";
		}
	}
}

function GroupCode_Change(val) {
	if(document.getElementById("group_checkidx")) {
		if(val == "Y") {
			document.getElementById("group_checkidx").style.display ="";
		} else {
			document.getElementById("group_checkidx").style.display ="none";
		}
	}
}

function GroupCodeAll(checkval,checkcount) {
	for(var i=0; i<checkcount; i++) {
		if(document.getElementById("group_code_idx"+i)) {
			document.getElementById("group_code_idx"+i).checked = checkval;
		}
	}
}

function BrandSelect() {
	window.open("product_brandselect.php","brandselect","height=400,width=420,scrollbars=no,resizable=no");
}

function FiledSelect(pagetype) {
	window.open("product_select.php?type="+pagetype,pagetype,"height=400,width=420,scrollbars=no,resizable=no");
}

function chkFieldMaxLenFunc(thisForm,reserveType) {
	if (reserveType=="Y") { max=5; addtext="/특수문자(소수점)";} else { max=6; }
	if (thisForm.reserve.value.bytes() > max) {
		alert("입력할 수 있는 허용 범위가 초과되었습니다.\n\n" + "숫자"+addtext+" " + max + "자 이내로 입력이 가능합니다.");
		thisForm.reserve.value = thisForm.reserve.value.cut(max);
		thisForm.reserve.focus();
	}
}

function getSplitCount(objValue,splitStr)
{
	var split_array = new Array();
	split_array = objValue.split(splitStr);
	return split_array.length;
}

function getPointCount(objValue,splitStr,falsecount)
{
	var split_array = new Array();
	split_array = objValue.split(splitStr);
	
	if(split_array.length!=2) {
		if(split_array.length==1) {
			return false;
		} else {
			return true;
		}
	} else {
		if(split_array[1].length>falsecount) {
			return true;
		} else {
			return false;
		}
	}
}

function isDigitSpecial(objValue,specialStr)
{
	if(specialStr.length>0) {
		var specialStr_code = parseInt(specialStr.charCodeAt(i));

		for(var i=0; i<objValue.length; i++) {
			var code = parseInt(objValue.charCodeAt(i));
			var ch = objValue.substr(i,1).toUpperCase();
			
			if((ch<"0" || ch>"9") && code!=specialStr_code) {
				return true;
				break;
			}
		}
	} else {
		for(var i=0; i<objValue.length; i++) {
			var ch = objValue.substr(i,1).toUpperCase();
			if(ch<"0" || ch>"9") {
				return true;
				break;
			}
		}
	}
}
//-->
</SCRIPT>
<!-- 옵션 수정 추가 -->
<script>
$(function() {
	//옵션항목설정
	var arr_opt1 = new Array();
	var arr_opt2 = new Array();
	var arr_opt3 = new Array();
	var opt1 = opt2 = opt3 = '';
	var opt_val;

	$(".opt-cell").each(function() {
		opt_val = $(this).text().split(" > ");
		opt1 = opt_val[0];
		opt2 = opt_val[1];
		opt3 = opt_val[2];

		if(opt1 && $.inArray(opt1, arr_opt1) == -1)
			arr_opt1.push(opt1);

		if(opt2 && $.inArray(opt2, arr_opt2) == -1)
			arr_opt2.push(opt2);

		if(opt3 && $.inArray(opt3, arr_opt3) == -1)
			arr_opt3.push(opt3);
	});


	$("input[name=opt1]").val(arr_opt1.join());
	$("input[name=opt2]").val(arr_opt2.join());
	$("input[name=opt3]").val(arr_opt3.join());
						// 옵션목록생성
	$("#option_table_create").click(function() {
		//var it_id = $.trim($("input[name=it_id]").val()); // 수정
		var it_id = $.trim($("#it_id").val()); // 수정
		var opt1_subject = $.trim($("#opt1_subject").val());
		var opt2_subject = $.trim($("#opt2_subject").val());
		var opt3_subject = $.trim($("#opt3_subject").val());
		var opt1 = $.trim($("#opt1").val());
		var opt2 = $.trim($("#opt2").val());
		var opt3 = $.trim($("#opt3").val());
		var $option_table = $("#sit_option_frm");

		if(!opt1_subject || !opt1) {
			alert("옵션명과 옵션항목을 입력해 주십시오.");
			return false;
		}

		$.post(
			"../admin/ajax_productoption.php",
			{ it_id: it_id, w: "u", opt1_subject: opt1_subject, opt2_subject: opt2_subject, opt3_subject: opt3_subject, opt1: opt1, opt2: opt2, opt3: opt3 },
			function(data) {
				$option_table.empty().html(data);
			}
		);
	});

	// 모두선택
	$(document).on("click", "input[name=opt_chk_all]", function() {
		if($(this).is(":checked")) {
			$("input[name='opt_chk[]']").attr("checked", true);
		} else {
			$("input[name='opt_chk[]']").attr("checked", false);
		}
	});

	// 선택삭제
	$(document).on("click", "#sel_option_delete", function() {
		var $el = $("input[name='opt_chk[]']:checked");
		if($el.size() < 1) {
			alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
			return false;
		}

		$el.closest("tr").remove();
	});

	// 일괄적용
	$(document).on("click", "#opt_value_apply", function() {
		if($(".opt_com_chk:checked").size() < 1) {
			alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
			return false;
		}

		var opt_price = $.trim($("#opt_com_price").val());
		var opt_stock = $.trim($("#opt_com_stock").val());
		var opt_noti = $.trim($("#opt_com_noti").val());
		var opt_use = $("#opt_com_use").val();
		var $el = $("input[name='opt_chk[]']:checked");

		// 체크된 옵션이 있으면 체크된 것만 적용
		if($el.size() > 0) {
			var $tr;
			$el.each(function() {
				$tr = $(this).closest("tr");

				if($("#opt_com_price_chk").is(":checked"))
					$tr.find("input[name='opt_price[]']").val(opt_price);

				if($("#opt_com_stock_chk").is(":checked"))
					$tr.find("input[name='opt_stock_qty[]']").val(opt_stock);

				if($("#opt_com_noti_chk").is(":checked"))
					$tr.find("input[name='opt_noti_qty[]']").val(opt_noti);

				if($("#opt_com_use_chk").is(":checked"))
					$tr.find("select[name='opt_use[]']").val(opt_use);
			});
		} else {
			if($("#opt_com_price_chk").is(":checked"))
				$("input[name='opt_price[]']").val(opt_price);

			if($("#opt_com_stock_chk").is(":checked"))
				$("input[name='opt_stock_qty[]']").val(opt_stock);

			if($("#opt_com_noti_chk").is(":checked"))
				$("input[name='opt_noti_qty[]']").val(opt_noti);

			if($("#opt_com_use_chk").is(":checked"))
				$("select[name='opt_use[]']").val(opt_use);
		}
	});
});
</script>
<!-- form 위치변경 2015 10 23 유동혁 -->
<form name=form1 method=post enctype="multipart/form-data">
<input type=hidden name=mode>
<input type=hidden name=code value="">
<input type=hidden name=htmlmode value='wysiwyg'>
<input type=hidden name=delprdtimg>
<input type=hidden name=option1>
<input type=hidden name=option2>
<input type=hidden name=option_price>

<!-- 옵션 수정 END -->
<table border=0 cellpadding=0 cellspacing=0 width=1000 style="table-layout:fixed">
<col width=175></col>
<col width=5></col>
<col width=740></col>
<col width=80></col>
<tr>
	<td width=175 valign=top nowrap><? include ("menu.php"); ?></td>
	<td width=5 nowrap></td>
	<td valign=top>

	<table width="100%"  border="0" cellpadding="1" cellspacing="0" bgcolor="#D0D1D0">
	<tr>
		<td>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:3px solid #EEEEEE" bgcolor="#ffffff">
		<tr>
			<td style="padding:10">
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=165></col>
				<col width=></col>
				<tr>
					<td height=29 align=center background="images/tab_menubg.gif">
					<FONT COLOR="#ffffff"><B>상품 신규등록</B></FONT>
					</td>
					<td></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height=2 bgcolor=red></td></tr>
			<tr>
				<td bgcolor=#FBF5F7>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=10></col>
				<col width=></col>
				<col width=10></col>
				<tr>
					<td colspan=3 style="padding:15px 15px 5px 15px">
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td style="padding-bottom:5"><img src="images/icon_boxdot.gif" border=0 align=absmiddle> <B>상품 신규등록</B></td>
					</tr>
					<tr>
						<td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 카테고리 생성은 본사 쇼핑몰에서만 관리할 수 있습니다.</td>
					</tr>
					<tr>
						<td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 입점사는 생성된 대분류 카테고리명을 선택하고 중>소>세분류로 구분하여 상품등록 합니다.</td>
					</tr>
					<tr>
						<td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 등록한 상품은 [상품진열]기능을 통해 진열할 수 있습니다.</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr>
					<td><img src="images/tab_boxleft.gif" border=0></td>
					<td></td>
					<td><img src="images/tab_boxright.gif" border=0></td>
				</tr>
				</table>
				</td>
			</tr>

			<!-- 처리할 본문 위치 시작 -->
			<tr><td height=0></td></tr>
			<tr>
				<td style="padding:15px">
				
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">

				<!-- form 위치변경 2015 10 23 유동혁 -->

<!-- 카테고리 링크 선택 추가 2015 10 26 유동혁 -->
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>카테고리 선택</B></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>

					<table width=100% border=0 cellspacing=0 cellpadding=0>
					<tr height=22 align=center>
						<td width=150 nowrap>
						<table width="150" cellpadding="0" cellspacing="1" border="0" bgcolor=E7E7E7>
						<tr>
							<td bgcolor=FEFCE2 align="center" height="23"><B>대분류</B></td>
						</tr>
						</table>
						</td>
						<td align=center><img src=images/icon_arrow02.gif border=0></td>
						<td width=150 nowrap>
						<table width="150" cellpadding="0" cellspacing="1" border="0" bgcolor=E7E7E7>
						<tr>
							<td bgcolor=FEFCE2 align="center" height="23"><B>중분류</B></td>
						</tr>
						</table>
						</td>
						<td align=center><img src=images/icon_arrow02.gif border=0></td>
						<td width=150 nowrap>
						<table width="150" cellpadding="0" cellspacing="1" border="0" bgcolor=E7E7E7>
						<tr>
							<td bgcolor=FEFCE2 align="center" height="23"><B>소분류</B></td>
						</tr>
						</table>
						</td>
						<td align=center><img src=images/icon_arrow02.gif border=0></td>
						<td width=150 nowrap>
						<table width="150" cellpadding="0" cellspacing="1" border="0" bgcolor=E7E7E7>
						<tr>
							<td bgcolor=FEFCE2 align="center" height="23"><B>세분류</B></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td height=6 colspan=7></td>
					</tr>

					<tr>
						<td valign=top>
							<?=$codeA_list?>
						</td>
						<td></td>
						<td valign=top>
							<?=$codeB_list?>
						</td>
						<td></td>
						<td valign=top>
							<?=$codeC_list?>
						</td>
						<td></td>
						<td valign=top>
							<?=$codeD_list?>
						</td>
					</tr>
					<tr>
						<td colspan='7' style='height: 35px; text-align: right;'>
							<?=$codeSelect?>
<?	
	//카테고리 스크립트 실행
	echo $strcodelist;
	echo "<script>SearchCodeInit(\"".$code_a."\",\"".$code_b."\",\"".$code_c."\",\"".$code_d."\");</script>"; 
?>
						</td>
					</tr>
					<tr>
						<td colspan=7>
						<img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>카테고리 선택결과</B>
						&nbsp;
							<div class="table_none">
								<table width=100% cellpadding=0 cellspacing=1 id=Category_table>
									<col><col width=50 style="padding-right:10"><col width=52 align=right>
<?
	$cate_query="select * from tblproductlink where c_productcode='".$prcode."' and c_productcode!=''";
	$cate_result=pmysql_query($cate_query);
	$i=0;

	while($cate_row=pmysql_fetch_array($cate_result)){

		$cate_array[$i]["c_category"]=$cate_row[c_category];
		$cate_cut="";
		$catename="";
		$cate_cut[]=str_pad(substr($cate_row[c_category],0,3), 12, "0");
		if(substr($cate_row[c_category],3,3)!='000')$cate_cut[]=str_pad(substr($cate_row[c_category],0,6), 12, "0");
		if(substr($cate_row[c_category],6,3)!='000')$cate_cut[]=str_pad(substr($cate_row[c_category],0,9), 12, "0");
		if(substr($cate_row[c_category],9,3)!='000')$cate_cut[]=str_pad(substr($cate_row[c_category],0,12), 12, "0");

		foreach($cate_cut as $k){
			$catename_qry="select * from tblproductcode where code_a='".substr($k,0,3)."' and code_b='".substr($k,3,3)."' and code_c='".substr($k,6,3)."' and code_d='".substr($k,9,3)."'";
			$catename_result=pmysql_query($catename_qry);
			$catename_data=pmysql_fetch_array($catename_result);
			$catename[]=$catename_data[code_name];
		}
		$cate_array[$i]["c_codename"]=implode(" > ",$catename);
	$i++;
	}
	if($cate_array){
		foreach($cate_array as $v=>$k){
	?>
									<tr>
										<td id=cate_name><?=$k[c_codename]?></td>
										<td>
										<input type=text name=category[] value="<?=$k[c_category]?>" style="display:none">
										</td>
										<td>
										<!--<img src="../img/i_select.gif" border=0 onClick="cate_mod(document.forms[0]['cate[]'][0],this.parentNode.parentNode)" class=hand>-->
										<a href="javascript:void(0)" onClick="cate_del(this.parentNode.parentNode)"><img src="img/btn/btn_cate_del01.gif" border=0 align=absmiddle></a>
										</td>
									</tr>
	<?
		}
	}
?>

								</table>
							</div>
						</td>
					</tr>
					<tr><td colspan=7 height=5></tr>

					<tr><td height=1 colspan=7 bgcolor=CDCDCD></td></tr>

					</table>

					</td>
				</tr>

				<tr>
<!-- 카테고리 링크 선택 추가 2015 10 26 유동혁 -->

				<tr><td height=20></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>상품정보</B></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<col width=130></col>
					<col width=250></col>
					<col width=95></col>
					<col width=></col>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><font color=FF4800>*</font> 상품명</td>
						<td colspan=3 style="padding:7px 7px"><input name=productname value="" maxlength=250 style="width:388" onKeyDown="chkFieldMaxLen(250)"></td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><font color=FF4800>*</font> 교육 할인가</td>
						<td style="padding:7px 7px"><input name=sellprice value="" size=18 maxlength=10></td>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><font color=FF4800>*</font> 인터넷최저가</td>
						<td style="padding:7px 7px"><input name=consumerprice value="" size=18 maxlength=10><br><font style="color:#2A97A7;font-size:8pt">※ 0입력시, 표시안함</font></td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 적립금(률)</td>
						<td style="padding:7px 7px"><input name=reserve value="" size=18 maxlength=6 onKeyUP="chkFieldMaxLenFunc(this.form,this.form.reservetype.value);"><select name="reservetype" style="width:77;font-size:8pt;margin-left:1px;" onchange="chkFieldMaxLenFunc(this.form,this.value);"><option value="N" selected>적립금(￦)</option><option value="Y">적립률(%)</option></select><br><font style="color:#2A97A7;font-size:8pt;letter-spacing:-0.5pt;">* 적립률은 소수점 둘째자리까지 입력 가능합니다.<br>* 적립률에 대한 적립 금액 소수점 자리는 반올림.</span></td>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 정상가</td>
						<td style="padding:7px 7px"><input name=buyprice value="" size=18 maxlength=10></td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 제조원</td>
						<td style="padding:7px 7px"><input name=production value="" size=18 maxlength=20 onKeyDown="chkFieldMaxLen(50)">&nbsp;<a href="javascript:FiledSelect('PR');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 원산지</td>
						<td style="padding:7px 7px"><input name=madein value="" size=18 maxlength=20 onKeyDown="chkFieldMaxLen(30)">&nbsp;<a href="javascript:FiledSelect('MA');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 브랜드</td>
						<td style="padding:7px 7px"><input name=brandname value="" size=18 maxlength=40 onKeyDown="chkFieldMaxLen(50)">&nbsp;<a href="javascript:BrandSelect();"><img src="images/btn_select.gif" border="0" align="absmiddle"></a><br>
						<font style="color:#2A97A7;font-size:8pt">※ 브랜드를 직접 입력시에도 등록됩니다.</font></td>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 모델명</td>
						<td style="padding:7px 7px"><input name=model value="" size=18 maxlength=40 onKeyDown="chkFieldMaxLen(50)">&nbsp;<a href="javascript:FiledSelect('MO');"><img src="images/btn_select.gif" border="0" align="absmiddle"></a></td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 진열코드</td>
						<td style="padding:7px 7px" colspan="3"><input name=selfcode value="" size=18 maxlength=20 onKeyDown="chkFieldMaxLen(20)"><br><font style="color:#2A97A7;font-size:8pt">* 쇼핑몰에서 자동으로 발급되는 상품코드와는 별개로 운영상 필요한 자체상품코드를 입력해 주세요.</font></td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 출시일</td>
						<td style="padding:7px 7px" colspan="3"><input name=opendate value="" size=18 maxlength=8>&nbsp;&nbsp;예) <?=DATE("Ymd")?>(출시년월일)<br>
						<font style="color:#2A97A7;font-size:8pt">* 가격비교 페이지 등 제휴업체 관련 노출시 사용됩니다.<br>* 잘못된 출시일 지정으로 인한 문제는 상점에서 책임지셔야 됩니다.</font></td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 수량</td>
						<td colspan=3 style="padding:7px 7px">
<?php
						$checkquantity="F";

						$arrayname= array("품절","무제한","수량");
						$arrayprice=array("E","F","C");
						$arraydisable=array("true","true","false");
						$arraybg=array("silver","silver","white");
						$arrayquantity=array("","","$quantity");
						$cnt = count($arrayprice);
						for($i=0;$i<$cnt;$i++){
							echo "<input type=radio id=\"idx_checkquantity".$i."\" name=checkquantity value=\"".$arrayprice[$i]."\" "; 
							if($checkquantity==$arrayprice[$i]) echo "checked "; echo "onClick=\"document.form1.quantity.disabled=".$arraydisable[$i].";document.form1.quantity.style.background='".$arraybg[$i]."';document.form1.quantity.value='".$arrayquantity[$i]."';\"><label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_checkquantity".$i.">".$arrayname[$i]."</label>&nbsp;&nbsp;";
						}
						echo ": <input type=text name=quantity size=5 maxlength=5 value=\"".($quantity==0?"":$quantity)."\">개";

						echo "<script>document.form1.quantity.disabled=true;document.form1.quantity.style.background='silver';document.form1.checkquantity.value='';</script>\n";
?>
						</td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 최소주문한도</td>
						<td style="padding:7px 7px"><input type=text name=miniq value="1" size=5 maxlength=5> 개 이상</td>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 최대주문한도</td>
						<td style="padding:7px 7px">
						<input type=radio id="idx_checkmaxq1" name=checkmaxq value="A" checked onclick="document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkmaxq1>무제한</label>&nbsp;<input type=radio id="idx_checkmaxq2" name=checkmaxq value="B" onclick="document.form1.maxq.disabled=false;document.form1.maxq.style.background='white';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_checkmaxq2>수량</label>:<input name=maxq size=5 maxlength=5 value="">개 이하
						<script>
						if (document.form1.checkmaxq[0].checked) { document.form1.maxq.disabled=true;document.form1.maxq.style.background='silver'; }
						else if (document.form1.checkmaxq[1].checked) { document.form1.maxq.disabled=false;document.form1.maxq.style.background='white'; }
						</script>
						</td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 배송비</td>
						<td colspan=3 style="padding:7px 7px">
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<tr>
							<td><input type=radio id="idx_deliprtype0" name=deli value="H" checked onclick="document.form1.deli_qty.disabled=true;document.form1.deli_qty.style.background='silver';document.form1.deli_price_value3.disabled=true;document.form1.deli_price_value3.style.background='silver';document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype0>기본 배송비 <b>유지</b></label>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input type=radio id="idx_deliprtype2" name=deli value="F" onclick="document.form1.deli_qty.disabled=true;document.form1.deli_qty.style.background='silver';document.form1.deli_price_value3.disabled=true;document.form1.deli_price_value3.style.background='silver';document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype2>배송비 <b><font color="#0000FF">무료</font></b></label>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<div style='display:none;'>
								<input type=radio id="idx_deliprtype1" name=deli value="G" onclick="document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype1>개별 배송비 <b><font color="#38A422">착불</font></b></label>
								</div>
							</td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td><input type=radio id="idx_deliprtype3" name=deli value="N" onclick="document.form1.deli_qty.disabled=true;document.form1.deli_qty.style.background='silver';document.form1.deli_price_value3.disabled=true;document.form1.deli_price_value3.style.background='silver';document.form1.deli_price_value1.disabled=false;document.form1.deli_price_value1.style.background='';document.form1.deli_price_value2.disabled=true;document.form1.deli_price_value2.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype3>배송비 <b><font color="#FF0000">유료</font></b> <input type=text name=deli_price_value1 value="" size=6 maxlength=6 disabled style='background:silver'>원</label>
								<br>
								<input type=radio id="idx_deliprtype4" name=deli value="Y" onclick="document.form1.deli_qty.disabled=true;document.form1.deli_qty.style.background='silver';document.form1.deli_price_value3.disabled=true;document.form1.deli_price_value3.style.background='silver';document.form1.deli_price_value2.disabled=false;document.form1.deli_price_value2.style.background='';document.form1.deli_price_value1.disabled=true;document.form1.deli_price_value1.style.background='silver';"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliprtype4>배송비 <b><font color="#FF0000">유료</font></b> <input type=text name=deli_price_value2 value="" size=6 maxlength=6 disabled style='background:silver'>원 (구매수 대비 개별 배송비 증가 : <FONT COLOR="#FF0000"><B>상품구매수×개별 배송비</B></font>)</label>
							<br>
								<input type=radio id="idx_deliprtype5" name=deli value="Z" <?php if($_data->deli_price>0 && $_data->deli=="Z") echo "checked";?> 
									onclick="
										document.form1.deli_qty.disabled=false;
										document.form1.deli_qty.style.background='';
										document.form1.deli_price_value3.disabled=false;
										document.form1.deli_price_value3.style.background='';
										document.form1.deli_price_value2.disabled=true;
										document.form1.deli_price_value2.style.background='silver';
										document.form1.deli_price_value1.disabled=true;
										document.form1.deli_price_value1.style.background='silver';
										"
									>
								<label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_deliprtype5>배송비 <b><font color="#FF0000">유료</font></b></label>
								<input type='text' name='deli_qty' value="<?php if($_data->deli_price>0 && $_data->deli=="Z") echo $_data->deli_qty;?>" <?php if($_data->deli_price<=0 || $_data->deli!="Z") echo "disabled style='background:silver'";?> size=6 maxlength=6 onkeypress="return isNumberKey(event)" > 개 까지
								<input type=text onkeypress="return isNumberKey(event)" name=deli_price_value3 value="<?php if($_data->deli_price>0 && $_data->deli=="Z") echo $_data->deli_price;?>" size=6 maxlength=6 <?php if($_data->deli_price<=0 || $_data->deli!="Z") echo "disabled style='background:silver'";?> class="input">원 (수량별 배송비 증가 : <FONT COLOR="#FF0000"><B>상품구매수 대비 배송비</B></font>)&nbsp;<a href="javascript:deli_helpshow();"></a>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>

					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 검색어</td>
						<td colspan=3 style="padding:7px 7px">
						<input name=keyword value="" size=80 maxlength=100 onKeyDown="chkFieldMaxLen(100)">
						</td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 상품 특이사항</td>
						<td colspan=3 style="padding:7px 7px">
						<input name=addcode value="" size=43 maxlength=200 onKeyDown="chkFieldMaxLen(200)">&nbsp;&nbsp;<font style="color:#2A97A7;font-size:8pt">(예: 향수는 용량표시, TV는 17인치등)</font>
						</td>
					</tr>
					<tr><td height=1 colspan=4 bgcolor=E7E7E7></td></tr>
					</table>
					</td>
				</tr>
				<tr><td height=15></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>사진정보</B></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<col width=130></col>
					<col width=></col>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 큰이미지</td>
						<td style="padding:7px 7px">
						<input type=file name="userfile" class=button style="width=300px" > <font style="color:#2A97A7;font-size:8pt">(권장이미지 : 550X550)</font>
						<br>
						<input type=checkbox id="idx_imgcheck1" name=imgcheck value="Y"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_imgcheck1><font color=#003399>큰 이미지로 중간/작은 이미지 자동생성 (이미지 권장 사이즈로 변경)</font></label>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 중간이미지</td>
						<td style="padding:7px 7px">
						<input type=file name="userfile2" class=button style="width=300px"  > <font style="color:#2A97A7;font-size:8pt">(권장이미지 : 300X300)</font>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 작은이미지</td>
						<td style="padding:7px 7px">
						<input type=file name="userfile3" class=button style="width=300px" > <font style="color:#2A97A7;font-size:8pt">(권장이미지 : 130X130)</font>
						<input type=hidden name=setcolor value="<?=$setcolor?>">
						<BR>
						<table border=0 cellpadding=0 cellspacing=0>
						<tr>
							<td><input type=checkbox name=imgborder value="Y" <?=(($imgborder)=="Y"?"checked":"")?>></td>
							<td style="padding-top:4px"><font color=#003399>신규 등록시,&nbsp;(&nbsp;</td>
							<td width=10 align=center valign=middle><div id="ColorPreview" style="background-color: #<?=$setcolor?>;height: 10px; width: 15px"></div></td>
							<td style="padding-top:4px"><font color=#003399>&nbsp;)&nbsp;로 상품 테두리선 생성!&nbsp;&nbsp;다른 색상선택-></font></td>
							<td><a href="JavaScript:SelectColor()"><img src="images/ed_color_bg.gif" align=absmiddle border=0></a></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
<!-- 기타 이미지 추가 (멀티이미지) 20151023 유동혁 -->
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 기타 이미지</td>
						<td class="td_con1" style="border-bottom-width:1pt; border-bottom-color:rgb(255,153,51); border-bottom-style:solid;position:relative;">
						<table width="100%">
<?php
	$urlpath=$Dir.DataDir."shopimages/multi/";
	for($i=1;$i<=10;$i+=2){
		$gbn1=sprintf("%02d",$i);
		$gbn2=sprintf("%02d",$i+1);
?>
							<tr bgColor=#f0f0f0>
								<td class=lineleft style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" align=middle width="50%" bgcolor="#F9F9F9"><input type=file name=mulimg<?=$gbn1?> style="width:100%"><input type=hidden name=oldimg<?=$gbn1?> value="<?=$mulimg_name[$gbn1]?>"></td>
								<td class=line style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" align=middle width="50%" bgcolor="#F9F9F9"><input type=file name=mulimg<?=$gbn2?> style="width:100%"><input type=hidden name=oldimg<?=$gbn2?> value="<?=$mulimg_name[$gbn2]?>"></td>
							</tr>
		<?php if(ord($mulimg_name[$gbn1]) || ord($mulimg_name[$gbn2])){?>
							<tr>
								<td class=lineleft style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; PADDING-BOTTOM: 5px; LINE-HEIGHT: 125%; PADDING-TOP: 5px" align=middle width="50%" bgcolor="#F9F9F9">
									<?php if(ord($mulimg_name[$gbn1])){?>
									 <img src="<?=$urlpath."s".$mulimg_name[$gbn1]?>" width="100" height="100" border="0"><A HREF="javascript:mulimgdel('<?=$gbn1?>');"><img src="images/icon_del1.gif" border="0"></a>
									<?php }else{echo"&nbsp;";}?>
								</td>
								<td class=line style="PADDING-RIGHT: 5px; PADDING-LEFT: 5px; PADDING-BOTTOM: 5px; LINE-HEIGHT: 125%; PADDING-TOP: 5px" align=middle width="50%" bgcolor="#F9F9F9">
									<?php if(ord($mulimg_name[$gbn2])){?>
									<img src="<?=$urlpath."s".$mulimg_name[$gbn2]?>" width="100" height="100" border="0"><A HREF="javascript:mulimgdel('<?=$gbn2?>');"><img src="images/icon_del1.gif" border="0"></a>
									<?php }else{echo"&nbsp;";}?>
								</td>
							</tr>
		<?php }?>
<?php
	}
?>
						</table>
						</td>
					</tr>
<!--// 기타 이미지 추가 (멀티이미지) 20151023 유동혁 -->
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>

					</table>
					</td>
				</tr>
				
				<tr><td height=15></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>상품 상세정보</B>

					</td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<tr>
						<td>
						<textarea wrap=off id="ir1" style="width:100%; height:300" name=content></textarea>
						</td>
					</tr>
					<tr>
						<td>
						<img id="size_checker" style="display:none;">
						<img id="size_checker2" style="display:none;">
						<img id="size_checker3" style="display:none;">
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height=15></td></tr>
				<tr>
					<td><img src="images/icon_dot03.gif" border=0 align=absmiddle> <B>추가정보</B></td>
				</tr>

				<tr><td height=15></td></tr>
				<tr><td height=1 bgcolor=red></td></tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<col width=130></col>
					<col width=></col>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 옵션정보</td>
						<td style="padding:7px 7px">
						<!-- 옵션추가 2015 10 23 유동혁 -->
	
							<div class="sit_option tbl_frm01">
								<span class="frm_info">옵션항목은 콤마(,) 로 구분하여 여러개를 입력할 수 있습니다. 옷을 예로 들어 [옵션1 : 사이즈 , 옵션1 항목 : XXL,XL,L,M,S] , [옵션2 : 색상 , 옵션2 항목 : 빨,파,노]<br><strong>옵션명과 옵션항목에 따옴표(', ")는 입력할 수 없습니다.</strong></span>
								<table>
									<caption>상품선택옵션 입력</caption>
									<colgroup>
										<col class="grid_4">
										<col>
									</colgroup>
									<tbody>
									<tr>
										<th scope="row">
											<label for="opt1_subject">옵션1</label>
											<input type="text" name="opt1_subject" value="<?=$opt1_subject?>" id="opt1_subject" class="frm_input" size="15">
										</th>
										<td>
											<label for="opt1"><b>옵션1 항목</b></label>
											<input type="text" name="opt1" value="<?=$opt1?>" id="opt1" class="frm_input" size="50">
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="opt2_subject">옵션2</label>
											<input type="text" name="opt2_subject" value="<?=$opt2_subject?>" id="opt2_subject" class="frm_input" size="15">
										</th>
										<td>
											<label for="opt2"><b>옵션2 항목</b></label>
											<input type="text" name="opt2" value="<?=$opt2?>" id="opt2" class="frm_input" size="50">
										</td>
									</tr>
									</tbody>
								</table>
								<div class="btn_confirm02 btn_confirm">
									<button type="button" id="option_table_create" class="btn_frmline">옵션목록생성</button>
								</div>
							</div>
							<div id="sit_option_frm">
							</div>
								
						<!-- 옵션 수정 end -->
						</td>
					</tr>
					<!-- 추가옵션 2015 10 28 유동혁 -->
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 상품추가옵션</td>
						<td style="padding:7px 7px">
							<div id="sit_supply_frm" class="sit_option tbl_frm01">
								<?php echo help('옵션항목은 콤마(,) 로 구분하여 여러개를 입력할 수 있습니다. 스마트폰을 예로 들어 [추가1 : 추가구성상품 , 추가1 항목 : 액정보호필름,케이스,충전기]<br><strong>옵션명과 옵션항목에 따옴표(\', ")는 입력할 수 없습니다.</strong>'); ?>
								<table>
								<caption>상품추가옵션 입력</caption>
								<colgroup>
									<col class="grid_4">
									<col>
								</colgroup>
								<tbody>
								<?php
								$i = 0;
								do {
									$seq = $i + 1;
								?>
								<tr>
									<th scope="row">
										<label for="spl_subject_<?php echo $seq; ?>">추가<?php echo $seq; ?></label>
										<input type="text" name="spl_subject[]" id="spl_subject_<?php echo $seq; ?>" value="<?php echo $spl_subject[$i]; ?>" class="frm_input" size="15">
									</th>
									<td>
										<label for="spl_item_<?php echo $seq; ?>"><b>추가<?php echo $seq; ?> 항목</b></label>
										<input type="text" name="spl[]" id="spl_item_<?php echo $seq; ?>" value="" class="frm_input" size="40">
										<?php
										if($i > 0)
											echo '<button type="button" id="del_supply_row" class="btn_frmline">삭제</button>';
										?>
									</td>
								</tr>
								<?php
									$i++;
								} while($i < $spl_count);
								?>
								</tbody>
								</table>
								<div id="sit_option_addfrm_btn"><button type="button" id="add_supply_row" class="btn_frmline">옵션추가</button></div>
								<div class="btn_confirm02 btn_confirm">
									<button type="button" id="supply_table_create">옵션목록생성</button>
								</div>
							</div>
							
							<div id="sit_option_addfrm"></div>

						</td>
					</tr>
					<!-- //추가옵션 -->
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr style='display:none;'>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 아이콘 꾸미기</td>
						<td style="padding:7px 7px">

						<table border=0 cellpadding=0 cellspacing=0 width=70%>
<?php
						//$iconarray = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28");
						$iconarray = array("01","02","03","04","05","06","07");
						$totaliconnum = 0;
						for($i=0;$i<count($iconarray);$i++) {
							if($i%7==0) echo "<tr height=25>";
							echo "<td align=center><img src=\"".$Dir."images/common/icon".$iconarray[$i].".gif\" border=0 align=absmiddle><br><input type=checkbox name=icon onclick=CheckChoiceIcon('".$totaliconnum."') value=\"".$iconarray[$i]."\" ";
							if($iconvalue2[$iconarray[$i]]=="Y") echo "checked";
							echo "></td>\n";
							if($i%7==6) echo "</tr>";
							$totaliconnum++;
						}
?>
						</table>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 배송/쇼환/환불정보</td>
						<td style="padding:7px 7px">
						<input type=checkbox id="idx_deliinfono1" name=deliinfono value="Y"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_deliinfono1>배송/교환/환불정보 노출안함</label> <font style="color:#2A97A7;font-size:8pt">(상세화면 하단에 배송/교환/환불정보가 노출안됨)</font>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>

					<?if($_venderdata->grant_product[3]=="N") {?>

					<tr>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9> 상품진열</td>
						<td style="padding:7px 7px">
						<input type=radio id="idx_display1" name=display value="Y" checked><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_display1>보이기 [ON]</label>
						<img width=50 height=0>
						<input type=radio id="idx_display2" name=display value="N"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_display2>안보이기 [OFF]</label>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>

					<?} else {?>

					<input type=hidden name=display value="N">
					
					<?}?>
					<!-- 정보고시 등록/수정 임시 막음 -->

					<tr style='display:none;'>
						<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9>
							정보 고시 등록/수정
							<input type="hidden" name="sabangnet_prop_val" value="<?=$_data->sabangnet_prop_val?>">
							<input type="hidden" name="sabangnet_prop_option" value="<?=$data->sabangnet_prop_option?>">
							<input type="hidden" name="prop_type" value="001" />
						</td>
						<td>
							<div class="table_style01" id="prop_type001">
							<table cellSpacing=0 cellPadding=0 width="100%" border=0>
							<tr>
								<!--<th><span>종류</span></th>-->
								<?if(!$sabangnet_prop_option[1]){$sabangnet_prop_option[1]="종류";}?>
								<th><input type="text" id="prop_opt0011" value="<?=$sabangnet_prop_option[1];?>" style=""/></th>
								<td><input type=text id=prop0011 value="<?=$sabangnet_prop_val[1];?>" style=";"></td>
								<!--<th><span>제조국</span></th>-->
								<?if(!$sabangnet_prop_option[2]){$sabangnet_prop_option[2]="제조국";}?>
								<th><input type="text" id="prop_opt0012" value="<?=$sabangnet_prop_option[2];?>" style=""/></th>
								<td><input type=text id=prop0012 value="<?=$sabangnet_prop_val[2];?>" style=";"></td>
							</tr>
							<tr>
								<!--<th><span>소재</span></th>-->
								<?if(!$sabangnet_prop_option[3]){$sabangnet_prop_option[3]="소재";}?>
								<th><input type="text" id="prop_opt0013" value="<?=$sabangnet_prop_option[3];?>" style=""/></th>
								<td><input type=text id=prop0013 value="<?=$sabangnet_prop_val[3];?>"  style=";"></td>
								<!--<th><span>취급시주의사항</span></th>-->
								<?if(!$sabangnet_prop_option[4]){$sabangnet_prop_option[4]="취급시 주의사항";}?>
								<th><input type="text" id="prop_opt0014" value="<?=$sabangnet_prop_option[4];?>" style=""/></th>
								<td><input type=text id=prop0014 value="<?=$sabangnet_prop_val[4];?>"  style=";"></td>
							</tr>
							<tr>
								<!--<th><span>색상</span></th>-->
								<?if(!$sabangnet_prop_option[5]){$sabangnet_prop_option[5]="색상";}?>
								<th><input type="text" id="prop_opt0015" value="<?=$sabangnet_prop_option[5];?>" style=""/></th>
								<td><input type=text id=prop0015 value="<?=$sabangnet_prop_val[5];?>" style=";"></td>
								<!--<th><span>품질 보증기준</span></th>-->
								<?if(!$sabangnet_prop_option[6]){$sabangnet_prop_option[6]="품질 보증기준";}?>
								<th><input type="text" id="prop_opt0016" value="<?=$sabangnet_prop_option[6];?>" style=""/></th>
								<td><input type=text id=prop0016 value="<?=$sabangnet_prop_val[6];?>" style=";"></td>
							</tr>
							<tr>
								<!--<th><span>크기</span></th>-->
								<?if(!$sabangnet_prop_option[7]){$sabangnet_prop_option[7]="크기";}?>
								<th><input type="text" id="prop_opt0017" value="<?=$sabangnet_prop_option[7];?>" style=""/></th>
								<td><input type=text id=prop0017 value="<?=$sabangnet_prop_val[7];?>"  style=";"></td>
								<!--<th><span>고객센터 전화번호</span></th>-->
								<?if(!$sabangnet_prop_option[8]){$sabangnet_prop_option[8]="고객센터 전화번호";}?>
								<th><input type="text" id="prop_opt0018" value="<?=$sabangnet_prop_option[8];?>" style=""/></th>
								<td><input type=text id=prop0018 value="<?=$sabangnet_prop_val[8];?>" style=";"></td>
							</tr>
								<!--<th><span>제조자</span></th>-->
								<?if(!$sabangnet_prop_option[9]){$sabangnet_prop_option[9]="제조자";}?>
								<th><input type="text" id="prop_opt0019" value="<?=$sabangnet_prop_option[9];?>" style=""/></th>
								<td><input type=text id=prop0019 value="<?=$sabangnet_prop_val[9];?>" style=";"></td>
								<!--<th><span></span></th>-->
								<th><input type="text" id="prop_opt00110" value="<?=$sabangnet_prop_option[10];?>" style=""/></th>
								<td><input type=text id=prop00110 value="<?=$sabangnet_prop_val[10];?>" style=";"></td>
							<tr>
								<!--<th><span>수입자</span></th>-->
								<?if(!$sabangnet_prop_option[11]){$sabangnet_prop_option[11]="수입자";}?>
								<th><input type="text" id="prop_opt00111" value="<?=$sabangnet_prop_option[11];?>" style=""/></th>
								<td><input type=text id=prop00111 value="<?=$sabangnet_prop_val[11];?>" style=";"></td>
								<th><input type="text" id="prop_opt00112" value="<?=$sabangnet_prop_option[12];?>" style=""/></th>
								<td><input type=text id=prop00112 value="<?=$sabangnet_prop_val[12];?>" style=";"></td>
							</tr>
							</table>
							</div>
							</td>
						</tr>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height=20></td></tr>
				<tr>
					<td align=center>
					<!--A HREF="javascript:formSubmit('preview')"><img src="images/btn_preview01.gif" border=0></A>
					&nbsp;-->
					<A HREF="javascript:formSubmit('insert')"><img src="images/btn_regist01.gif" border=0></A>
					</td>
				</tr>

				<input type=hidden name=iconnum value='<?=$totaliconnum?>'>
				<input type=hidden name=iconvalue>

				<!-- form 위치변경 -->

				</table>

				<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

				</td>
			</tr>
			<!-- 처리할 본문 위치 끝 -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>
</table>
</form>
<!-- 추가옵션 2015 10 28 유동혁 -->
<script>
$(function() {
	<?php if($it['it_id'] && $ps_run) { ?>
	// 추가옵션의 항목 설정
	var arr_subj = new Array();
	var subj, spl;

	$("input[name='spl_subject[]']").each(function() {
		subj = $.trim($(this).val());
		if(subj && $.inArray(subj, arr_subj) == -1)
			arr_subj.push(subj);
	});

	for(i=0; i<arr_subj.length; i++) {
		var arr_spl = new Array();
		$(".spl-subject-cell").each(function(index) {
			subj = $(this).text();
			if(subj == arr_subj[i]) {
				spl = $(".spl-cell:eq("+index+")").text();
				arr_spl.push(spl);
			}
		});

		$("input[name='spl[]']:eq("+i+")").val(arr_spl.join());
	}
	console.log( subj ); console.log( spl );
	<?php } ?>
	// 입력필드추가
	$("#add_supply_row").click(function() {
		var $el = $("#sit_supply_frm tr:last");
		var fld = "<tr>\n";
		fld += "<th scope=\"row\">\n";
		fld += "<label for=\"\">추가</label>\n";
		fld += "<input type=\"text\" name=\"spl_subject[]\" value=\"\" class=\"frm_input\" size=\"15\">\n";
		fld += "</th>\n";
		fld += "<td>\n";
		fld += "<label for=\"\"><b>추가 항목</b></label>\n";
		fld += "<input type=\"text\" name=\"spl[]\" value=\"\" class=\"frm_input\" size=\"40\">\n";
		fld += "<button type=\"button\" id=\"del_supply_row\" class=\"btn_frmline\">삭제</button>\n";
		fld += "</td>\n";
		fld += "</tr>";

		$el.after(fld);

		supply_sequence();
	});

	// 입력필드삭제
	$(document).on("click", "#del_supply_row", function() {
		$(this).closest("tr").remove();

		supply_sequence();
	});

	// 옵션목록생성
	$("#supply_table_create").click(function() {
		var it_id = $.trim($("input[name=it_id]").val());
		var subject = new Array();
		var supply = new Array();
		var subj, spl;
		var count = 0;
		var $el_subj = $("input[name='spl_subject[]']");
		var $el_spl = $("input[name='spl[]']");
		var $supply_table = $("#sit_option_addfrm");

		$el_subj.each(function(index) {
			subj = $.trim($(this).val());
			spl = $.trim($el_spl.eq(index).val());

			if(subj && spl) {
				subject.push(subj);
				supply.push(spl);
				count++;
			}
		});

		if(!count) {
			alert("추가옵션명과 추가옵션항목을 입력해 주십시오.");
			return false;
		}

		$.post(
			"../admin/ajax_productoption_plus.php",
			{ it_id: it_id, w: "u", 'subject[]': subject, 'supply[]': supply },
			function(data) {
				$supply_table.empty().html(data);
			}
		);
	});

	// 모두선택
	$(document).on("click", "input[name=spl_chk_all]", function() {
		if($(this).is(":checked")) {
			$("input[name='spl_chk[]']").attr("checked", true);
		} else {
			$("input[name='spl_chk[]']").attr("checked", false);
		}
	});

	// 선택삭제
	$(document).on("click", "#sel_supply_delete", function() {
		var $el = $("input[name='spl_chk[]']:checked");
		if($el.size() < 1) {
			alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
			return false;
		}

		$el.closest("tr").remove();
	});

	// 일괄적용
	$(document).on("click", "#spl_value_apply", function() {
		if($(".spl_com_chk:checked").size() < 1) {
			alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
			return false;
		}

		var spl_price = $.trim($("#spl_com_price").val());
		var spl_stock = $.trim($("#spl_com_stock").val());
		var spl_noti = $.trim($("#spl_com_noti").val());
		var spl_use = $("#spl_com_use").val();
		var $el = $("input[name='spl_chk[]']:checked");

		// 체크된 옵션이 있으면 체크된 것만 적용
		if($el.size() > 0) {
			var $tr;
			$el.each(function() {
				$tr = $(this).closest("tr");

				if($("#spl_com_price_chk").is(":checked"))
					$tr.find("input[name='spl_price[]']").val(spl_price);

				if($("#spl_com_stock_chk").is(":checked"))
					$tr.find("input[name='spl_stock_qty[]']").val(spl_stock);

				if($("#spl_com_noti_chk").is(":checked"))
					$tr.find("input[name='spl_noti_qty[]']").val(spl_noti);

				if($("#spl_com_use_chk").is(":checked"))
					$tr.find("select[name='spl_use[]']").val(spl_use);
			});
		} else {
			if($("#spl_com_price_chk").is(":checked"))
				$("input[name='spl_price[]']").val(spl_price);

			if($("#spl_com_stock_chk").is(":checked"))
				$("input[name='spl_stock_qty[]']").val(spl_stock);

			if($("#spl_com_noti_chk").is(":checked"))
				$("input[name='spl_noti_qty[]']").val(spl_noti);

			if($("#spl_com_use_chk").is(":checked"))
				$("select[name='spl_use[]']").val(spl_use);
		}
	});
});

function supply_sequence()
{
	var $tr = $("#sit_supply_frm tr");
	var seq;
	var th_label, td_label;

	$tr.each(function(index) {
		seq = index + 1;
		$(this).find("th label").attr("for", "spl_subject_"+seq).text("추가"+seq);
		$(this).find("th input").attr("id", "spl_subject_"+seq);
		$(this).find("td label").attr("for", "spl_item_"+seq);
		$(this).find("td label b").text("추가"+seq+" 항목");
		$(this).find("td input").attr("id", "spl_item_"+seq);
	});
}
</script>
<!-- //추가옵션 -->
<?php
if ($predit_type=="Y") {
?>
<!-- 에디터 변경 -->
<script type="text/javascript" src="../SE2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">
var oEditors = [];

nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "ir1",
	sSkinURI: "../SE2/SmartEditor2Skin.html",
	htParams : {
		bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
		bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
		bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
		//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
		fOnBeforeUnload : function(){
		}
	},
	fOnAppLoad : function(){
	},
	fCreator: "createSEditor2"
});
</script>
<?php
}
include("copyright.php"); 
