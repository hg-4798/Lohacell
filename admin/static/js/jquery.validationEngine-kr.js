(function($) {
	$.fn.validationEngineLanguage = function() {
	};
	$.validationEngineLanguage = {
		newLang : function() {
			$.validationEngineLanguage.allRules = {
				"required" : { // Add your regex rules here, you can take
								// telephone as an example
					"regex" : "none",
					"alertText" : "필수",
					"alertTextCheckboxMultiple" : "선택항목을 선택해주세요.",
					"alertTextCheckboxe" : "필수체크 사항입니다."
				},
				"equals":{
					"regex" : "none",
					"alertText" : "필수"
				},
				"length" : {
					"regex" : "none",
					"alertText" : "",
					"alertText2" : " ~ ",
					"alertText3" : " 자를 입력하세요."
				},
				"maxCheckbox" : {
					"regex" : "none",
					"alertText" : "Checks allowed Exceeded"
				},
				"minCheckbox" : {
					"regex" : "none",
					"alertText" : "최소한 항목을",
					"alertText2" : " 개 선택해주세요."
				},
                "minRadio" : {
                    "regex" : "none",
                    "alertText" : "최소한 항목을",
                    "alertText2" : " 개 선택해주세요."
                },
				"confirm" : {
					"regex" : "none",
					"alertText" : "입력사항이 맞지 않습니다."
				},
				"telephone" : {
					//"regex" : /^([0-9]{2,3}\-\[0-9]{3,4}\-\[0-9]{4}?)$/,
					"regex": /^[0-9]{2,3}-[0-9]{3,4}-[0-9]{4}$/,
					"alertText" : "전화번호가 잘못 입력되었습니다. <br><br>입력 예)<br>02-0000-0000 / 010-0000-0000"
				},
				"email": {
					// HTML5 compatible email regex ( http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#	e-mail-state-%28type=email%29 )
					"regex": /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
					"alertText": "이메일 주소가 유효하지 않습니다."
				},
				"email_id":{
					"regex" : "^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*",
					"alertText": "이메일 아이디가 올바르지 않습니다."
				},
				"domain":{
					"regex" : "^([a-z0-9-]+\.)+[a-z0-9]{2,4}$",
					"alertText": "도메인이 올바르지 않습니다."
				},
				"date" : {
					"regex": /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/,
					"alertText" : "날짜는 반드시 2010-08-01 형태로 입력되어야 합니다."
				},
				"year" : {
					"regex": /^[0-9]{4}$/,
					"alertText" : "연도는 반드시 2015 형태로 입력되어야 합니다."
				},
				"noSpecialCaracters" : {
					"regex" : "^[0-9a-zA-Z가-힣\.]+$",
					"alertText" : "특수문자 입력은 불가합니다."
				},
				"ajaxUser" : {
					"file" : "validateUser.php",
					"extraData" : "name=eric",
					"alertTextOk" : "유효한 사용자입니다.",
					"alertTextLoad" : "로딩중입니다. 잠시 기다려주세요.",
					"alertText" : "This user is already taken"
				},
				"ajaxName" : {
					"file" : "validateUser.php",
					"alertText" : "This name is already taken",
					"alertTextOk" : "This name is available",
					"alertTextLoad" : "로딩중입니다. 잠시 기다려주세요."
				},
				"onlyLetter" : {
					"regex" : "^[a-zA-Z\ \']+$",
					"alertText" : "문자열만 입력 가능합니다."
				},
				"onlyLetterNumber" : {
					"regex" : "^[a-zA-Z0-9]+$", ///^[a-zA-Z0-9]+$/",
					"alertText" : "영문자와 숫자만 입력 가능합니다."
				},
				"numeric" : {
					"regex" : "^[0-9]+$", ///^[a-zA-Z0-9]+$/",
					"alertText" : "숫자만 입력 가능합니다."
				},
				"price" : {
					"regex" : "^[0-9\,]+$", ///^[a-zA-Z0-9]+$/",
					"alertText" : "숫자만 입력 가능합니다."
				},
				"numberFormat": {
					"regex" : "^[\,0-9]+$", ///^[a-zA-Z0-9]+$/",
					"alertText" : "숫자만 입력 가능합니다."
				},
				"nospecialchar": {
					"regex" : "^[가-힣a-zA-Z0-9\ ]+$", ///^[a-zA-Z0-9]+$/",
					"alertText" : "특수문자는 입력할 수 없습니다."
				},
				"nospecialcharblank": {
					"regex" : "^[가-힣a-zA-Z0-9]+$", ///^[a-zA-Z0-9]+$/",
					"alertText" : "특수문자 및 공백은 입력할 수 없습니다."
				},
				"jobsubject":{
					"regex" : "^[\.\,\-\_\)\(\[가-힣a-zA-Z0-9\ ]+$", ///^[a-zA-Z0-9]+$/",
					"alertText" : "특수문자는 입력할 수 없습니다."
				},
				"validate2fields" : {
					"nname" : "validate2fields",
					"alertText" : "성,이름을 모두 입력해야합니다."
				},
				"minSize": {
					"regex": "none",
					"alertText": "최소한 ",
					"alertText2": " 글자를 입력해주세요."
				},
				"maxSize": {
					"regex": "none",
					"alertText": "최대 ",
					"alertText2": " characters allowed"
				},
				"groupRequired": {
					"regex": "none",
					"alertText": "You must fill one of the following fields",
					"alertTextCheckboxMultiple": "Please select an option",
					"alertTextCheckboxe": "This checkbox is required"
				},
				"min": {
					"regex": "none",
					"alertText": "Minimum value is "
				},
				"max": {
					"regex": "none",
					"alertText": "입력가능한 최대값을 넘었습니다.\n입력가능한 최대값 :"
				},
				"birth": {
					"regex": /^[\-\+]?(([0-9]{8}))?$/,
					"alertText": "생년월일이 잘못 입력되었습니다. <br>입력 예)19850101"
				},
				"integer": {
					"regex": /^[\-\+]?\d+$/,
					"alertText": "숫자만 입력 가능합니다."
				},
				"number": {
					// Number, including positive, negative, and floating decimal. credit: orefalo
					"regex": /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/,
					"alertText": "Invalid floating decimal number"
				},
				"userid": {
					"regex": "^[a-z]+[a-z0-9]{6,20}$",
					"alertText": "아이디는 영문자로 시작하는 6~20자 영문자 또는 숫자이어야 합니다."
				},
				"userpw": {
					"regex": "^[a-z0-9]{7,50}$",
					"alertText": "비밀번호는 최소 7자리 이상 입력하세요."
				},
				"userpw_": {
					"regex": "^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$",
					"alertText": "비밀번호는 비밀번호는 문자, 숫자, 특수문자의 조합으로 6~20자리로 입력하세요."
				}
			}

		}
	}

$.validationEngineLanguage.newLang();

})(jQuery);



var validation_option = {
	display:'alert',
	showOneMessage:true,
	validationEventTrigger:'submit',
	promptPosition:'centerRight',
	scroll:false
}