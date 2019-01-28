<?php
	include("../config.php");

    //fetch table rows from mysql db
	$stmt = "select CustomerID,FirstName,LastName,NickName,TitleName,TelephoneNo,MemberTypeID,CustomerType from uac_customer
	where TelephoneNo='".$_GET['phone']."'";
    $query = sqlsrv_query($conn, $stmt);

    //create an array
    $object_array = array();
    if($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
    {
		 array_push($object_array,$row);
		 $json_array=json_encode($object_array);
		 echo $json_array;
    }else{
		echo '#1';
	}
   
?>