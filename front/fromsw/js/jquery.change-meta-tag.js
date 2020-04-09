$(document).ready(function () {
	$.urlParam = function(name){
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
		if (results==null){
			return null;
		}
		else{
			return decodeURI(results[1]) || 0;
		}
	}
	var bridx = $.urlParam('bridx');
	var Description = "신원 공식 패션몰,지이크,지이크 파렌하이트,베스띠 벨리,비키,씨,온라인 전용 혜택 신원몰.";
	var title = "신원몰";
	if(bridx == "301"){//BB
		Description = "워킹우먼의 엘레강스룩을 제안하는 여성 패션 브랜드, 제품정보 및 카탈로그 제공, 매장 안내.";
		title = "베스띠벨리-신원몰";
	} else if(bridx == "302"){//VIKI
		Description = "모던&시크 감성의 영캐주얼 브랜드, 제품정보 및 카탈로그 제공, 매장 안내.";
		title = "비키-신원몰";
	} else if(bridx == "303"){//SI
		Description = "스마트 워킹우먼의 감각있는 영캐주얼 브랜드, 제품정보 및 카탈로그 제공, 매장 안내.";
		title = "SI-신원몰";
	} else if(bridx == "304"){//IB
		Description = "뉴 컨템포러리 캐주얼 감성의 여성 의류, 브랜드 소개, 카탈로그, 매장정보 수록";
		title = "이사베이-신원몰";
	} else if(bridx == "305"){//SIEG
		Description = "매스 컨템포러리 브랜드 지이크, 제품정보 및 카탈로그 제공, 매장 안내.";
		title = "지이크-신원몰";
	} else if(bridx == "306"){//SIEG-F
		Description = "지이크 파렌하이트, 남성 캐릭터 캐주얼 브랜드, 캠페인, 룩북, 매장정보 수록";
		title = "지이크 파렌하이트-신원몰";
	} else if(bridx == "307"){//VANHART
		Description = "반하트 디 알바자, 이탈리안 모던 클래식, 믹스&매치 스타일링 제안, 브랜드 비주얼, 매장 안내.";
		title = "반하트 디 알바자-신원몰";
	}
	document.title = title;
	$("meta[name='Description']").attr("content", Description);
	$('head').append('<meta property="og:type" content="website">');
	$('head').append('<meta property="og:title" content="'+title+'">');
	$('head').append('<meta property="og:description" content="'+Description+'">');
	$('head').append('<meta property="og:url" content="'+$(location).attr('href')+'">');

	
});
