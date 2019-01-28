<?php
header("Content-type: text/plain; charset=utf-8");
	include("../config.php");

	date_default_timezone_set("Asia/Bangkok");
    $date = date("Y-m-d H:i:s");
    $sql = "update mas_promotion set EffectiveDate = ? ,ExpirationDate = ? ,TimeStart = ? ,TimeEnd = ?, UpdatedDate = ?,UpdatedBy = ? where PromotionID= ?";
				$params = array($_GET['DateStart'],$_GET['DateEnd'],$_GET['TimeStart'],$_GET['TimeEnd'],$date,$_GET['UpdateBy'],$_GET['PromotionID']);
       			$stmt = sqlsrv_query( $conn, $sql, $params);
				if( $stmt === false ) {
					$response="Error";
		 		die( print_r( sqlsrv_errors(), true));
				}
				else
				{
					$response="บันทึกรายการแล้ว";
				}
	echo $response;
?>
