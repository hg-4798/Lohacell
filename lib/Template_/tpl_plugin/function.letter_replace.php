<?
function letter_replace($str, $start=0, $length=2, $replace_str='*') {
		$str_length = mb_strlen($str, "UTF8");
		if($str_length<=$length) return $str;

		$str_new = '';
		if($start>0) $str_new.=str_repeat($replace_str, $start);
		$str_new.=mb_substr($str, $start, $length, "UTF8");
		$str_new.=str_repeat($replace_str, $str_length-($start+$length));
		return $str_new;
	}
?>
