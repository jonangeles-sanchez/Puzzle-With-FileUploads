<?php

if($argc!=3){
	echo 'USAGE: thumbNailer.php filename dimension\n';
}

$name=$argv[1];
$origName=$name;
$dimension=$argv[2];
$newWidth=0;
$newHeight=0;

try{
	$imageInfo=getimagesize($name);
	
	if($imageInfo===false){
		throw new Exception("Error getting info about image");
	}
	
	print_r($imageInfo);
	
	$origWidth=$imageInfo[0];
	$origHeight=$imageInfo[1];
	
	if($origHeight<$dimension){
		throw new Exception("Dimension specified is taller than original height");
	}
	
	if($origWidth<$dimension){
		throw new Exception("Dimension specified is wider than original width");
	}
	
	if($origWidth>$origHeight){
		$ratio=$dimension/$origHeight;
		$newHeight=round($dimension);
		$newWidth=round($ratio*$origWidth);
	}
	else{
		$ratio=$dimension/$origWidth;
		$newWidth=round($dimension);
		$newHeight=round($ratio*$origHeight);
	}
	
	echo $newWidth . " x " . $newHeight . "\n\n";

	$name=pathinfo($name, PATHINFO_FILENAME);
	
	echo $name . "\n\n";
	
	$newName=$name."_thumb";
	
	echo $newName . "\n\n";
	
	if($imageInfo['mime']=="image/jpeg"){
		$origImage=imagecreatefromjpeg($origName);
	}	
	else if($imageInfo['mime']=="image/png"){
		$origImage=imagecreatefrompng($origName);
	}
	else if($imageInfo['mime']=="image/gif"){
		$origImage=imagecreatefromgif($origName);
	}
	
	$newImage=imagecreatetruecolor($newWidth, $newHeight);
	
	imagecopyresampled($newImage, $origImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
	
	$thumb=imagecreatetruecolor($dimension, $dimension);
	
	$x=$newWidth/2 - $dimension/2;
	$y=$newHeight/2 - $dimension/2;
	
	imagecopyresampled($thumb, $newImage, 0, 0, $x, $y, $dimension, $dimension, $dimension, $dimension);
	
	if($imageInfo['mime']=="image/jpeg"){
	$newName=$newName.".jpg";	
	$done=imagejpeg($thumb, $newName, 100);
	}
	else if($imageInfo['mime']=="image/png"){
	$newName=$newName.".png";	
	$done=imagepng($thumb, $newName, 0);
	}
	else if($imageInfo['mime']=="image/gif"){
	$newName=$newName.".gif";	
	$done=imagegif($thumb, $newName);
	}
	
	imagedestroy($newImage);
	imagedestroy($thumb);
	
}
catch(Exception $e){
	echo "ERROR:" . $e->getMessage() . "\n";
}