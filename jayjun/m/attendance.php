<?php
include_once('outline/header_m.php');
$page_cate = '출석체크';
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

	<section class="attendance">

		<div class="chk_area">
			<span class="icon"></span>
			<p class="ment">매일 출석도장 찍고, 포인트도 챙겨가세요!</p>
			<ul>
				<li>출석체크 포인트 <strong class="point-color">100P</strong></li>
				<li>개근상 <strong class="point-color">2,000P</strong></li>
			</ul>
			<button type="button" class="chk_atdc">출석도장찍기</button>
		</div><!-- //.chk_area -->
		
		<div class="calendar_area mt-35 mb-10">
			<div class="count">
				이번달 출석 횟수 :  <strong class="point-color">6일</strong> / 28일<!-- //[D] 비로그인 회원 접속시 이번달 출석 횟수 미노출 -->
				<span class="tag_regular">개근</span><!-- //[D] 개근시 개근 아이콘 노출 -->
			</div>

			<div class="cal_controls"><!-- [D] 이전달, 다음달 클릭시 월별 출석 확인 가능 -->
				<a href="javascript:;" class="btn_prev">이전달</a>
				<span class="month">2017년 2월</span>
				<a href="javascript:;" class="btn_next">다음달</a>
				<div class="mt-10 ta-c"><a href="javascript:;" class="go_today">TODAY</a></div><!-- //[D] 클릭 시 오늘이 포함된 해당 년/월로 이동 -->
			</div>

			<div class="calendar">
				<ul class="list_day clear">
					<li>SUN</li>
					<li>MON</li>
					<li>TUE</li>
					<li>WED</li>
					<li>THU</li>
					<li>FRI</li>
					<li>SAT</li>
				</ul>
				<ul class="list_date clear"><!-- [D] li의 개수는 항상 7의 배수. 비로그인 회원 접속시 출석, 결석 아이콘 미노출(.attend, .absent 클래스 삭제) -->
					<li></li>
					<li></li>
					<li></li>
					<li class="attend">1</li><!-- //[D] 출석한 경우 .attend 클래스 추가 -->
					<li class="attend">2</li>
					<li class="absent">3</li><!-- //[D] 결석한 경우 .absent 클래스 추가(출석체크 안하고 지나간 경우) -->
					<li class="absent">4</li>
					<li class="attend">5</li>
					<li class="absent">6</li>
					<li class="today">7</li><!-- //[D] 당일은 .today 클래스 추가(출석한 경우 .attend / 결석한 경우 .absent 클래스로 변경) -->
					<li>8</li>
					<li>9</li>
					<li>10</li>
					<li>11</li>
					<li>12</li>
					<li>13</li>
					<li>14</li>
					<li>15</li>
					<li>16</li>
					<li>17</li>
					<li>18</li>
					<li>19</li>
					<li>20</li>
					<li>21</li>
					<li>22</li>
					<li>23</li>
					<li>24</li>
					<li>25</li>
					<li>26</li>
					<li>27</li>
					<li>28</li>
					<li></li>
					<li></li>
					<li></li>
					<li></li>
				</ul>
			</div><!-- //.calendar -->
		</div><!-- //.calendar_area -->

	</section><!-- //.attendance -->

</main>
<!-- //내용 -->

<?php
include_once('outline/footer_m.php');
?>