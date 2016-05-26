<?php

       ############ПОЕХАЛИ#############
  
    error_reporting(E_ALL);// включаем вывод всех ошибок:
    mb_internal_encoding('utf-8');// устанавливаем внутреннюю кодировку скрипта 

  // если файлы  и пути  пришли:
   if (isset($_FILES['image']['name'])){
      //if ($_FILES['image']['size'] > 5000000) exit('()');
      $papka = 'windows_images';
	  if (!file_exists ($papka)) mkdir($papka, 0755, true);
      
             $width = intval($_POST['width']);
             $height = intval($_POST['height']);
             $rlc = intval($_POST['rlc']);
             $thumbnail = intval($_POST['thumbnail']);
             $size_bord = intval($_POST['size_bord']);
             $color_bord=trim($_POST['color_bord']);
             $rgb = explode(',', $color_bord);
             $color =  $_POST['color'];
             $contrast = $_POST['contrast'];
             $brightness = $_POST['brightness'];
             if(!$contrast = - intval($_POST['contrast'])){
                 $contrast = 0;
             }
             if(!$brightness =  intval($_POST['brightness'])){
                 $brightness = 0;
             }
             
             if(strlen($rlc)>1 ||$width > 1280 || $height > 960 || strlen($thumbnail) >1 ||  strlen($color_bord)>17 || abs($contrast) > 100 || abs($brightness) >100){
                 exit;            
             }
             if(!preg_match('/^0x[a-z0-9]{6}/i', $color)){
                 $color = '#FFFFFF';
             }
  	 	
	  	// получаем имя файла без пути:
	        $name=basename($_FILES['image']['name']);
	        $needle="/[\W]/";
	        $name=preg_replace($needle, '.' , $name);
	        if(mb_strlen($name)>77){
				exit;
			}
	          // получаем расширение файла:
	        $ext = strtolower(mb_substr ($name, mb_strrpos($name, '.')+1));
	        $filetypes = array('jpg','png','gif','jpeg');
	        // если расширения совпадают
	        if (in_array($ext, $filetypes)){
                     deleteAllFiles($papka);
                  if($rlc){
                      $saveto = $papka.'/'.time().'.'.$ext;
                      resize_crop($_FILES['image']['tmp_name'], $saveto, $width, $height, $rlc);
                  }
                  elseif($thumbnail){
                      $saveto = $papka.'/'.time().'.'.$ext;
                      img_resize($_FILES['image']['tmp_name'], $saveto, $width,  $height, $color);
                  }
                  else{
                      $saveto = $papka.'/'.time().'.'.$ext;
                      resize($_FILES['image']['tmp_name'], $ext, $width,  $saveto);
                  }
                      if($size_bord){
                              set_border($saveto, $saveto, $ext, $size_bord, intval($rgb[0]), intval($rgb[1]), intval($rgb[2]));
                          }
        	     if($contrast){
                     set_contrast_brightness($saveto, $saveto, $ext, IMG_FILTER_CONTRAST, $contrast);
                 }
                 if($brightness){
                     set_contrast_brightness($saveto, $saveto, $ext, IMG_FILTER_BRIGHTNESS, $brightness);
                 }
                     
                                echo $saveto;
  
      }
    }

function set_contrast_brightness($src, $dest, $ext, $filter, $contr_bright){
    if(abs($contr_bright>100)) $contr_bright = 0;
    $typeok = true;
        switch($ext)
            {
              case "gif":   $src_ = imagecreatefromgif($src); break;
              case "jpeg":  
              case "jpg": $src_ = imagecreatefromjpeg($src); break;
              case "png":   $src_ = imagecreatefrompng($src); break;
              default:            $typeok = FALSE; break;
            }
            if($typeok){
                imagefilter ($src_ , // ресурс изображения
				           $filter,    // тип фильтра
					        $contr_bright  );
            }
            
            switch ($ext) {
    case 'gif':
        $source_gdim = imagegif($src_, $dest);
        break;
    case 'jpeg':
    case 'jpg':
        $source_gdim = imagejpeg($src_, $dest);
        break;
    case 'png':
        $source_gdim = imagepng($src_, $dest);
        break;
}
  imagedestroy($src_);
  return TRUE;
}

// РАМКА
function set_border($src, $dest, $ext, $size=5, $r=0, $g=0, $b=0){
 if($size>100)  $size = 0;
list($width, $height) = getimagesize($src);
$typeok = true;
switch($ext)
    {
      case "gif":   $src_ = imagecreatefromgif($src); break;
      case "jpeg":  
      case "jpg": $src_ = imagecreatefromjpeg($src); break;
      case "png":   $src_ = imagecreatefrompng($src); break;
      default:            $typeok = FALSE; break;
    }
    if($typeok){
        
    }
$im = imagecreatetruecolor($width+$size*2, $height+$size*2);
$red = imagecolorallocate($im, $r, $g, $b);
imagefill($im, 0, 0, $red);
 imagecopy($im , // выходное
				  $src_ , // исходное
				   $size,  // координата х выходного
				    $size , // координата у выходного
				     0 ,  // х исходного
				      0 , // у исходного
				       $width ,
				        $height );
                        
switch ($ext) {
    case 'gif':
        $source_gdim = imagegif($im, $dest);
        break;
    case 'jpeg':
    case 'jpg':
        $source_gdim = imagejpeg($im, $dest);
        break;
    case 'png':
        $source_gdim = imagepng($im, $dest);
        break;
}
imagedestroy($im);
imagedestroy($src_);
    
}

