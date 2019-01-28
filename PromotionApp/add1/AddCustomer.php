<?php
header("Content-type: text/plain; charset=utf-8");
$json = file_get_contents("php://input");
$_POST = json_decode($json, true);

foreach($_POST as $object) {
	list($prop1,  $CustomerID) = each($object);
	list($prop2,  $MemberTypeID) = each($object);
	list($prop3,  $TelephoneNo) = each($object);
	
	include("../config.php");
	$stmt_proID = "Select Max(PromotionID) AS proID from mas_promotion";
    $query_proID = sqlsrv_query($conn, $stmt_proID);
	$proID="";
	if($row = sqlsrv_fetch_array($query_proID)) {
          echo "" . $row["proID"]. "";
		  $proID=$row['proID'];
    }
	/*$sql = "Insert into mas_promotionCustomer(CustomerID,MemberTypeID,TelephoneNo,PromotionID) values (?,?,?,?)";
	$params = array($CustomerID,$MemberTypeID,$TelephoneNo,$proID);
       			$stmt = sqlsrv_query( $conn, $sql, $params);
				if( $stmt === false ) {
					$response="Error";
		 		die( print_r( sqlsrv_errors(), true));
				}
				else
				{
					$response='Success';
				}*/
	
	$stmt = "Insert into mas_promotionCustomer(CustomerID,MemberTypeID,TelephoneNo,PromotionID)
    values($CustomerID,$MemberTypeID,$TelephoneNo,$proID);";

    sqlsrv_query($conn, $stmt);

	$cv=iconv("Windows-874","utf-8","");
	if($cv==false){
		echo $response;
	}else{
		echo $cv;
	}
}
?>