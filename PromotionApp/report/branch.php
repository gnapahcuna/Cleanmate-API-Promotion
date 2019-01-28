<?php

   	include("../config.php");
	$stmt="";
	if($_GET['branchID']==1&&$_GET['branchGroupID']=1){
		$stmt="select b.BranchID,b.BranchNameTH,count(*) as Num, sum(od.PromoDiscount) as Total
from ops_promotionLog logs left join ((ops_order od left join uac_customer cust on od.CustomerID=cust.CustomerID)
left join mas_branch b on od.BranchID=b.BranchID)
on logs.OrderNo=od.OrderNo where PromotionID='".$_GET['PromotionID']."' AND od.PromoDiscount!=0
Group By b.BranchID,b.BranchNameTH";
	}else{
		$stmt="select b.BranchID,b.BranchNameTH,count(*) as Num, sum(od.PromoDiscount) as Total
from ops_promotionLog logs left join ((ops_order od left join uac_customer cust on od.CustomerID=cust.CustomerID)
left join mas_branch b on od.BranchID=b.BranchID)
on logs.OrderNo=od.OrderNo where PromotionID='".$_GET['PromotionID']."' AND od.PromoDiscount!=0 AND (b.BranchGroupID='".$_GET['branchGroupID']."' OR b.BranchID='".$_GET['branchID']."')
Group By b.BranchID,b.BranchNameTH";
	}
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