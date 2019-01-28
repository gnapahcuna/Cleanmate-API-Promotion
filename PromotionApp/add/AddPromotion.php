<?php

header("Content-type: text/plain; charset=utf-8");


  if($_SERVER["REQUEST_METHOD"]=="POST"){
    require '../config.php';
    createPro();

  }

  function createPro(){

    global $conn;

    $ConType = $_POST["ConType"];
    $ConPrice = $_POST["ConPrice"];
    $ConPiece = $_POST["ConPiece"];
    $ProductID = $_POST["ProductID"];
    $ServiceType = $_POST["ServiceType"];
    $CategoryID = $_POST["CategoryID"];

    $DisType = $_POST["DisType"];
    $DisPiece = $_POST["DisPiece"];
    $DisPriceType = $_POST["DisPriceType"];
    $DisPrice = $_POST["DisPrice"];
    $DisPer = $_POST["DisPer"];



    $stmt3 = "Select Max(PromotionID) AS proID from mas_promotion";
      $query = sqlsrv_query($conn, $stmt3);

      while($row = sqlsrv_fetch_array($query)) {
          echo "" . $row["proID"]. "";

    $stmt1 = "Insert into mas_promotionDetail(ConditionType,ConditionAmount,ConditionPrice,ProductID,ServiceType,CategoryID,PromotionID)
    values($ConType,$ConPiece,$ConPrice,$ProductID,$ServiceType,$CategoryID," . $row["proID"]. ");";
    }
    sqlsrv_query($conn, $stmt1);

    $stmt4 = "Select Max(PromotionDetailID) AS prodetailID from mas_promotionDetail";
      $query2 = sqlsrv_query($conn, $stmt4);

      while($row = sqlsrv_fetch_array($query2)) {
          echo "" . $row["prodetailID"]. "";


    $stmt2 = "Insert into mas_promotionDiscount(DiscountType,DiscountProductNo,DiscountRate,DiscountPriceType,DiscountPrice,PromotionDetailID)
    values($DisType,$DisPiece,$DisPer,$DisPriceType,$DisPrice," . $row["prodetailID"]. ");";
    }
    sqlsrv_query($conn, $stmt2);



    sqlsrv_close($conn);




}




 ?>
