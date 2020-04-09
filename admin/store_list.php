<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");
include("calendar.php");

####################### 페이지 접근권한 check ###############
if (!$_usersession->isAllowedTask($_NAV['current_idx'])) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################
include("header.php"); 

//print_r($_POST);

if($_POST['mode'] == "delete") {
    $sql = "Delete from tblstore Where sno = ".$_POST['sno']."";
    pmysql_query($sql,get_db_conn());
}

################## 브랜드(벤더) 쿼리 ########################
$referer1 = '';
//$ref_qry="SELECT vender,id,com_name,delflag FROM tblvenderinfo ORDER BY com_name ASC";
$ref_qry = "SELECT  a.vender,a.id,a.com_name,a.delflag, b.bridx, b.brandname 
				FROM    tblvenderinfo a 
				JOIN    tblproductbrand b on a.vender = b.vender 
				ORDER BY b.brandname ASC
				";
$ref2_result=pmysql_query($ref_qry);
#########################################################

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",strtotime('-1 month'));
$period[4] = date("Y-m-d",strtotime('-3 month'));
$period[5] = date("Y-m-d",strtotime('-6 month'));

$search_start = $_POST["search_start"];
$search_end = $_POST["search_end"];
$store_name = $_POST["store_name"];
$sel_vender = $_POST["sel_vender"];
$selected[sel_vender][$sel_vender]='selected';
$sel_category = $_POST["sel_category"];
$selected[sel_category][$sel_category]='selected';

$search_start = $search_start?$search_start:$period[0];
$search_end = $search_end?$search_end:$period[0];
$search_s = $search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e = $search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

$termday = (strtotime($search_end)-strtotime($search_start))/86400;
if ($termday>367) {
	alert_go('검색기간은 1년을 초과할 수 없습니다.');
}

//매장명
if($store_name) {
    $where .= " AND    name like '%".$store_name."%' ";
}

//벤더
if($sel_vender) {
    $where .= " AND    vendor = {$sel_vender} ";
}

//매장구분
if($sel_category) {
    $where .= " AND    category = '{$sel_category}' ";
}

$query = "  SELECT  COUNT(*) 
            FROM    tblstore 
            where   1=1 
            ".$where."
        ";
$paging = new Paging($query,10,20);
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;

# 0220 -derklee
/*
$sql = "SELECT  sno, name, location, address, phone, view, area_code, category, vendor, stime, etime, tblstore.display_yn,pickup_yn,delivery_yn,day_delivery_yn,cj_deli_code,
                coordinate, store_code, regdt, com_name , b.brandname
        FROM    tblstore
        join tblvenderinfo on tblstore.vendor = tblvenderinfo.vender LEFT JOIN    tblproductbrand b on tblvenderinfo.vender = b.vender
        where   1=1
        ".$where."
        order by name asc
        ";
*/
$sql = "SELECT  sno, name,  address, phone, view, area_code, category, stime, etime, tblstore.display_yn,pickup_yn,delivery_yn,day_delivery_yn,cj_deli_code,
                coordinate, store_code, regdt
        FROM    tblstore 
        where   1=1 
        ".$where."
        order by name asc 
        ";
$sql = $paging->getSql($sql);
$result=pmysql_query($sql,get_db_conn());
//echo "sql = ".$sql."<br>";
//exit();

?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">


function searchForm() {
	document.form1.action="store_list.php";
	document.form1.submit();
}

function OnChangePeriod(val) {
	var pForm = document.form1;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";
	period[3] = "<?=$period[3]?>";
	period[4] = "<?=$period[4]?>";
	period[5] = "<?=$period[5]?>";


	pForm.search_start.value = period[val];
	pForm.search_end.value = period[0];
}

function GoPage(block,gotopage) {
	document.idxform.block.value = block;
	document.idxform.gotopage.value = gotopage;
	document.idxform.submit();
}

function StoreExcel() {
    //document.form1.target = "_blank";
	document.form1.action="store_list_excel.php";
	document.form1.submit();
	document.form1.action="";
}

function StoreInfo(id){
	window.open("about:blank","store_set","width=800,height=750,scrollbars=no");
	document.storeform.target="store_set";
	document.storeform.sno.value='';	// 0220 -dereklee
    if(id > 0) {
	    document.storeform.sno.value=id;
    } 
	document.storeform.submit();
}

function StoreDel(id){

    if(confirm("정말 삭제하시겠습니까?")) {

        document.form1.mode.value="delete";
        document.form1.sno.value=id;
        document.form1.action="store_list.php";
        document.form1.submit();
    }
}



