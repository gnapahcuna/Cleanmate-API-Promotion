<?php

   	include("../config.php");
	$stmt="";
	if($_GET['branchID']==1&&$_GET['branchGroupID']==1){
		$stmt = "select p.PromotionID,p.PromotionName,p.EffectiveDate,p.ExpirationDate,TimeStart,TimeEnd,ISNULL(emp.FirstName,0) as CreatedBy,emp.BranchID,b.BranchNameTH,
case when '".$_GET['dates']."' >= p.EffectiveDate AND '".$_GET['dates']."' <= p.ExpirationDate then 'Active'
	when '".$_GET['dates']."' <= p.EffectiveDate then 'Planning'  else 'Non-Active' end as IsActives,
sum(case when PromoDiscount!=0 AND pmb.BranchGroupID='".$_GET['branchGroupID']."' then 1 else 0 end) as Num,
ISNULL(sum(case when PromoDiscount!=0 AND pmb.BranchGroupID='".$_GET['branchGroupID']."' then PromoDiscount else 0 end),0) as Total
from ((mas_promotion p left join mas_promotionBranch pmb on p.PromotionID=pmb.PromotionID)
left join (uac_employee emp left join mas_branch b on emp.BranchID=b.BranchID) on p.CreatedBy=emp.EmployeeCode)
left join (ops_promotionLog logs left join ops_order od on logs.OrderNo=od.OrderNo) on p.PromotionID=logs.PromotionID
where  p.CreatedBy!=0 AND p.IsActive=2
Group By p.PromotionID,p.PromotionName,p.EffectiveDate,p.ExpirationDate,TimeStart,TimeEnd,emp.FirstName,emp.BranchID,b.BranchNameTH Order By emp.BranchID ASC, IsActives ASC";
	}else{
		$stmt = "select p.PromotionID,p.PromotionName,p.EffectiveDate,p.ExpirationDate,TimeStart,TimeEnd,ISNULL(emp.FirstName,0) as CreatedBy,emp.BranchID,b.BranchNameTH,
case when '".$_GET['dates']."' >= p.EffectiveDate AND '".$_GET['dates']."' <= p.ExpirationDate then 'Active'
	when '".$_GET['dates']."' <= p.EffectiveDate then 'Planning'  else 'Non-Active' end as IsActives,
sum(case when PromoDiscount!=0 AND od.BranchID='".$_GET['branchID']."' then 1 else 0 end) as Num,
ISNULL(sum(case when PromoDiscount!=0 AND od.BranchID='".$_GET['branchID']."' then PromoDiscount else 0 end),0) as Total
from ((mas_promotion p left join mas_promotionBranch pmb on p.PromotionID=pmb.PromotionID)
left join (uac_employee emp left join mas_branch b on emp.BranchID=b.BranchID) on p.CreatedBy=emp.EmployeeCode)
left join (ops_promotionLog logs left join ops_order od on logs.OrderNo=od.OrderNo) on p.PromotionID=logs.PromotionID
where ((pmb.BranchID IS NOT NULL AND pmb.BranchID='".$_GET['branchID']."') OR (pmb.BranchID IS NULL AND pmb.BranchGroupID='".$_GET['branchGroupID']."')) AND p.CreatedBy!=0 AND p.IsActive=2
Group By p.PromotionID,p.PromotionName,p.EffectiveDate,p.ExpirationDate,TimeStart,TimeEnd,emp.FirstName,emp.BranchID,b.BranchNameTH Order By emp.BranchID ASC, IsActives ASC";
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