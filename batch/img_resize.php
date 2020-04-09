#!/usr/local/php/bin/php
<?php
$ftp_server = 'upload.cdn.cloudn.co.kr';
$ftp_user_name = 'commercelab_shinwonmall';
$ftp_user_pass = 'shinwonmall1@';



$connStr = "host=117.52.153.102 port=5432 dbname=shinwon user=shinwon password=shinwon@@0522";
$conn = pg_connect($connStr);

$sql = "insert into tblimage_conv select distinct substr(tag_style_no,0,10),'N' from tblproduct
where not exists (select null from tblimage_conv where substr(tag_style_no,0,10)=style)";
pg_query($conn,$sql);
$sql = "update tblimage_conv set conv='N' where style in (select distinct substr(tag_style_no,0,10) from tblproduct where extract(day from now() - modifydate)<1)";
pg_query($conn,$sql);

$result = pg_query($conn, "select style from tblimage_conv where conv='N' order by style limit 5000");
$arr = pg_fetch_all($result);
if(is_array($arr))
foreach($arr as $item) {
  // set up basic connection
  $conn_id = ftp_connect($ftp_server);
  // login with username and password
  $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
  // check connection
  if ((!$conn_id) || (!$login_result)) {
    die("FTP connection has failed !");
  }
  sleep(1);
  $style = $item['style'];
  echo($style."\r\n");
  $pr = substr($style,0,2);
  $ch = @ftp_chdir($conn_id, "./images/$pr/".$style);
  $r = true;
  if($ch) {
    // get contents of the current directory
    $contents = ftp_nlist($conn_id, ".");
    if(is_array($contents))
    foreach($contents as $file) {
      if(substr($file,0,2)!='M_' && strtoupper(substr($file,-4))=='.JPG') {
        echo($file."\r\n");
        $r = @ftp_get($conn_id,"/tmp/".$file,$file,FTP_BINARY);
        if(!$r) {
          $r = @ftp_get($conn_id,"/tmp/".$file,$file,FTP_BINARY);
          if(!$r) break;
        }

        $newwidth = 414;
        $newheight = 414;
        list($width, $height) = getimagesize("/tmp/".$file);
        if($width<4000 && $height<4000) {
          $src = imagecreatefromjpeg("/tmp/".$file);
          $dst = imagecreatetruecolor($newwidth, $newheight);
          imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
          imagejpeg($dst, "/tmp/".$file);
          $r = @ftp_put($conn_id,"M_".$file,"/tmp/".$file,FTP_BINARY);
          if(!$r) {
            $r = @ftp_put($conn_id,"M_".$file,"/tmp/".$file,FTP_BINARY);
            if(!$r) break;
          }
        }
        unlink("/tmp/".$file);
      }
    }
  }
  if($r)
  $result = pg_query($conn, "update tblimage_conv set conv='Y' where style='$style'");
  // close the connection
  ftp_close($conn_id);
}
pg_close();
