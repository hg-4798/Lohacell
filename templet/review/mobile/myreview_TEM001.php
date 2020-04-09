<?php

$tebmenu  = $_REQUEST['tebmenu'];
include_once($Dir."conf/config.point.new.php");
?>


<!-- <div class="sub-title">
    <h2>상품리뷰</h2>
    <a class="btn-prev" href="mypage.php"><img src="./static/img/btn/btn_page_prev.png" alt="이전 페이지"></a>
</div> -->

<div id="page">
<!-- 내용 -->
<main id="content" class="subpage goodsReview-write">


	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>상품리뷰</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="mypage_review">

		<div class="review_info">
			<ul class="clear">
				<li>
					<span class="icon_review"></span>
					<span class="txt">리뷰 작성시<br><span class="point-color"><?=number_format($pointSet_new['protext_down_point'])?>P~<?=number_format($pointSet_new['protext_up_point'])?>P</span> 지급</span>
				</li>
				<li>
					<span class="icon_photo_review"></span>
					<span class="txt">포토 리뷰 작성시<br><span class="point-color"><?=number_format($pointSet_new['poto_point'])?>P</span> 지급</span>
				</li>
			</ul>
		</div><!-- //.review_info -->
		
		<div class="tab_type1 mt-15" data-ui="TabMenu">
			<div class="tab-menu clear mb-20">
				<a data-content="menu" data-review_type='reviewwrite' data-review_count='<?=$t_count?>' class="<?=$review_display['reviewwrite']?> review_change" title="선택됨">리뷰작성</a>
				<a data-content="menu" data-review_type='reviewok' data-review_count='<?=$t_count?>' class="<?=$review_display['reviewok']?> review_change">완료리뷰</a>
			</div>

			<!-- 리뷰작성 -->
			<div class="tab-content <?=$review_display['reviewwrite']?>" data-content="content">
				<div class="check_period">
					<ul>
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
							<li class="<?=$dayClassName?>"><a href="javascript:;" onClick = "GoSearch('<?=$kk?>', this)"><?=$vv?></a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
						<?}?>
					</ul>
				</div><!-- //.check_period -->

				<div class="review_list">
					<ul>
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

?>
						<li>
							<p class="date">주문날짜 <?=COMMON::format($row->date_order_1,'Y.m.d')?></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="<?=$Dir?>'/m/productdetail.php?productcode='<?=$sub_row->productcode?>"><img src="<?=$sub_row->tinyimage?>" alt="상품 이미지"></a></div>
									<div class="info">
										<p class="name"><?=strip_tags($sub_row->productname)?></p>
										<? if($row->option_type=='option'){?><p class="opt"><?=strip_tags($sub_row->option_name)?></p><? } ?>
										<p class="price">￦ <?=number_format($row->price_end)?></p>
									</div>
									<div class="btns"><a href="javascript:;" class="btn_review_write btn-basic" onclick="javascript:reviewopen('<?=$row->idx?>','<?=$row->productcode?>','<?=$row->option_code?>','<?=$row->order_num?>')">리뷰작성</a></div>
									<input type=hidden name='modify_pdtimg' value="<?=$file?>">
                                    <input type=hidden name='modify_brandname' value="<?=$sub_row->brandname?>">
                                    <input type=hidden name='modify_productname' value="<?=$sub_row->productname?>">
								</div>
							</div>
						</li>
<?
				$cnt++;
			}
		} else {
?>
						<li>
							<div class="no-content">내역이 없습니다.</div>
						</li>
<?
		}
