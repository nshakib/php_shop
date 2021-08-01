<?php include "inc/header.php";?>
<?php 
	$login = Session::get("custLogin");
	if($login == false)
	{
		header("Location:login.php");
	}

?>
<style>
    .psuccess{width: 500px;height: 200px;text-align: center;border: 1px solid #ddd;margin: 0 auto;padding: 20px;}
    .psuccess h2{border-bottom: 1px solid #ddd;margin-bottom: 20px;padding-bottom: 10px;}
    .psuccess p{line-height: 25px;text-align: left;font-size: 18px;}
</style>
 <div class="main">
    <div class="content">
    	<div class="section group">
            <div class="psuccess">
                <h2>Success</h2>
                <?php
                    $cmrId = Session::get("cmrId");
                    $amount = $ct->payableAmount($cmrId);
                    if($amount)
                    {
                        $sum = 0;
                        while($result = $amount->fetch_assoc())
                        {
                            $price = $result['price'];
                            $sum +=$price;
                        }
                    }
                ?>
                <p style="color:red;">Total Payable Amout(Including Vat) : $
                    <?php 
                        $vat = $sum * 0.1;
                        $total = $sum+$vat;
                        echo $total;
                    ?>
                </p>
                <p>Thanks for purchase. Receive Your Order Successful. We will you contact you Asap
                    with delivery delivery details. Here is your details.... <a href="orderdetails.php">Visit here</a>
                </p>
            </div>
            <div class="back">
                <a href="cart.php">Previous</a>
            </div>
 		</div>
 	</div>
</div>
<?php include "inc/footer.php";?>
