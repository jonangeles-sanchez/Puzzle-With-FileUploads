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
  //$max=1;
  //phpinfo();
  ?>

  <title>CS 305 Examples - <?php echo $title; ?></title>

</head>

<body>
  <!-- enctype required to upload files -->
  <form method="post" enctype="multipart/form-data">

    <p>
      <label for="fileUpload1">Upload another file:</label>
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max;?>">
      <input type="file" id="fileUpload1" name="file1">
    </p>

    <p>
      <label for="fileUpload2">Upload another file:</label>
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max;?>">
      <input type="file" id="fileUpload2" name="file2">
    </p>

    <p>
      <input type="submit" name="upload" value="Upload these files">
    </p>
  </form>


  <?php
  try{
    if(!checkDir($dest)){
      throw new Exception('<p>ERROR, cannot write to final destination</p>');
    }
    if(isset($_POST['upload'])){
      echo "<pre>";
      print_r($_FILES);
      echo "</pre>";
    }

    foreach($_FILES as $file){
      //echo "Next: {$file['name']} {$file['error']}<br>";

      if($file['error']){

        switch($file['error']){

          //be specific about error
          case 1:
          throw new Exception ('Error uploading, file size exceeds server limits<br>');
          case 2:
          throw new Exception ('Error uploading, file size exceeds page-defined limits<br>');
          case 3:
          throw new Exception ('Error uploading, file was only partially uploaded<br>');
          case 4:
          throw new Exception ('Error uploading, no file specified for upload<br>');
          case 6:
          throw new Exception ('Error uploading, missing a temporary folder<br>');
          case 7:
          throw new Exception ('Error uploading, failed to write to disk<br>');
          case 8:
          throw new Exception ('Error uploading, file upload halted by php extension<br>');
        }
      }
      //else{
      $sizeCheck=tooBig($file['size'], $max);


      if($sizeCheck){
        throw new Exception ('Error uploading, size exceeds max allowed<br>');
      }
      //else{
      $fileParts = pathinfo($file['name']);
      //echo "Boot";
      echo "<pre>";
      print_r($fileParts);
      // dir field will always be . for uploaded files
      echo "</pre>";

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
        throw new Exception('Error uploading, file type not allowed<br>');
      }
      //else{
      $file['name']=str_replace(' ', '-', $file['name']);
      $existing=scandir($dest);
      if(in_array($file['name'], $existing)){
        throw new Exception('Error uploading, duplicate file name<br>');
      }
      //else{
      move_uploaded_file($file['tmp_name'], $dest.$file['name']);
      echo "File '{$file['name']}' uploaded successfully<br>";
    }
    //  }
    //}
    //}
    //}
  }
  catch(Exception $e){
    echo $e->getMessage(), "<br>";
  }

//  finally{ //happens no matter what
    //echo 'printing final parts';

  //  echo '</body></html>';

//  }

?>

</body>
</html>