?>

					</ul>
				</div><!-- //.review_list -->
				
				<div class="list-paginate mt-15">
					<?=$paging->a_prev_page.$paging->print_page.$paging->a_next_page?>
				</div><!-- //.list-paginate -->
			</div>
			<!-- //리뷰작성 -->

			<!-- 완료리뷰 -->
			<div class="tab-content <?=$review_display['reviewok']?>" data-content="content"><!-- [D] 통합포인트와 구성 동일 -->
				<div class="check_period">
					<ul>
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
							<li class="<?=$dayClassName?>"><a href="javascript:;" onClick = "GoSearch('<?=$kk?>', this)"><?=$vv?></a></li><!-- [D] 해당 조회기간일때 .on 클래스 추가 -->
						<?}?>
					</ul>
				</div><!-- //.check_period -->

				<div class="review_list">
					<ul class="accordion_list">
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
						<li>
							<p class="date">작성날짜 <?=$write_date?></p>
							<div class="cart_wrap">
								<div class="goods_area">
									<div class="img"><a href="<?=$Dir?>m/productdetail.php?productcode=<?=$sub_row->productcode?>"><img src="<?=$sub_row->tinyimage?>" alt="상품 이미지"></a></div>
									<div class="info accordion_btn">
										<p class="name"><?=strip_tags($sub_row->productname)?></p>
										<? if($row->option_type=='option'){?><p class="opt"><?=strip_tags($sub_row->option_name)?></p><? } ?>
									</div>
									<div class="btns">
										<button class="btn_review_write btn-line" type="button" onclick="javascript:Review_Modify('modify','<?=$row->num?>');"><span>수정</span></button>
									<!--
										<a href="javascript:;" class="btn_review_write btn-line" onclick="javascript:Review_Modify('<?=$row->num?>', '<?=$file?>', '<?=$sub_row->brandname?>', '<?=$sub_row->productname?>','<?=$row->productcode?>','<?=$row->marks?>','<?=$row->ordercode?>','<?=$row->productorder_idx?>','<?=$row->up_rfile?>','<?=$row->upfile?>','<?=$row->up_rfile2?>','<?=$row->upfile2?>','<?=$row->up_rfile3?>','<?=$row->upfile3?>','<?=$row->up_rfile4?>','<?=$row->upfile4?>','<?=$row->up_rfile5?>','<?=$row->upfile5?>','<?=$row->size?>','<?=$row->deli?>','<?=$row->color?>','<?=$row->quality?>','<?=$row->kg?>','<?=$row->cm?>');">수정</a>
										<a href="javascript:;" class="btn-basic" onclick="javascript:ajax_review_del('<?=$row->num?>')">삭제</a>
									-->
									</div>
								</div>
							</div>
							<div class="review_con accordion_con">
								<div class="star">
									<img src="/jayjun/m/static/img/icon/rating_score<?=$row->marks?>.png" alt="5점 만점 중 5점">
								</div>
								<p class="tit"><?=strcutMbDot($review_title, 60)?></p>
								<div class="txt">
									<?foreach($arrUpFile as $auf=>$aufv){?>
										<img src="<?=$aufv?>"><br>
									<?}?>
									<?=$review_content?>
								</div>
								<input type=hidden name=modify_subject id="modify_subject_<?=$row->num?>" value="<?=$review_title?>">
                                <input type=hidden name=modify_content id="modify_content_<?=$row->num?>" value="<?=$row->content?>">
							</div>
						</li>
<?
				$cnt++;
			}
		} else {
?>
						<li>
							<div class="no-content">내역이 없습니다.</div>
						</li>
<?
		}
?>
					</ul>
				</div><!-- //.review_list -->
				
				<div class="list-paginate mt-15">
					<?=$r_paging->a_prev_page.$r_paging->print_page.$r_paging->a_next_page?>
				</div><!-- //.list-paginate -->
			</div>
			<!-- //완료리뷰 -->
		</div><!-- //.point_tab -->

	</section><!-- //.mypage_point -->

</main>
<!-- //내용 -->
</div>
<form name=form2 method=GET action="<?=$_SERVER['PHP_SELF']?>">
<input type=hidden name=review_type value="<?=$review_type?>">
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
<input type=hidden name=tebmenu value="<?=$tebmenu?>">
<input type=hidden name=block2 value="<?=$block2?>">
<input type=hidden name=gotopage2 value="<?=$gotopage2?>">
<input type=hidden name="date1" id="" value="<?=$strDate1?>">
<input type=hidden name="date2" id="" value="<?=$strDate2?>">

