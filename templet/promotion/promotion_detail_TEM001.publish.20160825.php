<?php
    $idx = $_GET['idx'];
    $num = $_GET['num'];
    $gotopage = $_GET['gotopage'];
    $keyword = trim($_GET['keyword']);              // 검색어
    $view_mode = trim($_GET['view_mode']) ?: "M";   // M : 이미지 형태, L : 리스트 형태
    $view_type = trim($_GET['view_type']) ?: "A";   // A : 전체, R : 진행중 이벤트, E : 종료된 이벤트, W : 당첨자 발표
    $mode = trim($_GET['mode']);

    $sql = "SELECT *, current_date - publication_date as diff_date FROM tblpromo WHERE idx = '{$idx}' ";

    if ( pmysql_num_rows(pmysql_query($sql)) === 0 ) {
        echo "<script type='text/javascript'>alert('존재하지 않는 내용입니다.'); history.go(-1);</script>";
    }

    $row = pmysql_fetch_object(pmysql_query($sql));
    $event_type         = $row->event_type;
    $winner_list_html   = $row->winner_list_content;    // 당첨자 발표 내용이 있는 경우
    $diff_date          = $row->diff_date;              // 발표일과 오늘날짜의 차이
    $start_date         = $row->start_date;
    $end_date           = $row->end_date;
    $today              = date("Y-m-d");

    if ( $winner_list_html != "" ) {
        // 당첨자 발표가 있는 경우
        $navi_title = "당첨자 발표";
    } else {
        if ( $today >= $start_date && $today <= $end_date ) {
            // 진행중 이벤트
            $navi_title = "진행중 프로모션";
        } else {
            // 종료된 이벤트
            $navi_title = "지난 프로모션";
        }
    }

    if ( $winner_list_html != "" ) {
        // 당첨자 발표가 있는 경우
        include($Dir.TempletDir."promotion/promotion_detail_winner_list_TEM001.php");
    } elseif ( $event_type == "3" && !empty($num) ) {
        // 포토이벤트 상세페이지
        include($Dir.TempletDir."promotion/promotion_detail_{$event_type}_view_TEM001.php");
    } else {
        include($Dir.TempletDir."promotion/promotion_detail_{$event_type}_TEM001.php");
    }

    include($Dir.TempletDir."promotion/promotion_detail_bottom_TEM001.php");
?>


