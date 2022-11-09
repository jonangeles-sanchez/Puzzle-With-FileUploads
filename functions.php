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

                sliceAndSave($img);
                uploadImg($img);
            }
        }
    }

    /**
     * Resizes image
     * Height and width should be multiples of 3
     * Longest side should be 612 pixels
     */
    function resizeImg($img){

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
     */
    function sliceAndSave($img){
        $imgMeta = getimagesize($img['tmp_name']);
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

    
?>