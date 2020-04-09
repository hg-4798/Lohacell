<?php
if(strlen($Dir)==0) $Dir="../";

include_once($_SERVER['DOCUMENT_ROOT']."/lib/init.php");
include_once(DOC_ROOT."/lib/lib.php");
include_once(DOC_ROOT."/lib/paging.php");

#배송지 관리 리스트
$list_sql ="SELECT * FROM tbldestination WHERE mem_id = '".$_ShopInfo->getMemid()."' ORDER BY NO DESC";
# 페이징
$paging = new New_Templet_paging($list_sql, 5,  5, 'GoPage', true);
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;

$sql = $paging->getSql( $list_sql );
$result = pmysql_query( $sql, get_db_conn() );
while( $row = pmysql_fetch_array( $result ) ){
	$list[] = $row;
}

#기본 배송지
$base['Y'] = '(기본)';

include('./include/top.php');
include('./include/gnb.php');
?>
<div id="page">
<!-- 내용 -->
<main id="content" class="subpage">

	<form name='destination_form' id='destination_form' action="" method='POST' onsubmit="return false;" >
	<input type='hidden' id='mode' name='mode'>
	<input type='hidden' id='chk' name='chk' value="N">
	<input type='hidden' id='no' name='no'>
	<input type='hidden' id='mobile' name='mobile'>
	<input type=hidden name=block>
	<input type=hidden name=gotopage>
	<!-- 배송지 추가 팝업 -->
	<section class="pop_layer layer_add_deli">
		<div class="inner">
			<h3 class="title">배송지 추가 <button type="button" class="btn_close" id="btn_close">닫기</button></h3>

			<div class="board_type_write">
				<dl>
					<dt>
						<span class="required">배송지명</span>
						<label><input type="checkbox" class="check_def" id="base_chk" name="base_chk"> <span>기본 배송지로 설정</span></label>
					</dt>
					<dd><input type="text" class="w100-per validate[required]" data-errormessage-value-missing="배송지명을 입력하세요." label = "배송지 명" name="destination_name" id="destination_name" maxlength="20"></dd>
				</dl>
				<dl>
					<dt><span class="required">받는사람</span></dt>
					<dd><input type="text" class="w100-per validate[required]" data-errormessage-value-missing="받는사람을 입력하세요." name="get_name" id="get_name" label = "받는사람" maxlength="20"></dd>
				</dl>
				<dl>
					<dt><span class="required">휴대폰 번호</span></dt>
					<dd>
						<div class="input_tel">
							<select class="select_line" id="post_add_phone1"> 
								<option value="010" selected="">010</option>
								<option value="011">011</option>
								<option value="016">016</option>
								<option value="017">017</option>
								<option value="018">018</option>
								<option value="019">019</option>
							</select>
							<span class="dash"></span>
							<input type="tel" maxlength="4" id="post_add_phone2" class="validate[required, custom[numeric], minSize[4]]" data-errormessage-value-missing="휴대폰 가운데 번호를 입력하세요." label = "중간번호">
							<span class="dash"></span>
							<input type="tel" maxlength="4" id="post_add_phone3" class="validate[required, custom[numeric], minSize[4]]" data-errormessage-value-missing="휴대폰 마지막 번호를 입력하세요" label = "마지막 번호">
						</div>
					</dd>
				</dl>
				<dl>
					<dt><span class="required">주소</span></dt>
					<dd>
						<div class="input_addr">
							<input type="text" name="postcode_new" id="postcode_new"  class="w100-per validate[required]" data-errormessage-value-missing="우편번호를 입력하세요." placeholder="우편번호" label = "우편번호" maxlength="5" readonly>
							<div class="btn_addr"><button type="button" onclick="Address.search_zip();" class="btn-basic h-input">주소찾기</button></div>
						</div>
						<input type="text" class="w100-per mt-5 validate[required]" data-errormessage-value-missing="기본주소를 입력하세요." placeholder="기본주소" name="addr1" id="addr1" title="주소" label = "주소" maxlength="120">
						<input type="text" class="w100-per mt-5 validate[required]" data-errormessage-value-missing="상세주소를 입력하세요." placeholder="상세주소" name="addr2" id="addr2" maxlength="120">
						<input type="hidden" name="postcode" id="postcode" title="우편번호(구)" maxlength="7" readonly>
					</dd>
				</dl>
				<div class="btn_area mt-20">
					<ul>
						<li><button class="btn-point h-input" type="submit" id="btnSubmit">저장</button></li>
					</ul>
				</div>
			</div><!-- //.board_type_write -->
		</div>
	</section><!-- //.layer_add_deli -->
	<!-- //배송지 추가 팝업 -->
	</form>

	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>배송지 관리</span>
		</h2>
	</section><!-- //.page_local -->

	<section class="my_deli_site sub_bdtop">
		<div class="btn_area">
			<ul>
				<li><button type="button" class="btn_add_deli btn-line h-input" id="address_add">배송지 추가</button></li>
			</ul>
		</div>

		<div class="list_deli">
			<ul>
