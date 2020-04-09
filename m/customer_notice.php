<?php
$Dir="../";
include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once($Dir."lib/shopdata.php");

if(!class_exists('Paging',false)) {
	include_once('../lib/paging.php');
}

include('./include/top.php');



$page_num       = $_POST[page_num];
$search_name       = $_POST['search_name'];

$sql = "SELECT  *
        FROM    tblcustomer_notice 
        WHERE   1=1
        AND     notice_type='Y'
        AND     viewyn ='Y' ";
if($search_name == null){
    $sql .= "ORDER BY regdt DESC";
} else {
    $sql .= "AND		title like '%{$search_name}%'
        	ORDER BY regdt DESC";
}

$paging = new New_Templet_paging($sql, 5,  5, 'GoPage', true);

$t_count = $paging->t_count;
$gotopage = $paging->gotopage;

$sql = $paging->getSql($sql);
$ret = pmysql_query($sql,get_db_conn());
//exdebug($sql);


include('./include/gnb.php');
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
function GoPage(block,gotopage) {
	document.idxform.block.value=block;
	document.idxform.gotopage.value=gotopage;
	document.idxform.submit();
}
function ViewNotice(num) {
	location.href="customer_notice_view.php?num="+num;
}

function SearchKeyWord (){
	document.form1.submit();
}

//-->
</SCRIPT>

<!-- 내용 -->
<div id="page">
	<main id="content" class="subpage">
		
		<section class="page_local">
			<h2 class="page_title">
				<a href="javascript:history.back();" class="prev">이전페이지</a>
				<span>공지사항</span>
			</h2>
			
		</section><!-- //.page_local -->

		<section class="cs_notice sub_bdtop">
			<form name="form1" action="customer_notice.php" method="POST">
			<div class="board_search">
				<div class="input_addr">
					<input type="text" name="search_name">
					<div class="btn_addr"><a href="javascript:SearchKeyWord();" class="btn-point h-input">검색</a></div>
				</div>
			</div><!-- //.board_search -->
			</form>
			<table class="th-top">
				<colgroup>
					<col style="width:auto;">
					<col style="width:22.35%;">
				</colgroup>
				<thead>
					<tr>
						<th>제목</th>
						<th>등록일</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				$cnt=0;
				if ($t_count > 0) {	
					while($row = pmysql_fetch_object($ret)) {
						$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
                        $reg_date = substr($row->regdt,0,4)."-".substr($row->regdt,4,2)."-".substr($row->regdt,6,2);
						echo "<tr>\n";
						echo "<td><a href=\"javascript:ViewNotice('{$row->no}');\" class=\"subject\">{$row->title}</a></td>\n";
						echo "<td><span class=\"brightest\">{$reg_date}</span></td>\n";
						echo "</tr>\n";
						
					}
				} else {
					echo "<tr>";
					echo "<td colspan=\"2\" class=\"none\">검색결과가 없습니다.</td>";
					echo "</tr>";
				}		
				?>
					<!-- [D] 게시물이 없는 경우 -->
					<!-- <tr>
						<td colspan="2" class="none">검색결과가 없습니다.</td>
					</tr>
					<tr>
						<td colspan="2" class="none">등록된 게시물이 없습니다.</td>
					</tr> -->
					<!-- //[D] 게시물이 없는 경우 -->
				</tbody>
			</table><!-- //.th-top -->

			<div class="list-paginate mt-20">
				<?=$paging->a_prev_page.$paging->print_page.$paging->a_next_page?>
			</div>

		</section><!-- //.cs_notice -->

	</main>
</div>
<!-- //내용 -->

<?
include('./include/bottom.php');
?>