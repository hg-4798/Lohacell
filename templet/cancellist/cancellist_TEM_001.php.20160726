<?php
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}

$imagepath=$Dir.DataDir."shopimages/etc/main_logo.gif";
$flashpath=$Dir.DataDir."shopimages/etc/main_logo.swf";

if (file_exists($imagepath)) {
	$mainimg="<img src=\"".$imagepath."\" border=\"0\" align=\"absmiddle\">";
} else {
	$mainimg="";
}
if (file_exists($flashpath)) {
	if (preg_match("/\[MAINFLASH_(\d{1,4})X(\d{1,4})\]/",$_data->shop_intro,$match)) {
		$width=$match[1];
		$height=$match[2];
	}
	$mainflash="<script>flash_show('".$flashpath."','".$width."','".$height."');</script>";
} else {
	$mainflash="";
}
$pattern=array("(\[DIR\])","(\[MAINIMG\])","/\[MAINFLASH_(\d{1,4})X(\d{1,4})\]/");
$replace=array($Dir,$mainimg,$mainflash);
$shop_intro=preg_replace($pattern,$replace,$_data->shop_intro);


//$mb_qry="select * from tblmainbannerimg order by banner_sort";

$arpm=array("B"=>"무통장","V"=>"계좌이체","O"=>"가상계좌","Q"=>"가상계좌(매매보호)","C"=>"신용카드","P"=>"신용카드(매매보호)","M"=>"핸드폰");

if (stripos($shop_intro,"<table")!==false || strlen($mainflash)>0)
	$main_banner=$shop_intro;
else
	$main_banner=nl2br($shop_intro);
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

	
	<div class="containerBody sub-page">
	
		<div class="breadcrumb">
			<ul>
				<li><a href="/">HOME</a></li>
				<li><a href="mypage.php">MY PAGE</a></li>
				<li class="on"><a>주문취소교환반품</a></li>
			</ul>
		</div>

		<!-- LNB -->
		<div class="left_lnb">
			<? include ($Dir.FrontDir."mypage_TEM01_left.php");?> 
			<!---->
		</div><!-- //LNB -->
		
		<!-- 내용 -->
		<div class="right_section mypage-content-wrap">
