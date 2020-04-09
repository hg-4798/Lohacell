<?php
function checked($v,$v2,$checked='checked') {
	if($v==$v2) return $checked;
	else return '';
}