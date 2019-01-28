<?php

   	include("../config.php");
	$stmt="select distinct p.PromotionID,p.PromotionName,p.EffectiveDate,p.ExpirationDate,p.TimeStart,p.TimeEnd,ISNULL(emp.FirstName,0) as CreatedBy,emp.BranchID,b.BranchNameTH
from (mas_promotion p left join mas_promotionBranch pmb on p.PromotionID=pmb.PromotionID)
left join (uac_employee emp left join mas_branch b on emp.BranchID=b.BranchID) on p.CreatedBy=emp.EmployeeCode
where ((pmb.BranchID IS NOT NULL AND pmb.BranchID='".$_GET['branchID']."') OR (pmb.BranchID IS NULL AND pmb.BranchGroupID='".$_GET['branchGroupID']."')) AND p.CreatedBy!=0 AND p.IsActive=3";
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