<?php 
$listnum = 5;
//$listnum = 2;

#####날짜 셋팅 부분
$s_year=(int)$_POST["s_year"];
$s_month=(int)$_POST["s_month"];
$s_day=(int)$_POST["s_day"];

$e_year=(int)$_POST["e_year"];
$e_month=(int)$_POST["e_month"];
$e_day=(int)$_POST["e_day"];

$day_division = $_POST['day_division'];

$limitpage = $_POST['limitpage'];

if($e_year==0) $e_year=(int)date("Y");
if($e_month==0) $e_month=(int)date("m");
if($e_day==0) $e_day=(int)date("d");

$etime=strtotime("$e_year-$e_month-$e_day");

$stime=strtotime("$e_year-$e_month-$e_day -1 month");
if($s_year==0) $s_year=(int)date("Y",$stime);
if($s_month==0) $s_month=(int)date("m",$stime);
if($s_day==0) $s_day=(int)date("d",$stime);

$strDate1 = date("Y-m-d",strtotime("$s_year-$s_month-$s_day"));
$strDate2 = date("Y-m-d",$etime);

$whereDate1 = str_replace("-", "", $strDate1) . "000000";
$whereDate2 = str_replace("-", "", $strDate2) . "235959";

// 작성하지 않은 리뷰 리스트

$sql  = "SELECT tblResult.* ";
$sql .= "FROM ";
$sql .= "   ( ";
$sql .= "       SELECT a.*, b.regdt  ";
$sql .= "       FROM tblorderproduct a LEFT JOIN tblorderinfo b ON a.ordercode = b.ordercode ";
$sql .= "       WHERE b.id = '" . $_ShopInfo->getMemid()  . "' and ( (b.oi_step1 = 3 AND b.oi_step2 = 0) OR (b.oi_step1 = 4 AND b.oi_step2 = 0) ) ";
$sql .= "       AND ( b.regdt >= '{$whereDate1}' AND b.regdt <= '{$whereDate2}' ) ";
$sql .= "       ORDER BY a.idx DESC ";
$sql .= "   ) AS tblResult LEFT ";
$sql .= "   OUTER JOIN tblproductreview tpr ON tblResult.productcode = tpr.productcode and tblResult.ordercode = tpr.ordercode and tblResult.idx = tpr.productorder_idx ";
$sql .= "WHERE tpr.productcode is null ";
$sql .= "ORDER BY tblResult.idx desc ";

echo $sql . "<br/>";

//$paging = new New_Templet_paging($sql,10,5,'GoPage',true);
$paging = new amg_Paging($sql, 10, $listnum, 'GoPageAjax', '1');
//echo $sql;
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;

$sql = $paging->getSql($sql);
$result = pmysql_query($sql,get_db_conn());
?>

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


