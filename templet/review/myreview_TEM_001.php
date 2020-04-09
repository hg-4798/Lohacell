<?php
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

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

<?

if ( $isMobile ) {
    $s_curdate = date("YmdHis",strtotime("$s_year-$s_month-$s_day"));
    $e_curdate = date("Ymd235959",$etime);
} else {
	$s_curtime=strtotime("$s_year-$s_month-$s_day");
	$s_curdate=date("Ymd",$s_curtime)."000000";
	$s_curdate1=date("Y-m-d",$s_curtime)." 00:00:00";
	$e_curtime=strtotime("$e_year-$e_month-$e_day");
	$e_curdate=date("Ymd",$e_curtime)."999999";
	$e_curdate1=date("Y-m-d",$e_curtime)." 23:59:59";
}

# ====================================================================================================================================
# 작성하지 않은 리뷰 리스트
# 현재는 배송중부터 작성 가능하지만, 구매확정 이후 시점으로 변경해야 됨.2016-08-09 jhjeong
# ====================================================================================================================================
/*
$sql  = "SELECT tblResult.* ";
$sql .= "FROM ";
$sql .= "   ( ";
$sql .= "       SELECT  a.*, b.regdt  ";
$sql .= "       FROM    tblorderproduct a LEFT JOIN tblorderinfo b ON a.ordercode = b.ordercode ";
$sql .= "       WHERE   b.id = '" . $_ShopInfo->getMemid()  . "' ";
//$sql .= "       AND     ( a.op_step = 3 OR a.op_step = 4 ) ";     // 상태를 상품별로 변경 (2016.07.13 - 김재수 * 결제완료에서 리뷰쓰기를 했을경우 구매확정이 된 오류)
$sql .= "       AND     (a.op_step = 4 and a.order_conf = '1') ";   // 구매 확정 이후에 리뷰 작성하게 수정 2016-08-12
$sql .= "       AND     ( b.regdt >= '{$s_curdate}' AND b.regdt <= '{$e_curdate}' ) ";
$sql .= "       ORDER BY a.idx DESC ";
$sql .= "   ) AS tblResult LEFT ";
$sql .= "   OUTER JOIN tblproductreview tpr ON tblResult.productcode = tpr.productcode and tblResult.ordercode = tpr.ordercode and tblResult.idx = tpr.productorder_idx ";
$sql .= "WHERE tpr.productcode is null ";
$sql .= "ORDER BY tblResult.idx desc ";
*/
$sql ="SELECT oi.order_num, op.idx, op.productcode,op.option_code,op.option_type,op.date_order_1,op,price_end,vi.productorder_idx";
$sql .= " FROM tblorder_basic AS oi ";
$sql .= "LEFT JOIN tblorder_product AS op ON(oi.order_num=op.order_num)";
$sql .= "LEFT JOIN tblproductreview AS vi ON(op.idx=vi.productorder_idx) ";
$sql .= "WHERE oi.member_id='".MEMID."' ";
$sql .= "AND op.order_status ='6' ";
$sql .= "AND op.cs_type IN ('0','E') ";
$sql .= "AND op.cs_status ='0' ";
$sql .= "AND op.option_type ='option' ";
$sql .= "AND vi.productorder_idx is null ";
$sql .= "AND ( op.date_order_1 >= '{$s_curdate1}' AND op.date_order_1 <= '{$e_curdate1}' ) ";
if ( $isMobile ) {
    $paging = new New_Templet_paging($sql, 3, 8, 'GoPage', true);
} else {
	$paging = new New_Templet_paging($sql,10,10,'GoPage',true);
}
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;

$sql = $paging->getSql($sql);
$result=pmysql_query($sql,get_db_conn());
//exdebug($sql);

$Point = new POINT;
$cfg_point = $Point->getConfigPair();


if ( $isMobile ) {
	$r_s_curtime=strtotime("$r_s_year-$r_s_month-$r_s_day");
	$r_s_curdate=date("Ymd",$r_s_curtime)."000000";
	$r_e_curtime=strtotime("$r_e_year-$r_e_month-$r_e_day");
	$r_e_curdate=date("Ymd",$r_e_curtime)."999999";
//    $r_s_curdate = date("YmdHis",strtotime("$s_year-$s_month-$s_day"));
  //  $r_e_curdate = date("Ymd235959",$etime);
} else {
	$r_s_curtime=strtotime("$r_s_year-$r_s_month-$r_s_day");
	$r_s_curdate=date("Ymd",$r_s_curtime)."000000";
	$r_e_curtime=strtotime("$r_e_year-$r_e_month-$r_e_day");
	$r_e_curdate=date("Ymd",$r_e_curtime)."999999";
}

