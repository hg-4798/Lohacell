<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<HEAD>
<TITLE><?=$_data->shoptitle?> - 이용약관</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META name="description" content="<?=(ord($_data->shopdescription)?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<?php include($Dir."lib/style.php")?>
</HEAD>

<!--php끝-->
<?php  include ($Dir.MainDir.$_data->menu_type.".php") ?>
<body<?=($_data->layoutdata["MOUSEKEY"][0]=="Y"?" oncontextmenu=\"return false;\"":"")?><?=($_data->layoutdata["MOUSEKEY"][1]=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=($_data->layoutdata["MOUSEKEY"][2]=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>
<!--  <script type="text/javascript" src="<?=$Dir?>js/instagramAPI.js"></script>-->
<?php include($Dir.TempletDir."instagram/instagramTEM001.php"); ?>

<script type="text/javascript">
var memId = "<?=$_ShopInfo->getMemid()?>";
var search_word = $("#search_word").val();
$(document).ready(function() {
	$('.btn-view-detail').click(function(){
		$('.CLS_instagram').fadeIn();
	});

	//더보기
	$(".more_view").on("click",function(){
        var id = $(this).attr("id");
        if(id){
//             $("#more"+id).html('<a href="javascript:;" class="more_view" id="'+id+'">더보기</a>');
            $.ajax({
                type: "POST",
                url: "../front/ajax_instagram_more.php",
                data: "id="+ id+"&search_word="+search_word,
                contentType : "application/x-www-form-urlencoded; charset=UTF-8",
                error:function(request,status,error){
                    //alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }
            }).done(function(data){
            	 var arrData = data.split("|||");
            	 //console.log(arrData[0]);
                $(".comp-posting").append(arrData[0]);
                $(document).trigger('create');
                $('.asymmetry_list>ul').masonry('reloadItems');

				var listLen = 0;

				for(var i=0;i<$('.comp-posting>li>figure>a>img').length;i++)
				{
					$('.comp-posting>li>figure>a>img').eq(i).attr("src", $('.comp-posting>li>figure>a>img').eq(i).attr("src"));
				}

				$('.comp-posting>li>figure>a>img').on('load', function(){
					listLen++;
					if(listLen == $('.comp-posting>li').length)
					{
						$('.asymmetry_list>ul').masonry('layout');
					}
				});

				$('.asymmetry_list>ul').masonry('layout');

                if(arrData[1] == ""){
                	$(".more_btn").remove();
                }else{
                	$(".more_btn").attr("id","more"+arrData[1] );
					$(".more_view").attr("id",arrData[1]);
                }
            	$('.btn-view-detail').click(function(){
            		$('.CLS_instagram').fadeIn();
            	});
            	 $(".btn-view-detail").click(function() {
         	        var idx = $(this).attr("idx");
         	       detailView(idx);
         	    });

            });
        }else{
            $(".btn_list_more .mt-50").html('The End');// no results
        }
    });

    $(".btn-close").click(function() {
    	reset();
    });
});

//인스타그램 상세정보
function detailView(idx){
	$.ajax({
		type: "POST",
		url: "ajax_instagram_detail.php",
		data: "idx="+idx,
		dataType:"JSON"
	}).done(function(data){
 		//console.log(data);
		reset();
		var tag ="";
		if(data[0]['hash_tags'] != 0){
			var arrTag = data[0]['hash_tags'].split(",");
    		$.each( arrTag, function( i, v ){
    			tag += " #"+$.trim(v);
  			  $(".tag").text(tag);
  		    });
		}
		if(data[0]['relation_product'] != 0){
			var arrRelation = data[0]['relation_product'].split(",");
		}	
		if(data[0]['productname'] != 0){
			var arrProdName = data[0]['productname'].split(",");
		}		
		if(data[0]['brandname'] != 0){
			var arrBrandName = data[0]['brandname'].split(",");
		}	
		if(data[0]['brandimage'] != 0){
			var arrProdImage = data[0]['brandimage'].split(",");
		}	
		
		var html =  "";
		if(data[0]['relation_product'] != ""){
    		$.each( arrProdName, function( i, v ){
    			html += '<li>';
    			html += '<a href="javascript:prod_detail(\''+arrRelation[i]+'\');">';
    			html += '<figure>';
    			html += '<img src="<?=$productimgpath ?>'+arrProdImage[i]+'" alt="관심상품">';
    			html += '<figcaption># '+arrBrandName[i]+'<br>'+arrProdName[i]+' ';
    			html += '</figcaption>';
    			html += '</figure>';
    			html += '</a>';
    			html += '</li>';
				$(".related-list").html(html);
			});
		}
		$("#content").html(data[0]['content']); // HTML 로 보이도록 수정 (2016.11.02 - peter.Kim)
		$("#instagram_img").attr("src","<?=$instaimgpath ?>"+data[0]['img_file']+"");

		if(data[0]['section'] == null){
			$(".title").append('<button class="comp-like btn-like detail-like like_i'+idx+'"  onclick="detailSaveLike(\''+idx+'\',\'off\',\'instagram\',\''+memId+'\',\'\')" id="likedetail_'+idx+'" title="선택 안됨"><span class="like_icount_'+idx+'"><strong>좋아요</strong>'+data[0]['hott_cnt']+'</span></button>');
		}else{
			$(".title").append('<button class="comp-like btn-like detail-like like_i'+idx+' on " onclick="detailSaveLike(\''+idx+'\',\'on\',\'instagram\',\''+memId+'\',\'\')" id="likedetail_'+idx+'" title="선택됨"><span class="like_icount_'+idx+'"><strong>좋아요</strong>'+data[0]['hott_cnt']+'</span></button>');
		}
    	$(".view-prev").attr("href","javascript:pagePrev(\""+data[0]['pre_idx']+"\")");
    	$(".view-next").attr("href","javascript:pageNext(\""+data[0]['next_idx']+"\")");

	});

	accessPlus(idx,"tblinstagram","access","idx");
}

function pagePrev(idx){
	if(idx == ""){
		alert("첫번째 인스타그램 입니다.");
		return;
	}else{
		$.ajax({
			type: "POST",
			url: "ajax_instagram_detail.php",
			data: "idx="+idx,
			dataType:"JSON"
		}).done(function(data){
			reset();
			var tag ="";
			if(data[0]['hash_tags'] != 0){
				var arrTag = data[0]['hash_tags'].split(",");
	    		$.each( arrTag, function( i, v ){
	    			tag += " #"+$.trim(v);
	  			  $(".tag").text(tag);
	  		    });
			}
			if(data[0]['relation_product'] != 0){
				var arrRelation = data[0]['relation_product'].split(",");
			}	
			if(data[0]['productname'] != 0){
				var arrProdName = data[0]['productname'].split(",");
			}		
			if(data[0]['brandname'] != 0){
				var arrBrandName = data[0]['brandname'].split(",");
			}	
			if(data[0]['brandimage'] != 0){
				var arrProdImage = data[0]['brandimage'].split(",");
			}

            var html =  "";
			if(data[0]['relation_product'] != ""){
	    		$.each( arrProdName, function( i, v ){
	    			html += '<li>';
	    			html += '<a href="javascript:prod_detail(\''+arrRelation[i]+'\');">';
	    			html += '<figure>';
	    			html += '<img src="<?=$productimgpath ?>'+arrProdImage[i]+'" alt="관심상품">';
	    			html += '<figcaption># '+arrBrandName[i]+'<br>'+arrProdName[i]+' ';
	    			html += '</figcaption>';
	    			html += '</figure>';
	    			html += '</a>';
	    			html += '</li>';
					$(".related-list").html(html);
				});
			}
			$("#content").html(data[0]['content']); // HTML 로 보이도록 수정 (2016.11.02 - peter.Kim)
			$("#instagram_img").attr("src","<?=$instaimgpath ?>"+data[0]['img_file']+"");

			if(data[0]['section'] == null){
				$(".title").append('<button class="comp-like btn-like detail-like like_i'+idx+'"  onclick="detailSaveLike(\''+idx+'\',\'off\',\'instagram\',\''+memId+'\',\'\')" id="likedetail_'+idx+'" title="선택 안됨"><span class="like_icount_'+idx+'"><strong>좋아요</strong>'+data[0]['hott_cnt']+'</span></button>');
			}else{
				$(".title").append('<button class="comp-like btn-like detail-like like_i'+idx+' on " onclick="detailSaveLike(\''+idx+'\',\'on\',\'instagram\',\''+memId+'\',\'\')" id="likedetail_'+idx+'" title="선택됨"><span class="like_icount_'+idx+'"><strong>좋아요</strong>'+data[0]['hott_cnt']+'</span></button>');
			}

	    	$(".view-prev").attr("href","javascript:pagePrev(\""+data[0]['pre_idx']+"\")");
	    	$(".view-next").attr("href","javascript:pageNext(\""+data[0]['next_idx']+"\")");

		});
	}
}

function pageNext(idx){
	if(idx == ""){
		alert("마지막 인스타그램 입니다.");
		return;
	}else{
		$.ajax({
			type: "POST",
			url: "ajax_instagram_detail.php",
			data: "idx="+idx,
			dataType:"JSON"
		}).done(function(data){
			reset();
			var tag ="";
			if(data != null){
				if(data[0]['hash_tags'] != 0){
					var arrTag = data[0]['hash_tags'].split(",");
		    		$.each( arrTag, function( i, v ){
		    			tag += " #"+$.trim(v);
		  			  $(".tag").text(tag);
		  		    });
				}
				if(data[0]['relation_product'] != 0){
					var arrRelation = data[0]['relation_product'].split(",");
				}	
				if(data[0]['productname'] != 0){
					var arrProdName = data[0]['productname'].split(",");
				}		
				if(data[0]['brandname'] != 0){
					var arrBrandName = data[0]['brandname'].split(",");
				}	
				if(data[0]['brandimage'] != 0){
					var arrProdImage = data[0]['brandimage'].split(",");
				}	

                var html =  "";
				if(data[0]['relation_product'] != ""){
		    		$.each( arrRelation, function( i, v ){
		    			html += '<li>';
		    			html += '<a href="javascript:prod_detail(\''+arrRelation[i]+'\');">';
		    			html += '<figure>';
		    			html += '<img src="<?=$productimgpath ?>'+arrProdImage[i]+'" alt="관심상품">';
		    			html += '<figcaption># '+arrBrandName[i]+'<br>'+arrProdName[i]+' ';
		    			html += '</figcaption>';
		    			html += '</figure>';
		    			html += '</a>';
		    			html += '</li>';
						$(".related-list").html(html);
					});
				}
				$("#content").html(data[0]['content']); // HTML 로 보이도록 수정 (2016.11.02 - peter.Kim)
				$("#instagram_img").attr("src","<?=$instaimgpath ?>"+data[0]['img_file']+"");
	
				if(data[0]['section'] == null){
					$(".title").append('<button class="comp-like btn-like detail-like like_i'+idx+'"  onclick="detailSaveLike(\''+idx+'\',\'off\',\'instagram\',\''+memId+'\',\'\')" id="likedetail_'+idx+'" title="선택 안됨"><span class="like_icount_'+idx+'"><strong>좋아요</strong>'+data[0]['hott_cnt']+'</span></button>');
				}else{
					$(".title").append('<button class="comp-like btn-like detail-like like_i'+idx+' on " onclick="detailSaveLike(\''+idx+'\',\'on\',\'instagram\',\''+memId+'\',\'\')" id="likedetail_'+idx+'" title="선택됨"><span class="like_icount_'+idx+'"><strong>좋아요</strong>'+data[0]['hott_cnt']+'</span></button>');
				}
	
		    	$(".view-prev").attr("href","javascript:pagePrev(\""+data[0]['pre_idx']+"\")");
		    	$(".view-next").attr("href","javascript:pageNext(\""+data[0]['next_idx']+"\")");
			}
		});
	}
}

//정렬 순 검색
function sortSelect(val){
	$("input[name=sort]").val(val);
	$("form[name='searchForm']").submit();
}

$(document).on( 'keyup', '#search_word', function( event ){
    if( event.keyCode == 13 ) $('#searchForm').submit();
});
</script>

<?php
include ($Dir."lib/bottom.php")
?>
</BODY>
