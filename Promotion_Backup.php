<?php
header("Content-type: text/plain; charset=utf-8");
error_reporting(0);
$json = file_get_contents("php://input");
$_POST = json_decode($json, true);

$str = "";
$chk="";
$cv  = iconv("Windows-874", "utf-8", "â»ÃâÁªÑè¹"); 
$desc = iconv("Windows-874", "utf-8", "«Ñ¡áËé§Å´ 25%"); 
if($cv == false) {
	$cv = "â»ÃâÁªÑè¹";
	$desc .= "«Ñ¡áËé§Å´ 50%";
}
include("config.php");
$netAmount=0;
$i=0;
$k=0;
$total_price="";
$Amount=0;
$kk=0;

$u='.000';
date_default_timezone_set('Asia/Bangkok');
$Date = date('Y-m-d H:i:s');
$Total=0;
//getIDPromotion
	/*$promotonID_arr=array();
	$stmt_promo = "select PromotionID from mas_promotiontest where IsActive=1";
    $query_promo = sqlsrv_query($conn, $stmt_promo);
	while($row = sqlsrv_fetch_array($query_promo, SQLSRV_FETCH_ASSOC))
    {
		array_push($promotonID_arr,$row['PromotionID']);
	}
	for($p=0;$p<sizeof($promotonID_arr);$p++){
		$promotionID=$promotionID[$p];
	}*/

	foreach($_POST as $object) {
		list($prop1,  $ProductID) = each($object);
		list($prop2,  $ServiceType) = each($object);
		list($prop3,  $BranchID) = each($object);
		list($prop4,  $BranchGroupID) = each($object);
		list($prop5,  $Telephon) = each($object);
		list($prop6,  $TotalAll) = each($object);
		list($prop7,  $TotalPrice) = each($object);

		if($ServiceType==1){
			$Total+=1;
		}
	}

	$arr_promo_detail=array();
	$arr_promo_price=array();
	$arr_promo_id=array();
	$arr_promo_id_test=array();

