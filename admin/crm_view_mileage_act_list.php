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
$search_s = $search_start?str_replace("-","",$search_start." 00:00:00"):"";
$search_e = $search_end?str_replace("-","",$search_end." 23:59:59"):"";


// 기간선택 조건
if ($search_s != "" || $search_e != "") { 
	$qry.= "AND date_insert >= '{$search_s}' AND date_insert <= '{$search_e}' ";
}

$sql = "Select  count(*) 
        FROM    tblpoint 
        WHERE   mem_id = '".$mem_id."' 
        ".$qry."
       ";
//print_r($sql);
$paging = new newPaging($sql,10,5,'GoPageBoard');
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;


########### E포인트 구하기
$sql = "SELECT  *
        FROM    tblpoint 
        WHERE   mem_id = '".$mem_id."' 
        ".$qry."
        ORDER BY idx DESC 
        ";
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

function actPointInOut(id){
	window.open("about:blank","actpoint_set","width=445,height=750,scrollbars=no");
	document.reserveform.target="actpoint_set";
	document.reserveform.id.value=id;
	document.reserveform.type.value="actpoint";
	document.reserveform.submit();
}

//-->
</script>

			<div class="contentsBody">

                <form name="memo_frm" action="<?=$_SERVER['PHP_SELF']?>" method=GET>
                <input type="hidden" name="id" value="<?=$mem_id?>">
                <input type="hidden" name="menu" value="mileage_act">
				<h3 class="table-title">포인트 조회</h3>
				<table class="th-left">
					<caption>포인트 조회</caption>
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
					<caption>E포인트 리스트</caption>
					<colgroup>
						<col style="width:60px">
						<col style="width:100px">
						<col style="width:auto">
						<col style="width:150px">
						<col style="width:100px">
					</colgroup>
					<thead>
						<tr>
							<th scope="row">번호</th>
							<th scope="row">날짜</th>
							<th scope="row">상세내역</th>
							<th scope="row">적립/사용 포인트</th>
							<th scope="row">유효기간</th>
						</tr>
					</thead>
					<tbody>
<?
                if($cnt_memo > 0) {
                    $cnt = 0;
                    while($row_memo = pmysql_fetch_object($ret_memo)) {

						$number = ($t_count-($setup['list_num'] * ($gotopage-1))-$cnt);
						$expire = ($row_memo->point>0)?substr($row_memo->date_expire,0,10):'';
?>
						<tr>
							<td><?=$number?></td>
							<td><?=substr($row_memo->date_insert,0,10)?></td>
							<td class="ta-l"><?=$row_memo->point_reason?></td>
							<td><?=number_format($row_memo->point)?>P</td>
							<td><?=$expire?></td>
						</tr>
<?
                        $cnt_memo--;
                        $cnt++;
                    }
?>
                        <tr>
                            <td colspan="5" class="ta-c">
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
							<td colspan="5" class="ta-c">내역이 없습니다.</td>
						</tr>
<?
                }
pmysql_free_result($ret_board);
?>
					</tbody>
				</table>

				<form name=idxform action="<?=$_SERVER['PHP_SELF']?>" method=GET>
				<input type="hidden" name="id" value="<?=$mem_id?>">
				<input type="hidden" name="menu" value="mileage_act">
				<input type=hidden name=type>
				<input type=hidden name=block value="<?=$block?>">
				<input type=hidden name=gotopage value="<?=$gotopage?>">
				<input type=hidden name=search_start value="<?=$search_start?>">
				<input type=hidden name=search_end value="<?=$search_end?>">
				</form>

				<form name=reserveform action="actpoint_inout.php" method=post>
				<input type=hidden name=type>
				<input type=hidden name=id>
				</form>



			</div><!-- //.contentsBody -->