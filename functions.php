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
        global $max;
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
        
        //Check image size is smaller than max image size
        $sizeError=checkImgSizeError($img['size'], $max);

        if($sizeError){
          echo "Error uploading, img \"{$img['name']}\" has a size that
          exceeds the max allowed<br>";
        } else {
            //Check if file type is valid
            $fileTypeError=checkFileTypeError($imgMetaData);
        }
        if (!$fileTypeError) {
            echo "Error uploading, img $imgMetaData type not allowed<br>";
        } else {
            $img['name'] = str_replace(' ', '-', $img['name']);
            //Check if directory exists
            $existing = scandir($location);
            if (in_array($img['name'], $existing)) {
                echo "Error uploading, duplicate img \"{$img['name']}\"<br>";
            } else {
                //Upload image to file directory
                move_uploaded_file($img['tmp_name'], $location . $img['name']);
                echo "File '{$img['name']}' uploaded successfully<br>";
            }
        }
    }

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

	/**
	*Function checks if sides are both >= 612 pixels
	*Check Width and Check Height
	*@return error if image < 612
	*/
	function checkImageSideLength($imgMetaData){

 		$width = $exif["COMPUTED"]["Width"];
		$height = $exif["COMPUTED"]["Height"];

		if($height >= 612 || $width >= 612){
			$err = true;
			return $err;
		}else{
			$err = false;
			return $err;
		}

	/*
	if (height < 612) check if (width < 612)
	if height is less but width is great return 0;
	if height is greater, return 0
	if height is less and width is less, return error
	*/

	}
?>
