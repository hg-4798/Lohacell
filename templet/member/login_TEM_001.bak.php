<link rel="stylesheet" type="text/css" href="../../css/tem_001.css" media="all" />
<!-- 상세페이지 -->
<div class="main_wrap">

	<!-- 로그인 -->
	<div class="login_wrap">
		<h1></h1>
		<table width="815" border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed;">
			<colgroup><col width="402" /><col width="*" /><col width="402" /></colgroup>
			<tr valign=top>
				<td>
					<div class="login_box pb_30">
						<h2>회원 로그인</h2>
						<table class="spec_view" width="100%" border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed;">
							<colgroup><col width="70" /><col width="*" /></colgroup>
							<tr>
								<th>아이디</th>
								<td><input type="text" name="id" id="" class="text_st w250 mt_5" /></td>
							</tr>
							<tr>
								<th>비밀번호</th>
								<td><input type="password" name="passwd" id="" class="text_st w250 mt_5" onkeydown="CheckKeyForm1()" /></td>
							</tr>
							<tr>
								<th></th>
								<td><a href="JavaScript:CheckForm()" class="btn_black w250 mt_5">로그인</a></td>
							</tr>
						</table>
					</div>
				</td>
				<td></td>
				<td>
					<div class="login_box">
						<h2>고객센터</h2>
						<table class="spec_view" width="100%" border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed;">
							<colgroup><col width="150" /><col width="*" /></colgroup>
							<tr>
								<th>아이디를 잊었나요?</th>
								<td><a href="findid.php" class="btn_black w200 mt_5">아이디 찾기</a></td>
							</tr>
							<tr>
								<th>비밀번호를 잊었나요?</th>
								<td><a href="findpw.php" class="btn_black w200 mt_5">비밀번호 찾기</a></td>
							</tr>
							<tr>
								<th>아직 회원이 아니신가요?</th>
								<td><a href="[JOIN]" class="btn_black w200 mt_5">회원가입</a></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan=3>
					<div class="no_member">
						<h2>비회원 주문</h2>
						<table class="spec_view" width="100%" border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed;">
							<colgroup><col width="50" /><col width="150" /><col width="70" /><col width="150" /><col width="" /></colgroup>
							<tr>
								<th>이름</th>
								<td><input type="text" name="ordername" id="" class="text_st02" maxlength="20" /></td>
								<th>주문번호</th>
								<td><input type="text" name="ordercodeid" id="" class="text_st02" maxlength="20" onkeydown="CheckKeyForm2()"></td>
								<td><a href="javascirpt:CheckOrder();" class="btn_small w100">비회원 주문조회</a></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
			[IFNOLOGIN]
			<tr>
				<td align="center">
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td height="20" colspan="2"></td>
				</tr>
				<tr>
					<td><A HREF=[NOLOGIN]><IMG SRC=[DIR]images/member/login_con_text5_skin2.gif border="0"></a></td>
					<td><A HREF=[NOLOGIN]><IMG SRC=[DIR]images/member/login_con_btn4_skin2.gif border="0"></A></td>
				</tr>
				<tr>
					<td height="10" colspan="2"></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="1" bgcolor="#9EB8E4"></td>
			</tr>
			[ENDNOLOGIN]
		</table>

	</div>
	<!-- #로그인 -->

</div>
<!-- #상세페이지 -->
