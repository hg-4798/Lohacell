<?php
include_once('outline/header_m.php');
$page_cate = '기획전';
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
			<span class="brand">아울렛 입고상품 30% 할인</span>
			<span class="date">2017.01.14~2017.01.14</span>
		</h4>

		<div class="timesale">
			<div class="img"><img src="static/img/test/@timesale01.jpg" alt="타임세일 이미지"></div>
			<div class="time_area">
				<span class="icon_watch"></span>
				<div id="countdown" class="t_count">
					<div class="d_day">D-<strong class="days point-color">00</strong></div>
					<div class="time">
						<span class="hours">00</span>
					</div>
					<div class="time">
						<span class="minutes">00</span>
					</div>
					<div class="time">
						<span class="seconds">00</span>
					</div>
				</div>
			</div>
		</div><!-- //.timesale -->

		<div class="btns mt-20">
			<ul>
				<li><a href="promotion.php" class="icon_list">목록</a></li>
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

<script type="text/javascript">
(function (e) {
  e.fn.countdown = function (t, n) {
  function i() {
	eventDate = Date.parse(r.date) / 1e3;
	currentDate = Math.floor(e.now() / 1e3);
	if (eventDate <= currentDate) {
	  n.call(this);
	  clearInterval(interval)
	}
	seconds = eventDate - currentDate;
	days = Math.floor(seconds / 86400);
	seconds -= days * 60 * 60 * 24;
	hours = Math.floor(seconds / 3600);
	seconds -= hours * 60 * 60;
	minutes = Math.floor(seconds / 60);
	seconds -= minutes * 60;
	days == 1 ? thisEl.find(".timeRefDays").text("Day") : thisEl.find(".timeRefDays").text("Days");
	hours == 1 ? thisEl.find(".timeRefHours").text("Hour") : thisEl.find(".timeRefHours").text("Hours");
	minutes == 1 ? thisEl.find(".timeRefMinutes").text("Minute") : thisEl.find(".timeRefMinutes").text("Minutes");
	seconds == 1 ? thisEl.find(".timeRefSeconds").text("Second") : thisEl.find(".timeRefSeconds").text("Seconds");
	if (r["format"] == "on") {
	  days = String(days).length >= 2 ? days : "0" + days;
	  hours = String(hours).length >= 2 ? hours : "0" + hours;
	  minutes = String(minutes).length >= 2 ? minutes : "0" + minutes;
	  seconds = String(seconds).length >= 2 ? seconds : "0" + seconds
	}
	if (!isNaN(eventDate)) {
	  thisEl.find(".days").text(days);
	  thisEl.find(".hours").text(hours);
	  thisEl.find(".minutes").text(minutes);
	  thisEl.find(".seconds").text(seconds)
	} else {
	  alert("Invalid date. Example: 30 Tuesday 2013 15:50:00");
	  clearInterval(interval)
	}
  }
  var thisEl = e(this);
  var r = {
	date: null,
	format: null
  };
  t && e.extend(r, t);
  i();
  interval = setInterval(i, 1e3)
  }
  })(jQuery);
  $(document).ready(function () {
  function e() {
	var e = new Date;
	e.setDate(e.getDate() + 60);
	dd = e.getDate();
	mm = e.getMonth() + 1;
	y = e.getFullYear();
	futureFormattedDate = mm + "/" + dd + "/" + y;
	return futureFormattedDate
  }
  $("#countdown").countdown({
	date: "1 may 2017 00:00:00", // Change this to your desired date to countdown to
	format: "on"
  });
});
</script>

<?php
include_once('outline/footer_m.php');
?>