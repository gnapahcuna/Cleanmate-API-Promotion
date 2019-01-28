<?php
header("Content-type: text/plain; charset=utf-8");
	$PrivilageID=3000;
	$CouponNo="";
	include("config.php");
	for($i=1000;$i<2001;$i++){
		$CouponNo=''.$i;
		//echo $CouponNo;
	$sql = "Insert into mas_privilage(PrivilageID,ConponNo,BranchGroupID,ServiceType,DiscountValue,IsActive,CreatedDate,EffectiveDate,ExpirationDate) values (?,?,?,?,?,?,?,?,?)";
	$params = array($PrivilageID,$CouponNo,3,1,100,1,'2019-01-25','2019-02-01','2019-03-31');
    $stmt = sqlsrv_query( $conn, $sql, $params);
	if( $stmt === false ) {
		$response="Error";
	die( print_r( sqlsrv_errors(), true));
	}
	else
	{
		$response='Success';
	}
	$PrivilageID++;	
	}

	echo $response;
?>