<div class="containerBody sub-page">

	<div class="breadcrumb">
		<ul>
			<li><a href="/">HOME</a></li>
			<li><a href="mypage.php">MY PAGE</a></li>
			<li class="on"><a>상품리뷰</a></li>
		</ul>
	</div>
	
	<!-- LNB -->
	<div class="left_lnb">
		<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
		<!---->
	</div><!-- //LNB -->

	<!-- right_section -->
	<div class="right_section mypage-content-wrap">
				
		<div class="my-review-list">

			<div class="mypage-title">상품리뷰 작성<span id="product_review_write_count">Total(<?=number_format($t_count)?>)</span></div>
			<!-- 날짜 설정 -->
			<form id="date_1" name="date_1">
			<div class="date_find_wrap">
				<ul class="date_setting">
					<li class="title">기간별 조회</li>
					<li class="date">
						<?
							if(!$day_division) $day_division = '1MONTH';

						?>
						<?foreach($arrSearchDate as $kk => $vv){?>
							<?
								$dayClassName = "";
								if($day_division != $kk){
									$dayClassName = 'btn_white_s';
								}else{
									$dayClassName = 'btn_black_s';
								}
							?>
							<a href="Javascript:;" class="<?=$dayClassName?>" onClick = "GoSearch2('<?=$kk?>', this, 'date_1')"><?=$vv?></a>
						<?}?>
						
					</li>
					<li class="title">일자별 조회</li>
					<li class="date">
						<div class="input_bg"><input type="text" name="date1" id="" value="<?=$strDate1?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> ~ 
						<div class="input_bg"><input type="text" name="date2" id="" value="<?=$strDate2?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> &nbsp;&nbsp;
						<a href="javascript:CheckForm('date_1');" class="btn-dib-function"><span>SEARCH</span></a>
					</li>
					
				</ul>
			</div>
                <input type=hidden name=s_year value="<?=$s_year?>">
                <input type=hidden name=s_month value="<?=$s_month?>">
                <input type=hidden name=s_day value="<?=$s_day?>">
                <input type=hidden name=e_year value="<?=$e_year?>">
                <input type=hidden name=e_month value="<?=$e_month?>">
                <input type=hidden name=e_day value="<?=$e_day?>">
                <input type=hidden name=day_division value="<?=$day_division?>">
                <input type=hidden name=mode value="1">
			</form><!-- //날짜 설정 -->
			
			<table class="th-top util top-line-none">
				<caption>나의 작성 리뷰리스트</caption>
				<colgroup>
					<col style="width:135px" >
					<col style="width:100px" >
					<col style="width:auto" >
					<col style="width:130px" >
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문일</th>
						<th scope="col" colspan="2">상품정보</th>
						<th scope="col">리뷰작성</th>
					</tr>
				</thead>
				<tbody id="product_review_write">
                    <?
                        $cnt = 0;
                        while ( $row = pmysql_fetch_object($result) ) { 
                            // 상품 정보
                            $sub_sql  = "SELECT *, b.brandname "; 
                            $sub_sql .= "FROM tblproduct a LEFT JOIN tblproductbrand b ON a.brand = b.bridx ";
                            $sub_sql .= "WHERE a.productcode = '" . $row->productcode . "' ";
                            $sub_row = pmysql_fetch_object(pmysql_query($sub_sql));

                            // 옵션 정보
                            $arrOptions = array();

                            // 옵션1
                            if ( !empty($sub_row->option1) && !empty($row->opt1_name) ) {
                                array_push($arrOptions, $sub_row->option1 . " : " . $row->opt1_name);
                            }

                            // 옵션2
                            if ( !empty($sub_row->option2) && !empty($row->opt2_name) ) {
                                array_push($arrOptions, $sub_row->option2 . " : " . $row->opt2_name);
                            }

                            // 수량
                            if ( !empty($row->quantity) ) {
                                array_push($arrOptions, "수량 : " . number_format($row->quantity) . "개");
                            }

                            // 주문일
                            $order_date = substr($row->regdt, 0, 3) . "-" . substr($row->regdt, 4, 2) . "-" . substr($row->regdt, 6, 2);
                            if ( empty($row->regdt) ) {
                                $order_date = substr($row->ordercode, 0, 3) . "-" . substr($row->ordercode, 4, 2) . "-" . substr($row->ordercode, 6, 2);
                            }
                    ?>

					<tr>
						<td><?=$order_date?></td>
						<td><img class="img-size-mypage" src="/data/shopimages/product/<?=$sub_row->tinyimage?>"></td>
						<td class="ta-l">
							<span class="brand-color"><?=$sub_row->brandname?></span><br>
							<span><?=$sub_row->productname?></span><br>
							<span><?=implode(" / ", $arrOptions)?></span>
						</td>
						<td><a href="javascript:;" onClick="javascript:send_review_write_page('<?=$row->productcode?>', '<?=$row->ordercode?>', '<?=$row->idx?>');" class="btn-dib-line"><span>리뷰쓰기</span></a></td>
					</tr>
    
                    <?
                            $cnt++;
                        } // end of while
                        pmysql_free_result($result);

                        if ( $cnt == 0 ) { 
                            echo "<tr><td colspan='4'>리뷰작성 가능한 내용이 없습니다.</td></tr>";
                        }
                    ?>
				</tbody>
			</table>

			<div class="paging" id="product_review_write_paging">
	    		<?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
			</div>

		</div><!-- //.my-review-list -->

