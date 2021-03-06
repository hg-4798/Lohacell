
	<div class="containerBody sub-page">

	<div class="breadcrumb">
		<ul>
			<li><a href="/">HOME</a></li>
			<li><a href="mypage.php">MY PAGE</a></li>
			<li class="on"><a>MY COUPON</a></li>
		</ul>
	</div>

		<!-- LNB -->
		<div class="left_lnb">
			<? include ($Dir.FrontDir."mypage_TEM01_left.php");?>
			<!---->
		</div><!-- //LNB -->

		<!-- 내용 -->
		<div class="right_section mypage-content-wrap">


			<h4 class="mypage-title align-top">쿠폰 내역</h4>
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
						<col style="width:130px"><col style="width:auto"><col style="width:135px"><col style="width:130px"><col style="width:165px"><col style="width:155px">
					</colgroup>
					<thead>
					<tr>
						<th scope="col">쿠폰번호</th>
						<th scope="col">쿠폰명</th>
						<th scope="col">사용혜택</th>
						<th scope="col">사용여부</th>
						<th scope="col">유효기간</th>
						<th scope="col">적용대상</th>
						<th scope="col">유의사항</th>
					</tr>
					</thead>
<?
		$sql = "SELECT issue.coupon_code, issue.id, issue.date_start, issue.date_end, ";
		$sql.= "issue.used, issue.issue_member_no, issue.issue_recovery_no, issue.ci_no, ";
		$sql.= "info.coupon_name, info.sale_type, info.sale_money, info.amount_floor, ";
		$sql.= "info.productcode, info.use_con_Type1, info.use_con_type2, info.description, ";
		$sql.= "info.use_point, info.vender, info.delivery_type, info.coupon_use_type, ";
		$sql.= "info.coupon_type, info.sale_max_money, info.coupon_is_mobile,info.not_productcode, ";
		$sql.= "info.use_promo ";
		$sql.= "FROM tblcouponissue issue ";
		$sql.= "JOIN tblcouponinfo info ON info.coupon_code = issue.coupon_code ";
		$sql.= "WHERE issue.id = '".$_ShopInfo->getMemid()."' ";
		$sql.= "AND (issue.date_end >= '".date("YmdH")."' and issue.used = 'N') ";   // 2016-07-04 사용가능 쿠폰만
		$sql.= "AND ( (issue.date_start <= '".str_replace( '-', '', $strDate1)."00' and issue.date_end >= '".str_replace( '-', '', $strDate1)."00') or (issue.date_start <= '".str_replace( '-', '', $strDate2)."23' and issue.date_end >= '".str_replace( '-', '', $strDate2)."23') or (issue.date_start >= '".str_replace( '-', '', $strDate1)."00' and issue.date_end <= '".str_replace( '-', '', $strDate2)."23')  ) ";
		$sql.= "ORDER BY issue.date_end DESC ";


		$paging = new New_Templet_paging($sql,10,10,'GoPage',true);
		$t_count = $paging->t_count;
		$gotopage = $paging->gotopage;

		$sql = $paging->getSql($sql);
		$result = pmysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=pmysql_fetch_object($result)) {
			$code_a=substr($row->productcode,0,3);
			$code_b=substr($row->productcode,3,3);
			$code_c=substr($row->productcode,6,3);
			$code_d=substr($row->productcode,9,3);

			$cate_info = "";

			$product ="";

			$prleng=strlen($row->productcode);
			$coupondate=date("YmdH");

			$couponcheck="";
			if($row->date_start>$coupondate || $row->date_end<$coupondate || $row->date_end==''){
				if($row->used=="Y"){
					$couponcheck="사용";
				}else{
					$couponcheck="사용불가";
				}
			}else if($row->used=="Y"){
				$couponcheck="사용";
			}else{
				$couponcheck="사용가능";
			}
			$likecode=$code_a;
			if($code_b!="000") $likecode.=$code_b;
			if($code_c!="000") $likecode.=$code_c;
			if($code_d!="000") $likecode.=$code_d;

			if($prleng==18) $productcode[$cnt]=$row->productcode;
			else $productcode[$cnt]=$likecode;

			if($row->sale_type<=2) {
				$dan="%";
			} else {
				$dan="원";
			}
			if($row->sale_type%2==0) {
				$sale = "할인";
			} else {
				$sale = "적립";
			}

			if( $row->productcode=="ALL" ) {
				$product="전체상품";
			} else if( $row->productcode=="GOODS" ) {
				$product = "상품 ";
				$prSql = "SELECT cp.coupon_code, pr.productname FROM tblcouponproduct cp ";
				$prSql.= "JOIN tblproduct pr ON pr.productcode = cp.productcode WHERE cp.coupon_code = '".$row->coupon_code."' ";
				$prRes = pmysql_query( $prSql, get_db_conn() );
				$prCnt = 0;
				while( $prRow = pmysql_fetch_object( $prRes ) ){
					if( $prCnt == 0 ) $product .= " [ ".$prRow->productname." ] ";
					$prCnt++;
				}
				if( $prCnt > 1 ) $product .= '외 '.( $prCnt - 1 );
			} else if( $row->productcode=="CATEGORY" || $row->not_productcode=="CATEGORY"){
				$product = "카테고리 ";

				$prSql = "SELECT pc.code_a, pc.code_b, pc.code_c, pc.code_d, pc.code_name ";
				$prSql.= "FROM tblcouponcategory cc ";
				$prSql.= "JOIN tblproductcode pc ON ";
				$prSql.= " ( CASE ";
				$prSql.= " WHEN CHAR_LENGTH( cc.categorycode ) = 3 THEN ( pc.code_a = cc.categorycode AND pc.code_b = '000' ) ";
				$prSql.= " WHEN CHAR_LENGTH( cc.categorycode ) = 6 THEN ( pc.code_a||pc.code_b = cc.categorycode AND pc.code_c = '000' ) ";
				$prSql.= " WHEN CHAR_LENGTH( cc.categorycode ) = 9 THEN ( pc.code_a||pc.code_b||pc.code_c = cc.categorycode AND pc.code_d = '000' ) ";
				$prSql.= " WHEN CHAR_LENGTH( cc.categorycode ) = 12 THEN ( pc.code_a||pc.code_b||pc.code_c||pc.code_d = cc.categorycode ) ";
				$prSql.= " END ) ";
				$prSql.= "WHERE cc.coupon_code = '".$row->coupon_code."' ";
				$prSql.= "ORDER BY code_a, code_b, code_c, code_d , sort ";
				$prRes = pmysql_query( $prSql, get_db_conn() );
				$prCnt = 0;
				while( $prRow = pmysql_fetch_object( $prRes ) ){
					if( $prCnt == 0 ) $product .= " [ ".$prRow->code_name." ] ";
					$prCnt++;
					$cate_info[] = $prRow;;
				}
				if( $prCnt > 1 ) $product .= '외 '.( $prCnt - 1 );
			} else if( $row->productcode=="BRAND" ) {
                $product = "브랜드 ";

                $prSql = "SELECT cb.coupon_code, pr.productname FROM tblcouponbrand cb ";
                $prSql.= "JOIN tblproduct pr ON pr.brand = cb.bridx WHERE cb.coupon_code = '".$row->coupon_code."' ";
                $prRes = pmysql_query( $prSql, get_db_conn() );
                $prCnt = 0;
                while( $prRow = pmysql_fetch_object( $prRes ) ){
                    if( $prCnt == 0 ) $product .= " [ ".$prRow->productname." ] ";
                    $prCnt++;
                }
                if( $prCnt > 1 ) $product .= '외 '.( $prCnt - 1 );
            }

            //기획전 쿠폰
            if( $row->use_promo=="Y" ) {
                $product = "기획전";

                $prSql  = "SELECT a.special_list, c.idx, c.title ";
                $prSql .= "FROM tblspecialpromo a ";
                $prSql .= "   LEFT JOIN tblpromotion b ON a.special::integer = b.seq ";
                $prSql .= "   LEFT JOIN tblpromo c ON b.promo_idx = c.idx ";
                $prSql .= "   LEFT JOIN tblcouponpromo d ON c.idx = d.promo_idx ";
                $prSql .= "WHERE d.coupon_code = '".$row->coupon_code."' ";
                $prSql .= "ORDER BY c.rdate desc ";
                $prRes = pmysql_query( $prSql, get_db_conn() );

                $productname = array();
                while( $prRow = pmysql_fetch_object( $prRes ) ) {
                    $special_list   = str_replace(",", "','", $prRow->special_list);

                    $sub_sql = "SELECT productname ";
                    $sub_sql .= "FROM tblproduct ";
                    $sub_sql .= "WHERE productcode in ( '{$special_list}' ) ";
                    $sub_res = pmysql_query( $sub_sql, get_db_conn() );

                    while ($sub_row = pmysql_fetch_object( $sub_res )) {
                        $productname[] = $sub_row->productname;
                    }
                }

                $prCnt = 0;
                foreach( $productname as $pKey=>$pVal ){
                    if ($prCnt == 0) $product .= " [ " . $pVal . " ] ";
                    $prCnt++;
                }
                if( $prCnt > 1 ) $product .= '외 '.( $prCnt - 1 );
            }

			$t = sscanf($row->date_start,'%4s%2s%2s%2s%2s%2s');
			$s_time = strtotime("{$t[0]}-{$t[1]}-{$t[2]} {$t[3]}:00:00");
			$t = sscanf($row->date_end,'%4s%2s%2s%2s%2s%2s');
			$e_time = strtotime("{$t[0]}-{$t[1]}-{$t[2]} {$t[3]}:00:00");

			$date=date("Y-m-d H",$s_time)."시~<br>".date("Y-m-d H",$e_time)."시";
?>
			 <tr class="coupon-list-padding">
				 <td><?=$row->coupon_code?></td>
				 <td><?=$row->coupon_name?></td>
				 <td><?=number_format($row->sale_money).$dan.' '.$sale?></td>
				 <td><?=$couponcheck?></td>
				 <td><?=$date?></td>
				 <td>
				<?if($cate_info){?>
					<?if($row->use_con_type2 =='Y'){?> (포함) <?}else{?>(제외)<?}?>
				<?}?>
					<?=$product?>
				 </td>
				 <td>
					 <span style="color:;">
						 <?if($cate_info){?>
							<?foreach($cate_info as $c_val){?>
								[<?=$c_val->code_name?>]<br>
							<?}?>

						 	카테고리<?if($row->use_con_type2 =='Y'){?> (포함) <?}else{?>(제외)<?}?>상품 주문시에만 사용 가능합니다.
						<?}else{?>
							-
						<?}?>
					 </span>
				 </td>
			 </tr>
<?

			$cnt++;
		}
		pmysql_free_result($result);
		if ($cnt==0) {
			echo " <tr><td colspan='6'>내역이 없습니다.</td></tr>";
		}
