<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include("access.php");

//exdebug($_POST);
//exdebug($_GET);
//exdebug($_VenderInfo->getVidx());

$_ShopData=new ShopData($_ShopInfo);
$_ShopData=$_ShopData->shopdata;
$regdate = $_ShopData->regdate;

$date_year1      = $_POST["date_year1"]?$_POST["date_year1"]:date("Y");
$date_month1     = $_POST["date_month1"]?$_POST["date_month1"]:date("m");
$date_year2      = $_POST["date_year2"]?$_POST["date_year2"]:date("Y");
$date_month2     = $_POST["date_month2"]?$_POST["date_month2"]:date("m");

$search_start   = $date_year1.$date_month1."01";
$search_end     = $date_year2.$date_month2."31";


if(ord($date_year)==0) $date_year=date("Y");
if(ord($date_month)==0) $date_month=date("m");

$search_s = $search_start?str_replace("-","",$search_start."000000"):"";
$search_e = $search_end?str_replace("-","",$search_end."235959"):"";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (strtotime($search_end)-strtotime($search_start))/86400;
if ($termday>367) {
    alert_go('검색기간은 1년을 초과할 수 없습니다.');
}

//$vperiod        = (int)$_POST["vperiod"];
$sel_vender     = $_VenderInfo->getVidx();

$search_start = $search_start?$search_start:date("Y-m")."-01";
$search_end = $search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s = $search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[1]."000000");
$search_e = $search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";


// 브랜드 조건
if($sel_vender) {
    $qry.= " and v.vender = ".$sel_vender."";
}

$setup[list_num] = 10000;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];
if ($block != "") {
    $nowblock = $block;
    $curpage  = $block * $setup[page_num] + $gotopage;
} else {
    $nowblock = 0;
}


