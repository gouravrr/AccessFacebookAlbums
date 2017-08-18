<?php
header("Content-type: application/json; charset=utf-8");

//generate random string for password
function generateRandomString($length = 9) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

if(isset($_POST, $_POST['ImgsArray'])){
    $Imgsss = $_POST['ImgsArray'];
    $Imgss = explode(', ', $Imgsss);
    if(!empty($Imgss)){
      foreach ($Imgss as $Imgs) {
        if(!empty($Imgs)){
          $FileName = generateRandomString().'.jpg';
          $contents= file_get_contents($Imgs);
          $savefile = fopen($FileName, 'w');
          fwrite($savefile, $contents);
          fclose($savefile);
        }
      }
    }
    exit($_POST['ImgsArray']);
}else{
    exit("INVALID REQUEST.");
}
?>