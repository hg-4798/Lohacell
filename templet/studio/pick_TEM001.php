<?php
$basename=basename($_SERVER["PHP_SELF"]);

$img_path = "/data/shopimages/pick/";
$tblName   = "tblpick";

// ===================================================================================
// 리스트 조회하기
// ===================================================================================
$listnum = 8; // service 사용안함
//    $listnum = 3;   // dev

// 전체 건수 조회
$t_sql    = "SELECT count(*) FROM {$tblName} where hidden = 1 ";
list($total_row_count) = pmysql_fetch($t_sql);


/**************************************************
 * 함수설명 -- 이미지 사이즈가 너무 큰 경우 사이즈를 조정 : 높이 , 넓이 제한
 * 파라미터 -- 파일 이름  , 원하는 가로 ,세로길이
 * 리턴값  --
 * 동작    --
 이미지 길이중 넓이나 높이중 더 긴 쪽을 원하는 사이즈로 만든다
 즉 사이즈가 원하는 사이즈가  h 50, w 30  이고
 이미지 사이즈가  h 70 , w 100 이면 w 100 => 30으로 바꾸고 그 비례로 높이도 바꾼다
 ***************************************************/

function getResizeImg($fileName,$sizeWidth,$sizeHeight)
{
	$img_size=getimagesize($fileName);
	if ($img_size[0]>1)
	{
		//### 이미지중 큰 길이 쪽을 원하는 사이즈로 만든다
		if (($img_size[0]> $sizeWidth) || ($img_size[1] > $sizeHeight))
		{
			if ($img_size[0]> $img_size[1])
			{
				$img_size[1]=floor ($img_size[1]*($sizeWidth/$img_size[0]));
				$img_size[0]=$sizeWidth;
			}
			else
			{
				$img_size[0]=floor ($img_size[0]*($sizeHeight/$img_size[1]));
				$img_size[1]=$sizeHeight;
			}
		}
		$resize = "width=".$img_size[0]." height=".$img_size[1] ;
		return  $resize;
	}
	return ;
}

/*무한스크롤 삭제(1)
 if ( $isMobile ) {
 $t_sql    = "SELECT * FROM {$tblName} where hidden = 1 ORDER BY sort asc, no desc ";
 } else {
 $t_sql    = "SELECT * FROM {$tblName} where hidden = 1 ORDER BY sort asc, no desc limit {$listnum} ";
 } */
$t_sql    = "SELECT * FROM {$tblName} where hidden = 1 ORDER BY sort asc, no desc ";

$result = pmysql_query($t_sql);


$list_html = '';
while ($row = pmysql_fetch_array($result)) {
	$resize = getResizeImg($Dir.$img_path.$row['img'], 220, 200);
	
	$list_html .= '
					<li>
						<a href="'.$row['link'].'">
							<div class="thumb"><img src="'.$img_path.$row['img'].'" alt="PICK 이미지" '.$resize.' ></div>
							<div class="caption">
								<p class="tit">'. $row['title'] .'</p>
								<p class="comment">' . $row['subtitle'] . '</p>
							</div>
						</a>
					</li>';
}

?>
<div id="contents">
	<div class="containerBody sub-page">
		<div class="promotion-wrap">
			<div class="breadcrumb studio-top">
				<ul>
					<li><a href="/">HOME</a></li>
					<li class="on"><a href="<?=$_SERVER['PHP_SELF']?>">PICK</a></li>
				</ul>
			</div>
			<div class="promotion-area">		
			
				<h3 class="title">SNS 핫이슈 아이템을 만나보세요</h3>
				<ul class="pick-list">				
					<?=$list_html?>									
				</ul>
			</div>
		</div>
	</div>
</div>


<div id="create_openwin" style="display:none"></div>

<!-- 무한스크롤 삭제(2)
<script type="text/javascript">
    var page = 2;
    var endpage = false;

    var $win = $(window),
    	$doc = $(document);

	$win.scroll(function()
			{		
    			if($win.scrollTop() == $doc.height() - $win.height())
    			{			
    				if(!endpage){	
    					$.ajax({
    		            	type: "get",
    		            	url: "/front/ajax_get_pick_list.php",
    		            	data: 'gotopage=' + page + '&list_num=<?=$listnum?>'
    		        	}).success(function ( result ) {
    
    		            	var arrTmp = result.split("||");
    
    		            	if ( arrTmp[0] == "END" ) {
    		                	// 마지막 페이지인 경우 더보기 숨김
    		            		endpage = true;
    		            	} else {
    		               		// 더보기 링크를 다음페이지로 셋팅
    		                	page++;
    		            	}
    		            	if ( arrTmp[1] != "" ) {
    		                	// 추가 내용이 있으면 기존꺼에 추가
    		                	$('.pick-list').append(arrTmp[1]);
    		            	}
    		        	});
    				}
				}
			});

</script>
 -->