</form>


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
	$(".viewimg1").attr("src","");
	$("#add-photo1").find(".image_preview").hide();
	$("#add-photo2").find('input[type=file]').val("");
	$(".viewimg2").attr("src","");
	$("#add-photo2").find(".image_preview").hide();
	$("#add-photo3").find('input[type=file]').val("");
	$(".viewimg3").attr("src","");
	$("#add-photo3").find(".image_preview").hide();
	$("#add-photo4").find('input[type=file]').val("");
	$(".viewimg4").attr("src","");
	$("#add-photo4").find(".image_preview").hide();
	
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


function ajax_review_insert() {

    var op_idx          = $("#op_idx").val();
    var review_title    = $("#inp_writer").val().trim();
    var review_content  = $("#inp_content").val().trim();
	var kg  = $("#kg").val().trim();
	var cm  = $("#cm").val().trim();
//    var review_vote     = $("#review_vote option:selected").val();
    var review_num      = $("#review_num").val();
    var size = $(':radio[name="review_size"]:checked').val();
    var color = $(':radio[name="review_color"]:checked').val();
    var deli = $(':radio[name="review_deli"]:checked').val();
    var quality = $(':radio[name="review_quality"]:checked').val();

    //console.log(op_idx);
    //console.log(review_title);
    //console.log(review_content);
    //console.log(review_vote);
    //console.log(review_num);
    var mode            = "request";
    if(review_num > 0) mode = "result";

	if ( review_title == "" ) {
        alert("제목을 입력해 주세요.");
        $("#inp_writer").val('').focus();
    } else if ( review_content == "" ) {
        alert("내용을 입력해 주세요.");
        $("#inp_content").val('').focus();
    } else if ( chkReviewContentLength($("#inp_content")[0]) === false ) {
        $("#inp_content").focus();
    } else if ( size == "" ) {
    	alert("사이즈를 선택해 주세요");
    } else if ( color == "" ) {   
    	alert("색상을 선택해 주세요");
    } else if ( deli == "" ) {      
    	alert("배송을 선택해 주세요");
    } else if ( quality == "" ) {           
    	alert("품질/만족도를 선택해 주세요");
    } else {
		$("#size").val(size);
		$("#deli").val(deli);
		$("#color").val(color);
		$("#quality").val(quality);
		$("#mode").val("inup");
		
        var fd = new FormData($("#reviewForm")[0]);

        $.ajax({
            url: "../front/ajax_insert_review_v2.php",
            type: "POST",
            data: fd,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
        }).success(function(data){
			data=data.trim();
            if( data === "SUCCESS" ) {
                alert("리뷰가 등록되었습니다.");
                // 탭 선택 유지하기 위해 변경.
                //location.reload();
                //document.form2.review_type.value = mode;
                document.form2.submit();
				//location.href = "/m/mypage_review.php";
            } else {
                var arrTmp = data.split("||");
                if ( arrTmp[0] === "FAIL" ) {
                    alert(arrTmp[1]);
                } else {
                    alert("리뷰 등록이 실패하였습니다.");
                }
            }
        }).error(function(){
            alert("다시 시도해 주십시오.");
        });
    }
}

