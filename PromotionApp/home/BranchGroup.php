<?php
	include("../config.php");

	$stmt="";
	if($_GET['BranchGroupID']==1){
		$stmt = "select BranchGroupID,BranchGroupName from mas_branchgroup where IsActive=1";
	}else{
		$stmt = "select BranchGroupID,BranchGroupName from mas_branchgroup where IsActive=1 AND BranchGroupID='".$_GET['BranchGroupID']."'";
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