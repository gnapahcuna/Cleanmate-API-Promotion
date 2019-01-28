<?php

header("Content-type: text/plain; charset=utf-8");


  if($_SERVER["REQUEST_METHOD"]=="POST"){
    require '../config.php';
    createPro();

  }

  function createPro(){

    global $conn;

    $Proname = $_POST["Proname"];
    $IsActive = $_POST["IsActive"];
    $StartDate = $_POST["StartDate"];
    $StopDate = $_POST["StopDate"];
    $User = $_POST["User"];


    date_default_timezone_set("Asia/Bangkok");
    $date = date("Y-m-d H:i:s");




    $stmt = "Insert into mas_promotion(PromotionName,IsActive,EffectiveDate,ExpirationDate,CreatedDate,CreatedBy)
    values('$Proname','$IsActive','$StartDate','$StopDate','$date','$User');";

    sqlsrv_query($conn, $stmt);


    sqlsrv_close($conn);




}




 ?>
