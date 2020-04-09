<?php
include_once('outline/header_m.php');
$page_cate = '이벤트';
?>

<!-- 내용 -->
<main id="content" class="subpage">
	<section class="page_local">
		<h2 class="page_title">
			<a href="javascript:history.back();" class="prev">이전페이지</a>
			<span>프로모션</span>
		</h2>
		<div class="breadcrumb">
			<?php include_once('promotion_menu.php'); ?>
		</div>
	</section><!-- //.page_local -->

	<section class="photo_type_view">
		<h4 class="title_area with_brand">
			<span class="brand">여행하기 좋은 계절의 패션스타일</span>
			<span class="date">2017.01.14</span>
		</h4>

		<div><!-- [D] 에디터 영역 -->
			<img src="static/img/test/@event_view01.jpg" alt="이벤트 이미지">
		</div>

		<div class="btns mt-20">
			<ul>
				<li><a href="event.php" class="icon_list">목록</a></li>
				<li><a href="javascript:;" class="icon_like" title="선택 안됨">좋아요</a> <span class="count">23</span></li><!-- [D] 클릭시 좋아요 숫자+1, 재클릭시 좋아요 숫자-1 -->
				<li>
					<div class="wrap_bubble layer_sns_share on">
						<div class="btn_bubble"><button type="button" class="btn_sns_share">sns 공유</button></div>
						<div class="pop_bubble">
							<div class="inner">
								<button type="button" class="btn_pop_close">닫기</button>
								<div class="icon_container">
									<a href="javascript:;"><img src="static/img/icon/icon_sns_kas.png" alt="카카오스토리"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_face.png" alt="페이스북"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_twit.png" alt="트위터"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_band.png" alt="밴드"></a>
									<a href="javascript:;"><img src="static/img/icon/icon_sns_link.png" alt="url"></a>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
		</div><!-- //.btns -->

		<div class="other_posting">
			<dl>
				<dt>PREV</dt>
				<dd><a href="#">비오는 날을 좋아하는 당신의 패션</a></dd>
			</dl>
			<dl>
				<dt>NEXT</dt>
				<dd><a href="#">햇볕 좋은 날을 좋아하는 당신의 패션</a></dd>
			</dl>
		</div><!-- //.other_posting -->

		<div class="reply_write">
			<textarea placeholder="※ 로그인 후 작성이 가능합니다."></textarea>
			<div class="clear">
				<span class="txt_count"><span class="point-color">0</span>/300</span>
				<a href="javascript:;" class="btn-point">등록</a>
			</div>
		</div><!-- //.reply_write -->

		<div class="reply_list">
			<p class="count">댓글 21</p>
			<ul>
				<li><!-- [D] 본인이 작성한 댓글 -->
					<div class="info">
						<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
					</div>
					<p class="content">궁금하고 또 궁금해서 물어보는데요. 매거진안에 나오는 옷 어디 브랜드인가요?</p>
					<div class="btns">
						<a href="javascript:;" class="btn-line">수정</a>
						<a href="javascript:;" class="btn-basic">삭제</a>
					</div>
				</li>

				<li><!-- [D] 수정 클릭시 작성폼으로 변경 (텍스트 수정 가능) -->
					<div class="info">
						<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
					</div>
					<div class="reply_write">
						<textarea>궁금하고 또 궁금해서 물어보는데요. 매거진안에 나오는 옷 어디 브랜드인가요?</textarea>
						<div class="clear">
							<span class="txt_count"><span class="point-color">0</span>/300</span>
							<a href="javascript:;" class="btn-line">수정</a>
						</div>
					</div>
				</li>

				<li>
					<div class="info">
						<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
					</div>
					<p class="content">궁금하고 또 궁금해서 물어보는데요. 매거진은 일주일에 한번씩 업데이트 되나요, 멋집니다.</p>
				</li>

				<li>
					<div class="info">
						<span class="writer">nunuen</span><span class="date">2017.01.20 17:33</span>
					</div>
					<p class="content">옷이 너무 이뻐요~</p>
				</li>
			</ul>
		</div><!-- //.reply_list -->
		
		<div class="list-paginate mt-15">
			<a href="#" class="prev-all disabled">처음</a><!-- [D] 버튼 비활성인 경우 .disabled 클래스 추가 -->
			<a href="#" class="prev disabled">이전</a>
			<a href="#" class="on">1</a>
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">5</a>
			<a href="#">6</a>
			<a href="#" class="next">다음</a>
			<a href="#" class="next-all">끝</a>
		</div>
	</section><!-- //.photo_type_view -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>