<?
//print_r($_GET);
//print_r($_POST);
function option_slice2( $content, $option_type = '0' ){
    $tmp_content = '';
    if( $option_type == '0' ) {
        $tmp_content = explode( chr(30), $content );
    } else {
        $tmp_content = explode( '@#', $content );
    }
    
    return $tmp_content;

}

$sql = "SELECT  count(*) 
        FROM tblbasket bs 
		LEFT JOIN tblproduct pd ON bs.productcode = pd.productcode
		WHERE bs.member_id='".$mem_id."'  AND direct_yn='N'
        ";
//print_r($sql);
$paging = new newPaging($sql,10,5,'GoPageWish');
$t_count = $paging->t_count;
$gotopage = $paging->gotopage;


########### 장바구니 구하기
$sql = "SELECT bs.option_type AS product_option,bs.*,pd.* 
		FROM tblbasket bs 
		LEFT JOIN tblproduct pd ON bs.productcode = pd.productcode
		WHERE bs.member_id='".$mem_id."'  AND direct_yn='N'
        ORDER BY  bs.date_insert DESC 
        ";
//print_r($sql);
$sql = $paging->getSql($sql);
$ret_board = pmysql_query($sql, get_db_conn());
$cnt_board = pmysql_num_rows($ret_board);
?>
<script type="text/javascript">
<!--

function GoPageWish(block,gotopage) {
	document.idxform.block.value = block;
	document.idxform.gotopage.value = gotopage;
	document.idxform.submit();
}

//-->
</script>

			<div class="contentsBody">

				<h3 class="table-title">장바구니 조회</h3>

				<table class="th-top">
					<caption>장바구니 리스트</caption>
					<colgroup>
						<col style="width:60px"><col style="width:100px"><col style="width:auto"><col style="width:120px"><col style="width:120px">
					</colgroup>
					<thead>
						<tr>
							<th scope="row">번호</th>
							<th scope="row" colspan=2>상품정보</th>
							<th scope="row">상품금액</th>
							<th scope="row">담은날짜</th>
						</tr>
					</thead>
					<tbody>
<?
                if($cnt_board > 0) {
                    $cnt=0;
                    
                    while($row_board = pmysql_fetch_object($ret_board)) {

                        $option = array();
                        $add_opt = "";
                        $number = ($t_count-($setup['list_num'] * ($gotopage-1))-$cnt);

                        $productname = $row_board->productname?strcutMbDot($row_board->productname, 35):"-";
                        $tinyimage = getProductImage($Dir.DataDir.'shopimages/product/',$row_board->tinyimage);
                        //echo "img = ".$tinyimage;

						if($row_board->product_option=='option') {
							$sub_sql = "SELECT * ";
							$sub_sql .= "FROM tblproduct_option ";
							$sub_sql .= "WHERE option_num = '" . $row_board->option_code . "' ";
							$sub_row = pmysql_fetch_object(pmysql_query($sub_sql));
						}else if($row_board->product_option=='product'){
							$sub_sql = "SELECT * ";
							$sub_sql .= "FROM tblproduct ";
							$sub_sql .= "WHERE productcode = '" . $row_board->option_code . "' ";
							$sub_row = pmysql_fetch_object(pmysql_query($sub_sql));
						}
                       // print_r($sub_sql);
?>
						<tr>
							<td><?=$number?></td>
							<td class="ta-l">
                                <A HREF="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_board->productcode?>" target="_blank"><img src="<?=$tinyimage?>" style='max-width:80px' border=0>&nbsp;</a>
                            </td>
							<td class="ta-l">
                                <A HREF="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$row_board->productcode?>" target="_blank">&nbsp;<?=$productname?>
                                <br>&nbsp;
									<?if($row_board->product_option=='option') { ?>
										<p class="mt-15" style="padding-left: 10px;">[옵션] <?=$sub_row->option_name;?></p>
									<? }else if($row_board->product_option=='product'){?>
										<p class="mt-15" style="padding-left: 10px;">[추가] <?=$sub_row->productname;?></p>
									<? } ?>
								</a>
                            </td>
							<td class="ta-r"><?=number_format($row_board->endprice)?></a></td>
							<td><?=$row_board->date_insert?></td>
						</tr>
<?
                        $cnt_board--;
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
							<td colspan="7" class="ta-c">관심상품정보가 없습니다.</td>
						</tr>
<?
                }
pmysql_free_result($ret_board);
?>
					</tbody>
				</table>

                <form name=idxform action="<?=$_SERVER['PHP_SELF']?>" method=GET>
                <input type="hidden" name="id" value="<?=$mem_id?>">
                <input type="hidden" name="menu" value="basket">
                <input type=hidden name=type>
                <input type=hidden name=block value="<?=$block?>">
                <input type=hidden name=gotopage value="<?=$gotopage?>">
                </form>

				<dl class="help-attention mt-50">
					<dt>도움말</dt>
					<!-- <dd>1. 비회원인 경우는 어쩌고 저쩌고</dd>
					<dd>2. 회원인 경우는 어쩌고 저쩌고</dd> -->
				</dl>


			</div><!-- //.contentsBody -->