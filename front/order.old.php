<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/product.class.php");
$product = new PRODUCT();
if($_data->member_buygrant=="Y" && strlen($_ShopInfo->getMemid())==0) {	//회원전용일 경우 로긴페이지로...
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}


if($_data->card_payfee<-50){
	$dc_cash_pay = $_data->card_payfee+50;
	$saletype="Y";
}else{
	$dc_cash_pay = $_data->card_payfee;
}

//결제관련
#### PG 데이타 세팅 ####
$_ShopInfo->getPgdata();
$escrow_info = GetEscrowType($_data->escrow_info);
if(ord($escrow_info["escrow_limit"])==0) $escrow_info["escrow_limit"]=100000;
if(ord($_data->escrow_id) && ($escrow_info["escrowcash"]=="Y" || $escrow_info["escrowcash"]=="A")) {
	$escrowok="Y";
} else {
	$escrowok="N";
	$escrow_info["escrowcash"]="";
	if($escrow_info["onlycash"]!="Y" && (ord($escrow_info["onlycard"])==0 && ord($escrow_info["nopayment"])==0)) $escrow_info["onlycash"]="Y";
}

$arrpayinfo=explode("=",$_data->bank_account);
$bank_payinfo = explode(",", $arrpayinfo[0]);

$cardid_info=GetEscrowType($_data->card_id);
//결제관련 종료


//장바구니 인증키 확인
if(strlen($_ShopInfo->getTempkey())==0 || $_ShopInfo->getTempkey()=="deleted") {
	$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
}

if($_REQUEST[selectItem]){
	$sql = "update tblbasket set tempkey='".$_ShopInfo->getTempkey()."' where tempkey = '".$_ShopInfo->getTempkeySelectItem()."'";
	pmysql_query($sql,get_db_conn());
	
	$_REQUEST[selectItem]=str_replace("selectItem=","",$_REQUEST[selectItem]);
	$arrSelectItem = explode("|", $_REQUEST[selectItem]);
	foreach($arrSelectItem as $k => $v){
		if($v) $arrItemSetting[] = $v;
	}
	$strWhere = implode("', '", $arrItemSetting);

	$selectItemQuery = "UPDATE tblbasket SET tempkey = '".$_ShopInfo->getTempkeySelectItem()."' WHERE basketidx not in ('".$strWhere."') AND tempkey='".$_ShopInfo->getTempkey()."'";
	pmysql_query($selectItemQuery);
}
if($_REQUEST[allcheck]){
	$sql = "update tblbasket set tempkey='".$_ShopInfo->getTempkey()."' where tempkey = '".$_ShopInfo->getTempkeySelectItem()."'";
	pmysql_query($sql,get_db_conn());
}


$basketsql2 = "SELECT a.productcode,a.package_idx,a.quantity,c.package_list,c.package_title,c.package_price ";
$basketsql2.= "FROM tblbasket AS a, tblproduct AS b, tblproductpackage AS c ";
$basketsql2.= "WHERE a.productcode=b.productcode ";
$basketsql2.= "AND b.package_num=c.num ";
$basketsql2.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
$basketsql2.= "AND a.package_idx>0 ";
$basketsql2.= "AND b.display = 'Y' ";
$basketresult2 = pmysql_query($basketsql2,get_db_conn());
while($basketrow2=@pmysql_fetch_object($basketresult2)) {
	if(ord($basketrow2->package_title) && ord($basketrow2->package_idx) && $basketrow2->package_idx>0) {
		$package_title_exp = explode("",$basketrow2->package_title);
		$package_price_exp = explode("",$basketrow2->package_price);
		$package_list_exp = explode("", $basketrow2->package_list);

		$title_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx] = $package_title_exp[$basketrow2->package_idx];

		if(strlen($package_list_exp[$basketrow2->package_idx])>1) {
			$basketsql3 = "SELECT productcode,quantity,productname,tinyimage,sellprice FROM tblproduct ";
			$basketsql3.= "WHERE pridx IN ('".str_replace(",","','",ltrim($package_list_exp[$basketrow2->package_idx],','))."') ";
			$basketsql3.= "AND display = 'Y' ";

			$basketresult3 = pmysql_query($basketsql3,get_db_conn());
			$sellprice_package_listtmp=0;
			while($basketrow3=@pmysql_fetch_object($basketresult3)) {
				$assemble_proquantity[$basketrow3->productcode]+=$basketrow2->quantity;
				$productcode_package_listtmp[] = $basketrow3->productcode;
				$quantity_package_listtmp[] = $basketrow3->quantity;
				$productname_package_listtmp[] = $basketrow3->productname;
				$tinyimage_package_listtmp[] = $basketrow3->tinyimage;
				$sellprice_package_listtmp+= $basketrow3->sellprice;
			}
			@pmysql_free_result($basketresult3);

			if(count($productcode_package_listtmp)>0) {  //장바구니 패키지 상품 정보 출력시 필요한 정보
				$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=0;
				if((int)$sellprice_package_listtmp>0) {
					$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=(int)$sellprice_package_listtmp;
					if(ord($package_price_exp[$basketrow2->package_idx])) {
						$package_price_expexp = explode(",",$package_price_exp[$basketrow2->package_idx]);
						if(ord($package_price_expexp[0]) && $package_price_expexp[0]>0) {
							$sumsellpricecal=0;
							if($package_price_expexp[1]=="Y") {
								$sumsellpricecal = ((int)$sellprice_package_listtmp*$package_price_expexp[0])/100;
							} else {
								$sumsellpricecal = $package_price_expexp[0];
							}
							if($sumsellpricecal>0) {
								if($package_price_expexp[2]=="Y") {
									$sumsellpricecal = $sellprice_package_listtmp-$sumsellpricecal;
								} else {
									$sumsellpricecal = $sellprice_package_listtmp+$sumsellpricecal;
								}
								if($sumsellpricecal>0) {
									if($package_price_expexp[4]=="F") {
										$sumsellpricecal = floor($sumsellpricecal/($package_price_expexp[3]*10))*($package_price_expexp[3]*10);
									} else if($package_price_expexp[4]=="R") {
										$sumsellpricecal = round($sumsellpricecal/($package_price_expexp[3]*10))*($package_price_expexp[3]*10);
									} else {
										$sumsellpricecal = ceil($sumsellpricecal/($package_price_expexp[3]*10))*($package_price_expexp[3]*10);
									}
									$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=$sumsellpricecal;
								}
							}
						}
					}
				}

				$productcode_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $productcode_package_listtmp;
				$quantity_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $quantity_package_listtmp;
				$productname_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $productname_package_listtmp;
				$tinyimage_package_list[$basketrow2->productcode][$basketrow2->package_idx] = $tinyimage_package_listtmp;
			}

			$productcode_package_listtmp=array();
			$quantity_package_listtmp=array();
			$productname_package_listtmp=array();
		}
	}
}
@pmysql_free_result($basketresult2);

