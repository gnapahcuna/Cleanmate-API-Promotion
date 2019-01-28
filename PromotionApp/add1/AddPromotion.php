<?php
header("Content-type: text/plain; charset=utf-8");
$json = file_get_contents("php://input");
$_POST = json_decode($json, true);

foreach($_POST as $object) {
	list($prop1,  $DisType) = each($object);
	list($prop2,  $DisPiece) = each($object);
	list($prop3,  $DisPriceType) = each($object);
	list($prop4,  $DisPrice) = each($object);
	list($prop5,  $DisPer) = each($object);
	list($prop6,  $ConType) = each($object);
	list($prop7,  $ConPiece) = each($object);
	list($prop8,  $ConPrice) = each($object);
	list($prop9,  $ProductID) = each($object);
	list($prop10, $ServiceType) = each($object);
	list($prop11, $CategoryID) = each($object);
	
	$detail1="";
	if($ConType=='NULL'){
		$detail1=NULL;
	}else{
		$detail1=$ConType;
	}
	
	include("../config.php");
	$stmt_proID = "Select Max(PromotionID) AS proID from mas_promotion";
    $query_proID = sqlsrv_query($conn, $stmt_proID);
	$proID="";
	if($row = sqlsrv_fetch_array($query_proID)) {
          echo "" . $row["proID"]. "";
		  $proID=$row['proID'];
    }
	/*$sql = "Insert into mas_promotionDetail(ConditionType,ConditionAmount,ConditionPrice,ProductID,ServiceType,CategoryID,PromotionID) values (?,?,?,?,?,?,?)";
	$params = array($ConType,$ConPiece,$ConPrice,$ProductID,$ServiceType,$CategoryID,$proID);
       			$stmt = sqlsrv_query( $conn, $sql, $params);
				if( $stmt === false ) {
					$response="Error";
		 		die( print_r( sqlsrv_errors(), true));
				}
				else
				{
					$response='Success';
				}*/
	$stmt = "Insert into mas_promotionDetail(ConditionType,ConditionAmount,ConditionPrice,ProductID,ServiceType,CategoryID,PromotionID)
    values($ConType,$ConPiece,$ConPrice,$ProductID,$ServiceType,$CategoryID,$proID);";
    sqlsrv_query($conn, $stmt);
	
	$stmt_detailID = "Select Max(PromotionDetailID) AS prodetailID from mas_promotionDetail";
    $query__detailID = sqlsrv_query($conn, $stmt_detailID);
	$proDetailID="";
	if($row = sqlsrv_fetch_array($query__detailID)) {
          echo "" . $row["prodetailID"]. "";
		  $proDetailID=$row["prodetailID"];
	}
	
	/*$sql1 = "Insert into mas_promotionDiscount(DiscountType,DiscountProductNo,DiscountRate,DiscountPriceType,DiscountPrice,PromotionDetailID) values (?,?,?,?,?,?)";
	$params1 = array($DisType,$DisPiece,$DisPer,$DisPriceType,$DisPrice,$proDetailID);
       			$stmt1 = sqlsrv_query( $conn, $sql1, $params1);
				if( $stmt1 === false ) {
					$response="Error";
		 		die( print_r( sqlsrv_errors(), true));
				}
				else
				{
					$response='Success';
				}*/
	$stmt2 = "Insert into mas_promotionDiscount(DiscountType,DiscountProductNo,DiscountRate,DiscountPriceType,DiscountPrice,PromotionDetailID)
    values($DisType,$DisPiece,$DisPer,$DisPriceType,$DisPrice,$proDetailID);";
    sqlsrv_query($conn, $stmt2);
	
	
	sqlsrv_close($conn);

	$cv=iconv("Windows-874","utf-8","");
	if($cv==false){
		echo $response;
	}else{
		echo $cv;
	}
}
?>