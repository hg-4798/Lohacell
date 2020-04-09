<?php
/********************************************************************* 
// 파 일 명		: vender_main.php 
// 설     명		: 입점업체 관리자모드 메인
// 상세설명	: 입점업체 관리자모드의 메인화면
// 작 성 자		: hspark
// 수 정 자		: 2015.10.23 - 김재수
// 
// 
*********************************************************************/ 
?>
<?php
#---------------------------------------------------------------
# 기본정보 설정파일을 가져온다.
#---------------------------------------------------------------
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/venderlib.php");
	include("access.php");
#---------------------------------------------------------------
# 기본 날짜를 설정한다.
#---------------------------------------------------------------
$curdate = date("Ymd");
$curdate_1 = date("Ymd",strtotime('-1 day'));

#---------------------------------------------------------------
# 메인에 보여질 쿼리들이만 다시 정리해야함
#---------------------------------------------------------------
	$sql = "SELECT ";
	//오늘 주문건수 및 주문금액
	$sql.= "COUNT(DISTINCT(CASE WHEN (a.ordercode LIKE '".$curdate."%') AND (b.deli_gbn NOT IN('C')) THEN a.ordercode ELSE NULL END)) as totordcnt, ";
	$sql.= "SUM(CASE WHEN (a.ordercode LIKE '".$curdate."%') AND (b.deli_gbn NOT IN('C')) THEN ((b.price+b.option_price)*b.option_quantity)+b.deli_price ELSE 0 END) as totordprice, ";
	//오늘 미배송 건수 및 미배송건 금액
	$sql.= "COUNT(DISTINCT(CASE WHEN (a.ordercode LIKE '".$curdate."%') AND (b.deli_gbn IN('N','X','S')) THEN a.ordercode ELSE NULL END)) as totdelaycnt, ";
	$sql.= "SUM(CASE WHEN (a.ordercode LIKE '".$curdate."%') AND (b.deli_gbn IN('N','X','S')) THEN ((b.price+b.option_price)*b.option_quantity)+b.deli_price ELSE 0 END) as totdelayprice, ";

	//1일전 주문건수 및 주문금액
	$sql.= "COUNT(DISTINCT(CASE WHEN (a.ordercode LIKE '".$curdate_1."%') AND (b.deli_gbn NOT IN('C')) THEN a.ordercode ELSE NULL END)) as totordcnt1, ";
	$sql.= "SUM(CASE WHEN (a.ordercode LIKE '".$curdate_1."%') AND (b.deli_gbn NOT IN('C')) THEN ((b.price+b.option_price)*b.option_quantity)+b.deli_price ELSE NULL END) as totordprice1, ";
	//1일전 미배송 건수 및 미배송건 금액
	$sql.= "COUNT(DISTINCT(CASE WHEN (a.ordercode LIKE '".$curdate_1."%') AND (b.deli_gbn IN('N','X','S')) THEN a.ordercode ELSE NULL END)) as totdelaycnt1, ";
	$sql.= "SUM(CASE WHEN (a.ordercode LIKE '".$curdate_1."%') AND (b.deli_gbn IN('N','X','S')) THEN ((b.price+b.option_price)*b.option_quantity)+b.deli_price ELSE 0 END) as totdelayprice1, ";

	//이달 주문건수 및 매출
	$sql.= "COUNT(DISTINCT(CASE WHEN (a.ordercode LIKE '".substr($curdate,0,6)."%') AND (b.deli_gbn NOT IN('C')) THEN a.ordercode ELSE NULL END)) as totmonordcnt, ";
	$sql.= "SUM(CASE WHEN (a.ordercode LIKE '".substr($curdate,0,6)."%' AND a.deli_gbn='Y') THEN ((b.price+b.option_price)*b.option_quantity)+b.deli_price ELSE 0 END) as totmonordprice, ";

	//이달 미배송 건수 및 미배송건 금액
	$sql.= "COUNT(DISTINCT(CASE WHEN (a.ordercode LIKE '".substr($curdate,0,6)."%') AND (b.deli_gbn IN('N','X','S')) THEN a.ordercode ELSE NULL END)) as totdelaycnt2, ";
	$sql.= "SUM(CASE WHEN (a.ordercode LIKE '".substr($curdate,0,6)."%') AND (b.deli_gbn IN('N','X','S')) THEN ((b.price+b.option_price)*b.option_quantity)+b.deli_price ELSE 0 END) as totdelayprice2 ";

	$sql.= "FROM tblorderinfo a, tblorderproduct b WHERE b.vender='".$_VenderInfo->getVidx()."' AND a.ordercode=b.ordercode ";
	if(substr($curdate,0,6)!=substr($curdate_1,0,6)) {
		$sql.="AND (a.ordercode LIKE '".substr($curdate,0,6)."%' OR a.ordercode LIKE '".$curdate_1."%') ";
	} else {
		$sql.="AND a.ordercode LIKE '".substr($curdate,0,6)."%' ";
	}
	$sql.= "AND NOT (b.productcode LIKE 'COU%' OR b.productcode LIKE '999999%') ";
	$filename=$_VenderInfo->getVidx().".admin.order.cache";

	get_db_cache($sql, $resval, $filename, 30);
	$row=$resval[0];

	$totordcnt=(int)$row->totordcnt;			//오늘 주문건수
	$totordprice=(int)$row->totordprice;		//오늘 주문금액
	$totdelaycnt=(int)$row->totdelaycnt;		//오늘 미배송건수
	$totdelayprice=(int)$row->totdelayprice;	//오늘 미배송금액

	$totordcnt1=(int)$row->totordcnt1;			//1일전 주문건수
	$totordprice1=(int)$row->totordprice1;		//1일전 주문금액
	$totdelaycnt1=(int)$row->totdelaycnt1;		//1일전 미배송건수
	$totdelayprice1=(int)$row->totdelayprice1;	//1일전 미배송금액

	$totmonordcnt=(int)$row->totmonordcnt;		//이달의 주문건수
	$totmonordprice=(int)$row->totmonordprice;	//이달의 매출
	$totdelaycnt2=(int)$row->totdelaycnt2;		//이달의 미배송건수
	$totdelayprice2=(int)$row->totdelayprice2;	//이달의 미배송금액

	include("header.php"); // 상단부분을 불러온다. 
