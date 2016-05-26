<?php
header ("Content-Type:text/html; charset=UTF-8", false);
?>

<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
@font-face {font-family:"C3216tbU";src: url("fonts/C3216tbU.ttf");}
body{
    color:#fffbfb;  font-weight: bold;
}
input{
	font-weight: bold;
	font-size: 22px;
	margin-left: 33px;
}
</style>
<script src="scripts/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/imgareaselect-animated.css">
	<script type="text/javascript" src="scripts/jquery.imgareaselect.js"></script>
<script src="scripts/script.js"></script>
</head>
<body style="padding: 0; margin: 0; background: #3a3c39;">

<div class="main" style="padding: 33px;">

<h1 style="font-family: C3216tbU;text-align: center;">QUICK PHOTOSHOP <a href="../quick_photoshop.rar"><img src="img/1432304683.jpg"> </a></h1>
<span style="color: #769c75; font-weight: bold; font-size: 21px;">
/*<br />
* @Copyright = OpenSourse<br />
 * Значит, если не передаётся параметр обрезания, то изменяется только ширина картинки<br>
 * Ecть возможность изменить размеры с  дополнением картинки выбранным цветом<br>
 * Допускается комбинация с рамкой, яркостью и контрастом<br>
* Также можно выделить и сохранить нужный участок картинки<br>
* Максимальные размеры - 1280х960<br>
*/
</span>
<form id="my_form" method='post' action='worker.php' enctype='multipart/form-data'>

<table>    
<tr>
    <td>jpg, png или gif файл. Суровая точка :)</td>
    <td><input type='file' name='image'  multiple='true' /></td>
</tr> 
<tr>
    <td></td>
    <td><input  type="hidden"  name="papka"   value="windows_images"/></td>
</tr>
<tr>
    <td>Ширина</td>
    <td><input type="text" name="width" value="1024"/></td>
</tr>
<tr>
    <td> Высота</td>
    <td><input type="text" name="height" value="768"/></td>
</tr>
<tr>
    <td>1 - обрежет справа, 2 - слева, 3 - по центру</td>
    <td><input type="text" name="rlc" value=""/></td>
</tr>
<tr style="display: none;">
    <td>Дополнение картинки выбранным цветом, любая цифра</td>
    <td><input type="text" name="thumbnail" value=""/></td>
</tr>  
<tr style="display: none;">
    <td>Выберите цвет, по умолчанию белый цвет</td>
    <td><input type="text" name="color" value="0xFFFFFF"/></td>
</tr>  
<tr>
    <td>Сделать рамку, размеры рамки, max 100</td>
    <td><input type="text" name="size_bord" value=""/></td>
</tr>
<tr>
    <td>Цвет рамки, по умолчанию чёрный</td>
    <td><input type="text" name="color_bord" value="0, 0, 0"/></td>
</tr>
<tr>
    <td>Изменить яркость</td>
    <td><input type="text" name="brightness" value=""/></td>
</tr>
<tr>
    <td>Повысить контраст</td>
    <td><input type="text" name="contrast" value=""/></td>
</tr>
<tr>
    <td><input type='submit' id='buttupload' value='Вперёд!' style="background: #359216; border: 5px solid blue; border-radius: 11px; margin-left: 55px;" /></td>
    <td></td>
</tr>
 </table>
</form>
<br />
<div id="imgEdit" style="text-align: center;">

				<img src="img/1432389016.jpg" id="thumbnail" />
                
				<br style="clear:both;"/>
				<form name="thumbnail">
					<input type="hidden" name="source" value="img/1432389016.jpg" id="source" />
					<input type="hidden" name="x1" value="" id="x1" />
					<input type="hidden" name="y1" value="" id="y1" />
					<input type="hidden" name="x2" value="" id="x2" />
					<input type="hidden" name="y2" value="" id="y2" />
					<input type="hidden" name="w" value="" id="w" />
					<input type="hidden" name="h" value="" id="h" />
				</form>
				<br />
                <span id="respa"></span>
                <span style="color: #769c75; font-weight: bold; font-size: 21px;">Bыделяем участок</span><br>
				<span id="butSave" style="cursor: pointer; width: 100px; text-align: center; border: 5px solid green; border-radius: 11px; font-weight: bold; font-size: 27px; background: white; color: #1b2150;">Сохранить</span><br /><br>
               <!-- <span id="redact" style="font-size: 33px; display: none;">
                <img style="height: 33px;" src="img/contrast.jpg"/>Контраст<br />
                <form id="contrast">
                    <input name="contrast" type="text" style="width: 33px;"/>
                </form>
                </span>-->
                <span id="response"></span>
				
			</div>

<div id='files' style="padding: 11px; text-align: center;">



</div>
</div>

<script>
    window.onbeforeunload = function() {
      $.ajax({
          type : 'POST',
          url : 'clear.php'
      });
};
</script>
</body>
</html>
  
	
  