#수량재고파악
$errmsg="";
$sql = "SELECT a.quantity as sumquantity,a.opt1_idx, b.productcode,b.productname,b.display,b.quantity, ";
$sql.= "b.option_quantity,b.option_ea,b.etctype,b.group_check,b.assembleuse,a.assemble_list AS basketassemble_list ";
$sql.= ", c.assemble_list,a.package_idx ";
$sql.= "FROM tblbasket a, tblproduct b ";
$sql.= "LEFT OUTER JOIN tblassembleproduct c ON b.productcode=c.productcode ";
$sql.= "WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
$sql.= "AND a.productcode=b.productcode ";
$result=pmysql_query($sql,get_db_conn());
$assemble_proquantity_cnt=0;
while($row=pmysql_fetch_object($result)) {

	##### 옵션별 판매수량 에 따른 재고수량 변경 ####
	if($row->option_ea && !$row->option_quantity){
		$temp_option_ea = explode(",",$row->option_ea);
		$row->sumquantity = $temp_option_ea[($row->opt1_idx-1)]*$row->sumquantity;
	}

	if($row->display!="Y") {
		$errmsg="[".str_replace("'","",$row->productname)."]상품은 판매가 되지 않는 상품입니다.\\n";
	}

	$assemble_list_exp = array();
	if(ord($errmsg)==0 && $row->assembleuse=="Y") { // 조립/코디 상품 등록에 따른 구성상품 체크
		if(ord($row->assemble_list)==0) {
			$errmsg="[".str_replace("'","",$row->productname)."]상품은 구성상품이 미등록된 상품입니다. 다른 상품을 주문해 주세요.\\n";
		} else {
			$assemble_list_exp = explode("",$row->basketassemble_list);
		}
	}

	if($row->group_check!="N") {
		if(strlen($_ShopInfo->getMemid())>0) {
			$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode ";
			$sqlgc.= "WHERE productcode='{$row->productcode}' ";
			$sqlgc.= "AND group_code='".$_ShopInfo->getMemgroup()."' ";
			$resultgc=pmysql_query($sqlgc,get_db_conn());
			if($rowgc=@pmysql_fetch_object($resultgc)) {
				if($rowgc->groupcheck_count<1) {
					$errmsg="[".str_replace("'","",$row->productname)."]상품은 지정 등급 전용 상품입니다.\\n";
				}
				@pmysql_free_result($resultgc);
			} else {
				$errmsg="[".str_replace("'","",$row->productname)."]상품은 지정 등급 전용 상품입니다.\\n";
			}
		} else {
			$errmsg="[".str_replace("'","",$row->productname)."]상품은 회원 전용 상품입니다.\\n";
		}
	}

	$package_productcode_tmp = array();
	$package_quantity_tmp = array();
	$package_productname_tmp = array();
	if(ord($errmsg)==0 && $row->package_idx>0) { // 패키지 상품 등록에 따른 구성상품 체크
		if(count($productcode_package_list[$row->productcode][$row->package_idx])>0) {
			$package_productcode_tmp = $productcode_package_list[$row->productcode][$row->package_idx];
			$package_quantity_tmp = $quantity_package_list[$row->productcode][$row->package_idx];
			$package_productname_tmp = $productname_package_list[$row->productcode][$row->package_idx];
		}
	}

	if(ord($errmsg)==0) {
		$miniq=1;
		$maxq="?";
		if(ord($row->etctype)) {
			$etctemp = explode("",$row->etctype);
			for($i=0;$i<count($etctemp);$i++) {
				if(strpos($etctemp[$i],"MINIQ=")===0)     $miniq=substr($etctemp[$i],6);
				if(strpos($etctemp[$i],"MAXQ=")===0)      $maxq=substr($etctemp[$i],5);
			}
		}

		if(strlen(dickerview($row->etctype,0,1))>0) {
			$errmsg="[".str_replace("'","",$row->productname)."]상품은 판매가 되지 않습니다. 다른 상품을 주문해 주세요.\\n";
		}
	}

	if(ord($errmsg)==0) {
		if ($miniq!=1 && $miniq>1 && $row->sumquantity<$miniq)
			$errmsg.="[".str_replace("'","",$row->productname)."]상품은 최소 {$miniq}개 이상 주문하셔야 합니다.\\n";

		if ($maxq!="?" && $maxq>0 && $row->sumquantity>$maxq)
			$errmsg.="[".str_replace("'","",$row->productname)."]상품은 최대 {$maxq}개 이하로 주문하셔야 합니다.\\n";

		if(ord($row->quantity)) {
			if ($row->sumquantity>$row->quantity) {
				if ($row->quantity>0)
					$errmsg.="[".str_replace("'","",$row->productname)."]상품의 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"현재 {$row->quantity} 개 입니다.")."\\n";
				else
					$errmsg.= "[".str_replace("'","",$row->productname)."]상품의 재고가 다른고객 주문등의 이유로 장바구니 수량보다 작습니다.\\n";
			}
		}
		if($assemble_proquantity_cnt==0) { //일반 및 구성상품들의 재고량 가져오기
			///////////////////////////////// 코디/조립 기능으로 인한 재고량 체크 ///////////////////////////////////////////////
			$basketsql = "SELECT productcode,assemble_list,quantity,assemble_idx FROM tblbasket WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$basketresult = pmysql_query($basketsql,get_db_conn());
			while($basketrow=@pmysql_fetch_object($basketresult)) {
				if($basketrow->assemble_idx>0) {
					if(ord($basketrow->assemble_list)) {
						$assembleprolistexp = explode("",$basketrow->assemble_list);
						for($i=0; $i<count($assembleprolistexp); $i++) {
							if(ord($assembleprolistexp[$i])) {
								$assemble_proquantity[$assembleprolistexp[$i]]+=$basketrow->quantity;
							}
						}
					}
				} else {
					$assemble_proquantity[$basketrow->productcode]+=$basketrow->quantity;
				}
			}
			@pmysql_free_result($basketresult);
			$assemble_proquantity_cnt++;
		}
		if(count($assemble_list_exp)>0) { // 구성상품의 재고 체크
			$assemprosql = "SELECT productcode,quantity,productname FROM tblproduct ";
			$assemprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_exp)."') ";
			$assemprosql.= "AND display = 'Y' ";
			$assemproresult=pmysql_query($assemprosql,get_db_conn());
			while($assemprorow=@pmysql_fetch_object($assemproresult)) {
				if(ord($assemprorow->quantity)) {
					if($assemble_proquantity[$assemprorow->productcode]>$assemprorow->quantity) {
						if($assemprorow->quantity>0) {
							$errmsg.="[".str_replace("'","",$row->productname)."]상품의 구성상품 [".str_replace("'","",$assemprorow->productname)."] 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"현재 {$assemprorow->quantity} 개 입니다.")."\\n";
						} else {
							$errmsg.="[".str_replace("'","",$row->productname)."]상품의 구성상품 [".str_replace("'","",$assemprorow->productname)."] 다른 고객의 주문으로 품절되었습니다.\\n";
						}
					}
				}
			}
			@pmysql_free_result($assemproresult);
		} else if(count($package_productcode_tmp)>0) {
			//$package_productcode_tmpexp = explode("",$package_productcode_tmp);
			//$package_quantity_tmpexp = explode("",$package_quantity_tmp);
			//$package_productname_tmpexp = explode("",$package_productname_tmp);
			$package_productcode_tmpexp = $package_productcode_tmp;
			$package_quantity_tmpexp = $package_quantity_tmp;
			$package_productname_tmpexp = $package_productname_tmp;
			for($i=0; $i<count($package_productcode_tmpexp); $i++) {
				if(ord($package_productcode_tmpexp[$i])) {
					if(ord($package_quantity_tmpexp[$i])) {
						if($assemble_proquantity[$package_productcode_tmpexp[$i]] > $package_quantity_tmpexp[$i]) {
							if($package_quantity_tmpexp[$i]>0) {
								$errmsg.="해당 상품의 패키지 [".str_replace("'","",$package_productname_tmpexp[$i])."] 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"현재 {$package_quantity_tmpexp[$i]} 개 입니다.")."\\n";
							} else {
								$errmsg.="해당 상품의 패키지 [".str_replace("'","",$package_productname_tmpexp[$i])."] 다른 고객의 주문으로 품절되었습니다.\\n";
							}
						}
					}
				}
			}
		} else { // 일반상품의 재고 체크
			if(ord($row->quantity)) {
				if($assemble_proquantity[$assemprorow->productcode]>$row->quantity) {
					if ($row->quantity>0) {
						$errmsg.="[".str_replace("'","",$row->productname)."]상품의 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"현재 {$row->quantity} 개 입니다.")."\\n";
					} else {
						$errmsg.= "[".str_replace("'","",$row->productname)."]상품의 재고가 다른고객 주문등의 이유로 장바구니 수량보다 작습니다.\\n";
					}
				}
			}
		}
		if(ord($row->option_quantity)) {
			$sql = "SELECT opt1_idx, opt2_idx, quantity FROM tblbasket ";
			$sql.= "WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$sql.= "AND productcode='{$row->productcode}' ";
			$result2=pmysql_query($sql,get_db_conn());
			while($row2=pmysql_fetch_object($result2)) {
				$optioncnt = explode(",",ltrim($row->option_quantity,','));
				$optionvalue=$optioncnt[($row2->opt2_idx==0?0:($row2->opt2_idx-1))*10+($row2->opt1_idx-1)];

				if($optionvalue<=0 && $optionvalue!="") {
					$errmsg.="[".str_replace("'","",$row->productname)."]상품의 옵션은 다른 고객의 주문으로 품절되었습니다.\\n";
				} else if($optionvalue<$row2->quantity && $optionvalue!="") {
					$errmsg.="[".str_replace("'","",$row->productname)."]상품의 선택된 옵션의 재고가 ".($_data->ETCTYPE["STOCK"]=="N"?"부족합니다.":"$optionvalue 개 입니다.")."\\n";
				}
			}
			pmysql_free_result($result2);
		}
	}
}
pmysql_free_result($result);

