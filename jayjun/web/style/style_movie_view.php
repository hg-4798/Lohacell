<?php include_once('../outline/header.php') ?>

<div id="contents">
	<div class="style-page">

		<article class="style-wrap">
			<header><h2 class="style-title">MOVIE</h2></header>
			<div class="style-view">
				<div class="bulletin-info mb-10">
					<ul class="title">
						<li>여행을 좋아하는 당신의 패션</li>
						<li class="txt-toneC">2017. 01. 04</li>
					</ul>
					<ul class="share-like clear">
						<li><a href="javascript:history.back();"><i class="icon-list">리스트 이동</i></a></li>
						<li><button type="button"><span><i class="icon-like">좋아요</i></span> <span>11</span></button></li> <!-- [D] 좋아요 i 태그에 .on 추가 -->
						<li>
							<div class="sns">
								<i class="icon-share">공유하기</i>
								<div class="links">
									<a href="#"><i class="icon-kas">카카오 스토리</i></a>
									<a href="#"><i class="icon-facebook-dark">페이스북</i></a>
									<a href="#"><i class="icon-twitter">트위터</i></a>
									<a href="#"><i class="icon-band">밴드</i></a>
									<a href="#"><i class="icon-link">링크</i></a>
								</div>
							</div>
						</li>
					</ul>
				</div><!-- //.bulletin-info -->
				<div class="editor-output">
					<p><iframe width="1100" height="619" src="https://www.youtube.com/embed/RdQQuV2BUX4" frameborder="0" allowfullscreen></iframe></p>
				</div>
				<div class="prev-next clear">
					<div class="prev clear"><span class="mr-20">PREV</span><a href="#">비오는 날을 좋아하는 당신의 패션</a></div>
					<div class="next clear"><span class="ml-20">NEXT</span><a href="#">햇볕 좋은 날을 좋아하는 당신의 패션</a></div>
				</div><!-- //.prev-next -->
				<section class="reply-list-wrap mt-80">
					<header><h2>댓글 입력과 댓글 리스트 출력</h2></header>
					<div class="reply-count clear">
						<div class="fl-l">댓글 <strong class="fz-16">235</strong></div>
						<div class="byte "><span class="point-color">0</span> / 300</div>
					</div>
					<div class="reply-reg-box">
						<div class="box">
							<form>
								<fieldset>
									<legend>댓글 입력 창</legend>
									<textarea placeholder="※ 로그인 후 작성이 가능합니다."></textarea>
									<button class="btn-point" type="submit"><span>등록</span></button>
								</fieldset>
							</form>
						</div>
					</div>
					<ul class="reply-list">
						<li>
							<div class="reply">
								<div class="btn">
									<button class="btn-basic h-small" type="button"><span>수정</span></button>
									<button class="btn-line h-small" type="button"><span>삭제</span></button>
								</div>
								<p class="name"><strong>박길동</strong><span class="pl-5">(2017.02.20 16:33)</span></p>
								<div class="comment editor-output">
									<p>다들 말한것처럼 크기는 사진처럼 넉넉해보이진 않아요.. 일반 에코백 사이즈입니다.</p>
									<p>그래도 이쁘고 짱짱하고 튼튼하고 조으다</p>
									<p>컬러 이뿌당</p>
								</div>
							</div><!-- //.reply -->
						</li>
						<li>
							<div class="reply">
								<div class="btn hide"> <!-- [D] 버튼 출력발생시 .hide 클래스 삭제 -->
									<button class="btn-basic h-small" type="button"><span>수정</span></button>
									<button class="btn-line h-small" type="button"><span>삭제</span></button>
								</div>
								<p class="name"><strong>홍길동</strong><span class="pl-5">(2017.02.20 16:33)</span></p>
								<div class="comment editor-output">
									<p>다들 말한것처럼 크기는 사진처럼 넉넉해보이진 않아요.. 일반 에코백 사이즈입니다.</p>
									<p>그래도 이쁘고 짱짱하고 튼튼하고 조으다</p>
									<p>컬러 이뿌당</p>
								</div>
							</div><!-- //.reply -->
						</li>
						<li>
							<div class="reply">
								<div class="btn hide"> <!-- [D] 버튼 출력발생시 .hide 클래스 삭제 -->
									<button class="btn-basic h-small" type="button"><span>수정</span></button>
									<button class="btn-line h-small" type="button"><span>삭제</span></button>
								</div>
								<p class="name"><strong>홍길동</strong><span class="pl-5">(2017.02.20 16:33)</span></p>
								<div class="comment editor-output">
									<p>다들 말한것처럼 크기는 사진처럼 넉넉해보이진 않아요.. 일반 에코백 사이즈입니다.</p>
									<p>그래도 이쁘고 짱짱하고 튼튼하고 조으다</p>
									<p>컬러 이뿌당</p>
								</div>
							</div><!-- //.reply -->
						</li>
					</ul><!-- //.reply-list -->
					<div class="list-paginate mt-20">
						<a href="#" class="prev-all"></a>
						<a href="#" class="prev"></a>
						<a href="#" class="number on">1</a>
						<a href="#" class="number">2</a>
						<a href="#" class="number">3</a>
						<a href="#" class="number">4</a>
						<a href="#" class="number">5</a>
						<a href="#" class="number">6</a>
						<a href="#" class="number">7</a>
						<a href="#" class="number">8</a>
						<a href="#" class="number">9</a>
						<a href="#" class="number">10</a>
						<a href="#" class="next on"></a>
						<a href="#" class="next-all on"></a>
					</div><!-- //.list-paginate -->
				</section><!-- //.reply-list-wrap -->
			</div><!-- //.style-view -->
		</article>

	</div>
</div><!-- //#contents -->

<?php include_once('../outline/footer.php') ?>