<?
include_once dirname(__FILE__)."/../../lib/product.class.php";
$product = new PRODUCT();
$dc_data = $product->getProductDcRate($productcode);
?>

<link rel="stylesheet" type="text/css" href="../../css/tem_001.css" media="all" />

<!-- start container -->
<div id="container">
	<!-- start contents -->
	<div class="contents">
	
	<div class="title_noline">
		<div class="path">
			<ul>
				
				<?=$codenavi?>
				<!--
				<li class="home">홈&nbsp;&gt;&nbsp;</li>
				<li>화장품원료&nbsp;&gt;&nbsp;</li>
				<li>기능성원료&nbsp;&gt;&nbsp;</li>
				<li>보습</li>
				-->
			</ul>
		</div>
	</div>


<form name=form1 method=post action="<?=$Dir.FrontDir?>basket.php">
<div class="detail_header_warp">

				
                <div class="product_photo">
				<?if($multi_img=="Y" && $yesimage[0]) {?>
				 <div class="product_photo_mimg"> <img src="<?=$Dir?>images/common/trans.gif" alt="제품메인사진" name=primg width=400 height=400></div>
                    <ul>
					<?
					for($i=0;$i<$y;$i++) {
						if($changetype=="0") {	//마우스 오버
							$ahref_type =  "<a href=\"javascript:primg_preview('{$yesimage[$i]}','{$xsize[$i]}','{$ysize[$i]}')\" onmouseover=\"primg_preview2('{$yesimage[$i]}','{$xsize[$i]}','{$ysize[$i]}')\">";
						}else{
							$ahref_type = "<a href=\"javascript:primg_preview('{$yesimage[$i]}','{$xsize[$i]}','{$ysize[$i]}')\">";
						}
				?>		
						<li><?=$ahref_type?><img src="<?=$imagepath?>s<?=$yesimage[$i]?>" alt="" style="width:45px;height:45px"/></a></li>
				<?	
					}
				}else{
								
					if(strlen($_pdata->maximage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->maximage)) {
						$imgsize=GetImageSize($Dir.DataDir."shopimages/product/".$_pdata->maximage);
						if(($imgsize[1]>550 || $imgsize[0]>750) && $multi_img!="I") $imagetype=1;
						else $imagetype=0;
					}
					if(strlen($_pdata->minimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->minimage)) {
						$width=GetImageSize($Dir.DataDir."shopimages/product/".$_pdata->minimage);
						if($width[0]>=300) $width[0]=400;
						else if (strlen($width[0])==0) $width[0]=400;
						echo "<div class=\"product_photo_mimg\">";
						echo "<a href=\"javascript:primage_view('".$_pdata->maximage."','".$imagetype."')\">";
						echo "<img src=\"".$Dir.DataDir."shopimages/product/".$_pdata->minimage."\" border=\"0\" width=\"".$width[0]."\"></a>";		
						echo "</div>";
					} else {
						echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\"></td>\n";
					}

				}?>
						
                    </ul>
                </div><!-- end product_photo -->

                <div class="product_info">

		        <p class="product_name"><?=$_pdata->productname?></p>

					<?
				
					$reserveconv=getReserveConversion($_pdata->reserve,$_pdata->reservetype,$_pdata->sellprice,"Y");
					$SellpriceValue=0;
					if(strlen($dicker=dickerview($_pdata->etctype,number_format($_pdata->sellprice),1))>0){
					?>
						<dl class="product_info_price">
						<dt>판매가격 </dt><dd>:</dd>
						<dd><font id="idx_consumer"><?if($_pdata->consumerprice){ echo number_format($_pdata->consumerprice)." → ";}?></font><font class="sale_price" id="idx_price"><?=$dicker?></font></dd>
						
						<?if($_ShopInfo->memid){?>
						<div style="display:block;">
						<?}else{?>
						<div style="display:none;">
						<?}?>
						<dt>에코머니 </dt><dd>:</dd>
						<dd><font id="idx_reserve"><?=number_format($reserveconv)?>원</font></dd>
						</div>
						</dl>
					<?						
						$prdollarprice="";
						$priceindex=0;
					} else if(strlen($optcode)==0 && strlen($_pdata->option_price)>0) {
						$option_price = $_pdata->option_price;
						$option_consumer = $_pdata->option_consumer;
						$option_reserve = $_pdata->option_reserve;
						$pricetok=explode(",",$option_price);
						$consumertok=explode(",",$option_consumer);
						$reservetok=explode(",",$option_reserve);
						$priceindex = count($pricetok);
						for($tmp=0;$tmp<=$priceindex;$tmp++) {
							$pricetokdo[$tmp]=number_format($pricetok[$tmp]/$ardollar[1],2);
							$spricetok[$tmp]=number_format($pricetok[$tmp]);
							$pricetok[$tmp]=number_format(getProductSalePrice($pricetok[$tmp], $dc_data[price]));
							$consumertok[$tmp]=number_format($consumertok[$tmp]);
							$reservetok[$tmp]=number_format($reservetok[$tmp]);
						}	
					?>
						<dl class="product_info_price">
						<?if($_pdata->consumerprice){?>
						<dt>시중가격 </dt><dd>:</dd><dd><font id="idx_consumer"><?=number_format($_pdata->consumerprice)?>원</font></dd>
						<?}?>
						<dt>판매가격 </dt><dd>:</dd><dd><font id="idx_sprice"><?=number_format($_pdata->sellprice)?>원</font></dd>

						<?if($_ShopInfo->memid){?>
						<dt>회원가격 </dt><dd>:</dd><dd><font id=""><font class="sale_price" id="idx_price"><?=number_format(getProductSalePrice($_pdata->sellprice,$dc_data[price]))?>원</font></font></dd>
						<?}?>
						<input type=hidden name=price value="<?=number_format($_pdata->sellprice)?>">
						<input type=hidden name=sprice value="<?=number_format($_pdata->sellprice)?>">
						<input type=hidden name=consumer value="<?=number_format($_pdata->consumerprice)?>">
						<input type=hidden name=o_reserve value="<?=number_format($_pdata->option_reserve)?>">
						<?if($dollarok){?>
						<dt>해외화폐 </dt><dd>:</dd>
						<dd><FONT id="idx_dollarprice"><?=$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]?></font></dd>
						<input type=hidden name=dollarprice value="<?=number_format($_pdata->sellprice/$ardollar[1],2)?>">
						<?}?>
						<?if($_ShopInfo->memid){?>
						<div style="display:block;">
						<?}else{?>
						<div style="display:none;">
						<?}?>
						<dt>에코머니 </dt><dd>:</dd>
						<dd><font id="idx_reserve"><?=number_format($reserveconv)?>원</font></dd>
						</div>
						</dl>
					
					<?
						$SellpriceValue=str_replace(",","",$pricetok[0]);
					} else if(strlen($optcode)>0) {
						
					?>
						<dl class="product_info_price">
						<dt>판매가격 </dt><dd>:</dd>
						<dd><font id="idx_consumer"><?if($_pdata->consumerprice){ echo number_format($_pdata->consumerprice)." → ";}?></font><font class="sale_price" id="idx_price"><?=number_format($_pdata->sellprice)?>원</font></dd>
						<input type=hidden name=price value="<?=number_format($_pdata->sellprice)?>">
						<input type=hidden name=sprice value="<?=number_format($_pdata->sellprice)?>">
						<input type=hidden name=consumer value="<?=number_format($_pdata->consumerprice)?>">
						<input type=hidden name=o_reserve value="<?=number_format($_pdata->option_reserve)?>">
						<?if($dollarok){?>
						<dt>해외화폐 </dt><dd>:</dd>
						<dd><FONT id="idx_dollarprice"><?=$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]?></font></dd>
						<input type=hidden name=dollarprice value="<?=number_format($_pdata->sellprice/$ardollar[1],2)?>">
						<?}?>
						
						<?if($_ShopInfo->memid){?>
						<div style="display:block;">
						<?}else{?>
						<div style="display:none;">
						<?}?>
						<dt>에코머니 </dt><dd>:</dd>
						<dd><font id="idx_reserve"><?=number_format($reserveconv)?>원</font></dd>
						</div>
						</dl>
					<?
						$SellpriceValue=$_pdata->sellprice;
					} else if(strlen($_pdata->option_price)==0) {
						if($_pdata->assembleuse=="Y") {
					?>
						<dl class="product_info_price">
						<dt>판매가격 </dt><dd>:</dd>
						<dd><font id="idx_consumer"><?if($_pdata->consumerprice){ echo number_format($_pdata->consumerprice)." → ";}?></font><font class="sale_price" id="idx_price"><?=number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice))?>원</font></dd>
						<input type=hidden name=price value="<?=number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice))?>">
						<input type=hidden name=sprice value="<?=number_format($_pdata->sellprice)?>">
						<input type=hidden name=consumer value="<?=number_format(($miniq>1?$miniq*$_pdata->consumerprice:$_pdata->consumerprice))?>">
						<input type=hidden name=o_reserve value="<?=number_format(($miniq>1?$miniq*$_pdata->option_reserve:$_pdata->option_reserve))?>">
						<?if($dollarok){?>
						<dt>해외화폐 </dt><dd>:</dd>
						<dd><FONT id="idx_dollarprice"><?=$ardollar[0]." ".number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice)/$ardollar[1],2)." ".$ardollar[2]?></font></dd>
						<input type=hidden name=dollarprice value="<?=number_format(($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice)/$ardollar[1],2)?>">
						<?}?>
						
						<?if($_ShopInfo->memid){?>
						<div style="display:block;">
						<?}else{?>
						<div style="display:none;">
						<?}?>
						<dt>에코머니 </dt><dd>:</dd>
						<dd><font id="idx_reserve"><?=number_format($reserveconv)?>원</font></dd>
						</div>
						</dl>
					<?
							
							$SellpriceValue=($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice);
						} else {
					?>
						<dl class="product_info_price">
						<?if($_pdata->consumerprice){?>
						<dt>시중가격 </dt><dd>:</dd><dd><font id="idx_consumer"><?=number_format($_pdata->consumerprice)?>원</font></dd>
						<?}?>
						<dt>판매가격 </dt><dd>:</dd><dd><font id="idx_sprice"><?=number_format($_pdata->sellprice)?>원</font></dd>

						<?if($_ShopInfo->memid){?>
						<dt>회원가격 </dt><dd>:</dd><dd><font id=""><font class="sale_price" id="idx_price"><?=number_format(getProductSalePrice($_pdata->sellprice,$dc_data[price]))?>원</font></font></dd>
						<?}?>
						<input type=hidden name=price value="<?number_format($_pdata->sellprice)?>">
						<input type=hidden name=sprice value="<?=number_format($_pdata->sellprice)?>">
						<input type=hidden name=consumer value="<?=number_format($_pdata->consumerprice)?>">
						<input type=hidden name=o_reserve value="<?=number_format($_pdata->option_reserve)?>">
						<?if($dollarok){?>
						<dt>해외화폐 </dt><dd>:</dd>
						<dd><FONT id="idx_dollarprice"><?=$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]?></font></dd>
						<input type=hidden name=dollarprice value="<?=$ardollar[0]." ".number_format($_pdata->sellprice/$ardollar[1],2)." ".$ardollar[2]?>">
						<?}?>
						
						<?if($_ShopInfo->memid){?>
						<div style="display:block;">
						<?}else{?>
						<div style="display:none;">
						<?}?>
						<dt>에코머니 </dt><dd>:</dd>
						<dd><font id="idx_reserve"><?=number_format($reserveconv)?>원</font></dd>
						</div>
						</dl>
					<?
							$SellpriceValue=$_pdata->sellprice;
						}
						$priceindex=0;
					}
					?>
                  
                   <dl class="product_info_spec">
					<?if($_pdata->madein){?>
					<dt>원산지 </dt><dd>:</dd>
					<dd><?=$_pdata->madein?></dd>
					<?}?>
					<?if($_pdata->production){?>
					<dt>제조사 </dt><dd>:</dd>
					<dd><?=$_pdata->production?></dd>
					<?}?>
					<?if($_pdata->opendate){?>
					<dt>출시일 </dt><dd>:</dd>
					<dd><?=@substr($_pdata->opendate,0,4).(@substr($_pdata->opendate,4,2)?"-".@substr($_pdata->opendate,4,2):"").(@substr($_pdata->opendate,6,2)?"-".@substr($_pdata->opendate,6,2):"")?></dd>
				  <?}?>
			
				   <dt>구매수량</dt><dd>:</dd>
				   <dd><input type=text name="quantity" value="<?=($miniq>1?$miniq:"1")?>" <?if($_pdata->assembleuse=="Y"){echo " readonly";}else{ echo "onkeyup='strnumkeyup(this)'";}?> class="amount">
				  </dd>
				   <dd><a href="javascript:change_quantity('up')"><img src="<?=$Dir?>image/detail/amount_plus.gif" alt="수량"></a><a href="javascript:change_quantity('dn')"><img src="<?=$Dir?>image/detail/amount_minus.gif" alt="수량"></a></dd>

				   <!--
				   <dt>고객선호도</dt><dd>:</dd>
				   <dd>★★★★★</dd>
				   -->
				   <dt>제품상태</dt><dd>:</dd>
				   <?=viewicon($_pdata->etctype)?>

				   </dl>
					<?
				
					
					if(strlen($_pdata->option1)>0) {
						$temp = $_pdata->option1;
						$tok = explode(",",$temp);
						$optprice = explode(",",$_pdata->option_price);
						$optcode = explode(",",$_pdata->optcode);

						$count=count($tok);
						
						if ($priceindex!=0) {
							$onchange_opt1="onchange=\"change_price(1,document.form1.option1.selectedIndex-1,";
							if(strlen($_pdata->option2)>0) $onchange_opt1.="document.form1.option2.selectedIndex-1";
							else $onchange_opt1.="''";
							$onchange_opt1.=")";
							$onchange_opt1.="\"";
						} else {
							$onchange_opt1="onchange=\"change_price(0,document.form1.option1.selectedIndex-1,";
							if(strlen($_pdata->option2)>0) $onchange_opt1.="document.form1.option2.selectedIndex-1";
							else $onchange_opt1.="''";
							$onchange_opt1.="\"";
						}
						$optioncnt = explode(",",ltrim($_pdata->option_quantity,','));
					?>
						<dl class="product_info_option">
						<dt><?=$tok[0]?></dt><dd>:</dd>
						<dd>
					
						<select id="option1" <?=$onchange_opt1?> name="option1">
							<option value="">=옵션선택=</option>
							<option value="" >-------------</option>
					<?
							for($i=1;$i<$count;$i++) {
								if(strlen($tok[$i])>0) {
									
					?>			<option value="<?=$i?>"><?=$tok[$i]." (".number_format($optprice[$i-1])."원) (".$optcode[$i-1].")"?>
					
					<?			}
								if(strlen($_pdata->option2)==0 && $optioncnt[$i-1]=="0"){
									echo " (품절) ";
								}
					?>
								</option>
					<?		}		?>
								</select>
					   </dd>
					   </dl>
					<?}?>
					
					<?
					$onchange_opt2="";
				if(strlen($_pdata->option2)>0) {
					$temp = $_pdata->option2;
					$tok = explode(",",$temp);
					$count2=count($tok);
					$onchange_opt2.="onchange=\"change_price(0,";
					if(strlen($_pdata->option1)>0) $onchange_opt2.="document.form1.option1.selectedIndex-1";
					else $onchange_opt2.="''";
					$onchange_opt2.=",document.form1.option2.selectedIndex-1)\"";
					
				?>
						<dl class="product_info_option">
						<dt><?=$tok[0]?></dt><dd>:</dd>
						<dd>
					
						<select id="option2" <?=$onchange_opt2?> name="option2">
							<option value="">=옵션선택=</option>
							<option value="" >-------------</option>
					<?
							for($i=1;$i<$count2;$i++) {
								if(strlen($tok[$i])>0) {
									
					?>			<option value="<?=$i?>"><?=$tok[$i]?>
					
					<?			}
								if(strlen($_pdata->option2)==0 && $optioncnt[$i-1]=="0"){
									echo " (품절) ";
								}
					?>
								</option>
					<?		}		?>
								</select>
					   </dd>
					   </dl>
					<?}?>
					<?
					
					if(strlen($optcode)>0) {
						$sql = "SELECT * FROM tblproductoption WHERE option_code='".$optcode."' ";
						$result = pmysql_query($sql,get_db_conn());
						if($row = pmysql_fetch_object($result)) {
							$optionadd = array (&$row->option_value01,&$row->option_value02,&$row->option_value03,&$row->option_value04,&$row->option_value05,&$row->option_value06,&$row->option_value07,&$row->option_value08,&$row->option_value09,&$row->option_value10);
							$opti=0;
							$option_choice = $row->option_choice;
							$exoption_choice = explode("",$option_choice);
					?>
								<dl class="product_info_option">
								<dt>상품옵션</dt><dd>:</dd>
								<dd>
					<?
							while(strlen($optionadd[$opti])>0) {
								$opval = str_replace('"','',explode("",$optionadd[$opti]));
								$opcnt=count($opval);
					?>	
								
								
								<select id="mulopt" onchange="chopprice('<?=$opti?>)" name="mulopt">
									<option value="">=<?=$opval[0].($exoption_choice[$opti]==1?"(필수)":"(선택)")?>=</option>
									
					<?
								for($j=1;$j<$opcnt;$j++) {
								$onchange_opt3="";
									$exop = str_replace('"','',explode(",",$opval[$j]));
									if($exop[1]>0) $onchange_opt3.=$exop[0]."(+".$exop[1]."원)";
									else if($exop[1]==0) $onchange_opt3.=$exop[0];
									else $onchange_opt3.=$exop[0]."(".$exop[1]."원)";
									
					?>				<option value="<?=$opval[$j]?>"><?=$onchange_opt3?>
									
					<?			}		?>
								</select><input type=hidden name="opttype" value="0"><input type=hidden name="optselect" value="<?=$exoption_choice[$opti]?>"><br>
								
									
					<?			$opti++;
							}?>
							<input type=hidden name="mulopt"><input type=hidden name="opttype"><input type=hidden name="optselect">
							</dd>
						</dl>
					<?	}
						pmysql_free_result($result);
					}
					if(strlen($onchange_opt1)==0 && strlen($onchange_opt1)==0 && strlen($onchange_opt3)==0) {
					?>
						<input type=hidden name="option1">
						<input type=hidden name="option2">
					<?}?>
					
					<?
					
						// 패키지 선택 출력
					$arrpackage_title=array();
					$arrpackage_list=array();
					$arrpackage_price=array();
					$arrpackage_pricevalue=array();
					if((int)$_pdata->package_num>0) {
						$sql = "SELECT * FROM tblproductpackage WHERE num='".(int)$_pdata->package_num."' ";
						$result = pmysql_query($sql,get_db_conn());
						$package_count=0;
						if($row = @pmysql_fetch_object($result)) {
							pmysql_free_result($result);
							if(strlen($row->package_title)>0) {
								$arrpackage_title = explode("",$row->package_title);
								$arrpackage_list = explode("",$row->package_list);
								$arrpackage_price = explode("",$row->package_price);

								$package_listrep = str_replace("","",$row->package_list);

								if(strlen($package_listrep)>0) {
									$sql = "SELECT pridx,productcode,productname,sellprice,tinyimage,quantity,etctype FROM tblproduct ";
									$sql.= "WHERE pridx IN ('".str_replace(",","','",trim($package_listrep,','))."') ";
									$sql.= "AND assembleuse!='Y' ";
									$sql.= "AND display='Y' ";
									$result2 = pmysql_query($sql,get_db_conn());
									while($row2 = @pmysql_fetch_object($result2)) {
										$arrpackage_proinfo[productcode][$row2->pridx] = $row2->productcode;
										$arrpackage_proinfo[productname][$row2->pridx] = $row2->productname;
										$arrpackage_proinfo[sellprice][$row2->pridx] = $row2->sellprice;
										$arrpackage_proinfo[tinyimage][$row2->pridx] = $row2->tinyimage;
										$arrpackage_proinfo[quantity][$row2->pridx] = $row2->quantity;
										$arrpackage_proinfo[etctype][$row2->pridx] = $row2->etctype;
									}
									@pmysql_free_result($result2);
								}

								for($t=1; $t<count($arrpackage_list); $t++) {
									$arrpackage_pricevalue[0]=0;
									$arrpackage_pricevalue[$t]=0;
									if(strlen($arrpackage_list[$t])>0) {
										$arrpackage_list_exp = explode(",",$arrpackage_list[$t]);
										$sumsellprice=0;
										for($tt=0; $tt<count($arrpackage_list_exp); $tt++) {
											$sumsellprice += (int)$arrpackage_proinfo[sellprice][$arrpackage_list_exp[$tt]];
										}

										if((int)$sumsellprice>0) {
											$arrpackage_pricevalue[$t]=(int)$sumsellprice;
											if(strlen($arrpackage_price[$t])>0) {
												$arrpackage_price_exp = explode(",",$arrpackage_price[$t]);
												if(strlen($arrpackage_price_exp[0])>0 && $arrpackage_price_exp[0]>0) {
													$sumsellpricecal=0;
													if($arrpackage_price_exp[1]=="Y") {
														$sumsellpricecal = ((int)$sumsellprice*$arrpackage_price_exp[0])/100;
													} else {
														$sumsellpricecal = $arrpackage_price_exp[0];
													}
													if($sumsellpricecal>0) {
														if($arrpackage_price_exp[2]=="Y") {
															$sumsellpricecal = $sumsellprice-$sumsellpricecal;
														} else {
															$sumsellpricecal = $sumsellprice+$sumsellpricecal;
														}
														if($sumsellpricecal>0) {
															if($arrpackage_price_exp[4]=="F") {
																$sumsellpricecal = floor($sumsellpricecal/($arrpackage_price_exp[3]*10))*($arrpackage_price_exp[3]*10);
															} else if($arrpackage_price_exp[4]=="R") {
																$sumsellpricecal = round($sumsellpricecal/($arrpackage_price_exp[3]*10))*($arrpackage_price_exp[3]*10);
															} else {
																$sumsellpricecal = ceil($sumsellpricecal/($arrpackage_price_exp[3]*10))*($arrpackage_price_exp[3]*10);
															}
															$arrpackage_pricevalue[$t]=$sumsellpricecal;
														}
													}
												}
											}
										}
									}
									$propackage_option.= "<option value=\"".$t."\" >".$arrpackage_title[$t]."</option>\n";
									$package_count++;
								}
							}
						}

						if($package_count>0) {
					?>
							<dl class="product_info_option">
							<dt>패키지선택</dt><dd>:</dd>
							<dd>
						
							<select onchange="packagecal()" name="package_idx">
								<option value="">=패키지를 선택하세요=</option>
								<option value="" >-------------------------</option>
								<?=$propackage_option?>
							</select>
						   </dd>
						   </dl>
						   <input type=hidden name="package_type" value="<?=$row->package_type?>">
					<?					
						}
					}
					?>
				
			         <ul class="buybtn">
						<?
						if($_pdata->assembleuse!="Y"){
						if(strlen($dicker)==0) {
							$temp = $_pdata->option1;
							$tok = explode(",",$temp);
							$goods_count=count($tok);
							
							$check_optea='0';
							if($goods_count>"1"){
								$check_optea="1";	
							}
							
							$optioncnt = explode(",",ltrim($_pdata->option_quantity,','));
							$check_optout=array();
							$check_optin=array();
							for($gi=1;$gi<$goods_count;$gi++) {
								
								if(strlen($_pdata->option2)==0 && $optioncnt[$gi-1]=="0"){ $check_optout[]='1';}
								else{  $check_optin[]='1';}
							}
							
							
							if(strlen($_pdata->quantity)>0 && ($_pdata->quantity<="0" || (count($check_optin)=='0' && $check_optea))){
						?>
								<li><FONT style="color:#F02800;"><b>품 절</b></FONT></li>
						<?
							}else {
						?>
								<li><a href="javascript:CheckForm('ordernow','<?=$opti?>');"><img alt="바로구매" src="<?=$Dir?>image/detail/btn_buy.gif"></a></li>
								<li><a href="javascript:CheckForm('','<?=$opti?>');"><img alt="장바구니" src="<?=$Dir?>image/detail/basket_btn.gif"></a></li>
						<?	
							}
					
							if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
						?>
								<li><a href="javascript:CheckForm('wishlist','<?=$opti?>')"><img alt="위시리스트" src="<?=$Dir?>image/detail/btn_wish_list.gif"></a></li>
						<?
							} else {
						?>
								<li><a href="javascript:check_login();"><img alt="위시리스트" src="<?=$Dir?>image/detail/btn_wish_list.gif"></a></li>
						<?
							}
						}
						}
						?>
					
					</ul>
					<input type=hidden name=code value="<?=$code?>">
					<input type=hidden name=productcode value="<?=$productcode?>">
					<input type=hidden name=ordertype>
					<input type=hidden name=opts>
					<?=($brandcode>0?"<input type=hidden name=brandcode value=\"".$brandcode."\">\n":"")?>


                </div>



