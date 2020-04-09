<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/product.class.php");
$product = new PRODUCT();


#### PG ����Ÿ ���� ####
$_ShopInfo->getPgdata();
########################

$ip = $_SERVER['REMOTE_ADDR'];

$receipt_yn = $paymethod!="B"?"Y":$receipt_yn;

$sslchecktype="";
if($_POST["ssltype"]=="ssl" && strlen($_POST["sessid"])==64) {
	$sslchecktype="ssl";
}
if($sslchecktype=="ssl") {
	$secure_data=getSecureKeyData($_POST["sessid"]);
	if(!is_array($secure_data)) {
		alert_go('�������� ������ �߸��Ǿ����ϴ�.',-2);
	}
	foreach($secure_data as $key=>$val) {
		${$key}=$val;
	}
} else {
	foreach($_POST as $key=>$val) {
		${$key}=$val;
	}
}


$sender_name=str_replace(" ","",$sender_name);
$sender_email=str_replace("'","",$sender_email);
$receiver_name=str_replace(" ","",$receiver_name);
$order_msg=str_replace("'","",$order_msg);
$sender_tel=str_replace("'","",$sender_tel);
$receiver_tel1=str_replace("'","",$receiver_tel1);
$receiver_tel2=str_replace("'","",$receiver_tel2);
$receiver_addr=str_replace("'","",$receiver_addr);
$rpost=$rpost1.$rpost2;

$gift_sel=$_POST['gift_sel'];
$deli_type = $_POST["deli_type"];

$loc=substr($raddr1,0,4);