<?
// ===============================================================================================================
// 작성한 상품리뷰
// ===============================================================================================================

// 작성한 리뷰 리스트
$sql  = "SELECT tpr.*, tblResult.* ";
$sql .= "FROM ";
$sql .= "   ( ";
$sql .= "   SELECT a.*, b.regdt ";
$sql .= "   FROM tblorderproduct a LEFT JOIN tblorderinfo b ON a.ordercode = b.ordercode ";
$sql .= "   WHERE b.id = '" . $_ShopInfo->getMemid()  . "' and ( (b.oi_step1 = 3 AND b.oi_step2 = 0) OR (b.oi_step1 = 4 AND b.oi_step2 = 0) ) ";
$sql .= "   ORDER BY a.idx DESC ";
$sql .= "   ) AS tblResult LEFT JOIN tblproductreview tpr ON tblResult.productcode = tpr.productcode and tblResult.ordercode = tpr.ordercode and tblResult.idx = tpr.productorder_idx ";
$sql .= "WHERE tpr.id = '" . $_ShopInfo->getMemid() . "' AND ( tpr.date >= '{$whereDate1}' AND tpr.date <= '{$whereDate2}' ) ";
$sql .= "ORDER BY tpr.num desc ";

//$paging = new New_Templet_paging($sql,10,5,'GoPage',true);
$paging = new amg_Paging($sql, 10, $listnum, 'GoPageAjax', '2');
//echo $sql;
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;

$sql = $paging->getSql($sql);
$result = pmysql_query($sql,get_db_conn());

?>
		<div class="my-review-write-list">

			<div class="mypage-title">작성한 상품리뷰 <span id="product_writed_review_count">Total(<?=number_format($t_count)?>)</span></div>
			<!-- 날짜 설정 -->
			<form id="date_2" name="date_2">
			<div class="date_find_wrap">
				<ul class="date_setting">
					<li class="title">기간별 조회</li>
					<li class="date">
						<?
							if(!$day_division) $day_division = '1MONTH';

						?>
						<?foreach($arrSearchDate as $kk => $vv){?>
							<?
								$dayClassName = "";
								if($day_division != $kk){
									$dayClassName = 'btn_white_s';
								}else{
									$dayClassName = 'btn_black_s';
								}
							?>
							<a href="Javascript:;" class="<?=$dayClassName?>" onClick = "GoSearch2('<?=$kk?>', this, 'date_2')"><?=$vv?></a>
						<?}?>
						
					</li>
					<li class="title">일자별 조회</li>
					<li class="date">
						<div class="input_bg"><input type="text" name="date1" id="" value="<?=$strDate1?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> ~ 
						<div class="input_bg"><input type="text" name="date2" id="" value="<?=$strDate2?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> &nbsp;&nbsp;
						<a href="javascript:CheckForm('date_2');" class="btn-dib-function"><span>SEARCH</span></a>
					</li>
					
				</ul>
			</div>

                <input type=hidden name=s_year value="<?=$s_year?>">
                <input type=hidden name=s_month value="<?=$s_month?>">
                <input type=hidden name=s_day value="<?=$s_day?>">
                <input type=hidden name=e_year value="<?=$e_year?>">
                <input type=hidden name=e_month value="<?=$e_month?>">
                <input type=hidden name=e_day value="<?=$e_day?>">
                <input type=hidden name=day_division value="<?=$day_division?>">
                <input type=hidden name=mode value="2">
			</form><!-- //날짜 설정 -->
			
			<table class="th-top util top-line-none">
				<caption>나의 작성 리뷰리스트</caption>
				<colgroup>
					<col style="width:135px" >
					<col style="width:100px" >
					<col style="width:auto" >
					<col style="width:130px" >
					<col style="width:130px" >
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문일</th>
						<th scope="col" colspan="2">상품정보</th>
						<th scope="col">작성일</th>
						<th scope="col">평점</th>
					</tr>
				</thead>
				<tbody id="product_writed_review">

