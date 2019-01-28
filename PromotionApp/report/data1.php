<?php

   	include("../config.php");
	
	$stmt="select logs.OrderNo,od.OrderDate,cust.FirstName+' '+cust.LastName as CustomerName,od.PromoDiscount,b.BranchNameTH
from ops_promotionLog logs left join ((ops_order od left join uac_customer cust on od.CustomerID=cust.CustomerID)
left join mas_branch b on od.BranchID=b.BranchID)
on logs.OrderNo=od.OrderNo where PromotionID='".$_GET['PromotionID']."' AND od.PromoDiscount!=0 and b.BranchID='".$_GET['branchID']."'";
    //fetch table rows from mysql db
    $query = sqlsrv_query($conn, $stmt);

    //create an array
    $object_array = array();
    while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
    {
 		array_push($object_array,$row);
    }
    $json_array=json_encode($object_array);
	echo $json_array;
?>