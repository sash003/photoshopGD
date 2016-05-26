<?php

deleteAllFiles('windows_images');
deleteAllFiles('crop_images');

function deleteAllFiles($dir){
	$list = glob($dir."/*");
	for ($i=0; $i < count($list); $i++){			
	if (is_dir($list[$i])) deleteAllFiles ($list[$i]);
	else unlink($list[$i]);
	}
}