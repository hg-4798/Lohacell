
<!-- 상세페이지 -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
<div class="main_wrap">
	<div class="customer_wrap">

	<!-- LNB -->
	
	<?	include ($Dir.FrontDir."mypage_TEM01_left.php");?>
	
	<!-- #LNB -->

	<!-- right_section -->
		<div class="content_area">
			<div class="line_map_r">				
				<a href="<?=$Dir.MainDir?>main.php">홈</a> > <a href="mypage.php">마이페이지</a> ><strong> 1:1문의 	</strong></div>
				
			<div class="customer_notice_wrap">
				<!-- <h3 class="notice_title">1:1 문의하기<span>고객게시판과 1:1 맞춤상담을 통한 문의내역과 답변을 보실 수 있습니다.</span></h3> -->

		<h3 class="ng fz_16 mt_40" style="padding-bottom:5px">1:1문의</h3>
		<table class="list_table" cellpadding="0" cellspacing="0" >
			<colgroup>
				<col width="6%"></col>
				<col></col>
				<col width="17%"></col>
				<col width="13%"></col>
				<col width="17%"></col>
			</colgroup>
			<thead>
				<tr>
					<th>NO</th>
					<th>글제목</th>
					<th>문의날짜</th>
					<th>답변여부</th>
					<th>답변날짜</th>
				</tr>
			</thead>
			<tbody>
<?php
		$sql = "SELECT idx,subject,date,re_date FROM tblpersonal ";
		$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
		$sql.= "ORDER BY idx DESC";
		$paging = new Tem001_saveheels_Paging($sql,10,10,'GoPage',true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;
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


			echo "<tr height=\"28\" align=\"center\">\n";
			echo "	<td><font color=\"#333333\">".$number."</font></td>\n";
			echo "	<td>&nbsp;<A HREF=\"javascript:ViewPersonal('".$row->idx."')\"><font color=\"#333333\">".strip_tags($row->subject)."</font></A></td>\n";
			echo "	<td><font color=\"#333333\">".$date."</font></td>\n";
			echo "	<td>";
			if(strlen($row->re_date)==14) {
				echo "답변완료";
			} else {
				echo "미답변";
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
		</tbody>
		</table>
		
	
		
	<div class="page_wrap">
		<div>&nbsp;</div>
		<div class="page">
		<?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
		</div>
	</div>
	
	<div class="button_area">
		<div class="button_box">
			<A HREF="javascript:PersonalWrite()" class="btn_small">1:1문의하기</A>
		</div>
	</div>
	


		</div>
	</div>
	</div>
	<!-- #right_section -->
	
</div>

</td></tr></table>
<!-- #상세페이지 -->