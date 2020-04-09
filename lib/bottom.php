<?php
/*********************************************************************
// 파 일 명		: bottom.php
// 설     명		: 하단 템플릿
// 상세설명	: 하단 ( INFOMATION, CONTACT INFO, HELP DESK) 템플릿
// 작 성 자		: 2016.01.14 - 김재수
// 수 정 자		: 2016.07.28 - 김재수
// 수 정 자		: 2017.01.20 - 위민트
//
*********************************************************************/
?>
<?php
if(basename($_SERVER['SCRIPT_NAME'])===basename(__FILE__)) {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>


<!-- quick -->
<div id="quick">
    <div class="quick_wrp">
        <ul class="quick_menu">
            <li>
                <a href="javascript:chkAuthMemLoc('../front/basket.php','pc');">
					<span class="va_wrp">
						<i class="icon-quick-cart"></i>
						<span class="name">장바구니</span>
						<span class="count"><?=number_format($icon_gnb_basket_cnt)?></span>
					</span>
                </a>
            </li>
            <li>
                <a href="../front/mypage_good.php">
					<span class="va_wrp">
						<i class="icon-quick-like"></i>
						<span class="name">좋아요</span>
						<span class="count">12</span>
					</span>
                </a>
            </li>
            <li>
                <a href="../front/mypage_act_point.php">
					<span class="va_wrp">
						<i class="icon-quick-point"></i>
						<span class="name">포인트</span>
					</span>
                </a>
            </li>
        </ul>
        <a href="javascript:;" id="btnTop" class="btn_top"><span>TOP</span></a>
    </div>
</div>
<!-- //quick -->


<!-- footer -->
<footer id="footer">
    <div class="ft_notice">
        <div class="inner_align clear">
            <div class="notice_summary fl-l">
                <h2 class="tit">공지</h2>
                <p class="subject"><a href="">5월 5일 I KNOW I ONE 공식몰 Grand OPEN!</a></p>
                <a href="" class="more">+ MORE</a>
            </div>
            <div class="notice_summary fl-r">
                <h2 class="tit">가이드</h2>
                <p class="subject"><a href="">소중한 내 피부, 정품인지 꼭 확인하세요!</a></p>
                <a href="" class="more">+ MORE</a>
            </div>
        </div>
    </div>
    <div class="ft_wrp">
        <div class="inner_align">
            <div class="logo"><img src="/jayjun/web/static/img/common/ft_logo.png" alt="i KNOW iONE"></div>
            <div class="ft_info">
                <ul class="ft_menu">
                    <li><a href="../front/etc_agreement.php">이용약관</a></li>
                    <li><a href="../front/etc_privacy.php">개인정보취급방침</a></li>
                    <li><a href="../front/customer_notice.php">고객센터</a></li>
                </ul>
                <div class="company_info">
                    <p><span><?=$_data->companyaddr?></span><span>고객(제품)상담 : 080-881-2001</span><span>기업(IR)상담 : 02-2193-9513</span><span>팩스 : 02-2193-9595</span></p>
                    <p><span>법인명 : 제이준코스메틱(주)</span><span>대표자 : <?=$_data->companyowner?></span><span>사업자 등록번호 : [222-85-12156]</span></p>
                    <p><span>개인정보관리책임자 : 최시원</span><span>통신판매업 신고 : 2016-서울강남-00111</span></p>
                </div>
                <div class="copyright_info">Copyright(c) <?=$_data->companyname?>. all right reserved.</div>
                <div class="btn_link"><a href="#;" onclick="javascript:window.open('https://admin.kcp.co.kr/Modules/escrow/kcp_pop.jsp?site_cd=A7J0L','KCPHelp','width=500,height=450,scrollbars=auto,resizable=yes');" target="_blank">에스크로 서비스 가입 확인</a></div>
            </div>
            <div class="family_site">
                <div class="select is-custom">
                    <select title="FAMILY SITE 바로가기">
                        <option value="">FAMILY SITE</option>
                        <option value="">FAMILY SITE 1</option>
                        <option value="">FAMILY SITE 2</option>
                    </select>
                </div>
            </div>
            <div class="sns_link">
                <a href="" target="_blank"><i class="icon-ft-sns-insta">instagram</i></a>
                <a href="" target="_blank"><i class="icon-ft-sns-youtube">youtube</i></a>
                <a href="" target="_blank"><i class="icon-ft-sns-facebook">facebook</i></a>
            </div>
        </div>
    </div>
</footer>
<!-- //footer -->
</div>
<!-- //wrap -->