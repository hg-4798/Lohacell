<?php

function getProductImage( $img_path, $img_name, $mobile_flag = null ) {
    global $isMobile;

    if(strpos($img_name, "http://") === false) {
        if(strlen($img_name)!=0 && file_exists($img_path.$img_name)){
            $img_name = $img_path.$img_name;
            if ( strpos($img_path,'/product/') !== false) {
                // 이미지 정보를 변경했을때 바로 변경된 이미지를 가져옴 (상품이미지인 경우에만)
                $img_name .= '?v='.date('YmdHi'); 
            }
        } else { 
            if ( $isMobile || ( $mobile_flag != null && $mobile_flag ) ) {
                $img_name = "../images/common/noimage_m.gif"; 
            } else {
                $img_name = "../images/common/noimage.gif"; 
            }
        }
    }

    return $img_name;
}

$Dir = "../";

$s = getProductImage($Dir.'data/shopimages/mainbanner/', "a2beb860a0b4600b01b3816b29c240981.jpg");
echo $s;
$s = getProductImage($Dir.'data/shopimages/product/', "001002007001000003/001002007001000003_thum2_500X500.jpg");
echo $s;


?>
