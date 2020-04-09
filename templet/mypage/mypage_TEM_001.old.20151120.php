<script type="text/javascript">
	$(function(){
		$('a.pop_benefit').click(function(){
			$('div.pop_member_benefit').css('display','block');
		});
		$('a.close').click(function(){
			$('div.pop_member_benefit').css('display','none');
		});
	});
</script>


<?php
$subTop_flag = 3;
//include ($Dir.MainDir."sub_top.php");
?>
	<div class="containerBody sub_skin">
		<!-- LNB -->
		<div class="left_lnb">
			<? include  "mypage_TEM01_left.php";  ?>			
		</div><!-- //LNB -->
		
		<!-- 내용 -->
		<div class="right_section mb_80">
			
			<h3 class="title mb_20">
				MY PAGE
				<p class="line_map"><a>홈</a> &gt; <a class="on">마이페이지</a></p>
			</h3>


			<!-- 최근주문정보 -->
			<div class="table_wrap">
				<h3>최근주문정보 <span class="total">( Total : <strong><?=$t_count?></strong> )</span></h3>
				<p class="table_info"><a href="<?=$Dir.FrontDir?>mypage_orderlist.php">> 더보기</a></p>
				<table class="th_top">
					<colgroup>
						<col width="170" /><col width="85" /><col width="*px" /><col width="145" /><col width="100" />
					</colgroup>
					<tr>
						<th>주문정보</th>
						<th colspan=2>상품정보</th>
						<th>주문금액</th>
						<!--th>진행상태</th-->
					</tr>
<?php
if( count( $ord_data ) > 0 ){
	foreach( $ord_data as $k=>$od ){
		$sql_prd = "SELECT a.vender, a.ordercode, a.productcode, a.productname, a.opt1_name, a.opt2_name,  ";
		$sql_prd.= "a.price, a.option_price, a.option_quantity, a.coupon_price, a.deli_price, ";
        $sql_prd.= "b.maximage, b.minimage, b.tinyimage  ";
        $sql_prd.= "FROM tblorderproduct as a ";
		$sql_prd.= "LEFT JOIN tblproduct as b on a.productcode = b.productcode ";
		$sql_prd.= "WHERE a.ordercode='".$od->ordercode."' AND a.option_type = 0 ";
		# 쿠폰 / 배송비등 상품이 아닌것 빼기 위해  AND length(a.productcode) > 17 조건 추가 2014-08-20 11:11
		$res_prd = pmysql_query($sql_prd);
		$cnt_prd = pmysql_num_rows($res_prd);	//상품 개수
        //echo $sql_prd;
		
		
		#####주문상품 개수가 2개 이상일때
		$i = 0;	//rowspan을 적용하기 위한 count
        $actual_amt = 0;
		while($row_prd = pmysql_fetch_array($res_prd)){

            $actual_amt = ($row_prd['price'] + $row_prd['option_price']) * $row_prd['option_quantity'];

            if( is_file( $Dir.DataDir."shopimages/product/".$row_prd['tinyimage'] ) ){
                $imgsrc = $Dir.DataDir."shopimages/product/".$row_prd['tinyimage'];
            } else {
                $imgsrc = $Dir."/images/common/noimage.gif";
            }

			if( $i == 0 ) {
?>
					<tr>
						<td rowspan='<?=$cnt_prd?>' >
							<a href="javascript:OrderDetail('<?=$od->ordercode?>')">
								<?=$od->ordercode?>
							</a>
						</td>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_prd['productcode']?>">				
								<img src="<?=$imgsrc?>" border="0" align="center" class="img-size-mypage" />
							</a>
						</td>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_prd['productcode']?>">
								<?=$row_prd['productname']?><br>(<?=$row_prd['opt1_name'].$row_prd['opt2_name']?>)
							</a>
						</td>
						<td><?=number_format($actual_amt);?>원</td>
					</tr>
<?php
			} else {
?>
					<tr>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_prd['productcode']?>">				
								<img src="<?=$imgsrc?>" border="0" align="center" class="img-size-mypage" />
							</a>
						</td>
						<td class="ta_l">
							<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_prd['productcode']?>">
								<?=$row_prd['productname']?><br>(<?=$row_prd['opt1_name'].$row_prd['opt2_name']?>)
							</a>
						</td>
						<td><?=number_format($actual_amt);?>원</td>
					</tr>
<?php
			}
			$i++;
		}//while
	}//foreach
} else {
?>
						<tr>
							<td colspan = '5' style = 'text-align:center;'>주문이 없습니다.</td>
						</tr>	
<?php
}
?>
				</table>
			</div><!-- //최근주문정보 -->

		</div><!-- 내용 -->

	</div>
