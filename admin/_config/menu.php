<?php

$menu = array();

//환경설정
$menu['cog'] = array(
	'name'=>'환경설정',
	'href'=>DIR_ADMIN.'/shop_basicinfo.php',
	'icon'=>'',
	'children'=>array(
		array(
			'idx'=> 'sh-1',
			'name'=>'기본정보설정',
			'children'=>array(
				array('name'=>'상점 기본정보 관리', 'href'=>DIR_ADMIN.'/shop_basicinfo.php'),
				array('name'=>'브라우저 타이틀/키워드', 'href'=>DIR_ADMIN.'/shop_keyword.php'),
				array('name'=>'쇼핑몰 이용약관', 'href'=>DIR_ADMIN.'/shop_agreement.php'),
				array('name'=>'쇼핑몰 개인정보처리방침', 'href'=>DIR_ADMIN.'/shop_privacyinfo.php'),
				array('name'=>'쇼핑몰 기타약관', 'href'=>DIR_ADMIN.'/shop_etc_agreement.php')
			)
		),
		array(
			'idx'=> 'sh-2',
			'name'=>'운영설정',
			'children'=>array(
				array('name'=>'상품 검색', 'href'=>DIR_ADMIN.'/shop_search.php'),
				array('name'=>'포인트 설정', 'href'=>DIR_ADMIN.'/shop_point.php'),
				array('name'=>'마일리지 설정', 'href'=>DIR_ADMIN.'/shop_mileage.php'),
				array('name'=>'쿠폰 정책설정', 'href'=>DIR_ADMIN.'/shop_coupon.php'),
				array('name'=>'주문 정책설정', 'href'=>DIR_ADMIN.'/shop_order.php'),
				array('name'=>'카드사 혜택안내설정', 'href'=>DIR_ADMIN.'/shop_card.php')
			)
		),
		array(
			'idx'=> 'sh-3',
			'name'=>'배송관리',
			'children'=>array(
				array('name'=>'배송/반품 정책설정', 'href'=>DIR_ADMIN.'/shop_deli.php'),
				array('name'=>'지역별 배송비설정', 'href'=>DIR_ADMIN.'/shop_area_deli.php'),
			)
		),
		array(
			'idx'=> 'sh-4',
			'name'=>'보안설정',
			'children'=>array(
				array('name'=>'그룹 및 권한설정', 'href'=>DIR_ADMIN.'/shop_rolelist.php'),
				array('name'=>'운영자/부운영자설정', 'href'=>DIR_ADMIN.'/shop_adminlist.php'),
			)
		)
	)
);

$menu['design'] = array(
	'name'=>'디자인관리',
	'href'=>DIR_ADMIN.'/main_banner_mng.php?no=113',
	'icon'=>'',
	'children'=>array(
		array(
			'idx'=> 'de-1',
			'name'=>'메인관리',
			'children'=>array(
				array('name'=>'GNB 배너','href'=>DIR_ADMIN.'/main_banner_mng.php?no=113'),
				array('name'=>'상단 롤링 배너','href'=>DIR_ADMIN.'/main_banner_mng.php?no=77'),
				array('name'=>'중단 배너','href'=>DIR_ADMIN.'/main_banner_mng.php?no=85'),
				//array('name'=>'중단 띠배너','href'=>DIR_ADMIN.'/main_banner_mng.php?no=120'),
				array('name'=>'하단 배너','href'=>DIR_ADMIN.'/main_banner_mng.php?no=118'),
				//array('name'=>'NEW ARRIVALS 관리','href'=>DIR_ADMIN.'/main_banner_mng.php?no=78'),
				array('name'=>'BEST SELLER관리','href'=>DIR_ADMIN.'/main_banner_mng.php?no=87'),
				array('name'=>'REVIEW 배너관리','href'=>DIR_ADMIN.'/main_review.php'),
				//array('name'=>'RANKING 관리','href'=>DIR_ADMIN.'/main_banner_mng.php?no=88'),
				array('name'=>'NEW ARRIVALS 관리','href'=>DIR_ADMIN.'/main_new_arrivals.php'),
				array('name'=>'카테고리 배너', 'href'=>DIR_ADMIN.'/category_banner.php', 'sync'=>array(
					DIR_ADMIN.'/design/category_banner_register.php'
				))
			)
		),
		array(
			'idx'=> 'de-2',
			'name'=>'이벤트&기획전 배너관리',
			'children'=>array(
				array('name'=>'이벤트&기획전 메인배너','href'=>DIR_ADMIN.'/main_banner_mng.php?no=127'),
				//array('name'=>'기획전 메인배너','href'=>DIR_ADMIN.'/main_banner_mng.php?no=128'),
			)
		)
	)
);


