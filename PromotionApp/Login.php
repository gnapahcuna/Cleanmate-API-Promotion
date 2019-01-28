<?php
header("Content-type: text/plain; charset=utf-8");
  $ID="";
  $response="";
  $ip="";
  $check="";
  
  if($_GET['user']){
	 	include("config.php");
		$stmt = "select distinct AccountCode,FirstName,LastName,b.BranchGroupID,emp.BranchID,BranchNameTH,r.RoleDesc,SignOnIPAddress,
CASE WHEN IsSignOn IS NULL THEN 0  ELSE IsSignOn END as IsSignOn,
CASE WHEN '".$_GET['dates']."' between EffectiveDate and ExpireDate THEN '#1' ELSE '#2' END as Checked,RoleDesc
from ((uac_useraccount acc left join (uac_employee emp left join mas_branch b on emp.BranchID=b.BranchID)
on acc.AccountCode=emp.EmployeeCode) left join (uac_userpermission userp left join uac_programcode pgc on userp.ProgramCode=pgc.ProgramCode) 
on acc.UserAccountCode=userp.UserAccountCode
left join uac_role r on acc.RoleCode=r.RoleCode) inner join uac_rolepermission rpm on acc.RoleCode=rpm.RoleCode 
where acc.Username='".$_GET['user']."' AND acc.Password='".$_GET['pass']."' and (AccoutType=2 OR AccoutType=1) and ProgramName='App POS'";
    	$query = sqlsrv_query($conn, $stmt);
		
		
		$stmt1 = "select distinct p.ProgramCode,ProgramName,ProgramDescription,MenuName,coalesce(usp.IsCreate,0) as IsCreate,
coalesce(usp.IsRead,0) as IsRead,coalesce(usp.IsUpdate,0) as IsUpdate,coalesce(usp.IsDelete,0) as IsDelete 
from (uac_useraccount acc left join
(uac_programcode p left join uac_userpermission usp on p.ProgramCode = usp.ProgramCode)
on acc.UserAccountCode=usp.UserAccountCode) inner join uac_rolepermission rpm on acc.RoleCode=rpm.RoleCode 
where acc.Username='".$_GET['user']."' AND acc.Password='".$_GET['pass']."' and (AccoutType=2 OR AccoutType=1) and ProgramName='App POS'";
    	$query1 = sqlsrv_query($conn, $stmt1);
		
		$object_array = array();
		$object_array1 = array();
		if($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
		{	
			$check=$result['Checked'];
			array_push($object_array,$result);
			$ip=$result['IsSignOn'];
			$ID=$result['AccountCode'];
		}
		
		if($check=="#1"){
		while($result = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC)){
			array_push($object_array1,$result);
		}
		if($ID!=""&&$ip=='0'){
			$sql_2 = "update uac_useraccount SET IsSignOn=?,SignOnIPAddress=? where AccountCode=?";
			$params_2 = array($_GET['IsSignOn'],$_GET['ip'],$ID);
       		$stmt_2 = sqlsrv_query($conn, $sql_2, $params_2);
			if( $stmt_2 === false ) {
				$response="Error";
			}
			else
			{
				//$response="บันทึกรายการเรียบร้อยแล้ว";
			}
		}
		$result_data=array();
		array_push($result_data,array('Data_User'=>$object_array,'Data_Role'=>$object_array1));
		
		$json_array=json_encode($result_data);
		echo $json_array;
		}else{
			echo $check;
		}
  }else{
	  echo "กรุณาใส่ Username และ Password";
  }
  
  $cv=iconv("Windows-874","utf-8",$response);
  
?>