if(ord($errmsg)) {
	echo "<html></head><body onload=\"alert('{$errmsg}');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";
	exit;
}

$card_miniprice=$_data->card_miniprice;
$deli_area=$_data->deli_area;
$admin_message = $_data->order_msg;
$reserve_limit = $_data->reserve_limit;
$reserve_maxprice = $_data->reserve_maxprice;
if($reserve_limit==0) $reserve_limit=1000000000000;
if($_data->rcall_type=="Y") {
	$rcall_type = $_data->rcall_type;
	$bankreserve="Y";
} else if($_data->rcall_type=="N") {
	$rcall_type = $_data->rcall_type;
	$bankreserve="Y";
} else if($_data->rcall_type=="M") {
	$rcall_type="Y";
	$bankreserve="N";
} else {
	$rcall_type="N";
	$bankreserve="N";
}
$etcmessage=explode("=",$admin_message);

$user_reserve=0;
if(strlen($_ShopInfo->getMemid())>0) {
	$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
	$result = pmysql_query($sql,get_db_conn());
	if($row = pmysql_fetch_object($result)) {

		$reserve_chk= $row->reserve_chk;
		$user_reserve = $row->reserve;
		if($user_reserve>$reserve_limit) {
			$okreserve=$reserve_limit;
			$remainreserve=$user_reserve-$reserve_limit;
		} else {
			$okreserve=$user_reserve;
			$remainreserve=0;
		}
		$home_addr="";
		if(strlen($row->home_post)==6) {
			$home_post1=substr($row->home_post,0,3);
			$home_post2=substr($row->home_post,3,3);
		}
		$row->home_addr = str_replace("\"","",$row->home_addr);
		$home_addr = explode("=",$row->home_addr);
		$home_addr1 = $home_addr[0];
		$home_addr2 = $home_addr[1];

		$office_addr="";
		if(strlen($row->office_post)==6) {
			$office_post1=substr($row->office_post,0,3);
			$office_post2=substr($row->office_post,3,3);
		}
		$row->office_addr = str_replace("\"","",$row->office_addr);
		$office_addr = explode("=",$row->office_addr);
		$office_addr1 = $office_addr[0];
		$office_addr2 = $office_addr[1];

		$name = $row->name;
		$email = $row->email;
		if (ord($row->mobile)) $mobile = $row->mobile;
		else if (ord($row->home_tel)) $mobile = $row->home_tel;
		else if (ord($row->office_tel)) $mobile = $row->office_tel;
		$mobile=explode("-",replace_tel(check_num($mobile)));
		$home_tel=explode("-",replace_tel(check_num($row->home_tel)));

		$group_code=$row->group_code;
		pmysql_free_result($result);
		if(ord($group_code) && $group_code!=NULL) {
			$sql = "SELECT * FROM tblmembergroup WHERE group_code='{$group_code}' AND SUBSTR(group_code,1,1)!='M' ";
			$result=pmysql_query($sql,get_db_conn());
			if($row=pmysql_fetch_object($result)){
				$group_code = $row->group_code;
				$group_level=$row->group_level;
				$group_deli_free=$row->group_deli_free;
				$org_group_name=$row->group_name;  //그룹정보로 인해 추가
				$group_name=$row->group_name;
				$group_type=substr($row->group_code,0,2);
				$group_usemoney=$row->group_usemoney;
				$group_addmoney=$row->group_addmoney;
				$group_payment=$row->group_payment;
				if($group_payment=="B") $group_name.=" (현금결제시)";
				else if($group_payment=="C") $group_name.=" (카드결제시)";
			}
			pmysql_free_result($result);
		}
	} else {

		$_ShopInfo->setMemid("");
	}
}

