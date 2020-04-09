<?php // hspark
if($MenuCode=="nomenu") {
	echo "<html></head><body onload=\"alert('해당 페이지 접근권한이 없습니다');\"></body></html>";
	exit;
}

include_once("header.php");
?>
	<div class="content-wrap">
	<div class="table_style04" style="padding-top: 100px;">
		<table border=0 cellpadding=0 cellspacing=0 width=100%>

			<tr>
				<td align=center><img src="/admin/images/acessno.gif" height="183" border="0" width="414"></td>
			</tr>
		</table>

	</div>
	</div>
<?php 
include("copyright.php");