<?
		$s_curtime=strtotime("$s_year-$s_month-$s_day");
		$s_curdate=date("Ymd",$s_curtime)."000000";
		$e_curtime=strtotime("$e_year-$e_month-$e_day");
		$e_curdate=date("Ymd",$e_curtime)."999999";
		
		# 취소/교환/반품/환불 대기
		$sql = "SELECT 
					a.ordercode,
					min(a.id) as id,
					min(a.paymethod) as paymethod,
					min(a.oi_step1) as oi_step1,
					min(a.oi_step2) as oi_step2,
					min(oc.regdt) as regdt,
					min(oc.oc_no) as oc_no,
					min(oc.rfee) as rfee,
					min(oc.rprice) as rprice
					FROM
					tblorderinfo a join tblorderproduct b on a.ordercode = b.ordercode left join tblorder_cancel oc ON b.oc_no=oc.oc_no
					join tblvenderinfo v on b.vender = v.vender
					WHERE 1=1
					/*AND b.option_type = 0*/
					AND a.id = '".$_ShopInfo->getMemid()."'
					AND ( 
						(b.redelivery_type = 'G' And b.op_step = 41)
						OR (a.oi_step1 in (3,4) And (coalesce(b.opt1_change, '') = '' And coalesce(b.opt2_change, '') = '') And b.op_step = 41)
						OR (a.bank_date is not null And ((a.oi_step1 in (1,2) and b.op_step = 41) OR b.op_step = 42) And ((coalesce(b.opt1_change, '') = '' And coalesce(b.opt2_change, '') = ''))) ) ";

		$sql.= "AND oc.regdt >= '".$s_curdate."' AND oc.regdt <= '".$e_curdate."' ";
		$sql.= "GROUP BY oc.oc_no, a.ordercode ORDER BY oc.oc_no DESC";

		//echo $sql;
		
		$paging = new New_Templet_paging($sql,10,10,'GoPage',true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		$sql = $paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		
		//exdebug($sql);
 ?>
			<div class="cancle-list-wrap">
			<div class="mypage-title">주문취소/교환/반품 대기<span>Total (<?=number_format($t_count)?>)</span></div>
			<!-- 날짜 설정 -->
			<form name="form1" action="<?=$_SERVER['PHP_SELF']?>">
			<div class="date_find_wrap">
				<ul class="date_setting">
					<li class="title">기간별 조회</li>
					<li class="date">
						<?
							if(!$day_division) $day_division = '1MONTH';

						?>
						<?foreach($arrSearchDate as $kk => $vv){?>
							<?
								$dayClassName = "";
								if($day_division != $kk){
									$dayClassName = 'btn_white_s';
								}else{
									$dayClassName = 'btn_black_s';
								}
							?>
							<a href="Javascript:;" class="<?=$dayClassName?>" onClick = "GoSearch2('<?=$kk?>', this)"><?=$vv?></a>
						<?}?>
						
					</li>
					<li class="title">일자별 조회</li>
					<li class="date">
						<div class="input_bg"><input type="text" name="date1" id="" value="<?=$strDate1?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> ~ 
						<div class="input_bg"><input type="text" name="date2" id="" value="<?=$strDate2?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> &nbsp;&nbsp;
						<a href="javascript:CheckForm();" class="btn-dib-function"><span>SEARCH</span></a>
					</li>
					
				</ul>
			</div>
			</form><!-- //날짜 설정 -->

			<table class="th-top util top-line-none">
				<colgroup>
					<col style="width:160px"><col style="width:100px"><col style="width:auto"><col style="width:94px"><col style="width:91px"><col style="width:91px"><col style="width:103px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문정보</th>
						<th scope="col" colspan="2">상품정보/옵션</th>
						<th scope="col">환불금액</th>
						<th scope="col">결제정보</th>
						<th scope="col">접수일자</th>
						<th scope="col">진행단계</th>
					</tr>
				</thead>
				<tbody>

<?		

		if($t_count){
			$row_cnt=1;
			while($row=pmysql_fetch_object($result)) {

				$orProduct	= null;
				$orvender	= null;

				$sql2		 = "SELECT op.*, p.tinyimage, pb.brandname FROM tblorderproduct op left join tblproduct p ON op.productcode=p.productcode left join tblproductbrand pb on p.brand=pb.bridx ";
				$sql2		.= "WHERE op.ordercode='".$row->ordercode."' AND op.oc_no='".$row->oc_no."' ";
				$sql2		.= "/*AND op.option_type = 0*/ order by vender, productcode";
				
				$result2	=pmysql_query($sql2,get_db_conn());

				while($row2=pmysql_fetch_object($result2)){
					
					# 상품정보
					$orProduct[$row2->idx] = (object) array(
						'vender' => $row2->vender,
						'brandname' => $row2->brandname,
						'productcode' => $row2->productcode,
						'productname' => $row2->productname,
						'tinyimage' => $row2->tinyimage,
						'opt1_name' => $row2->opt1_name,
						'opt2_name' => $row2->opt2_name,
						'text_opt_subject' => $row2->text_opt_subject,
						'text_opt_content' => $row2->text_opt_content,
						'option_quantity' => $row2->option_quantity,
						'op_step' => $row2->op_step
					);
					if ($orvender[$row2->vender]['t_pro_count'] == '') {
						$orvender[$row2->vender]['t_pro_count']	= 1; // 벤더 상품수
						$orvender[$row2->vender]['t_pro_price']	= ($row2->price + $row2->option_price) * $row2->option_quantity; // 벤더 총 주문금액
						$orvender[$row2->vender]['t_deli_price']	= $row2->deli_price; // 벤더 총 배송비
					} else {
						$orvender[$row2->vender]['t_pro_count']	= $orvender[$row2->vender]['t_pro_count'] + 1; // 벤더 상품수
						$orvender[$row2->vender]['t_pro_price']	= $orvender[$row2->vender]['t_pro_price'] + (($row2->price + $row2->option_price) * $row2->option_quantity); // 벤더 총 주문금액
						$orvender[$row2->vender]['t_deli_price']	= $orvender[$row2->vender]['t_deli_price'] + $row2->deli_price; // 벤더 총 배송비
					}
				}
				pmysql_free_result($result2);

				//exdebug($orProduct);

				$pr_cnt=0;

				foreach( $orProduct as $pr_idx=>$prVal ) { // 상품

					$file = getProductImage($Dir.DataDir.'shopimages/product/', $prVal->tinyimage);

					$optStr	= "";
					$option1	 = $prVal->opt1_name;
					$option2	 = $prVal->opt2_name;

					if( strlen( trim( $prVal->opt1_name ) ) > 0 ) {
						$opt1_name_arr	= explode("@#", $prVal->opt1_name);
						$opt2_name_arr	= explode(chr(30), $prVal->opt2_name);
						for($g=0;$g < sizeof($opt1_name_arr);$g++) {
							if ($g > 0) $optStr	.= " / ";
							$optStr	.= $opt1_name_arr[$g].' : '.$opt2_name_arr[$g];
						}
						$optStr .= " / 수량 : ".number_format($prVal->option_quantity)."개";
					}
						
					if( strlen( trim( $prVal->text_opt_subject ) ) > 0 ) {
						$text_opt_subject_arr	= explode("@#", $prVal->text_opt_subject);
						$text_opt_content_arr	= explode("@#", $prVal->text_opt_content);

						for($s=0;$s < sizeof($text_opt_subject_arr);$s++) {
							if ($text_opt_content_arr[$s]) {
								if ($optStr != '') $optStr	.= " / ";
								$optStr	.= $text_opt_subject_arr[$s].' : '.$text_opt_content_arr[$s];
							}
						}
					}

					/*if ($orvender[$prVal->vender]['t_pro_count'] > 1) {
						$list_rs	= " rowspan='".$orvender[$prVal->vender]['t_pro_count']."'";
						if ($row_cnt < $t_count && ($pr_cnt == 0 || ($pr_cnt+1) == $orvender[$prVal->vender]['t_pro_count'])) {
							$list_clname	= "divide-line-color";
							$list_cl	= " class='".$list_clname."'";
						} else {
							$list_clname	= "";
							$list_cl	= "";
						}
					} else {
						$list_rs	= "";
						$list_clname	= "";
						$list_cl	= "";
					}*/

					if (count($orProduct) > 1) {
						$list_rs	= " rowspan='".count($orProduct)."'";
						if ($row_cnt < $t_count && ($pr_cnt == 0 || ($pr_cnt+1) == count($orProduct))) {
							$list_clname	= "divide-line-color";
							$list_cl	= " class='".$list_clname."'";
						} else {
							$list_clname	= "";
							$list_cl	= "";
						}
					} else {
						$list_rs	= "";
						$list_clname	= "";
						$list_cl	= "";
					}

					if ($pr_cnt == 0) {
						$total_refund_price	= number_format($orvender[$prVal->vender]['t_pro_price'] + $orvender[$prVal->vender]['t_deli_price']);
?>
					<tr>
						<td<?=$list_rs.$list_cl?>><a href="javascript:OrderDetail('<?=$row->ordercode?>')"><strong><?=$row->ordercode?></strong><br>(<?=substr($row->ordercode,0,4)."-".substr($row->ordercode,4,2)."-".substr($row->ordercode,6,2)?>)</a></td>
						<td><a href="../front/productdetail.php?productcode=<?=$prVal->productcode?>"><img src="<?=$file?>" alt="" class="img-size-mypage"></a></td>
						<td class="ta-l">
						<a href="javascript:OrderDetail('<?=$row->ordercode?>')">
							<span class="brand-color">[<?=$prVal->brandname?>]</span><br>
							<span><?=$prVal->productname?></span><br>
							<span><?=$optStr?></span>
						</a>
						</td>
						<td<?=$list_rs.$list_cl?>><strong><?=$total_refund_price?></strong></td>
						<td<?=$list_rs.$list_cl?>><?=$arpm[$row->paymethod[0]]?></td>
						<td<?=$list_rs.$list_cl?>><?=substr($row->regdt,0,4)."-".substr($row->regdt,4,2)."-".substr($row->regdt,6,2)?></td>
						<td<?=$list_rs.$list_cl?>>처리중</td>
					</tr>
<?				
					} else {
?>
					<tr>
						<td<?=$list_cl?>><img src="<?=$file?>" alt="" class="img-size-mypage"></td>
						<td class="ta-l <?=$list_clname?>">
							<span class="brand-color">[<?=$prVal->brandname?>]</span><br>
							<span><?=$prVal->productname?></span><br>
							<span><?=$optStr?></span>
						</td>
					</tr>
<?
					}
					$pr_cnt++;
				}
				$row_cnt++;
			}
		}else{
?>
					<tr>
						<td colspan="7">내역이 없습니다.</td>
					</tr>
<?php		
		}
?>
				</tbody>
			</table>
			<div class="paging mt-20"><?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?></div>



<?
		$r_s_curtime=strtotime("$r_s_year-$r_s_month-$r_s_day");
		$r_s_curdate=date("Ymd",$r_s_curtime)."000000";
		$r_e_curtime=strtotime("$r_e_year-$r_e_month-$r_e_day");
		$r_e_curdate=date("Ymd",$r_e_curtime)."999999";
		
		# 취소/교환/반품/환불 대기
		$sql = "SELECT 
					a.ordercode,
					min(a.id) as id,
					min(a.paymethod) as paymethod,
					min(a.oi_step1) as oi_step1,
					min(a.oi_step2) as oi_step2,
					min(oc.regdt) as regdt,
					min(oc.oc_no) as oc_no,
					min(oc.rfee) as rfee,
					min(oc.rprice) as rprice
					FROM
					tblorderinfo a join tblorderproduct b on a.ordercode = b.ordercode left join tblorder_cancel oc ON b.oc_no=oc.oc_no
					join tblvenderinfo v on b.vender = v.vender
					WHERE 1=1
					/*AND b.option_type = 0*/
					AND a.id = '".$_ShopInfo->getMemid()."'
					AND ( 
						(a.oi_step1 = 0 And a.oi_step2 = 44)
						OR (b.redelivery_type = 'G' And b.op_step = 44)
						OR (a.oi_step1 in (3,4) And a.oi_step2 = 42)
						OR (a.oi_step1 > 0 And b.op_step = 44 And ((coalesce(b.opt1_change, '') = '' And coalesce(b.opt2_change, '') = ''))) ) ";

		$sql.= "AND oc.regdt >= '".$r_s_curdate."' AND oc.regdt <= '".$r_e_curdate."' ";
		$sql.= "GROUP BY oc.oc_no, a.ordercode ORDER BY oc.oc_no DESC";

		//echo $sql;
		
		$r_paging = new New_Templet_paging($sql,10,10,'GoPage2',true);
		$r_t_count = $r_paging->t_count;
		$gotopage2 = $r_paging->gotopage;

		$sql = $r_paging->getSql($sql);
		$result=pmysql_query($sql,get_db_conn());
		
		//exdebug($sql);
 ?>

			<div class="mypage-title mt-30">주문취소/교환/반품 내역<span>Total (<?=number_format($r_t_count)?>)</span></div>
			<!-- 날짜 설정 -->
			<form name="form3" action="<?=$_SERVER['PHP_SELF']?>">
			<div class="date_find_wrap">
				<ul class="date_setting">
					<li class="title">기간별 조회</li>
					<li class="date">
						<?
							if(!$r_day_division) $r_day_division = '1MONTH';

						?>
						<?foreach($arrSearchDate as $kk => $vv){?>
							<?
								$dayClassName = "";
								if($r_day_division != $kk){
									$dayClassName = 'btn_white_s';
								}else{
									$dayClassName = 'btn_black_s';
								}
							?>
							<a href="Javascript:;" class="<?=$dayClassName?>" onClick = "GoSearch3('<?=$kk?>', this)"><?=$vv?></a>
						<?}?>
						
					</li>
					<li class="title">일자별 조회</li>
					<li class="date">
						<div class="input_bg"><input type="text" name="r_date1" id="" value="<?=$r_strDate1?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> ~ 
						<div class="input_bg"><input type="text" name="r_date2" id="" value="<?=$r_strDate2?>" readonly ></div><a href="javascript:;" class="btn_calen CLS_cal_btn"></a> &nbsp;&nbsp;
						<a href="javascript:CheckForm3();" class="btn-dib-function"><span>SEARCH</span></a>
					</li>
					
				</ul>
			</div>
			</form><!-- //날짜 설정 -->

			<table class="th-top util top-line-none">
				<colgroup>
					<col style="width:160px"><col style="width:100px"><col style="width:auto"><col style="width:94px"><col style="width:91px"><col style="width:91px"><col style="width:103px">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">주문정보</th>
						<th scope="col" colspan="2">상품정보/옵션</th>
						<th scope="col">환불금액</th>
						<th scope="col">결제정보</th>
						<th scope="col">접수일자</th>
						<th scope="col">진행단계</th>
					</tr>
				</thead>
				<tbody>

<?		

		if($r_t_count){
			while($row=pmysql_fetch_object($result)) {

				$orProduct	= null;
				$orvender	= null;

				$sql2		 = "SELECT op.*, p.tinyimage, pb.brandname FROM tblorderproduct op left join tblproduct p ON op.productcode=p.productcode left join tblproductbrand pb on p.brand=pb.bridx ";
				$sql2		.= "WHERE op.ordercode='".$row->ordercode."' AND op.oc_no='".$row->oc_no."' ";
				$sql2		.= "/*AND op.option_type = 0*/ order by vender, productcode";
				
				$result2	=pmysql_query($sql2,get_db_conn());

				while($row2=pmysql_fetch_object($result2)){
					
					# 상품정보
					$orProduct[$row2->idx] = (object) array(
						'vender' => $row2->vender,
						'brandname' => $row2->brandname,
						'productcode' => $row2->productcode,
						'productname' => $row2->productname,
						'tinyimage' => $row2->tinyimage,
						'opt1_name' => $row2->opt1_name,
						'opt2_name' => $row2->opt2_name,
						'text_opt_subject' => $row2->text_opt_subject,
						'text_opt_content' => $row2->text_opt_content,
						'option_quantity' => $row2->option_quantity,
						'redelivery_type' => $row2->redelivery_type,
						'op_step' => $row2->op_step
					);
					if ($orvender[$row2->vender]['t_pro_count'] == '') {
						$orvender[$row2->vender]['t_pro_count']	= 1; // 벤더 상품수
						$orvender[$row2->vender]['t_pro_price']	= ($row2->price + $row2->option_price) * $row2->option_quantity; // 벤더 총 주문금액
						$orvender[$row2->vender]['t_deli_price']	= $row2->deli_price; // 벤더 총 배송비
					} else {
						$orvender[$row2->vender]['t_pro_count']	= $orvender[$row2->vender]['t_pro_count'] + 1; // 벤더 상품수
						$orvender[$row2->vender]['t_pro_price']	= $orvender[$row2->vender]['t_pro_price'] + (($row2->price + $row2->option_price) * $row2->option_quantity); // 벤더 총 주문금액
						$orvender[$row2->vender]['t_deli_price']	= $orvender[$row2->vender]['t_deli_price'] + $row2->deli_price; // 벤더 총 배송비
					}
				}
				pmysql_free_result($result2);

				$pr_cnt=0;

				foreach( $orProduct as $pr_idx=>$prVal ) { // 상품

					$file = getProductImage($Dir.DataDir.'shopimages/product/', $prVal->tinyimage);

					$optStr	= "";
					$option1	 = $prVal->opt1_name;
					$option2	 = $prVal->opt2_name;

					if( strlen( trim( $prVal->opt1_name ) ) > 0 ) {
						$opt1_name_arr	= explode("@#", $prVal->opt1_name);
						$opt2_name_arr	= explode(chr(30), $prVal->opt2_name);
						for($g=0;$g < sizeof($opt1_name_arr);$g++) {
							if ($g > 0) $optStr	.= " / ";
							$optStr	.= $opt1_name_arr[$g].' : '.$opt2_name_arr[$g];
						}
						$optStr .= " / 수량 : ".number_format($prVal->option_quantity)."개";
					}
						
					if( strlen( trim( $prVal->text_opt_subject ) ) > 0 ) {
						$text_opt_subject_arr	= explode("@#", $prVal->text_opt_subject);
						$text_opt_content_arr	= explode("@#", $prVal->text_opt_content);

						for($s=0;$s < sizeof($text_opt_subject_arr);$s++) {
							if ($text_opt_content_arr[$s]) {
								if ($optStr != '') $optStr	.= " / ";
								$optStr	.= $text_opt_subject_arr[$s].' : '.$text_opt_content_arr[$s];
							}
						}
					}

					/*if ($orvender[$prVal->vender]['t_pro_count'] > 1) {
						$list_rs	= " rowspan='".$orvender[$prVal->vender]['t_pro_count']."'";
					} else {
						$list_rs	= "";
					}*/
					if (count($orProduct) > 1) {
						$list_rs	= " rowspan='".count($orProduct)."'";
					} else {
						$list_rs	= "";
					}

					if ($pr_cnt == 0) {
						list($step_prev)=pmysql_fetch_array(pmysql_query("select step_prev from tblorder_cancel_log where ordercode='".trim($row->ordercode)."' AND oc_no='".trim($row->oc_no)."' order by ocl_no asc limit 1"));
						$res_text	= "취소완료";
						if ($prVal->redelivery_type == "Y") $res_text = "반품완료";
						if ($prVal->redelivery_type == "G") $res_text = "교환완료";
						$total_refund_price	= number_format($row->rprice - $row->rfee);
						if ($total_refund_price == 0 || $step_prev == 0) $total_refund_price = "-";
?>
					<tr>
						<td<?=$list_rs?>><a href="javascript:OrderDetail('<?=$row->ordercode?>')"><strong><?=$row->ordercode?></strong><br>(<?=substr($row->ordercode,0,4)."-".substr($row->ordercode,4,2)."-".substr($row->ordercode,6,2)?>)</a></td>
						<td><a href="../front/productdetail.php?productcode=<?=$prVal->productcode?>"><img src="<?=$file?>" alt="" class="img-size-mypage"></a></td>
						<td class="ta-l">
						<a href="javascript:OrderDetail('<?=$row->ordercode?>')">
							<span class="brand-color">[<?=$prVal->brandname?>]</span><br>
							<span><?=$prVal->productname?></span><br>
							<span><?=$optStr?></span>
						</a>
						</td>
						<td<?=$list_rs?>><strong><?=$total_refund_price?></strong></td>
						<td<?=$list_rs?>><?=$arpm[$row->paymethod[0]]?></td>
						<td<?=$list_rs?>><?=substr($row->regdt,0,4)."-".substr($row->regdt,4,2)."-".substr($row->regdt,6,2)?></td>
						<td<?=$list_rs?>><?=$res_text?></td>
					</tr>
<?				
					} else {
?>
					<tr>
						<td><img src="<?=$file?>" alt="" class="img-size-mypage"></td>
						<td class="ta-l">
							<span class="brand-color">[<?=$prVal->brandname?>]</span><br>
							<span><?=$prVal->productname?></span><br>
							<span><?=$optStr?></span>
						</td>
					</tr>
<?
					}
					$pr_cnt++;
				}
			}
		}else{
?>
					<tr>
						<td colspan="7">내역이 없습니다.</td>
					</tr>
<?php		
		}
?>
				</tbody>
			</table>
			<div class="paging mt-20"><?=$a_div_prev_page.$r_paging->a_prev_page.$r_paging->print_page.$r_paging->a_next_page.$a_div_next_page?></div>
			<dl class="attention mt-70">
				<dt>유의사항</dt>
				<dd>[주문번호]를 클릭하시면 주문취소/교환/반품을 하실 수  있습니다.</dd>
				<dd>결제 전 상태에서는 모든 주문건 취소가 가능하며, 출고완료된 상품은 반품메뉴를 이용하시기 바랍니다.</dd>
				<dd>상품 일부만 취소/교환/반품을 원하시는 경우 1:1 문의 또는 고객센터(02-2145-1400)로 문의 부탁드립니다.</dd>
				<dd>배송처리 이후 14일이 경과되면 자동 구매확정 처리 되며 교환/반품이 불가능합니다. 상품하자 또는 오배송으로 인한 교환/반품 신청은
 1:1 문의 또는 고객센터(02-2145-1400)로 <br />문의 부탁드립니다. </dd>
				<dd>무통장입금 결제주문의 경우, 환불금액 입금에 3-4일정도(영업일기준) 소요됩니다.</dd>
			</dl>
			
		</div><!-- //.cancle-list-wrap -->
		</div><!-- 내용 -->

	</div>


<div id="create_openwin" style="display:none"></div>

<? include($Dir."admin/calendar_join.php");?>
</div>
</BODY>
</HTML>
















