<?php

if($argc!=3){
	echo 'USAGE: imageScaler.php filename percentage\n';
}

$name=$argv[1];
$origName=$name;
$percentage=$argv[2];

try{
	$imageInfo=getimagesize($name);
	
	if($imageInfo===false){
		throw new Exception("Error getting info about image");
	}
	
	print_r($imageInfo);
	
	$origWidth=$imageInfo[0];
	$origHeight=$imageInfo[1];
	
	$newWidth=round($origWidth*($percentage/100));
	$newHeight=round($origHeight*($percentage/100));
	
	echo $newWidth . " x " . $newHeight . "\n\n";
	
	$name=pathinfo($name, PATHINFO_FILENAME);
	
	echo $name . "\n\n";
	
	$newName=$name."_resized";
	
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
	
	if($imageInfo['mime']=="image/jpeg"){
	$newName=$newName.".jpg";	
	$done=imagejpeg($newImage, $newName, 100);
	}
	else if($imageInfo['mime']=="image/png"){
	$newName=$newName.".png";	
	$done=imagepng($newImage, $newName, 0);
	}
	else if($imageInfo['mime']=="image/gif"){
	$newName=$newName.".gif";	
	$done=imagegif($newImage, $newName);
	}
}
catch(Exception $e){
	echo "ERROR:" . $e->getMessage() . "\n";
}