<?php
function data_req(){
	$user='ad_user';
	$pwd='data_2020';
	$data='ad_list';
	try{
		$conn=new PDO('mysql:host=localhost;dbname=ad_list',$user,$pwd);
		return $conn;
	}catch(PDOException $e){
		echo $sql.$e->getMessage();
	}
}
?>