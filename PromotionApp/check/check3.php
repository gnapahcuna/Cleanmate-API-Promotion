<?php
header("Content-type: text/plain; charset=utf-8");
$json = file_get_contents("php://input");
$_POST = json_decode($json, true);

foreach($_POST as $object) {
	list($prop1,  $ServiceType) = each($object);
	list($prop2,  $CategoryID) = each($object);
	list($prop3,  $ProductID) = each($object);
	list($prop4,  $BranchID) = each($object);
	list($prop5,  $BranchGroupID) = each($object);

	include("../config.php");

	
		$stmt="select p.PromotionID,PromotionName,pd.ServiceType,pd.CategoryID,pd.ProductID,
case when ServiceType='$ServiceType' AND ProductID ='$ProductID' AND CategoryID ='$CategoryID' then 1 
	else 0 end as checking,
case when ServiceType='$ServiceType' AND ProductID ='$ProductID' AND CategoryID ='$CategoryID' then PromotionName  
	else '' end as checkingName,
case when ServiceType='$ServiceType' AND ProductID ='$ProductID' AND CategoryID ='$CategoryID' then ProductID
	else 0 end as checkingID
from (mas_promotion p left join mas_promotionDetail pd on p.PromotionID=pd.PromotionID) 
left join mas_promotionBranch pmb on p.PromotionID=pmb.PromotionID 
where ((pmb.BranchID IS NOT NULL AND pmb.BranchID='$BranchID') OR (pmb.BranchID IS NULL AND pmb.BranchGroupID='$BranchGroupID'))
AND IsActive=2";
    //fetch table rows from mysql db
    $query = sqlsrv_query($conn, $stmt);

    //create an array
	$IsCheck=false;
	$CheckName='';
	$CheckID='';
    $object_array = array();
    while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
    {
		if($row['checking']=='1'){
			$IsCheck=true;
			$CheckName=$row['checkingName'];
			$CheckID=$row['checkingID'];
			break;
		}
 		//array_push($object_array,$row);
    }
}
	array_push($object_array,array('IsCheck' => $IsCheck,'CheckName' => $CheckName,'CheckID' => $CheckID));
    $json_array=json_encode($object_array);
	echo $json_array;
?>