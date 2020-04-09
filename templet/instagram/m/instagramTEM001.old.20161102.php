<?php include_once('outline/header_m.php'); ?>
<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

$instaimgpath = $Dir.DataDir."shopimages/instagram/";
$productimgpath = $Dir.DataDir."shopimages/product/";

$search_word = $_POST['search_word'];
$sort = $_POST["sort"] ? $_POST["sort"] : 'latest';
$sql = "SELECT  i.*,
						COALESCE((select COUNT( tl.hott_code )AS hott_cnt from tblhott_like tl WHERE tl.section = 'instagram' AND i.idx::varchar = tl.hott_code),0) AS hott_cnt
			FROM tblinstagram i
			LEFT JOIN ( SELECT hott_code, section FROM tblhott_like WHERE section = 'instagram' AND like_id = '".$_ShopInfo->getMemid()."' GROUP BY hott_code, section ) li on i.idx::varchar = li.hott_code
			WHERE i.display = 'Y' ";
if(!empty($search_word)){
	$sql .= "AND ( i.title iLIKE '%{$search_word}%' OR i.content iLIKE '%{$search_word}%' OR i.hash_tags = '%{$search_word}%')  ";
}

//검색 조건
$order = "";
if(!empty($sort)){
	if($sort == "latest"){
		$order .= " ORDER BY i.regdt desc";
	}else if($sort == "best"){
		$order .= " ORDER BY i.access desc";
	}else if($sort == "like"){
		$order .= " ORDER BY hott_cnt desc";
	}
}

$sql .=	$order;
$sql .= " LIMIT 5";
$result = pmysql_query($sql);
while ( $row = pmysql_fetch_array($result) ) {
	$arrInstaList[] = $row;
	$idx = $row['idx'];
}

//데이터가 있는지 체크
$check_sql = "SELECT * FROM tblinstagram WHERE display = 'Y' AND idx < '{$idx}' ";
$chk_result = pmysql_query($check_sql);
$count = pmysql_num_rows( $chk_result );
while ( $chk_row = pmysql_fetch_array($chk_result) ) {
	$chkidx = $chk_row['idx'];
}

?>

<!-- [D] 2016. 퍼블 작업 -->
<section class="top_title_wrap">
	<h2 class="page_local">
		<a href="<?=$Dir.MDir ?>" class="prev"></a>
		<span>INSTAGRAM</span>
		<a href="/m/shop.php" class="home"></a>
	</h2>
</section>

<div class="instagram-wrap">
	<form name="searchForm" id="searchForm" method="post" action="<?=$_SERVER['PHP_SELF']?>">
    <div class="sorting_area">
		<div class="searchbox clear">
			<input type="search" name="search_word" id="search_word" value="<?=$search_word ?>">
			<button type="submit" class="btn-def">검색</button>
		</div>
		<div class="list_sort">
			<ul class="clear">
				<li><a href="javascript:sortSelect('latest');">최신순</a></li>
				<li><a href="javascript:sortSelect('best');">인기순</a></li>
				<li><a href="javascript:sortSelect('like');">좋아요순</a></li>
			</ul>
		</div>
	</div><!-- //.sorting_area -->
		<input type="hidden" name="sort" value="<?=$sort ?>">
	</form>
	<div class="asymmetry_list">
		<?if(count($arrInstaList) > 0){ ?>
		<ul class="instagram-list">
			<?foreach( $arrInstaList as $key=>$val ){ 
				$arrTag = explode(",",$val['hash_tags']);
			?>
			<li>
				<div class="name">
					<span></span> <!-- instagram id -->
					<?if($val['section']){ ?>
					<button class="comp-like btn-like like_i<?=$val['idx']?> on" onclick="detailSaveLike('<?=$val['idx']?>','on','instagram','<?=$_ShopInfo->getMemid()?>','<?=$brand ?>')" id="like_<?=$val['idx']?>" title="선택됨"><span  class="like_icount_<?=$val['idx']?>"><strong>좋아요</strong><?=$val['hott_cnt'] ?></span></button>
					<?}else{ ?>
					<button class="comp-like btn-like like_i<?=$val['idx']?>" onclick="detailSaveLike('<?=$val['idx']?>','off','instagram','<?=$_ShopInfo->getMemid()?>','<?=$brand ?>')" id="like_<?=$val['idx']?>" title="선택 안됨"><span class="like_icount_<?=$val['idx']?>"><strong>좋아요</strong><?=$val['hott_cnt'] ?></span></button>
					<?} ?>
				</div>
				<div class="cont-img"><img src="<?=$instaimgpath.$val['img_file']?>" alt=""></div>
				<div class="title">
					<p><?=strcutMbDot(strip_tags($val['content']),35) ?></p>
					<p class="tag">
					<?foreach($arrTag as $tag){?>
						<?="#".trim($tag)?>
					<?} ?>
					</p>
				</div>
				<div class="btnwrap mb-10">
					<ul class="ea2">
						<li><a href="<?=$val['link_m_url'] ?>" class="btn-def">INSTAGRAM</a></li>
						<li><a href="javascript:relatedView('<?=$val['relation_product'] ?>');" class="btn-def btn-related">관련상품 보기</a></li>
						<!-- <li><a class="btn-def btn-related-no">관련상품 없음</a></li> --><!-- [D] 관련상품이 없는 경우 -->
					</ul>
				</div>
			</li>
			<?} ?>
		</ul>
		<?} ?>
	</div>	
	<?if($chkidx != ""){ ?>
	<div class="btn_list_more mt-20 more_btn" id="more<?=$idx ?>">
		<a href="javascript:;" class="more_view" id="<?=$idx ?>">더보기</a>
	</div>
	<?} ?>	