foreach($_POST as $object) {
	list($prop1,  $ProductID) = each($object);
	list($prop2,  $ServiceType) = each($object);
	list($prop3,  $BranchID) = each($object);
	list($prop4,  $BranchGroupID) = each($object);
	list($prop5,  $Telephon) = each($object);
	list($prop6,  $TotalAll) = each($object);
	list($prop7,  $TotalPrice) = each($object);
	//$i+=$Counts;
	
	$k+=1;
	$i++;
	$k++;
	$kk=sizeof($_POST);
	$stmt = "select pd.ProductID,pd.CategoryID,pd.ProductNameTH,ProductPrice
	from mas_product pd left join (
	mas_pricelist pl left join mas_branch b on pl.BranchGroupID=b.BranchGroupID) 
	on pd.ProductID=pl.ProductID
	where b.BranchID='".$BranchID."' and pd.ProductID='".$ProductID."'";
    $query = sqlsrv_query($conn, $stmt);
	$price="";
	$CategoryID="";
    $MemberID="";
    $CustomerID="";
	
	$stmt_cust="select MemberTypeID,CustomerID from uac_customer where TelephoneNo='".$Telephon."'";
	$query_cust = sqlsrv_query($conn, $stmt_cust);
	if($row = sqlsrv_fetch_array($query_cust, SQLSRV_FETCH_ASSOC))
    {
        $MemberID=trim($row['MemberTypeID']);
        $CustomerID=trim($row['CustomerID']);
	}

    if($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
    {
		$total_price+=$row['ProductPrice'];
		$price=$row['ProductPrice'];
		$CategoryID=$row['CategoryID'];

		//$Amount+=$price;
	}
	$stmt_ser="select (case when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL then $price
	when ServiceType IS NULL AND CategoryID IS NULL AND ProductID IS NULL then $price
	when ServiceType IS NULL AND CategoryID IS NULL AND ProductID =$ProductID then $price
	when ServiceType =$ServiceType AND CategoryID IS NULL AND ProductID =$ProductID then $price
	when ServiceType =$ServiceType AND CategoryID =$CategoryID AND ProductID =$ProductID then $price
	when ServiceType IS NULL AND CategoryID =$CategoryID AND ProductID IS NULL then $price
	when ServiceType =$ServiceType AND CategoryID =$CategoryID AND ProductID IS NULL then $price
else 0 end) as Amount
from ((mas_promotion p left join 
(mas_promotionDetail pd left join mas_promotionDiscount pdc 
on pd.PromotionDetailID=pdc.PromotionDetailID)
on p.PromotionID=pd.PromotionID) left join mas_promotionBranch pdb on p.PromotionID=pdb.PromotionID)
left join mas_promotionCustomer pdcm on p.PromotionID=pdcm.PromotionID
where p.IsActive=1 AND BranchGroupID=$BranchGroupID AND '".$Date."' between EffectiveDate and ExpirationDate";
	$query_ser = sqlsrv_query($conn, $stmt_ser);
	if($row = sqlsrv_fetch_array($query_ser, SQLSRV_FETCH_ASSOC))
    {
		$Amount+=$row['Amount'];
		//$Total+=$row['indexs'];
	}
	
	$stmt1 = "select p.PromotionID,p.PromotionName,coalesce(ConditionType,0)as ConditionType,

	case when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL 
	AND ConditionType =2 AND ConditionAmount IS NULL AND $Amount>=ConditionPrice then 1 
	when ServiceType IS NULL AND CategoryID IS NULL AND ProductID IS NULL 
	AND ConditionType =2 AND ConditionAmount IS NULL AND $Amount>=ConditionPrice then 1
	when ServiceType IS NULL AND CategoryID IS NULL AND ProductID =$ProductID
	AND ConditionType =2 AND ConditionAmount IS NULL AND $Amount>=ConditionPrice then 1
	when ServiceType =$ServiceType AND CategoryID IS NULL AND ProductID =$ProductID  
	AND ConditionType =2 AND ConditionAmount IS NULL AND $Amount>=ConditionPrice then 1
	when ServiceType =$ServiceType AND CategoryID =$CategoryID AND ProductID =$ProductID
	AND ConditionType =2 AND ConditionAmount IS NULL AND $Amount>=ConditionPrice then 1
	when ServiceType IS NULL AND CategoryID =$CategoryID AND ProductID IS NULL
	AND ConditionType =2 AND ConditionAmount IS NULL AND $Amount>=ConditionPrice then 1
	else 0 end as checkAmount,ConditionPrice,


	(case when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND ConditionType=1 AND 
	BranchID IS NULL AND CustomerID IS NULL AND MemberTypeID IS NULL AND
	ConditionAmount<=$Total AND DiscountType=2 AND DiscountPriceType=2
	AND ((case when $Total>ConditionAmount  then 
	(ConditionAmount+($Total-ConditionAmount))-(($Total/ConditionAmount)-1)
	else ConditionAmount end) =$i OR
	(case when $Total>ConditionAmount  then 
	(ConditionAmount+($Total-ConditionAmount))-(($Total/ConditionAmount)-1)
	else ConditionAmount end) <$i ) AND $i<=$Total
	then (CAST($price as float)*DiscountRate)/100


	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND ConditionType=1 AND
	BranchID IS NULL AND CustomerID IS NULL AND MemberTypeID IS NULL AND 
	ConditionAmount<=$Total AND DiscountType=2 AND DiscountPriceType=1 
	AND ((case when $Total>ConditionAmount  then 
	(ConditionAmount+($Total-ConditionAmount))-(($Total/ConditionAmount)-1)
	else ConditionAmount end) =$i OR
	(case when $Total>ConditionAmount  then 
	(ConditionAmount+($Total-ConditionAmount))-(($Total/ConditionAmount)-1)
	else ConditionAmount end) <$i ) AND $i<=$Total
	then DiscountPrice


	when $ServiceType=ServiceType AND CategoryID =$CategoryID AND ProductID IS NULL AND ConditionType=1 AND 
	BranchID IS NULL AND CustomerID IS NULL AND MemberTypeID IS NULL AND
	ConditionAmount<=$Total AND DiscountType=2 AND DiscountPriceType=2
	AND ((case when $Total>ConditionAmount  then 
	(ConditionAmount+($Total-ConditionAmount))-(($Total/ConditionAmount)-1)
	else ConditionAmount end) =$i OR
	(case when $Total>ConditionAmount  then 
	(ConditionAmount+($Total-ConditionAmount))-(($Total/ConditionAmount)-1)
	else ConditionAmount end) <$i ) AND $i<=$Total
	then (CAST($price as float)*DiscountRate)/100


	when $ServiceType=ServiceType AND CategoryID =$CategoryID AND ProductID IS NULL AND ConditionType=1 AND
	BranchID IS NULL AND CustomerID IS NULL AND MemberTypeID IS NULL AND 
	ConditionAmount<=$Total AND DiscountType=2 AND DiscountPriceType=1 
	AND ((case when $Total>ConditionAmount  then 
	(ConditionAmount+($Total-ConditionAmount))-(($Total/ConditionAmount)-1)
	else ConditionAmount end) =$i OR
	(case when $Total>ConditionAmount  then 
	(ConditionAmount+($Total-ConditionAmount))-(($Total/ConditionAmount)-1)
	else ConditionAmount end) <$i ) AND $i<=$Total
	then DiscountPrice


	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND ConditionType=1 AND 
	BranchID IS NULL AND CustomerID IS NULL AND MemberTypeID IS NULL AND
	ConditionAmount<=$Total AND DiscountType=1 
	AND DiscountProductNo IS NULL 
	AND DiscountPriceType=1 then DiscountPrice

	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND ConditionType=1 AND 
	BranchID IS NULL AND CustomerID IS NULL AND MemberTypeID IS NULL AND
	ConditionAmount<=$Total AND DiscountType=1 
	AND DiscountProductNo IS NULL 
	AND DiscountPriceType=2 then (CAST($price as float)*DiscountRate)/100
	
	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND BranchID IS NULL AND CustomerID IS NULL AND MemberTypeID IS NULL AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL 
	AND  DiscountPriceType=2 then (CAST($price as float)*DiscountRate)/100

	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND BranchID IS NULL AND CustomerID IS NULL AND MemberTypeID IS NULL AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL
	AND  DiscountPriceType=1 then DiscountPrice
	
	
	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND BranchID IS NULL AND CustomerID IS NULL AND MemberTypeID=$MemberID AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL 
	AND  DiscountPriceType=2 then (CAST($price as float)*DiscountRate)/100

	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND BranchID =$BranchID AND CustomerID=$CustomerID AND MemberTypeID=$MemberID  AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=2 then (CAST($price as float)*DiscountRate)/100
	
	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND BranchID IS NULL AND CustomerID=$CustomerID AND MemberTypeID IS NULL AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=2 then (CAST($price as float)*DiscountRate)/100
   
   	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND BranchID IS NULL AND CustomerID=$CustomerID AND MemberTypeID IS NULL AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=1 then DiscountPrice

	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND ConditionType =2 AND 
	ConditionAmount IS NULL AND $Amount>=ConditionPrice AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=1 then DiscountPrice

	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND ConditionType =2 AND 
	ConditionAmount IS NULL AND $Amount>=ConditionPrice AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=2 then (CAST(ConditionPrice as float)*DiscountRate)/100
	
	when $ServiceType=ServiceType AND CategoryID =$CategoryID AND ProductID IS NULL AND ConditionType =2 AND 
	ConditionAmount IS NULL AND $Amount>=ConditionPrice AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=1 then DiscountPrice

	when $ServiceType=ServiceType AND CategoryID =$CategoryID AND ProductID IS NULL AND ConditionType =2 AND 
	ConditionAmount IS NULL AND $Amount>=ConditionPrice AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=2 then (CAST(ConditionPrice as float)*DiscountRate)/100

	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND ConditionType =1 AND 
	$i=ConditionAmount AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=1 then ($i/ConditionAmount)*DiscountPrice

	when $ServiceType=ServiceType AND CategoryID IS NULL AND ProductID IS NULL AND ConditionType =1 AND 
	$i=ConditionAmount AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=2 then ($i/ConditionAmount)*((CAST(ConditionPrice as float)*DiscountRate)/100)

	when $ServiceType=ServiceType AND CategoryID= $CategoryID AND ProductID =$ProductID AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=1 then DiscountPrice

	when $ServiceType=ServiceType AND CategoryID= $CategoryID AND ProductID =$ProductID AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=2 then (CAST($price as float)*DiscountRate)/100

	when $ServiceType=ServiceType AND CategoryID= $CategoryID AND ProductID IS NULL AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=1 then DiscountPrice

	when $ServiceType=ServiceType AND CategoryID= $CategoryID AND ProductID IS NULL AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=2 then (CAST($price as float)*DiscountRate)/100
	
	when ServiceType IS NULL AND CategoryID = $CategoryID AND ProductID IS NULL AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=2 then (CAST(ConditionPrice as float)*DiscountRate)/100 

	when ServiceType IS NULL AND CategoryID = $CategoryID AND ProductID IS NULL AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=1 then DiscountPrice 

	when ServiceType IS NULL AND CategoryID IS NULL AND ProductID = $ProductID AND ConditionType IS NULL AND 
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=2 then (CAST($price as float)*DiscountRate)/100

	when ServiceType IS NULL AND CategoryID IS NULL AND ProductID = $ProductID AND ConditionType IS NULL AND
	ConditionAmount IS NULL AND ConditionPrice IS NULL AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=1 then DiscountPrice

	when ServiceType IS NULL AND CategoryID IS NULL AND ProductID IS NULL AND ConditionType =2 AND 
	ConditionAmount IS NULL AND $Amount>=ConditionPrice AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=1 then DiscountPrice

	when ServiceType IS NULL AND CategoryID IS NULL AND ProductID IS NULL AND ConditionType =2 AND 
	ConditionAmount IS NULL AND $Amount>=ConditionPrice AND DiscountType=1 AND DiscountProductNo IS NULL AND DiscountPriceType=2 then (CAST($price as float)*DiscountRate)/100

	else 0 end) as totals
	from ((mas_promotion p left join 
(mas_promotionDetail pd left join mas_promotionDiscount pdc 
on pd.PromotionDetailID=pdc.PromotionDetailID)
on p.PromotionID=pd.PromotionID) left join mas_promotionBranch pdb on p.PromotionID=pdb.PromotionID)
left join mas_promotionCustomer pdcm on p.PromotionID=pdcm.PromotionID
	where p.IsActive=1 AND BranchGroupID='".$BranchGroupID."' AND '".$Date."' between EffectiveDate and ExpirationDate";

	$query1 = sqlsrv_query($conn, $stmt1);
	
    while($row = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC))
    {
		if($row['checkAmount']==1){
			$Amount=$Amount-$row['ConditionPrice'];
		}
		if($row['totals']>0){
			array_push($arr_promo_detail,$row['PromotionName']);
			array_push($arr_promo_price,$row['totals']);
			array_push($arr_promo_id,$row['PromotionID']);
			array_push($arr_promo_id_test,$row['PromotionID']);
		}

		$netAmount+=$row['totals'];
		$desc =$row['PromotionName'];
		//$chk .='['.$price.']'.'=>'.$row['totals'];
		//$desc .=$ServiceType.','.$Total.','.$i.'['.$row['totals'].']';
		
	}
	
	//$str .= "$ProductID  $ServiceType $BranchID  $cv: $counts \n\n";
	//$total = $total+$price;
}

