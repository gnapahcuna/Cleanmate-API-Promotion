<?php

header("Content-type: text/plain; charset=utf-8");


	//if($_GET['ID']){
		include("../config.php");

	$stmt = "select * from mas_branchgroup";
    $query = sqlsrv_query($conn, $stmt);

    //create an array
    $object_array = array();
    while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
    {
		array_push($object_array,array('BranchGroupID' => $row['BranchGroupID'],'BranchGroupName' => $row['BranchGroupName'] ));
    }
    $json_array=json_encode($object_array);
	echo $json_array;
 ?>