</div><!-- end detail_header_warp -->

</form>

<?php
if($package_count>0) { //패키지 상품 출력
?>
<!-- 패키지 상품 출력 시작 //-->
						
<div class="detail_header_warp">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td bgcolor="#FFFFFF">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<col width="130"></col>
			<col width=""></col>
			<?php
			$packagecoll=5;
			for($j=1; $j<count($arrpackage_title); $j++) {
			$arrpackage_list_exp = explode(",", $arrpackage_list[$j]);
			?>
			<tr>
				<td align="center" bgcolor="#F8F8F8" style="padding:5px;border-right:1px #EDEDED solid;border-bottom:1px #EDEDED solid;">

				<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center"><b><?=$arrpackage_title[$j]?></b></td>
				</tr>
				<tr>
					<td align="center" style="padding:3px;"><?=(strlen($dicker)>0?$dicker:"<b><FONT color=\"#F02800\" id=\"idx_price".$j."\">".number_format($SellpriceValue+$arrpackage_pricevalue[$j])."원</font></b>")?></td>
				</tr>
				</table>

				</td>
				<td style="border-bottom:1px #EDEDED solid;">

				<table border="0" cellpadding="0" cellspacing="0" width=100%>
				<tr>
					<td width=100% style="padding:5">

					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="<?=ceil(100/$packagecoll)?>%" valign="top" align="center" style="padding:5px;">

						<table border="0" cellpadding="0" cellspacing="0" width="110">
						<tr>
							<td align="center" valign=middle style="border:1px #EAEAEA solid;padding:10px;" bgcolor="#EDEDED">
							<?
								if (strlen($_pdata->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_pdata->tinyimage)) {
									echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($_pdata->tinyimage)."\" border=\"0\" ";
									$width = getimagesize($Dir.DataDir."shopimages/product/".$_pdata->tinyimage);
									if($width[0]>$width[1]) echo "width=\"100\"> ";
									else echo "height=\"100\">";
								} else {
									echo "<img src=\"".$Dir."images/no_img.gif\" width=\"100\" border=\"0\">";
								}
							?>
						</td>
						</tr>
						<tr>
							<td height="3"></td>
						</tr>
						<tr>
							<td align="center" style="word-break:break-all;padding:10px;padding-top:0px;color:#BEBEBE;"><b>기본상품</b></td>
						</tr>
						</table>

						</td>
						<?
						for($ttt=1; $ttt<count($arrpackage_list_exp); $ttt++) {
						if(strlen($arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]])>0) {
						?>
						<?=($ttt%$packagecoll==0?"</tr><tr>":"")?>
						<td width="<?=ceil(100/$packagecoll)?>%" valign="top" align="center" style="padding:5px;">

						<table border="0" cellpadding="0" cellspacing="0" width="110">
						<tr>
							<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0" id="P<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>" onmouseover="quickfun_show(this,'P<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>','')" onmouseout="quickfun_show(this,'P<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>','none')">
							<tr>
								<td align="center" valign=middle style="border:1px #EAEAEA solid;padding:10px;" bgcolor="#EDEDED"><A HREF="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>" onmouseover="window.status='상품상세조회';return true;" onmouseout="window.status='';return true;">

							<?
							if (strlen($arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]])>0 && file_exists($Dir.DataDir."shopimages/product/".$arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]])) {
								echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]])."\" border=\"0\" ";
								$width = getimagesize($Dir.DataDir."shopimages/product/".$arrpackage_proinfo[tinyimage][$arrpackage_list_exp[$ttt]]);
								if($width[0]>$width[1]) echo "width=\"100\"> ";
								else echo "height=\"100\">";
							} else {
								echo "<img src=\"".$Dir."images/no_img.gif\" width=\"100\" border=\"0\" align=\"center\">";
							}
							?>
							</A></td>
							</tr>
							<tr>
								<td height="3" style="position:relative;"><?=($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','P','".$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]."','".($arrpackage_proinfo[quantity][$arrpackage_list_exp[$ttt]]=="0"?"":"1")."')</script>":"")?></td>
							</tr>
							<tr>
								<td align="center" style="word-break:break-all;padding:10px;padding-top:0px;"><A HREF="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$arrpackage_proinfo[productcode][$arrpackage_list_exp[$ttt]]?>" onmouseover="window.status='상품상세조회';return true;" onmouseout="window.status='';return true;"><FONT class="prname"><?=viewproductname($arrpackage_proinfo[productname][$arrpackage_list_exp[$ttt]],$arrpackage_proinfo[etctype][$arrpackage_list_exp[$ttt]],"")?></FONT></A></td>
							</tr>
							</table>

							</td>
						</tr>
						</table>

						</td>
					<?
						}
					}

					if($ttt<$packagecoll) {
						$empty_count = $packagecoll-$ttt;
						for($ttt=0; $ttt<$empty_count; $ttt++) {
					?>
						<td width="<?=ceil(100/$packagecoll)?>%"></td>
					<?
						}
					}
					?>
					</tr>
					</table>

					</td>
				</tr>
				</table>

				</td>
			</tr>

			<?
					}
			?>
		</table>
		</td>
		</tr>
		</table>
