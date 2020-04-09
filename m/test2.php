<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <title>jQuery Event</title>
  <style>
  body {
	margin: 20px;
	font-family: "맑은 고딕";
  }
  #image_preview {
    display:none;
  }
  </style>
  <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>

 </head>
 <body>
	<form>
    <p>
        <label for="image">Image:</label>
        <br />
        <input type="file" name="image" id="image" />
    </p>
	</form>
	<div id="image_preview" style="width:300px;height:300px">
		<img src="#" style="width:100%;height:100%"/>
		<br />
		<a href="#">Remove</a>
	</div>


	<script type="text/javascript">
	
$(document).ready(function(){
	/** 
	onchange event handler for the file input field.
	It emplements very basic validation using the file extension.
	If the filename passes validation it will show the image using it's blob URL and will hide the input field and show a delete button to allow the user to remove the image
	*/
    if (!('url' in window) && ('webkitURL' in window)) {
        window.URL = window.webkitURL;
    }

	$('#image').on('change', function(e) {
		
		ext = $(this).val().split('.').pop().toLowerCase(); //확장자
		
		//배열에 추출한 확장자가 존재하는지 체크
		if($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
			resetFormElement($(this)); //폼 초기화
			window.alert('이미지 파일이 아닙니다! (gif, png, jpg, jpeg 만 업로드 가능)');
		} else {
			blobURL = URL.createObjectURL(e.target.files[0]);
			$('#image_preview img').attr('src', blobURL);
			$('#image_preview').slideDown(); //업로드한 이미지 미리보기 
			$(this).slideUp(); //파일 양식 감춤
		}
	});

	/**
	onclick event handler for the delete button.
	It removes the image, clears and unhides the file input field.
	*/
	$('#image_preview a').bind('click', function() {
		resetFormElement($('#image'));
		$('#image').slideDown(); //파일 양식 보여줌
		$(this).parent().slideUp(); //미리 보기 영역 감춤
		return false; //기본 이벤트 막지
	});
});

	/** 
	* 폼요소 초기화 
	* Reset form element
	* 
	* @param e jQuery object
	*/
	function resetFormElement(e) {
		e.wrap('<form>').closest('form').get(0).reset(); 
		//리셋하려는 폼양식 요소를 폼(<form>) 으로 감싸고 (wrap()) , 
		//감싼 폼 ( closest('form')) 에서 Dom요소를 반환받고 ( get(0) ),
		//DOM에서 제공하는 초기화하는 메서드 reset()을 호출
		e.unwrap(); //감싼 <form> 태그를 제거
	}
	</script>

 </body>
</html>