$menu['member'] = array(
	'name'=>'회원관리', 
	'href'=>DIR_ADMIN.'/member_list.php',
	'icon'=>'',
	'children'=>array(
		array(
			'idx'=> 'me-1',
			'name'=>'회원정보관리',
			'children'=>array(
				array('name'=>'회원목록','href'=>DIR_ADMIN.'/member_list.php'),
				array('name'=>'휴면회원조회','href'=>DIR_ADMIN.'/member_sleep_list.php'),
				array('name'=>'탈퇴회원조회','href'=>DIR_ADMIN.'/memberout_list.php')
			)
		),
		array(
			'idx'=> 'me-2',
			'name'=>'회원등급 설정',
			'children'=>array(
				array('name'=>'회원등급 관리','href'=>DIR_ADMIN.'/member_groupnew.php'),
				array('name'=>'등급별 회원관리','href'=>DIR_ADMIN.'/member_groupmemberview.php', 'sync'=>array(DIR_ADMIN.'/member_mailsend.php'))
			)
		),
		array(
			'idx'=> 'me-3',
			'name'=>'회원관리 부가기능',
			'children'=>array(
				array('name'=>'회원 로그인 리스트','href'=>DIR_ADMIN.'/member_log_list.php'),
				array('name'=>'포인트 지급/사용내역','href'=>DIR_ADMIN.'/member/point_log.php'),
				array('name'=>'마일리지 지급/사용내역','href'=>DIR_ADMIN.'/member/mileage_log.php')
			)
		)
	)
);

$menu['product'] = array(
	'name'=>'상품관리', 
	'href'=>DIR_ADMIN.'/product/product_list.php', 
	'icon'=>'',
	'children'=>array(
		array(
			'idx'=> 'pr-1',
			'name'=>'카테고리관리',
			'children'=>array(
				array('name'=>'카테고리관리','href'=>DIR_ADMIN.'/product/product_code.php'),
				array('name'=>'라인관리','href'=>DIR_ADMIN.'/product/product_line.php')
			)
		),
		array(
			'idx'=> 'pr-2',
			'name'=>'상품관리',
			'children'=>array(
				array(
					'name'=>'상품목록',
					'href'=>DIR_ADMIN.'/product/product_list.php',
					'sync'=>array(
						DIR_ADMIN.'/product/product_input.php' //상품등록
					)
				),
				array('name'=>'카테고리 추천상품 관리','href'=>DIR_ADMIN.'/product/product_mdchoice.php'),
				array('name'=>'상품 컬러칩관리', 'href'=>DIR_ADMIN.'/product/colorchip.php','sync'=>array(DIR_ADMIN.'/product/colorchip_register.php')),
				array('name'=>'기간할인설정','href'=>DIR_ADMIN.'/product/product_timesale.php', 'sync'=>array(DIR_ADMIN.'/product/product_timesale.register.php'))
				//array('name'=>'기간할인등록/수정','href'=>DIR_ADMIN.'/product/product_timesale.register.php','hidden'=>true),
			)
		),
		/*
		array(
			'idx'=> 'pr-3',
			'name'=>'상품 일괄 관리',
			'children'=>array(
				array('name'=>'일반상품판매가 일괄변경','href'=>DIR_ADMIN.'/product/price_change.php?type=normal'),
				array('name'=>'임직원상품판매가 일괄변경','href'=>DIR_ADMIN.'/product/price_change.php?type=staff'),
				array('name'=>'상품 일괄 간편수정','href'=>DIR_ADMIN.'/product/product_batch_update.php'),
				
			)
		),
		*/
		array(
			'idx'=> 'pr-4',
			'name'=>'기타관리',
			'children'=>array(
				array('name'=>'아이콘 일괄수정','href'=>DIR_ADMIN.'/product/product_batch_icon.php'),
				array('name'=>'상품 아이콘관리', 'href'=>DIR_ADMIN.'/product/product_icon.php')
			)
		)	
	)
);

