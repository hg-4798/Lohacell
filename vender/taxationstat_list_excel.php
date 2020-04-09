<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
include("access.php");

//exdebug($_POST);
//exdebug($_GET);

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",strtotime('-1 month'));

header("Content-type: application/vnd.ms-excel");
Header("Content-Disposition: attachment; filename=taxationstat_list_excel_".date("Ymd",$CurrentTime).".xls");
Header("Pragma: no-cache");
Header("Expires: 0");
Header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
Header("Content-Description: PHP4 Generated Data");


//$s_check    = $_POST["s_check"];
//$search     = trim($_POST["search"]);

$sel_vender     = $_VenderInfo->getVidx();
//$brandname      = $_POST["brandname"];  // 벤더이름 검색



$date_year1      = $_POST["date_year1"]?$_POST["date_year1"]:date("Y");
$date_month1     = $_POST["date_month1"]?$_POST["date_month1"]:date("m");
$date_year2      = $_POST["date_year2"]?$_POST["date_year2"]:date("Y");
$date_month2     = $_POST["date_month2"]?$_POST["date_month2"]:date("m");

$search_start   = $date_year1.$date_month1."01";
$search_end     = $date_year2.$date_month2."31";

$search_start = $search_start?$search_start:date("Ym")."01";
$search_end = $search_end?$search_end:date("Ymd");
$search_s = $search_start?str_replace("-","",$search_start."000000"):"";
$search_e = $search_end?str_replace("-","",$search_end."235959"):"";

// 브랜드 조건
if($sel_vender) {
    $qry.= " and v.vender = ".$sel_vender."";
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

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>

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

    <?php
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
</TABLE>
</body>
</html>