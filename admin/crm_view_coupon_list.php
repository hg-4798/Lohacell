<?
//print_r($_GET);
//print_r($_POST);
$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",strtotime('-1 month'));


$search_start   = $_GET["search_start"];
$search_end     = $_GET["search_end"];

$search_start = $search_start?$search_start:"";
$search_end = $search_end?$search_end:"";
$search_s = $search_start?$search_start." 00:00:00":"";//0000
$search_e = $search_end?$search_end." 23:59:59":"";//5959


// 기간선택 조건
if ($search_s != "" || $search_e != "") { 
	$qry.= "AND (date_start >= '{$search_s}' AND date_end <= '{$search_e}') ";
}

$sql = "Select  count(*) 
        FROM    tblcouponissue  
       WHERE   id = '".$mem_id."' 
        ".$qry."
       ";
$paging = new newPaging($sql,10,5,'GoPageBoard');
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;


########### 쿠폰 구하기
$sql = "SELECT 	*
        FROM 	tblcouponissue 
        WHERE   id = '".$mem_id."' 
        ".$qry."
        ORDER BY  ci_no DESC ,coupon_code desc
        ";

//print_r($sql);

$sql = $paging->getSql($sql);
$ret_memo = pmysql_query($sql, get_db_conn());
$cnt_memo = pmysql_num_rows($ret_memo);
?>
<script type="text/javascript">
<!--
function searchForm() {
	//document.form1.action="order_list_all_order.php";
	document.memo_frm.submit();
}

function OnChangePeriod(val) {
    //alert(val);
	var pForm = document.memo_frm;
	var period = new Array(7);
	period[0] = "<?=$period[0]?>";
	period[1] = "<?=$period[1]?>";
	period[2] = "<?=$period[2]?>";
	period[3] = "<?=$period[3]?>";
	
    if(val < 4) {
	    pForm.search_start.value = period[val];
	    pForm.search_end.value = period[0];
    }else{
	    pForm.search_start.value = '';
	    pForm.search_end.value = '';
    }
}

function GoPageBoard(block,gotopage) {
	document.idxform.block.value = block;
	document.idxform.gotopage.value = gotopage;
	document.idxform.submit();
}

function ReserveInOut(id){
	window.open("about:blank","reserve_set","width=445,height=750,scrollbars=no");
	document.reserveform.target="reserve_set";
	document.reserveform.id.value=id;
	document.reserveform.type.value="reserve";
	document.reserveform.submit();
}

//-->
</script>

			<div class="contentsBody">

                <form name="memo_frm" action="<?=$_SERVER['PHP_SELF']?>" method=GET>
                <input type="hidden" name="id" value="<?=$mem_id?>">
                <input type="hidden" name="menu" value="coupon">
				<h3 class="table-title">쿠폰 조회</h3>
				<table class="th-left">
					<caption>쿠폰 조회</caption>
					<colgroup>
						<col style="width:18%"><col style="width:82%">
					</colgroup>
					<tbody>

						<TR>
							<th scope="row">날짜</th>
							<td>
                                <div class="date-choice">
                                    <input class="w100" type="text" name="search_start" OnClick="Calendar(event)" value="<?=$search_start?>"/> ~ <input class="w100" type="text" name="search_end" OnClick="Calendar(event)" value="<?=$search_end?>"/>
                                    <button OnClick="javascript:OnChangePeriod(0);" class="btn-line" type="button"><span>오늘</span></button>
                                    <button OnClick="OnChangePeriod(1)" class="btn-line" type="button"><span>7일</span></button>
                                    <button OnClick="OnChangePeriod(2)" class="btn-line" type="button"><span>14일</span></button>
                                    <button OnClick="OnChangePeriod(3)" class="btn-line" type="button"><span>한달</span></button>
                                    <button OnClick="OnChangePeriod(4)" class="btn-line" type="button"><span>전체</span></button>
                                </div>
							</td>
						</TR>

					</tbody>
				</table>
				<div class="btn-place">
					<a href="javascript:searchForm();" class="btn-dib on">검색</a>
				</div>
                </form>


				<table class="th-top">
					<caption>쿠폰 리스트</caption>
					<colgroup>
						<col style="width:60px">
						<col style="width:100px">
						<col style="width:auto">
						<col style="width:100px">
						<col style="width:100px">
						<col style="width:200px">
					</colgroup>
					<thead>
						<tr>
							<th scope="row">번호</th>
							<th scope="row">쿠폰타입</th>
							<th scope="row">쿠폰명</th>
							<th scope="row">사용혜택</th>
							<th scope="row">사용기준</th>
							<th scope="row">유효기간</th>
						</tr>
					</thead>
					<tbody>
<?
                if($cnt_memo > 0) {
                    $cnt = 0;
                    while($row_memo = pmysql_fetch_object($ret_memo)) {

                        $number = ($t_count-($setup['list_num'] * ($gotopage-1))-$cnt);

                        $couponcheck = "";
                        if($row_memo->date_end < NOW ){

                            if($row_memo->used=="Y") $couponcheck = "사용";
                            else $couponcheck = "사용불가";

                        } else if($row_memo->used == "Y") {
                            $couponcheck = "사용";
                        } else {
                            $couponcheck = "미사용";
                        }
?>
						<tr>
							<td><?=$number?></td>
							<td><?if($row_memo->type_use=='P'){ echo "상품별쿠폰";}else if($row_memo->type_use=='B'){echo "장비구니";}else{echo "무료배송쿠폰";}?></td>
							<td class="ta-l"><?=$row_memo->coupon_name?></td>
							<td><?=number_format($row_memo->sale_price)?><?if($row_memo->sale_type=='K')echo "원";else echo"%";?></td>
							<td><?=$couponcheck?></td>
                            <td><?=$row_memo->date_start?><br>~<br><?=$row_memo->date_end?></td>
						</tr>
<?
                        $cnt_memo--;
                        $cnt++;
                    }
?>
                        <tr>
                            <td colspan="7" class="ta-c">
                                <div id="page_navi01" style="height:'40px'">
                                    <div class="page_navi">
                                        <ul>
                                            <?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
<?
                } else {
?>
						<tr>
							<td colspan="7" class="ta-c">내역이 없습니다.</td>
						</tr>
<?
                }
pmysql_free_result($ret_board);
?>
					</tbody>
				</table>

                <form name=idxform action="<?=$_SERVER['PHP_SELF']?>" method=GET>
                <input type="hidden" name="id" value="<?=$mem_id?>">
                <input type="hidden" name="menu" value="coupon">
                <input type=hidden name=type>
                <input type=hidden name=block value="<?=$block?>">
                <input type=hidden name=gotopage value="<?=$gotopage?>">
                <input type=hidden name=search_start value="<?=$search_start?>">
                <input type=hidden name=search_end value="<?=$search_end?>">
                </form>

                <form name=reserveform action="reserve_money_new.php" method=post>
                <input type=hidden name=type>
                <input type=hidden name=id>
                </form>


				<dl class="help-attention mt-50">
					<dt>도움말</dt>
					<!-- <dd>1. 비회원인 경우는 어쩌고 저쩌고</dd>
					<dd>2. 회원인 경우는 어쩌고 저쩌고</dd> -->
				</dl>


			</div><!-- //.contentsBody -->