?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function GoNoticeView(artid) {
	url="shop_notice.php?type=view&artid="+artid;
	document.location.href=url;
}
function GoCounselView(artid) {
	url="shop_counsel.php?type=view&artid="+artid;
	document.location.href=url;
}
</script>

<table border=0 cellpadding=0 cellspacing=0 width=1000 style="table-layout:fixed;min-height:500px;">
<col width=175></col>
<col width=5></col>
<col width=740></col>
<col width=80></col>
<tr>
	<td width=175 valign=top nowrap><? include ("menu.php"); // 해당 메뉴부분을 불러온다. ?></td>
	<td width=5 nowrap></td>
	<td valign=top>

	<table width="100%"  border="0" cellpadding="1" cellspacing="0" bgcolor="#D0D1D0" style="table-layout:fixed">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed;border:3px solid #EEEEEE" bgcolor="#ffffff">
		<tr>
			<td style="padding:10">
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<col width=></col>
			<col width=10></col>
			<col width=220></col>
			<tr>
				<td valign=top>
				<!-- 중앙 내용 시작 -->
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed;border:1px solid #EEEEEE" bgcolor="#ffffff">
					<tr>
						<td valign=top style="padding:12,10">
						<table border=0 cellpadding=0 cellspacing=0 width=100% height=100% style="table-layout:fixed">
						<col width=></col>
						<col width=></col>
						<col width=></col>
						<tr>
							<td valign=top style="padding:2">
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<tr height=25>
								<td bgcolor=#FCF7FD style="padding:7,5"><img src=images/icon_dot07.gif border=0 width=5 height=13 align=absmiddle> 오늘 현황 <img src=images/icon_today.gif border=0 align=absmiddle></td>
							</tr>
							<tr><td height=1 bgcolor=#FFFFFF></td></tr>
							<tr>
								<td height=66 valign=top style="padding:5;border:1px solid #E7E7E7">
								<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<col width=60></col>
								<col width=></col>
								<tr>
									<td style="padding-left:5"><A HREF="order_list.php">주문수</A></td>
									<td><font class=verdana style="font-size:8pt"><?=$totordcnt?></font>건</td>
								</tr>
								<tr>
									<td style="padding-left:5"><A HREF="order_list.php">주문액</A></td>
									<td><font class=verdana style="font-size:8pt"><?=number_format($totordprice)?></font>원</td>
								</tr>
								<tr>
									<td style="padding-left:5"><A HREF="order_list.php">미배송</A></td>
									<td><font class=verdana style="font-size:8pt"><?=$totdelaycnt?></font>건</td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>

							<td valign=top style="padding:2">
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<tr height=25>
								<td bgcolor=#FCF7FD style="padding:7,5"><img src=images/icon_dot07.gif border=0 width=5 height=13 align=absmiddle> 어제 현황</td>
							</tr>
							<tr><td height=1 bgcolor=#FFFFFF></td></tr>
							<tr>
								<td height=66 valign=top style="padding:5;border:1px solid #E7E7E7">
								<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<col width=60></col>
								<col width=></col>
								<tr>
									<td style="padding-left:5"><A HREF="order_list.php">주문수</A></td>
									<td><font class=verdana style="font-size:8pt"><?=$totordcnt1?></font>건</td>
								</tr>
								<tr>
									<td style="padding-left:5"><A HREF="order_list.php">주문액</A></td>
									<td><font class=verdana style="font-size:8pt"><?=number_format($totordprice1)?></font>원</td>
								</tr>
								<tr>
									<td style="padding-left:5"><A HREF="order_list.php">미배송</A></td>
									<td><font class=verdana style="font-size:8pt"><?=$totdelaycnt1?></font>건</td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>

							<td valign=top style="padding:2">
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<tr height=25>
								<td bgcolor=#FCF7FD style="padding:7,5"><img src=images/icon_dot07.gif border=0 width=5 height=13 align=absmiddle> 이달 현황</td>
							</tr>
							<tr><td height=1 bgcolor=#FFFFFF></td></tr>
							<tr>
								<td height=66 valign=top style="padding:5;border:1px solid #E7E7E7">
								<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<col width=60></col>
								<col width=></col>
								<tr>
									<td style="padding-left:5"><A HREF="sellstat_list.php">주문수</A></td>
									<td><font class=verdana style="font-size:8pt"><?=$totmonordcnt?></font>건</td>
								</tr>
								<tr>
									<td style="padding-left:5"><A HREF="sellstat_list.php">매출액</A></td>
									<td><font class=verdana style="font-size:8pt"><?=number_format($totmonordprice)?></font>원</td>
								</tr>
								<tr>
									<td style="padding-left:5"><A HREF="order_list.php">미배송</A></td>
									<td><font class=verdana style="font-size:8pt"><?=$totdelaycnt2?></font>건</td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
				<td height=20></td>
				<td valign=top>
