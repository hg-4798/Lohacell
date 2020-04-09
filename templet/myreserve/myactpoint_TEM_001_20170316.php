<?php
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

//exdebug($_POST);

$s_curtime=strtotime("$s_year-$s_month-$s_day");
$s_curdate=date("Ymd",$s_curtime)."000000";
$e_curtime=strtotime("$e_year-$e_month-$e_day 23:59:59");
$e_curdate=date("Ymd",$e_curtime)."235959";

//exdebug($s_curdate);
//exdebug($e_curdate);

$sql = "SELECT  pid, regdt, body, point, use_point, expire_date, tot_point 
        FROM    tblpoint_act  
        WHERE   mem_id = '".$_ShopInfo->getMemid()."' 
        AND     regdt >= '".$s_curdate."' AND regdt <= '".$e_curdate."' 
        ORDER BY pid DESC
        ";
$paging = new New_Templet_paging($sql, 10,  10, 'GoPage', true);
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;
//exdebug($t_count);
//exdebug($sql);

$sql = $paging->getSql($sql);
$result=pmysql_query($sql,get_db_conn());
//exdebug($sql);
?>
<script type="text/javascript">
<!--
$(document).ready(function(){
});


//-->
</script>
<style>
/** 달력 팝업 **/
.calendar_pop_wrap {position:relative; background-color:#FFF;}
.calendar_pop_wrap .calendar_con {position:absolute; top:0px; left:0px;width:247px; padding:10px; border:1px solid #b8b8b8; background-color:#FFF;}
.calendar_pop_wrap .calendar_con .month_select { text-align:center; background-color:#FFF; padding-bottom:10px;}
.calendar_pop_wrap .calendar_con .day {clear:both;border-left:1px solid #e4e4e4;}
.calendar_pop_wrap .calendar_con .day th {background:url('../admin/img/common/calendar_top_bg.gif') repeat-x; width:34px; font-size:11px; border-top:1px solid #9d9d9d;border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4; padding:6px 0px 4px;}
.calendar_pop_wrap .calendar_con .day th.sun {color:#ff0012;}
.calendar_pop_wrap .calendar_con .day td {border-right:1px solid #e4e4e4;border-bottom:1px solid #e4e4e4; background-color:#FFF; width:34px;  font-size:11px; text-align:center; font-family:tahoma;}
.calendar_pop_wrap .calendar_con .day td a {color:#35353f; display:block; padding:2px 0px;}
.calendar_pop_wrap .calendar_con .day td a:hover {font-weight:bold; color:#ff6000; text-decoration:none;}
.calendar_pop_wrap .calendar_con .day td.pre_month a {color:#fff; display:block; padding:3px 0px;}
.calendar_pop_wrap .calendar_con .day td.pre_month a:hover {text-decoration:none; color:#fff;}
.calendar_pop_wrap .calendar_con .day td.today {background-color:#52a3e7; }
.calendar_pop_wrap .calendar_con .day td.today a {color:#fff;}
.calendar_pop_wrap .calendar_con .close_btn {text-align:center; padding-top:10px;}
</style>

<?
 ?>
 <div id="contents">
 	  <!-- 네비게이션 -->
	<div class="top-page-local">
		<ul>
			<li><a href="/">HOME</a></li>
			<li><a href="<?=$Dir?>front/mypage.php">마이 페이지</a></li>
			<li class="on">Action 포인트</li>
		</ul>
	</div>
	<!-- //네비게이션-->
	<div class="inner">
		<main class="mypage_wrap"><!-- 페이지 성격에 맞게 클래스 구분 -->

			<!-- LNB -->
			<? include  "mypage_TEM01_left.php";  ?>
			<!-- //LNB -->

			<article class="mypage_content">
				<section class="mypage_main">
					<div class="title_box_border">
						<h3>Action 포인트</h3>
					</div>

					<!-- 게시판 목록 -->
					<div class="myboard mt-50">
						<div class="order_right">
							<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
							<div class="total">총 <?=number_format($t_count)?>건</div>
							<div class="date-sort clear">
								<div class="type month">
									<p class="title">기간별 조회</p>
								<?
									if(!$day_division) $day_division = '1MONTH';

								?>
								<?foreach($arrSearchDate as $kk => $vv){?>
									<?
										$dayClassName = "";
										if($day_division != $kk){
											$dayClassName = '';
										}else{
											$dayClassName = 'on';
										}
									?>
									<button type="button" class="<?=$dayClassName?>" onClick = "GoSearch2('<?=$kk?>', this)"><span><?=$vv?></span></button>
								<?}?>
								</div>
								<div class="type calendar">
									<p class="title">일자별 조회</p>
									<div class="box">
										<div><input type="text" title="일자별 시작날짜" name="date1" id="" value="<?=$strDate1?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
									</div>
									<span>-</span>
									<div class="box">
										<div><input type="text" title="일자별 시작날짜" name="date2" id="" value="<?=$strDate2?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
									</div>
								</div>
								<button type="button" class="btn-go" onClick="javascript:CheckForm();"><span>검색</span></button>
							</div>
						    </form>
						</div>
						<table class="th_top">
							<caption></caption>
							<colgroup>
								<col style="width:15%">
								<col style="width:auto">
								<col style="width:15%">
								<col style="width:15%">
								<col style="width:15%">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">날짜</th>
									<th scope="col">상세내역</th>
									<th scope="col">적립포인트</th>
									<th scope="col">사용포인트</th>
									<th scope="col">잔여포인트</th>
								</tr>
							</thead>
							<tbody>
<?
    $cnt=0;
    if ($t_count > 0) {
        while($row=pmysql_fetch_object($result)) {

            $regdt = substr($row->regdt,0,4).".".substr($row->regdt,4,2).".".substr($row->regdt,6,2);
?>
								<tr>
									<td><?=$regdt?></td>
									<td class="ta-l"><?=$row->body?></td>
									<td><? if( $row->point <= 0 ) { echo 0; } else { echo number_format( $row->point ); } ?></td>
									<td><? if( $row->point <= 0 ) { echo number_format( $row->point ); } else { echo 0; } ?></td>
									<td><?=number_format($row->tot_point)?></td>
								</tr>
<?
		    $cnt++;
		}
	} else {
?>
								<tr>
									<td colspan="7">내역이 없습니다.</td>
								</tr>
<?
	}
?>
							</tbody>
						</table>
						<div class="list-paginate mt-20"><?=$paging->a_prev_page.$paging->print_page.$paging->a_next_page?></div>
					</div>
					<!-- // 게시판 목록 -->

				</section>
			</article>
		</main>
	</div>
</div><!-- //#contents -->


<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>