<?php
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - <?=$setup[board_name]?></TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?php include($Dir."lib/style.php")?>
</HEAD>
<body<?=($_data->layoutdata["MOUSEKEY"][0]=="Y"?" oncontextmenu=\"return false;\"":"")?><?=($_data->layoutdata["MOUSEKEY"][1]=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=($_data->layoutdata["MOUSEKEY"][2]=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>
<?php include ($Dir.MainDir.$_data->menu_type.".php") ?>
<?php
$left_name=end(explode('_',$setup['board_skin']));

/*


if($mypageid && strpos($left_name,"TEM")!==false){
	include ($Dir.FrontDir."mypage_".$left_name."_left.php");
}
?>

<?if($_data->icon_type == 'tem_001'){?>
<div id="main_wrap">
	<div id="container">
		<? include $Dir.FrontDir."customer_menu.php";?> 
		
		
<?}else{?>
	<?if(strpos($_data->icon_type,"tem")!==false && $mypageid){	?>
	<div class="main_wrap">
		<div class="container">
			<div class="right_section">
				<div class="right_section">
					<div class="right_article_wrap">
						<div class="right_article">
							<!-- 주소복사 -->
							<p class="local_copy"><span><a href="<?=$Dir.MainDir?>main.php">홈</a> > <a href="mypage.php">마이페이지</a> > 상품문의</span></p>
							<!-- #주소복사 -->
							<h1>상품 Q&A</h1>
							<table border=0 cellpadding=0 cellspacing=0 width=100% >
							<tr>
								<td align=center>
									<!-- 게시판 타이틀 및 바로가기 링크 -->
									<?=MakeBoardTop($setup);?>
	<?}else if(strpos($_data->icon_type,"tem")!==false){?>
	<div class="main_wrap">
		<div class="container">
			<div class="left_lnb">

				<div class="lnb_wrap">
					<div class="lnb">
						<h1>커뮤니티</h1>
						<ul>
						<?
							$board_qry="select * from tblboardadmin ORDER BY date DESC ";
							$board_result=pmysql_query($board_qry);
							while($board_data=pmysql_fetch_object($board_result)){
								$checked[$setup['board']]="class='on'";
															
						?>
							<li><a href="<?=$Dir.BoardDir?>board.php?board=<?=$board_data->board?>" <?=$checked[$board_data->board]?>><?=$board_data->board_name?></a></li>
						<?}?>
						</ul>
					</div>
				</div>

			</div>
			<div class="right_section">

				<div class="right_article_wrap">
					<div class="right_article">
						<h1><?=$setup['board_name']?></h1>
						<div>
	<?}?>
<?}?>


*/

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<!-- 메인 컨텐츠 -->
			<div class="main_wrap">
				
				<?		
				$subTop_flag = 3;
				//include ($Dir.MainDir."sub_top.php");
				?>

				<div class="containerBody sub_skin">
					
					<!-- 고객센터 LNB -->
					<?php //include ("top_lnb.php");
						$lnb_flag = 5;
						include ($Dir.MainDir."lnb.php");
					?>