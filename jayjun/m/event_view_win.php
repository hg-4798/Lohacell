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
			<span class="brand">이벤트 당첨자 발표</span>
			<span class="date">2017.01.14~2017.01.14</span>
		</h4>

		<div class="editor_area2"><!-- [D] 에디터 영역 -->
			<img src="static/img/test/@event_view03.jpg" alt="이벤트 이미지">
			<p><br>안녕하세요 신원몰입니다.<br><br>
			신원의 다양한 겨울 이벤트에 참여해주신 모든 분들께 감사의 말씀을 드립니다.<br>
			당첨되신 분들 모두 축하드리며, 앞으로도 신원몰 많은 사랑 부탁드립니다.<br><br>
			이벤트 참여고객에게도 기회를 드릴 예정이니 아쉽게도 당첨되지 않으신 고객님들은 도전해보시기 바랍니다.<br>
			(당첨자 발표 하단의 유의 / 고지사항 확인하세요)<br><br>
			▶ 발렌타인 데이 할인권 (20명)<br><br>
			김*름 (4722)<br><br>
			김*희 (5220)<br><br>
			강*리 (9930)<br><br>
			김*희 (5220)<br><br>
			강*리 (9930)<br></p>
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

	</section><!-- //.photo_type_view -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>