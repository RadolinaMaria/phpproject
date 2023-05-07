<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){
	$unique_number = mysqli_real_escape_string($conn, $_POST['unique_number']);
	$address = mysqli_real_escape_string($conn, $_POST['address']);
	$view = mysqli_real_escape_string($conn, $_POST['view']);
	$type = mysqli_real_escape_string($conn, $_POST['type']);
	$area = mysqli_real_escape_string($conn, $_POST['area']);
	$rooms = $_POST['rooms'];
	$isolation = $_POST['isolation'];
	$AirCondition = $_POST['AirCondition'];
	$price = $_POST['price'];
	$image = $_FILES['image']['name'];
  $image_size = $_FILES['image']['size'];
  $image_tmp_name = $_FILES['image']['tmp_name'];
  $image_folder = 'uploaded_img/'.$image;

	$select_product_number = mysqli_query($conn, "SELECT unique_number FROM `products` WHERE unique_number = '$unique_number'") or die('query failed');

	if(mysqli_num_rows($select_product_number) > 0){
		$message[] = 'Имотът вече е добавен';
	}else{
		$add_product_query = mysqli_query($conn, "INSERT INTO `products`(unique_number, address, view, type, area, rooms, isolation, AirCondition, price, image) VALUES ('$unique_number', '$address', '$view', '$type', '$area', '$rooms', '$isolation', '$AirCondition','$price', '$image')") or die('query failed');

		if($add_product_query){
			if($image_size > 2000000){
				$message[] = 'Размерът на снимката е прекалено голям!';
			}else{
				move_uploaded_file($image_tmp_name, $image_folder);
				$message[] = 'Имотът е добавен успешно!';
			}	
		}else{
			$message[] = 'Имотът не беше добавен!';
		}
	}
}

if(isset($_GET['delete'])){
	$delete_id = $_GET['delete'];
	$delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
	$fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
	unlink('uploaded_img'.$fetch_delete_image['image']);
	mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
	header('location:admin_products.php');
}