<!-- [D] 20160824 댓글 퍼블리싱 작업 -->
<div id="contents">
	<div class="inner">
		<main class="event_wrap">
			<section class="event_view reply">
				<div class="subject">
					<p>여름시즌 한정 이벤트</p>
					<p>2016.08.05 ~ 2016.09.01</p>
					<div class="sns_wrap">
						<ul>
							<li><a href="javascript:void(0);">facebook</a></li>
							<li><a href="javascript:void(0);">twitter</a></li>
							<li><a href="javascript:void(0);">blog</a></li>
							<li><a href="javascript:void(0);">instagram</a></li>
						</ul>
					</div>
				</div>
				<div class="event_content">
					<img src="../static/img/test/@test_event_content.jpg" alt="이벤트 컨텐츠">
				</div>
				<section class="reply-list">
					<p>댓글<em>(20)</em></p>
					<div class="reply-inner">
						<div class="reply-reg-box">
							<div class="box">
								<form>
									<fieldset>
										<legend>댓글 입력 창</legend>
										<textarea></textarea>
										<button><span class="btn-type1">입력</span></button>
									</fieldset>
								</form>
							</div>
							<div class="text-area">
								<p>* 20자 이상 입력해 주세요.</p>
								<p>* 로그인후 작성하실 수 있습니다.</p>
							</div>
						</div>
						<ul class="reply-list">
							<li>
								<div class="reply">
									<div class="btn">
										<button class="btn-line" type="button"><span>삭제</span></button>
										<button class="btn-line" type="button"><span>수정</span></button>
									</div>
									<p class="name">dksdfdf (2016-05-05 15:50:22)</p>
									<div class="comment">
										<p>고객님, 저희 핫티에서 구매해 주셔서 감사합니다.</p>
									</div>
									<a class="answer-reply">답글 <strong>12</strong></a>
									<ul class="reply-reply">
										<li>
											<div class="btn">
												<button class="btn-line" type="button"><span>삭제</span></button>
												<button class="btn-line" type="button"><span>수정</span></button>
											</div>
											<p class="name"><strong>DKFDLL203</strong><span>2016.07.20</span></p>
											<div class="comment">
												<p>컬러 이뿌당</p>
											</div>
										</li>
										<li>
											<div class="btn hide"> <!-- [D] 버튼 출력발생시 .hide 클래스 삭제 -->
												<button class="btn-line" type="button"><span>삭제</span></button>
												<button class="btn-line" type="button"><span>수정</span></button>
											</div>
											<p class="name"><strong>DKFDLL203</strong><span>2016.07.20</span></p>
											<div class="comment">
												<p>다들 말한것처럼 크기는 사진처럼 넉넉해보이진 않아요.. 일반 에코백 사이즈입니다.</p>
												<p>그래도 이쁘고 짱짱하고 튼튼하고 조으다</p>
												<p>컬러 이뿌당</p>
											</div>
										</li>
									</ul><!-- //.reply-reply -->
								</div><!-- //.reply -->
							</li>
							<li>
								<div class="reply">
									<div class="btn">
										<button class="btn-line" type="button"><span>삭제</span></button>
										<button class="btn-line" type="button"><span>수정</span></button>
									</div>
									<p class="name">dksdfdf (2016-05-05 15:50:22)</p>
									<div class="comment">
										<p>고객님, 저희 핫티에서 구매해 주셔서 감사합니다.</p>
									</div>
									<a class="answer-reply">답글 <strong>12</strong></a>
									<ul class="reply-reply">
										<li>
											<div class="btn hide">
												<button class="btn-line" type="button"><span>삭제</span></button>
												<button class="btn-line" type="button"><span>수정</span></button>
											</div>
											<p class="name"><strong>DKFDLL203</strong><span>2016.07.20</span></p>
											<div class="comment">
												<p>컬러 이뿌당</p>
											</div>
										</li>
									</ul><!-- //.reply-reply -->
								</div><!-- //.reply -->
							</li>
							<li>
								<div class="reply">
									<div class="btn">
										<button class="btn-line" type="button"><span>삭제</span></button>
										<button class="btn-line" type="button"><span>수정</span></button>
									</div>
									<p class="name">dksdfdf (2016-05-05 15:50:22)</p>
									<div class="comment">
										<p>고객님, 저희 핫티에서 구매해 주셔서 감사합니다.</p>
										<p>감사하고 또 감사해요.~ 나이키 짱!</p>
									</div>
									<a class="answer-reply">답글 <strong>12</strong></a>
									<ul class="reply-reply">
										<li>
											<div class="btn hide">
												<button class="btn-line" type="button"><span>삭제</span></button>
												<button class="btn-line" type="button"><span>수정</span></button>
											</div>
											<p class="name"><strong>DKFDLL203</strong><span>2016.07.20</span></p>
											<div class="comment">
												<p>컬러 이뿌당</p>
											</div>
										</li>
									</ul><!-- //.reply-reply -->
								</div><!-- //.reply -->
							</li>
						</ul><!-- //.reply-list -->
					</div>
					<div class="list-paginate mt-40">
						<span class="border_wrap">
							<a href="javascript:;" class="prev-all"></a>
							<a href="javascript:;" class="prev"></a>
						</span>
						<a class="on">1</a>
						<span class="border_wrap">
							<a href="javascript:;" class="next"></a>
							<a href="javascript:;" class="next-all"></a>
						</span>
					</div><!-- //.list-paginate -->
				</section>
				<div class="btn_wrap">
					<a href="javascript:;" class="btn-type1">목록</a>
				</div>
			</section>
		</main>
	</div>
</div><!-- //#contents -->
<!-- // [D] 20160824 댓글 퍼블리싱 작업 -->