//$netAmount = $total_price-$netAmount;
if($netAmount==0){
	$netAmount='100001';
}
$object_array = array();
//array_push($object_array,array('Price' => $netAmount, 'Description' => $desc));
//array_push($object_array,array('Price' => $netAmount, 'Description' => $desc));
$first_promo="";
$last_promo="";
$first_promo_arr=array();
$last_promo_arr=array();
$ans_price=0;
$ans_desc="";

$array = array_unique($arr_promo_id_test);
foreach ($array as $key => $value){
	$total=0;
	for($i=0;$i<count($arr_promo_id);$i++){
		if($value==$arr_promo_id[$i]){
			$total+=$arr_promo_price[$i];
		}
	}
	for($i=0;$i<count($arr_promo_id);$i++){
		if($value==$arr_promo_id[$i]){
			array_push($object_array,array('PromotionID' => $value, 'Price' => $total, 'Description' => $arr_promo_detail[$i]));	
			break;
		}
	}	
}

for($j=0;$j<count($arr_promo_detail);$j++){
	$first_promo=$arr_promo_id[$j];
	if($first_prom!=$last_promo){
		$last_promo=$first_promo;
	}else{
		
		$ans_desc=$arr_promo_detail[$j];
		$ans_price+=$arr_promo_price[$j];
		//array_push($object_array,array('PromotionID' => $last_promo, 'Price' => $arr_promo_price[$j], 'Description' => $ans_desc));		
	}
}

$json_array=json_encode($object_array);
echo $json_array;
?>