$menu['order'] = array(
	'name'=>'주문/매출',
	'href'=>DIR_ADMIN.'/order_list_all.php',
	'icon'=>'',
	'children'=>array(
		array(
			'idx'=> 'or-1',
			'name'=>'주문조회 및 배송관리',
			'children'=>array(
				array('name'=>'전체주문조회', 'href'=>DIR_ADMIN.'/order_list_all.php'),
				array('name'=>'주문완료조회', 'href'=>DIR_ADMIN.'/order/order_step1.php'),
				array('name'=>'결제완료조회', 'href'=>DIR_ADMIN.'/order/order_step2.php'),
				array('name'=>'배송준비중조회', 'href'=>DIR_ADMIN.'/order/order_step3.php'),
				array('name'=>'배송중조회', 'href'=>DIR_ADMIN.'/order/order_step4.php'),
				array('name'=>'배송완료조회', 'href'=>DIR_ADMIN.'/order/order_step5.php'),
				// array('name'=>'주문취소목록', 'href'=>DIR_ADMIN.'/order_list_cancel.php')
			)
		),
		array(
			'idx'=> 'or-2',
			'name'=>'장바구니 및 매출분석',
			'children'=>array(
//				array('name'=>'전체상품 매출분석', 'href'=>DIR_ADMIN.'/order_allsale.php'),
//				array('name'=>'개별상품 매출분석', 'href'=>DIR_ADMIN.'/order_eachsale.php'),
				array('name'=>'카테고리 매출분석', 'href'=>DIR_ADMIN.'/sales/category.php'),
				array('name'=>'장바구니 상품분석', 'href'=>DIR_ADMIN.'/order_basket.php') 
			)
		),
		array(
			'idx'=> 'or-3',
			'name'=>'매출관리',
			'children'=>array(
				array('name'=>'일자별 조회', 'href'=>DIR_ADMIN.'/sales/price_day.php'),
				array('name'=>'월별 조회', 'href'=>DIR_ADMIN.'/sales/price_month.php'),
				array('name'=>'결제수단별 조회', 'href'=>DIR_ADMIN.'/sales/price_paymethod.php'),
				array('name'=>'주문건별 조회', 'href'=>DIR_ADMIN.'/sales/price_order.php'),
				array('name'=>'품목별 조회', 'href'=>DIR_ADMIN.'/sales/price_option.php')
			)
		)	
	)
);

$menu['marketing'] = array(
	'name'=>'마케팅지원', 
	'href'=>DIR_ADMIN.'/market_promotion_list.php',
	'icon'=>'',
	'children'=>array(
		array(
			'idx'=> 'ma-1',
			'name'=>'프로모션관리',
			'children'=>array(
				array('name'=>'기획전관리','href'=>DIR_ADMIN.'/market_promotion_list.php', 'sync'=>array(DIR_ADMIN.'/market_promotion_reg.php', DIR_ADMIN.'/promotion/market_promotion_product_new.php')),
				array('name'=>'이벤트관리','href'=>DIR_ADMIN.'/market_promotion_event_list.php', 'sync'=>array(DIR_ADMIN.'/market_promotion_event_reg.php')),
				array('name'=>'팝업관리','href'=>DIR_ADMIN.'/market_eventpopup.php', 'sync'=>array(DIR_ADMIN.'/market_eventpopup.write.php')),
				array('name'=>'사은품설정','href'=>DIR_ADMIN.'/gift_settings.php')
			)
		),
		array(
			'idx'=> 'ma-2',
			'name'=>'쿠폰관리', 
			'children'=>array(
				array('name'=>'쿠폰목록','href'=>DIR_ADMIN.'/coupon_lists.php', 'sync'=>array(DIR_ADMIN.'/coupon/coupon_detail.php',DIR_ADMIN.'/coupon/coupon_manual_issued.php')),
				array('name'=>'쿠폰등록','href'=>DIR_ADMIN.'/coupon_register.php'),
			)
		)
	)
);