$sql = "SELECT privercy FROM tbldesign ";
$result=pmysql_query($sql,get_db_conn());
if($row=pmysql_fetch_object($result)) {
	$privercy_exp = @explode("=", $row->privercy);
	$privercybody=$privercy_exp[1];
}
pmysql_free_result($result);

if(ord($privercybody)==0) {
	$buffer = file_get_contents($Dir.AdminDir."privercy2.txt");
	$privercybody=$buffer;
}

$pattern=array("[SHOP]","[NAME]","[EMAIL]","[TEL]");
$replace=array($_data->shopname,$_data->privercyname,"<a href=\"mailto:{$_data->privercyemail}\">{$_data->privercyemail}</a>",$_data->info_tel);
$privercybody = str_replace($pattern,$replace,$privercybody);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<HEAD>
<TITLE><?=$_data->shoptitle?> - 주문서 작성</TITLE>
<META name="description" content="<?=(ord($_data->shopdescription)?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.10.1.min.js"></script>
<?php include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--

$(window).ready(function(){
	var deli_price = $("#delivery_price").html();

	$(".deli_type, .dev_payment, .receipt_yn").click(function(){
		var deli_type = $(".deli_type:checked").val();
		if(deli_type=="1"){
			$("#delivery_price").html(0);
		}else{
			$("#delivery_price").html(deli_price);
		}
		calcuPayment();
	});

	$(".CLS_deliMsg").click(function(){
		$(this).parent().parent().parent().prev().val($(this).html());
		$('div.delivery_message').css('display' , 'none');
	})
});

function calcuPayment(){
	var paymethod = $(".dev_payment:checked").val();
	var receipt_yn = $(".receipt_yn:checked").val();
	$.get("product_dc_price.php?paymethod="+paymethod+"&receipt_yn="+receipt_yn,function(data,status){

		$("#memberdc_price").html(comma(data)); 

		payment_product_price = uncomma($("#paper_goodsprice").html());
		payment_delivery_price = uncomma($("#delivery_price").html());
		payment_memberdc_price = data; 
		//uncomma($("#memberdc_price").html());
		payment_coupondc_price = uncomma($("#coupon_dc").val());
		payment_usereserve_price = uncomma($("#usereserve").val());

		pament_price = payment_product_price + payment_delivery_price - payment_memberdc_price - payment_coupondc_price - payment_usereserve_price;
		$("#price_sum").html(comma(pament_price));

	
	} );

	chgPayDataSel(paymethod,receipt_yn);
}

function chgPayDataSel(paymethod,receipt_yn){
	
	$("#pay_data_sel").html("");
	if(paymethod=="B" && receipt_yn=="N"){
		$("#pay_data_sel").append("<option value='' >입금 계좌번호 선택 (반드시 주문자 성함으로 입금)</option>");
		$("#pay_data_sel").append("<option value='<?=$bank_payinfo[2]?>'' ><?=$bank_payinfo[2]?></option>");
	}else{
		$("#pay_data_sel").append("<option value='' >입금 계좌번호 선택 (반드시 주문자 성함으로 입금)</option>");
		$("#pay_data_sel").append("<option value='<?=$bank_payinfo[0]?>'' ><?=$bank_payinfo[0]?></option>");
		$("#pay_data_sel").append("<option value='<?=$bank_payinfo[1]?>'' ><?=$bank_payinfo[1]?></option>");
	}
}



function change_message(gbn) {
	if(gbn==1) {
		document.all["msg_idx2"].style.display="none";
		document.all["msg_idx1"].style.display="";
		document.form1.msg_type.value=gbn;
	} else if(gbn==2) {
		document.all["msg_idx2"].style.display="";
		document.all["msg_idx1"].style.display="none";
		document.form1.msg_type.value=gbn;
	}
}
function SameCheck(checked) {
	if(checked) {
		document.form1.receiver_name.value=document.form1.sender_name.value;
		document.form1.receiver_tel11.value="<?=$home_tel[0]?>";
		document.form1.receiver_tel12.value="<?=$home_tel[1]?>";
		document.form1.receiver_tel13.value="<?=$home_tel[2]?>";
		document.form1.receiver_tel21.value=document.form1.sender_tel1.value;
		document.form1.receiver_tel22.value=document.form1.sender_tel2.value;
		document.form1.receiver_tel23.value=document.form1.sender_tel3.value;

		document.form1.rpost1.value="<?=$home_post1?>";
		document.form1.rpost2.value="<?=$home_post2?>";
		document.form1.raddr1.value="<?=$home_addr1?>";
		document.form1.raddr2.value="<?=$home_addr2?>";
	} else {
		document.form1.receiver_name.value="";
		document.form1.receiver_tel11.value="";
		document.form1.receiver_tel12.value="";
		document.form1.receiver_tel13.value="";
		document.form1.receiver_tel21.value="";
		document.form1.receiver_tel22.value="";
		document.form1.receiver_tel23.value="";

		document.form1.rpost1.value='';
		document.form1.rpost2.value='';
		document.form1.raddr1.value='';
		document.form1.raddr2.value='';
	}
}
<?php if(strlen($_ShopInfo->getMemid())>0){?>
function addrchoice() {
	window.open("<?=$Dir.FrontDir?>addrbygone.php","addrbygone","width=100,height=100,toolbar=no,menubar=no,scrollbars=yes,status=no");
}

function coupon_cancel(){
	$("#ID_coupon_code_layer").html("");
	document.form1.coupon_code.value=0;
	document.form1.coupon_dc.value=0;
	document.form1.coupon_reserve.value=0;

	document.getElementById("sumprice").value=parseInt(document.form1.total_sum.value)-(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value));

	document.getElementById("price_sum").innerHTML=comma(parseInt(document.form1.total_sum.value)-(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value)));

	payment_reset();
}

