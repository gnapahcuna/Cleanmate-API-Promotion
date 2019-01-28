<?php

header("Content-type: text/plain; charset=utf-8");


  if($_SERVER["REQUEST_METHOD"]=="POST"){
    require '../config.php';
    createPro();

  }

  function createPro(){

    global $conn;

    $BranchGroupID = $_POST["BranchGroupID"];


        $stmt3 = "Select Max(PromotionID) AS proID from mas_promotion";
          $query3 = sqlsrv_query($conn, $stmt3);

          while($row2 = sqlsrv_fetch_array($query3)) {
              echo "" . $row2["proID"]. "";

              $stmt = "Insert into mas_promotionBranch(BranchGroupID,PromotionID)
              values($BranchGroupID," . $row2["proID"]. ");";






    }


    sqlsrv_query($conn, $stmt);
    sqlsrv_close($conn);


}






 ?>