$menu['contents'] = array(
	'name'=>'콘텐츠관리',
	'href'=>DIR_ADMIN.'/community_lookbook_list.php',
	'icon'=>'',
	'children'=>array(
		array(
			'idx'=> 'co-1',
			'name'=>'콘텐츠관리',
			'children'=>array(
				array('name'=>'LOOKBOOK 관리', 'href'=>DIR_ADMIN."/community_lookbook_list.php", 'sync'=>array(
					DIR_ADMIN.'/community_lookbook_write.php'
				)),
				array('name'=>'인스타그램관리', 'href'=>DIR_ADMIN."/sns_list.php")
			)
		),
		array(
			'idx'=> 'co-2',
			'name'=>'i REVIEW관리',
			'children'=>array(
				array('name'=>'동영상 등록 관리', 'href'=>DIR_ADMIN."/review/review_video_list.php", 'sync'=>array(
					DIR_ADMIN.'/review/video_write.php'
				)),
				array('name'=>'서브 배너관리', 'href'=>DIR_ADMIN."/review/review_banner_list.php", 'sync'=>array(
					DIR_ADMIN.'/review/review_banner_write.php'
				)),
				array('name'=>'블로그 콘텐츠관리', 'href'=>DIR_ADMIN."/review/review_blog_list.php", 'sync'=>array(
					DIR_ADMIN.'/review/review_blog_write.php'
				))
			)
		)
	)
);

$menu['statistics'] = array(
	'name'=>'통계분석',
	'href'=>DIR_ADMIN.'/counter_timeorder.php',
	'icon'=>'',
	'children'=>array(
		array(
			'idx'=> 'st-1',
			'name'=>'트래픽분석',
			'children'=>array(
				array('name'=>'시간별 주문 시도건수','href'=>DIR_ADMIN.'/counter_timeorder.php'),
				array('name'=>'일자별 주문 시도건수','href'=>DIR_ADMIN.'/counter_dayorder.php'),
				array('name'=>'일자별 회원가입수','href'=>DIR_ADMIN.'/counter_memberday.php'),
			)
		),
		array(
			'idx'=> 'st-2',
			'name'=>'고객선호도 분석',
			'children'=>array(
				array('name'=>'분류별 선호도','href'=>DIR_ADMIN.'/counter_prcodeprefer.php'),
				array('name'=>'상품 선호도','href'=>DIR_ADMIN.'/counter_productprefer.php'),
				array('name'=>'상품 검색 선호도','href'=>DIR_ADMIN.'/counter_prsearchprefer.php'),
				array('name'=>'Site 구성요소 선호도','href'=>DIR_ADMIN.'/counter_sitepageprefer.php')
			)
		)
	)
);

$menu['cs'] = array(
	'name'=>'CS관리', 
	'href'=>DIR_ADMIN.'/cscenter/cs_cancel.php',
	'icon'=>'',
	'children'=>array(
		array(
			'idx'=> 'cs-1',
			'name'=>'CS 관리',
			'children'=>array(
				//array('name'=>'CS 통합 리스트','href'=>DIR_ADMIN.'/cscenter/cs_all.php'),
				array('name'=>'취소','href'=>DIR_ADMIN.'/cscenter/cs_cancel.php'),
				array('name'=>'교환','href'=>DIR_ADMIN.'/cscenter/cs_exchange.php'),
				array('name'=>'반품','href'=>DIR_ADMIN.'/cscenter/cs_return.php')
			)
		),
		array(
			'idx'=> 'cs-2',
			'name'=>'게시판관리',
			'children'=>array(
				array('name'=>'CS게시판','href'=>DIR_ADMIN.'/cscenter_freeboard.php'),
				array('name'=>'CS 공지사항','href'=>DIR_ADMIN.'/cscenter_notice.php'),
				array('name'=>'1:1문의 관리','href'=>DIR_ADMIN.'/community_personal.php'),
				array('name'=>'고객 공지사항 관리','href'=>DIR_ADMIN.'/customer_notice.php'),
				array('name'=>'FAQ 카테고리 관리','href'=>DIR_ADMIN.'/faq_category.php'),
				array('name'=>'FAQ 관리','href'=>DIR_ADMIN.'/faq.php'),
				array('name'=>'FAQ 등록','href'=>DIR_ADMIN.'/faq_register.php')
			)
		),
		array(
			'idx'=> 'cs-3',
			'name'=>'상품리뷰관리',
			'children'=>array(
				array('name'=>'상품리뷰관리','href'=>DIR_ADMIN.'/product_review.php')
			)
		),
		array(
			'idx'=> 'cs-4',
			'name'=>'매장관리',
			'children'=>array(
				array('name'=>'매장관리','href'=>DIR_ADMIN.'/store_list.php')
			)
		)
	)
);


