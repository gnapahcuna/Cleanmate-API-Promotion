<?php
	include("../config.php");

    //fetch table rows from mysql db
    $stmt = "select mas_service.ServiceType,ServiceNameTH,ServiceNameEN,mas_service.ImageFile from mas_product LEFT JOIN mas_pricelist
	ON mas_product.ProductID = mas_pricelist.ProductID LEFT JOIN mas_productcategory
	ON mas_pricelist.CategoryID=mas_productcategory.CategoryID LEFT JOIN mas_service
	ON mas_pricelist.ServiceType=mas_service.ServiceType where mas_product.IsActive=1
	and mas_pricelist.BranchGroupID='".$_GET['BranchGroupID']."' 
	Group By mas_service.ServiceType,ServiceNameTH,ServiceNameEN,mas_service.ImageFile";
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