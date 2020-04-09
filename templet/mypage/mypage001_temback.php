<link rel="stylesheet" type="text/css" href="../../css/tem_001.css" media="all" />
<!-- 상세페이지 -->
<div class="main_wrap">
	<div class="container">
	


	<!-- LNB -->
	<div class="left_lnb">

		<div class="lnb_wrap">
			<div class="lnb">
				<h1>마이페이지</h1>
				<ul>
					<li><a class="on" href="<?=$Dir.FrontDir?>mypage.php">마이페이지</a></li>
					<li><a href="<?=$Dir.FrontDir?>mypage_orderlist.php">주문내역</a></li>
					<li><a href="<?=$Dir.FrontDir?>mypage_personal.php">1:1문의</a></li>
					<li><a href="<?=$Dir.FrontDir?>wishlist.php">WishList</a></li>
					<li><a href="<?=$Dir.FrontDir?>mypage_reserve.php">적립금</a></li>
					<li><a href="<?=$Dir.FrontDir?>mypage_coupon.php">쿠폰내역</a></li>
					<? if(getVenderUsed()) { ?>
					<li><a href="<?=$Dir.FrontDir?>mypage_custsect.php">단골매장</a></li>
					<? } ?>
					<li><a href="<?=$Dir.FrontDir?>mypage_usermodify.php">회원정보</a></li>
					<li><a href="<?=$Dir.FrontDir?>mypage_memberout.php">회원탈퇴</a></li>
					<li><a href="../../board/board.php?board=qna&mypageid=1">상품문의</a></li>
				</ul>
			</div>
		</div>

	</div>
	<!-- #LNB -->

	<!-- right_section -->
		<div class="right_section">
			<div class="right_section">
				
				<div class="right_article_wrap">
					<div class="right_article">

						<!-- 주소복사 -->
						<p class="local_copy"><span><a href="#">홈</a> > <a href="#">마이페이지</a> > <a href="#">주문내역</a> </span></p>
						<!-- #주소복사 -->

						<h1>마이페이지</h1>



					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td valign="bottom" style="font-size:11px;letter-spacing:-0.5pt;color:#A1A1A1;">
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td>
							<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="50%" valign="top">

								<div class="bd_box_wrap03">
									<div class="bd_box">
									<h1>나의 혜택</h1>
									
									<table align="center" cellpadding="0" cellspacing="0" border="0" class="ptb_20">
									<tr>
										<td valign="top">
										<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" style="font-size:11px;letter-spacing:-0.5pt;"><font color="#000000"><b>적립금 보유내역</b></font></td>
										</tr>
										<tr>
											<td align="center" style="font-size:18px;line-height:22px;letter-spacing:-0.5pt;"><font color="#FF4C00"><b><?=number_format($_mdata->reserve)?>원</b></font></td>
										</tr>
										<tr>
											<td align="center"><A HREF="<?=$Dir.FrontDir?>mypage_reserve.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin3_tabel01btn.gif" BORDER="0"></A></td>
										</tr>
										</table>
										</td>
										<td align="center"><img src="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin3_tabel01line.gif" border="0"></td>
										<td valign="top">
										<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="center" style="font-size:11px;letter-spacing:-0.5pt;"><font color="#000000"><b>사용 가능한 쿠폰 내역</b></font></td>
										</tr>
										<tr>
											<td align="center" style="font-size:18px;line-height:22px;letter-spacing:-0.5pt;"><font color="#FF4C00"><b><?=number_format($coupon_cnt)?>장</b></font></td>
										</tr>
										<tr>
											<td align="center"><A HREF="<?=$Dir.FrontDir?>mypage_coupon.php"><IMG SRC="<?=$Dir?>images/common/mypage/<?=$_data->design_mypage?>/mypage_skin3_tabel01btn.gif" BORDER="0"></A></td>
										</tr>
										</table>
										</td>
									</tr>
									</table>

									</div>
								</div>

								</td>
								<td width="50%" align="right" valign="top">

									<div class="bd_box_wrap03">
										<div class="bd_box">
										<h1>나의 혜택</h1>

										<table cellpadding="0" cellspacing="0" width="100%" class="th_left_st">
										<col width="80"></col>
										<col width=""></col>
										<col></col>
										<tr style="letter-spacing:-0.5pt;">
											<th style="font-size:11px;"><font color="#000000"><b>집주소</b></font></th>
											<td style="font-size:11px;"><?=str_replace("=","<br>",$_mdata->home_addr)?></td>
										</tr>
										<tr style="letter-spacing:-0.5pt;">
											<th style="font-size:11px;"><font color="#000000"><b>전화번호</b></font></th>
											<td style="font-size:11px;"><?=$_mdata->home_tel?><?if(strlen($_mdata->mobile)>0)echo ", ".$_mdata->mobile;?></td>
										</tr>
										<tr style="letter-spacing:-0.5pt;">
											<th style="font-size:11px;"><font color="#000000"><b>이메일</b></font></th>
											<td style="font-size:11px;"><A HREF="mailto:<?=$_mdata->email?>"><?=$_mdata->email?></A></td>
										</tr>
										</table>


									</div>
								</div>

								</td>
							</tr>
							</table>
							</td>
						</tr>
				<?
						if(strlen($_ShopInfo->getMemid())>0 && strlen($_ShopInfo->getMemgroup())>0) {
							$arr_dctype=array("B"=>"현금","C"=>"카드","N"=>"");
							$sql = "SELECT a.name,b.group_code,b.group_name,b.group_payment,b.group_usemoney,b.group_addmoney ";
							$sql.= "FROM tblmember a, tblmembergroup b WHERE a.id='".$_ShopInfo->getMemid()."' AND b.group_code=a.group_code ";
							$result=pmysql_query($sql,get_db_conn());
							if($row=pmysql_fetch_object($result)) {
				?>
						<tr>
							<td height="20"></td>
						</tr>
						<tr>
							<td>

								<div class="bd_box_wrap02">
									<h1>Member Discount</h1>
									<div class="bd_box">

									<table width="100%" cellpadding="0" cellspacing="0">
									<tr>
										<td>
										<?if(file_exists($Dir.DataDir."shopimages/etc/groupimg_".$row->group_code.".gif")){?>
										<img src="<?=$Dir.DataDir?>shopimages/etc/groupimg_<?=$row->group_code?>.gif" border=0>
										<?}else{?>
										<img src="<?=$Dir?>images/common/group_img.gif" border="0">
										<?}?>
										</td>
										<td width="100%">
										<B><?=$row->name?></B>님은 <B><FONT color="#FF6633">[<?=$row->group_name?>]</FONT></B> 회원입니다.<br>
										<?if ($row->group_code[0]!="M") {?>
										<B><font color="#0099CC"><?=$row->name?></B>님이 <B><?=number_format($row->group_usemoney)?>원</B> 이상 <?=$arr_dctype[$row->group_payment]?>구매시,
										<?
										$type=substr($row->group_code,0,2);
										if($type=="RW") echo "적립금에 ".number_format($row->group_addmoney)."원을 <B>추가 적립</B>해 드립니다.";
										else if($type=="RP") echo "구매 적립금의 ".number_format($row->group_addmoney)."배를 <B>적립</B>해 드립니다.";
										else if($type=="SW") echo "구매금액 ".number_format($row->group_addmoney)."원을 <B>추가 할인</B>해 드립니다.";
										else if($type=="SP") echo "구매금액의 ".number_format($row->group_addmoney)."%를 <B>추가 할인</B>해 드립니다.";
										?>
										<?}?>
										</font>
										</td>
									</tr>
									</table>

									</div>
								</div>


							</td>
						</tr>
				<?
							}
							pmysql_free_result($result);
						}
				?>
						</table>
						</td>
					</tr>
					<tr>
						<td height="20"></td>
					</tr>
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td><h1 class="mypage_tit">최근주문내역<span> (고객님께서 최근 주문하신 상품의 상세내용과 배송현황 입니다.)</span></h1></td>
							<td align="right" style="padding-bottom:3px;"><A HREF="<?=$Dir.FrontDir?>mypage_orderlist.php" class="btn_small">전체보기</A></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%" border="0" style="table-layout:fixed" class="th_top_st">
						<!-- 주문일자, 주문 상품명, 배송상태, 배송추적, 결제방법, 결제금액, 상세정보  -->
						<col width="65"></col>
						<col></col>
						<col width="80"></col>
						<col width="100"></col>
						<col width="80"></col>
						<col width="80"></col>
						<col width="60"></col>
						<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
							<th>주문일자</th>
							<th>주문상품명</th>
							<th>배송상태</th>
							<th>배송추적</th>
							<th>결제방법</th>
							<th>결제금액</th>
							<th>상세정보</th>
						</tr>
				<?
						$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
						$result=pmysql_query($sql,get_db_conn());
						$delicomlist=array();
						while($row=pmysql_fetch_object($result)) {
							$delicomlist[$row->code]=$row;
						}
						pmysql_free_result($result);

						$curdate=date("Ymd",strtotime('-1 month'));
						$sql = "SELECT ordercode, price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn ";
						$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
						$sql.= "AND ordercode >= '".$curdate."' AND (del_gbn='N' OR del_gbn='A') ";
						$sql.= "ORDER BY ordercode DESC LIMIT 5 ";

						$result=pmysql_query($sql,get_db_conn());
						$cnt=0;
						while($row=pmysql_fetch_object($result)) {
							echo "<tr bgcolor=#FFFFFF onmouseover=\"this.style.background='#FEFBD1'\" onmouseout=\"this.style.background='#FFFFFF'\">\n";
							echo "	<td align=center style=\"font-size:8pt;padding:3\">".substr($row->ordercode,0,4).".".substr($row->ordercode,4,2).".".substr($row->ordercode,6,2)."</td>\n";
							echo "	<td colspan=3>\n";
							echo "	<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\" class=\"style_none\">\n";
							echo "	<col width=></col>\n";
							echo "	<col width=1></col>\n";
							echo "	<col width=80></col>\n";
							echo "	<col width=1></col>\n";
							echo "	<col width=100></col>\n";
							$sql = "SELECT * FROM tblorderproduct WHERE ordercode='".$row->ordercode."' ";
							$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
							$result2=pmysql_query($sql,get_db_conn());
							$jj=0;
							while($row2=pmysql_fetch_object($result2)) {
								if($jj>0) echo "<tr><td colspan=5 height=1 bgcolor=#E7E7E7></tr>";
								echo "<tr>\n";
								echo "	<td style=\"font-size:8pt;padding:3;line-height:11pt\"><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='주문내역조회';return true;\" onmouseout=\"window.status='';return true;\">".$row2->productname."</a></td>\n";
								echo "	<td></td>\n";
								echo "	<td align=center style=\"font-size:8pt;\">";
								if ($row2->deli_gbn=="C") echo "주문취소";
								else if ($row2->deli_gbn=="D") echo "취소요청";
								else if ($row2->deli_gbn=="E") echo "환불대기";
								else if ($row2->deli_gbn=="X") echo "발송준비";
								else if ($row2->deli_gbn=="Y") echo "발송완료";
								else if ($row2->deli_gbn=="N") {
									if (strlen($row->bank_date)<12 && strstr("BOQ", $row->paymethod[0])) echo "입금확인중";
									else if ($row->pay_admin_proc=="C" && $row->pay_flag=="0000") echo "결제취소";
									else if (strlen($row->bank_date)>=12 || $row->pay_flag=="0000") echo "발송준비";
									else echo "결제확인중";
								} else if ($row2->deli_gbn=="S") {
									echo "발송준비";
								} else if ($row2->deli_gbn=="R") {
									echo "반송처리";
								} else if ($row2->deli_gbn=="H") {
									echo "발송완료 [정산보류]";
								}
								echo "	</td>\n";
								echo "	<td></td>\n";
								echo "	<td align=center style=\"font-size:8pt;padding-top:3\">";
								$deli_url="";
								$trans_num="";
								$company_name="";
								if($row2->deli_gbn=="Y") {
									if($row2->deli_com>0 && $delicomlist[$row2->deli_com]) {
										$deli_url=$delicomlist[$row2->deli_com]->deli_url;
										$trans_num=$delicomlist[$row2->deli_com]->trans_num;
										$company_name=$delicomlist[$row2->deli_com]->company_name;
										echo $company_name."<br>";
										if(strlen($row2->deli_num)>0 && strlen($deli_url)>0) {
											if(strlen($trans_num)>0) {
												$arrtransnum=explode(",",$trans_num);
												$pattern=array("[1]","[2]","[3]","[4]");
												$replace=array(substr($row2->deli_num,0,$arrtransnum[0]),substr($row2->deli_num,$arrtransnum[0],$arrtransnum[1]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1],$arrtransnum[2]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1]+$arrtransnum[2],$arrtransnum[3]));
												$deli_url=str_replace($pattern,$replace,$deli_url);
											} else {
												$deli_url.=$row2->deli_num;
											}
											echo "<A HREF=\"javascript:DeliSearch('".$deli_url."')\"><img src=".$Dir."images/common/btn_mypagedeliview.gif border=0></A>";
										}
									} else {
										echo "-";
									}
								} else {
									echo "-";
								}
								echo "	</td>\n";
								echo "</tr>\n";
								$jj++;
							}
							pmysql_free_result($result2);
							echo "	</table>\n";
							echo "	</td>\n";
							echo "	<td align=center style=\"font-size:8pt;\">";
							if (strstr("B",$row->paymethod[0])) echo "무통장입금";
							else if (strstr("V",$row->paymethod[0])) echo "실시간계좌이체";
							else if (strstr("O",$row->paymethod[0])) echo "가상계좌";
							else if (strstr("Q",$row->paymethod[0])) echo "가상계좌-<FONT COLOR=\"red\">매매보호</FONT>";
							else if (strstr("C",$row->paymethod[0])) echo "신용카드";
							else if (strstr("P",$row->paymethod[0])) echo "신용카드-<FONT COLOR=\"red\">매매보호</FONT>";
							else if (strstr("M",$row->paymethod[0])) echo "휴대폰";
							else echo "";
							echo "	</td>\n";
							echo "	<td align=right style=\"font-size:8pt;padding-right:5\"><FONT COLOR=\"#EE1A02\"><B>".number_format($row->price)."원</B></FONT></td>\n";
							echo "	<td align=center><A HREF=\"javascript:OrderDetailPop('".$row->ordercode."')\" onmouseover=\"window.status='주문내역조회';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir."images/common/mypage_detailview.gif\" border=0></A></td>\n";
							echo "</tr>\n";
							$cnt++;
						}
						pmysql_free_result($result);

						if ($cnt==0) {
							echo "<tr height=40><td colspan=7 align=center bgcolor=#FFFFFF>최근 1개월 이내에 구매하신 내역이 없습니다.</td></tr>";
						}
				?>
						</table>
						</td>
					</tr>
				<?
					if($_data->personal_ok=="Y") {	//1:1고객게시판을 사용중이라면,,,,,
				?>
					<tr>
						<td height="20"></td>
					</tr>
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td><h1 class="mypage_tit">최근문의내역<span> (고객게시판과1:1 맞춤상담을 통한 문의내역과 답변을 보실수 있습니다.)</span></h1></td>
							<td align="right" style="padding-bottom:3px;"><A HREF="<?=$Dir.FrontDir?>mypage_personal.php"  class="btn_small">전체보기</A></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%" class="th_top_st">
						<col width="110"></col>
						<col></col>
						<col width="65"></col>
						<col width="105"></col>
						<tr>
							<th>문의날짜</th>
							<th>제목</th>
							<th>답변여부</th>
							<th>답변날짜</th>
						</tr>
				<?
						$sql = "SELECT idx,subject,date,re_date FROM tblpersonal ";
						$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
						$sql.= "ORDER BY idx DESC LIMIT 5 ";
						$result = pmysql_query($sql,get_db_conn());
						$cnt=0;
						while($row=pmysql_fetch_object($result)) {
							$date = substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)."(".substr($row->date,8,2).":".substr($row->date,10,2).")";
							$re_date="-";
							if(strlen($row->re_date)==14) {
								$re_date = substr($row->re_date,0,4)."/".substr($row->re_date,4,2)."/".substr($row->re_date,6,2)."(".substr($row->re_date,8,2).":".substr($row->re_date,10,2).")";
							}
							if($cnt>0) echo "<tr><td height=\"1\" colspan=\"4\" bgcolor=\"#DDDDDD\"></td></tr>\n";

							echo "<tr height=\"28\" align=\"center\">\n";
							echo "	<td><font color=\"#333333\">".$date."</font></td>\n";
							echo "	<td align=\"left\"><A HREF=\"javascript:ViewPersonal('".$row->idx."')\"><font color=\"#333333\">".strip_tags($row->subject)."</font></A></td>\n";
							echo "	<td>";
							if(strlen($row->re_date)==14) {
								echo "<img src=\"".$Dir."images/common/mypersonal_skin_icon1.gif\" border=\"0\" align=\"absmiddle\">";
							} else {
								echo "<img src=\"".$Dir."images/common/mypersonal_skin_icon2.gif\" border=\"0\" align=\"absmiddle\">";
							}
							echo "	</td>\n";
							echo "	<td><font color=\"#333333\">".$re_date."</font></td>\n";
							echo "</tr>\n";
							$cnt++;
						}
						pmysql_free_result($result);
						if ($cnt==0) {
							echo "<tr height=\"30\"><td colspan=\"4\" align=\"center\">문의내역이 없습니다.</td></tr>";
						}
				?>
						</table>
						</td>
					</tr>
				<?
					}
				?>
					<tr>
						<td height="20"></td>
					</tr>
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td><h1 class="mypage_tit">관심상품 목록 <span>(고객님께서 찜하신 상품입니다.)</span></h1></td>
							<td align="right" style="padding-bottom:3px;"><A HREF="<?=$Dir.FrontDir?>wishlist.php"  class="btn_small">전체보기</A></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td height="2" bgcolor="636363"></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td>
							<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								<table cellpadding="2" cellspacing="0" width="100%">
								<TR>
				<?
								$sql = "SELECT b.productcode,b.productname,b.sellprice,b.quantity,b.reserve,b.reservetype,b.tinyimage, ";
								$sql.= "b.option_price,b.option_quantity,b.selfcode,b.etctype FROM tblwishlist a, tblproduct b ";
								$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
								$sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
								$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
								$sql.= "AND b.display='Y' LIMIT 5 ";
								$result=pmysql_query($sql,get_db_conn());
								$cnt=0;
								while($row=pmysql_fetch_object($result)) {
									if ($cnt!=0 && $cnt%5==0) {
										echo "</tr><tr><td colspan=\"9\" height=\"10\"></td></tr>\n";
									}
									if ($cnt!=0 && $cnt%5!=0) {
										echo "<td width=\"10\" nowrap></td>";
									}
									echo "<td width=\"20%\" align=\"center\" valign=\"top\">\n";
									echo "<TABLE cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\" border=\"0\" id=\"W".$row->productcode."\" onmouseover=\"quickfun_show(this,'W".$row->productcode."','')\" onmouseout=\"quickfun_show(this,'W".$row->productcode."','none')\">\n";
									echo "<TR height=\"100\">\n";
									echo "	<TD align=\"center\">";
									echo "<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\">";
									if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)) {
										echo "<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=\"0\" ";
										$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
										if($_data->ETCTYPE["IMGSERO"]=="Y") {
											if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) echo "height=\"".$_data->primg_minisize2."\" ";
											else if (($width[1]>=$width[0] && $width[0]>=$_data->primg_minisize) || $width[0]>=$_data->primg_minisize) echo "width=\"".$_data->primg_minisize."\" ";
										} else {
											if ($width[0]>=$width[1] && $width[0]>=$_data->primg_minisize) echo "width=\"".$_data->primg_minisize."\" ";
											else if ($width[1]>=$_data->primg_minisize) echo "height=\"".$_data->primg_minisize."\" ";
										}
									} else {
										echo "<img src=\"".$Dir."images/no_img.gif\" border=\"0\" align=\"center\"";
									}
									echo "	></A></td>";
									echo "</tr>\n";
									echo "<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','W','".$row->productcode."','".($row->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
									echo "<tr>";
									echo "	<td align=\"center\" style=\"word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='상품상세조회';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT></A></td>\n";
									echo "</tr>\n";
									echo "<tr>\n";
									echo "	<td align=\"center\" class=\"prprice\">";
									if($dicker=dickerview($row->etctype,number_format($row->sellprice)."원",1)) {
										echo $dicker;
									} else if(strlen($_data->proption_price)==0) {
										echo "<img src=\"".$Dir."images/common/won_icon.gif\" border=\"0\" align=\"absmiddle\"> ".number_format($row->sellprice)."원";
										if (strlen($row->option_price)!=0) echo "<FONT color=\"#FF0000\">(옵션변동)</FONT>";
									} else {
										echo "<img src=\"".$Dir."images/common/won_icon3.gif\" border=\"0\" align=\"absmiddle\"> ";
										if (strlen($row->option_price)==0) echo number_format($row->sellprice)."원";
										else echo str_replace("[PRICE]",number_format($row->sellprice),$_data->proption_price);
									}
									if ($row->quantity=="0") echo soldout(1);
									echo "	</td>\n";
									echo "</tr>\n";
									$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
									if($reserveconv>0) {
										echo "<tr>\n";
										echo "	<td align=\"center\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=\"0\" align=\"absmiddle\"> ".number_format($reserveconv)."원</td>\n";
										echo "</tr>\n";
									}
									echo "</table>\n";
									echo "</td>";

									$cnt++;
								}
								if($cnt>0 && $cnt<5) {
									for($k=0; $k<(5-$cnt); $k++) {
										echo "<td width=\"10\" nowrap></td>\n<td width=\"20%\"></td>\n";
									}
								}
								pmysql_free_result($result);
								if ($cnt==0) {
									echo "<td height=\"30\" colspan=\"9\" align=\"center\">WishList에 담긴 상품이 없습니다.</td>";
								}
								
				?>
								</tr>
								</TABLE>
								</td>
							</tr>
							</table>
							</td>
						</tr>
						<tr>
							<td height="10"></td>
						</tr>
						<tr>
							<td height="1" bgcolor="#DDDDDD"></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td height="20"></td>
					</tr>
					</table>


				</div>
			</div>
	</div>
	<!-- #right_section -->
	
	</div>
</div>
<!-- #상세페이지 -->