<?
    $cnt = 0;
    while($row=pmysql_fetch_object($result)) { 

        // 상품 정보
        $sub_sql  = "SELECT *, b.brandname "; 
        $sub_sql .= "FROM tblproduct a LEFT JOIN tblproductbrand b ON a.brand = b.bridx ";
        $sub_sql .= "WHERE a.productcode = '" . $row->productcode . "' ";
        $sub_row = pmysql_fetch_object(pmysql_query($sub_sql));

        // 옵션 정보
        $arrOptions = array();

        // 옵션1
        if ( !empty($sub_row->option1) && !empty($row->opt1_name) ) {
            array_push($arrOptions, $sub_row->option1 . " : " . $row->opt1_name);
        }

        // 옵션2
        if ( !empty($sub_row->option2) && !empty($row->opt2_name) ) {
            array_push($arrOptions, $sub_row->option2 . " : " . $row->opt2_name);
        }

        // 수량
        if ( !empty($row->quantity) ) {
            array_push($arrOptions, "수량 : " . number_format($row->quantity) . "개");
        }

        // 별점 
        $marks = '';
        for ($i = 0; $i < $row->marks; $i++) {
            $marks .= '★';
        }

        // 주문일
        $order_date = $row->regdt;
        if ( empty($order_date) ) {
            $order_date = substr($row->ordercode, 0, 3) . "-" . substr($row->ordercode, 4, 2) . "-" . substr($row->ordercode, 6, 2);
        }

        // 작성일
        $write_date = substr($row->date, 0, 4) . "-" . substr($row->date, 4, 2) . "-" . substr($row->date, 6, 2);

        // 업로드 이미지 정보
        $arrUpFile = array();

        if ( !empty($row->upfile) ) { array_push($arrUpFile, $row->upfile); }
        if ( !empty($row->upfile2) ) { array_push($arrUpFile, $row->upfile2); }
        if ( !empty($row->upfile3) ) { array_push($arrUpFile, $row->upfile3); }
        if ( !empty($row->upfile4) ) { array_push($arrUpFile, $row->upfile4); }

        // 리뷰 제목/내용
        $review_title   = $row->subject;
        $review_content = str_replace("\n", "<br/>", $row->content);
?>
					<tr class="my-write-review" ids="<?=$row->num?>">
						<td><?=$order_date?></td>
						<td><img class="img-size-mypage" src="/data/shopimages/product/<?=$sub_row->tinyimage?>"></td>
						<td class="ta-l">
							<span class="brand-color"><?=$sub_row->brandname?></span><br>
							<span><?=$sub_row->productname?></span><br>
							<span><?=implode(" / ", $arrOptions)?></span>
						</td>
						<td><?=$write_date?></td>
						<td><?=$marks?></td>
					</tr>
					<tr class="open-content" style="display:none;">
						<td colspan="5">
							<div class="list-tr-open">
								<div class="review_contents my-wirte">
									<?=$review_title?> <br/><br/>

                                    <?
                                        foreach ( $arrUpFile as $key => $val ) {
                                            echo "<img src='" . $Dir.DataDir."shopimages/review/" . $val . "' /> <br/>";
                                        }
                                        echo "<br/>";
                                    ?>
                                    <?=$review_content?>
									<div class="btn-place">
										<button class="btn-dib-line " type="button" onClick="javascript:send_review_write_page('<?=$row->productcode?>', '<?=$row->ordercode?>', '<?=$row->idx?>', '<?=$row->num?>');"><span>수정</span></button>
										<button class="btn-dib-line " type="button" onClick="javascript:delete_review('<?=$row->num?>');"><span>삭제</span></button>
									</div>
								</div>
							</div>
						</td>
					</tr>
<?
        $cnt++;
    } 

    if ( $cnt == 0 ) {
        echo "<tr><td colspan='5'>작성한 상품리뷰가 없습니다.</td></tr>";
    }
?>

				</tbody>
			</table>

			<div class="paging" id="product_writed_review_paging">
	    		<?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
			</div>

		</div><!-- //.my-review-write-list -->

	</div><!-- //.right_section -->
</div><!-- //.containerBody -->

