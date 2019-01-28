<?php
   	include("../config.php");

    //fetch table rows from mysql db
    $stmt = "select PromotionName,EffectiveDate,ExpirationDate,TimeStart,TimeEnd from mas_promotion where PromotionID='".$_GET['PromotionID']."'";
    $query = sqlsrv_query($conn, $stmt);
	
	$stmt1 = "select ser.ServiceNameTH,cate.CategoryNameTH,pdt.ProductNameTH,pd.ConditionType,pd.ConditionAmount,pd.ConditionPrice,
pdc.DiscountType,pdc.DiscountProductNo,pdc.DiscountPriceType,pdc.DiscountRate,pdc.DiscountPrice,ser.ServiceType,cate.CategoryID,pdt.ProductID
from (((mas_promotionDetail pd left join mas_product pdt on pd.ProductID=pdt.ProductID)
left join mas_productcategory cate on pd.CategoryID=cate.CategoryID) left join mas_service ser
on pd.ServiceType=ser.ServiceType) left join mas_promotionDiscount pdc on pd.PromotionDetailID=pdc.PromotionDetailID
where PromotionID='".$_GET['PromotionID']."'";
	$query1 = sqlsrv_query($conn, $stmt1);

	$stmt2 = "select cust.FirstName+' '+cust.LastName as CustomerName,cust.CustomerID
from mas_promotionCustomer pcust left join uac_customer cust on pcust.CustomerID=cust.CustomerID
where PromotionID='".$_GET['PromotionID']."'";
	$query2 = sqlsrv_query($conn, $stmt2);

	$stmt3 = "select BranchGroupName,BranchNameTH,pbranch.BranchGroupID,pbranch.BranchID
from (mas_promotionBranch pbranch left join mas_branch b on pbranch.BranchID=b.BranchID)
left join mas_branchgroup bg on pbranch.BranchGroupID=bg.BranchGroupID
where PromotionID='".$_GET['PromotionID']."'";
	$query3 = sqlsrv_query($conn, $stmt3);
    
    //create an array
	$data_promo_1=array();
    $object_array = array();
    while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
    {
 		array_push($data_promo_1,$row);
    }
	$data_promo_2=array();
	while($row = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC))
    {
 		array_push($data_promo_2,$row);
    }
	$data_promo_3=array();
	while($row = sqlsrv_fetch_array($query2, SQLSRV_FETCH_ASSOC))
    {
 		array_push($data_promo_3,$row);
    }
	$data_promo_4=array();
	while($row = sqlsrv_fetch_array($query3, SQLSRV_FETCH_ASSOC))
    {
 		array_push($data_promo_4,$row);
    }
	
	array_push($object_array,array('ProName'=>$data_promo_1,'ProDetail'=>$data_promo_2,'Cust'=>$data_promo_3,'Branch'=>$data_promo_4));
    $json_array=json_encode($object_array);
	echo $json_array;
?>