$subquery = "
            SELECT 'sale' as saletype, o.ordercode, min(o.id) as id, min(o.sender_name) as sender_name, min(o.paymethod) as paymethod,
                    min(o.oldordno) as oldordno, min(is_mobile) as is_mobile,
                    min(o.bank_date) as cdt, min(o.oi_step1) as oi_step1, min(o.oi_step2) as oi_step2, min(op.op_step) as op_step,
                    op.productcode, min(op.productname) as productname, min(op.opt1_name) as opt1_name, min(op.opt2_name) as opt2_name,
                    min(op.text_opt_subject) as text_opt_subject, min(op.text_opt_content) as text_opt_content, min(op.option_price_text) as option_price_text,
                    min(op.vender) as vender, min(v.brandname) as brandname,
                    min(op.price) as price, min(op.option_price) as option_price, min(op.option_quantity) as option_quantity,
                    sum( (op.price+op.option_price) * op.option_quantity) as op_ordprice, sum(op.coupon_price) as op_coupon,
                    sum(op.use_point) as op_usepoint, sum(o.deli_price) as o_deli_price, sum(op.deli_price) as op_deli_price,
                    min(op.rate) as rate, min(p.buyprice) as buyprice, op.idx, min(op.option_type) as option_type,
                    min(op.redelivery_type) as redelivery_type,
                    min(vi.taxation_type) as taxation_type,
                    (sum(case when op.rate = 0   then ((op.price+op.option_price) * op.option_quantity) * (1 - (vi.rate/100::float)) 
                                                 else ((op.price+op.option_price) * op.option_quantity) * (1 - (op.rate/100::float)) end
                    )) as except_commission_price,
                    ((sum(case when op.rate = 0   then ((op.price+op.option_price) * op.option_quantity) * (1 - (vi.rate/100::float)) 
                                                 else ((op.price+op.option_price) * op.option_quantity) * (1 - (op.rate/100::float)) end
                    ))+sum(op.deli_price)) as calculate_price
            FROM    tblorderinfo o
            JOIN    tblorderproduct op on o.ordercode = op.ordercode
            JOIN    tblproductbrand v on op.vender = v.vender
            JOIN    tblproduct p on op.productcode = p.productcode
            JOIN    tblvenderinfo vi on op.vender = vi.vender 
            WHERE   1=1
            AND	    o.bank_date >= '{$search_s}' and o.bank_date <= '{$search_e}'
            AND	    o.oi_step1 in ('1', '2', '3', '4')
            AND 	(o.oi_step2 >= 0 and o.oi_step2 < 45)
            ".$qry."
            GROUP BY o.ordercode, op.productcode, op.idx
            UNION ALL
            SELECT 'refund' as saletype, o.ordercode, min(o.id) as id, min(o.sender_name) as sender_name, min(o.paymethod) as paymethod,
                    min(o.oldordno) as oldordno, min(is_mobile) as is_mobile,
                    oc.cfindt as cdt, min(o.oi_step1) as oi_step1, min(o.oi_step2) as oi_step2, min(op.op_step) as op_step,
                    op.productcode, min(op.productname) as productname, min(op.opt1_name) as opt1_name, min(op.opt2_name) as opt2_name,
                    min(op.text_opt_subject) as text_opt_subject, min(op.text_opt_content) as text_opt_content, min(op.option_price_text) as option_price_text,
                    min(op.vender) as vender, min(v.brandname) as brandname,
                    min(op.price) as price, min(op.option_price) as option_price, min(op.option_quantity) as option_quantity,
                    sum( (op.price+op.option_price) * op.option_quantity) as op_ordprice, sum(op.coupon_price) as op_coupon,
                    sum(op.use_point) as op_usepoint, sum(o.deli_price) as o_deli_price, sum(op.deli_price) as op_deli_price,
                    min(op.rate) as rate, min(p.buyprice) as buyprice, op.idx, min(op.option_type) as option_type,
                    min(op.redelivery_type) as redelivery_type,
                    min(vi.taxation_type) as taxation_type,
                    (sum(case when op.rate = 0   then ((op.price+op.option_price) * op.option_quantity) * (1 - (vi.rate/100::float)) 
                                                else ((op.price+op.option_price) * op.option_quantity) * (1 - (op.rate/100::float)) end
                    )) as except_commission_price,
                    ((sum(case when op.rate = 0   then ((op.price+op.option_price) * op.option_quantity) * (1 - (vi.rate/100::float)) 
                                                 else ((op.price+op.option_price) * op.option_quantity) * (1 - (op.rate/100::float)) end
                    ))+sum(op.deli_price)) as calculate_price
            FROM    tblorderinfo o
            JOIN    tblorderproduct op on o.ordercode = op.ordercode
            JOIN    tblorder_cancel oc on o.ordercode = oc.ordercode and op.oc_no = oc.oc_no
            JOIN    tblproductbrand v on op.vender = v.vender
            JOIN    tblproduct p on op.productcode = p.productcode
            JOIN    tblvenderinfo vi on op.vender = vi.vender 
            WHERE   1=1
            AND	    oc.cfindt >= '{$search_s}' and oc.cfindt <= '{$search_e}'
            AND	    o.oi_step1 in ('1', '2', '3', '4')
            AND 	(o.oi_step2 >= 0 and o.oi_step2 < 45)
            AND	    op.op_step = 44
            ".$qry."
            GROUP BY o.ordercode, op.productcode, op.idx, oc.cfindt
        ";

$sql = "SELECT COUNT(*) as t_count FROM (".$subquery.") a ";
//echo $sql;
/*
$result = pmysql_query($sql,get_db_conn());
while($row = pmysql_fetch_object($result)) {
	$t_count+=$row->t_count;
	$sumprice+=(int)$row->sumprice;
	$sumreserve+=(int)$row->sumreserve;
	$sumdeliprice+=(int)$row->sumdeliprice;
}
pmysql_free_result($result);
*/
list($t_count) = pmysql_fetch($sql, get_db_conn());
//exdebug($t_count);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

include("header.php");
?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="calendar.js.php"></script>
<script language="JavaScript">

    function searchForm() {
        document.sForm.submit();
    }

    function OrderDetailView(ordercode) {
        document.detailform.ordercode.value = ordercode;
        window.open("","vorderdetail","scrollbars=yes,width=800,height=600");
        document.detailform.submit();
    }

    function GoPage(block,gotopage) {
        document.pageForm.block.value=block;
        document.pageForm.gotopage.value=gotopage;
        document.pageForm.submit();
    }

    function GoOrderby(orderby) {
        document.pageForm.block.value = "";
        document.pageForm.gotopage.value = "";
        document.pageForm.orderby.value = orderby;
        document.pageForm.submit();
    }

    function OrderExcel() {
        document.sForm.action="taxationstat_list_excel.php";
        document.sForm.target="processFrame";
        document.sForm.submit();
        document.sForm.target="";
        document.sForm.action="";
    }
</script>

