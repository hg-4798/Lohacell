<?php 
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

$search=$_GET[search];
$searchAll=$_GET[searchAll];
$name=$_GET[name];
$subject=$_GET[subject];
$contents=$_GET[contents];


if($searchAll){
	$where[]="(subject like '%".$search."%' or content like '%".$search."%')";

}else{
	if($subject)$where[]="subject like '%".$search."%'";
	if($contents)$where[]="content like '%".$search."%'";
}

$sql = "SELECT COUNT(*) as t_count FROM tblnotice ";
if(count($where))$sql.=" where ".implode(" and ", $where);
$paging = new Tem001_Paging($sql,10,10);
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE><?=$_data->shoptitle?> - 공지사항</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META name="description" content="<?=(ord($_data->shopdescription)?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?php include($Dir."lib/style.php")?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function GoPage(block,gotopage) {
	document.idxform.block.value=block;
	document.idxform.gotopage.value=gotopage;
	document.idxform.submit();
}
function ViewNotice(date) {
	location.href="customer_notice_view.php?date="+date;
}

//-->
</SCRIPT>
</HEAD>

<?php  include ($Dir.MainDir.$_data->menu_type.".php") ?>

<body<?=($_data->layoutdata["MOUSEKEY"][0]=="Y"?" oncontextmenu=\"return false;\"":"")?><?=($_data->layoutdata["MOUSEKEY"][1]=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=($_data->layoutdata["MOUSEKEY"][2]=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>


<!-- start container -->
<div id="container">

<? include $Dir.FrontDir."customer_menu.php";?> 
	<div class="cs_contents">
		<div class="title">
			<h2><img src="<?=$Dir?>image/community/title_notice.gif" alt="공지사항" /></h2>
			<div class="path">
				<ul>
					<li class="home">홈&nbsp;&gt;&nbsp;</li>
					<li>커뮤니티&nbsp;&gt;&nbsp;</li>
					<li>공지사항</li>
				</ul>
			</div>
		</div>
<div class="sub_title"><img src="<?=$Dir?>image/community/community_title_01.png" alt="공지사항" /></div>

<div class="board_search_block">
       <div class="search_board">
	   	<form method=get name=frm action=<?=$PHP_SELF?>>
			<ul>
				<li ><input type=checkbox name="searchAll" <?if($searchAll=='on'){echo "checked";}?> id = 'searchAll' checked onClick = 'findAll();' class="boardsearch_check">&nbsp;통합검색</li>
				<li><input type=checkbox name="subject" <?if($subject=='on'){echo "checked";}?> class="boardsearch_check">&nbsp;제목</li>
				<li><input type=checkbox name="contents" <?if($contents=='on'){echo "checked";}?> class="boardsearch_check" >&nbsp;내용</li>
				<li><input name="search" value="<?=$search?>" class="boardsearch_input"></li>
				<li><a href="javascript:document.frm.submit();"><img src="<?=$Dir?>image/community/bt_search_board.gif"></a></li>
			</ul>
			
		</FORM>
			
		</div>
</div>
<div class="boardlist_warp">
<span class="total_articles">Total <font class="board_no"><?=$t_count?></font> Articles, <strong><?=$gotopage?></strong> of <strong><?=ceil($t_count/$setup[list_num])?></strong> Pages </span>
<div class="boardlist_bar">
             <ul>
<!--			 <li class="cell5"><img src="<?=$Dir?>image/community/community_bar01.gif" /></li>-->
			 <li class="cell10"><img src="<?=$Dir?>image/community/community_bar02.gif" /></li>
			 <li class="cell65"><img src="<?=$Dir?>image/community/community_bar03.gif" /></li>
			 <li class="cell10"><img src="<?=$Dir?>image/community/community_bar04.gif" /></li>
			 <li class="cell10"><img src="<?=$Dir?>image/community/community_bar05.gif" /></li>
			 <li class="cell5"><img src="<?=$Dir?>image/community/community_bar06.gif" /></li>
			 </ul>
</div>

<div class="boardlist_list">
<?php
		
		$sql = "SELECT date,subject,access FROM tblnotice ";
		if(count($where))$sql.=" where ".implode(" and ", $where);
		$sql.= "ORDER BY date DESC ";
		
		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=pmysql_fetch_object($result)) {
			$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

			$date = substr($row->date,0,4)."-".substr($row->date,4,2)."-".substr($row->date,6,2);
			$re_date="-";
			if(strlen($row->re_date)==14) {
				$re_date = substr($row->re_date,0,4)."-".substr($row->re_date,4,2)."-".substr($row->re_date,6,2);
			}

?>
			 <ul>
<!--			 <li class="cell5 boardcheckbox"><input type="checkbox" name="" value="" class="" /></li>-->
			 <li class="cell10 boardlist_no"><?=$number?></li>
			 <li class="cell65 boardtitle"><A HREF="javascript:ViewNotice('<?=$row->date?>')"><?=strip_tags($row->subject)?></A></li>
			 <li class="cell10"><img src="<?=$Dir?>image/community/icon_admin.png" /></li>
			 <li class="cell10 boarddate"><?=$date?></li>
			 <li class="cell5 boardhit" ><?=$row->access?></li>
			 </ul>

<?
			$cnt++;
		}
		pmysql_free_result($result);
		if ($cnt==0) {
			echo "<ul><li style='width:850px'>문의 내역이 없습니다.</li></ul>";
		}
?>

<!--
			 <ul class="boardlist_notice">
			 <li class="cell5 boardcheckbox"><input type="checkbox" name="" value="" class="" /></li>
			 <li class="cell5"><img src="<?=$Dir?>image/community/icon_news.png" /></li>
			 <li class="cell65 boardtitle">[12월 EVENT] 에팩회원 5만명 돌파 !</li>
			 <li class="cell10"><img src="<?=$Dir?>image/community/icon_admin.png" /></li>
			 <li class="cell10 boarddate">2013-12-18</li>
			 <li class="cell5 boardhit" >44</li>
			 </ul>

			 <ul>
			 <li class="cell5 boardcheckbox"><input type="checkbox" name="" value="" class="" /></li>
			 <li class="cell5 boardlist_no">55</li>
			 <li class="cell65 boardtitle">[12월 EVENT] 에팩회원 5만명 돌파 !</li>
			 <li class="cell10"><img src="<?=$Dir?>image/community/icon_admin.png" /></li>
			 <li class="cell10 boarddate">2013-12-18</li>
			 <li class="cell5 boardhit" >44</li>
			 </ul>
-->
</div><!-- boardlist_list 끝 -->
</div><!-- boardlist_warp 끝 -->
	<div class="paging">
		<?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
	</div><!-- paging 끝 -->
	<!--
	<div class="board_bt_warp">
			<ul>
				<li><a href="board_view.html"><img src="<?=$Dir?>image/board/bt_mini_view.gif"></a></li>
				<li><a href="#"><img src="<?=$Dir?>image/board/bt_mini_list.gif"></a></li>
				<li><a href="#"><img src="<?=$Dir?>image/board/bt_mini_delete.gif"></a></li>
				<li><a href="board_write.html"><img src="<?=$Dir?>image/board/bt_mini_write_gray.gif"></a></li>
			</ul>
	</div>
	-->
</div>	<!-- cs_contents 끝 -->
</div><!-- //container 끝 -->

<div class="clearboth"></div>		
<!--footer start -->


<form name=idxform method=get action="<?=$_SERVER['PHP_SELF']?>">
<input type=hidden name=block>
<input type=hidden name=gotopage>
<input type=hidden name=searchAll value="<?=$searchAll?>">
<input type=hidden name=subject value="<?=$subject?>">
<input type=hidden name=contents value="<?=$contents?>">
<input type=hidden name=search value="<?=$search?>">
</form>

<!-- footer 시작 -->
<?php  include ($Dir."lib/bottom.php") ?>
</BODY>
</HTML>