</div>
<!-- 패키지 상품 출력 끝 //-->
<?
} //패키지 상품 출력 끝
?>



<?	//코디 상품 출력

	if($_pdata->assembleuse=="Y" && count($_adata)>0) {
?>
<div class="detail_header_warp">
	<div style="padding:15px; background-color:#f8f8f8">

			<table cellpadding="0" cellspacing="0" width="100%" height="100">
			<tr>
				<td height="100%" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t08.gif"></td>
				<td width="100%" bgcolor="#F8F8F8" valign="top">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
<?
		$assemble_type_exp = explode("",$_adata->assemble_type);
		$assemble_title_exp = explode("",$_adata->assemble_title);
		$assemble_pridx_exp = explode("",$_adata->assemble_pridx);
		$assemble_list_exp = explode("",$_adata->assemble_list);
		
		if(count($assemble_type_exp)>0) {
?>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<input type=text name=assemble_type value="<?=implode("|",$assemble_type_exp)?>">
					<input type=hidden name=assemble_list value="">
					<input type=hidden name=assembleuse value="Y">
					<col width="60"></col>
					<col width=""></col>
<?

			
			for($j=1; $j<count($assemble_type_exp); $j++) {
				$assemble_list_pexp = explode(",",$assemble_list_exp[$j]);

?>
					<tr>
						<td valign="bottom" style="padding:5px;"><?
					if(strlen($assemble_pridx_exp[$j])>0 && (strlen($_acdata[$assemble_pridx_exp[$j]]->quantity)=='' || $_acdata[$assemble_pridx_exp[$j]]->quantity>=$miniq)) {
						if(strlen($_acdata[$assemble_pridx_exp[$j]]->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$_acdata[$assemble_pridx_exp[$j]]->tinyimage)) {
							echo "<a href=\"javascript:assemble_proinfo('".$j."');\"><img src=\"".$Dir.DataDir."shopimages/product/".$_acdata[$assemble_pridx_exp[$j]]->tinyimage."\" border=\"0\" id=\"acimage".$j."\" width=\"50\" height=\"40\"></a>";
						} else {
							echo "<a href=\"javascript:assemble_proinfo('".$j."');\"><img src=\"".$Dir."images/acimage.gif\" border=\"0\" id=\"acimage".$j."\" width=\"50\" height=\"40\"></a>";
						}
						$assemble_state = "M";
					} else {
						echo "<a href=\"javascript:assemble_proinfo('".$j."');\"><img src=\"".$Dir."images/acimage.gif\" border=\"0\" id=\"acimage".$j."\" width=\"50\" height=\"40\"></a>";
						$assemble_state = "A";
					}
						?></td>
						<td valign="bottom" style="padding:5px;">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td colspan="2"><span style="font-size:12px;"><b><?=$assemble_title_exp[$j]?></b></font></td>
						</tr>
						<tr>
							<td width="100%"><select name="acassembleselect[]" id="acassemble<?=$j?>" onchange="setAssenbleChange(this,'<?=$j?>');" onclick="setCurrentSelect(this.selectedIndex);" style="font-size:12px;letter-spacing:-0.5pt;width:100%;">
							<option value=""><?=($assemble_type_exp[$j]=="Y"?"&nbsp;&nbsp;&nbsp;━━━━━━━━━━━━━━━━&nbsp;[필수항목] 선택해 주세요&nbsp;━━━━━━━━━━━━━━━━━&nbsp;&nbsp;":"&nbsp;&nbsp;&nbsp;━━━━━━━━━━━━━━━━━━━&nbsp;선택해 주세요&nbsp;&nbsp;━━━━━━━━━━━━━━━━━━━ ")?></option>
<?
					for($k=1; $k<count($assemble_list_pexp); $k++) {
						if(strlen($_acdata[$assemble_list_pexp[$k]]->pridx)>0 && (strlen($_acdata[$assemble_list_pexp[$k]]->quantity)==0 || $_acdata[$assemble_list_pexp[$k]]->quantity>0)) {
							if($_acdata[$assemble_list_pexp[$k]]->pridx==$_acdata[$assemble_pridx_exp[$j]]->pridx) {
								echo "<option value=\"".$_acdata[$assemble_list_pexp[$k]]->productcode."|".$_acdata[$assemble_list_pexp[$k]]->quantity."|".$_acdata[$assemble_list_pexp[$k]]->sellprice."|G|".htmlspecialchars($_acdata[$assemble_list_pexp[$k]]->tinyimage)."\" selected style=\"color:#FF00FF;\">".$_acdata[$assemble_list_pexp[$k]]->productname." / 기본선택</option>\n";
							} else {
								$minus_price = $_acdata[$assemble_list_pexp[$k]]->sellprice - $_acdata[$assemble_pridx_exp[$j]]->sellprice;
								echo "<option value=\"".$_acdata[$assemble_list_pexp[$k]]->productcode."|".$_acdata[$assemble_list_pexp[$k]]->quantity."|".$_acdata[$assemble_list_pexp[$k]]->sellprice."|".$assemble_state."|".htmlspecialchars($_acdata[$assemble_list_pexp[$k]]->tinyimage)."\" style=\"color:#FF4C00;\">".$_acdata[$assemble_list_pexp[$k]]->productname.($minus_price>0?" / +".number_format($minus_price):" / ".number_format($minus_price))."</option>\n";
							}
						}
					}
?>
							</select></td>
						</tr>
						</table>
						</td>
					</tr>
<?
			}
?>
					</table>
					</td>
				</tr>
				<tr>
					<td style="padding-top:20px;padding-left:5px;padding-right:5px;padding-bottom:10px;"><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0><tr><td height="1" bgcolor="#DADADA"></td></tr></table></td>
				</tr>
				<tr>
					<td style="padding:5px;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td align="center" bgcolor="#FFFFFF" style="padding:10px;border:1px #DADADA solid;">
						<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td><span style="font-size:16px;color:#000000;line-height:18px;"><b>구매수량&nbsp;:&nbsp;</b></span></td>
								<td>
								<table cellpadding="0" cellspacing="0">
								<tr>
									<td><input type=text name="assemblequantity" value="<?=($miniq>1?$miniq:"1")?>" size="4" style="height:24px;text-align:center;font-weight:bold;font-size:14px;BORDER:#DFDFDF 1px solid;BACKGROUND-COLOR:#FFFFFF;padding-top:4pt;padding-bottom:1pt;" readonly></td>
									<td style="padding-left:4px;padding-right:4px;">
									<table cellpadding="0" cellspacing="0">
									<tr>
										<td valign="top" style="padding-bottom:1px;"><a href="javascript:change_quantity('up')"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_neroup2.gif" border="0"></a></td>
									</tr>
									<tr>
										<td valign="bottom" style="padding-top:1px;"><a href="javascript:change_quantity('dn')"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_nerodown2.gif" border="0"></a></td>
									</tr>
									</table>
									</td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>
							<?if(strlen($dicker)==0) { ?>
							<td style="padding-left:20px;">
							<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td><span style="font-size:16px;color:#000000;line-height:18px;"><b>합계금액&nbsp;:&nbsp;</b></span></td>
								<td>
								<table cellpadding="0" cellspacing="0">
								<tr>
									<td><input type=text name="assembleprice" id="idx_assembleprice" value="<?=number_format($miniq>1?$miniq*$_pdata->sellprice:$_pdata->sellprice)?>" size="12" style="height:24px;text-align:right;font-weight:bold;font-size:14px;BORDER:#DFDFDF 1px solid;BACKGROUND-COLOR:#FFFFFF;padding-top:4pt;padding-bottom:1pt;padding-right:2pt;" readonly></td>
									<td style="padding-left:20px;"><a href="javascript:CheckForm('','')" onMouseOver="window.status='장바구니담기';return true;" class="btn_black">장바구니 담기</a></td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>
							<? } ?>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
<?
		}
?>
				</td>
				<td background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/skin_tag_t04.gif"></td>
			</tr>
			</table>
			</div>
			</div>
<?
	}