<!-- #상세페이지 -->
<form name=idxform method="POST" action="<?=$_SERVER['PHP_SELF']?>">
<input type=hidden name=block>
<input type=hidden name=gotopage>
<input type=hidden name=idx value=<?=$idx?>>
</form>

<form name=reviewWriteForm method="POST" action="mypage_review_write.php">
<input type="hidden" name="productcode" />
<input type="hidden" name="ordercode" />
<input type="hidden" name="productorder_idx" />
<input type="hidden" name="review_num" />
</form>

<form name=form2 method=post action="<?=$_SERVER['PHP_SELF']?>">
<input type=hidden name=block value="<?=$block?>">
<input type=hidden name=gotopage value="<?=$gotopage?>">
<input type=hidden name=ordgbn value="<?=$ordgbn?>">
<input type=hidden name=limitpage class = 'CLS_limit_page_val' value="<?=$limitpage?>">
<input type=hidden name=s_year value="<?=$s_year?>">
<input type=hidden name=s_month value="<?=$s_month?>">
<input type=hidden name=s_day value="<?=$s_day?>">
<input type=hidden name=e_year value="<?=$e_year?>">
<input type=hidden name=e_month value="<?=$e_month?>">
<input type=hidden name=e_day value="<?=$e_day?>">
<input type=hidden name=day_division value="<?=$day_division?>">
</form>

</table>

<? include($Dir."admin/calendar_join.php");?>

<SCRIPT LANGUAGE="JavaScript">
<!--

var listnum = "<?=$listnum?>";

function send_review_write_page(productcode, ordercode, productorder_idx, review_num) {
    if ( review_num == undefined ) {
        review_num = 0;
    }

    var frm = document.reviewWriteForm;

    frm.productcode.value = productcode;
    frm.ordercode.value = ordercode;
    frm.productorder_idx.value = productorder_idx;
    frm.review_num.value = review_num;
    frm.submit();
}

function delete_review(review_num) {
    if ( confirm("삭제하시겠습니까?") ) {
        $.ajax({
            type        : "GET", 
            url         : "ajax_delete_review.php", 
            contentType : "application/x-www-form-urlencoded; charset=UTF-8",
            data        : { review_num : review_num }
        }).done(function ( data ) {
            if ( data === "SUCCESS" ) {
                alert("리뷰가 삭제되었습니다.");
                location.href = "/front/mypage_review.php";
            }
        });
    }
}

function GoPageAjax(block,gotopage,mode,mode,start_date,end_date) {
    var s_date = '';
    var e_date = '';
    
    if ( start_date != undefined ) {
        s_date = start_date;
    }

    if ( end_date != undefined ) {
        e_date = end_date;
    }

    var params = {
        block : block,
        gotopage : gotopage,
        listnum : listnum,
        mode : mode,
        start_date : s_date,
        end_date : e_date,
    };

    $.ajax({
        type        : "GET", 
        url         : "ajax_get_myreview_list.php", 
        contentType : "application/x-www-form-urlencoded; charset=UTF-8",
        data        : params
    }).done(function ( data ) {
        var arrData = data.split("|||");

        if ( mode === '1' ) {
            $("#product_review_write").html(arrData[0]);
            $("#product_review_write_paging").html(arrData[1]);
            $("#product_review_write_count").html("Total(" + arrData[2] + ")");
        } else {
            $("#product_writed_review").html(arrData[0]);
            $("#product_writed_review_paging").html(arrData[1]);
            $("#product_writed_review_count").html("Total(" + arrData[2] + ")");
        }

    });
}

//-->
</SCRIPT>

<script LANGUAGE="JavaScript">
<!--
var NowYear=parseInt(<?=date('Y')?>);
var NowMonth=parseInt(<?=date('m')?>);
var NowDay=parseInt(<?=date('d')?>);
var NowTime=parseInt(<?=time()?>);