<?
					$sql = "SELECT * FROM tblvenderstorecount WHERE vender='".$_VenderInfo->getVidx()."' ";
					$result=pmysql_query($sql,get_db_conn());
					$row=pmysql_fetch_object($result);
					pmysql_free_result($result);
					$prdt_allcnt=$row->prdt_allcnt;
					$prdt_cnt=$row->prdt_cnt;
					$cust_cnt=$row->cust_cnt;
					$count_total=$row->count_total;
					$count_today=0;

					$period_0 = date("Ymd");
					$period_1 = date("Ymd",time()-(60*60*24*1));
					$period_2 = date("Ymd",time()-(60*60*24*2));
					$period_3 = date("Ymd",time()-(60*60*24*3));
					$period_4 = date("Ymd",time()-(60*60*24*4));
					$period_5 = date("Ymd",time()-(60*60*24*5));
					$period_6 = date("Ymd",time()-(60*60*24*6));
					$period_7 = date("Ymd",time()-(60*60*24*7));
					$visit[$period_1]=0;
					$visit[$period_2]=0;
					$visit[$period_3]=0;
					$visit[$period_4]=0;
					$visit[$period_5]=0;
					$visit[$period_6]=0;
					$visit[$period_7]=0;
					$sql = "SELECT date,cnt FROM tblvenderstorevisit ";
					$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
					$sql.= "AND date<='".$period_0."' AND date >='".$period_7."' ";
					$result=pmysql_query($sql,get_db_conn());
					$sumvisit=0;
					while($row=pmysql_fetch_object($result)) {
						if($row->date==$period_0) {
							$count_today=$row->cnt;
						} else {
							$sumvisit=$sumvisit+$row->cnt;
							$visit[$row->date]=$row->cnt;
						}
					}
					pmysql_free_result($result);