function Review_Modify(review_num, pdtimg, brandname, pdtname, pdtcode, marks, ordercode, op_idx, up_rfile, upfile,
                        up_rfile2, upfile2, up_rfile3, upfile3, up_rfile4, upfile4, up_rfile5, upfile5,size,deli,color,quality,kg,cm) {

    Review_Init('result');

    var review_num          = review_num;
    var modify_pdtimg       = pdtimg;
    var modify_brandname    = brandname;
    var modify_productname  = pdtname;
    var modify_subject      = $("#modify_subject_"+review_num).val();
    var modify_content      = $("#modify_content_"+review_num).val();

    //console.log(upfile);

    //Layer 에 값 채우기
 //   $(".modify_info img").attr({"src":modify_pdtimg});
//    $("#qna-brandname").html("["+modify_brandname+"]");
    $("#qna-productname").html(modify_productname);
//    $("#review_vote").val(marks);
    $("#inp_writer").val(modify_subject);
    $("#inp_content").val(modify_content);
	$("#kg").val(kg);
	$("#cm").val(cm);
    $("#op_idx").val(op_idx);
    $("#review_num").val(review_num);

	$('input:radio[name="review_size"][value="'+size+'"]').prop('checked', true);
	$('input:radio[name="review_color"][value="'+color+'"]').prop('checked', true);
	$('input:radio[name="review_deli"][value="'+deli+'"]').prop('checked', true);
	$('input:radio[name="review_quality"][value="'+quality+'"]').prop('checked', true);

    if(upfile != ""){
        var this_photo = "add-photo1";
        var imgpath = "http://<?=$_SERVER["HTTP_HOST"]?>/data/shopimages/review/"+upfile;
		//$("#"+this_photo).find(".image_preview").html("<img src="+imgpath+" style='position:absolute;top:0;left:0;width:100%;height:100%;'><a href=\"#\" class=\"delete-btn img_del\" data-del_num='1'><button type=\"button\"></button></a>")
		$(".viewimg1").attr("src",imgpath);
		$("#"+this_photo).find(".image_preview").show();
        $("#upfile").val(upfile);
    }

    if(upfile2 != ""){
        var this_photo = "add-photo2";
        var imgpath = "http://<?=$_SERVER["HTTP_HOST"]?>/data/shopimages/review/"+upfile2;
        //$("#"+this_photo).find(".image_preview").html("<img src="+imgpath+" style='position:absolute;top:0;left:0;width:100%;height:100%;'><a href=\"#\" class=\"delete-btn img_del\" data-del_num='2'><button type=\"button\"></button></a>")
		$(".viewimg2").attr("src",imgpath);
		$("#"+this_photo).find(".image_preview").show();
        $("#upfile2").val(upfile2);
    }

    if(upfile3 != ""){
        var this_photo = "add-photo3";
        var imgpath = "http://<?=$_SERVER["HTTP_HOST"]?>/data/shopimages/review/"+upfile3;
        //$("#"+this_photo).find(".image_preview").html("<img src="+imgpath+" style='position:absolute;top:0;left:0;width:100%;height:100%;'><a href=\"#\" class=\"delete-btn img_del\" data-del_num='3'><button type=\"button\"></button></a>")
		$(".viewimg3").attr("src",imgpath);
		$("#"+this_photo).find(".image_preview").show();
        $("#upfile3").val(upfile3);
    }

    if(upfile4 != ""){
        var this_photo = "add-photo4";
        var imgpath = "http://<?=$_SERVER["HTTP_HOST"]?>/data/shopimages/review/"+upfile4;
        //$("#"+this_photo).find(".image_preview").html("<img src="+imgpath+" style='position:absolute;top:0;left:0;width:100%;height:100%;'><a href=\"#\" class=\"delete-btn img_del\" data-del_num='4'><button type=\"button\"></button></a>")
		$(".viewimg4").attr("src",imgpath);
		$("#"+this_photo).find(".image_preview").show();
        $("#upfile4").val(upfile4);
    }

    if(upfile5 != ""){
        var this_photo = "add-photo5";
        var imgpath = "http://<?=$_SERVER["HTTP_HOST"]?>/data/shopimages/review/"+upfile5;
        //$("#"+this_photo).find(".image_preview").html("<img src="+imgpath+" style='position:absolute;top:0;left:0;width:100%;height:100%;'><a href=\"#\" class=\"delete-btn img_del\" data-del_num='5'><button type=\"button\"></button></a>")
		$(".viewimg5").attr("src",imgpath);
		$("#"+this_photo).find(".image_preview").show();
        $("#upfile5").val(upfile5);
    }


    $('.goodsReview-write').fadeIn();
}