<?
		if( count($list) > 0 ) {
			foreach( $list as $key=>$val ){
?>
				<li>
					<div class="info">
						<p class="tit"><?=htmlspecialchars($val['destination_name'])?><?php if($val['base_chk'] == 'Y') echo "<span class=\"btn-point h-small\">기본</span>";?></p>
						<p class="tel"><?=addMobile($val['mobile'])?></p>
						<p class="addr"><?=htmlspecialchars($val['addr1'])?> <?=htmlspecialchars($val['addr2'])?></p>
					</div>
					<div class="btns">
                        <button class="btn_add_deli btn-line" type="button" onclick="Address.modify(<?=$val['no'] ?>);" ><span>수정</span></button>
                        <button class="btn-basic" type="button" onclick="Address.row_delete(<?=$val['no']?>);"><span>삭제</span></button>
					</div>
				</li>
<?
			}
		} else {
?>
			<li>등록된 주소가 없습니다.</li>
<?
		}
?>
			</ul>
		</div><!-- //.list_deli -->

		<div class="list-paginate mt-15">
			<?=$paging->a_prev_page.$paging->print_page.$paging->a_next_page?>
		</div><!-- //.list-paginate -->

	</section><!-- //.my_deli_site -->

</main>
<!-- //내용 -->
</div>

    <script src="<?=POST_JS;?>"></script>
    <script Language="JavaScript">
    var Address = {
        init: function () {
            var me = this;

            var option = $.extend({}, validation_option, {
                validateNonVisibleFields: false,
                onValidationComplete: function (form, status) {
                    if (status) me.submit();
                }
            });
            $("#destination_form").validationEngine('attach', option);


            $("#address_add").click(function () {
                if(<?=count($list)?> > 4 ){
                    alert("배송지등록을 최대 5개까지 가능합니다.");
                    $(".btn_close").trigger('click');
                    return false;
                }else {
                    //form 리셋
                    document.forms['destination_form'].reset();
                    $("#btnSubmit").text("추가");
                    //$("#submit_type").text("추가");
                    $("#base_chk").attr("checked", false);
                }
            });

            //form 리셋
            $(".btn_close").click(function () {
                document.forms['destination_form'].reset();
            });

            //기본 배송지 체크값 설정
            $("#base_chk").change(function () {
                if ($("#base_chk").is(":checked")) {
                    $("#chk").val("Y");
                } else {
                    $("#chk").val("N");
                }
            });

        },
        submit: function () {
            if ($("#mode").val() == '') {
                $("#mode").val("insert");
            }
            if($("#mode").val()!='delete'){
                $("#mobile").val($("#post_add_phone1").val() + "-" + $("#post_add_phone2").val() + "-" + $("#post_add_phone3").val());
            }
            var formdata = $("#destination_form").serialize();
            $.ajax({
                type: "POST",
                url: "../front/ajax_address_change.php",
                data: formdata,
                dataType: "JSON",
                success: function(r) {
                    alert(r.msg);
                    window.location.reload();
                }
            });
        },
        modify: function (no) {  // 기존 수정
            $('.layer-title').text('배송지 수정');
            $.ajax({
                type: "POST",
                url: "../front/ajax_address_change.php",
                data: {no:no, mode:"getdata"},
                dataType: "JSON"
            }).done(function (data) {
                $("#mode").val("modify");
                $("#no").val(no);
                $("#destination_name").val(data[0]['destination_name']);
                $("#get_name").val(data[0]['get_name']);
                // $("#mobile").val(data[0]['mobile']);
                $("#postcode").val(data[0]['postcode']);
                $("#postcode_new").val(data[0]['postcode_new']);
                $("#addr1").val(data[0]['addr1']);
                $("#addr2").val(data[0]['addr2']);
                $("#btnSubmit").text("수정");
                //$("#submit_type").text("수정");

                var mobile = data[0]['mobile'];
                var phones = mobile.split("-");
                if(phones[0] != null){
                    $("#post_add_phone1").val(phones[0]).attr("selected", "selected");
                }
                if(phones[1] != null){
                    $("#post_add_phone2").val(phones[1]);
                }
                if(phones[2] != null){
                    $("#post_add_phone3").val(phones[2]);
                }

                if(data[0]['base_chk'] == "Y"){
                    $("#base_chk").prop("checked", true);
                }else{
                    $("#base_chk").prop("checked", false);
                }
            });
        },
        search_zip: function (text) {
            daum.postcode.load(function () {
                new daum.Postcode({
                    oncomplete: function (data) {
                        var postcode = data.zonecode; //2015-08-01 시행 새 우편번호
                        var zipCode1 = data.postcode1; //구 우편번호1
                        var zipCode2 = data.postcode2; //구 우편번호2

                        if (data.userSelectedType == 'R') { //도로명
                            var address = data.roadAddress;
                        } else {//지번
                            var address = data.jibunAddress;
                        }

                        $("#postcode_new").val(postcode);
                        $("#postcode").val(zipCode1 + "-" + zipCode2);
                        $("#addr1").val(address);
                    }
                }).open();
            });
        },
        row_delete: function (no) {
            if (confirm('삭제하시겠습니까?')) {
                $("#mode").val("delete");
                $("#no").val(no);
                $.ajax({
                    type: "POST",
                    url: "../front/ajax_address_change.php",
                    data: {no:no, mode:"delete"},
                    dataType: "JSON",
                    success: function(r) {
                        alert(r.msg);
                        window.location.reload();
                    }
                });
            } else {
                return;
            }
        }
    }

    $(function (){
        Address.init();
    });

    function GoPage(block,gotopage) {
        document.destination_form.block.value=block;
        document.destination_form.gotopage.value=gotopage;
        document.destination_form.submit();
    }

</script>

<?php
include('./include/bottom.php');
?>