# ====================================================================================================================================
# 작성한 리뷰 리스트
# ====================================================================================================================================

$sql2  = "SELECT vi.*,op.option_type  ";
$sql2 .= "FROM tblproductreview AS vi ";
$sql2 .= "LEFT JOIN  tblorder_product AS op ON(vi.productorder_idx=op.idx)";
$sql2 .= "WHERE vi.id = '" . MEMID . "' ";
$sql2 .= "AND ( vi.date >= '{$s_curdate}' AND vi.date <= '{$e_curdate}' ) ";
$sql2 .= "ORDER BY vi.num desc ";
#echo $sql2;
//exdebug($sql2);

if ( $isMobile ) {
	$r_paging = new New_Templet_paging($sql2, 3, $listnum, 'GoPage2', true);
	$r_t_count = $r_paging->t_count;
	$gotopage2 = $r_paging->gotopage;

	$sql2 = $r_paging->getSql($sql2);
	$result2 = pmysql_query($sql2,get_db_conn());

    include($Dir.TempletDir."review/mobile/myreview_TEM001.php");
} else {
	$r_paging = new New_Templet_paging($sql2,10,10,'GoPage2',true);
	$r_t_count = $r_paging->t_count;
	$gotopage2 = $r_paging->gotopage;

	$sql2 = $r_paging->getSql($sql2);
	$result2 = pmysql_query($sql2,get_db_conn());
    //exdebug($sql2);
 ?>


 
<div id="contents">
	<div class="mypage-page">

		<h2 class="page-title">상품리뷰</h2>

		<div class="inner-align page-frm clear">

			<? include  "mypage_TEM01_left.php";  ?>
			<article class="my-content">
			
				<div class="review-info clear">
				<div class="inner">리뷰 작성시<br><strong class="point-color">
					<?if($cfg_point['review_text_short']> 0){?><? } ?>

					 <?=number_format($cfg_point['review_text_short'])?>P~
					
					 <?=number_format($cfg_point['review_text_long'])?>P</strong> 지급</div>
					<div class="inner">포토 리뷰 작성시<br><strong class="point-color"><?=number_format($cfg_point['review_photo'])?>P</strong> 지급</div>
				</div>

				<section class="mt-25" data-ui="TabMenu">
					<div class="tabs"> 
						<button type="button" data-content="menu" data-review_type='reviewwrite' data-review_count='<?=$t_count?>' class="<?=$review_display['reviewwrite']?> review_change"><span>리뷰작성</span></button>
						<button type="button" data-content="menu" data-review_type='reviewok' data-review_count='<?=$r_t_count?>' class="<?=$review_display['reviewok']?> review_change"><span>완료리뷰</span></button>
					</div>
					<header class="my-title mt-40">
						<h3 class="fz-0">리뷰</h3>
						<div class="count">전체 <strong class="review_count"><?if($review_type=="reviewwrite"){echo $t_count;}else{echo $r_t_count;}?></strong></div>
						<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
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
								<span class="dash"></span>
								<div class="box">
									<div><input type="text" title="일자별 시작날짜" name="date2" id="" value="<?=$strDate2?>" readonly></div>
										<button type="button" class="btn_calen CLS_cal_btn">달력 열기</button>
								</div>
							</div>
							<button type="button" class="btn-point" onClick="javascript:CheckForm();"><span>검색</span></button>
						</div>
						</form>
					</header>
					<div  class="<?=$review_display['reviewwrite']?>" data-content="content">
						<table class="th-top">
							<caption>리뷰 작성</caption>
							<colgroup>
								<col style="width:100px">
								<col style="width:auto">
								<col style="width:135px">
								<col style="width:135px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">주문날짜</th>
									<th scope="col">상품정보</th>
									<th scope="col">결제금액</th>
									<th scope="col">리뷰작성</th>
								</tr>
							</thead>
							<tbody>
<?
		$cnt=0;

		if($t_count){
			while($row=pmysql_fetch_object($result)) {

				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);

				// 상품 정보
				$sub_sql  = "SELECT pr.*,op.* ";
				$sub_sql .= "FROM tblproduct AS pr ";
				$sub_sql .= "LEFT JOIN  tblproduct_option AS op ON(pr.productcode=op.productcode) ";
				$sub_sql .= "WHERE pr.productcode = '" . $row->productcode . "' ";
				$sub_sql .= "AND op.option_num = '" . $row->option_code . "' ";
				$sub_row = pmysql_fetch_object(pmysql_query($sub_sql));
				//pre($sub_sql);
?>
								<tr>
									<td class="txt-toneB"><?=COMMON::format($row->date_order_1,'Y.m.d')?></td>
									<td class="pl-25">
										<div class="goods-in-td">
											<div class="thumb-img"><a href="<?=$Dir.FrontDir.'productdetail.php?productcode='.$sub_row->productcode?>"><img src="<?=$sub_row->tinyimage?>" alt="썸네일"></a></div>
											<div class="info">
												<p class="brand-nm"></p>
												<p class="goods-nm"><?=strip_tags($sub_row->productname)?></p>
												<p class="opt">
												<? if($row->option_type=='option'){?><p class="opt"><?=strip_tags($sub_row->option_name)?></p><? } ?>
												</p>
											</div>
										</div>
									</td>
									<td class="txt-toneA fw-bold"><?=number_format($row->price_end)?>원</td>
									<td><div class=""><button class="btn-basic h-small" type="button" onclick="javascript:reviewopen('<?=$row->idx?>','<?=$row->productcode?>','<?=$row->option_code?>','<?=$row->order_num?>')"><span>작성하기</span></button></div></td>
									<input type=hidden name='modify_pdtimg' value="<?=$file?>">
                                    <input type=hidden name='modify_brandname' value="<?=$sub_row->brandname?>">
                                    <input type=hidden name='modify_productname' value="<?=$sub_row->productname?>">
								</tr>
<?
				$cnt++;
			}
		} else {
?>
								<tr>
									<td colspan="6">내역이 없습니다.</td>
								</tr>
<?
		}