function reserve_check(temp) {
	r_type="<?=$rcall_type?>";

	if(r_type=="N" && document.form1.coupon_code.value){
		document.form1.usereserve.value=0;
		document.form1.okreserve.value=temp;
		document.form1.usereserve.focus();
		alert('쿠폰과 적립금은 동시 사용이 불가능합니다.');
		//return;
	}
	temp=parseInt(temp);
	if(isNaN(document.form1.usereserve.value)) {
		document.form1.usereserve.value=0;
		document.form1.okreserve.value=temp;
		document.form1.usereserve.focus();
		alert('숫자만 입력하셔야 합니다.');
		//return;
	}
	if(parseInt(document.form1.usereserve.value)>temp) {
		document.form1.usereserve.value=0;
		document.form1.okreserve.value=temp;
		document.form1.usereserve.focus();
		alert('사용가능 적립금 보다 적거나 똑같이 입력하셔야 합니다.');
		//return;
	}
	if(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value)>parseInt(document.form1.total_sum.value)){
		//document.getElementById("dc_price").innerHTML=comma(parseInt(document.form1.coupon_dc.value));
		document.getElementById("price_sum").innerHTML=comma(parseInt(document.form1.total_sum.value)-parseInt(document.form1.coupon_dc.value));
		document.form1.usereserve.value=0;
		document.form1.okreserve.value=temp;
		document.form1.usereserve.focus();
		alert('총 합계 금액 보다 적거나 똑같이 입력하셔야 합니다.');
		//return;
	}
	document.form1.okreserve.value=parseInt(temp-document.form1.usereserve.value);
	document.form1.usereserve.value=temp-document.form1.okreserve.value;

	//document.getElementById("dc_price").innerHTML=comma(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value));
	document.getElementById("sumprice").value=parseInt(document.form1.total_sum.value)-(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value));

	document.getElementById("price_sum").innerHTML=comma(parseInt(document.form1.total_sum.value)-(parseInt(document.form1.coupon_dc.value)+parseInt(document.form1.usereserve.value)));

	payment_reset();
}
<?php }?>


function payment_reset(){

	for(var i=0;i<document.getElementsByName("dev_payment").length;i++){
		document.getElementsByName("dev_payment")[i].checked=false;
	}
}
function get_post() {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form=form1&post=rpost&addr=raddr1&gbn=2","f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");
}