function getMonthDays(sYear,sMonth) {
	var Months_day = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31)
	var intThisYear = new Number(), intThisMonth = new Number();
	datToday = new Date();													// 현재 날자 설정
	
	intThisYear = parseInt(sYear);
	intThisMonth = parseInt(sMonth);
	
	if (intThisYear == 0) intThisYear = datToday.getFullYear();				// 값이 없을 경우
	if (intThisMonth == 0) intThisMonth = parseInt(datToday.getMonth())+1;	// 월 값은 실제값 보다 -1 한 값이 돼돌려 진다.
	

	if ((intThisYear % 4)==0) {													// 4년마다 1번이면 (사로나누어 떨어지면)
		if ((intThisYear % 100) == 0) {
			if ((intThisYear % 400) == 0) {
				Months_day[2] = 29;
			}
		} else {
			Months_day[2] = 29;
		}
	}
	intLastDay = Months_day[intThisMonth];										// 마지막 일자 구함
	return intLastDay;
}

function ChangeDate(gbn) {
	year=document.form1[gbn+"_year"].value;
	month=document.form1[gbn+"_month"].value;
	totdays=getMonthDays(year,month);

	MakeDaySelect(gbn,1,totdays);
}

function MakeDaySelect(gbn,intday,totdays) {
	document.form1[gbn+"_day"].options.length=totdays;
	for(i=1;i<=totdays;i++) {
		var d = new Option(i);
		document.form1[gbn+"_day"].options[i] = d;
		document.form1[gbn+"_day"].options[i].value = i;
	}
	document.form1[gbn+"_day"].selectedIndex=intday;
}

function GoSearch2(gbn, obj, frm_id) {
    var frm = $("#" + frm_id)[0];

	$(".btn_white_s, .btn_black_s").attr('class','btn_white_s');
	$(obj).attr('class','btn_black_s');

	var s_date = new Date(NowTime*1000);
	switch(gbn) {
		case "TODAY":
			break;
		case "1WEEK":
			s_date.setDate(s_date.getDate()-7);
			break;
		case "15DAY":
			s_date.setDate(s_date.getDate()-15);
			break;
		case "1MONTH":
			s_date.setMonth(s_date.getMonth()-1);
			break;
		case "3MONTH":
			s_date.setMonth(s_date.getMonth()-3);
			break;
		case "6MONTH":
			s_date.setMonth(s_date.getMonth()-6);
			break;
		case "12MONTH":
			s_date.setFullYear(s_date.getFullYear()-1);
			break;
		default :
			break;
	}
	e_date = new Date(NowTime*1000);

	//======== 시작 날짜 셋팅 =========//
	var s_month_str = str_pad_right(parseInt(s_date.getMonth())+1);
	var s_date_str = str_pad_right(parseInt(s_date.getDate()));

	// 폼에 셋팅
	frm.s_year.value = s_date.getFullYear();
	frm.s_month.value = s_month_str;
	frm.s_day.value = s_date_str;
	//날짜 칸에 셋팅
	var s_date_full = s_date.getFullYear()+"-"+s_month_str+"-"+s_date_str;
	frm.date1.value=s_date_full;
	//======== //시작 날짜 셋팅 =========//
	
	//======== 끝 날짜 셋팅 =========//
	var e_month_str = str_pad_right(parseInt(e_date.getMonth())+1);
	var e_date_str = str_pad_right(parseInt(e_date.getDate()));

	// 폼에 셋팅
	frm.e_year.value = e_date.getFullYear();
	frm.e_month.value = e_month_str;
	frm.e_day.value = e_date_str;

	frm.day_division.value = gbn;
	
	//날짜 칸에 셋팅
	var e_date_full = e_date.getFullYear()+"-"+e_month_str+"-"+e_date_str;
	frm.value=e_date_full;
	//======== //끝 날짜 셋팅 =========//
	
	/*
	document.form1.s_year.value=parseInt(s_date.getFullYear());
	document.form1.s_month.value=parseInt(s_date.getMonth());
	document.form1.e_year.value=NowYear;
	document.form1.e_month.value=NowMonth;
	totdays=getMonthDays(parseInt(s_date.getFullYear()),parseInt(s_date.getMonth()));
	MakeDaySelect("s",parseInt(s_date.getDate()),totdays);
	totdays=getMonthDays(NowYear,NowMonth);
	MakeDaySelect("e",NowDay,totdays);
	document.form1.submit();
	*/
}

