<?php include("inc/header.php"); ?>
<!-- Check unauthorize access -->
<?php 
	$login = Session::get("custLogin");
	if($login == false)
	{
		header("Location:login.php");
	}

?>
<style>
	table.tblone img{height: 90px;width: 100px;}
</style>
 <div class="main">
    <div class="content">
    	<div class="cartoption">		
			<div class="cartpage">
			    	<h2 style="text-align: center;width:auto">Product Compare</h2>
						<table class="tblone">
							<tr>
								<th>SL</th>
								<th>Product Name</th>
								<th>Image</th>
								<th>Price</th>
								<th>Action</th>
							</tr>
							<?php
								$cmrId = Session::get("cmrId");
								$getPro = $pd->getComparedData($cmrId);
								if($getPro)
								{
									$i= 0;
									while($result = $getPro->fetch_assoc())
									{
										$i++;
							?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $result['productName']?></td>
								<td><img src="admin/<?php echo $result['images']?>" alt=""/></td>
								<td>$ <?php echo $result['price']?></td>
								<td><a href="details.php?proid=<?php echo $result['productId'] ?>">View</a></td>
								<!-- <td><a onclick=" return confirm('Are sure to Delete!')" href="?delpro=<?php echo $result['cartId'] ?>">X</a></td>
                                <td></td> -->
                            </tr>
							<?php }}?>
							
						</table>
					</div>
					<div class="shopping">
						<div class="shopleft" style="width: 100%;text-align:center;">
							<a href="index.php"> <img src="images/shop.png" alt="" /></a>
						</div>
					</div>
    	</div>  	
       <div class="clear"></div>
    </div>
 </div>
</div>
<?php include("inc/footer.php"); ?>
