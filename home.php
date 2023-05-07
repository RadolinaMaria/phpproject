<?php
include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
	header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta chrset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>home</title>

	<!-- font link -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

	<!-- CSS file link -->
	<link rel="stylesheet" href="css/style.css"> 
</head>

<body>
	<?php include 'header.php'; ?>

	<section class="home">

		<div class="content">
			<h3>Имотите, които виждате, казват много за нас</h3>
			<p>Намерете новия си имот в Estates</p>
			<!-- <a href="about.php" class="white-btn">Научи повече</a> -->
		</div>

	</section>


	<section class="products">
		<div class="box-container">
			<?php 

				$select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
				if(mysqli_num_rows($select_products) > 0){
					while($fetch_products = mysqli_fetch_assoc($select_products)){				
			?>
			<form action="" method="post" class="box">
				
				<img src="uploaded_img/<?php echo $fetch_products['image'];?>" alt="">

				<table class="table_products">
					<tr>
						<td>Aдрес: </td>
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
					<tr
						<td>Изолация: </td>
						<td><div class="isolation"><?php echo $fetch_products['isolation']; ?> </div></td>
					</tr>
				
<tr
						<td>Климатизация: </td>
						<td><div class="AirCondition"><?php echo $fetch_products['AirCondition']; ?> </div></td>
					</tr>
				</table>

				<div class="price"><?php echo $fetch_products['price'];?> лв.</div>

				<!-- 
				<div class="address"><?php echo $fetch_products['address'];?></div>
				<div class="view"><?php echo $fetch_products['view'];?></div>
				<div class="type"><?php echo $fetch_products['type'];?></div>
				<div class="area"><?php echo $fetch_products['area'];?></div>
				<div class="rooms"><?php echo $fetch_products['rooms'];?></div>		
				<div class="isolation"><?php echo $fetch_products['isolation'];?></div> -->
		<div class="AirCondition"><?php echo $fetch_products['AirCondition'];?></div> 

				
			</form>

			<?php 
				}
			}else{
				echo '<p class="empty">Все още няма добавени имоти</p>';
			}	
			?>
			
		</div>
	</section>


	<?php include 'footer.php'; ?>


	<script src="js/script.js"></script>
</body>

</html>