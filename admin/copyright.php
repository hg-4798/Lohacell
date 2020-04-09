<SCRIPT LANGUAGE="JavaScript">
<!--
function SendSMS() {
	window.open("sendsms.php","sendsmspop","width=220,height=350,scrollbars=no");
}
function MemberMemo() {
	window.open("member_memoconfirm.php","memopop","width=250,height=120,scrollbars=no");
}
//-->
</SCRIPT>


<!--{* Modal:S *}-->
<div class="modal">
    <div class="modal-dialog admin-modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="tools">
                    <button type="button" class="min" ><i class="fa fa-window-minimize "></i></button>
                    <button type="button" class="max" ><i class="fa fa-window-maximize"></i></button>
                    <button type="button" class="close" ><i class="fa fa-window-close"></i></button>
                </div>
                <h4 class="modal-title" id="adminModalLabel"></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!--{* Modal:E *}-->

<!--{* Loading:S *}-->
<div class="dimm-loading"><div id="loading"></div><p class="comment"></div></div>
<!--{* Loading:E *}-->

<? if($layout !='inc'){?>
<div class="admin_footer_wrap">
	<div class="info">
		<ul>
			<li>사업자번호:206-81-21131<span>l</span>통신판매 제 18-874호<span>l</span>서울시 강남구 논현동 228-5 B&M빌딩 4층 (주)커머스랩</li>
			<li>대표이사:김준태<span>l</span>개인정보보호정책 및 담당:김대영<span>l</span>전화:02-3448-0911<span>l</span>팩스 02-3448-0919<span>l</span>메일 help@duometis.co.kr</li>
			<li class="copy">Copyright(C)<span>AJASHOP&trade;</span>ALL Rights Reserved</li>
		</ul>
	</div>
</div>

<div style="display:none;">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td valign="top" background="/admin/images/copyright_bg_line.gif"><img src="/admin/images/space01.gif" height="1" border="0" width=10></td>
	<td width="100%" valign="top" background="/admin/images/copyright_bg_line.gif">
	<table cellpadding="0" cellspacing="0" width="970">
	<tr>
		<td>
		<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
			<TD><IMG SRC="/admin/images/copyright_img1.gif" WIDTH=26 HEIGHT=33 ALT=""></TD>
			<TD><a href="javascript:parent.topframe.webftp_popup();"><IMG SRC="/admin/images/copyright_ftp.gif" WIDTH=35 HEIGHT=33 ALT="" border=0></a></TD>
			<TD><a href="javascript:SendSMS();"><IMG SRC="/admin/images/copyright_sms.gif" WIDTH=41 HEIGHT=33 ALT="" border=0></a></TD>
			<TD><a href="javascript:MemberMemo();"><IMG SRC="/admin/images/copyright_memo.gif" WIDTH=48 HEIGHT=33 ALT="" border=0></a></TD>
			<TD><a href="javascript:parent.topframe.GoMenu(0,'sitemap.php');"><IMG SRC="/admin/images/copyright_sitemap.gif" WIDTH=52 HEIGHT=33 ALT="" border=0></a></TD>
			<TD width="100%" background="/admin/images/copyright_bg.gif">&nbsp;</TD>
			<TD><a href="main.php"><IMG SRC="/admin/images/copyright_home.gif" WIDTH=32 HEIGHT=33 ALT="" border=0></a></TD>
			<TD><a href="#top"><IMG SRC="/admin/images/copyright_top.gif" WIDTH=46 HEIGHT=33 ALT=""></a></TD>
			<TD><a href="javascript:history.go(-1);"><IMG SRC="/admin/images/copyright_back.gif" WIDTH=34 HEIGHT=33 ALT=""></a></TD>
			<TD><IMG SRC="/admin/images/copyright_img2.gif" WIDTH=22 HEIGHT=33 ALT=""></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td style="padding-top:8pt; padding-bottom:25pt;">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td align=center><img src="/admin/images/copyright_logo.gif" border="0"></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
</div>
<? } ?>


</body>
</html> 