<!-- [D] 20160824 상품리스트 퍼블리싱 작업 -->
<div id="contents">
	<div class="inner">
		<main class="event_wrap">
			<section class="event_view product-list">
				<div class="subject">
					<p>여름시즌 한정 이벤트</p>
					<p>2016.08.05 ~ 2016.09.01</p>
					<div class="sns_wrap">
						<ul>
							<li><a href="javascript:void(0);">facebook</a></li>
							<li><a href="javascript:void(0);">twitter</a></li>
							<li><a href="javascript:void(0);">blog</a></li>
							<li><a href="javascript:void(0);">instagram</a></li>
						</ul>
					</div>
				</div>
				<div class="event_content">
					<img src="../static/img/test/@test_event_content.jpg" alt="이벤트 컨텐츠">
				</div>

				<div class="category_tab">
				   <ul class="clear">
					  <li class="on"><a href="#none" onclick="$(window).scrollTop($('#eventCate01').offset().top-100);">나이키 여름 시즌 러닝화 세일</a></li>
					  <li><a href="#none" onclick="$(window).scrollTop($('#eventCate02').offset().top-100);">나이키 쇼룸 상품 빅세일</a></li>
					  <li><a href="#none" onclick="$(window).scrollTop($('#eventCate03').offset().top-100);">나이키 여름 시즌 러닝화 세일</a></li>
					  <li><a href="#none" onclick="$(window).scrollTop($('#eventCate04').offset().top-100);">점퍼</a></li>
					  <li><a href="#none" onclick="$(window).scrollTop($('#eventCate05').offset().top-100);">아우터</a></li>
					  <li><a href="#none" onclick="$(window).scrollTop($('#eventCate06').offset().top-100);">자켓</a></li>
				   </ul>
				</div>

				<h4 id="eventCate01" class="category_name">나이키 여름 시즌 러닝화 세일</h4>
				<div class="brand-style-list">
					<ul class="comp-goods">
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list5.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like on" title="선택됨"><span><strong>좋아요</strong>159</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list6.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list7.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list8.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like on" title="선택됨"><span><strong>좋아요</strong>159</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list9.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
								</figcaption>
							</figure>
						</li>
					</ul>
				</div>

				<h4 id="eventCate02" class="category_name">나이키 쇼룸 상품 빅세일</h4>
				<div class="brand-style-list">
					<ul class="comp-goods">
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list5.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like on" title="선택됨"><span><strong>좋아요</strong>159</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list6.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list7.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list8.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like on" title="선택됨"><span><strong>좋아요</strong>159</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list9.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list5.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like on" title="선택됨"><span><strong>좋아요</strong>159</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list6.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list7.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list8.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like on" title="선택됨"><span><strong>좋아요</strong>159</span></button>
								</figcaption>
							</figure>
						</li>
						<li>
							<figure>
								<a href="javascript:void(0);"><img src="../static/img/test/@test_main_list9.jpg" alt=""></a>
								<div class="color-thumb">
									<ul>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb1.jpg" alt="white"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb2.jpg" alt="blue"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb3.jpg" alt="red"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb4.jpg" alt="green"></a></li>
										<li><a href="javascript:void(0);"><img src="../static/img/test/@test_list_item_thumb5.jpg" alt="black"></a></li>
									</ul>
								</div>
								<figcaption>
									<a href="javascript:void(0);">
										<strong class="brand">ADIDAS</strong>
										<p class="title">Adidas Gazelle 아디다스 가젤</p>
										<span class="price"><strong>100,000원</strong></span>
										<div class="star"><span class="comp-star star-score"><strong style="width:80%;">5점만점에 4점</strong></span>(55)</div>
									</a>
									<button class="comp-like btn-like"><span><strong>좋아요</strong>55</span></button>
								</figcaption>
							</figure>
						</li>
					</ul>
				</div>

				<div class="btn_wrap">
					<a href="javascript:;" class="btn-type1">목록</a>
				</div>
			</section>
		</main>
	</div>
</div><!-- //#contents -->
<!-- // [D] 20160824 상품리스트 퍼블리싱 작업 -->

<!-- [D] 20160824 갤러리형 퍼블리싱 작업 -->
<div id="contents">
	<div class="inner">
		<main class="event_wrap">
			<section class="event_view photo">
				<div class="subject">
					<p>여름시즌 한정 이벤트</p>
					<p>2016.08.05 ~ 2016.09.01</p>
					<div class="sns_wrap">
						<ul>
							<li><a href="javascript:void(0);">facebook</a></li>
							<li><a href="javascript:void(0);">twitter</a></li>
							<li><a href="javascript:void(0);">blog</a></li>
							<li><a href="javascript:void(0);">instagram</a></li>
						</ul>
					</div>
				</div>
				<div class="event_content">
					<img src="../static/img/test/@test_event_content.jpg" alt="이벤트 컨텐츠">
				</div>
				<section class="photo-list-wrap">
					<p>등록된 포토<em>(20)</em></p>
					<ul class="photo-list">
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event01.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event02.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event03.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event04.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event01.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event05.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event06.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event04.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event01.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event02.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event03.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
						<li>
							<a href="#">
								<figure>
									<div class="img"><img src="../static/img/test/@test_photo_event04.jpg" alt=""></div>
									<figcaption>
										<p class="list-subject">여름시즌 한정 이벤트</p>
										<p class="list-date">2016.08.05  16:25:10</p>
										<p class="list-name">damoim79</p>
									</figcaption>
								</figure>
							</a>
						</li>
					</ul><!-- //.reply-list -->
					<div class="list-paginate mt-40">
						<span class="border_wrap">
							<a href="javascript:;" class="prev-all"></a>
							<a href="javascript:;" class="prev"></a>
						</span>
						<a class="on">1</a>
						<span class="border_wrap">
							<a href="javascript:;" class="next"></a>
							<a href="javascript:;" class="next-all"></a>
						</span>
					</div><!-- //.list-paginate -->
					<div class="btn_wrap_list">
						<a href="javascript:;" class="btn-type1 c1 btn-photo-write">쓰기</a>
					</div>
				</section>
				<div class="btn_wrap">
					<a href="javascript:;" class="btn-type1">목록</a>
				</div>
			</section>
		</main>
	</div>