?>

				</table>
				<div class="paging mt_30"><?=$a_div_prev_page.$paging->a_prev_page.$paging->print_page.$paging->a_next_page.$a_div_next_page?></div>

				<form name="coupon_code_form" action="<?=$_SERVER['PHP_SELF']?>">

				<div class="coupon-nuber-reg">
					<h4 class="mypage-title coupon-list2">쿠폰 등록</h4>
					<div class="input">
						<input class="input-def" type="text" name="coupon_code1" id="coupon_code1" value="" maxlength=4 > -
						<input class="input-def" type="text" name="coupon_code2" id="coupon_code2" value="" maxlength=4 > -
						<input class="input-def" type="text" name="coupon_code3" id="coupon_code3" value="" maxlength=4 > -
						<input class="input-def" type="text" name="coupon_code4" id="coupon_code4" value="" maxlength=4 >
						<a href="javascript:submitPaper();" class="btn-dib-function"><span>등록</span></a>
					</div>
				</div>
				</form>

				<dl class="attention mt-70">
					<dt>유의사항</dt>
					<dd>장바구니 쿠폰은 주문서당 한 개의 쿠폰만 적용가능하며, 상품쿠폰은 중복적용이 가능합니다. (별도 명시가 없을경우)</dd>
					<dd>유효기간이 만기된 쿠폰은 자동 소멸되며 재발행되지 않습니다.</dd>
					<dd>할인쿠폰의 할인금액이 상품의 판매가를 초과할 경우 사용이 불가능합니다.</dd>
				</dl>

		</div>
		</div><!-- 내용 -->

	</div>


<div id="create_openwin" style="display:none"></div>
<? include($Dir."admin/calendar_join.php");?>
</div>
</BODY>
</HTML>