?>



<!-- 쇼핑태그 -->
<?if($_data->ETCTYPE["TAGTYPE"]!="N") {?>

<div class="tag_list_wrap">
	<div class="tag_list">
		<div class="reg">
			<p>본상품의 태그를 달아주세요(한번에 하나의 태그만) </p>
			<input type="text" name="searchtagname" id="searchtagname" /> &nbsp; <a href="#a" class="btn_black" onclick="tagCheck('<?=$productcode?>')">TAG달기</a>
		</div>
		<div class="list">
		<span id="prtaglist" style="word-break:break-all">
		<?
			$arrtaglist=explode(",",$_pdata->tag);
			$jj=0;
			for($i=0;$i<count($arrtaglist);$i++) {
				$arrtaglist[$i]=preg_replace("/<|>/","",$arrtaglist[$i]);
				if(strlen($arrtaglist[$i])>0) {
					if($jj>0) echo ",&nbsp;&nbsp;";
					echo "<a href=\"".$Dir.FrontDir."tag.php?tagname=".urlencode($arrtaglist[$i])."\" onmouseover=\"window.status='".$arrtaglist[$i]."';return true;\" onmouseout=\"window.status='';return true;\">".$arrtaglist[$i]."</a>";
					$jj++;
				}
			}
		?>

		</span>
			<!--p><a href="javascript:tagView();" class="btn_small">더보기</a></p-->
		</div>
	</div>
</div>

<?}?>
<!-- #쇼핑태그 -->