</script>
<div class="content-wrap">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
					<!-- 페이지 타이틀 -->
					<div class="title_depth3">매장관리<span>매장정보를 확인할 수 있습니다</span></div>
				</td>
			</tr>
			
			<tr>
				<td>
					<!-- 소제목 -->
					<div class="title_depth3_sub">매장 조회</span></div>
				</td>
			</tr>
			<form name=form1 action="<?=$_SERVER['PHP_SELF']?>" method=post>
            <input type=hidden name=mode>
			<input type=hidden name=sno>
			<tr>
				<td>
				
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<div class="table_style01">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
                            <th><span>매장명 입력</span></th>
                            <TD><input name=store_name size=47 value="<?=$store_name?>" class="input"></TD>
                        </TR>
                        <!-- <TR>
                            <th><span>브랜드</span></th>
                            <TD>
                                <select name=sel_vender class="select">
                                    <option value="">==== 전체 ====</option>
<?
                                while($ref2_data=pmysql_fetch_object($ref2_result)){
                                    if ( trim($ref2_data->com_name) == "" ) { continue; }
?>
                                    <option value="<?=$ref2_data->vender?>" <?=$selected[sel_vender][$ref2_data->vender]?>><?=$ref2_data->brandname?></option>
<?}?>
                                </select>&nbsp;
                            </TD>
					    </TR> -->
                        <TR>
                            <th><span>매장구분</span></th>
                            <TD>
                                <select name="sel_category">
                                    <option value="">==== 전체 ====</option>
                                <? foreach ($store_category as $k=>$v){ ?><option value="<?=$k?>" <?=$selected[sel_category][$k]?>><?=$v?><? } ?>
                                </select>
                            </TD>
					    </TR>

						</TABLE>
						</div>
						</td>
					</tr>					
				</table>
				</td>
			</tr>
			<tr>
				<td style="padding-top:4pt;" align="center">
                    <input type="button" onclick="searchForm();" class="btn-point" value="조회하기">
                    <input type="button" onclick="StoreExcel();" class="btn-basic dark" value="엑셀다운로드">&nbsp;
                 </td>
			</tr>
			</form>
			<tr>
				<td height="20"></td>
			</tr>

			<form name=form2 action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<tr>
				<td style="padding-bottom:3pt;">
<?php


		$colspan=10;
?>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="372">&nbsp;</td>
					<td width="" align="right"><img src="images/icon_8a.gif" border="0">총 : <B><?=number_format($t_count)?></B>건, &nbsp;&nbsp;<img src="images/icon_8a.gif" border="0">현재 <b><?=$gotopage?>/<?=ceil($t_count/$setup['list_num'])?></b> 페이지</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td>
				<div class="table_style02">
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
			
				<TR >
					<th>번호</th>
					<th>지역</th>
					<th>매장구분</th>
					<th>매장명</th>
					<th>전화번호</th>
                    <th>노출</th>
                    <th>영업시간</th>
					<th>작성일</th>
					<th>수정</th>
					<th>삭제</th>
				</TR>
<?php
		$colspan=15;

		$cnt=0;
		$thisordcd="";
		$thiscolor="#FFFFFF";
		while($row=pmysql_fetch_object($result)) {
			$number = ($t_count-($setup['list_num'] * ($gotopage-1))-$cnt);

            if( ($number%2)==0 ) $thiscolor="#FEF8ED";
            else $thiscolor="#FFFFFF";

            $regdt = substr($row->regdt, 0, 4)."-".substr($row->regdt, 4, 2)."-".substr($row->regdt, 6, 2)." ".substr($row->regdt, 8, 2).":".substr($row->regdt, 10, 2).":".substr($row->regdt, 12, 2);
?>
                <tr bgcolor=<?=$thiscolor?> onmouseover="this.style.background='#FEFBD1'" onmouseout="this.style.background='<?=$thiscolor?>'">
                    <td><?=number_format($number)?></td>
                    <td><?=$store_area[$row->area_code]?></td>
                    <td><?=$store_category[$row->category]?></td>
                    <td><?=$row->name?> (<?=$row->store_code?>)</td>
                    <td><?=$row->phone?></td>
                    <!-- 0220 -dereklee -->
                    <td><?if($row->view == 0){echo '숨김';} else {echo '노출';}?></td>
                   <!-- 0220 end -->
                    <td><?=$row->stime." ~ ".$row->etime?></td>
                    <td><?=$regdt?></td>
                    <td><a href="javascript:StoreInfo('<?=$row->sno?>');"><img src="img/btn/btn_cate_modify.gif" alt="수정" /></a></td>
                    <td><a href="javascript:StoreDel('<?=$row->sno?>')"><img src="img/btn/btn_cate_del01.gif" alt="삭제" /></a></td>
                </tr>
<?
			$cnt++;
		}
		pmysql_free_result($result);
		if($t_count==0) {
			echo "<tr height=28 bgcolor=#FFFFFF><td colspan={$colspan} align=center>조회된 내용이 없습니다.</td></tr>\n";
		}
?>
				</TABLE>
				</div>
				</td>
			</tr>
			<tr>
				<td align="center">
				<table cellpadding="0" cellspacing="0" width="100%">
