<?php
function preview($path, $placeholder="") {

	switch($placeholder) {
		case '306':
		case '550':
		case '1200':
		case 'list':
		case 'square':
			$placeholder = "/admin/images/product/noimg.jpg";
			// $placeholder="/admin/images/common/noimg_{$placeholder}.jpg";
			break;
		case 'default':
		case 'logo_gray':
			$placeholder = "/admin/images/product/noimg.jpg";
			break;
		case 'blank':
			$placeholder="/admin/images/space01.gif";
			break;
		default:
			$placeholder = "/admin/images/product/noimg.jpg";
			break;
	}
	
	
	if(!$path) return $placeholder;
	if(strpos_array($path, array("http://","https://"))!==false) {
		return $path;
	}
	else {
		list($path_file, $ver) = explode('?',$path);
		if(is_file($_SERVER['DOCUMENT_ROOT'].$path_file)){ //로컬이미지체크
			return $path;
		}
		else {
			return $placeholder;
		}
	}
}
?>