$nav = array();
foreach($menu as $k1=>&$v1) {
	$id = $k1;
	$v1['id'] = $id;

	if(!is_array($v1['children'])) continue;
	
	foreach($v1['children'] as $k2=>&$v2) {
		$id=$k1.'_'.$k2;
		$v2['id'] = $id;

		$v2['collapse'] = (isset($_COOKIE['JJLNB-'.$id]))?'collapse':'';

		if(!is_array($v2['children'])) continue;
		$active = false;

		

		foreach($v2['children'] as $k3=>&$v3) {
			$id=$k1.'_'.$k2.'_'.$k3;
			$v3['id'] = $id;
			//if($_SERVER['REQUEST_URI'] == $v3['href']) {
			//echo $v3['name'].' : '.$v3['href']."<Br />";

		
			$active = (strstr($_SERVER['REQUEST_URI'],$v3['href']))?true:false;
			if(!$active && $v3['sync']) {
				foreach($v3['sync'] as $sync) {
					if (strstr($_SERVER['REQUEST_URI'],$sync)) {
						$active = true;
						continue;
					}
				}
			}

			if($active) {
				$v1['active'] = 'active';
				$v2['active'] = 'active';
				$v3['active'] = 'active';

				$nav = array($v1['name'],$v2['name'],$v3['name']);
				$lnb = $v1;
				$current_idx = $v2['idx'];
			}
		}
	}
}

//pre($lnb);
$_NAV = array(
	'url'=>$_SERVER['PHP_SELF'],
	'nav'=>$nav,
	'lnb'=>$lnb['children'],
	'current_idx' =>$current_idx,
	'page_tit'=>array_pop($nav)
);

//  pre($_NAV);



/*
$menu['cog_1'] = array('name'=>'기본정보설정', 'href'=>'', 'parent'=>'cog');
$menu['cog_1_1'] = array('name'=>'상점 기본정보 관리', 'href'=>DIR_ADMIN.'/shop_basicinfo.php', 'parent'=>'cog_1');
$menu['cog_1_1'] = array('name'=>'브라우저 타이틀/키워드', 'href'=>DIR_ADMIN.'/shop_basicinfo.php', 'parent'=>'cog_1');
$menu['cog_1_1'] = array('name'=>'쇼핑몰 이용약관', 'href'=>DIR_ADMIN.'/shop_basicinfo.php', 'parent'=>'cog_1');
$menu['cog_1_1'] = array('name'=>'쇼핑몰 개인정보처리방침', 'href'=>DIR_ADMIN.'/shop_basicinfo.php', 'parent'=>'cog_1');
$menu['cog_1_1'] = array('name'=>'쇼핑몰 기타약관', 'href'=>DIR_ADMIN.'/shop_basicinfo.php', 'parent'=>'cog_1');

$menu['cog_2'] = array('name'=>'운영설정', 'href'=>'', 'parent'=>'cog_2');
$menu['cog_2_1'] = array('name'=>'상품검색 설정', 'href'=>'', 'parent'=>'cog_2');
$menu['cog_2_2'] = array('name'=>'포인트 정책설정', 'href'=>'', 'parent'=>'cog_2');
$menu['cog_2_3'] = array('name'=>'쿠폰 정책설정', 'href'=>'', 'parent'=>'cog_2');
$menu['cog_2_4'] = array('name'=>'주문 정책설정', 'href'=>'', 'parent'=>'cog_2');

$menu['cog_3'] = array('name'=>'배송관리', 'href'=>'', 'parent'=>'cog');
$menu['cog_4'] = array('name'=>'보안설정', 'href'=>'', 'parent'=>'cog');
*/







//pre($menu);

$_MENU = array($menu['cog'],$menu['design'],$menu['member'],$menu['product'],$menu['order'],$menu['marketing'],$menu['contents'],$menu['statistics'],$menu['cs']);

//unset($menu);

function pre($arr) {
	echo "<pre>";
	print_R($arr);
	echo "</pre>";
}
?>