?>
							</tbody>
						</table>
						<div class="list-paginate mt-20">
							<?=$paging->a_prev_page.$paging->print_page.$paging->a_next_page?>
						</div>
					</div>
					<div class="<?=$review_display['reviewok']?>" data-content="content">
						<table class="th-top table-toggle">
							<caption>완료리뷰</caption>
							<colgroup>
								<col style="width:100px">
								<col style="width:auto">
								<col style="width:300px">
								<col style="width:120px">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">작성날짜</th>
									<th scope="col">상품정보</th>
									<th scope="col">내용</th>
									<th scope="col">평가</th>
								</tr>
							</thead>
							<tbody>
<?
		$cnt=0;
		if($r_t_count){
			while($row=pmysql_fetch_object($result2)) {

				$number = ($r_t_count-($setup[list_num] * ($gotopage2-1))-$cnt);

               // 상품 정보
				$sub_sql  = "SELECT pr.*,op.* ";
				$sub_sql .= "FROM tblproduct AS pr ";
				$sub_sql .= "LEFT JOIN  tblproduct_option AS op ON(pr.productcode=op.productcode) ";
				$sub_sql .= "WHERE pr.productcode = '" . $row->productcode . "' ";
				$sub_sql .= "AND op.option_num = '" . $row->option_code . "' ";
				//pre($sub_sql);
				$sub_row = pmysql_fetch_object(pmysql_query($sub_sql));

				// 주문일
                $order_date = $row->regdt;
                if ( empty($order_date) ) {
                    $order_date = substr($row->ordercode, 0, 4) . "." . substr($row->ordercode, 4, 2) . "." . substr($row->ordercode, 6, 2);
                }

                // 작성일
                $write_date = substr($row->date, 0, 4) . "." . substr($row->date, 4, 2) . "." . substr($row->date, 6, 2);

                // 업로드 이미지 정보
                $arrUpFile = array();

                if ( !empty($row->upfile) ) { array_push($arrUpFile, $row->upfile); }
                if ( !empty($row->upfile2) ) { array_push($arrUpFile, $row->upfile2); }
                if ( !empty($row->upfile3) ) { array_push($arrUpFile, $row->upfile3); }
                if ( !empty($row->upfile4) ) { array_push($arrUpFile, $row->upfile4); }
                if ( !empty($row->upfile5) ) { array_push($arrUpFile, $row->upfile5); }

                // 리뷰 제목/내용
                $review_title   = $row->subject;
                $review_content = nl2br($row->content);

                if($row->best_type == "1") $best_img = "<img src='../static/img/icon/icon_review_best.gif' alt='상품평 베스트'>";
                else $best_img = "";

                if($row->type == "1") $photo_img = "<img src='../static/img/icon/icon_review_photo.gif' alt='포토상품평'>";
                else $photo_img = "";



?>
								<tr>
									<td class="txt-toneB"><?=$write_date?></td>
									<td class="pl-25">
										<div class="goods-in-td">
											<div class="thumb-img"><a href="<?=$Dir.FrontDir.'productdetail.php?productcode='.$sub_row->productcode?>"><img src="<?=$sub_row->tinyimage?>" alt="썸네일"></a></div>
											<div class="info">
												<p class="brand-nm"></p>
												<p class="goods-nm"><?=strip_tags($sub_row->productname)?></p>
												<p class="opt">
													<? if($row->option_type=='option'){?><p class="opt"><?=strip_tags($sub_row->option_name)?></p><? } ?>
												</p>
											</div>
										</div>
									</td>
									<td class="subject"><a href="javascript:;" class="menu ellipsis w300"><?=strcutMbDot($review_title, 60)?></a></td>
									<td class="review-rating">
										<!-- <img src="../static/img/icon/rating1.png" alt="5점 만점 중 1점">
										<img src="../static/img/icon/rating2.png" alt="5점 만점 중 2점">
										<img src="../static/img/icon/rating3.png" alt="5점 만점 중 3점">
										<img src="../static/img/icon/rating4.png" alt="5점 만점 중 4점"> -->
										<img src="/jayjun/web/static/img/icon/rating_score<?=$row->marks?>.png" alt="5점 만점 중 5점">
									</td>
								</tr>
								<tr class="hide">
									<td class="reset" colspan="4">
										<div class="board-answer editor-output">
											<div class="btn">
												<button class="btn-basic h-small w50" type="button" onclick="javascript:Review_Modify('modify','<?=$row->num?>');"><span>수정</span></button>
												<!--button class="btn-line h-small w50" type="button" onclick="javascript:ajax_review_del('<?=$row->num?>')"><span>삭제</span></button-->
											</div>
											<?foreach($arrUpFile as $auf=>$aufv){?>
												<img src="<?=$aufv?>"><br>
											<?}?>
											<?=$review_content?>
										</div>
									</td>
									<input type=hidden name=modify_subject id="modify_subject_<?=$row->num?>" value="<?=$review_title?>">
                                    <input type=hidden name=modify_content id="modify_content_<?=$row->num?>" value="<?=$row->content?>">
								</tr>
<?
				$cnt++;
			}
		} else {
?>
								<tr>
									<td colspan="6">내역이 없습니다.</td>
								</tr>
<?
		}