<div class="d_banner">
			<ul>
				<li><a href="<?=$Dir.FrontDir?>company.php"><img src="<?=$Dir?>image/detail/d_banner1.jpg" alt="에코팩토리 스토리"></a></li>
				<li><a href="<?=$Dir.BoardDir?>board.php?board=recipesix"><img src="<?=$Dir?>image/detail/d_banner2.jpg" alt="에코팩토리 아카데미"></a></li>
				<li><a href="<?=$Dir.BoardDir?>board.php?board=event"><img src="<?=$Dir?>image/detail/d_banner3.jpg" alt="이벤트"></a></li>
			</ul>
</div><!-- end d_banner -->




<div class="product_description_warp">
	<div class="product_description">


<div class="description_menu"><a name=1>&nbsp;</a>
<ul>
	<li><a href="#1"><img src="<?=$Dir?>image/detail/d_title_over_01.gif"  alt="제품상세"></a></li>
	<li><a href="#2"><img src="<?=$Dir?>image/detail/d_title_02.gif" alt="상품후기"></a></li>
	<li><a href="#3"><img src="<?=$Dir?>image/detail/d_title_03.gif" alt="제품Q&A"></a></li>
	<li><a href="#4"><img src="<?=$Dir?>image/detail/d_title_04.gif"  alt="배송정책"></a></li>
	<li><a href="#5"><img src="<?=$Dir?>image/detail/d_title_05.gif" alt="주의사항"></a></li>
