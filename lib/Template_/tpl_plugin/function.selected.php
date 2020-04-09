<?php
function selected($v,$v2,$selected='selected') {
    if($v==$v2) return $selected;
    else return '';
}