?>
								
							</tbody>
						</table>
						<div class="list-paginate mt-20">
							<?=$r_paging->a_prev_page.$r_paging->print_page.$r_paging->a_next_page?>
						</div>
					</div>
				</section>

			</article><!-- //.my-content -->
		</div><!-- //.page-frm -->

	</div>
</div><!-- //#contents -->

<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>

<script type="text/javascript">

$(document).ready(function(){
	/*
    $('.layer-dimm-wrap .btn-close').click(function(){
        // 상단 탭 유지하고, 레이어 팝업창 초기화 위해서..
        //location.reload();
        document.form2.submit();
    });+*/
	$(".review_change").click(function(){
//		alert($(this).data('point_type'));
		$("input[name=review_type]").val($(this).data('review_type'));
		$(".review_count").html($(this).data('review_count'));

	})

	$(".img_del").click(function(){
		var delnum=$(this).data('del_num');
		DeletePhoto(delnum);

	})
		
});

function Review_Init(mode) {

    //document.form2.review_type.value = mode;

 //   $("#review_vote").val("");
    $("#inp_writer").val("");
    $("#inp_content").val("");
    $("#op_idx").val("");
	$("#kg").val("");
	$("#cm").val("");
	$("#add-photo1").find('input[type=file]').val("");
	$("#add-photo1").find(".upload-display").remove();
	$("#add-photo1").find(".photoBox").removeClass("after");
	$("#add-photo2").find('input[type=file]').val("");
	$("#add-photo2").find(".upload-display").remove();
	$("#add-photo2").find(".photoBox").removeClass("after");
	$("#add-photo3").find('input[type=file]').val("");
	$("#add-photo3").find(".upload-display").remove();
	$("#add-photo3").find(".photoBox").removeClass("after");
	$("#add-photo4").find('input[type=file]').val("");
	$("#add-photo4").find(".upload-display").remove();
	$("#add-photo4").find(".photoBox").removeClass("after");

//    $(".add-photo").find('p').remove();
//    $(".add-photo").find('button').remove();
}