if(isset($_POST['update_product'])){

	$update_p_id = $_POST['update_p_id'];
	$update_unique_number = $_POST['update_unique_number'];
	$update_address = $_POST['update_address'];
	$update_view = $_POST['update_view'];
	$update_type = $_POST['update_type'];
	$update_area = $_POST['update_area'];
	$update_rooms = $_POST['update_rooms'];
	$update_isolation = $_POST['update_isolation'];
	$update_AirCondition = $_POST['update_AirCondition'];
	$update_price = $_POST['update_price'];

	mysqli_query($conn, "UPDATE `products` SET unique_number='$update_unique_number', address = '$update_address', view = '$update_view', type = '$update_type', area = '$update_area', rooms = '$update_rooms', isolation = '$update_isolation', AirCondition = '$update_AirCondition', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

	$update_image = $_FILES['update_image']['name'];
   	$update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   	$update_image_size = $_FILES['update_image']['size'];
   	$update_folder = 'uploaded_img/'.$update_image;
   	$update_old_image = $_POST['update_old_image'];

   	if(!empty($update_image)){
    	if($update_image_size > 2000000){
        	$message[] = 'image file size is too large';
      }else{
        mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
        move_uploaded_file($update_image_tmp_name, $update_folder);
        unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta chrset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>products</title>

	<!-- font link -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

	<!-- CSS file link -->
	<link rel="stylesheet" href="css/admin_style.css"> 

</head>
<body>

<?php include 'admin_header.php'; ?>


<!-- Product CRUD start-->

<section class="add-products">
	
	<h1 class="title">налични имоти</h1>

	<form action="" method="post" enctype="multipart/form-data">
		<h3>добави  имот</h3>
		
		<input type="text" name="unique_number" class="box" placeholder="единен номер" required>

		<input type="text" name="address" class="box" placeholder="адрес" required>
		
		<input type="text" name="view" class="box" placeholder="изложение" required>
		
		<!-- тип -->
		<select name="type" class="box" required>
			<option value="Студио">Студио</option>
			<option value="Гарсониера">Гарсониера</option>
			<option value="Мезонет">Мезонет</option>
			<option value="Многостаен">Многостаен</option>
			<option value="Тристаен">Тристаен</option>
			<option value="Двустаен">Двустаен </option>
			<option value="Боксониера">Боксониера</option>
		
		</select>

		<!-- квадратура -->
		<input type="text" name="area" class="box" placeholder="квадратура" required>

		

		<!-- брой стаи -->
		<input type="number" min="0" name="rooms" class="box" placeholder="брой стаи" required>

		<input type="text"  name="isolation" class="box" placeholder="изолация" required>

		<input type="text"  name="AirCondition" class="box" placeholder="климатизация" required>

		<!-- цена -->
		<input type="number" min="0" name="price" class="box" placeholder="цена" required>

		<!-- снимка -->
		<input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>

		<input type="submit" value="добави" name="add_product" class="btn">
	</form>
</section>

<!-- Product CRUD end-->

<!-- show products -->

<section  class="show-products">
	<div class="box-container">

		<?php
			$select_products = mysqli_query($conn, "SELECT * from `products`") or die('query failed');
			if(mysqli_num_rows($select_products) > 0){
				while($fetch_products = mysqli_fetch_assoc($select_products)){
		?>
		<div class="box">
			<div class="unique_number">единен номер: <?php echo $fetch_products['unique_number']; ?></div>
			<img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">	
			
			<table class="table_products">
				<tr>
					<td>Адрес: </td>
					<td><div class="address"><?php echo $fetch_products['address']; ?></div></td>
				</tr>
				<tr>
					<td>Изглед: </td>
					<td><div class="view"><?php echo $fetch_products['view']; ?></div></td>
				</tr>
				<tr>	
					<td>Тип: </td>
					<td><div class="type"><?php echo $fetch_products['type']; ?></div></td>
				</tr>
				<tr>
					<td>Квадратура: </td>
					<td><div class="area"><?php echo $fetch_products['area']; ?></div></td>
				</tr>
				<tr>
					<td>Брой стаи: &nbsp;&nbsp;</td>
					<td><div class="rooms"><?php echo $fetch_products['rooms']; ?> </div></td>
				</tr>
				<tr>
					<td>Изолация: </td>
					<td><div class="isolation"><?php echo $fetch_products['isolation']; ?> </div></td>
				</tr>
	
<tr>
					<td>Климатизация: </td>
					<td><div class="AirCondition"><?php echo $fetch_products['AirCondition']; ?> </div></td>
				</tr>
			
			</table>
			
         	<div class="price"><?php echo $fetch_products['price']; ?> лв</div>
			
         	<a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">редактирай</a>
         	<a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Сигурен ли си, че искаш да изтриеш този имот?')">изтрий</a>
		</div>
		
		<?php
			}
		}else{
			echo '<p class="empty">все още няма добавени имоти</p>';
		}
		?>
	</div>
</section>

<!-- show products -->

<!-- UPDATE -->
<section class="edit-product-form">
	<?php 
		if(isset($_GET['update'])){
			$update_id = $_GET['update'];
			$update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or dire('query failed');
			if(mysqli_num_rows($update_query) > 0){
				while($fetch_update = mysqli_fetch_assoc($update_query)){
	?>
	<form action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id'];?>">

		<input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image'];?>">
		
		<img src="uploaded_img/<?php echo $fetch_update['image'];?>" alt="">

		<input type="text" name="update_unique_number" value="<?php echo $fetch_update['unique_number'];?>" class="box" required placeholder="въведи единен номер">
		
		<input type="text" name="update_address" value="<?php echo $fetch_update['address'];?>" class="box" required placeholder="въведи адрес">
		
		<input type="text" name="update_view" value="<?php echo $fetch_update['view'];?>" class="box" required placeholder="въведи изглед">
		
		<select name="update_type" value="<?php echo $fetch_update['type'];?>" class="box" required>
			<option value="Студио">Студио</option>
			<option value="Гарсониера">Гарсониера</option>
			<option value="Мезонет">Мезонет</option>
			<option value="Многостаен">Многостаен</option>
			<option value="Тристаен">Тристаен</option>
			<option value="Двустаен">Двустаен </option>
			<option value="Боксониера">Боксониера</option>
		</select>

		
		
		<!-- квадратура -->
		<input type="text" name="area" class="box" placeholder="квадратура" required>

		<!-- Брой стаи -->
		<input type="number" min="0" name="rooms" value="<?php echo $fetch_update['rooms'];?>" class="box" placeholder="въведи брой стаи " required>

		<input type="text"  name="isolation" class="box" value="<?php echo $fetch_update['isolation'];?>" placeholder="въведи наличие на изолация  " required>

		<input type="text"  name="AirCondition" class="box" value="<?php echo $fetch_update['AirCondition'];?>" placeholder="въведи наличие на климатизация " required>



		<!-- цена -->
		<input type="number" min="0" name="update_price" class="box" value="<?php echo $fetch_update['price'];?>" placeholder="въведи цена" required>

		<input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">

		<input type="submit" value="редактирай" name="update_product" class="btn">
		<input type="reset" value="откажи" id="close-update" name="update_product" class="option-btn">
	</form>
	<?php 
			}
		}
		}else{
			echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
		}
	?>
</section>


<!-- Admin js file link-->
<script src="js/admin_script.js"></script>

</body>
</html>