</ul>
<div class="clearboth"></div>
</div>


<div class="description_include">
                <ul>
					<li><img src="<?=$Dir?>image/detail/detail_bg01.gif"></li>
					<?if($_data->coupon_ok=="Y" && strlen($coupon_body)>0) {?>
					<li><div class="detail_content"><?=$coupon_body?></div></li>
					<?}?>
					<li>
					<div class="detail_content">
					<?php
					if(strlen($detail_filter)>0) {
						$_pdata->content = preg_replace($filterpattern,$filterreplace,$_pdata->content);
					}

					if (strpos($_pdata->content,"table>")!=false || strpos($_pdata->content,"TABLE>")!=false)
						echo "<pre>".$_pdata->content."</pre>";
					else if(strpos($_pdata->content,"</")!=false)
						echo nl2br($_pdata->content);
					else if(strpos($_pdata->content,"img")!=false || strpos($_pdata->content,"IMG")!=false)
						echo nl2br($_pdata->content);
					else
					echo str_replace(" ","&nbsp;",nl2br($_pdata->content));
					?>
					</div>
					</li>
					<li><img src="<?=$Dir?>image/detail/detail_bg3.gif" ></li>
				</ul>
</div>

   </div><!-- //end product_description -->



	<div class="product_review">

<div class="description_menu"><a name=2>&nbsp;</a>
<ul>
	<li><a href="#1"><img src="<?=$Dir?>image/detail/d_title_01.gif"  alt="제품상세"></a></li>
	<li><a href="#2"><img src="<?=$Dir?>image/detail/d_title_over_02.gif" alt="상품후기"></a></li>
	<li><a href="#3"><img src="<?=$Dir?>image/detail/d_title_03.gif" alt="제품Q&A"></a></li>
	<li><a href="#4"><img src="<?=$Dir?>image/detail/d_title_04.gif"  alt="배송정책"></a></li>
	<li><a href="#5"><img src="<?=$Dir?>image/detail/d_title_05.gif" alt="주의사항"></a></li>
</ul>
<div class="clearboth"></div>
</div>

<div class="description_review_list">
<?if($_data->review_type!="N") {?>
<?php include($Dir.FrontDir."prreview_tem001.php"); ?>
<?}?>
<!--
<div class="description_titlearea">
<h4><img src="<?=$Dir?>image/detail/detail_review.gif"  alt="제품상세"></h4>
<span><img src="<?=$Dir?>image/detail/d_review_btn.gif"  alt="제품상세"></span>
<span><img src="<?=$Dir?>image/detail/d_all_btn.gif"  alt="제품상세"></span>
</div>
                   <dl>
				   <dt>
				   <ul class="description_listbar">
				   <li class="cell5">no</li>
				   <li class="cell40">title</li>
				   <li class="cell15">name</li>
				   <li class="cell20">date</li>
				   <li class="cell20">score</li>
				   </ul>
				   </dt>
				   <dd>
				   <ul class="description_list">
				   <li class="cell5">55</li>
				   <li class="cell40">제품상세페이지 아이템 리뷰입니다.</li>
				   <li class="cell15">김에코</li>
				   <li class="cell20">[ 2013.12.31 ]</li>
				   <li class="cell20">★★★★★</li>
				   </ul>
				   </dd>
				   <dd></dd>
				   <dd></dd>
				   <dd></dd>
				   <dd></dd>
				   </dl>
				   -->