function Review_Write(op_idx, ptdimg, brandname, ptdname) {

    Review_Init('request');

    var num = op_idx;
   // var modify_pdtimg       = ptdimg;
   // var modify_brandname    = brandname;
    var modify_productname  = ptdname;
    //console.log(num);

    //Layer 에 값 채우기
    //$(".modify_info img").attr({"src":modify_pdtimg});
    //$("#qna-brandname").html("["+modify_brandname+"]");
    $("#qna-productname").html(modify_productname);
    $("#op_idx").val(num);


    $('.goodsReview-write').fadeIn();
}





function ajax_review_del(review_num) {
	
	$("#review_num").val(review_num);
	$("#mode").val("del");
	
	if(confirm("리뷰를 삭제하시겠습니까?")){
		var fd = new FormData($("#reviewForm")[0]);
		$.ajax({
			type        : "GET",
			url         : "ajax_delete_review.php",
			contentType : "application/x-www-form-urlencoded; charset=UTF-8",
			data        : { review_num : review_num }
		}).done(function ( data ) {
			data=data.trim();
			if ( data === "SUCCESS" ) {
				alert("리뷰가 삭제되었습니다.");
				document.form2.submit();
			}else{
				alert("리뷰 삭제가 실패하였습니다.");
			}
		});/*
		$.ajax({
			url: "ajax_delete_review.php",
			type: "GET",
			data: {review_num:review_num},
			async: false,
			cache: false,
			contentType: false,
			processData: false,
		}).success(function(data){
			data=data.trim();
			if( data === "SUCCESS" ) {
				alert("리뷰가 삭제되었습니다.");
				// 탭 선택 유지하기 위해 변경.
				//location.reload();
				//document.form2.review_type.value = mode;
				document.form2.submit();
			} else {
				var arrTmp = data.split("||");
				if ( arrTmp[0] === "FAIL" ) {
					alert(arrTmp[1]);
				} else {
					alert("리뷰 삭제가 실패하였습니다.");
				}
			}
		}).error(function(){
			alert("다시 시도해 주십시오.");
		});*/
	}
}

function DeletePhoto(this_photo){

		if(this_photo=="1") var filenum='';
		else  var filenum=this_photo;

		$("#add-photo"+this_photo+"").find('input[type=file]').val("");
		$("#add-photo"+this_photo+"").find(".upload-display").remove();
		$("#add-photo"+this_photo+"").find(".photoBox").removeClass("after");
		
		$("#add-photo"+this_photo+"").find("#upfile"+filenum).val("");

		
}

function browser() {
    var s = navigator.userAgent.toLowerCase();
    var match = /(webkit)[ \/](\w.]+)/.exec(s) ||
            /(opera)(?:.*version)?[ \/](\w.]+)/.exec(s) ||
            /(msie) ([\w.]+)/.exec(s) ||
            !/compatible/.test(s) && /(mozilla)(?:.*? rv:([\w.]+))?/.exec(s) || [];
    return { name: match[1] || "", version: match[2] || "0" };
}



function reviewopen (productorder_idx,productcode,option_code,order_num) {
    switch(productorder_idx) {
        case '2': //미로그인
        case 2:
            UI.confirm("로그인 후 이용이 가능합니다.\n로그인 화면으로 이동하시겠습니까?", function() {
                document.location.href="/front/login.php?chUrl={_SERVER.REQUEST_URI}";
            })
            break;
        case '3': //권한없음(리뷰기작성)
        case 3:
            UI.alert('상품에 대한 주문 내역이 없습니다.');
            break;
        case '4': //권한없음(주문내역없음)
        case 4:
            UI.alert('이미 구매후기를 등록했습니다. (구매확정 후 1회 등록 가능).');
            break;
        case '1': //작성창
        default:
            UI.modal('/front/review/review_write.php','리뷰작성', {productorder_idx:productorder_idx,productcode:productcode,option_code:option_code,order_num:order_num});
            break;
    }

}
function Review_Modify(mode,idx){
	UI.modal('/front/review/review_write.php','리뷰수정', {mode:mode,idx:idx});
}
</script>
<?}?>