function str_pad_right(num){
	
	var str = "";
	if(num<10){
		str = "0"+num;
	}else{
		str = num;
	}
	return str;

}

function isNull(obj){
	return (typeof obj !="undefined" && obj != "")?false:true;
}

function CheckForm(frm_id) {
    var frm = $("#" + frm_id)[0];

	//##### 시작날짜 셋팅
	var sdatearr = "";
	var str_sdate = frm.date1.value;
	if(!isNull(frm.date1.value)){
		sdatearr = str_sdate.split("-");
		if(sdatearr.length==3){
		// 폼에 셋팅
			frm.s_year.value = sdatearr[0];
			frm.s_month.value = sdatearr[1];
			frm.s_day.value = sdatearr[2];
		}
	}
	var s_date = new Date(parseInt(sdatearr[0]),parseInt(sdatearr[1]),parseInt(sdatearr[2]));
	
	//##### 끝 날짜 셋팅
	var edatearr = "";
	var str_edate = frm.date2.value;
	if(!isNull(frm.date2.value)){
		edatearr = str_edate.split("-");
		if(edatearr.length==3){
		// 폼에 셋팅
			frm.e_year.value = edatearr[0];
			frm.e_month.value = edatearr[1];
			frm.e_day.value = edatearr[2];
		}
	}
	var e_date = new Date(parseInt(edatearr[0]),parseInt(edatearr[1]),parseInt(edatearr[2]));

	if(s_date>e_date) {
		alert("조회 기간이 잘못 설정되었습니다. 기간을 다시 설정해서 조회하시기 바랍니다.");
		return;
	}
	
	//frm.submit();

    var mode = frm.mode.value;
    var start_date = frm.s_year.value + frm.s_month.value + frm.s_day.value;
    var end_date = frm.e_year.value + frm.e_month.value + frm.e_day.value;

    GoPageAjax(0, 0, mode, mode, start_date, end_date);
	
	/*
	s_year=document.form1.s_year.value;
	s_month=document.form1.s_month.value;
	s_day=document.form1.s_day.value;
	s_date = new Date(parseInt(s_year), parseInt(s_month), parseInt(s_day));

	e_year=document.form1.e_year.value;
	e_month=document.form1.e_month.value;
	e_day=document.form1.e_day.value;
	e_date = new Date(parseInt(e_year), parseInt(e_month), parseInt(e_day));
	tmp_e_date = new Date(parseInt(e_year), parseInt(e_month)-6, parseInt(e_day));

	if(s_date<tmp_e_date) {
		alert("조회 기간이 6개월을 넘었습니다. 6개월 이내로 설정해서 조회하시기 바랍니다.");
		return;
	}
	
	document.form1.submit();
	*/
}

function GoOrdGbn(temp) {
	document.form1.ordgbn.value=temp;
	document.form1.submit();
}

function OrderDetail(ordercode) {
	document.detailform.ordercode.value=ordercode;
	document.detailform.submit();
}
function DeliSearch(deli_url){
	window.open(deli_url,"배송추적","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizeble=yes,copyhistory=no,width=600,height=550");
}
function DeliveryPop(ordercode) {
	document.deliform.ordercode.value=ordercode;
	window.open("about:blank","delipop","width=600,height=370,scrollbars=no");
	document.deliform.submit();
}

function GoPage(block,gotopage) {
	document.form2.block.value=block;
	document.form2.gotopage.value=gotopage;
	document.form2.submit();
}
-->
</script>

<SCRIPT>
$(document).ready(function(){

	$("input[name='date1'], input[name='date2']").click(function(){
		Calendar(event);
	})
	$(".CLS_cal_btn").click(function(){
		$(this).prev().find("input[type='text']").focus();
		$(this).prev().find("input[type='text']").trigger('click');
	})
		//limitpage
	$(".CLS_limit_page").click(function(){
		$(".CLS_limit_page_val").val($(this).attr('idx'));
		document.form2.submit();
	})
});
</SCRIPT>