if (ord($paymethod)==0) {
	echo "<html></head><body onload=\"alert('��������� ���õ��� �ʾҽ��ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

if (ord($usereserve)>0 && !IsNumeric($usereserve)) {
	echo "<html></head><body onload=\"alert('�������� ���ڸ� �Է��Ͻñ� �ٶ��ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

if(ord($_data->escrow_id)==0 && $paymethod=="Q") {
	echo "<html></head><body onload=\"alert('����ũ�� ������ �������� �ʽ��ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

$escrow_info = GetEscrowType($_data->escrow_info);
if(ord($_data->escrow_id)>0 && ($escrow_info["escrowcash"]=="Y" || $escrow_info["escrowcash"]=="A")) {
	$escrowok="Y";
} else {
	$escrowok="N";
	$escrow_info["escrowcash"]="";
	if($escrow_info["onlycash"]!="Y" && (ord($escrow_info["onlycard"])==0 && ord($escrow_info["nopayment"])==0)) $escrow_info["onlycash"]="Y";
}

$pg_type="";
switch ($paymethod) {
	case "B":
		break;
	case "V":
		$pgid_info=GetEscrowType($_data->trans_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "O":
		$pgid_info=GetEscrowType($_data->virtual_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "Q":
		$pgid_info=GetEscrowType($_data->escrow_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "C":
		$pgid_info=GetEscrowType($_data->card_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "P":
		$pgid_info=GetEscrowType($_data->card_id);
		$pg_type=$pgid_info["PG"];
		break;
	case "M":
		$pgid_info=GetEscrowType($_data->mobile_id);
		$pg_type=$pgid_info["PG"];
		break;
}
$pg_type=trim($pg_type);

$pmethod=$paymethod.$pg_type;

if ($paymethod!="B" && ord($pg_type)==0) {
	echo "<html></head><body onload=\"alert('�����Ͻ� ��������� �̿��Ͻ� �� �����ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

$card_splittype=$_data->card_splittype;
$card_splitmonth=$_data->card_splitmonth;
$card_splitprice=$_data->card_splitprice;

$coupon_ok=$_data->coupon_ok;
$card_miniprice=$_data->card_miniprice;
$reserve_limit=$_data->reserve_limit;
$reserve_maxprice=$_data->reserve_maxprice;
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

if($_data->reserve_useadd==-1) $reserve_useadd="N";
else if($_data->reserve_useadd==-2) $reserve_useadd="U";
else $reserve_useadd=$_data->reserve_useadd;

$etcmessage=explode("=",$_data->order_msg);

#�������� ���ݰ����ÿ��� ��밡���ϰ� ���ݰ����� ���þ�������
if($bankreserve=="N" && !strstr("BVOQ",$paymethod)) {
	$usereserve=0;
}

$user_reserve=0;
$reserve_type="N";
if(ord($_ShopInfo->getMemid())>0) {
	$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
	$result=pmysql_query($sql,get_db_conn());
	if($row=pmysql_fetch_object($result)) {
		if($paymethod != 'B'){
			$ordercode=$_POST['ordr_idxx'];
		}else{
			$ordercode=unique_id();
		}
		$user_reserve = $row->reserve;
		$group_code=$row->group_code;
		pmysql_free_result($result);

		if(ord($group_code)>0 && $group_code!=NULL) {
			$sql = "SELECT * FROM tblmembergroup WHERE group_code='{$group_code}' ";
			$result=pmysql_query($sql,get_db_conn());
			if($row=pmysql_fetch_object($result)) {
				$group_code=$row->group_code;
				$group_level=$row->group_level;
				$group_deli_free=$row->group_deli_free;
				$group_name=$row->group_name;
				$group_type=substr($row->group_code,0,2);
				$group_usemoney=$row->group_usemoney;
				$group_addmoney=$row->group_addmoney;
				$group_payment=$row->group_payment;
			}
			pmysql_free_result($result);
		}
	} else {
		$_ShopInfo->SetMemNULL();
		//guest
		if($paymethod != 'B'){
			$ordercode=$_POST['ordr_idxx']."X";
		}else{
			$ordercode=unique_id()."X";
		}
		$id="X".date("iHs").$sender_name;
	}
} else {
	//guest
	if($paymethod != 'B'){
		$ordercode=$_POST['ordr_idxx']."X";
	}else{
		$ordercode=unique_id()."X";
	}
	$id="X".date("iHs").$sender_name;
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
	if(ord($basketrow2->package_title)>0 && ord($basketrow2->package_idx)>0 && $basketrow2->package_idx>0) {
		$package_title_exp = explode("",$basketrow2->package_title);
		$package_price_exp = explode("",$basketrow2->package_price);
		$package_list_exp = explode("", $basketrow2->package_list);

		$title_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx] = $package_title_exp[$basketrow2->package_idx];

		if(ord($package_list_exp[$basketrow2->package_idx])>1) {
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

			if(count($productcode_package_listtmp)>0) {  //��ٱ��� ��Ű�� ��ǰ ���� ��½� �ʿ��� ����
				$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=0;
				if((int)$sellprice_package_listtmp>0) {
					$price_package_listtmp[$basketrow2->productcode][$basketrow2->package_idx]=(int)$sellprice_package_listtmp;
					if(ord($package_price_exp[$basketrow2->package_idx])>0) {
						$package_price_expexp = explode(",",$package_price_exp[$basketrow2->package_idx]);
						if(ord($package_price_expexp[0])>0 && $package_price_expexp[0]>0) {
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

//������� �ľ�
$errmsg="";
$sql = "SELECT a.quantity as sumquantity, a.opt1_idx ,b.productcode,b.productname,b.display,b.quantity,b.group_check, ";
$sql.= "b.option_quantity,b.option_ea,b.etctype,b.assembleuse,a.assemble_list AS basketassemble_list ";
$sql.= ", c.assemble_list,a.package_idx ";
$sql.= "FROM tblbasket a, tblproduct b ";
$sql.= "LEFT OUTER JOIN tblassembleproduct c ON b.productcode=c.productcode ";
$sql.= "WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
$sql.= "AND a.productcode=b.productcode ";
$result=pmysql_query($sql,get_db_conn());
$assemble_proquantity_cnt=0;
while($row=pmysql_fetch_object($result)) {


	##### �ɼǺ� �Ǹż��� �� ���� ������� ���� ####
	if($row->option_ea && !$row->option_quantity){
		$temp_option_ea = explode(",",$row->option_ea);
		$row->sumquantity = $temp_option_ea[($row->opt1_idx-1)]*$row->sumquantity;
	}


	if($row->display!="Y") {
		$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ���� �ʴ� ��ǰ�Դϴ�.\\n";
	}
	if($row->group_check!="N") {
		if(ord($_ShopInfo->getMemid())>0) {
			$sqlgc = "SELECT COUNT(productcode) AS groupcheck_count FROM tblproductgroupcode ";
			$sqlgc.= "WHERE productcode='{$row->productcode}' ";
			$sqlgc.= "AND group_code='".$_ShopInfo->getMemgroup()."' ";
			$resultgc=pmysql_query($sqlgc,get_db_conn());
			if($rowgc=@pmysql_fetch_object($resultgc)) {
				if($rowgc->groupcheck_count<1) {
					$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
				}
				@pmysql_free_result($resultgc);
			} else {
				$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� ���� ��� ���� ��ǰ�Դϴ�.\\n";
			}
		} else {
			$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� ȸ�� ���� ��ǰ�Դϴ�.\\n";
		}
	}
	$assemble_list_exp = array();
	if(ord($errmsg)==0 && $row->assembleuse=="Y") { // ����/�ڵ� ��ǰ ��Ͽ� ���� ������ǰ üũ
		if(ord($row->assemble_list)==0) {
			$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� ������ǰ�� �̵�ϵ� ��ǰ�Դϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.\\n";
		} else {
			$assemble_list_exp = explode("",$row->basketassemble_list);
		}
	}
	if(ord($errmsg)==0) {
		$miniq=1;
		$maxq="?";
		if(ord($row->etctype)>0) {
			$etctemp = explode("",$row->etctype);
			for($i=0;$i<count($etctemp);$i++) {
				if(strpos($etctemp[$i],"MINIQ=")===0)     $miniq=substr($etctemp[$i],6);
				if(strpos($etctemp[$i],"MAXQ=")===0)      $maxq=substr($etctemp[$i],5);
			}
		}

		if(ord(dickerview($row->etctype,0,1))>0) {
			$errmsg="[".str_replace("'","",$row->productname)."]��ǰ�� �ǸŰ� ���� �ʽ��ϴ�. �ٸ� ��ǰ�� �ֹ��� �ּ���.\\n";
		}
	}

	$package_productcode_tmp = array();
	$package_quantity_tmp = array();
	$package_productname_tmp = array();
	if(ord($errmsg)==0 && $row->package_idx>0) { // ��Ű�� ��ǰ ��Ͽ� ���� ������ǰ üũ
		if(count($productcode_package_list[$row->productcode][$row->package_idx])>0) {
			$package_productcode_tmp = $productcode_package_list[$row->productcode][$row->package_idx];
			$package_quantity_tmp = $quantity_package_list[$row->productcode][$row->package_idx];
			$package_productname_tmp = $productname_package_list[$row->productcode][$row->package_idx];
		}
	}

	if(ord($errmsg)==0) {



		if ($miniq!=1 && $miniq>1 && $row->sumquantity<$miniq)
			$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� �ּ� {$miniq}�� �̻� �ֹ��ϼž� �մϴ�.\\n";

		if ($maxq!="?" && $maxq>0 && $row->sumquantity>$maxq)
			$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� �ִ� {$maxq}�� ���Ϸ� �ֹ��ϼž� �մϴ�.\\n";

		if(ord($row->quantity)>0) {
			if ($row->sumquantity>$row->quantity) {
				if ($row->quantity>0)
					$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� ����� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� {$row->quantity} �� �Դϴ�.")."\\n";
				else
					$errmsg.= "[".str_replace("'","",$row->productname)."]��ǰ�� ����� �ٸ����� �ֹ����� ������ ��ٱ��� �������� �۽��ϴ�.\\n";
			}
		}


		if($assemble_proquantity_cnt==0) { //�Ϲ� �� ������ǰ���� ����� ��������
			///////////////////////////////// �ڵ�/���� ������� ���� ����� üũ ///////////////////////////////////////////////
			$basketsql = "SELECT productcode,assemble_list,quantity,assemble_idx FROM tblbasket ";
			$basketsql.= "WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$basketresult = pmysql_query($basketsql,get_db_conn());
			while($basketrow=@pmysql_fetch_object($basketresult)) {
				if($basketrow->assemble_idx>0) {
					if(ord($basketrow->assemble_list)>0) {
						$assembleprolistexp = explode("",$basketrow->assemble_list);
						for($i=0; $i<count($assembleprolistexp); $i++) {
							if(ord($assembleprolistexp[$i])>0) {
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
		if(count($assemble_list_exp)>0) { // ������ǰ�� ��� üũ
			$assemprosql = "SELECT productcode,quantity,productname FROM tblproduct ";
			$assemprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_exp)."') ";
			$assemprosql.= "AND display = 'Y' ";
			$assemproresult=pmysql_query($assemprosql,get_db_conn());
			while($assemprorow=@pmysql_fetch_object($assemproresult)) {
				if(ord($assemprorow->quantity)>0) {
					if($assemble_proquantity[$assemprorow->productcode]>$assemprorow->quantity) {
						if($assemprorow->quantity>0) {
							$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� ������ǰ [".str_replace("'","",$assemprorow->productname)."] ����� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� {$assemprorow->quantity} �� �Դϴ�.")."\\n";
						} else {
							$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� ������ǰ [".str_replace("'","",$assemprorow->productname)."] �ٸ� ������ �ֹ����� ǰ���Ǿ����ϴ�.\\n";
						}
					}
				}
			}
		} else if(count($package_productcode_tmp)>0) { // ��Ű�� ������ǰ�� ��� üũ
			//$package_productcode_tmpexp = explode("",$package_productcode_tmp);
			//$package_quantity_tmpexp = explode("",$package_quantity_tmp);
			//$package_productname_tmpexp = explode("",$package_productname_tmp);
			$package_productcode_tmpexp = $package_productcode_tmp;
			$package_quantity_tmpexp = $package_quantity_tmp;
			$package_productname_tmpexp = $package_productname_tmp;
			for($i=0; $i<count($package_productcode_tmpexp); $i++) {
				if(ord($package_productcode_tmpexp[$i])>0) {
					if(ord($package_quantity_tmpexp[$i])>0) {
						if($assemble_proquantity[$package_productcode_tmpexp[$i]] > $package_quantity_tmpexp[$i]) {
							if($package_quantity_tmpexp[$i]>0) {
								$errmsg.="�ش� ��ǰ�� ��Ű�� [".str_replace("'","",$package_productname_tmpexp[$i])."] ����� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� {$package_quantity_tmpexp[$i]} �� �Դϴ�.")."\\n";
							} else {
								$errmsg.="�ش� ��ǰ�� ��Ű�� [".str_replace("'","",$package_productname_tmpexp[$i])."] �ٸ� ������ �ֹ����� ǰ���Ǿ����ϴ�.\\n";
							}
						}
					}
				}
			}
		} else { // �Ϲݻ�ǰ�� ��� üũ
			if(ord($row->quantity)>0) {
				if($assemble_proquantity[$assemprorow->productcode]>$row->quantity) {
					if ($row->quantity>0) {
						$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� ����� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"���� {$row->quantity} �� �Դϴ�.")."\\n";
					} else {
						$errmsg.= "[".str_replace("'","",$row->productname)."]��ǰ�� ����� �ٸ����� �ֹ����� ������ ��ٱ��� �������� �۽��ϴ�.\\n";
					}
				}
			}
		}
		if(ord($row->option_quantity)>0) {
			$sql = "SELECT opt1_idx, opt2_idx, quantity FROM tblbasket ";
			$sql.= "WHERE tempkey='".$_ShopInfo->getTempkey()."' ";
			$sql.= "AND productcode='{$row->productcode}' ";
			$result2=pmysql_query($sql,get_db_conn());
			while($row2=pmysql_fetch_object($result2)) {
				$optioncnt = explode(",",ltrim($row->option_quantity,','));
				$optionvalue=$optioncnt[($row2->opt2_idx==0?0:($row2->opt2_idx-1))*10+($row2->opt1_idx-1)];

				if($optionvalue<=0 && $optionvalue!="") {
					$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� �ɼ��� �ٸ� ������ �ֹ����� ǰ���Ǿ����ϴ�.\\n";
				} else if($optionvalue<$row2->quantity && $optionvalue!="") {
					$errmsg.="[".str_replace("'","",$row->productname)."]��ǰ�� ���õ� �ɼ��� ����� ".($_data->ETCTYPE["STOCK"]=="N"?"�����մϴ�.":"$optionvalue �� �Դϴ�.")."\\n";
				}
			}
			pmysql_free_result($result2);
		}
	}
}
pmysql_free_result($result);



if(ord($errmsg)>0) {
	echo "<html></head><body onload=\"alert('{$errmsg}');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
	exit;
}

$sql = "SELECT b.vender FROM tblbasket a, tblproduct b WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
$sql.= "AND a.productcode=b.productcode GROUP BY b.vender ";
$res=pmysql_query($sql,get_db_conn());

$sumprice=0;
$reserve=0;
$deli_price=0;

$optcnt=0;
$count=0;
$setquotacnt = 0;
$basketcnt=array();
$prcode=array();
$arrvender=array();
$prprice=array();
$prname=array();
$orderpatten = array("'","\\\\");
$orderreplace = array("","");
$goodname="";
$allprname="";
$arr_deliprice=array();
$arr_delimsg=array();
$arr_delisubj=array();

$address=" ".$raddr1;
$address=str_replace("'","",strip_tags($address));
$address=" ".$address;	//������ ��۷� ���ϱ�����....
$beforehand_reservePrice = 0;

while($vgrp=pmysql_fetch_object($res)) {
	//1. vender�� 0�� �ƴϸ� �ش� ������ü�� ��ۺ� �߰� �������� �����´�.
	$_vender=null;
	if($vgrp->vender>0) {
		$sql = "SELECT deli_price,deli_pricetype,deli_mini,deli_area,deli_limit,deli_area_limit FROM tblvenderinfo WHERE vender='{$vgrp->vender}' ";
		$res2=pmysql_query($sql,get_db_conn());
		if($_vender=pmysql_fetch_object($res2)) {
			if($_vender->deli_price==-9) {
				$_vender->deli_price=0;
				$_vender->deli_after="Y";
			}
			if ($_vender->deli_mini==0) $_vender->deli_mini=1000000000;
		}
		pmysql_free_result($res2);

	}

	$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,SUBSTR(a.date,1,8) date,b.productcode,b.productname,b.sellprice,b.membergrpdc, b.option_reserve, ";
	$sql.= "b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option_ea,b.option1,b.option2, ";
	$sql.= "b.etctype,b.deli_price,b.deli,b.sellprice*a.quantity as realprice, b.selfcode, b.bisinesscode,a.assemble_list,a.assemble_idx,a.package_idx ";
	$sql.= "FROM tblbasket a, tblproduct b ";
	$sql.= "WHERE b.vender='{$vgrp->vender}' ";
	$sql.= "AND a.tempkey='".$_ShopInfo->getTempkey()."' ";
	$sql.= "AND a.productcode=b.productcode ";
	$sql.= "ORDER BY a.date DESC ";
	$result=pmysql_query($sql,get_db_conn());

	$vender_sumprice = 0;	//�ش� ������ü�� �� ���ž�
	$vender_delisumprice = 0;//�ش� ������ü�� �⺻��ۺ� �� ���ž�
	$vender_deliprice = 0;
	$deli_productprice=0;
	$deli_productname1="";
	$deli_productname2="";
	$deli_init = false;
	$aryRealPrice = array();
	$aryProductName = array();
	while($row = pmysql_fetch_object($result)) {
		$groupPriceList = $product->getProductGroupPrice($row->productcode);
		if ($groupPriceList) { // �Ϲ� �� ����ȸ�� �ݾ� ���ý� �α��� �Ǿ��մ� user ��޿� ���� �Ǹ� �ݾ� ����
			$row->sellprice = $groupPriceList[sellprice];
			$row->consumerprice = $groupPriceList[consumerprice];
			$row->consumer_reserve = $groupPriceList[consumer_reserve];
		}
		//ȯ������ ����
		$row->sellprice = exchageRate($row->sellprice);
		$row->consumerprice = exchageRate($row->consumerprice);
		$row->consumer_reserve = exchageRate($row->consumer_reserve);

		if(ord($prcode[0])>0) {
			if(substr($row->productcode,0,12)==substr($prcode[0],0,12)) $prcode[0]=substr($prcode[0],0,12);
			else if(substr($row->productcode,0,9)==substr($prcode[0],0,9)) $prcode[0]=substr($prcode[0],0,9);
			else if(substr($row->productcode,0,6)==substr($prcode[0],0,6)) $prcode[0]=substr($prcode[0],0,6);
			else if(substr($row->productcode,0,3)==substr($prcode[0],0,3)) $prcode[0]=substr($prcode[0],0,3);
			else $prcode[0]="";
		}
		if((int)$basketcnt[0]==0) $prcode[0]=$row->productcode;

		if($vgrp->vender>0) {
			if(ord($prcode[$vgrp->vender])>0) {
				if(substr($row->productcode,0,12)==substr($prcode[$vgrp->vender],0,12)) $prcode[$vgrp->vender]=substr($prcode[$vgrp->vender],0,12);
				else if(substr($row->productcode,0,9)==substr($prcode[$vgrp->vender],0,9)) $prcode[$vgrp->vender]=substr($prcode[$vgrp->vender],0,9);
				else if(substr($row->productcode,0,6)==substr($prcode[$vgrp->vender],0,6)) $prcode[$vgrp->vender]=substr($prcode[$vgrp->vender],0,6);
				else if(substr($row->productcode,0,3)==substr($prcode[$vgrp->vender],0,3)) $prcode[$vgrp->vender]=substr($prcode[$vgrp->vender],0,3);
				else $prcode[$vgrp->vender]="";
			}
			if((int)$basketcnt[$vgrp->vender]==0) $prcode[$vgrp->vender]=$row->productcode;
		}

		$optvalue2[$count]="";
		if(preg_match("/^\[OPTG\d{4}\]$/",$row->option1)) {
			$optioncode = substr($row->option1,5,4);
			$row->option_price="";
			if($row->optidxs!="") {
				$tempoptcode = substr($row->optidxs,0,-1);
				$exoptcode = explode(",",$tempoptcode);
				$sqlopt = "SELECT * FROM tblproductoption WHERE option_code='{$optioncode}' ";
				$resultopt = pmysql_query($sqlopt,get_db_conn());
				if($rowopt = pmysql_fetch_object($resultopt)){
					$optionadd = array (&$rowopt->option_value01,&$rowopt->option_value02,&$rowopt->option_value03,&$rowopt->option_value04,&$rowopt->option_value05,&$rowopt->option_value06,&$rowopt->option_value07,&$rowopt->option_value08,&$rowopt->option_value09,&$rowopt->option_value10);
					$opti=0;
					$optvalue[$count]="";
					while(ord($optionadd[$opti])>0) {
						if($exoptcode[$opti]>0) {
							$opval = explode("",str_replace('"','',$optionadd[$opti]));
							$exop = explode(",",str_replace('"','',$opval[$exoptcode[$opti]]));
							$optvalue[$count].= ", {$opval[0]} : ";
							if ($exop[1]>0) $optvalue[$count].=$exop[0]."(<font color=#FF3C00>+{$exop[1]}��</font>)";
							else if($exop[1]==0) $optvalue[$count].=$exop[0];
							else $optvalue[$count].=$exop[0]."(<font color=#FF3C00>{$exop[1]}��</font>)";
							$row->sellprice+=$exop[1];
						}
						$opti++;
					}
					$optvalue[$count] = substr($optvalue[$count],1);
					$optcnt++;

					$optvalue2[$count] = sprintf("[OPTG%03d]",$optcnt);
				}
			}
		}

		$productcode[$count]=$row->productcode;
		$option_quantity[$productcode[$count]]=$row->option_quantity;
		$option_ea[$productcode[$count]]=$row->option_ea;
		$option1num[$count]=$row->opt1_idx;
		$option2num[$count]=($row->opt2_idx>0?$row->opt2_idx:1);
		$productname[$count]=str_replace($orderpatten,$orderreplace,$row->productname);
		$addcode[$count]=str_replace($orderpatten,$orderreplace,$row->addcode);
		$quantity[$count]=$row->quantity;
		$vender[$count]=$vgrp->vender;
		$selfcode[$count]=$row->selfcode;
		$bisinesscode[$count]=$row->bisinesscode;
		$assemble_idx[$count]=$row->assemble_idx;
		$assemble_info[$count]="";
		$assemble_productcode[$count]="";
		$package_idx[$count]=$row->package_idx;
		$package_info[$count]="";
		$package_productcode[$count]="";

		if(ord($row->bisinesscode)>0) {
			$bisinessvalue[$row->bisinesscode]=$row->bisinesscode;
		}

		if($msg_type=="2") {
			$ordermessage[$count]=${"order_prmsg".$count};
		} else {
			$ordermessage[$count]=$order_prmsg;
		}
		$ordermessage[$count]=str_replace("'","",$ordermessage[$count]);

		if($row->assemble_idx>0 && ord(str_replace("","",$row->assemble_list))>0) {
			$assemble_list_proexp = explode("",$row->assemble_list);
			$alprosql = "SELECT productcode,productname,sellprice FROM tblproduct ";
			$alprosql.= "WHERE productcode IN ('".implode("','",$assemble_list_proexp)."') ";
			$alprosql.= "AND display = 'Y' ";
			$alproresult=pmysql_query($alprosql,get_db_conn());

			$assemble_productcode_imp=array();
			$assemble_productname_imp=array();
			$assemble_sellprice_imp=array();
			$assemble_sellerprice=0;
			while($alprorow=@pmysql_fetch_object($alproresult)) {
				$assemble_sellerprice+=$alprorow->sellprice;
				$assemble_productcode_imp[]=$alprorow->productcode;
				$assemble_productname_imp[]=$alprorow->productname;
				$assemble_sellprice_imp[]=(int)$alprorow->sellprice;
			}
			if(count($assemble_productcode_imp)>0) {
				$assemble_info[$count] = str_replace($orderpatten,$orderreplace,implode("",$assemble_productcode_imp).":".implode("",$assemble_productname_imp).":".implode("",$assemble_sellprice_imp));
				$assemble_productcode[$count]=implode("",$assemble_productcode_imp);
			}
			@pmysql_free_result($alproresult);

			//######### �ڵ�/������ ���� ���� ���� üũ ###############
			$price = $assemble_sellerprice;
			$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$assemble_sellerprice,"N");
		} else if($row->package_idx>0 && ord($row->package_idx)>0) {
			$package_info[$count] = str_replace($orderpatten,$orderreplace,implode("",$productcode_package_list[$row->productcode][$row->package_idx]).":".implode("",$productname_package_list[$row->productcode][$row->package_idx]).":".$price_package_listtmp[$row->productcode][$row->package_idx]).":".$title_package_listtmp[$row->productcode][$row->package_idx];
			$package_productcode[$count]=implode("",$productcode_package_list[$row->productcode][$row->package_idx]);

			//######### �ɼǿ� ���� ���� ���� üũ ###############
			if (ord($row->option_price)==0) {
				$price = $row->sellprice+$price_package_listtmp[$row->productcode][$row->package_idx];
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$price,"N");
			} else if (ord($row->opt1_idx)>0) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$priceindex = count($pricetok);
				$price = $pricetok[$row->opt1_idx-1]+$price_package_listtmp[$row->productcode][$row->package_idx];
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$price,"N");
			}
		} else {
			//######### �ɼǿ� ���� ���� ���� üũ ###############
			if (ord($row->option_price)==0) {
				$price = $row->sellprice;
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"N");
			} else if (ord($row->opt1_idx)>0) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$priceindex = count($pricetok);
				$price = $row->sellprice + $pricetok[$row->opt1_idx-1];
				$tempreserve = getReserveConversion($row->reserve,$row->reservetype,$pricetok[$row->opt1_idx-1],"N");
			}
		}
		$realreserve[$count]=$tempreserve;

		if (ord($goodname)>0) $goodname = $row->productname." ��.."; else $goodname = $row->productname;

		### Ÿ�� ���� / ������ Ư�� �������� �� ����
		$timesale_sellprice = $couponDefaultPrice = 0;
		$timesale_sellprice = getSpeDcPrice($row->productcode);
		if($timesale_sellprice > 0) $price = $timesale_sellprice;
		$couponDefaultPrice = $price * $row->quantity;

		//######### ��ǰ Ư�����η� ���� ############
		$dc_data = $product->getProductDcRate($row->productcode);
		$salemoney = getProductDcPrice($price,$dc_data[price]);
		$salereserve = getProductDcPrice($price,$dc_data[reserve]);

		//######### �ɼǺ� ������ ���� ############
		$option_reserve = explode(',',$row->option_reserve);

		if($option_reserve[$row->opt1_idx-1]>0){
			$tempreserve=$option_reserve[$row->opt1_idx-1];
		}

		//�߰� ������ ����
		$tempreserve+=$salereserve;
		$realreserve[$count] = $tempreserve;

		//ȸ�� ������ ����
		$bf_price = $price;
		$price = $price - $salemoney;
		$tot_salemoney+=$salemoney*$row->quantity;

		//######### �ɼǿ� ���� ���� ���� üũ �� ############
		$bf_sumprice += $bf_price*$row->quantity;  //������ �� �ݾ�(�ѱ��űݾ״뺰 ���η��� ���ϱ� ����)
		$sumprice += $price*$row->quantity;


		$vender_sumprice += $price*$row->quantity;
		$reserve += $tempreserve*$row->quantity;

		$arrvender[0]["sumprice"]+=$bf_price*$row->quantity;
		if($vgrp->vender>0) {
			$arrvender[$vgrp->vender]["sumprice"]+=$bf_price*$row->quantity;
		}

		if ($row->opt1_idx>0) {
			$temp = $row->option1;
			$tok = explode(",",$temp);
			$option1[$count]=$tok[0]." : ".$tok[$row->opt1_idx];
			$option1[$count]=str_replace("'","",$option1[$count]);
		}  // if
		if ($row->opt2_idx>0) {
			$temp = $row->option2;
			$tok = explode(",",$temp);
			$option2[$count]=$tok[0]." : ".$tok[$row->opt2_idx];
			$option2[$count]=str_replace("'","",$option2[$count]);
		}  // if
		if(ord($optvalue2[$count])>0) $option1[$count]=$optvalue2[$count];
		$date[$count]=$row->date;
//		$realprice[$count]=$price;
		$realprice[$count]=$bf_price;


		### Ÿ�� ���� / ������ Ư�� �������� �� ����
		$aryRealPrice[$row->productcode] = $couponDefaultPrice;
		$aryProductName[$row->productcode] = $row->productname;

		########### ���� ���� ###############
		$prprice[0][$row->productcode]=$price*$row->quantity;
		$prprice[0][substr($row->productcode,0,3)]+=$price*$row->quantity;
		$prprice[0][substr($row->productcode,0,6)]+=$price*$row->quantity;
		$prprice[0][substr($row->productcode,0,9)]+=$price*$row->quantity;
		$prprice[0][substr($row->productcode,0,12)]+=$price*$row->quantity;

		$prname[0][$row->productcode]=$row->productname.", ";
		$prname[0][substr($row->productcode,0,3)].=$row->productname.", ";
		$prname[0][substr($row->productcode,0,6)].=$row->productname.", ";
		$prname[0][substr($row->productcode,0,9)].=$row->productname.", ";
		$prname[0][substr($row->productcode,0,12)].=$row->productname.", ";
		if($vgrp->vender>0) {
			$prprice[$vgrp->vender][$row->productcode]=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,3)]+=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,6)]+=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,9)]+=$price*$row->quantity;
			$prprice[$vgrp->vender][substr($row->productcode,0,12)]+=$price*$row->quantity;

			$prname[$vgrp->vender][$row->productcode]=$row->productname.", ";
			$prname[$vgrp->vender][substr($row->productcode,0,3)].=$row->productname.", ";
			$prname[$vgrp->vender][substr($row->productcode,0,6)].=$row->productname.", ";
			$prname[$vgrp->vender][substr($row->productcode,0,9)].=$row->productname.", ";
			$prname[$vgrp->vender][substr($row->productcode,0,12)].=$row->productname.", ";
		}


		$allprname.=$row->productname.", ";

		//######## Ư����üũ : ���ݰ�����ǰ//�����ڻ�ǰ #####
		if (ord($row->etctype)>0) {
			$etctemp = explode("",$row->etctype);
			for ($i=0;$i<count($etctemp);$i++) {
				switch ($etctemp[$i]) {
					case "BANKONLY":
						$bankonly = "Y";
						break;
					case "SETQUOTA":
						if ($card_splittype=="O" && $sumprice>=$card_splitprice) {
							$setquotacnt++;
						}
						break;
				}
			}
		}

		//################ ���� ��ۺ� üũ #################
		if (($row->deli=="Y" || $row->deli=="N") && $row->deli_price>0) {
			if($row->deli=="Y") {
				$deli_productprice += $row->deli_price*$row->quantity;
				$deli_productname2.=$row->productname.", ";
			} else {
				$deli_productprice += $row->deli_price;
				$deli_productname2.=$row->productname.", ";
			}
		} else if($row->deli=="F" || $row->deli=="G") {
			$deli_productprice += 0;
			if($row->deli=="F") {
				$deli_productname2.=$row->productname.", ";
			} else {
				$deli_productname2.=$row->productname.", ";
			}
		} else {
			$deli_init=true;
			$vender_delisumprice += $price*$row->quantity;
		}
		$deli_productname1.=$row->productname.", ";

		$basketcnt[0]++;
		if($vgrp->vender>0) $basketcnt[$vgrp->vender]++;
		$count++;
	}
	pmysql_free_result($result);

	$deli_area="";
	$deli_productname="";
	$vender_deliprice=$deli_productprice;
	if($deli_productprice>0) {
		$deli_productname=$deli_productname2;
	}

	$vender_deliarealimit_init=false;
	if($_vender) {
		$arr_delisubj[$vgrp->vender]="";
		if(ord($_vender->deli_area_limit)>0) {
			if($_vender->deli_pricetype=="Y") {
				$vender_delisumprice = $vender_sumprice;
			}

			$vender_deliarealimit = "";
			$vender_deliarealimit_exp = "";
			$deli_area_limit_exp = "";
			$deli_area_limit_exp1 = "";
			$deli_area_limit_exp2 = "";

			$deli_area_limit_exp = explode(":",$_vender->deli_area_limit);
			for($i=0; $i<count($deli_area_limit_exp); $i++) {
				$deli_area_limit_exp1=explode("=",$deli_area_limit_exp[$i]);

				$deli_area_limit_exp2=explode(",",$deli_area_limit_exp1[0]);
				for($jj=0;$jj<count($deli_area_limit_exp2);$jj++){
					if(ord(trim($deli_area_limit_exp2[$jj]))>0 && strpos($address,$deli_area_limit_exp2[$jj])>0) {
						$vender_deliarealimit = setDeliLimit($vender_delisumprice,@implode("=", @array_slice($deli_area_limit_exp1, 1)),"Y");
						if(ord($vender_deliarealimit)>0) {
							$vender_deliarealimit_exp = explode("", $vender_deliarealimit);
							$vender_deliarealimit_init=true;
							$vender_deliprice+=$vender_deliarealimit_exp[0];
							$arr_delisubj[$vgrp->vender]="�ش� ����� {$deli_area_limit_exp2[$jj]}�̰� ��ǰ �����հ谡 {$vender_deliarealimit_exp[1]}�� ���";
							break;
						}
					}
				}
				if(ord($vender_deliarealimit_exp[0])>0) {
					break;
				}
			}
		}

		if($vender_deliarealimit_init==false){
			if($_vender->deli_price>0) {
				if($_vender->deli_pricetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}

				if ($vender_delisumprice<$_vender->deli_mini && $deli_init) {
					$vender_deliprice+=$_vender->deli_price;
					$deli_productname=$deli_productname1;

					if($_vender->deli_mini<1000000000) {
						$arr_delisubj[$vgrp->vender]="�ش� ��ǰ �����հ谡 ".number_format($_vender->deli_mini)."�� �̸��� ���";
					} else {
						$arr_delisubj[$vgrp->vender]="�ش� ��ǰ ���Ž� ������ û��";
					}
				}
			} else if(ord($_vender->deli_limit)>0) {
				if($_vender->deli_pricetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}
				if($deli_init) {
					$delilmitprice = setDeliLimit($vender_delisumprice,$_vender->deli_limit,"Y");
					$delilmitprice_exp = explode("", $delilmitprice);
					$vender_deliprice+=$delilmitprice_exp[0];
					$deli_productname=$deli_productname1;

					$arr_delisubj[$vgrp->vender]="�ش� ��ǰ �����հ谡 {$delilmitprice_exp[1]}�� ���";
				}
			}
		}
		$deli_area=$_vender->deli_area;
	} else {
		$arr_delisubj[$vgrp->vender]="";
		if(ord($_data->deli_area_limit)>0) {
			if($_data->deli_basefeetype=="Y") {
				$vender_delisumprice = $vender_sumprice;
			}

			$vender_deliarealimit = "";
			$vender_deliarealimit_exp = "";
			$deli_area_limit_exp = "";
			$deli_area_limit_exp1 = "";
			$deli_area_limit_exp2 = "";

			$deli_area_limit_exp = explode(":",$_data->deli_area_limit);
			for($i=0; $i<count($deli_area_limit_exp); $i++) {
				$deli_area_limit_exp1=explode("=",$deli_area_limit_exp[$i]);

				$deli_area_limit_exp2=explode(",",$deli_area_limit_exp1[0]);
				for($jj=0;$jj<count($deli_area_limit_exp2);$jj++){
					if(ord(trim($deli_area_limit_exp2[$jj]))>0 && strpos($address,$deli_area_limit_exp2[$jj])>0) {
						$vender_deliarealimit = setDeliLimit($vender_delisumprice,@implode("=", @array_slice($deli_area_limit_exp1, 1)),"Y");

						if(ord($vender_deliarealimit)>0) {
							$vender_deliarealimit_exp = explode("", $vender_deliarealimit);
							$vender_deliarealimit_init=true;
							$vender_deliprice+=$vender_deliarealimit_exp[0];
							$arr_delisubj[$vgrp->vender]="�ش� ����� {$deli_area_limit_exp2[$jj]}�̰� ��ǰ �����հ谡 {$vender_deliarealimit_exp[1]}�� ���";
							break;
						}
					}
				}
				if(ord($vender_deliarealimit_exp[0])>0) {
					break;
				}
			}
		}

		if($vender_deliarealimit_init==false){
			if($_data->deli_basefee>0) {
				if($_data->deli_basefeetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}

				if ($vender_delisumprice<$_data->deli_miniprice && $deli_init) {
					$vender_deliprice+=$_data->deli_basefee;
					$deli_productname=$deli_productname1;

					if($_data->deli_miniprice<1000000000) {
						$arr_delisubj[$vgrp->vender]="�ش� ��ǰ �����հ谡 ".number_format($_data->deli_miniprice)."�� �̸��� ���";
					} else {
						$arr_delisubj[$vgrp->vender]="�ش� ��ǰ ���Ž� ������ û��";
					}
				}
			} else if(ord($_data->deli_limit)>0) {
				if($_data->deli_basefeetype=="Y") {
					$vender_delisumprice = $vender_sumprice;
				}

				if($deli_init) {
					$delilmitprice = setDeliLimit($vender_delisumprice,$_data->deli_limit,"Y");
					$delilmitprice_exp = explode("", $delilmitprice);
					$vender_deliprice+=$delilmitprice_exp[0];
					$deli_productname=$deli_productname1;

					$arr_delisubj[$vgrp->vender]="�ش� ��ǰ �����հ谡 {$delilmitprice_exp[1]}�� ���";
				}
			}
		}
		$deli_area=$_data->deli_area;
	}
	if($deli_productprice>0) {
		if(ord($arr_delisubj[$vgrp->vender])>0) {
			$arr_delisubj[$vgrp->vender].=", ��ǰ ������ۺ� ����";
		} else {
			$arr_delisubj[$vgrp->vender].="��ǰ ������ۺ� ����";
		}
	}

	//������ ��۷Ḧ ����Ѵ�.
	$area_price=0;
	$array_deli = explode("|",$deli_area);
	$cnt2= floor(count($array_deli)/2);
	for($kk=0;$kk<$cnt2;$kk++){
		$subdeli=explode(",",$array_deli[$kk*2]);
		for($jj=0;$jj<count($subdeli);$jj++){
			if(ord(trim($subdeli[$jj]))>0 && strpos($address,$subdeli[$jj])>0) {
				$area_price=$array_deli[$kk*2+1];
			}
		}
	}

	if($area_price>0) {
		if(ord($arr_delisubj[$vgrp->vender])>0) {
			$arr_delisubj[$vgrp->vender].=", �ش� ����� �߰���۷�";
		} else {
			$arr_delisubj[$vgrp->vender].="�ش� ����� �߰���۷�";
		}
	}


	$vender_deliprice+=$area_price;
	if($vender_deliprice>0) {
		//�׷��ۺ� ����ó��
		if($group_deli_free!='1'){
			$arr_deliprice[$vgrp->vender]=$vender_deliprice;
		}else{
			$arr_deliprice[$vgrp->vender]=0;
		}
		$arr_delimsg[$vgrp->vender]=substr($deli_productname,0,-2);
	}
	$deli_price+=$vender_deliprice;
}
pmysql_free_result($res);

//�׷��ۺ� ����ó��
if($group_deli_free=='1'){
	$deli_price=0;
}

if($_POST['direct_deli']=='Y'){
	$deli_price=0;
}

if(count($bisinessvalue)>0) {
	$bisinessvalue_imp = implode("','", $bisinessvalue);
	$bisql = "SELECT companyviewval, companycode ";
	$bisql.= "FROM tblproductbisiness ";
	$bisql.= "WHERE companycode IN ('{$bisinessvalue_imp}') ";
	$biresult=pmysql_query($bisql,get_db_conn());

	while($birow = pmysql_fetch_object($biresult)) {
		$companyviewval[$birow->companycode] = str_replace($orderpatten,$orderreplace,$birow->companyviewval);
	}
}
// ���ݰ�����ǰ�� �ִµ� ī��������ý�
if ($bankonly=="Y" && !strstr("BVOQ",$paymethod)) {
	echo "<html></head><body onload=\"alert('���ݰ��� ��ǰ�� �ֱ� ������ ������ �Ա� ������ �����Ͻ� �� �ֽ��ϴ�.');parent.document.form1.process.value='N';parent.ProcessWait('hidden');\"></body></html>";
	exit;
}

// ��ü��ǰ(basketcnt)�� �����ڼ��û�ǰ(setquotacnt)�� ���� �����������̰�����ǰ���� ���õǾ� ������
if ($basketcnt[0]==$setquotacnt && $setquotacnt>0 && $card_splittype=="O") $card_splittype="Y";

if($reserve_limit<0) $reserve_limit=(int)($sumprice*abs($reserve_limit)/100);

$usereserve = str_replace(",","",$usereserve);

if ($usereserve>0) {
	if($reserve_maxprice>$sumprice)
		$usereserve=0;
	else if($user_reserve>=$_data->reserve_maxuse && $usereserve<=$reserve_limit && $usereserve<=$user_reserve) {
		$reserve_type="Y";
	} else $usereserve=0;
} else $usereserve=0;

if($_data->coupon_ok=="Y" && strlen($coupon_code)==8 && $rcall_type=="N" && $usereserve>0) {
	$usereserve=0;
}

if($sumprice<$_data->bank_miniprice) {
	echo "<html></head><body onload=\"alert('�ֹ� ������ �ּ� �ݾ��� ".number_format($_data->bank_miniprice)."�� �Դϴ�.');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
	exit;
} else if($sumprice<=0) {
	echo "<html></head><body onload=\"alert('��ǰ �� ������ 0���� ��� ��ǰ �ֹ��� ���� �ʽ��ϴ�.');parent.location.href='basket.php'\"></body></html>";
	exit;
}

if(strstr("CP", $paymethod)) {
	if($_data->card_miniprice>$sumprice) {
		echo "<html></head><body onload=\"alert('ī����� �ּ� �ֹ��ݾ׺��� �����ݾ��� �۽��ϴ�.');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
		exit;
	}
} else if(strstr("BVOQ",$paymethod) && $sumprice<$_data->bank_miniprice) {
	echo "<html></head><body onload=\"alert('�ּ� �ֹ��ݾ׺��� �����ݾ��� �۽��ϴ�.');parent.location.href='".$Dir.FrontDir."basket.php'\"></body></html>";
	exit;
}

if ($reserve_type=="N") $usereserve=0;

############################################# ������� �۾��Ϸ� ############################################




//�ֹ� ��ٱ��Ͽ� ��ǰ ����
for($orderi=0;$orderi<$count;$orderi++) {
	if(ord($optvalue2[$orderi])>0){
		$optvalue2[$orderi]=str_replace("'","\'",$optvalue2[$orderi]);
		$optvalue[$orderi]=str_replace("'","\'",$optvalue[$orderi]);
		$sql = "INSERT INTO tblorderoptiontemp (ordercode, productcode, opt_idx, opt_name) VALUES ('{$ordercode}','{$productcode[$orderi]}','{$optvalue2[$orderi]}','{$optvalue[$orderi]}')";
		pmysql_query($sql,get_db_conn());
		backup_save_sql($sql);
	}

	//if($reserve_useadd!="N" && $usereserve>=$reserve_useadd && $usereserve!=0) $realreserve[$orderi]=0;
	if($reserve_useadd!="N" && $usereserve>=$reserve_useadd && $usereserve!=0 || !$_ShopInfo->getMemid()) $realreserve[$orderi]=0;
	else if($reserve_useadd=="U" && $usereserve!=0) {
		$reservepercent = 100 * ($sumprice-$usereserve) / $sumprice;
		$realreserve[$orderi]=round($realreserve[$orderi]*($reservepercent/100),-1);
	}

	if($beforehand_reserve == 'Y'){//����������
		$beforehand_reservePrice += $realreserve[$orderi];
		$realreserve[$orderi] = 0;
		
	}
	

	$sql = "INSERT INTO tblorderproducttemp (vender, ordercode, tempkey, productcode, productname, opt1_name, opt2_name, package_idx, assemble_idx, addcode, quantity, price, reserve, date, selfcode, productbisiness, order_prmsg, assemble_info) VALUES ('{$vender[$orderi]}','{$ordercode}','".$_ShopInfo->getTempkey()."','{$productcode[$orderi]}','{$productname[$orderi]}','{$option1[$orderi]}','{$option2[$orderi]}','{$package_idx[$orderi]}','{$assemble_idx[$orderi]}','{$addcode[$orderi]}','{$quantity[$orderi]}','{$realprice[$orderi]}','{$realreserve[$orderi]}','{$date[$orderi]}','{$selfcode[$orderi]}', '{$companyviewval[$bisinesscode[$orderi]]}','{$ordermessage[$orderi]}','{$package_info[$orderi]}={$assemble_info[$orderi]}')";
	pmysql_query($sql,get_db_conn());
	backup_save_sql($sql);

	if (pmysql_errno()) {
		sendmail(AdminMail,"[���!] INSERT ERROR",$_SERVER['HTTP_HOST']."<br>$sql - ".pmysql_error(),"Content-Type: text/plain\r\n");
	}
}
delete_cache_file("main");

$oldtempkey=$_ShopInfo->getTempkey();
$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);
$_ShopInfo->setGifttempkey($oldtempkey);
$_ShopInfo->setOldtempkey($oldtempkey);
$_ShopInfo->setOkpayment("");
$_ShopInfo->Save();

if ($paymethod=="B") $pay_data = $pay_data1;
else if (strstr("CP", $paymethod)) $pay_data = $pay_data2;
else if ($paymethod=="V") $pay_data = "�ǽð� ������ü ������";
if($_data->ETCTYPE["VATUSE"]=="Y") {
	$sumpricevat = return_vat($sumprice);
}









































/* ��ǰ ���� ���� ���� ���� */
if(count($coupon_code_goods) > 0 && $sumprice > 0){
	$basketSql = array();
	foreach($coupon_code_goods as $couponGoods){
		$arrCouponGoods = explode("||", $couponGoods);
		$goods_cate_sql = "SELECT * FROM tblproductlink WHERE c_productcode = '".$arrCouponGoods[1]."'";
		$goods_cate_result = pmysql_query($goods_cate_sql,get_db_conn());
		$categorycode = array();
		while($goods_cate_row=pmysql_fetch_object($goods_cate_result)) {
			list($cate_a, $cate_b, $cate_c, $cate_d) = sscanf($goods_cate_row->c_category,'%3s%3s%3s%3s');
			$categorycode[] = $cate_a;
			$categorycode[] = $cate_a.$cate_b;
			$categorycode[] = $cate_a.$cate_b.$cate_c;
			$categorycode[] = $cate_a.$cate_b.$cate_c.$cate_d;
		}
		if(count($categorycode) > 0){
			$addCategoryQuery = "('".implode("', '", $categorycode)."')";
		}else{
			$addCategoryQuery = "('')";
		}

		$date = date("YmdH");
		$sqlGoodsCou = "SELECT
												a.coupon_code, a.coupon_name, a.sale_type, a.sale_money, a.bank_only, a.productcode,
												a.mini_price,a.use_con_type1,a.use_con_type2,a.use_point,a.vender, b.date_start, b.date_end, a.sale_max_money, a.amount_floor
											FROM
												tblcouponinfo a
												JOIN tblcouponissue b on a.coupon_code=b.coupon_code
												LEFT JOIN tblcouponproduct c on b.coupon_code=c.coupon_code
												LEFT JOIN tblcouponcategory d on b.coupon_code=d.coupon_code
											WHERE
												b.id='".$_ShopInfo->getMemid()."'
												AND b.date_start<='".date("YmdH")."'
												AND (b.date_end>='".date("YmdH")."' OR b.date_end='')
												AND a.coupon_code='".$arrCouponGoods[0]."'
												AND b.used='N'
												AND a.coupon_use_type = '2'
												AND (c.productcode = '".$arrCouponGoods[1]."' OR (d.categorycode IN ".$addCategoryQuery." AND a.use_con_type2 = 'Y'))
											ORDER BY
												coupon_use_type
											ASC";
		$resultGoodsCou = pmysql_query($sqlGoodsCou,get_db_conn());
		if($rowGoodsCou=pmysql_fetch_object($resultGoodsCou)) {
			$goods_coupon_code = $rowGoodsCou->coupon_code;
			$goods_coupon_name = $rowGoodsCou->coupon_name;
			$goods_use_con_type2 = $rowGoodsCou->use_con_type2;
			$goods_sale_type = $rowGoodsCou->sale_type;
			$goods_use_con_type1 = $rowGoodsCou->use_con_type1;
			$goods_sale_money = $rowGoodsCou->sale_money;
			$goods_mini_price = $rowGoodsCou->mini_price;
			$goods_vender = $rowGoodsCou->vender;
			$goods_bank_only = $rowGoodsCou->bank_only;
			$goods_amount_floor = $rowGoodsCou->amount_floor;
			$goods_delivery_type = $rowGoodsCou->delivery_type;
			$goods_delivery_type = $rowGoodsCou->delivery_type;
			$goods_sale_max_money = $rowGoodsCou->sale_max_money;


			if($goods_sale_type <= 2){
				$couponDcPrice = ($aryRealPrice[$arrCouponGoods[1]]*$goods_sale_money)*0.01;
				$couponDcPrice = ($couponDcPrice / pow(10, $goods_amount_floor)) * pow(10, $goods_amount_floor);
			}else{
				$couponDcPrice = $goods_sale_money;
			}
			if($goods_sale_max_money && $goods_sale_max_money < $couponDcPrice){
				$couponDcPrice = $goods_sale_max_money;
			}

			if($goods_sale_type%2==0) {
				$coumoney = -$couponDcPrice;
				$coureserve=0;
				$sumprice = $sumprice + $coumoney;
			}else {
				$coumoney = 0;
				$coureserve= $couponDcPrice;
			}
			$couponmsg = $aryProductName[$arrCouponGoods[1]];


			#$sqlC = "INSERT INTO tblorderproducttemp (vender, ordercode, tempkey, productcode, productname, quantity, price, reserve, date, order_prmsg) VALUES ('{$rowGoodsCou->vender}','{$ordercode}','{$oldtempkey}','COU{$goods_coupon_code}X','{$goods_coupon_name}','1','{$coumoney}','{$coureserve}','".date("Ymd")."','{$couponmsg}')";
			$sqlC = "INSERT INTO tblorderproducttemp (vender, ordercode, tempkey, productcode, productname, quantity, price, reserve, date, order_prmsg) VALUES ('{$rowGoodsCou->vender}','{$ordercode}','{$oldtempkey}','COU{$goods_coupon_code}X','{$goods_coupon_name}','1','[COUPON_MONEY]','[COUPON_RESERVE]','".date("Ymd")."','{$couponmsg}')";

			# ���� ������ �ٸ� ��ǰ���� ������ ��������� �ش� ������ ������/�������� ������
			$basketSql[$ordercode.$goods_coupon_code]['sql'] = $sqlC;
			$basketSql[$ordercode.$goods_coupon_code]['price'] = $basketSql[$ordercode.$goods_coupon_code]['price']+$coumoney;
			$basketSql[$ordercode.$goods_coupon_code]['reserve'] = $basketSql[$ordercode.$goods_coupon_code]['reserve']+$coureserve;
		}
		pmysql_free_result($resultGoodsCou);
	}
	foreach($basketSql as $v){
		$newQuery = str_replace("[COUPON_MONEY]", $v['price'], $v['sql']);
		$newQuery = str_replace("[COUPON_RESERVE]", $v['reserve'], $newQuery);
		pmysql_query($newQuery,get_db_conn());
		backup_save_sql($newQuery);
	}
}


# ��ٱ��� ����
if(count($coupon_code_basket) > 0 && $sumprice > 0) {
	foreach($coupon_code_basket as $coupon_code){
		if(ord($group_type)>0 && $group_type!=NULL && $sumprice>=$group_usemoney && ($group_payment=="N" || ($group_payment=="B" && strstr("BVOQ",$paymethod)) || ($group_payment=="C" && strstr("CP", $paymethod)))) {
			$dc_price="-".$tot_salemoney;

			$tot_salereserve = $reserve;
			$mem_reserve=$tot_salereserve;
			if($escrow_info["esbank"]=="Y" && $paymethod=="Q" && $group_payment=="B") $dc_price="0";
		}

		if(ord($_ShopInfo->getMemid())>0 && $_data->coupon_ok=="Y" && strlen($coupon_code)==8) {
			$date = date("YmdH");
			$sql = "SELECT a.coupon_code, a.coupon_name, a.sale_type, a.sale_money, a.bank_only, a.productcode, ";
			$sql.= "a.mini_price,a.use_con_type1,a.use_con_type2,a.use_point,a.vender, b.date_start, b.date_end, a.sale_max_money ";
			$sql.= "FROM tblcouponinfo a, tblcouponissue b ";
			$sql.= "WHERE b.id='".$_ShopInfo->getMemid()."' ";
			$sql.= "AND a.coupon_code=b.coupon_code AND b.date_start<='{$date}' ";
			$sql.= "AND (b.date_end>='{$date}' OR b.date_end='') ";
			$sql.= "AND a.coupon_code='{$coupon_code}' AND b.used='N' AND a.coupon_use_type != '2' ";
			$resultcou = pmysql_query($sql,get_db_conn());
			if($rowcou=pmysql_fetch_object($resultcou)) {
				$code_a=substr($rowcou->productcode,0,3);
				$code_b=substr($rowcou->productcode,3,3);
				$code_c=substr($rowcou->productcode,6,3);
				$code_d=substr($rowcou->productcode,9,3);

				$likecode=$code_a;
				if($code_b!="000") $likecode.=$code_b;
				if($code_c!="000") $likecode.=$code_c;
				if($code_d!="000") $likecode.=$code_d;

				if($prcode[$rowcou->vender]=="") $prcode[$rowcou->vender]="ALL";  // ��ü��ǰ
				else {
					$prleng=strlen($rowcou->productcode);

					if($prleng==18) $tempprcode=$rowcou->productcode;
					else $tempprcode=$likecode;

					$num = strlen($tempprcode);
					$prcode[$rowcou->vender] = substr($prcode[$rowcou->vender],0,$num);
				}

				$rowcou->productcode=$likecode;

				if($rowcou->bank_only=="Y" && $escrow_info["esbank"]=="Y" && $paymethod=="Q") {
					$coupon_code="";
				} else if($rowcou->bank_only=="Y" && !strstr("BVOQ", $paymethod)) {
					$coupon_code="";
				} else if(($rowcou->mini_price==0 || $rowcou->mini_price<=$arrvender[$rowcou->vender]["sumprice"])  // �ѱ��űݾ��� �����ݾ׺��� ū�� �˻�
				&& ($rowcou->use_con_type2=="Y" && ($rowcou->productcode==$prcode[$rowcou->vender] || $rowcou->productcode=="ALL" || ($rowcou->use_con_type1=="Y" && $rowcou->productcode!="ALL"))
				|| ($rowcou->use_con_type2=="N" && (($rowcou->use_con_type1=="Y" && $arrvender[$rowcou->vender]["sumprice"]-$prprice[$rowcou->vender][$rowcou->productcode]>0) || ($rowcou->use_con_type1=="N" && ord($prprice[$rowcou->vender][$rowcou->productcode])==0))))
				){
					if($rowcou->productcode=="ALL") {		#����ǰ
						$couponmoney = $arrvender[$rowcou->vender]["sumprice"];
						$couponmsg=$allprname;
					} else if($rowcou->use_con_type2=="N") {#�ش��ǰ �Ǵ� ī�װ����� ������ ������ ��ǰ
						$couponmoney = $arrvender[$rowcou->vender]["sumprice"]-$prprice[$rowcou->vender][$rowcou->productcode];
						$couponmsg=str_replace($prname[$rowcou->vender][$rowcou->productcode],"",$allprname);
					} else {								#�ش��ǰ �Ǵ� �ش� ī�װ��� ����ǰ
						$couponmoney = $prprice[$rowcou->vender][$rowcou->productcode];
						$couponmsg=$prname[$rowcou->vender][$rowcou->productcode];
					}
					$couponmsg=substr($couponmsg,0,-2);

					$tempcoumoney = floor(($rowcou->sale_type<=2?($couponmoney/100*$rowcou->sale_money):($couponmoney>0?$rowcou->sale_money:0))/pow(10,$rowcou->amount_floor))*pow(10,$rowcou->amount_floor);

					if($rowcou->sale_max_money && $rowcou->sale_max_money < $tempcoumoney){
						$tempcoumoney = $rowcou->sale_max_money;
					}

					//1�������� ���ֱ� ���ؼ� ����.
					if($rowcou->sale_type%2==0) {      // �ֹ��ݾ�����
						$coumoney = -$tempcoumoney;
						$coureserve=0;
						$sumprice = $sumprice + $coumoney;
					} else {
						$coumoney = 0;
						$coureserve= $tempcoumoney;
					}

					// �׷�������뿩��
					if($rowcou->use_point!="Y") {
						$dc_price="0";
					}

					if(ord($tempcoumoney)>0 && $tempcoumoney>0) {
						$coupon_name=titleCut(50,$rowcou->coupon_name)." - ".number_format($rowcou->sale_money).($rowcou->sale_type<=2?"%":"��").($rowcou->sale_type%2==0?"����":"����")."����";
						$coupon_name = addslashes($coupon_name);

						$sql = "INSERT INTO tblorderproducttemp (vender, ordercode, tempkey, productcode, productname, quantity, price, reserve, date, order_prmsg) VALUES ('{$rowcou->vender}','{$ordercode}','{$oldtempkey}','COU{$coupon_code}X','{$coupon_name}','1','{$coumoney}','{$coureserve}','".date("Ymd")."','{$couponmsg}')";
						pmysql_query($sql,get_db_conn());
						backup_save_sql($sql);
					}
				}
			}
			pmysql_free_result($resultcou);
		}
	}
}
//��ܿ��� �̹� �������� �� ��ǰ���� �����.
//if(ord($dc_price)>0) $sumprice= $sumprice - $salemoney;

//���űݾ״뺰 �߰�����
$tot_price_dc=0;
$tot_dc_per=getTotalPriceDc($bf_sumprice);
if($tot_dc_per)$tot_price_dc=round($sumprice*$tot_dc_per/100,-1,PHP_ROUND_HALF_DOWN);



//��ǰ���Ű���(������ ����)���� �ݾ����ΰ� ����
$sumprice = $sumprice - $tot_price_dc;

if($beforehand_reserve == 'Y'){//����������
	//$beforehand_reservePrice;
	$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('{$ordercode}','{$oldtempkey}','99999999999X','��� ���� ����','1','-{$beforehand_reservePrice}','0','".date("Ymd")."')";
	pmysql_query($sql,get_db_conn());
	backup_save_sql($sql);
	$sumprice = $sumprice - $beforehand_reservePrice;

}

if($deli_type==0 && $deli_price>0) $sumprice+=$deli_price;

if($_data->ETCTYPE["VATUSE"]=="Y") {
	if($sumpricevat>0) {
		$sumprice+=$sumpricevat;
		$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('{$ordercode}','{$oldtempkey}','99999999997X','�ΰ��� VAT 10% �ΰ�','1','{$sumpricevat}','0','".date("Ymd")."')";
		pmysql_query($sql,get_db_conn());
		backup_save_sql($sql);
	}
}
if (strstr("CPM", $paymethod) && $_data->card_payfee>0) {  // ī������� �߰� ������ ����
	$tempprice = ((int) ($sumprice * ($_data->card_payfee/100) /100)) * 100;
	$sumprice+=$tempprice;
	$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('{$ordercode}','{$oldtempkey}','99999999998X','ī������� �ݾ׿��� {$_data->card_payfee}% ������ �ΰ�','1','{$tempprice}','0','".date("Ymd")."')";
	pmysql_query($sql,get_db_conn());
	backup_save_sql($sql);
} else if (strstr("BVOQ",$paymethod) && $_data->card_payfee<0 && $sumprice>$usereserve) {
	// ���ݰ����� ������ ���� & �����ݾ׸����� ����������
	if($paymethod=="Q" && $escrow_info["esbank"]=="Y") {
		;
	} else {
		if($_data->card_payfee<-50){
			$_data->card_payfee+=50;
			$saletype="Y";
		}
		$_data->card_payfee=abs($_data->card_payfee);
		$dctemp = floor(($sumprice-$deli_price)/100*$_data->card_payfee/100)*100;
		if($saletype=="Y" && ord($_ShopInfo->getMemid())>0) {
			$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('{$ordercode}','{$oldtempkey}','99999999999X','���ݰ����� �����ݾ׿��� {$_data->card_payfee}% �߰� ����','1','0','{$dctemp}','".date("Ymd")."')";
			pmysql_query($sql,get_db_conn());
			backup_save_sql($sql);
		} else if($saletype!="Y") {
			$sumprice = $sumprice - $dctemp;
			$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('{$ordercode}','{$oldtempkey}','99999999999X','���ݰ����� �����ݾ׿��� {$_data->card_payfee}% �߰� ����','1',".-$dctemp.",'0','".date("Ymd")."')";
			pmysql_query($sql,get_db_conn());
			backup_save_sql($sql);
		}
	}
}

if($dc_price=='')$dc_price=0;
if($mem_reserve=='')$mem_reserve=0;
$last_price = $sumprice - $usereserve;

if ($paymethod=="Q" && $escrow_info["percent"]>0) {  // ����ũ�� ������ �߰� ������ ����
	$templast_price = ((int) ($last_price * ($escrow_info["percent"]/100) /10)) * 10;
	if($templast_price<300) $templast_price=300;
	$last_price+=$templast_price;
	$sql = "INSERT INTO tblorderproducttemp (ordercode, tempkey, productcode, productname, quantity, price, reserve, date) VALUES ('{$ordercode}','{$oldtempkey}','99999999998X','����ũ�� ������ �ݾ׿��� {$escrow_info['percent']}% ������ �ΰ�','1','{$templast_price}','0','".date("Ymd")."')";
	pmysql_query($sql,get_db_conn());
	backup_save_sql($sql);
}

//��ü�� ��۷� tblorderproducttemp ���̺��� insert
if(count($arr_deliprice)>0) {
	while(list($key,$val)=each($arr_deliprice)) {
		if($val>0) {
			$deli_type_tag = $deli_type=="1"?"(����)":"(����)";
			$sql = "INSERT INTO tblorderproducttemp (vender, ordercode, tempkey, productcode, productname, quantity, price, reserve, date, order_prmsg) VALUES ('{$key}','{$ordercode}','{$oldtempkey}','99999999990X','��۷�-{$deli_type_tag} ({$arr_delisubj[$key]})','1','{$val}','0','".date("Ymd")."','{$arr_delimsg[$key]}')";
			pmysql_query($sql,get_db_conn());
			backup_save_sql($sql);
		}
	}
}

$deli_price=$deli_type=="1"?0:$deli_price;

if(ord($_ShopInfo->getMemid())==0) {

	$sql = "INSERT INTO tblorderinfotemp (tot_price_dc, ordercode, tempkey, id, price, deli_price, paymethod, ";
	$sql.= "pay_data, sender_name, sender_email, sender_tel, receiver_name, receiver_tel1, receiver_tel2, ";
	$sql.= "receiver_addr, order_msg, ip, del_gbn, partner_id, loc, bank_sender, receipt_yn, order_msg2,deli_type) VALUES (";
	$sql.= "'{$tot_price_dc}', '{$ordercode}', '{$oldtempkey}', '{$id}', '{$last_price}', ";
	$sql.= "'{$deli_price}', '{$pmethod}', '{$pay_data}', '{$sender_name}', '{$sender_email}', ";
	$sql.= "'{$sender_tel}', '{$receiver_name}', '{$receiver_tel1}', '{$receiver_tel2}', ";
	$sql.= "'{$receiver_addr}', '{$order_msg}', '{$ip}', 'N', '".$_ShopInfo->getRefurl()."', '{$loc}', '{$bank_sender}', '{$receipt_yn}', '".$ordermessage[0]."', '".$deli_type."'  )";
	pmysql_query($sql,get_db_conn());
	backup_save_sql($sql);
	if (pmysql_errno()) {
		sendmail(AdminMail,"[���!] INSERT ERROR",$_SERVER['HTTP_HOST']."<br>$sql - ".pmysql_error(),"Content-Type: text/plain\r\n");
	}
} else {
	if($sumprice<=$usereserve) {
		$remain_reserve = $user_reserve - $sumprice;
		$usereserve = $sumprice;
	} else {
		$remain_reserve=$user_reserve-$usereserve;
	}
	if ($last_price<0) $last_price=0;

	$sql = "INSERT INTO tblorderinfotemp (tot_price_dc, ordercode, tempkey, id, price, deli_price, dc_price, mem_reserve, ";
	$sql.= "reserve, paymethod, pay_data, ";
	if($last_price==0) {
		$pay_data="�� ���űݾ� ".number_format($usereserve)."���� ���������� ����";
		$sql.= "bank_date, ";
		if(strstr("OQ", $paymethod)) $sql.= "pay_flag, ";	//������¸�,,,
	}
	$sql.= "sender_name, sender_email, sender_tel, receiver_name, receiver_tel1, receiver_tel2, ";
	$sql.= "receiver_addr, order_msg, ip, del_gbn, partner_id, loc, bank_sender, receipt_yn, order_msg2,deli_type) VALUES ( ";
	$sql.= "'{$tot_price_dc}', '{$ordercode}', '{$oldtempkey}', '".$_ShopInfo->getMemid()."', ";
	$sql.= "'{$last_price}', '{$deli_price}', '{$dc_price}', '{$mem_reserve}', '{$usereserve}', '{$pmethod}', ";
	$sql.= "'{$pay_data}', ";
	if($last_price==0) {
		$sql.= "'".date("YmdHis")."', ";
		if(strstr("OQ", $paymethod)) $sql.= "'0000', ";	//������¸�,,,
	}
	$sql.= "'{$sender_name}', '{$sender_email}', ";
	$sql.= "'{$sender_tel}', '{$receiver_name}', '{$receiver_tel1}', '{$receiver_tel2}', ";
	$sql.= "'{$receiver_addr}', '{$order_msg}', '{$ip}', 'N', '".$_ShopInfo->getRefurl()."', '{$loc}', '{$bank_sender}', '{$receipt_yn}', '".$ordermessage[0]."', '".$deli_type."' )";
	pmysql_query($sql,get_db_conn());
	backup_save_sql($sql);
	if (pmysql_errno()) {
		sendmail(AdminMail,"[���!] INSERT ERROR",$_SERVER['HTTP_HOST']."<br>$sql - ".pmysql_error(),"Content-Type: text/plain\r\n");
	}
}

// ���� �������� �ٽ� �־� �ְų� ���ش�.
/*	2014-02-21 ����ó���κп��� ����
	if(ord($_ShopInfo->getMemid())>0 && $reserve_type=="Y" && $_data->reserve_maxuse>=0) {
		$sql = "UPDATE tblmember SET reserve=$remain_reserve";
		if($usereserve>=3000){
			$sql.=" , reserve_chk='1' ";
		}
		$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
		pmysql_query($sql,get_db_conn());
	}
*/

//����ǰ
if($gift_sel){
	$productcode_gift=sprintf("%'98d",0)."GIFT";
	$gift_sql = "SELECT * FROM tblgiftinfo WHERE gift_regdate='".$gift_sel."'";

	$gift_res=pmysql_query($gift_sql,get_db_conn());
	if($gift_row=pmysql_fetch_object($gift_res)) {

		$sql_ins = "INSERT INTO tblorderproduct (
		ordercode	,
		tempkey		,
		productcode	,
		productname	,
		quantity	,
		price		,
		date		) VALUES (
		'{$ordercode}',
		'".$_ShopInfo->getGifttempkey()."',
		'".$productcode_gift."',
		'����ǰ - ".addslashes($gift_row->gift_name)."',
		'1',
		'0',
		'".date("Ymd")."')";

		pmysql_query($sql_ins,get_db_conn());

		if (ord($gift_row->gift_quantity) && $gift_row->gift_quantity>0) {
			pmysql_query("UPDATE tblgiftinfo SET gift_quantity=(gift_quantity-1) WHERE gift_regdate='".$gift_sel."'",get_db_conn());
		}

	}
	pmysql_free_result($gift_res);

}

if($paymethod!="B") {
	########### �����ý��� ���� ���� ##########
	//include("paylist_kcp.php");
	//exit;

	$mobile_agent = '/(iPod|iPhone|iPad|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
	if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
		echo "<script>
			if(parent.document.getElementsByName('good_mny')[0].value == '".$last_price."'){
				parent.kcp_AJAX();
			}else{
				alert('�����ݾ��� �ùٸ��� �ʽ��ϴ�.');
				parent.location.replace('basket.php');
			}
			</script>";
	} else {
		echo "<script>
				alert('����� ��� ������ �ƴմϴ�.');
				parent.location.replace('basket.php');
			</script>";
	}


	########### �����ý��� ���� ��   ##########
	exit;
}

########### ���� ������ ###########
include("payresult.php");
########### ���� ������ �� ########
