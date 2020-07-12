<?php 
$page=isset($_GET['page']) ? $_GET['page']:1;
$limit=10;
$offset=$limit*($page-1);
$sort_param=param_json_get();

//echo "offset=".$offset."<br/>";
$sql="SELECT * FROM storage_ad ORDER BY data_create $sort_param LIMIT $limit OFFSET $offset";

if(isset($_POST['sort'])){
	param_json_set($sort_param);
}

function param_json_get(){
	$file_json='param.json';
	$open=file_get_contents($file_json);
	$decode=json_decode($open);
	return $decode;
	//$result=array();
	/*foreach($decode as $key=>$value){
		$result[$key]=$value;
	}*/
	//print_r($result);
}

function param_json_set($sort_param){
	$param_edit="";
	if($sort_param=="ASC"){
		$param_edit="DESC";
	}else{
		$param_edit="ASC";
	}
	$write_json=json_encode($param_edit);
	$JsonFile='param.json';
	$write=fopen($JsonFile,'w') or die ('error write json!');
	$file=fwrite($write,$write_json);
	fclose($write);
}

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
$conn=data_req();



$sql_count="SELECT * FROM storage_ad";

$result=$conn->query($sql_count);
$error=$conn->errorInfo();
$numRows=$result->rowCount();
$count_pages=$numRows/$limit;
if(!is_int($count_pages)){
	round($count_pages);
}
//echo $numRows." ".$count_pages;


$array_data=array();
foreach($conn->query($sql) as $row){
	array_push($array_data,$row);
}
//print_r($array_data);
 ?>
<html>
<head>
	<title>Объявление</title>
	<style>
		.data-table{
			border-collapse: collapse;
		}
		table td{
			border:1px solid;
		}
	</style>
</head>
<body>
	<table class="data-table" border="1" >
		<tr class="table-header">
			<td>
				Меню для навигации
				<form method="post" action="" >
					<input type="submit" name="sort" value="Сортировка по " />
				</form>
			</td>
		</tr>
		
		<tr class="table-body">
			<td>
				<table class="paste-data">
				<?php foreach($conn->query($sql) as $row){ ?>
					<tr>
						<td>
							id записи: <?php echo $row[0] ?>
						</td>	
						<td>
							Дата создания: <?php echo $row[1] ?>
						</td>	
						<td>
							Имя: <?php echo $row[2] ?>
						</td>
						<td>
							Текст: <?php echo $row[3] ?>
						</td>	
						<td>
							Ссылки на фото: <?php 
								$links = explode("+***+", $row[4]);
								foreach($links as $paste){
									echo "images/".$paste."<br/>";
								}
							?>
						</td>				
					</tr>
				<?php } ?>				
				</table>
			</td>
		</tr>
		
		<tr class="table-page-navigation">
			<td>
				<table class="nav-bar">
					<tr>
						<?php for($i=0; $i<$count_pages; $i++){ ?>
							<td>
								<?php echo "<a href='?page=".($i+1)."'>".($i+1)."</a>";	?>
							</td>
						<?php } ?>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>