</div>

   </div><!-- //end product_review -->




	<div class="product_qna">

		<div class="description_menu"><a name=3>&nbsp;</a>
		<ul>
			<li><a href="#1"><img src="<?=$Dir?>image/detail/d_title_01.gif"  alt="제품상세"></a></li>
			<li><a href="#2"><img src="<?=$Dir?>image/detail/d_title_02.gif" alt="상품후기"></a></li>
			<li><a href="#3"><img src="<?=$Dir?>image/detail/d_title_over_03.gif"  alt="제품Q&A"></a></li>
			<li><a href="#4"><img src="<?=$Dir?>image/detail/d_title_04.gif"  alt="배송정책"></a></li>
			<li><a href="#5"><img src="<?=$Dir?>image/detail/d_title_05.gif"  alt="주의사항"></a></li>
		</ul>
		<div class="clearboth"></div>
		</div>

		<div class="description_qna_list">
			<?php include($Dir.FrontDir."prqna_tem001.php"); ?>
		</div>

	</div><!-- //end product_qna -->



	<div class="product_delivery">

		<div class="description_menu"><a name=4>&nbsp;</a>
		<ul>
			<li><a href="#1"><img src="<?=$Dir?>image/detail/d_title_01.gif"  alt="제품상세"></a></li>
			<li><a href="#2"><img src="<?=$Dir?>image/detail/d_title_02.gif" alt="상품후기"></a></li>
			<li><a href="#3"><img src="<?=$Dir?>image/detail/d_title_03.gif"  alt="제품Q&A"></a></li>
			<li><a href="#4"><img src="<?=$Dir?>image/detail/d_title_over_04.gif"  alt="배송정책"></a></li>
			<li><a href="#5"><img src="<?=$Dir?>image/detail/d_title_05.gif"  alt="주의사항"></a></li>
		</ul>
		<div class="clearboth"></div>
		</div>

		<div class="description_qna_list">
			<ul>
			<?=$deli_info?>
			</ul>
		</div>

	</div><!-- //end product_delivery -->



	<div class="product_caution">

		<div class="description_menu"><a name=5>&nbsp;</a>
		<ul>
			<li><a href="#1"><img src="<?=$Dir?>image/detail/d_title_01.gif"  alt="제품상세"></a></li>
			<li><a href="#2"><img src="<?=$Dir?>image/detail/d_title_02.gif" alt="상품후기"></a></li>
			<li><a href="#3"><img src="<?=$Dir?>image/detail/d_title_03.gif"  alt="제품Q&A"></a></li>
			<li><a href="#4"><img src="<?=$Dir?>image/detail/d_title_04.gif"  alt="배송정책"></a></li>
			<li><a href="#5"><img src="<?=$Dir?>image/detail/d_title_over_05.gif"  alt="주의사항"></a></li>
		</ul>
		<div class="clearboth"></div>
		</div>

		<div class="description_qna_list">
		    <ul>
				<li><img src="<?=$Dir?>image/detail_guide.gif"  alt="주의사항" width="100%"></li>
				
			</ul>
		</div>

   </div><!-- //end product_caution -->

 </div><!-- //end product_description_warp -->





<div class="ingredient_guide">

<ul>
<h4><img src="<?=$Dir?>image/detail/ingredient_guide_tit.gif" alt="재료가이드"></h4>
<li><img src="<?=$Dir?>image/detail/ingredient_guide_01.jpg" alt="피부/용도별 재료구분표"></li>
<li><img src="<?=$Dir?>image/detail/ingredient_guide_02.jpg" alt="사용레시피"></li>
<li><img src="<?=$Dir?>image/detail/ingredient_guide_03.jpg" alt="제품성적서"></li>
<li><img src="<?=$Dir?>image/detail/ingredient_guide_04.jpg" alt="유기농인증서"></li>
</ul>

</div><!-- //end product_description_banner -->


	</div><!-- //end contents -->
</div><!-- //end container -->
















<?=$count2?>



<script language="JavaScript">
var miniq=<?=($miniq>1?$miniq:1)?>;
var ardollar=new Array(3);
ardollar[0]="<?=$ardollar[0]?>";
ardollar[1]="<?=$ardollar[1]?>";
ardollar[2]="<?=$ardollar[2]?>";
<?
if(strlen($optcode)==0) {
	$maxnum=($count2-1)*10;
	if($optioncnt>0) {
		echo "num = new Array(";
		for($i=0;$i<$maxnum;$i++) {
			if ($i!=0) echo ",";
			if(strlen($optioncnt[$i])==0) echo "100000";
			else echo $optioncnt[$i];
		}
		echo ");\n";
	}
?>

function change_price(temp,temp2,temp3) {
	
<?=(strlen($dicker)>0)?"return;\n":"";?>
	if(temp3=="") temp3=1;
	price = new Array(<?if($priceindex>0) echo "'".number_format($_pdata->sellprice)."','".number_format($_pdata->sellprice)."',"; for($i=0;$i<$priceindex;$i++) { if ($i!=0) { echo ",";} echo "'".$pricetok[$i]."'"; } ?>);

	sprice = new Array(<?if($priceindex>0) echo "'".number_format($_pdata->sellprice)."','".number_format($_pdata->sellprice)."',"; for($i=0;$i<$priceindex;$i++) { if ($i!=0) { echo ",";} echo "'".$spricetok[$i]."'"; } ?>);


	consumer = new Array(<?if($priceindex>0) echo "'".number_format($_pdata->consumerprice)."','".number_format($_pdata->consumerprice)."',"; for($i=0;$i<$priceindex;$i++) { if ($i!=0) { echo ",";} echo "'".$consumertok[$i]."'"; } ?>);
	o_reserve = new Array(<?if($priceindex>0) echo "'".number_format($_pdata->option_reserve)."','".number_format($_pdata->option_reserve)."',"; for($i=0;$i<$priceindex;$i++) { if ($i!=0) { echo ",";} echo "'".$reservetok[$i]."'"; } ?>);
	doprice = new Array(<?if($priceindex>0) echo "'".number_format($_pdata->sellprice/$ardollar[1],2)."','".number_format($_pdata->sellprice/$ardollar[1],2)."',"; for($i=0;$i<$priceindex;$i++) { if ($i!=0) { echo ",";} echo "'".$pricetokdo[$i]."'"; } ?>);
	if(temp==1) {
		if (document.form1.option1.selectedIndex><? echo $priceindex+2 ?>)
			temp = <?=$priceindex?>;
		else temp = document.form1.option1.selectedIndex;
		document.form1.price.value = price[temp];
		
		document.all["idx_price"].innerHTML = document.form1.price.value+"원";


		if(sprice[temp]!='0'){
		document.form1.sprice.value = sprice[temp];
		document.all["idx_sprice"].innerHTML = document.form1.sprice.value+"원";
		}else{
			if(sprice[0]!='0'){
			document.form1.sprice.value = sprice[0];
			document.all["idx_sprice"].innerHTML = document.form1.sprice.value+"원";
			}
		}


		if(consumer[temp]!='0'){
		document.form1.consumer.value = consumer[temp];
		document.all["idx_consumer"].innerHTML = document.form1.consumer.value+"원";
		}else{
			if(consumer[0]!='0'){
			document.form1.consumer.value = consumer[0];
			document.all["idx_consumer"].innerHTML = document.form1.consumer.value+"원";
			}
		}
		if(o_reserve[temp]!='0'){
		document.form1.o_reserve.value = o_reserve[temp];
		document.all["idx_reserve"].innerHTML = document.form1.o_reserve.value+"원";
		}else{
			if(o_reserve[0]!='0'){
			document.form1.o_reserve.value = o_reserve[0];
			document.all["idx_reserve"].innerHTML = document.form1.o_reserve.value+"원";
			}
		}
		
<?if($_pdata->reservetype=="Y" && $_pdata->reserve>0) { ?>
		if(document.getElementById("idx_reserve")) {
			var reserveInnerValue="0";
			if(document.form1.price.value.length>0) {
				var ReservePer=<?=$_pdata->reserve?>;
				var ReservePriceValue=Number(document.form1.price.value.replace(/,/gi,""));
				if(ReservePriceValue>0) {
					reserveInnerValue = Math.round(ReservePer*ReservePriceValue*0.01)+"";
					var result = "";
					for(var i=0; i<reserveInnerValue.length; i++) {
						var tmp = reserveInnerValue.length-(i+1);
						if(i%3==0 && i!=0) result = "," + result;
						result = reserveInnerValue.charAt(tmp) + result;
					}
					reserveInnerValue = result;
				}
			}
			document.getElementById("idx_reserve").innerHTML = reserveInnerValue+"원";
		}
<? } ?>
		if(typeof(document.form1.dollarprice)=="object") {
			document.form1.dollarprice.value = doprice[temp];
			document.all["idx_dollarprice"].innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
		}
	}
	packagecal(); //패키지 상품 적용
	if(temp2>0 && temp3>0) {
		if(num[(temp3-1)*10+(temp2-1)]==0){
			alert('해당 상품의 옵션은 품절되었습니다. 다른 상품을 선택하세요');
			if(document.form1.option1.type!="hidden") document.form1.option1.focus();
			return;
		}
	} else {
		if(temp2<=0 && document.form1.option1.type!="hidden") document.form1.option1.focus();
		else document.form1.option2.focus();
		return;
	}
}

<? } else if(strlen($optcode)>0) { ?>

function chopprice(temp){
<?=(strlen($dicker)>0)?"return;\n":"";?>
	ind = document.form1.mulopt[temp];
	price = ind.options[ind.selectedIndex].value;
	originalprice = document.form1.price.value.replace(/,/g, "");
	document.form1.price.value=Number(originalprice)-Number(document.form1.opttype[temp].value);
	if(price.indexOf(",")>0) {
		optprice = price.substring(price.indexOf(",")+1);
	} else {
		optprice=0;
	}
	document.form1.price.value=Number(document.form1.price.value)+Number(optprice);
	if(typeof(document.form1.dollarprice)=="object") {
		document.form1.dollarprice.value=(Math.round(((Number(document.form1.price.value))/ardollar[1])*100)/100);
		document.all["idx_dollarprice"].innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
	}
	document.form1.opttype[temp].value=optprice;
	var num_str = document.form1.price.value.toString()
	var result = ''

	for(var i=0; i<num_str.length; i++) {
		var tmp = num_str.length-(i+1)
		if(i%3==0 && i!=0) result = ',' + result
		result = num_str.charAt(tmp) + result
	}
	document.form1.price.value = result;
	document.all["idx_price"].innerHTML=document.form1.price.value+"원";
	packagecal(); //패키지 상품 적용
}

<?}?>
<? if($_pdata->assembleuse=="Y") { ?>
function setTotalPrice(tmp) {
<?=(strlen($dicker)>0)?"return;\n":"";?>
	var i=true;
	var j=1;
	var totalprice=0;
	while(i) {
		if(document.getElementById("acassemble"+j)) {
			if(document.getElementById("acassemble"+j).value) {
				arracassemble = document.getElementById("acassemble"+j).value.split("|");
				if(arracassemble[2].length) {
					totalprice += arracassemble[2]*1;
				}
			}
		} else {
			i=false;
		}
		j++;
	}
	totalprice = totalprice*tmp;
	var num_str = totalprice.toString();
	var result = '';
	for(var i=0; i<num_str.length; i++) {
		var tmp = num_str.length-(i+1);
		if(i%3==0 && i!=0) result = ',' + result;
		result = num_str.charAt(tmp) + result;
	}
	if(typeof(document.form1.price)=="object") { document.form1.price.value=totalprice; }
	if(typeof(document.form1.dollarprice)=="object") {
		document.form1.dollarprice.value=(Math.round(((Number(document.form1.price.value))/ardollar[1])*100)/100);
		document.all["idx_dollarprice"].innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
	}
	if(document.getElementById("idx_assembleprice")) { document.getElementById("idx_assembleprice").value = result; }
	if(document.getElementById("idx_price")) { document.getElementById("idx_price").innerHTML = result+"원"; }
	if(document.getElementById("idx_price_graph")) { document.getElementById("idx_price_graph").innerHTML = result+"원"; }
	<?if($_pdata->reservetype=="Y" && $_pdata->reserve>0) { ?>
		if(document.getElementById("idx_reserve")) {
			var reserveInnerValue="0";
			if(document.form1.price.value.length>0) {
				var ReservePer=<?=$_pdata->reserve?>;
				var ReservePriceValue=Number(document.form1.price.value.replace(/,/gi,""));
				if(ReservePriceValue>0) {
					reserveInnerValue = Math.round(ReservePer*ReservePriceValue*0.01)+"";
					var result = "";
					for(var i=0; i<reserveInnerValue.length; i++) {
						var tmp = reserveInnerValue.length-(i+1);
						if(i%3==0 && i!=0) result = "," + result;
						result = reserveInnerValue.charAt(tmp) + result;
					}
					reserveInnerValue = result;
				}
			}
			document.getElementById("idx_reserve").innerHTML = reserveInnerValue+"원";
		}
	<? } ?>
}
<? } ?>

