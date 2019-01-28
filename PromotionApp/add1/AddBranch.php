<?php
header("Content-type: text/plain; charset=utf-8");
$json = file_get_contents("php://input");
$_POST = json_decode($json, true);

foreach($_POST as $object) {
	list($prop1,  $BranchID) = each($object);
	list($prop2,  $GroupID) = each($object);
	
	include("../config.php");
	$stmt_proID = "Select Max(PromotionID) AS proID from mas_promotion";
    $query_proID = sqlsrv_query($conn, $stmt_proID);
	$proID="";
	if($row = sqlsrv_fetch_array($query_proID)) {
          echo "" . $row["proID"]. "";
		  $proID=$row['proID'];
    }
	/*$sql = "Insert into mas_promotionBranch(BranchID,BranchGroupID,PromotionID) values (?,?,?)";
	$params = array($BranchID,$GroupID,$proID);
       			$stmt = sqlsrv_query( $conn, $sql, $params);
				if( $stmt === false ) {
					$response="Error";
		 		die( print_r( sqlsrv_errors(), true));
				}
				else
				{
					$response='Success';
				}*/
	$stmt = "Insert into mas_promotionBranch(BranchID,BranchGroupID,PromotionID)
              values($BranchID,$GroupID,$proID);";
    sqlsrv_query($conn, $stmt);

	$cv=iconv("Windows-874","utf-8","");
	if($cv==false){
		echo $response;
	}else{
		echo $cv;
	}
}
?>