</div><!-- //#contents -->
<!-- // [D] 20160824 갤러리형 퍼블리싱 작업 -->


<!-- [D] 20160824 갤러리형 상세 작업 -->
<div id="contents">
	<div class="inner">
		<main class="event_wrap">
			<section class="event_view photo">
				<div class="subject">
					<p>여름시즌 한정 이벤트</p>
					<p>2016.08.05 ~ 2016.09.01</p>
					<div class="sns_wrap">
						<ul>
							<li><a href="javascript:void(0);">facebook</a></li>
							<li><a href="javascript:void(0);">twitter</a></li>
							<li><a href="javascript:void(0);">blog</a></li>
							<li><a href="javascript:void(0);">instagram</a></li>
						</ul>
					</div>
				</div>

				<div class="event_content">
					<img src="../static/img/test/@test_event_content.jpg" alt="이벤트 컨텐츠">
				</div>

				<section class="photo_view">
					<p>등록된 포토<em>(20)</em></p>
					<div class="subject">
						<p>여름시즌 한정 이벤트</p>
						<p>2016.08.05 ~ 2016.09.01</p>
					</div>
					<div class="event_content">
						<p>안녕하세요.</p>
						<p>핫티 고객만족팀 김운영입니다.</p>
						<img src="../static/img/test/@test_event_content.jpg" alt="이벤트 컨텐츠">
					</div>
					<div class="btn_wrap_list">
						<a href="javascript:;" class="btn-type1 c1 btn-photo-write">수정</a>
						<a href="javascript:;" class="btn-type1 c1 btn-photo-write">목록</a>
					</div>
				</section>

				<div class="btn_wrap">
					<a href="javascript:;" class="btn-type1">목록</a>
				</div>
			</section>
		</main>
	</div>
</div><!-- //#contents -->
<!-- // D] 20160824 갤러리형 상세 작업 -->


<!-- [D] 20160824 쓰기 팝업 -->
<div class="layer-dimm-wrap pop-photo-write"> <!-- .layer-class 이부분에 클래스 추가하여 사용합니다. -->
	<div class="dimm-bg"></div>
	<div class="layer-inner w800">
		<h3 class="layer-title">HOT<span class="type_txt1">;T</span> 포토등록</h3>
		<button type="button" class="btn-close">창 닫기 버튼</button>
		<div class="layer-content">
			<table class="th_left">
				<caption>포토등록</caption>
				<colgroup>
					<col style="width:100px">
					<col style="width:auto">
				</colgroup>
				<tbody>
					<tr>
						<th scope="row"><label for="inp_writer">제목</label></th>
						<td>
							<input type="text" name="inp_writer" id="review_title" title="제목 입력자리" style="width:100%;">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="inp_content">내용</label></th>
						<td>
							<textarea name="inp_content" id="review_content" cols="30" rows="10" style="width:100%"></textarea>
						</td>
					</tr>
					<tr>
						<th scope="row">첨부</th>
						<td class="imageAdd">
							<input type="file" id="up_filename" name="up_filename[]">
							<div class="txt-box"></div> <!-- // 첨부파일명 노출 영역 -->
							<label for="up_filename">찾기</label>
						</td>
					</tr>
					<tr>
						<th scope="row">첨부</th>
						<td class="imageAdd">
							<input type="file" id="up_filename" name="up_filename[]">
							<div class="txt-box"></div> <!-- // 첨부파일명 노출 영역 -->
							<label for="up_filename">찾기</label>
						</td>
					</tr>
					<tr>
						<th scope="row">첨부</th>
						<td class="imageAdd">
							<input type="file" id="up_filename" name="up_filename[]">
							<div class="txt-box"></div> <!-- // 첨부파일명 노출 영역 -->
							<label for="up_filename">찾기</label>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="mt-5">파일명 : 한글,영문,숫자 / 파일용량 : 10M이하 / 첨부기능 파일형식 : GIF,JPG(JPEG)</p>
			<div class="btn_wrap"><a href="javascript:;" class="btn-type1" id="review_submit" onclick="javascript:ajax_review_insert();">등록하기</a></div>
		</div>
	</div>
</div>
<!-- [D] 20160824 쓰기 팝업 -->