<?php include("inc/header.php"); ?>

<?php 
    if(isset($_GET['delwish']))
    {
        $productId = $_GET['delwish'];

        $delwlist = $pd->delWlistData($cmrId, $productId);
    }
?>
 <div class="main">
    <div class="content">
    	<div class="cartoption">		
			<div class="cartpage">
			    	<h2 style="text-align: center;width:auto">Wish List</h2>
						<table class="tblone">
							<tr>
								<th>SL</th>
								<th>Product Name</th>
								<th>Image</th>
								<th>Price</th>
								<th>Action</th>
							</tr>
							<?php
								$getPro = $pd->checkWlist($cmrId);
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
								<td>$ <?php echo $result['price']?></td>
                                <td><img src="admin/<?php echo $result['images']?>" alt=""/></td>

								<td><a href="details.php?proid=<?php echo $result['productId'] ?>">Buy Now</a> |
                                    <a onclick=" return confirm('Are sure to Delete!')" href="?delwish=<?php echo $result['productId'] ?>">Remove</a>
                                </td>
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