function packagecal() {
<?=(count($arrpackage_pricevalue)==0?"return;\n":"")?>
	pakageprice = new Array(<? for($i=0;$i<count($arrpackage_pricevalue);$i++) { if ($i!=0) { echo ",";} echo "'".$arrpackage_pricevalue[$i]."'"; }?>);
	var result = "";
	var intgetValue = document.form1.price.value.replace(/,/g, "");
	var temppricevalue = "0";
	for(var j=1; j<pakageprice.length; j++) {
		if(document.getElementById("idx_price"+j)) {
			temppricevalue = (Number(intgetValue)+Number(pakageprice[j])).toString();
			result="";
			for(var i=0; i<temppricevalue.length; i++) {
				var tmp = temppricevalue.length-(i+1);
				if(i%3==0 && i!=0) result = "," + result;
				result = temppricevalue.charAt(tmp) + result;
			}
			document.getElementById("idx_price"+j).innerHTML=result+"원";
		}
	}

	if(typeof(document.form1.package_idx)=="object") {
		var packagePriceValue = Number(intgetValue)+Number(pakageprice[Number(document.form1.package_idx.value)]);

		if(packagePriceValue>0) {
			result = "";
			packagePriceValue = packagePriceValue.toString();
			for(var i=0; i<packagePriceValue.length; i++) {
				var tmp = packagePriceValue.length-(i+1);
				if(i%3==0 && i!=0) result = "," + result;
				result = packagePriceValue.charAt(tmp) + result;
			}
			returnValue = result;
		} else {
			returnValue = "0";
		}
		if(document.getElementById("idx_price")) {
			document.getElementById("idx_price").innerHTML=returnValue+"원";
		}
		if(document.getElementById("idx_price_graph")) {
			document.getElementById("idx_price_graph").innerHTML=returnValue+"원";
		}
		if(typeof(document.form1.dollarprice)=="object") {
			document.form1.dollarprice.value=Math.round((packagePriceValue/ardollar[1])*100)/100;
			if(document.getElementById("idx_price_graph")) {
				document.getElementById("idx_price_graph").innerHTML=ardollar[0]+" "+document.form1.dollarprice.value+" "+ardollar[2];
			}
		}
	}
}
</script>

<SCRIPT LANGUAGE="JavaScript">

var imagepath="<?=$imagepath?>";
var setcnt=0;

function primg_preview(img,width,height) {
	
	if($("img[name='primg']")!=null) {
		setcnt=0;
		$("img[name='primg']").attr("src",imagepath+img);

		if(width>0){
			$("img[name='primg']").css("width",width+"px");
		}
		if(height>0){
			$("img[name='primg']").css("height",height+"px");
		}
		//alert($("img[name='primg']").css("width"));
	} else {
		if(setcnt<=10) {
			setcnt++;
			setTimeout("primg_preview('"+img+"','"+width+"','"+height+"')",500);
		}
	}
}

function primg_preview3(img,width,height) {

	if($("img[name='primg']")!=null) {
		$("img[name='primg']").attr("src",imagepath+img);

		if(width>0){
			$("img[name='primg']").css("width",width+"px");
		}
		if(height>0){
			$("img[name='primg']").css("height",height+"px");
		}
	}
}

function primg_preview2(img,width,height) {
	obj = event.srcElement;
	clearTimeout(obj._tid);
	obj._tid=setTimeout("primg_preview3('"+img+"','"+width+"','"+height+"')",500);
}

primg_preview('<?=$yesimage[0]?>','<?=$xsize[0]?>','<?=$ysize[0]?>');


</SCRIPT>