</div><!-- //.instagram-wrap -->
<!-- //[D] 2016. 퍼블 작업 -->

<!-- 관련상품 레이어 팝업 -->
<div class="layer-dimm-wrap pop-related"> <!-- .layer-class 이부분에 클래스 추가하여 사용합니다. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner">
		<h3 class="layer-title">관련상품</h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">
			<div class="product-list">
				<div class="goods-list">
					<div class="goods-list-item">
					<!-- (D) 별점은 .star-score에 width:n%로 넣어줍니다. -->
						<ul id="relation_list">
							<li class="grid-sizer"></li>


						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- // 관련상품 레이어 팝업 -->

<script type="text/javascript">
var memId = "<?=$_ShopInfo->getMemid()?>";

$(document).ready(function() {

	//레이어 팝업
	$('.btn-related').click(function(){
		$('.pop-related').fadeIn();
	});

	//더 보기
	$(".more_view").on("click",function(){
        var id = $(this).attr("id");
        if(id){
            $.ajax({
                type: "POST",
                url: "../front/ajax_instagram_more.php",
                data: "id="+ id+"&type=mobile",
                contentType : "application/x-www-form-urlencoded; charset=UTF-8",
                error:function(request,status,error){
                    alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }
            }).done(function(data){  
            	 var arrData = data.split("|||");
            	 console.log(arrData[1]);
                $(".instagram-list").append(arrData[0]);
//                 $('.asymmetry_list>ul').masonry('reloadItems');
//                 $('.asymmetry_list>ul').masonry('layout');
                if(arrData[1] == ""){
                	$(".more_btn").remove();
                }else{
                	$(".more_btn").attr("id","more"+arrData[1] );
					$(".more_view").attr("id",arrData[1]);
                }
            	$('.btn-related').click(function(){
            		$('.pop-related').fadeIn();
            	});

            	$(".btn-related").on("click",function(){
            		 var code = $(this).attr("idx");
            		 relatedView(code);
            	});
            	
            });
        }else{
            $(".btn_list_more .mt-50").html('The End');// no results
        }
    });

});


//관련상품 보기
function relatedView(code){
	
	$.ajax({
		type: "POST",
		url: "../m/ajax_relation_product.php",
		data: "code="+code+"&type=mobile",
		dataType:"HTML",
	    error:function(request,status,error){
	       alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
	    }
	}).done(function(html){
		console.log(html);
		$("#relation_list").empty("");
		$("#relation_list").append("<li class='grid-sizer'></li>");
		$("#relation_list").append(html);
// 		$(".main-community-content").trigger("COMMUNITY_RESET");

	});
}

//정렬 순 검색
function sortSelect(val){
	$("input[name=sort]").val(val);
	$("form[name='searchForm']").submit();
}

</script>

<? include_once('outline/footer_m.php'); ?>
