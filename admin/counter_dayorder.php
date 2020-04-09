<?php // hspark
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "st-1";
$MenuCode = "counter";
if (!$_usersession->isAllowedTask($PageCode)) {
	include("AccessDeny.inc.php");
	exit;
}
#########################################################

$searchdate=$_POST["searchdate"];
$print=$_POST["print"];

if(ord($searchdate)==0) $searchdate=date("Ym");
if($searchdate==date("Ym")) $nowdate="Y";

list($year,$mon)=sscanf($searchdate,'%4s%2s');

$lastdays = array("0","31","28","31","30","31","30","31","31","30","31","30","31");
$lastdays[2] = date("t",strtotime("$year-02-01"));

include("header.php"); 
?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function search_date() {
	document.form1.submit();
}

function view_printpage(){
	window.open("about:blank","popviewprint","height=550,width=700,scrollbars=yes");
	document.form2.print.value="Y";
	document.form2.submit();
}

</script>
<div class="content-wrap">
			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr>
				<td>
					<!-- 페이지 타이틀 -->
					<div class="title_depth3">일자별 주문시도건수</div>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td align=center>
				<table cellpadding="0" cellspacing="0" width="100%">
				<form name=form1 method=post action="<?=$_SERVER['PHP_SELF']?>">
				<input type=hidden name=print value="<?=$print?>">
				<tr>
					<td align=center><img src="graph/dayorder.php?date=<?=$searchdate?>"></td>
				</tr>
<?php
				if($searchdate>=date("Ym",strtotime('-1 month'))) {
					$sql ="SELECT SUBSTR(date,7,2) as day,sum(cnt) as cnt FROM tblcounterorder 
					WHERE date LIKE '{$searchdate}%' GROUP BY day ";
				} else {	//1달 후 데이터는 월 데이타 테이블에서 찾는다.
					$sql ="SELECT SUBSTR(date,7,2) as day, cnt FROM tblcounterordermonth 
					WHERE date LIKE '{$searchdate}%'";
				}
				$sum=0;
				$result = pmysql_query($sql,get_db_conn());
				while($row = pmysql_fetch_object($result)){
					$time[$row->day]=$row->cnt;
					if($max<$row->cnt) $max=$row->cnt;
					$sum+=$row->cnt;
				}
				pmysql_free_result($result);
?>
				<tr>
					<td height="3" style="font-size:11px;">
<?php
					if($nowdate=="Y") {
						echo "* <b><font color=\"#FF6633\">".date("Y년 m월 d일")."</font></b> 현재";
					} else {
						echo "* <b><font color=\"#FF6633\">".substr($searchdate,0,4)."년 ".substr($searchdate,4,2)."월</font></b>";
					}
					echo " 일자별 주문시도 현황 입니다.";
?>
				</tr>
				<tr>
					<td>
                    <div class="table_style02">
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<TR>
                    	<th>날짜</th>
                        <th>요일</th>
                        <th>주문시도건수</th>
                        <th>퍼센트</th>
                        <th style="border-left-width:1pt; border-left-color:silver; border-left-style:dashed;">날짜</th>
                        <th>요일</th>
                        <th>주문시도건수</th>
                        <th>퍼센트</th>
					</TR>
<?php
					$weekname = array("<font color=#FF0000>일</font>","월","화","수","목","금","<font color=#0000FF>토</font>");
					$week = date("w",strtotime('first day of this month'))-1;
					if($searchdate==date("Ym")) $hour=date("d"); 
					else $hour=$lastdays[(int)$mon];
					$half=ceil($lastdays[(int)$mon]/2);

					for($i=1;$i<=$half;$i++) {
						$count = sprintf("%02d",$i);
						$count2=$i+$half;
						if($sum>0) $percent[$count]=$time[$count]/$sum*100;
						else $percent[$count]=0;
						if($pos=strpos($percent[$count],".")) {
							$percent[$count]=substr($percent[$count],0,$pos+3);
						}
						if($sum>0) $percent[$count2]=$time[$count2]/$sum*100;
						else $percent[$count2]=0;
						if($pos=strpos($percent[$count2],".")) {
							$percent[$count2]=substr($percent[$count2],0,$pos+3);
						}

						$visitcnt="&nbsp;";
						$strpercent="&nbsp;";
						if($count<=$hour) {
							$visitcnt=number_format($time[$count]);
							$strpercent=$percent[$count]."%";
						}
						$visitcnt2="&nbsp;";
						$strpercent2="&nbsp;";
						if($count2<=$hour) {
							$visitcnt2=number_format($time[$count2]);
							$strpercent2=$percent[$count2]."%";
						}

						echo "<tr>\n";
						echo "	<TD class=\"td_con2a\" align=center".($max>0 && $max==$time[$count]?" bgcolor=#E1F1FF":"").">".($max>0 && $max==$time[$count]?"<b><font color=#000000>{$count}</font></b>":$count)."</td>\n";
						echo "	<TD class=\"td_con1a\" align=center".($max>0 && $max==$time[$count]?" bgcolor=#E1F1FF":"").">".($max>0 && $max==$time[$count]?"<b><font color=#000000>".$weekname[($count+$week)%7]."</font></b>":$weekname[($count+$week)%7])."</td>\n";
						echo "	<TD class=\"td_con1a\" align=center".($max>0 && $max==$time[$count]?" bgcolor=#E1F1FF":"")."><font color=#00769D>".($max>0 && $max==$time[$count]?"<b>{$visitcnt}</b>":$visitcnt)."</font></td>\n";
						echo "	<TD class=\"td_con1a\" align=center".($max>0 && $max==$time[$count]?" bgcolor=#E1F1FF":"").">".($max>0 && $max==$time[$count]?"<b><font color=#000000>{$strpercent}</font></b>":$strpercent)."</td>\n";

						echo "	<TD class=\"td_con2a\" align=center".($max>0 && $max==$time[$count2]?" bgcolor=#E1F1FF":"")." style=\"border-left-width:1pt; border-left-color:silver; border-left-style:dashed;\">".($max>0 && $max==$time[$count2]?"<b><font color=#000000>{$count2}</font></b>":($count2<=$lastdays[(int)$mon]?$count2:"&nbsp;"))."</td>\n";
						echo "	<TD class=\"td_con1a\" align=center".($max>0 && $max==$time[$count2]?" bgcolor=#E1F1FF":"").">".($max>0 && $max==$time[$count2]?"<b><font color=#000000>".$weekname[($count2+$week)%7]."</font></b>":($count2<=$lastdays[(int)$mon]?$weekname[($count2+$week)%7]:"&nbsp;"))."</td>\n";
						echo "	<TD class=\"td_con1a\" align=center".($max>0 && $max==$time[$count2]?" bgcolor=#E1F1FF":"")."><font color=#00769D>".($max>0 && $max==$time[$count2]?"<b>{$visitcnt2}</b>":($count2<=$lastdays[(int)$mon]?$visitcnt2:"&nbsp;"))."</font></td>\n";
						echo "	<TD class=\"td_con1a\" align=center".($max>0 && $max==$time[$count2]?" bgcolor=#E1F1FF":"").">".($max>0 && $max==$time[$count2]?"<b><font color=#000000>{$strpercent2}</font></b>":($count2<=$lastdays[(int)$mon]?$strpercent2:"&nbsp;"))."</td>\n";

						echo "</tr>\n";
					}