<?php if(strlen($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y"){?>
function coupon_check(){
	window.open("about:blank","couponpopup","width=650,height=650,toolbar=no,menubar=no,scrollbars=yes,status=no");
	document.couponform.submit();
}
<?php }?>

function orderpaypop() {
	if(typeof(document.form1.usereserve)!="undefined") {
		document.orderpayform.usereserve.value=document.form1.usereserve.value;
	}
	if(typeof(document.form1.coupon_code)!="undefined") {
		document.orderpayform.coupon_code.value=document.form1.coupon_code.value;
	}
	document.orderpayform.email.value=document.form1.sender_email.value;
	document.orderpayform.address.value=document.form1.raddr1.value;
	document.orderpayform.mobile_num1.value=document.form1.sender_tel1.value;
	document.orderpayform.mobile_num.value=document.form1.sender_tel1.value+"-"+document.form1.sender_tel2.value+"-"+document.form1.sender_tel3.value

	var winpaypop=window.open("about:blank","orderpaypop","width=620,height=550,scrollbars=yes");
	winpaypop.focus();

<?php if($_data->ssl_type=="Y" && ord($_data->ssl_domain) && ord($_data->ssl_port) && $_data->ssl_pagelist["ORDER"]=="Y") {?>
	document.orderpayform.action='https://<?=$_data->ssl_domain?><?=($_data->ssl_port!="443"?":".$_data->ssl_port:"")?>/<?=RootPath.SecureDir?>orderpay.php';
<?php }?>

	document.orderpayform.submit();
}

function ordercancel(gbn) {
	if(gbn=="cancel" && document.form1.process.value=="N") {
		document.location.href="basket.php";
	} else {
		if (PROCESS_IFRAME.chargepop) {
			if (gbn=="cancel") alert("결제창과 연결중입니다. 취소하시려면 결제창에서 취소하기를 누르세요.");
			PROCESS_IFRAME.chargepop.focus();
		} else {
			PROCESS_IFRAME.PaymentOpen();
			ProcessWait('visible');
		}

	}
}

function ProcessWait(display) {
	var PAYWAIT_IFRAME = document.all.PAYWAIT_IFRAME;

	document.paywait.src = "<?=$Dir?>images/paywait.gif";
	var _x = document.body.clientWidth/2 + document.body.scrollLeft - 250;
	var _y = document.body.clientHeight/2 + document.body.scrollTop - 120;

	PAYWAIT_IFRAME.style.visibility=display;
	PAYWAIT_IFRAME.style.posLeft=_x;
	PAYWAIT_IFRAME.style.posTop=_y;

	PAYWAIT_LAYER.style.posLeft=_x;
	PAYWAIT_LAYER.style.posTop=_y;
	PAYWAIT_LAYER.style.visibility=display;
}

function ProcessWaitPayment() {
	var PAYWAIT_IFRAME = document.all.PAYWAIT_IFRAME;

	document.paywait.src = "<?=$Dir?>images/paywait2.gif";

	$("#PAYWAIT_LAYER").show();

	var t = $(document).scrollTop();
	var w = ($(window).width()-$("#PAYWAIT_LAYER").width())/2;
	var h = ($(window).height()-$("#PAYWAIT_LAYER").height())/2;


//				$("#PAYWAIT_LAYER").css("position","absolute");
	$("#PAYWAIT_IFRAME").css("display","block");
	$("#PAYWAIT_IFRAME").css("left",w);
	$("#PAYWAIT_IFRAME").css("top",t+h);

	$("#PAYWAIT_LAYER").css("display","block");
	$("#PAYWAIT_LAYER").css("left",w);
	$("#PAYWAIT_LAYER").css("top",t+h);
	/*
	var _x = document.body.clientWidth/2 + document.body.scrollLeft - 250;
	var _y = document.body.clientHeight/2 + document.body.scrollTop - 120;

	PAYWAIT_IFRAME.style.display='block';
	PAYWAIT_IFRAME.style.posLeft=_x;
	PAYWAIT_IFRAME.style.posTop=_y;

	PAYWAIT_LAYER.style.display='block';
	PAYWAIT_LAYER.style.posLeft=_x;
	PAYWAIT_LAYER.style.posTop=_y;
	*/
}

function PaymentOpen() {
	PROCESS_IFRAME.PaymentOpen();
	ProcessWait('visible');
}

function setPackageShow(packageid) {
	if(packageid.length>0 && document.getElementById(packageid)) {
		if(document.getElementById(packageid).style.display=="none") {
			document.getElementById(packageid).style.display="";
		} else {
			document.getElementById(packageid).style.display="none";
		}
	}
}

function comma(x)
{
	var temp = "";
	var x = String(uncomma(x));

	num_len = x.length;
	co = 3;
	while (num_len>0){
		num_len = num_len - co;
		if (num_len<0){
			co = num_len + co;
			num_len = 0;
		}
		temp = ","+x.substr(num_len,co)+temp;
	}
	return temp.substr(1);
}
function uncomma(x)
{
	var reg = /(,)*/g;
	x = parseInt(String(x).replace(reg,""));
	return (isNaN(x)) ? 0 : x;
}

function sel_paymethod(obj){

	var frm=document.form1;
	var	totp=uncomma(document.getElementById("price_sum").innerHTML);

	if(obj.value=='B'){
		document.getElementById("card_type").style.display="block";
	}else{
		document.getElementById("card_type").style.display="none";
		frm.pay_data1.value='';
	}

	if(obj.value=='Q'){

		if(frm.escrowcash.value=='Y' && (frm.escrow_limit.value>parseInt(totp))){

			alert('총 결제금액이'+frm.escrowcash.value+'이상일때만 에스크로 결제가 가능합니다.');
			frm.paymethod.value='';
			obj.checked=false;
			return;
		}
	}

	frm.paymethod.value=obj.value;
}

function sel_account(obj){

	var frm=document.form1;

	frm.pay_data1.value=obj.value;
}

function go_basket(){

	document.location.href="../../front/basket.php";
}
//-->
</SCRIPT>

</HEAD>

<body<?=($_data->layoutdata["MOUSEKEY"][0]=="Y"?" oncontextmenu=\"return false;\"":"")?><?=($_data->layoutdata["MOUSEKEY"][1]=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=($_data->layoutdata["MOUSEKEY"][2]=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<?
	if($_data->design_order[0]=="T") $_data->menu_type="nomenu";
	include ($Dir.MainDir.$_data->menu_type.".php");
?>
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
<?php

if($_data->icon_type=="001" || $_data->icon_type=="002" || $_data->icon_type=="003"){
	
if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/order_title.gif")) {
	echo "<td><img src=\"".$Dir.DataDir."design/order_title.gif\" border=\"0\" alt=\"주문서작성\"></td>\n";
} else {
	echo "<td>\n";
	echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
	echo "<TR>\n";
	echo "	<TD><IMG SRC={$Dir}images/{$_data->icon_type}/order_title_head.gif></TD>\n";
	echo "	<TD width=100% valign=top background={$Dir}images/{$_data->icon_type}/order_title_bg.gif></TD>\n";
	echo "	<TD width=40><IMG SRC={$Dir}images/{$_data->icon_type}/order_title_tail.gif ALT=></TD>\n";
	echo "</TR>\n";
	echo "</TABLE>\n";
	echo "</td>\n";
}
}

?>
</tr>
<tr>
	<td align=center>
	<?php  
	
//		include ($Dir.TempletDir."order/order{$_data->design_order}.php"); 
		include ($Dir.TempletDir."order/orderTEM01.old.php"); 
	
	?>
	</td>
</tr>
<tr>
	<td align=center>
	<div id="paybuttonlayer" name="paybuttonlayer" style="display:block;">
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr>
		<td align=center>
		<div class="btn_orderSheet">
		<ul>
			<li><A HREF="javascript:ordercancel('cancel')" onmouseover="window.status='취소';return true;"><img src="../image/cart/btn_cancel.gif" alt="취소하기" /></A></li>
			<li><A HREF="javascript:CheckForm()" onmouseover="window.status='결제';return true;"><img src="../image/cart/btn_order2.gif" alt="주문하기" /></A></li>
		</ul>
		</div>
		</td>
	</tr>
	</table>

	</div>
	<div id="payinglayer" name="payinglayer" style="display:none;">
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr>
		<td align=center><img src="<?=$Dir?>images/common/paying_wait.gif" border=0></td>
	</tr>
	</table>
	</div>
	</td>
</tr>
</table>

<?php

if($sumprice<$_data->bank_miniprice) {
	echo "<script>alert('주문 가능한 최소 금액은 ".number_format($_data->bank_miniprice)."원 입니다.');location.href='".$Dir.FrontDir."basket.php';</script>";
	exit;
} else if($sumprice<=0) {
	echo "<script>alert('상품 총 가격이 0원일 경우 상품 주문이 되지 않습니다.');location.href='".$Dir.FrontDir."basket.php';</script>";
	exit;
}

//if(strlen($_ShopInfo->getMemid())>0) echo "<script>document.form1.addrtype[0].checked=true;addrchoice();</script>";
?>
<input type=hidden name=process id=process value="N">
<input type=hidden name=escrow_limit value="<?=$escrow_info["escrow_limit"]?>">
<input type=hidden name=escrowcash value="<?=$escrow_info["escrowcash"]?>">
<input type=hidden name=paymethod>
<!--input type=text name=bank_sender-->
<input type=hidden name=pay_data1>
<input type=hidden name=pay_data2>
<input type=hidden name=sender_resno>
<input type=hidden name=sender_tel>
<input type=hidden name=receiver_tel1>
<input type=hidden name=receiver_tel2>
<input type=hidden name=receiver_addr>
<input type=hidden name=order_msg>
<?php if($_data->ssl_type=="Y" && ord($_data->ssl_domain) && ord($_data->ssl_port) && $_data->ssl_pagelist["ORDER"]=="Y") {?>
<input type=hidden name=shopurl value="<?=$_SERVER['HTTP_HOST']?>">
<?php }?>
</form>

<form name=couponform action="<?=$Dir.FrontDir?>coupon.php" method=post target=couponpopup>
<input type=hidden name=sumprice id="sumprice" value="<?=$sumprice?>">
</form>

<form name=orderpayform method=post action="<?=$Dir.FrontDir?>orderpay.php" target=orderpaypop>
<?php if($_data->ssl_type=="Y" && ord($_data->ssl_domain) && ord($_data->ssl_port) && $_data->ssl_pagelist["ORDER"]=="Y") {?>
<input type=hidden name=shopurl value="<?=$_SERVER['HTTP_HOST']?>">
<?php }?>
<input type=hidden name=coupon_code>
<input type=hidden name=usereserve>
<input type=hidden name=email>
<input type=hidden name=mobile_num1>
<input type=hidden name=mobile_num>
<input type=hidden name=address>
</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm() {


	paymethod=document.form1.paymethod.value.substring(0,1);



	<?php  if(strlen($_ShopInfo->getMemid())==0) { ?>
	if(document.form1.dongi[0].checked!=true) {
		alert("개인정보보호정책에 동의하셔야 비회원 주문이 가능합니다.");
		document.form1.dongi[0].focus();
		return;
	}
	if(document.form1.sender_name.type=="text") {
		if(document.form1.sender_name.value.length==0) {
			alert("주문자 성함을 입력하세요.");
			document.form1.sender_name.focus();
			return;
		}
		if(!chkNoChar(document.form1.sender_name.value)) {
			alert("주문자 성함에 \\(역슬래쉬) ,  '(작은따옴표) , \"(큰따옴표)는 입력하실 수 없습니다.");
			document.form1.sender_name.focus();
			return;
		}
	}
	<?php  } ?>

	ispaymentcheck=false;
	for(i=0;i<document.form1.dev_payment.length;i++) {
		if(document.form1.dev_payment[i].checked) {
			ispaymentcheck=true;
			break;
		}
	}
	if(ispaymentcheck==false) {
		alert("결제방법을 선택하세요.");
		document.form1.paymethod.value="";
		return;
	}
	

	if(document.form1.paymethod.value=='B' && document.form1.bank_sender.value==''){
		alert("입금자명을 입력하세요.");
		document.form1.bank_sender.focus();
		return;
	}
	

	if(document.form1.paymethod.value=='B' && document.form1.pay_data_sel.value==''){
		alert("입금계좌를 선택하세요.");
		return;
	}
	<?if($_ShopInfo->memid && $_ShopInfo->wsmember=="Y"){?>
	if(document.form1.paymethod.value=='B' && $(".receipt_yn:checked").length==0){
		alert("영수증 신청여부를 선택하세요.");
		return;
	}
	<?}?>

	if(document.form1.sender_tel1.value.length==0) {
		alert("주문자 전화번호를 입력하세요.");
		document.form1.sender_tel1.focus();
		return;
	}
	if(document.form1.sender_tel2.value.length==0) {
		alert("주문자 전화번호를 입력하세요.");
		document.form1.sender_tel2.focus();
		return;
	}
	if(document.form1.sender_tel3.value.length==0) {
		alert("주문자 전화번호를 입력하세요.");
		document.form1.sender_tel3.focus();
		return;
	}
	if(!IsNumeric(document.form1.sender_tel1.value)) {
		alert("주문자 전화번호 입력은 숫자만 입력하세요.");
		document.form1.sender_tel1.focus();
		return;
	}
	if(!IsNumeric(document.form1.sender_tel2.value)) {
		alert("주문자 전화번호 입력은 숫자만 입력하세요.");
		document.form2.sender_tel2.focus();
		return;
	}
	if(!IsNumeric(document.form1.sender_tel3.value)) {
		alert("주문자 전화번호 입력은 숫자만 입력하세요.");
		document.form3.sender_tel3.focus();
		return;
	}
	document.form1.sender_tel.value=document.form1.sender_tel1.value+"-"+document.form1.sender_tel2.value+"-"+document.form1.sender_tel3.value;

	if(document.form1.sender_email.value.length>0) {
		if(!IsMailCheck(document.form1.sender_email.value)) {
			alert("주문자 이메일 형식이 잘못되었습니다.");
			document.form1.sender_email.focus();
			return;
		}
	}

	if(document.form1.receiver_name.value.length==0) {
		alert("받는분 성함을 입력하세요.");
		document.form1.receiver_name.focus();
		return;
	}
	if(!chkNoChar(document.form1.receiver_name.value)) {
		alert("받는분 성함에 \\(역슬래쉬) ,  '(작은따옴표) , \"(큰따옴표)는 입력하실 수 없습니다.");
		document.form1.receiver_name.focus();
		return;
	}
	if(document.form1.receiver_tel11.value.length==0) {
		alert("받는분 전화번호를 입력하세요.");
		document.form1.receiver_tel11.focus();
		return;
	}
	if(document.form1.receiver_tel12.value.length==0) {
		alert("받는분 전화번호를 입력하세요.");
		document.form1.receiver_tel12.focus();
		return;
	}
	if(document.form1.receiver_tel13.value.length==0) {
		alert("받는분 전화번호를 입력하세요.");
		document.form1.receiver_tel13.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel11.value)) {
		alert("받는분 전화번호 입력은 숫자만 입력하세요.");
		document.form1.receiver_tel11.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel12.value)) {
		alert("받는분 전화번호 입력은 숫자만 입력하세요.");
		document.form1.receiver_tel12.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel13.value)) {
		alert("받는분 전화번호 입력은 숫자만 입력하세요.");
		document.form1.receiver_tel13.focus();
		return;
	}
	document.form1.receiver_tel1.value=document.form1.receiver_tel11.value+"-"+document.form1.receiver_tel12.value+"-"+document.form1.receiver_tel13.value;

	if(document.form1.receiver_tel21.value.length==0) {
		alert("받는분 비상전화번호를 입력하세요.");
		document.form1.receiver_tel21.focus();
		return;
	}
	if(document.form1.receiver_tel22.value.length==0) {
		alert("받는분 비상전화번호를 입력하세요.");
		document.form1.receiver_tel22.focus();
		return;
	}
	if(document.form1.receiver_tel23.value.length==0) {
		alert("받는분 비상전화번호를 입력하세요.");
		document.form1.receiver_tel23.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel21.value)) {
		alert("받는분 비상전화번호 입력은 숫자만 입력하세요.");
		document.form1.receiver_tel21.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel22.value)) {
		alert("받는분 비상전화번호 입력은 숫자만 입력하세요.");
		document.form1.receiver_tel22.focus();
		return;
	}
	if(!IsNumeric(document.form1.receiver_tel23.value)) {
		alert("받는분 비상전화번호 입력은 숫자만 입력하세요.");
		document.form1.receiver_tel23.focus();
		return;
	}
	document.form1.receiver_tel2.value=document.form1.receiver_tel21.value+"-"+document.form1.receiver_tel22.value+"-"+document.form1.receiver_tel23.value;

	if(document.form1.rpost1.value.length==0 || document.form1.rpost2.value.length==0) {
		alert("우편번호를 선택하세요.");
		get_post();
		return;
	}
	if(document.form1.raddr1.value.length==0) {
		alert("주소를 입력하세요.");
		document.form1.raddr1.focus();
		return;
	}
	if(document.form1.raddr2.value.length==0) {
		alert("상세주소를 입력하세요.");
		document.form1.raddr2.focus();
		return;
	}
	if(!chkNoChar(document.form1.raddr2.value)) {
		alert("상세주소에 \\(역슬래쉬) ,  '(작은따옴표) , \"(큰따옴표)는 입력하실 수 없습니다.");
		document.form1.raddr2.focus();
		return;
	}


	if(paymethod.length==0) {
		alert('결제 수단을 선택해주세요.');
		//orderpaypop();
		return;
	}