<?php				
		echo "<tr>\n";
		echo "	<td width=\"100%\" class=\"font_size\"><p align=\"center\">\n";
		echo "		".$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page;
		echo "	</td>\n";
		echo "</tr>\n";
?>
				</table>
				</td>
			</tr>
            <tr>
                <td align="right">
                    <input type="button" onclick="StoreInfo();" class="btn-point" value="추가하기">
                </td>
            </tr>
            </form>
			<!-- <input type=hidden name=tot value="<?=$cnt?>"> -->
            <tr>
                <td>
                    <!--{* 일괄변경:S *}-->
                    <form id="FrmStoreUpdate">
                        <input type="hidden" name="mode" value="list" />
                        <input type="hidden" name="act" value="batch_excel" />
                    <div class="area-bottom ta_c" style="margin-top: 10px;">
                        <span class="tit">일괄 업데이트</span>
                        <button type="button" class="ml_10" onclick="StoreList.sample()">양식다운로드</button>
                        <span class="bar"></span>
                        <button type="button" data-attach="excel" class="btn-basic dark" style="padding:6px;height:26px;min-width:80px">파일선택</button>
                        <span id="excel_view" class="ml_5">파일을 선택하세요.</span>
                        <input type="file" id="excel" class="hide" name="excel">

                        <button type="button" class="ml_10" onclick="StoreList.batch()">일괄적용</button>
                    </div>
                    </form>
                    <!--{* 일괄변경:E *}-->
                </td>
            </tr>


			<form name=idxform action="<?=$_SERVER['PHP_SELF']?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=ordercodes>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			<input type=hidden name=search_start value="<?=$search_start?>">
			<input type=hidden name=search_end value="<?=$search_end?>">
            <input type=hidden name=store_name value="<?=$store_name?>">
            <input type=hidden name=sel_vender value="<?=$sel_vender?>">
            <input type=hidden name=sel_category value="<?=$sel_category?>">
			</form>

            <!-- 매장 추가 -->
            <form name=storeform action="store_modify.php" method=post>
			<input type=hidden name=type>
            <input type=hidden name=mode>
			<input type=hidden name=sno>
			</form>

			<tr>
				<td height=20></td>
			</tr>
			<tr>
				<td>
				<!-- 매뉴얼 -->
					<div class="sub_manual_wrap">
						<div class="title"><p>매뉴얼</p></div>
						<dl>
							<dt><span>-</span></dt>
							<dd>-</dd>
						</dl>
					</div>
				</td>
			</tr>
			</table></div>
<script type="text/javascript" src="/static/js/blazy.min.js"></script>
<script type="text/javascript" src="/admin/static/js/jquery.form.js?{C.VER}"></script>
<script type="text/javascript" src="/admin/static/js/moment.js"></script>
<script type="text/javascript" src="/admin/static/js/calendar.js"></script>
<script type="text/javascript">
    var StoreList ={
        page:1,
        proc_url:'/admin/proc/store_list.proc.php',
        init: function(){
           $('[data-attach]').on('click', function() {
                var target = $(this).data('attach');
                $('#'+target).trigger('click');
            });

            $('input[type=file]').on('change', this.attach);

            //숫자만입력
            $('[data-filter="numeric"]').on('keydown input', function(evt) {
                var v = $(this).val();
                $(this).val(v.replace(/[^0-9]/g,''));
            });
        },
        attach: function() {
            var input = this;
            var accept = (/\.(xls|xlsx)$/i).test(input.value);
            if(!accept) {
                alert('엑셀 파일만 업로드 가능합니다.');
                return false;
            }
            $('#excel_view').text(input.value);
        },
        callback: function() {
            UI.modalClose();
        },

        batch: function() {
            var file = $('#excel').val();
            if(!file) {
                alert('파일을 선택하세요.');
                return false;
            }

            if(!confirm("일괄적용하시겠습니까?")) return false;
            UI.ing('업데이트중입니다.')
            var options = {
                url:this.proc_url,
                type:'POST',
                dataType:'json',
                success : function(r) {
                    $('#FrmStoreUpdate')[0].reset();
                    $('#excel_view').text('파일을 선택하세요.');
                    UI.ing();
                    if(r.success) {
                        r.data.callback = 'StoreList.callback';
                        UI.modal('/admin/product/product_batch_update.result.php','매장 일괄 간편수정 결과', r.data, 600);
                    }
                    else {
                        alert(r.msg);
                    }
                }

            }
            $('#FrmStoreUpdate').ajaxSubmit(options);
        },
        sample: function() {
            document.location.href='/data/excel/매장일괄간편수정_양식_V0.1.xlsx';
        },

    }


    $(function() {
        StoreList.init();
    })
</script>


<?=$onload?>
<?php 
include("copyright.php");
?>
