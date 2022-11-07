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


		$dest="./uploads";
		$max=1500000;//1.5M

	?>

  <title>CS 305 Examples - <?php echo $title; ?></title>

</head>

<body>
  <!-- enctype required to upload files -->





  <form method="post" enctype="multipart/form-data">

    <p>
      <label for="fileUpload1">Upload another file:</label>
      <!--for attribute important for screen readers... must match id of non-hiddent field-->

      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max; ?>">

      <input type="file" id="fileUpload1" name="file1"><!--name is only important if you need to directly access $_FILES array using this name-->

    </p>

    <p>
      <label for="fileUpload2">Upload another file:</label>
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max; ?>">
      <input type="file" id="fileUpload2" name="file2">
    </p>

    <p>
      <input type="submit" name="upload" value="Upload these files">
    </p>
  </form>

  <?php
  	if(!checkDir($dest)){
  		echo '<p>ERROR, cannot write to final destination or it does not exist</p>';
  	}
  	else{
      if(isset($_POST['upload'])){
  		echo "<pre>";
  		print_r($_FILES);
  		echo "</pre>";
  	}

      foreach($_FILES as $file){
        if($file['error']){
          echo 'Error uploading', $file['name'], '<br>';
        }
        else if(tooBig($file['size'])){
          echo 'Error uploading, file ', $file['name'], ' is too large<br>';
        }

        else{
          $fileParts=pathinfo($file['name']);
          //$fileParts=pathinfo("this/that/theother/file.png");
          echo "<pre>";
  	  print_r($fileParts);
  	  echo "</pre>";
  	
  	  $allowedFilesFlag=false;
  	  
  	  switch($fileParts['extension']){
  	  	case "jpg":
  	  	case "png":
  	  	case "jpeg":
  	  	case "gif":
  	  	case "webp":
  	  	case "tiff":
  	  	$allowedFilesFlag=true;
  	  	break;
  	   }
  	  
  	  if(!$allowedFilesFlag){
  	  	echo "Error - File type {$fileParts['extension']} not allowed<br>";
  	  }
  	  else{
  	  	$file['name']=str_replace(' ', '-', $file['name']);
  	  	$existing=scandir($dest);
  	  	echo "<pre>";
  	  	echo "***************************\n";
  	  	print_r($existing);
  	  	echo "***************************\n";
  	  	echo "</pre>";
  	  	
  	  	if(in_array($file['name'], $existing)){
  	  		echo "Error uploading, duplicate file name: {$file['name']}<br>";
  	  	}
  	  	else{
  	  		move_uploaded_file($file['tmp_name'], $dest.'/'.$file['name']);
  	  		echo "File '{$file['name']}' uploaded successfully<br>";
  	  	}
  	  }
        }
       }
      }

  ?>

</body>
</html>