?>
					</table>
					</td>
				</tr>
				<?php if($print!="Y"){?>
				<TR>
					<TD width="100%" background="images/counter_blackline_bg.gif" height="30" align=right>
					<table cellpadding="0" cellspacing="0">
					<tr>
						<td class="font_white" align=right>
						지난 접속통계 
						<select name=searchdate onchange="search_date()">
<?php
						$cnt=11;  
						for($i=0;$i<=$cnt;$i++) {
							$date=date("Ym",strtotime("-{$i} month"));
							echo "<option value=\"{$date}\"";
							if($date==$searchdate) echo " selected";
							echo ">".substr($date,0,4)."년 ".substr($date,4,2)."월</option>\n";
						}
?>
						</select>
						</td>
						<td align=right style="padding:0,5,0,5"><A HREF="javascript:view_printpage()"><img src="images/counter_btn_print.gif" width="90" height="20" border="0"></A></td>
					</tr>
					</table>
					</TD>
				</TR>
				<?php } else {?>
				<TR>
					<td align=right style="padding:20,20,0,5"><A HREF="javascript:print()"><img src="images/counter_btn_print.gif" width="90" height="20" border="0"></A></td>
				</TR>
				<?php }?>
				</form>
				</table>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
<?php if($print!="Y"){?>
			<tr>
				<td>
				<!-- 메뉴얼 -->
				<div class="sub_manual_wrap">
					<div class="title"><p>매뉴얼</p></div>
					<dl>
					  <dt><span>일자별 쇼핑몰 주문시도건수를 보여 드리고 있습니다.</span></dt>
                    </dl>
                    <dl>
                    	<dt><span>한달기준으로 방문자가 쇼핑몰에 방문하여 얼마나 많은 주문을 했는지 알 수 있습니다.<br />일자별 주문시도건수, 방문자수, 페이지뷰를 분석하여 각 요인이 쇼핑몰에 어떻게 반영되고 있는 지 분석할 수 있습니다. </span></dt>
                    </dl>
                    <dl>
                    	<dt><span>한달기준으로 최고 주문시도건수를 나타낸 일자의 방문자수와 페이지뷰를 분석하여, 주문시도건수와의 상관관계를 알 수 있습니다.<br />
예를 들면, 1월중 11일에 최고 주문시도건수인 150건이 발생했는 데, 이 날의 순방문자 순위는 10위이고, 페이지뷰는 2위인 경우, <br />이날 신규상품이나 인기상품의 등록등 컨텐츠 요소에 의한 주문시도건수의 순증가를 나타냈다고 볼 수 있습니다.<br />
외부요인인 순방문자가 증가하여 주문이 증가한 것이 아니고, 페이지뷰, 즉 내부요인에 의해서 주문이 증가한 경우입니다.<br />
그리고, 이 날의 컨텐츠의 갱신은 아주 성공적이었다고 평가할 수 있습니다 </span></dt>
                    </dl>
                </div>				
                </td>
			</tr>
			</table>
</div>

<form name=form2 method=post action="<?=$_SERVER['PHP_SELF']?>"  target=popviewprint>
<input type=hidden name=print>
<input type=hidden name=searchdate value=<?=$searchdate?>>
</form>
<?php 
include("copyright.php"); 
}