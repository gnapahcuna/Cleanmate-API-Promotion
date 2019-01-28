<?php
header("Content-type: text/plain; charset=utf-8");
$json = file_get_contents("php://input");
$_POST = json_decode($json, true);

foreach($_POST as $object) {
	list($prop1,  $Proname) = each($object);
	list($prop2,  $IsActive) = each($object);
	list($prop3,  $StartDate) = each($object);
	list($prop4,  $StopDate) = each($object);
	list($prop5,  $TimeStart) = each($object);
	list($prop6,  $TimeEnd) = each($object);
	list($prop7,  $User) = each($object);
	date_default_timezone_set("Asia/Bangkok");
    $date = date("Y-m-d H:i:s");

	include("../config.php");

	
		$sql = "Insert into mas_promotion(PromotionName,IsActive,EffectiveDate,ExpirationDate,CreatedDate,CreatedBy,TimeStart,TimeEnd) values (?,?,?,?,?,?,?,?)";
	
	
				$params = array($Proname,$IsActive,$StartDate,$StopDate,$date,$User,$TimeStart,$TimeEnd);
       			$stmt = sqlsrv_query( $conn, $sql, $params);
				if( $stmt === false ) {
					$response="Error";
		 		die( print_r( sqlsrv_errors(), true));
				}
				else
				{
					//$response=$orderID.','.$orderNo;
					$response='Success';
				}

	$cv=iconv("Windows-874","utf-8","");
	if($cv==false){
		echo $response;
	}else{
		echo $cv;
	}
}
?>