<?php
if(isset($_POST['image'])){
    $img = preg_replace("/[\<\>`,\[\]]/", 's', $_POST['image']);
    $x      = intval($_POST['x1']);
    $y      = intval($_POST['y1']);
    $crop_width = intval($_POST['w']);
    $crop_height = intval($_POST['h']);
    $dir = 'crop_images';
    if (!file_exists ($dir)) mkdir($dir, 0755, true);
    
    list($width, $height, $type)=getimagesize($img);
    $typeok = true;
    switch($type)
    {
      case IMAGETYPE_GIF:   $src = imagecreatefromgif($img); break;
      case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($img); break;
      case IMAGETYPE_PNG:   $src = imagecreatefrompng($img); break;
      default:            $typeok = FALSE; break;
    }
    if($typeok){
      $im = imagecreatetruecolor($crop_width, $crop_height);
      imagecopy($im , // выходное
				  $src , // исходное
				   0,  // координата х выходного
				    0 , // координата у выходного
				     $x ,  // х исходного
				      $y , // у исходного
				       $crop_width ,
				        $crop_height );
                        
                        switch($type)
    {
      case IMAGETYPE_GIF: $path = $dir.'/'.time().'.gif';
      $src = imagegif($im, $path); break;
      case IMAGETYPE_JPEG:  $path = $dir.'/'.time().'.jpeg';
      $src = imagejpeg($im, $path); break;
      case IMAGETYPE_PNG:  $path = $dir.'/'.time().'.png';
      $src = imagepng($im, $path); break;
      default:  break;
    }
    echo "<img src='$path'/>";
  }
}