<!-- <table border=0 cellpadding=0 cellspacing=0 width=1000 style="table-layout:fixed"> -->
<table border=0 cellpadding=0 cellspacing=0 width=1480 style="table-layout:fixed">
    <col width=175></col>
    <col width=5></col>
    <!-- <col width=740></col> -->
    <col width=1300></col>
    <!-- <col width=80></col> -->
    <tr>
        <td width=175 valign=top nowrap><? include ("menu.php"); ?></td>
        <td width=5 nowrap></td>
        <td valign=top>

            <table width="100%"  border="0" cellpadding="1" cellspacing="0" bgcolor="#D0D1D0">
                <tr>
                    <td>
                        <table width="100%"  border="0" cellpadding="0" cellspacing="0" style="border:3px solid #EEEEEE" bgcolor="#ffffff">
                            <tr>
                                <td style="padding:10">
                                    <table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
                                        <tr>
                                            <td>
                                                <table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
                                                    <col width=165></col>
                                                    <col width=></col>
                                                    <tr>
                                                        <td height=29 align=center background="images/tab_menubg.gif">
                                                            <FONT COLOR="#ffffff"><B>세금계산서 조회</B></FONT>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr><td height=2 bgcolor=red></td></tr>
                                        <tr>
                                            <td bgcolor=#FBF5F7>
                                                <table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
                                                    <col width=10></col>
                                                    <col width=></col>
                                                    <col width=10></col>
                                                    <tr>
                                                        <td colspan=3 style="padding:15,15,5,15">
                                                            <table border=0 cellpadding=0 cellspacing=0 width=100%>
                                                                <tr>
                                                                    <td style="padding-bottom:5"><img src="images/icon_boxdot.gif" border=0 align=absmiddle> <B>월별 조회</B></td>
                                                                </tr>
                                                                <!--<tr>
                                                                    <td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 입점사가 등록한 상품에 대해서만  조회할 수 있습니다.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding-left:5;color:#7F7F7F"><img src="images/icon_dot02.gif" border=0> 입점사는 통계자료만 열람할 수 있으며, 통계자료 수정 및 삭제는 본사 관리자만 가능합니다.</td>
                                                                </tr>-->
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><img src="images/tab_boxleft.gif" border=0></td>
                                                        <td></td>
                                                        <td><img src="images/tab_boxright.gif" border=0></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                        <!-- 처리할 본문 위치 시작 -->
                                        <tr><td height=0></td></tr>
                                        <tr>
                                            <td style="padding:5">

                                                <table border=0 cellpadding=0 cellspacing=0 width=100%>
                                                    <form name=sForm action="<?=$_SERVER[PHP_SELF]?>" method=post>
                                                        <input type=hidden name=code value="<?=$code?>">
                                                        <tr>
                                                            <td valign=top bgcolor=D4D4D4 style=padding:1>
                                                                <table border=0 cellpadding=0 cellspacing=0 width=100%>
                                                                    <tr>
                                                                        <td valign=top bgcolor=F0F0F0 style=padding:10>
                                                                            <table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
                                                                                <tr>
                                                                                    <td>
                                                                                        &nbsp;<U>기간선택</U>&nbsp;
                                                                                        <select name=date_year1 class="select" style="width:60px;">
                                                                                            <?php
                                                                                            for($i=substr($regdate,0,4);$i<=date("Y");$i++) {
                                                                                                echo "<option value=\"{$i}\" ";
                                                                                                if($i==$date_year1) echo "selected";
                                                                                                echo ">{$i}</option>\n";
                                                                                            }
                                                                                            ?>
                                                                                        </select>년
                                                                                        <select name=date_month1 class="select" style="width:50px;">
                                                                                            <?php
                                                                                            for($i=1;$i<=12;$i++) {
                                                                                                $ii=sprintf("%02d",$i);
                                                                                                echo "<option value=\"{$ii}\" ";
                                                                                                if($i==$date_month1) echo "selected";
                                                                                                echo ">{$ii}</option>\n";
                                                                                            }
                                                                                            ?>
                                                                                        </select>월 ~ &nbsp;
                                                                                        <select name=date_year2 class="select" style="width:60px;">
                                                                                            <?php
                                                                                            for($i=substr($regdate,0,4);$i<=date("Y");$i++) {
                                                                                                echo "<option value=\"{$i}\" ";
                                                                                                if($i==$date_year2) echo "selected";
                                                                                                echo ">{$i}</option>\n";
                                                                                            }
                                                                                            ?>
                                                                                        </select>년
                                                                                        <select name=date_month2 class="select" style="width:50px;">
                                                                                            <?php
                                                                                            for($i=1;$i<=12;$i++) {
                                                                                                $ii=sprintf("%02d",$i);
                                                                                                echo "<option value=\"{$ii}\" ";
                                                                                                if($i==$date_month2) echo "selected";
                                                                                                echo ">{$ii}</option>\n";
                                                                                            }
                                                                                            ?>
                                                                                        </select>월
                                                                                        &nbsp;
                                                                                        <A HREF="javascript:searchForm()"><img src=images/btn_inquery03.gif border=0 align=absmiddle></A>
                                                                                        <A HREF="javascript:OrderExcel()"><img src=images/btn_exceldown.gif border=0 align=absmiddle></A>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr><td height=5></td></tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </form>
                                                </table>

                                                <table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
                                                    <col width=130></col>
                                                    <col width=200></col>
                                                    <col width=></col>
                                                    <tr><td colspan=3 height=20></td></tr>
                                                    <tr>
                                                        <td colspan=2 style="padding-bottom:2">
                                                            <!-- <B>정렬방법</B>
					<select name=orderby onchange="GoOrderby(this.options[this.selectedIndex].value)">
					<option value="deli_date" <?if($orderby=="deli_date")echo"selected";?>>구입결정일</option>
					<option value="ordercode" <?if($orderby=="ordercode")echo"selected";?>>주문코드</option>
					</select> -->
                                                        </td>

                                                        <?

                                                        $colspan=6;

                                                        // 리스트 구하기
                                                        $sql = "SELECT substring(cdt, 1, 6) as cdt_month,* from (
                            ".$subquery."
                        ) a
                        ORDER BY cdt_month asc, saletype asc
                        ";
                                                        $result=pmysql_query($sql,get_db_conn());
                                                        //exdebug($sql);
                                                        //echo "sql = ".$sql."<br>";

                                                        $sales = array();           // 전체 배열
                                                        $tot_sale_cnt_ord = 0;      // 전체 결제 주문 수량
                                                        $tot_sale_cnt_prod = 0;     // 전체 결제 상품 수량
                                                        $tot_sale_ordprice = 0;     // 전체 결제 주문금액
                                                        $tot_sale_supply_price = 0; // 전체 결제 공급가금액
                                                        $tot_sale_deliprice = 0;    // 전체 결제 배송비 금액

                                                        $tot_refund_cnt_ord = 0;    // 전체 환불 주문 수량
                                                        $tot_refund_cnt_prod = 0;   // 전체 환불 상품 수량
                                                        $tot_refund_ordprice = 0;   // 전체 환불 주문금액
                                                        $tot_refund_supply_price = 0;   // 전체 환불 공급가금액
                                                        $tot_refund_deliprice = 0;  // 전체 환불 배송비 금액

                                                        while($row=pmysql_fetch_object($result)) {
                                                            $cdt = $row->cdt;
                                                            $saletype = $row->saletype;
                                                            $cnt_ord = $row->cnt_ord;
                                                            $cnt_prod = $row->cnt_prod;
                                                            $ordprice = $row->ordprice;
                                                            $coupon = $row->coupon;
                                                            $usepoint = $row->usepoint;
                                                            $o_deliprice = $row->o_deli_price;
                                                            //$op_deliprice = $row->op_deli_price;
                                                            $real_price = $ordprice - $coupon - $usepoint + $op_deliprice;


                                                            $thiscolor="#FFFFFF";
                                                            if($row->saletype == "refund") {
                                                                $thiscolor="#ffeeff";
                                                                $minus = -1;
                                                            } else {
                                                                $minus = 1;
                                                            }

                                                            $op_deliprice = $row->op_deli_price * $minus;
                                                            $except_commission_price    = $row->except_commission_price * $minus;
                                                            //$calculate_price            = $row->except_commission_price + $row->op_deliprice * $minus;
                                                            $calculate_price            = $row->calculate_price * $minus;
                                                            $cdt_month                  = $row->cdt_month;
                                                            $taxation_type              = $row->taxation_type;

                                                            $sales[$cdt_month]['op_deliprice'] += $op_deliprice;
                                                            $sales[$cdt_month]['except_commission_price'] += $except_commission_price;
                                                            $sales[$cdt_month]['calculate_price'] += $calculate_price;
                                                            $sales[$cdt_month]['calculate_supply_price'] += $calculate_price/1.1;
                                                            $sales[$cdt_month]['calculate_taxation_price'] += $calculate_price/11;

                                                            switch($taxation_type) {

                                                                case 3 :
                                                                    $sales[$cdt_month]['calculate_taxation_bill']  = $sales[$cdt_month]['except_commission_price']/1.1 + $sales[$cdt_month]['op_deliprice'];
                                                                    $sales[$cdt_month]['calculate_supply_price'] = 0;
                                                                    $sales[$cdt_month]['calculate_taxation_price'] = 0;
                                                                    break;

                                                                case 4 :
                                                                    $sales[$cdt_month]['calculate_taxation_bill'] = $sales[$cdt_month]['calculate_price'];
                                                                    $sales[$cdt_month]['calculate_supply_price']  = 0;
                                                                    $sales[$cdt_month]['calculate_taxation_price'] = 0;
                                                                    break;

                                                                default :
                                                                    $sales[$cdt_month]['calculate_taxation_bill']  = $sales[$cdt_month]['calculate_price'];
                                                                    $sales[$cdt_month]['calculate_supply_price'] = $sales[$cdt_month]['calculate_supply_price'];
                                                                    $sales[$cdt_month]['calculate_taxation_price'] = $sales[$cdt_month]['calculate_taxation_price'];
                                                                    break;


                                                            }
                                                            $cnt++;
                                                        }
                                                        pmysql_free_result($result);

                                                        $t_count = count($sales);

                                                        ?>

                                                        <td align=right valign=bottom>
                                                            총 주문수 : <B><?=number_format($t_count)?></B>건
                                                        </td>
                                                    </tr>
                                                    <tr><td colspan=3 height=1 bgcolor=red></td></tr>
                                                </table>

                                                <table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:">
                                                    <col width=40></col>
                                                    <col width=40></col>
                                                    <col width=80></col>
                                                    <!-- <col width=120></col> -->
                                                    <col width=140></col>
                                                    <col width=80></col>
                                                    <col width=200></col>
                                                    <tr height=32 align=center bgcolor=F5F5F5>
                                                        <th>발행일자</th>
                                                        <th>마진제외금액 [상품구매금액*(1-수수료율)]</th>
                                                        <th>세금계산서 금액</th>
                                                        <th>공급가액</th>
                                                        <th>세액</th>
                                                        <th>정산금액 [위탁수수료 + 배송비]</th>
                                                    </tr>

                                                    <?
                                                    $cnt=0;
                                                    $thisordcd="";
                                                    $thiscolor="#FFFFFF";
                                                    foreach($sales as $k => $v) {

                                                        ?>


                                                        <tr bgcolor=<?=$thiscolor?> onmouseover="this.style.background='#FEFBD1'" onmouseout="this.style.background='<?=$thiscolor?>'" height=40>
                                                            <td align="center"><?=substr($k, 0, 4)."-".substr($k, 4, 2)?></td>
                                                            <td align=right><?=number_format($v['except_commission_price'])?></td>
                                                            <td align=right><?=number_format($v['calculate_taxation_bill'])?></td>
                                                            <td align=right><?=number_format($v['calculate_supply_price'])?></td>
                                                            <td align=right><?=number_format($v['calculate_taxation_price'])?></td>
                                                            <td align=right><?=number_format($v['calculate_price'])?></td>
                                                        </tr>
                                                        <?
                                                        $i++;
                                                    }
                                                    ?>
                                                    <?


                                                    if($t_count==0) {
                                                        echo "<tr height=28 bgcolor=#FFFFFF><td colspan=".$colspan." align=center>조회된 내용이 없습니다.</td></tr>\n";
                                                    }
                                                    ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <!-- 처리할 본문 위치 끝 -->

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    <form name=detailform method="post" action="order_detail.php" target="vorderdetail">
        <input type=hidden name=ordercode>
    </form>

    <form name=pageForm method=post action="<?=$_SERVER[PHP_SELF]?>">
        <input type=hidden name=search_start value="<?=$search_start?>">
        <input type=hidden name=search_end value="<?=$search_end?>">
        <input type=hidden name=s_check value="<?=$s_check?>">
        <input type=hidden name=search value="<?=$search?>">
        <input type=hidden name=orderby value="<?=$orderby?>">
        <input type=hidden name=block>
        <input type=hidden name=gotopage>
    </form>
</table>

<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>
<?php include("copyright.php"); ?>
