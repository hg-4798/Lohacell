<?php 
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/product.class.php");
$product_class = new PRODUCT();

if(strlen($_ShopInfo->getMemid())==0) {
	exit;
}

$sumprice=$_POST["sumprice"];
$sumprice_t=$_POST["sumprice"];
$used=$_POST["used"];

?>
<html>
<head>
<title>쿠폰 조회 및 적용</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<style>
td	{font-family:"굴림,돋움";color:#4B4B4B;font-size:12px;line-height:17px;}
BODY,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

A:link    {color:#635C5A;text-decoration:none;}
A:visited {color:#545454;text-decoration:none;}
A:active  {color:#5A595A;text-decoration:none;}
A:hover  {color:#545454;text-decoration:underline;}
.input{font-size:12px;BORDER-RIGHT: #DCDCDC 1px solid; BORDER-TOP: #C7C1C1 1px solid; BORDER-LEFT: #C7C1C1 1px solid; BORDER-BOTTOM: #DCDCDC 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff;padding-top:2pt; padding-bottom:1pt; height:19px}
.select{color:#444444;font-size:12px;}
.textarea {border:solid 1;border-color:#e3e3e3;font-family:돋음;font-size:9pt;color:333333;overflow:auto; background-color:transparent}
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
window.moveTo(10,10);
window.resizeTo(612,650);
var all_list=new Array();
function prvalue() {
	var argv = prvalue.arguments;   
	var argc = prvalue.arguments.length;
	
	this.classname		= "prvalue"
	this.debug			= false;
	this.bank_only		= new String((argc > 0) ? argv[0] : "N");
	this.sale_type		= new String((argc > 1) ? argv[1] : "");
	this.use_con_type2	= new String((argc > 2) ? argv[2] : "");
	this.sale_money		= new String((argc > 3) ? argv[3] : "");
	this.prname			= new String((argc > 4) ? argv[4] : "");
	this.prprice		= new String((argc > 5) ? argv[5] : "");
}

function CheckForm() {
	if(document.form1.coupon_code.selectedIndex<=0){
		alert("사용하실 쿠폰을 선택하세요.");
		document.form1.coupon_code.focus();
		return;
	}
	if(document.form1.bank_only.value=="Y" && !confirm('해당 쿠폰은 현금결제시에만 사용가능합니다.\n무통장입금을 선택하셔야만 쿠폰 사용이 가능합니다.')){
		document.form1.coupon_code.focus();
		return;
	}


	opener.document.form1.coupon_code.value=document.form1.coupon_code.options[document.form1.coupon_code.selectedIndex].text;
	opener.document.form1.bank_only.value=document.form1.bank_only.value;
	opener.document.form1.coupon_dc.value=0;
	s_delivery_type=document.form1.delivery_type.value;
	s_total_price=parseInt(document.getElementById("total_price").innerHTML.replace(/\,/g,""));
	s_coupon_dc=parseInt(document.form1.coupon_dc.value);
	s_delivery_price=parseInt(opener.document.getElementById("delivery_price").innerHTML.replace(/\,/g,""));
	s_usereserve=parseInt(opener.document.form1.usereserve.value);
	
	if(opener.document.form1.okreserve==true){
		s_okreserve=parseInt(opener.document.form1.okreserve.value);
	}
		
	if(s_delivery_type=='N'){
		 goods_total=s_total_price;
		if(s_delivery_price<s_usereserve) t_delivery=s_delivery_price;	
		else t_delivery=s_usereserve;	
	}else{
		 goods_total=s_total_price+s_delivery_price;	
		 t_delivery=0;
	}
	
	if(goods_total<s_coupon_dc){
		if(opener.document.form1.okreserve==true){
			opener.document.form1.okreserve.value=s_okreserve+s_usereserve-t_delivery;
		}

		opener.document.form1.usereserve.value=t_delivery;
		//opener.document.getElementById("dc_price").innerHTML=comma(parseInt(goods_total)+parseInt(t_delivery));
		opener.document.form1.coupon_dc.value=goods_total;
		opener.document.getElementById("price_sum").innerHTML=comma(parseInt(opener.document.form1.total_sum.value)-parseInt(goods_total)-parseInt(t_delivery));
		
	}else{
		if(goods_total<(s_coupon_dc+s_usereserve)){
			if(opener.document.form1.okreserve==true){
				opener.document.form1.okreserve.value=s_okreserve+(s_usereserve-(goods_total-s_coupon_dc));
			}
			

			opener.document.form1.usereserve.value=goods_total-s_coupon_dc;
			dc_price=parseInt(opener.document.form1.usereserve.value)+parseInt(document.form1.coupon_dc.value);
			//opener.document.getElementById("dc_price").innerHTML=comma(dc_price);
			opener.document.form1.coupon_dc.value=document.form1.coupon_dc.value;
			opener.document.getElementById("price_sum").innerHTML=comma(parseInt(opener.document.form1.total_sum.value)-parseInt(dc_price));
		}else{

			dc_price=parseInt(opener.document.form1.usereserve.value)+parseInt(document.form1.coupon_dc.value);
			//opener.document.getElementById("dc_price").innerHTML=comma(dc_price);
			opener.document.form1.coupon_dc.value=document.form1.coupon_dc.value;
			opener.document.getElementById("price_sum").innerHTML=comma(parseInt(opener.document.form1.total_sum.value)-parseInt(dc_price));
		}
		
	}
//	opener.document.form1.usereserve.readOnly=true;
	
	window.close();
}

function coupon_cancel() {
	dc_price=parseInt(opener.document.form1.usereserve.value);
	opener.document.form1.coupon_dc.value=0;
	opener.document.form1.coupon_reserve.value=0;
	//opener.document.getElementById("dc_price").innerHTML=comma(dc_price);
	opener.document.getElementById("price_sum").innerHTML=comma(parseInt(opener.document.form1.total_sum.value)-parseInt(dc_price));
	
	opener.document.form1.coupon_code.value="";
	opener.document.form1.bank_only.value="N";
	window.close();
}
//-->
</SCRIPT>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" width="100%">
<form name=form1 method=post>
<input type=hidden name=bank_only value="N">
<input type=hidden name=coupon_dc value="0">
<input type=hidden name=coupon_reserve value="0">
<input type=hidden name=delivery_type value="N">
<input type="hidden" name=rcall_type value="<?=$_data->rcall_type?>">
<tr>
	<td><IMG src="<?=$Dir?>images/common/coupon_open_title.gif" border="0"></td>
</tr>
<tr>
	<td style="padding:10px;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><img src="<?=$Dir?>images/common/coupon_open_text01.gif" border="0" vspace="2"></td>
	</tr>
	<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<col width="65"></col>
		<col width=></col>
		<col width="130"></col>
		<col width="85"></col>
		<col width="80"></col>
		<tr>
			<td height="2" colspan="5" bgcolor="#000000"></td>
		</tr>
		<tr bgcolor="#F8F8F8" height="30" align="center">
			<td><font color="#333333"><b>쿠폰번호</b></font></td>
			<td><font color="#333333"><b>쿠폰명</b></font></td>	
			<td><font color="#333333"><b>쿠폰 적용상품</b></font></td>
			<td><font color="#333333"><b>제한사항</b></font></td>
			<td><font color="#333333"><b>혜택</b></font></td>
		</tr>
		<tr>
			<td height="1" colspan="5" bgcolor="#DDDDDD"></td>
		</tr>
<?php
		$id=$_ShopInfo->getMemid();
		$sql = "SELECT a.coupon_code, a.coupon_name, a.sale_type, a.sale_money, a.sale_max_money, a.bank_only, a.productcode,a.amount_floor, a.delivery_type,";
		$sql.= "a.mini_price, a.use_con_type1, a.use_con_type2, a.use_point, a.vender, b.date_start, b.date_end ";
		$sql.= "FROM tblcouponinfo a, tblcouponissue b ";
		$sql.= "WHERE b.id='{$id}' AND a.coupon_code=b.coupon_code AND b.date_start<='".date("YmdH")."' ";
		$sql.= "AND (b.date_end>='".date("YmdH")."' OR b.date_end='') ";
		$sql.= "AND b.used='N' AND a.coupon_use_type != '2'";
		$result = pmysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=pmysql_fetch_object($result)) {
			$coupon_code[$cnt]		= $row->coupon_code;
			$use_con_type2[$cnt]	= $row->use_con_type2;
			$sale_type[$cnt]		= $row->sale_type;
			$use_con_type1[$cnt]	= $row->use_con_type1;
			$sale_money[$cnt]		= $row->sale_money;
			$mini_price[$cnt]		= $row->mini_price;
			$vender[$cnt]			= $row->vender;
			$bank_only[$cnt]		= $row->bank_only;
			$amount_floor[$cnt]		= $row->amount_floor;
			$delivery_type[$cnt]		= $row->delivery_type;
			$delivery_type[$cnt]		= $row->delivery_type;
			$sale_max_money[$cnt]		= $row->sale_max_money;
			
			
			$prleng=strlen($row->productcode);

			list($code_a,$code_b,$code_c,$code_d) = sscanf($row->productcode,'%3s%3s%3s%3s');

			$likecode=$code_a;
			if($code_b!="000") $likecode.=$code_b;
			if($code_c!="000") $likecode.=$code_c;
			if($code_d!="000") $likecode.=$code_d;

			if($prleng==18) $productcode[$cnt]=$row->productcode;
			else $productcode[$cnt]=$likecode;

			$cnt++;
			if($row->sale_type<=2) {
				$dan="%";
			} else {
				$dan="원";
			}
			if($row->sale_type%2==0) {
				$sale = "할인";
			} else {
				$sale = "적립";
			}
			
			if($row->productcode=="ALL") {
				if($row->vender==0) {
					$product="전체상품";
				} else {
					$product="해당 입점업체 전체상품";
				}
			} else {
				$product = getCodeLoc($row->productcode);				
				if($row->vender>0) $product.=" (일부상품 제외)";

				if($prleng==18) {
					$sql2 = "SELECT productname as product FROM tblproduct WHERE productcode='{$row->productcode}' ";
					$result2 = pmysql_query($sql2,get_db_conn());
					if($row2 = pmysql_fetch_object($result2)) {
						$product.= " > ".$row2->product;
					}
					pmysql_free_result($result2);
				}
				if($row->use_con_type2=="N") {
					if($row->vender==0) {
						$product="[{$product}] 제외";
					} else {
						$product="[{$product}] 제외한 일부상품";
					}
				}
			}			
			$t = sscanf($row->date_start,'%4s%2s%2s%2s');
			$s_time = strtotime("{$t[0]}-{$t[1]}-{$t[2]} {$t[3]}:00:00");
			$t = sscanf($row->date_end,'%4s%2s%2s%2s');
			$e_time = strtotime("{$t[0]}-{$t[1]}-{$t[2]} {$t[3]}:00:00");

			$date=date("Y.m.d H",$s_time)."시 ~ ".date("Y.m.d H",$e_time)."시";
			if($cnt>1) echo "<tr><td height=\"1\" colspan=\"5\" bgcolor=\"#DDDDDD\"></td></tr>\n";
			echo "<tr align=\"center\">\n";
			echo "	<td>{$row->coupon_code}</td>\n";
			echo "	<td>\n";
			echo "	<TABLE cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\">\n";
			echo "	<TR>\n";
			echo "		<TD height=\"16\"><font color=\"#333333\">{$row->coupon_name}</font></TD>\n";
			echo "	</TR>\n";
			echo "	<TR>\n";
			echo "		<TD height=\"16\" nowrap><IMG src=\"{$Dir}images/common/coupon_open_btn1.gif\" align=\"absMiddle\" border=\"0\" style=\"MARGIN-RIGHT:2px\"><font color=\"#000000\" style=\"FONT-SIZE:11px;LETTER-SPACING:-0.5pt\"><b>{$date}</b></TD>\n";
			echo "	</TR>\n";
			echo "	</TABLE>\n";
			echo "	</td>\n";
			echo "	<td><font color=\"#333333\">{$product}</font></td>\n";
			echo "	<td><font color=\"#333333\">".($row->mini_price=="0"?"제한 없음":number_format($row->mini_price)."원 이상")."</font></td>\n";
			echo "	<td><font color=\"".($sale=="할인"?"#FF0000":"#0000FF")."\">".number_format($row->sale_money).$dan.$sale."</font></td>\n";
			echo "</tr>\n";
		}
		pmysql_free_result($result);
		if($cnt==0) {
			echo "<tr height=\"30\"><td colspan=\"5\" align=\"center\">보유한 쿠폰내역이 없습니다.</td></tr>\n";
		}
		echo "<tr><td height=\"1\" colspan=\"5\" bgcolor=\"#DDDDDD\"></td></tr>\n";
?>
		</table>
		</td>
	</tr>
	<?php if($used!="N"){?>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td><img src="<?=$Dir?>images/common/coupon_open_text02.gif" border="0" vspace="2"></td>
	</tr>
	<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<col width=></col>
		<col width="80"></col>
		<col width="100"></col>
		<col width="70"></col>
		<col width="70"></col>
		<tr>
			<td height="2" colspan="5" bgcolor="#000000"></td>
		</tr>
		<tr bgcolor="#F8F8F8" height="30" align="center">
			<td><font color="#333333"><b>상품명</b></font></td>
			<td><font color="#333333"><b>상품금액</b></font></td>	
			<td><font color="#333333"><b>쿠폰선택</b></font></td>
			<td><font color="#333333"><b>할인액(%)</b></font></td>
			<td><font color="#333333"><b>적립액(%)</b></font></td>
		</tr>
		<tr>
			<td height="1" colspan="5" bgcolor="#DDDDDD"></td>
		</tr>
<?php 
		$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,b.productcode,b.productname,b.sellprice, ";
		$sql.= "b.option_price,b.option_quantity,b.option1,b.option2,b.vender,a.assemble_list,a.assemble_idx, ";
		$sql.= "b.sellprice*a.quantity as realprice FROM tblbasket a, tblproduct b ";
		$sql.= "WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
		$sql.= "AND a.productcode=b.productcode ";
		$result=pmysql_query($sql,get_db_conn());
		$sumprice=array();
		$basketcnt=array();
		$prcode=array();
		$prname=array();
		$productall=array();
		while($row = pmysql_fetch_object($result)) {
			
			if(ord($prcode[0])) {
				if(substr($row->productcode,0,12)==substr($prcode[0],0,12)) $prcode[0]=substr($prcode[0],0,12);
				elseif(substr($row->productcode,0,9)==substr($prcode[0],0,9)) $prcode[0]=substr($prcode[0],0,9);
				elseif(substr($row->productcode,0,6)==substr($prcode[0],0,6)) $prcode[0]=substr($prcode[0],0,6);
				elseif(substr($row->productcode,0,3)==substr($prcode[0],0,3)) $prcode[0]=substr($prcode[0],0,3);
				else $prcode[0]="";
			}
			if((int)$basketcnt[0]==0) {
				$prcode[0]=$row->productcode;
				$prname[0]=str_replace('"','',strip_tags($row->productname));
			} else {
				$prname[0].="<br>".str_replace('"','',strip_tags($row->productname));
			}
			$productall[0][$basketcnt[0]]["prcode"]=$row->productcode;
			$productall[0][$basketcnt[0]]["prname"]=str_replace('"','',strip_tags($row->productname));
			if($row->vender>0) {
				if(ord($prcode[$row->vender])) {
					if(substr($row->productcode,0,12)==substr($prcode[$row->vender],0,12)) $prcode[$row->vender]=substr($prcode[$row->vender],0,12);
					elseif(substr($row->productcode,0,9)==substr($prcode[$row->vender],0,9)) $prcode[$row->vender]=substr($prcode[$row->vender],0,9);
					elseif(substr($row->productcode,0,6)==substr($prcode[$row->vender],0,6)) $prcode[$row->vender]=substr($prcode[$row->vender],0,6);
					elseif(substr($row->productcode,0,3)==substr($prcode[$row->vender],0,3)) $prcode[$row->vender]=substr($prcode[$row->vender],0,3);
					else $prcode[$row->vender]="";
				}
				if((int)$basketcnt[$row->vender]==0) {
					$prcode[$row->vender]=$row->productcode;
					$prname[$row->vender]=str_replace('"','',strip_tags($row->productname));
				} else {
					$prname[$row->vender].="<br>".str_replace('"','',strip_tags($row->productname));
				}
				$productall[$row->vender][$basketcnt[$row->vender]]["prcode"]=$row->productcode;
				$productall[$row->vender][$basketcnt[$row->vender]]["prname"]=str_replace('"','',strip_tags($row->productname));
			}

			if(preg_match("/^\[OPTG\d{4}\]$/",$row->option1)){
				$optioncode = substr($row->option1,5,4);
				$row->option1="";
				$row->option_price="";
				if(!empty($row->optidxs)) {
					$tempoptcode = rtrim($row->optidxs,',');
					$exoptcode = explode(",",$tempoptcode);

					$sqlopt = "SELECT * FROM tblproductoption WHERE option_code='{$optioncode}' ";
					$resultopt = pmysql_query($sqlopt,get_db_conn());
					if($rowopt = pmysql_fetch_object($resultopt)){
						$optionadd = array (&$rowopt->option_value01,&$rowopt->option_value02,&$rowopt->option_value03,&$rowopt->option_value04,&$rowopt->option_value05,&$rowopt->option_value06,&$rowopt->option_value07,&$rowopt->option_value08,&$rowopt->option_value09,&$rowopt->option_value10);
						$opti=0;
						$option_choice = $rowopt->option_choice;
						$exoption_choice = explode("",$option_choice);
						while(ord($optionadd[$opti])){
							if($exoptcode[$opti]>0){
								$opval = explode("",str_replace('"','',$optionadd[$opti]));
								$exop = explode(",",str_replace('"','',$opval[$exoptcode[$opti]]));
								$row->realprice+=($row->quantity*$exop[1]);
							}
							$opti++;
						}
					}
				}
			}

			if (ord($row->option_price)==0) {
				
				$price = $row->realprice;
			
			} else if (ord($row->opt1_idx)) {
				
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$price = $pricetok[$row->opt1_idx-1]*$row->quantity;
			}
			
			if($row->assemble_idx>0 && strlen(str_replace("","",$row->assemble_list))>0) {
				$assemble_list_proexp = explode("",$row->assemble_list);
				$alprosql = "SELECT productcode,productname,sellprice FROM tblproduct ";
				$alprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_proexp)."') ";
				$alprosql.= "AND display = 'Y' ";
				$alprosql.= "ORDER BY FIELD(productcode,'".implode("','",$assemble_list_proexp)."') ";
				$alproresult=pmysql_query($alprosql,get_db_conn());
				
			//	$assemble_str ="		<td width=\"50\" valign=\"top\" style=\"padding-left:12px;\" nowrap><font color=\"#FF7100\"			style=\"line-height:10px;\">┃<br>┗━<b>▶</b></font></td>\n";
				$assemble_str.="		<td width=\"100%\">\n";
				$assemble_str.="		<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-left:1px #DDDDDD solid;border-top:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\">\n";
				
				$assemble_sellerprice=0;
				while($alprorow=@pmysql_fetch_object($alproresult)) {
					$assemble_str.="		<tr>\n";
					$assemble_str.="			<td bgcolor=\"#FFFFFF\" style=\"border-bottom:1px #DDDDDD solid;\">\n";
					$assemble_str.="			<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
					$assemble_str.="			<col width=\"\"></col>\n";
					$assemble_str.="			<col width=\"80\"></col>\n";
					$assemble_str.="			<col width=\"120\"></col>\n";
					$assemble_str.="			<tr>\n";
					$assemble_str.="				<td style=\"padding:4px;word-break:break-all;\"><font color=\"#000000\">".$alprorow->productname."</font>&nbsp;</td>\n";
					$assemble_str.="				<td align=\"right\" style=\"padding:4px;border-left:1px #DDDDDD solid;border-right:1px #DDDDDD solid;\"><font color=\"#000000\">".number_format((int)$alprorow->sellprice)."원</font></td>\n";
					$assemble_str.="				<td align=\"center\" style=\"padding:4px;\">본 상품 1개당 수량1개</td>\n";
					$assemble_str.="			</tr>\n";
					$assemble_str.="			</table>\n";
					$assemble_str.="			</td>\n";
					$assemble_str.="		</tr>\n";
					$assemble_sellerprice+=$alprorow->sellprice;
				}
				@pmysql_free_result($alproresult);
				$assemble_str.="		</table>\n";
				$assemble_str.="		</td>\n";

				//######### 코디/조립에 따른 가격 변동 체크 ###############
				$price = $assemble_sellerprice*$row->quantity;

			}

			$dc_data = $product_class->getProductDcRate($row->productcode);
			$salemoney = getProductDcPrice($price,$dc_data[price]);
			

			//$productall[0][$basketcnt[0]]["price"]=$price-$salemoney;
			//$sumprice[0] += $price-$salemoney;
			
			$productall[0][$basketcnt[0]]["price"]=$price;
			$sumprice[0] += $price;


			
	
			
			if($row->vender>0) {
				$productall[$row->vender][$basketcnt[$row->vender]]["price"]=$price;
				$sumprice[$row->vender] += $price;
			}

			$basketcnt[0]++;
			if($row->vender>0) $basketcnt[$row->vender]++;

			if(strlen($row->productcode)==18) {
				$prname2[0][$row->productcode]=str_replace('"','',strip_tags($row->productname));

				$prprice[0][$row->productcode]=$price;
				$prprice[0][substr($row->productcode,0,3)]+=$price;
				if((int)$prbasketcnt[0][substr($row->productcode,0,3)]==0) {
					$prname2[0][substr($row->productcode,0,3)]=str_replace('"','',strip_tags($row->productname));
				} else {
					$prname2[0][substr($row->productcode,0,3)].="<br>".str_replace('"','',strip_tags($row->productname));
				}
				$prbasketcnt[0][substr($row->productcode,0,3)]++;

				$prprice[0][substr($row->productcode,0,6)]+=$price;
				if((int)$prbasketcnt[0][substr($row->productcode,0,6)]==0) {
					$prname2[0][substr($row->productcode,0,6)]=str_replace('"','',strip_tags($row->productname));
				} else {
					$prname2[0][substr($row->productcode,0,6)].="<br>".str_replace('"','',strip_tags($row->productname));
				}
				$prbasketcnt[0][substr($row->productcode,0,6)]++;

				$prprice[0][substr($row->productcode,0,9)]+=$price;
				if((int)$prbasketcnt[0][substr($row->productcode,0,9)]==0) {
					$prname2[0][substr($row->productcode,0,9)]=str_replace('"','',strip_tags($row->productname));
				} else {
					$prname2[0][substr($row->productcode,0,9)].="<br>".str_replace('"','',strip_tags($row->productname));
				}
				$prbasketcnt[0][substr($row->productcode,0,9)]++;

				$prprice[0][substr($row->productcode,0,12)]+=$price;
				if((int)$prbasketcnt[0][substr($row->productcode,0,12)]==0) {
					$prname2[0][substr($row->productcode,0,12)]=str_replace('"','',strip_tags($row->productname));
				} else {
					$prname2[0][substr($row->productcode,0,12)].="<br>".str_replace('"','',strip_tags($row->productname));
				}
				$prbasketcnt[0][substr($row->productcode,0,12)]++;

				if($row->vender>0) {
					$prname2[$row->vender][$row->productcode]=str_replace('"','',strip_tags($row->productname));

					$prprice[$row->vender][$row->productcode]=$price;
					$prprice[$row->vender][substr($row->productcode,0,3)]+=$price;
					if((int)$prbasketcnt[$row->vender][substr($row->productcode,0,3)]==0) {
						$prname2[$row->vender][substr($row->productcode,0,3)]=str_replace('"','',strip_tags($row->productname));
					} else {
						$prname2[$row->vender][substr($row->productcode,0,3)].="<br>".str_replace('"','',strip_tags($row->productname));
					}
					$prbasketcnt[$row->vender][substr($row->productcode,0,3)]++;

					$prprice[$row->vender][substr($row->productcode,0,6)]+=$price;
					if((int)$prbasketcnt[$row->vender][substr($row->productcode,0,6)]==0) {
						$prname2[$row->vender][substr($row->productcode,0,6)]=str_replace('"','',strip_tags($row->productname));
					} else {
						$prname2[$row->vender][substr($row->productcode,0,6)].="<br>".str_replace('"','',strip_tags($row->productname));
					}
					$prbasketcnt[$row->vender][substr($row->productcode,0,6)]++;

					$prprice[$row->vender][substr($row->productcode,0,9)]+=$price;
					if((int)$prbasketcnt[$row->vender][substr($row->productcode,0,9)]==0) {
						$prname2[$row->vender][substr($row->productcode,0,9)]=str_replace('"','',strip_tags($row->productname));
					} else {
						$prname2[$row->vender][substr($row->productcode,0,9)].="<br>".str_replace('"','',strip_tags($row->productname));
					}
					$prbasketcnt[$row->vender][substr($row->productcode,0,9)]++;

					$prprice[$row->vender][substr($row->productcode,0,12)]+=$price;
					if((int)$prbasketcnt[$row->vender][substr($row->productcode,0,12)]==0) {
						$prname2[$row->vender][substr($row->productcode,0,12)]=str_replace('"','',strip_tags($row->productname));
					} else {
						$prname2[$row->vender][substr($row->productcode,0,12)].="<br>".str_replace('"','',strip_tags($row->productname));
					}
					$prbasketcnt[$row->vender][substr($row->productcode,0,12)]++;
				}
			}
			$prname2[0][$prcode[0]]=$prname[0];
			$prprice[0][$prcode[0]]=$sumprice[0];

			$prname2[$row->vender][$prcode[$row->vender]]=$prname[$row->vender];
			$prprice[$row->vender][$prcode[$row->vender]]=$sumprice[$row->vender];

		}
		pmysql_free_result($result);
?>
		<tr height="26" align="center">
			<td id=idx_prname style="color:#333333"><?=$prname[0]?></td>
			<td id=idx_prprice style="color:#333333"><?=number_format($sumprice[0])."원";?></td>
			<td><select name=coupon_code onchange="change_group(this.value)" style="font-size:11px;background-color:#404040;color:#ffffff;letter-spacing:-0.5pt;">
			<option value="" style="color:#ffffff;">쿠폰선택</option>
<?php 
			$prscript="";
			//if($prcode=="") $prcode="ALL";
			for($i=0;$i<=$cnt;$i++) {
				if($prcode[$vender[$i]]=="") $prcode[$vender[$i]]="ALL";
				$num = strlen($productcode[$i]);
				$tempprcode = substr($prcode[$vender[$i]],0,$num);
			
				if(
				(    $productcode[$i]=="ALL" 
				|| ($use_con_type2[$i]=="Y" && $tempprcode==$productcode[$i])
				|| ($use_con_type1[$i]=="Y" && $use_con_type2[$i]=="Y" && $productcode[$i]!="ALL" && ord($prname2[$vender[$i]][$productcode[$i]]))
				|| ($use_con_type2[$i]=="N" && $use_con_type1[$i]=="N" && ord($prname2[$vender[$i]][$productcode[$i]])==0)
				|| ($use_con_type1[$i]=="Y" && $use_con_type2[$i]=="N" && $productcode[$i]!="ALL" && $sumprice[$vender[$i]]-$prprice[$vender[$i]][$productcode[$i]]>0)
				) 
				
				&& ($mini_price[$i]==0 || $mini_price[$i]<=$sumprice[$vender[$i]]) && isset($prprice[$vender[$i]])) 
				echo "<option value=\"{$i}\" style=\"color:#FFFFFF;\">{$coupon_code[$i]}</option>\n";

				$prscript.="var prval=new prvalue();\n";
				$prscript.="prval.bank_only=\"{$bank_only[$i]}\";\n";
				$prscript.="prval.sale_type=\"{$sale_type[$i]}\";\n";
				$prscript.="prval.use_con_type2=\"{$use_con_type2[$i]}\";\n";
				$prscript.="prval.sale_money=\"{$sale_money[$i]}\";\n";
				$prscript.="prval.amount_floor=\"{$amount_floor[$i]}\";\n";
				$prscript.="prval.delivery_type=\"{$delivery_type[$i]}\";\n";
				$prscript.="prval.maxprice=\"{$sale_max_money[$i]}\";\n";
				
				if($use_con_type2[$i]=="N") {
					$tmp_prname="";
					$tmp_sumprice=0;
					$tmp_prprice=0;
					$kk=0;
					$temparr=$productall[$vender[$i]];
					if(is_array($temparr)) {
						while(list($key,$val)=each($temparr)) {
							if(substr($val["prcode"],0,$num)!=$productcode[$i]) {
								if($kk>0) $tmp_prname.="<br> ";
								$tmp_prname.=$val["prname"];
								$tmp_prprice+=$val["price"];
								$kk++;
							}
							$tmp_sumprice+=$val["price"];
						}
					}
				} else {
					$tmp_prname="";
					$tmp_sumprice=0;
					$tmp_prprice=0;
					$kk=0;
					$temparr=$productall[$vender[$i]];
					if(is_array($temparr)) {
						while(list($key,$val)=each($temparr)) {
							if((substr($val["prcode"],0,$num)==$productcode[$i]) || $productcode[$i]=="ALL") {
								if($kk>0) $tmp_prname.="<br> ";
								$tmp_prname.=$val["prname"];
								$tmp_prprice+=$val["price"];
								$kk++;
							}
							$tmp_sumprice+=$val["price"];
						}
					}
				}
				$prscript.="prval.prname=\"{$tmp_prname}\";\n";
				$prscript.="prval.prprice=\"".number_format($tmp_prprice)."\";\n";
				$prscript.="all_list[{$i}]=prval;\n";
				$prscript.="prval=null;\n";
			}
?>
			</select></td>
			<?php  echo "<script>\n{$prscript}</script>\n"; ?>
			<td id=idx_sale_money1 style="color:red">─</td>
			<td id=idx_sale_money2 style="color:red">─</td>
		</tr>
		<input type=hidden name=prname value="<?=$prname[0]?>">
		<input type=hidden name=prprice value="<?=number_format($sumprice[0])."원";?>">
		<input type=hidden name=sale_money1 value="─">
		<input type=hidden name=sale_money2 value="─">
		<tr>
			<td height="1" colspan="5" bgcolor="#DDDDDD"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td>
			<div id="div_price">
			<table WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
				<tr>
					<td height="2" colspan="5" bgcolor="#000000"></td>
				</tr>
				<tr bgcolor="#F8F8F8" height="30" align="right">
					<td><font color="#333333"><b>총 구매 가격 <span id="total_price"><?=number_format($sumprice_t);?></span>원</b></font></td>
				</tr>
			</table>
			</div>
		</td>
	</tr>
	
	<tr>
		<td height="35" align="center"><a href="javascript:CheckForm();"><img src="<?=$Dir?>images/common/coupon_open_btn01.gif" border="0"></a><a href="javascript:coupon_cancel();"><img src="<?=$Dir?>images/common/coupon_open_btn02.gif" border="0" hspace="5"></a></td>
	</tr>
	<?php } else {?>
	<tr>
		<td height="35" align="center"><a href="javascript:window.close();"><img src="<?=$Dir?>images/common/coupon_open_btn01.gif" border="0"></a></td>
	</tr>
	<?php }?>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td style="padding-left:2px;padding-right:2px;">
		<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/coupon_open_table_01.gif" border="0"></TD>
		</TR>
		<TR>
			<TD background="<?=$Dir?>images/common/coupon_open_table_02.gif" style="padding-top:10px;padding-bottom:10px;padding-right:15px;padding-left:15px;">
			<TABLE cellSpacing="0" cellPadding="0" width="100%">
			<TR>
				<TD><IMG src="<?=$Dir?>images/common/coupon_open_text4.gif" border="0" vspace="6"></TD>
			</TR>
			<TR>
				<TD style="LINE-HEIGHT:16px;LETTER-SPACING:-0.5pt;padding-left:18px"><B>1 단계</B> - 쿠폰 선택에서  <font color="#FF6600"><b>보유하신 &quot;쿠폰번호&quot;를 선택</b></font>하시면 할인금액(또는 적립금액)이 나타납니다.</TD>
			</TR>
			<TR>
				<TD style="LINE-HEIGHT:16px;LETTER-SPACING:-0.5pt;padding-left:64px">(정률할인(적립)의 경우, 할인율(적립율)이 나타납니다.)</TD>
			</TR>
			<TR>
				<TD style="LINE-HEIGHT:16px;LETTER-SPACING:-0.5pt;padding-left:18px"><B>2 단계</B> - &quot;확인&quot; 버튼을 클릭하시면, 쿠폰결제 적용이 완료됩니다.</TD>
			</TR>
			<TR>
				<TD><HR color="#e5e5e5" noShade SIZE="1"></TD>
			</TR>
			<TR>
				<TD><IMG src="<?=$Dir?>images/common/coupon_open_text5.gif" border="0" vspace="6"></TD>
			</TR>
			<TR>
				<TD style="FONT-SIZE: 12px;LINE-HEIGHT:16px;LETTER-SPACING:-0.5pt;padding-left:18px"">① 각 쿠폰마다 <font color="#FF6600"><b>사용가능 금액</b></font>이 정해져 있습니다.<BR>② 쿠폰은 한 주문에 한해서 사용이 가능합니다.<BR>③ 각 쿠폰마다 사용기한이 정해져 있습니다.<BR>④ 주문 후 반품/환불/취소의 경우 한번 <font color="#FF6600"><b><u>사용하신 할인 쿠폰은 다시 사용하실 수 없습니다.</u></b></font><BR>⑤ 쿠폰 적용품목이 한정된 쿠폰은 <font color="#FF6600">해당 품목에서만 사용가능</font> 합니다.<BR>⑥ 할인/적립(%) 쿠폰은 적립금할인 등을 제외한 실제 결제금액에 적용됩니다.<BR>⑦ 해당 상품에 대한 쿠폰은 <font color="#FF6600">해당 상품만 구매시 적용이 가능</font>합니다.</TD>
			</TR>
			</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/coupon_open_table_03.gif" border="0"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	</table>
	</td>
</tr>
</form>
</table>
<SCRIPT LANGUAGE="JavaScript">
<!--
function change_group(idx){
	
	if(document.form1.rcall_type.value=="N" && opener.document.form1.usereserve.value!=0){
		alert("적립금과 쿠폰은 동시사용이 불가능 합니다.");
		dc_price=parseInt(opener.document.form1.usereserve.value);
		opener.document.form1.coupon_dc.value=0;
		//opener.document.getElementById("dc_price").innerHTML=comma(dc_price);
		opener.document.getElementById("price_sum").innerHTML=comma(parseInt(opener.document.form1.total_sum.value)-parseInt(dc_price));
		opener.document.form1.coupon_code.value="";
		opener.document.form1.bank_only.value="N";
		window.close();
	}
	
	if(idx.length>0) {
		idx = parseInt(idx);
		sale_money="";
		for(var i=0; i<all_list[idx].sale_money.length; i++) {
			var tmp = all_list[idx].sale_money.length-(i+1)
			if(i%3==0 && i!=0) sale_money = ',' + sale_money
			sale_money = all_list[idx].sale_money.charAt(tmp) + sale_money
		}
		if(all_list[idx].sale_type%2==0){
			money1 = document.form1.sale_money1;
			money2 = document.form1.sale_money2;
		} else{
			money1 = document.form1.sale_money2;
			money2 = document.form1.sale_money1;
		}
		if(all_list[idx].sale_type<=2) {
			money1.value=sale_money+"%";
		} else {
			money1.value=sale_money+"원";
		}
		money2.value="─";
		if(all_list[idx].sale_type%2==0){
			document.all["idx_sale_money1"].innerHTML=money1.value;
			document.all["idx_sale_money2"].innerHTML=money2.value;
		} else{
			document.all["idx_sale_money1"].innerHTML=money2.value;
			document.all["idx_sale_money2"].innerHTML=money1.value;
		}

		document.all["idx_prname"].innerHTML=all_list[idx].prname;
		//document.all["idx_prprice"].innerHTML=all_list[idx].prprice+"원";
		document.form1.bank_only.value=all_list[idx].bank_only;
		document.form1.delivery_type.value=all_list[idx].delivery_type;
		
		s_price=document.getElementById("total_price").innerHTML;
		//if(all_list[idx].sale_type<=2) coupon_money=comma(parseInt(all_list[idx].prprice.replace(/\,/g,""))*(parseInt(sale_money.replace(/\,/g,""))*0.01));
			
		if(all_list[idx].sale_type<=2){
			coupon_money=parseInt(all_list[idx].prprice.replace(/\,/g,""))*(parseInt(sale_money.replace(/\,/g,""))*0.01);
			if(all_list[idx].maxprice > 0 && all_list[idx].maxprice < coupon_money){
				coupon_money = all_list[idx].maxprice;
			}
			coupon_money=comma(Math.floor(coupon_money/Math.pow(10,all_list[idx].amount_floor))*Math.pow(10,all_list[idx].amount_floor));
		}else{
			 coupon_money=sale_money;
		}

		if(all_list[idx].sale_type%2==0) document.form1.coupon_dc.value=coupon_money.replace(/\,/g,"");
		else  document.form1.coupon_reserve.value=coupon_money.replace(/\,/g,"");
		
		
		product_price=parseInt(s_price.replace(/\,/g,""))-parseInt(all_list[idx].prprice.replace(/\,/g,""));
		total_settle=parseInt(all_list[idx].prprice.replace(/\,/g,""))-parseInt(coupon_money.replace(/\,/g,""))+product_price;
		if(total_settle<=0) total_settle=0;
		else total_settle=comma(total_settle);
		product_price=comma(product_price);

		if(all_list[idx].sale_type%2==0){
			table_set="<table WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">";
			table_set+="<tr><td height=\"2\" colspan=\"5\" bgcolor=\"#000000\"></td></tr>";
			table_set+="<tr><td  bgcolor=\"#F8F8F8\" height=\"30\" align=\"right\"><font color=\"#333333\"><b>총 구매 가격 <span id=\"total_price\">"+s_price+"</span>원</b></font></td></tr>";
			
			table_set+="<tr><td bgcolor=\"#F8F8F8\" height=\"30\" align=\"right\"><span style=\"color:red; font:bold;\">쿠폰 적용 금액 "+total_settle+"원</span></td></tr>";
			
			table_set+="</table>";

			opener.document.form1.coupon_reserve.value=0;

			document.getElementById("div_price").innerHTML=table_set;

		}else{
			table_set="<table WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">";
			table_set+="<tr><td height=\"2\" colspan=\"5\" bgcolor=\"#000000\"></td></tr>";
			table_set+="<tr><td  bgcolor=\"#F8F8F8\" height=\"30\" align=\"right\"><font color=\"#333333\"><b>총 구매 가격 <span id=\"total_price\">"+s_price+"</span>원</b></font></td></tr>";
			table_set+="<tr><td bgcolor=\"#F8F8F8\" height=\"30\" align=\"right\"><span style=\"color:red; font:bold;\">쿠폰 적립 금액 "+coupon_money+"원</span></td></tr>";
			table_set+="</table>";

			opener.document.form1.coupon_reserve.value=document.form1.coupon_reserve.value;	

			document.getElementById("div_price").innerHTML=table_set;
		}
		
		
		
	} else {
		
		document.form1.sale_money1.value="─";
		document.form1.sale_money2.value="─";
		document.form1.bank_only.value="N";
		document.form1.delivery_type.value="N";

		document.all["idx_sale_money1"].innerHTML=document.form1.sale_money1.value;
		document.all["idx_sale_money2"].innerHTML=document.form1.sale_money2.value;
		document.all["idx_prname"].innerHTML=document.form1.prname.value;
		//document.all["idx_prprice"].innerHTML=document.form1.prprice.value;
		
		s_price=document.getElementById("total_price").innerHTML;
		table_set="<table WIDTH=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\">";
		table_set+="<tr><td height=\"2\" colspan=\"5\" bgcolor=\"#000000\"></td></tr>";
		table_set+="<tr><td  bgcolor=\"#F8F8F8\" height=\"30\" align=\"right\"><font color=\"#333333\"><b>총 구매 가격 <span id=\"total_price\">"+s_price+"</span>원</b></font></td></tr>";
		table_set+="</table>";
		document.getElementById("div_price").innerHTML=table_set;
				
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

//-->
</SCRIPT>
</body>
</html>