<?php  if(strlen($_ShopInfo->getMemid())>0) { ?>
	<?php  if($_data->reserve_maxuse>=0 && ord($okreserve) && $okreserve>0) { ?>
	if(document.form1.usereserve.value > <?=$okreserve?>) {
		alert("적립금 사용가능금액보다 큽니다.");
		document.form1.usereserve.focus();
		return;
	} else if(document.form1.usereserve.value < 0) {
		alert("적립금은 0원보다 크게 사용하셔야 합니다.");
		document.form1.usereserve.focus();
		return;
	}
	<?php  } ?>

	<?php  if($_data->reserve_maxuse>=0 && ord($okreserve) && $okreserve>0 && $_data->coupon_ok=="Y" && $rcall_type=="N") { ?>
	if(document.form1.usereserve.value>0 && document.form1.coupon_code.value.length==8){
		alert('적립금과 쿠폰을 동시에 사용이 불가능합니다.\n둘중에 하나만 사용하시기 바랍니다.');
		document.form1.usereserve.focus();
		return;
	}
	<?php  } ?>

	<?php  if($_data->reserve_maxuse>=0 && $bankreserve=="N") { ?>
	if (document.form1.usereserve.value>0) {
		if(paymethod!="B" && paymethod!="V" && paymethod!="O" && paymethod!="Q") {
			alert('적립금은 현금결제시에만 사용이 가능합니다.\n현금결제로 선택해 주세요');
			document.form1.paymethod.value="";
			return;
		}
	}
	<?php  } ?>
<?php  } ?>

