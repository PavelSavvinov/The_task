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

$search_txt="";
$search_link="";

if(isset($_POST['search']) && !empty($_POST['txt_search'])){
$search_txt=$_POST['txt_search'];
$conn=data_req();
$sql="SELECT * FROM storage_ad WHERE txt LIKE '%$search_txt%'";

}else{
	echo "Пустое поле текста<br/>";
}

if(isset($_POST['search']) && !empty($_POST['photo_link'])){
	$search_link=$_POST['photo_link'];
	$sql_image="SELECT * FROM storage_ad WHERE photo_data LIKE '%$search_link%'";
	$conn=data_req();
}else{
	echo "Пустое поле ссылки для изображения<br/>";
}
 ?>
<html>
<head>
	<title>Поиск объявления</title>
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
	<table class="data-table">
		<tr>
			<td>
				<form method="post" action="">
					<p>Напишите искомое слово<br/><input type="text" name="txt_search" /></p>
					<p>Напишите искомую ссылку для фотографии<br/><input type="text" name="photo_link" /></p>
					<input type="submit" name="search" />
				</form>
			</td>
		</tr>
		<tr>
			<td>
				<table>
						Результаты поиска по тексту:
						<?php if($search_txt!=""){ foreach($conn->query($sql) as $row){ ?>		
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
						<?php } }?>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table>
						Результаты поиска по изображению:
						<?php if($search_link!=""){ foreach($conn->query($sql_image) as $row){ ?>		
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
						<?php } } ?>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>