?>
				
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed;border:1px solid #EEEEEE" bgcolor="#ffffff">
					<tr>
						<td valign=top style="padding:12,10">
						<table border=0 cellpadding=0 cellspacing=0 width=100% height=100% style="table-layout:fixed">
						<col width=></col>
						<tr>
							<td valign=top style="padding:2">
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<tr height=25>
								<td bgcolor=#FCF7FD style="padding:7,5"><img src=images/icon_dot07.gif border=0 width=5 height=13 align=absmiddle> 내 판매상품 현황</td>
							</tr>
							<tr><td height=1 bgcolor=#FFFFFF></td></tr>
							<tr>
								<td height=66 valign=top style="padding:5;border:1px solid #E7E7E7">
								<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
								<col width=120></col>
								<col width=></col>
								<tr>
									<td style="padding-left:5">상품등록 제한</td>
									<td><?=($_venderdata->product_max>0?"<font class=verdana style=\"font-size:8pt\"><B>".$_venderdata->product_max."</B></font> 개":"<B>무제한</B>")?></td>
								</tr>
								<tr>
									<td style="padding-left:5">등록 상품(판매중)</td>
									<td><font class=verdana style="font-size:8pt"><B><?=$prdt_allcnt?></B></font> 개</td>
								</tr>
								<tr>
									<td style="padding-left:5"><font color=#737373>진열중/진열안함</font></td>
									<td><font class=verdana style="font-size:8pt"><B><?=$prdt_cnt?></B>개/<font class=verdana style="font-size:8pt"><B><?=$prdt_allcnt?></B>개</td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>						
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>				
				</td>
			</tr>
			<tr>
				<td height=20 colspan=3></td>
			</tr>
			<tr>			
				<td valign=top colspan=3>	
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed;border:1px solid #EEEEEE" bgcolor="#ffffff">
					<tr>
						<td valign=top style="padding:12,10">
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<tr>
							<td valign=top bgcolor=#FEFCDA style="padding:7">
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<tr><td height=7></td></tr>
							<tr>
								<td><img src=images/icon_dot07.gif border=0 width=5 height=13 align=absmiddle> 주요기능 바로가기</td>
							</tr>
							<tr><td height=5></td></tr>
							<tr>
								<td bgcolor=#FFFFFF style="padding:10,10;border:1px solid #FFCC00">
								<table border=0 cellpadding=10 cellspacing=0 width=100%>
								<tr>
									<td class="font_size">
									<A HREF="delivery_info.php">배송관련기능설정</A><img src=images/main_center_quick_sel.gif>
									<A HREF="product_deliinfo.php">배송/교환/환불정보 노출</A><img src=images/main_center_quick_sel.gif>
									<A HREF="product_register.php">상품 신규등록</A><img src=images/main_center_quick_sel.gif>
									<A HREF="product_myprd.php">내 상품 관리</A><img src=images/main_center_quick_sel.gif>
									<A HREF="order_list.php">주문조회/배송</A><img src=images/main_center_quick_sel.gif>
									<A HREF="sellstat_list.php">판매상품 정산조회</A>
									</td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</td>
						</tr>	
						</table>
						</td>
					</tr>	
					</table>
					</td>
				</tr>	
				</table>
				</td>
			</tr>
			<tr>
				<td height=10 colspan=3></td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>
</table>
<?=$onload?>
<?php include("copyright.php"); // 하단부분을 불러온다. ?>