<?php  if ($_data->payment_type=="Y" || $_data->payment_type=="N") { ?>
	if(paymethod=="B" && document.form1.pay_data1.value.length==0) {
		if(typeof(document.form1.usereserve)!="undefined") {
			if(document.form1.usereserve.value<<?=$sumprice-$salemoney?>) {
				alert("은행을 선택하세요.");
				//orderpaypop();
				return;
			}
		} else {
			alert("은행을 선택하세요.");
			//orderpaypop();
			return;
		}
	}
<?php  } ?>

	prlistcnt="<?=$arr_prlist?>"+0;
	if(document.form1.msg_type.value=="1") {
		message_len = document.form1.order_prmsg.value.length;
		message_end = document.form1.order_prmsg.value.charCodeAt(message_len-1);
		if (message_len>0 && (message_end==39 || message_end==34 || message_end==92) ) {
			document.form1.order_prmsg.value += " ";
		}
	} else if(document.form1.msg_type.value=="2") {
		for(j=0;j<prlistcnt;j++) {
			message_len = document.form1["order_prmsg"+j].value.length;
			message_end = document.form1["order_prmsg"+j].value.charCodeAt(message_len-1);
			if (message_len>0 && (message_end==39 || message_end==34 || message_end==92) ) {
				document.form1["order_prmsg"+j].value += " ";
			}
		}
	}

	document.form1.receiver_addr.value = "우편번호 : " + document.form1.rpost1.value + "-" + document.form1.rpost2.value + "\n주소 : " + document.form1.raddr1.value + "  " + document.form1.raddr2.value;

<?php  if($_data->coupon_ok=="Y" && strlen($_ShopInfo->getMemid())>0) { ?>
	if (document.form1.bank_only.value=="Y") {
		if(paymethod!="B" && paymethod!="V" && paymethod!="O" && paymethod!="Q") {
			alert("선택하신 쿠폰은 현금결제만 가능합니다.\n현금결제로 선택해 주세요");
			document.form1.paymethod.value="";
			return;
		}
	}
<?php  } ?>
	document.form1.order_msg.value="";
	if(document.form1.process.value=="N") {
	<?php  if(ord($etcmessage[1])) {?>
		if(document.form1.nowdelivery.checked) {
			document.form1.order_msg.value+="<font color=red>희망배송일 : 가능한 빨리배송</font>";
		} else {
			document.form1.order_msg.value+="<font color=red>희망배송일 : "+document.form1.year.value+"년 "+document.form1.mon.value+"월 "+document.form1.day.value+"일";
			<?php  if(strlen($etcmessage[1])==6) { ?>
			document.form1.order_msg.value+=" "+document.form1.time.value;
			<?php  } ?>
			document.form1.order_msg.value+="</font>";
		}
	<?php  } ?>

	<?php  if($etcmessage[2]=="Y") { ?>
		if(document.form1.bank_sender.value.length>1 && (document.form1.paymethod.length==null && paymethod=="B")) {
			if(document.form1.order_msg.value.length>0) document.form1.order_msg.value+="\n";
			document.form1.order_msg.value+="입금자 : "+document.form1.bank_sender.value;
		}
	<?php  } ?>

		//지역별 추가배송료 확인
	<?php
/*
		echo "address = \" \"+document.form1.raddr1.value;\n";
		$array_deli = explode("|",$_data->deli_area);
		$cnt= floor(count($array_deli)/2);
		for($i=0;$i<$cnt;$i++){
			$subdeli=explode(",",$array_deli[$i*2]);
			$subcnt=count($subdeli);
			echo "if(";
			for($j=0;$j<$subcnt;$j++){
				if($j!=0) echo " || ";
				echo "address.indexOf(\"{$subdeli[$j]}\")>0";
			}
			echo "){ if(!confirm('";
			if($array_deli[$i*2+1]>0) echo "해당 지역은 배송료 ".number_format($array_deli[$i*2+1])."원이 추가됩니다.";
			else echo "해당 지역은 배송료 ".number_format(abs($array_deli[$i*2+1]))."원이 할인됩니다.";
			echo "')) return;}\n";
		}
*/
	?>


		if(document.form1.addorder_msg=="[object]") {
			if(document.form1.order_msg.value.length>0) document.form1.order_msg.value+="\n";
			document.form1.order_msg.value+=document.form1.addorder_msg.value;
		}
		document.form1.process.value="Y";
		document.form1.target = "PROCESS_IFRAME";

<?php if($_data->ssl_type=="Y" && ord($_data->ssl_domain) && ord($_data->ssl_port) && $_data->ssl_pagelist["ORDER"]=="Y") {?>
		document.form1.action='https://<?=$_data->ssl_domain?><?=($_data->ssl_port!="443"?":".$_data->ssl_port:"")?>/<?=RootPath.SecureDir?>order.php';
<?php }?>

		document.form1.submit();

		document.all.paybuttonlayer.style.display="none";
		document.all.payinglayer.style.display="block";


		if(paymethod!="B") ProcessWait("visible");

	} else {
		ordercancel();
	}
}

//-->
</SCRIPT>

<DIV id="PAYWAIT_LAYER" style='position:absolute; left:50px; top:120px; width:503; height: 255; z-index:1; display:none'><a href="JavaScript:PaymentOpen();"><img src="<?=$Dir?>images/paywait.gif" align=absmiddle border=0 name=paywait galleryimg=no></a></DIV>
<IFRAME id="PAYWAIT_IFRAME" name="PAYWAIT_IFRAME" style="left:50px; top:120px; width:503; height: 255; position:absolute; display:none"></IFRAME>
<!--IFRAME id=PROCESS_IFRAME name=PROCESS_IFRAME style="display:''" width=100% height=300></IFRAME-->
<!--IFRAME id=PROCESS_IFRAME name=PROCESS_IFRAME width="100%" height="500" <?if(!isdev()){?>style="display:none"<?}?>></IFRAME-->
<IFRAME id=PROCESS_IFRAME name=PROCESS_IFRAME width="100%" height="500" style="display:none"></IFRAME>
<?=$onload?>
<?php  include ($Dir."lib/bottom.php") ?>
</BODY>
</HTML>
