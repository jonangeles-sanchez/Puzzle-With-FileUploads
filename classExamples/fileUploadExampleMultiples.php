<!DOCTYPE html>
<html lang="en-US">

<head>

  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="styles.css">

  <?php
  require_once "fileUploadExampleFunctions.php";
  $title=basename($_SERVER['PHP_SELF'], '.php');
  $title=str_replace('-', ' ', $title);
  $title=ucwords($title);

  $dest="./uploads/";
  $max=1572864; //1.5M
  //$max=1;//this is only partially useful

  //phpinfo();
  ?>

  <title>CS 305 Examples - <?php echo $title; ?></title>

</head>

<body>
  <!-- "enctype" attribute required to upload files -->
  <form method="post" enctype="multipart/form-data">

    <p>
      <label for="fileUpload">Upload a file:</label>
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max;?>">
      <input type="file" id =fileUpload1 name="fileUpload1[]" multiple>
    </p>
    <p>
      <input type="submit" name="upload" value="Upload these files">
    </p>
  </form>


  <?php

  if(!checkDir($dest)){
    echo "<p>ERROR, cannot write to final destination</p>";
    echo "</body></html>";
    exit();
  }

  if(isset($_POST['upload'])){
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
  }

  foreach($_FILES as $file){

    for($i=0;$i<count($file['name']);$i++){
      echo $file['name'][$i], "<br>";

      if($file['error'][$i]){
        echo "Error uploading file {$file['name'][$i]}<br>";
      }

      else{

        $sizeCheck=tooBig($file['size'][$i], $max);


        if($sizeCheck){
          echo "Error uploading, file \"{$file['name'][$i]}\" has a size that
          exceeds the max allowed<br>";
        }
        else{
          $fileParts = pathinfo($file['name'][$i]);
          $allowedFilesFlag=1;

          switch($fileParts['extension']){
            case "exe":
            case "bin":
            case "cgi":
            case "js":
            case "pl":
            case "php":
            case "py":
            case "sh":
            $allowedFilesFlag=0;
            break;
          }

          if(!$allowedFilesFlag){
            echo "Error uploading, file type not allowed<br>";
          }
          else{
            $file['name'][$i]=str_replace(' ', '-', $file['name'][$i]);
            $existing=scandir($dest);
            if(in_array($file['name'][$i], $existing)){
              echo "Error uploading, duplicate file name<br>";
            }
            else{
              move_uploaded_file($file['tmp_name'][$i], $dest.$file['name'][$i]);
              echo "File '{$file['name'][$i]}' uploaded successfully<br>";
            }
          }
        }
      }
    }
  }
?>

</body>

</html>