function img_resize($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100)
{
  if (!file_exists($src)) return false;
 
  $size = getimagesize($src);
 
  if ($size === false) return false;
 
  // Определяем исходный формат по MIME-информации, предоставленной
  // функцией getimagesize, и выбираем соответствующую формату
  // imagecreatefrom-функцию.
  $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
  $icfunc = "imagecreatefrom" . $format;
  if (!function_exists($icfunc)) return false;
 
  $x_ratio = $width / $size[0];
  $y_ratio = $height / $size[1];
 
  $ratio       = min($x_ratio, $y_ratio);
  $use_x_ratio = ($x_ratio == $ratio);
 
  $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
  $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
  $new_left    = $use_x_ratio  ? 0 : floor(($width - $new_width) / 2);
  $new_top     = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);
 
  $isrc = $icfunc($src);
  $idest = imagecreatetruecolor($width, $height);
 
  imagefill($idest, 0, 0, $rgb);
  imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0,
    $new_width, $new_height, $size[0], $size[1]);
 
  imagejpeg($idest, $dest, $quality);
 
  imagedestroy($isrc);
  imagedestroy($idest);
 
  return true;
 
}

function resize($uploadfile, $ext, $source_width, $output){
    $typeok = true;
	switch($ext)
    {
      case "gif":   $src = imagecreatefromgif($uploadfile); break;
      case "jpeg":  
      case "jpg": $src = imagecreatefromjpeg($uploadfile); break;
      case "png":   $src = imagecreatefrompng($uploadfile); break;
      default:            $typeok = FALSE; break;
    }
      if ($typeok){
      list($width, $height) = getimagesize($uploadfile);

      $max = $source_width;
      $output_width = $width;
      $output_height  = $height;
      
      $output_width= $max;      
      $output_height = $max / $width * $height;

      $tmp = imagecreatetruecolor($output_width, $output_height);
      imagecopyresampled($tmp, $src, 0, 0, 0, 0, $output_width, $output_height, $width, $height);
      imageconvolution($tmp, array(array(-1, -1, -1),
      array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
      imagejpeg($tmp,$output);
      imagedestroy($tmp);
      imagedestroy($src);
       }
       return true;
}

function  resize_crop($source_path, $output_path, $source_w, $source_h, $rlc=''){

list($source_width, $source_height, $source_type) = getimagesize($source_path);

switch ($source_type) {
    case IMAGETYPE_GIF:
        $source_gdim = imagecreatefromgif($source_path);
        break;
    case IMAGETYPE_JPEG:
        $source_gdim = imagecreatefromjpeg($source_path);
        break;
    case IMAGETYPE_PNG:
        $source_gdim = imagecreatefrompng($source_path);
        break;
}

$source_aspect_ratio = $source_width / $source_height;
$desired_aspect_ratio = $source_w / $source_h;

if ($source_aspect_ratio > $desired_aspect_ratio) {
  
    $temp_height = $source_h;
    $temp_width = ( int ) ($source_h * $source_aspect_ratio);
} else {
  
    $temp_width = $source_w;
    $temp_height = ( int ) ($source_w / $source_aspect_ratio);
}

$temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
imagecopyresampled(
    $temp_gdim,
    $source_gdim,
    0, 0,
    0, 0,
    $temp_width, $temp_height,
    $source_width, $source_height
);
if($rlc == 1){
    $x0=0;
$y0 = ($temp_height - $source_h) / 2;
}
elseif($rlc==2){
    $x0 = $temp_width - $source_w;
$y0 = ($temp_height - $source_h) / 2;
}
elseif ($rlc == 3){
    $x0 = ($temp_width - $source_w) / 2;
$y0 = ($temp_height - $source_h) / 2;    
}

$desired_gdim = imagecreatetruecolor($source_w, $source_h);
imagecopy(
    $desired_gdim,
    $temp_gdim,
    0, 0,
    $x0, $y0,
    $source_w, $source_h
);

switch ($source_type) {
    case IMAGETYPE_GIF:
        $source_gdim = imagegif($desired_gdim, $output_path);
        break;
    case IMAGETYPE_JPEG:
        $source_gdim = imagejpeg($desired_gdim, $output_path);
        break;
    case IMAGETYPE_PNG:
        $source_gdim = imagepng($desired_gdim, $output_path);
        break;
}
return true;
}

function deleteAllFiles($dir){
    		$list = glob($dir."/*");
    		for ($i=0; $i < count($list); $i++){			
    		if (is_dir($list[$i])) deleteAllFiles ($list[$i]);
    		else unlink($list[$i]);
    		}
            }
            
            ?>