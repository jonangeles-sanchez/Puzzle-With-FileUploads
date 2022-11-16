<?php
    /**
     * Function is called when user submits a imgMe$imgMetaData to upload
     * File is checked to ensure it meets standards to become a puzzle
     * Standard Criteria Includes:
     * Save destination
     * Save errors
     * Image type
     * Image size
     * 
     */
    function checkFileValidity($img){
        global $maxSize;
        global $location;

        $imgMetaData = pathinfo($img['name']);
        /*
            metadata includes: 
            -error
            -size
            -dirname
            -basename
            -extension
            -filename
        */
        $imgSizeData = getimagesize($img['tmp_name']);
        
        
        //Check image size is smaller than max image size
        $sizeError=checkImgSizeError($img['size'], $maxSize);

        $lengthError= checkSideLength($img);

        if($sizeError || $lengthError){
            if($sizeError == true)
                echo '<script type="text/JavaScript">alert("Error uploading, file too large, please choose file below 1.5MB");</script>';
            else if($lengthError)
                echo '<script type="text/JavaScript">alert("Error uploading, file side length too small please choose image with width or height greater than 612 pixels");</script>';
            return;
        } else {
            //Check if file type is valid
            $fileTypeError=checkFileTypeError($imgMetaData);
        }
        if (!$fileTypeError) {
            echo '<script type="text/JavaScript">alert("Error uploading, img type not allowed");</script>';
        } else {
            $img['name'] = str_replace(' ', '-', $img['name']);
            //Check if directory exists
            $existing = scandir($location);
            if (in_array($img['name'], $existing)) {
                echo "Error uploading, duplicate img \"{$img['name']}\"<br>";
            } else {
                //Upload image to file directory
                uploadImg($img);
                //Resize image
                resizeImg($img, $imgSizeData);
                //Slice the image into 9 pieces & upload to folder
                sliceAndSave($img, $imgSizeData);
                createBlank($img, $imgSizeData);
            }
        }
    }

    /**
     * Resizes image into a new image in uploads folder
     * Height and width should be multiples of 3
     * Longest side should be 612 pixels
     */
    function resizeImg($img, $imgSizeData){
        global $location;
        $imgMeta = $imgSizeData;
        //print_r($img);
        //print_r($imgMeta);
        $name = $img['name']; //"guideImage.jpg"
        echo $name;
        $origName = $name;
        $height = $imgMeta[1];
        $width = $imgMeta[0];
        $newHeight = $height;
        $newWidth = $width;
        $pixelResizeCount = 0;

        //echo "Original height: $height<br>";
        //echo "Original width: $width<br>";

        if($height > $width){
            $newHeight = 612;
            while($newWidth % 3 != 0){
                $newWidth -= 1;
                $pixelResizeCount++;
                if($pixelResizeCount == 2){
                    break;
                }
            }
        } else if($width > $height){
            $newWidth = 612;
            while($newHeight % 3 != 0){
                $newHeight -= 1;
                $pixelResizeCount++;
                if($pixelResizeCount == 2){
                    break;
                }
            }
        }
        //echo "New height: $newHeight<br>";
        //echo "New width: $newWidth<br>";

        $name=pathinfo($name, PATHINFO_FILENAME);

        $newName = $name . "_resized";

        //echo "Type: ".$imgMeta['mime']."\n\n";

        echo "Name: " . $origName;

        if($imgMeta['mime']=="image/jpeg"){
            $origImage=imagecreatefromjpeg($location."guideImage.jpg");
            echo "created jpeg object";
        } else if($imgMeta['mime']=="image/png"){
            $origImage=imagecreatefrompng($location.$origName);
            echo "created png object";
        } else if($imgMeta['mime']=="image/gif"){
            $origImage=imagecreatefromgif($location.$origName);
            echo "created gif object";
        } else {
            echo "ERROR"; //used for debugging
        }
        
        
        echo "Type: ". gettype($origImage); //used for debugging

        $newImage=imagecreatetruecolor($newWidth, $newHeight);


	    imagecopyresampled($newImage, $origImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        //imagecopy($newImage, $origImage, 0, 0, 0, 0, $newWidth, $newHeight);
        
	    if($imgMeta['mime']=="image/jpeg"){
	        $newName=$location.$newName.".jpg";	
	        $done=imagejpeg($newImage, $newName, 100);
	    } else if($imgMeta['mime']=="image/png"){
	        $newName=$location.$newName.".png";	
	        $done=imagepng($newImage, $newName, 0);
        } else if($imgMeta['mime']=="image/gif"){
	        $newName=$location.$newName.".gif";	
	        $done=imagegif($newImage, $newName);
	    }
        

    }

    /**
     * Moves uploaded image to uploads folder and renamed as "guideImage.jpg"
     * Might use php for this one
     */
    function uploadImg($img){
        global $location;
        move_uploaded_file($img['tmp_name'], $location . "guideImage.jpg");
        //move_uploaded_file($img['tmp_name'], $location . $img['name']);
        echo "File '{$img['name']}' uploaded successfully<br>";
    }

    /**
     * Slices image into 9 even images and move into uploads folder
     * Check file type - i think this only works for jpg
     */
    function sliceAndSave($img, $imgSizeData){
        global $location;
        $height = $imgSizeData[1];
        $width = $imgSizeData[0];
        $sliceHeight = $height / 3;
        $sliceWidth = $width / 3;
        $count = 0;
        
        //move_uploaded_file($img['tmp_name'], $location . "guideImage.jpg");
        //originalName = movefile tmp_name to uploadsDir; 
        $img = imagecreatefromjpeg($location . "guideImage.jpg");
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $slice = imagecreatetruecolor($sliceWidth, $sliceHeight);
                imagecopyresampled($slice, $img, 0, 0, $j * $sliceWidth, $i * $sliceHeight, $sliceWidth, $sliceHeight, $sliceWidth, $sliceHeight);
                imagejpeg($slice, "./uploads/tile$count.jpg");
                $count++;
            }
        }

    }
    
    /*
    function sliceAndSave($img){
        $imgMeta = getimagesize($img['tmp_name']);
        print_r($imgMeta);
        $height = $imgMeta[1];
        $width = $imgMeta[0];
        $sliceHeight = $height / 3;
        $sliceWidth = $width / 3;
        $count = 0;

        $img = imagecreatefromjpeg($img['tmp_name']);
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $slice = imagecreatetruecolor($sliceWidth, $sliceHeight);
                imagecopyresampled($slice, $img, 0, 0, $j * $sliceWidth, $i * $sliceHeight, $sliceWidth, $sliceHeight, $sliceWidth, $sliceHeight);
                imagejpeg($slice, "./uploads/tile$count.jpg");
                $count++;
            }
        }

    }
    */

    /**
     * Function checks for the criteria of image size.
     * Criteria: Image must be less than 1.5MB
     * If image is greater than maxSize, there's an error
     */
    function checkImgSizeError($imgSize, $maxSize){
        if ($imgSize > $maxSize) {
            return true;
        }
        return false;
    }

    /**
     * Function validates that the file uploaded
     * is of valid type. Valid type = jpg, png, gif.
     * T/F is returned depending on the case.
     */
    function checkFileTypeError($imgMetaData){
        switch ($imgMetaData['extension']) {
            case "jpg":
            case "png":
            case "gif":
                $err = true;
                break;
            default:
                $err = false;
        }
        return $err;
    }

    function checkSideLength($img){
        $imgMeta  = getimagesize($img['tmp_name']);
        $imgHeight = $imgMeta[1];
        $imgWidth  = $imgMeta[0];
        if($imgHeight > 612 || $imgWidth > 612){
            return false;
        }else{
            return true;
        }
  
    }
    

    /**
     * Creates a blank image that is filled with color sampled from the center of the uploaded image
     */
    function createBlank($img, $imgSizeData){
        global $location;
        $imgMeta = $imgSizeData;
        $name = "guideImage.jpg";
        $origName = $name;
        $height = $imgMeta[1];
        $width = $imgMeta[0];
        $newHeight = $height/2; //getting colr
        $newWidth = $width/2; //get clr

        //imagecolorat()

        
        if($imgMeta['mime']=="image/jpeg"){
            $origImage=imagecreatefromjpeg($location."guideImage.jpg");
            echo "created jpeg object";
        }else if($imgMeta['mime']=="image/png"){
            $origImage=imagecreatefrompng($location.$origName);
            echo "created png object";
        }else if($imgMeta['mime']=="image/gif"){
            $origImage=imagecreatefromgif($location.$origName);
            echo "created gif object";
        } else {
            echo "ERROR";
        }
        
        list($tileWidth, $tileHeight) = getimagesize($location."tile0.jpg");
        $newImage=imagecreatetruecolor($tileWidth, $tileHeight);
        
        $rgb = imagecolorat($origImage, $newWidth, $newHeight);
        $colors = imagecolorsforindex($origImage, $rgb);
        $red = $colors['red'];
        $green = $colors['green'];
        $blue = $colors['blue'];
        $alpha = $colors['alpha'];
        $color = imagecolorallocatealpha($newImage, $red, $green, $blue, $alpha);
        imagefill($newImage, 0, 0, $color);

        $newName = "blank";

        if($imgMeta['mime']=="image/jpeg"){
            $newName=$location.$newName.".jpg";
            $done=imagejpeg($newImage, $newName, 100);
        } else if($imgMeta['mime']=="image/png"){
            $newName=$location.$newName.".png";
            $done=imagepng($newImage, $newName, 0);
        } else if($imgMeta['mime']=="image/gif"){
            $newName=$location.$newName.".gif";
            $done=imagegif($newImage, $newName);
        }



    }

    function deleteImages($location){
        $imgScan = scandir($location);
        foreach($imgScan as $img){
            if($img[0] != "." || $img == "guideImage.jpg"){
                unlink($location.$img);
            }
        }
    }
    
?>