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
					<li><a href="<?=$Dir.FrontDir?>mypage.php">마이페이지</a></li>
					<li><a href="<?=$Dir.FrontDir?>mypage_orderlist.php">주문내역</a></li>
					<li><a href="<?=$Dir.FrontDir?>mypage_personal.php">1:1문의</a></li>
					<li><a href="<?=$Dir.FrontDir?>wishlist.php">WishList</a></li>
					<li><a class="on" href="<?=$Dir.FrontDir?>mypage_reserve.php">적립금</a></li>
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
						<p class="local_copy"><span><a href="#">홈</a> > <a href="#">마이페이지</a> > <a href="#">적립금</a> </span></p>
						<!-- #주소복사 -->

						<h1>적립금</h1>

	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td style="padding:5px;">

		<div class="bd_box_wrap02">
			<h1>Point</h1>
			<div class="bd_box">

			<table cellpadding="0" cellspacing="0" align=center>
			<tr>
				<td><img src="<?=$Dir?>images/common/myreserve/<?=$_data->design_myreserve?>/mypersonal_skin3_t_text1.gif" border="0"></td>
				<td align="center" valign="bottom" style="padding-left:10px;padding-right:10px;font-size:30px;line-height:28px;letter-spacing:-0.5pt;"><font color="#0099CC"><b><?=number_format($reserve)?>원</b></font></td>
				<td><img src="<?=$Dir?>images/common/myreserve/<?=$_data->design_myreserve?>/mypersonal_skin3_t_text2.gif" border="0"></td>
			</tr>
			</table>

			</div>
		</div>

		</td>
	</tr>
	<tr>
		<td style="padding:10px;padding-right:0px;font-size:11px;letter-spacing:-0.5pt;line-height:15px;">
		* 고객님께서 사용가능한 적립금은 <b><?=number_format($reserve)?>원</b> 입니다.<br>
		* 적립된 금액이 <b><?=number_format($maxreserve)?>원 이상 누적</b>되었을때, 사용하실 수 있습니다.결제시 적립금 사용여부를 확인하는 안내문이 나옵니다. <br>
		* 적립금 내역은 <b>최근 6개월분이 제공</b>되므로 이점 양지 바랍니다.<br>
		* 주문완료 후 부여된 적립 내역은 해당 내역을 클릭하시면 상세내역을 확인하실 수 있습니다.(단, 삭제하신 주문내역은 조회가 불가능합니다. )
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" border="0" class="th_top_st">
		<col width="100"></col>
		<col></col>
		<col width="80"></col>
		<col width="80"></col>

		<tr>
			<th>발생일자</th>
			<th>발생내역</th>
			<th>결제금액</th>
			<th>적립금</th>
		</tr>
<?
		$sql = "SELECT COUNT(*) as t_count FROM tblreserve ";
		$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND date >= '".$s_curdate."' AND date <= '".$e_curdate."' ";
		$paging = new Paging($sql,10,10,'GoPage',true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		$sql = "SELECT * FROM tblreserve WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "AND date >= '".$s_curdate."' AND date <= '".$e_curdate."' ";
		$sql.= "ORDER BY date DESC";
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=pmysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
			$date=substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2);



			$ordercode="";
			$orderprice="";
			$orderdata=$row->orderdata;
			if(strlen($orderdata)>0) {
				$tmpstr=explode("=",$orderdata);
				$ordercode=$tmpstr[0];
				$orderprice=$tmpstr[1];
			}

			echo "<tr height=\"28\" align=\"center\">\n";
			echo "	<td><font color=\"#333333\">".$date."</font></td>\n";
			echo "	<td align=\"left\"><nobr><a";
			if(strlen($ordercode)>0) echo " style=\"cursor:hand;\" onclick=\"OrderDetailPop('".$ordercode."')\">";
			echo "<font color=\"#333333\">".$row->content."</font></a></td>\n";
			echo "	<td>";
			if(strlen($orderprice)>0 && $orderprice>0) {
				echo "<font color=\"#F02800\"><b>".number_format($orderprice)."원</b></font>";
			} else {
				echo "-";
			}
			echo "</td>\n";
			echo "	<td><font color=\"#333333\">".number_format($row->reserve)."원</font></td>\n";
			echo "</tr>\n";
			$cnt++;
		}
		pmysql_free_result($result);
		if ($cnt==0) {
			echo "<tr height=\"28\"><td colspan=\"4\" align=\"center\">해당내역이 없습니다.</td></tr>";
		}
?>
		</table>
		</td>
	</tr>

	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td align="center"><?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?></td>
	</tr>
	</table>


				</div>
			</div>
	</div>
	<!-- #right_section -->
	
	</div>
</div>
<!-- #상세페이지 -->