function ajax_review_del(review_num) {
	
	$("#review_num").val(review_num);
	$("#mode").val("del");
	
	if(confirm("리뷰를 삭제하시겠습니까?")){
		var fd = new FormData($("#reviewForm")[0]);
		$.ajax({
			type        : "GET",
			url         : "../front/ajax_delete_review.php",
			contentType : "application/x-www-form-urlencoded; charset=UTF-8",
			data        : { review_num : review_num }
		}).done(function ( data ) {
			data=data.trim();
			if ( data === "SUCCESS" ) {
				alert("리뷰가 삭제되었습니다.");
				document.form2.submit();
				//location.href = "/m/mypage_review.php";
			}else{
				alert("리뷰 삭제가 실패하였습니다.");
			}
		});
		
	}
}


</script>


<SCRIPT LANGUAGE="JavaScript">


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


function GoPage(block,gotopage) {
	//document.form2.review_type.value='request';
	document.form2.block.value=block;
	document.form2.gotopage.value=gotopage;
	document.form2.submit();
}
function GoPage2(block,gotopage) {
	//document.form2.review_type.value='request';
	document.form2.block2.value=block;
	document.form2.gotopage2.value=gotopage;
	document.form2.submit();
}

var NowTime=parseInt(<?=time()?>);
function GoSearch(gbn, obj) {

	var s_date = new Date(NowTime*1000);
	switch(gbn) {
		case "1MONTH":
			s_date.setMonth(s_date.getMonth()-1);
			break;
		case "3MONTH":
			s_date.setMonth(s_date.getMonth()-3);
			break;
		case "6MONTH":
			s_date.setMonth(s_date.getMonth()-6);
			break;
		case "9MONTH":
			s_date.setMonth(s_date.getMonth()-9);
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
	document.form2.s_year.value = s_date.getFullYear();
	document.form2.s_month.value = s_month_str;
	document.form2.s_day.value = s_date_str;
	//날짜 칸에 셋팅
	var s_date_full = s_date.getFullYear()+"-"+s_month_str+"-"+s_date_str;
	document.form2.date1.value=s_date_full;
	//======== //시작 날짜 셋팅 =========//
	
	//======== 끝 날짜 셋팅 =========//
	var e_month_str = str_pad_right(parseInt(e_date.getMonth())+1);
	var e_date_str = str_pad_right(parseInt(e_date.getDate()));

	// 폼에 셋팅
	document.form2.e_year.value = e_date.getFullYear();
	document.form2.e_month.value = e_month_str;
	document.form2.e_day.value = e_date_str;

	document.form2.day_division.value = gbn;
	
	//날짜 칸에 셋팅
	var e_date_full = e_date.getFullYear()+"-"+e_month_str+"-"+e_date_str;
	document.form2.date2.value=e_date_full;
	//======== //끝 날짜 셋팅 =========//

    document.form2.submit();
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

function DeletePhoto(this_photo){

		if(this_photo=="1") var filenum='';
		else  var filenum=this_photo;

		$("#add-photo"+this_photo+"").find('input[type=file]').val("");
		$("#add-photo"+this_photo+"").find("#upfile"+filenum).val("");
}


function reviewopen (productorder_idx,productcode,option_code,order_num) {
    switch(productorder_idx) {
        case '2': //미로그인
        case 2:
            UI.confirm("로그인 후 이용이 가능합니다.\n로그인 화면으로 이동하시겠습니까?", function() {
                document.location.href="/m/login.php?chUrl={_SERVER.REQUEST_URI}";
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
            UI.popup('/m/review/review_write.php','리뷰작성', {productorder_idx:productorder_idx,productcode:productcode,option_code:option_code,order_num:order_num});
            break;
    }

}
function Review_Modify(mode,idx){
    UI.popup('/m/review/review_write.php','리뷰수정', {mode:mode,idx:idx});
}
</SCRIPT>
