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
					<li><a class="on" href="<?=$Dir.FrontDir?>mypage_personal.php">1:1문의</a></li>
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
						<p class="local_copy"><span><a href="#">홈</a> > <a href="#">마이페이지</a> > <a href="#">1:1문의</a> </span></p>
						<!-- #주소복사 -->

						<h1>1:1문의</h1>

	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td valign="bottom" style="padding-left:10px;font-size:11px;letter-spacing:-0.5pt;"><font color="#666666">* 고객게시판과 1:1맞춤상담을 통한 문의내역과 답변을 보실 수 있습니다.</font></td>
			<td align=right style="padding-bottom:3px;"><A HREF="javascript:PersonalWrite()" class="btn_small">1:1문의하기</A></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<table cellpadding="0" cellspacing="0" width="100%" class="th_top_st">
		<col width="6%"></col>
		<col></col>
		<col width="17%"></col>
		<col width="13%"></col>
		<col width="17%"></col>
		<tr>
			<th>NO</th>
			<th>글제목</th>
			<th>문의날짜</th>
			<th>답변여부</th>
			<th>답변날짜</th>
		</tr>
<?php
		$sql = "SELECT COUNT(*) as t_count FROM tblpersonal ";
		$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
		$paging = new Paging($sql,10,10,'GoPage',true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		$sql = "SELECT idx,subject,date,re_date FROM tblpersonal ";
		$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "ORDER BY idx DESC";
		$sql = $paging->getSql($sql);
		$result = pmysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=pmysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

			$date = substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)."(".substr($row->date,8,2).":".substr($row->date,10,2).")";
			$re_date="-";
			if(strlen($row->re_date)==14) {
				$re_date = substr($row->re_date,0,4)."/".substr($row->re_date,4,2)."/".substr($row->re_date,6,2)."(".substr($row->re_date,8,2).":".substr($row->re_date,10,2).")";
			}

			if($cnt>0) {
				echo "<tr>\n";
				echo "	<td height=\"1\" colspan=\"5\" bgcolor=\"#DDDDDD\"></td>\n";
				echo "</tr>\n";
			}

			echo "<tr height=\"28\" align=\"center\">\n";
			echo "	<td><font color=\"#333333\">".$number."</font></td>\n";
			echo "	<td>&nbsp;<A HREF=\"javascript:ViewPersonal('".$row->idx."')\"><font color=\"#333333\">".strip_tags($row->subject)."</font></A></td>\n";
			echo "	<td><font color=\"#333333\">".$date."</font></td>\n";
			echo "	<td>";
			if(strlen($row->re_date)==14) {
				echo "<img src=\"".$Dir."images/common/mypersonal_skin_icon3.gif\" border=\"0\">";
			} else {
				echo "<img src=\"".$Dir."images/common/mypersonal_skin_icon2.gif\" border=\"0\">";
			}
			echo "	</td>\n";
			echo "	<td><font color=\"#333333\">".$re_date."</font></td>\n";
			echo "</tr>\n";
			$cnt++;
		}
		pmysql_free_result($result);
		if ($cnt==0) {
			echo "<tr height=\"30\"><td colspan=\"5\" align=\"center\">문의내역이 없습니다.</td></tr>";
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