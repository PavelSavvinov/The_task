<?php 
	$error="";
include 'mysql_connect.php';
	
	/*if(!empty($_FILES)){
		echo '<pre>';
		print_r($_FILES);
		echo '</pre>';
		$name_photos=array();
		for($i=0;$i<3;$i++){
			if(!empty($_FILES['photo']['name'][$i])){
				array_push($name_photos,$_FILES['photo']['name'][$i]);
			}
		}
		print_r($name_photos);
	}*/
	if(isset($_POST['name']) && isset($_POST['txt']) && isset($_POST['send'])){
		if(!empty($_POST['name']) && !empty($_POST['txt']) && mb_strlen($_POST['txt'], 'utf-8')<=2000 && mb_strlen($_POST['name'], 'utf-8')<=200){
			
			$name_photos=array();
			for($i=0;$i<3;$i++){
				if(!empty($_FILES['photo']['name'][$i])){
					array_push($name_photos,$_FILES['photo']['name'][$i]);
				}
			}	
			if(count($name_photos)==0){
				$name_photos[0]="Нету фотографий";
			}
			$send_image_links=implode("+***+", $name_photos);
			
			$data_txt=[$_POST['name'],$_POST['txt'],$send_image_links];
			$conn=data_req();
			try{
			$sql="INSERT INTO storage_ad (data_create,name,txt,photo_data) VALUES(NOW(),'$data_txt[0]','$data_txt[1]','$data_txt[2]')";
			$conn->exec($sql);
			echo "Данные отправлены";
			}catch(PDOException $e){
				echo $sql.$e->getMessage();
			}
			$conn=null;
		}else if(empty($_POST['name']) || empty($_POST['txt'])){
			$error="Пожалуйста заполните поля";
		}else if(mb_strlen($_POST['txt'], 'utf-8')>=2000){
			$error="Текстовое сообщение превышает более 2000 символов";
		}else if(mb_strlen($_POST['name'], 'utf-8')>=200){
			$error="Ваше имя превышает более 200 символов";
		}
	}
 ?>
<html>
<head>
	<title>Добавление новых объявлений</title>
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
	<table>
		<tr>
			<td>
				Страница для добавления новых объявлений <br/>
				<?php if(!empty($error)){echo $error;} ?>
			</td>
		</tr>
		<tr>
			<td>
				<form method="post" action="" enctype="multipart/form-data" >
					<input type="text" name="name" />
					<br/>
					<textarea name="txt" ></textarea>
					<br/>
					<input type="file" name="photo[]" />
					<input type="file" name="photo[]" />
					<input type="file" name="photo[]" />
					<br/>
					<input type="submit" name="send" />
				</form>
			</td>
		</tr>	
	</table>
</body>
</html>