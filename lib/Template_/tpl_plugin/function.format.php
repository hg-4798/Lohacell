<?
function format($v, $format) {
	switch($format) {
		case 'mobile':
			$text = str_replace('-','',$v);
			$rtn = preg_replace("/(0(?:2|[0-9]{2}))([0-9]+)([0-9]{4}$)/", "\\1-\\2-\\3", $v);
			break;
		case 'biz':
			$text = str_replace('-','',$v);
			$rtn = preg_replace("/([0-9]{3})([0-9]{2})([0-9]{5}$)/", "\\1-\\2-\\3", $v);
			break;
		case 'price':
			if(is_numeric($v)) $rtn = number_format($v);
			else $rtn = '';
			break;
		case 'id':
			$rtn = mb_substr($v ,0,-3,"UTF-8")."***";
			break;
		case 'today':
			list($date, $time) = exlode(' ',$v);
			if($date == date('Y-m-d')) {
				$rtn = $time;
			}
			else $rtn = $date;
			break;
		default:
			if(substr($v,0,19) == '1970-01-01 00:00:00' || !$v) $rtn = '-';
			else $rtn = date($format, strtotime($v));
			break;
	}
	return $rtn;
}
?>

