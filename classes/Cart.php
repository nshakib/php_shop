<?php 
    $filepath = realpath(dirname(__FILE__));
    include_once($filepath.'/../lib/Database.php');
    include_once($filepath.'/../helper/Format.php');
?>

<?php 
class Cart{
    private $db;
    private $fm;

    function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function addToCart($quantity, $id)
    {
        $quantity = $this->fm->validation($quantity);
        $quantity = mysqli_real_escape_string($this->db->link, $quantity);
        $productId = mysqli_real_escape_string($this->db->link, $id);
        $sId = session_id();

        $squery = "select * from tbl_product where productId = '$productId'";
        $result = $this->db->select($squery)->fetch_assoc();
        $productName = $result['productName'];
        $price = $result['price'];
        $image = $result['images'];

        $checkQuery = "select * from tbl_cart where productId = '$productId' AND sId ='$sId' ";
        $getPro = $this->db->select($checkQuery);
        if($getPro)
        {
            $msg = "Product Already Added";
            return $msg;
        }else{

        $query = "INSERT INTO tbl_cart(sId,productId, productName, price,quantity, images)
            VALUES('$sId', '$productId', '$productName','$price', '$quantity', '$image')";

        $product_insert = $this->db->insert($query);

            if($product_insert){
                header('Location:cart.php');
            } else {
                header('Location:404.php');
            }
        }

    }

    public function getCartProduct()
    {
        $sId = session_id();
        $query = "SELECT * FROM tbl_cart where sId = '$sId'";
        $result = $this->db->select($query);
        return $result;
    }

    public function updateCartQuantity($quantity, $cartId)
    {
        // $cartId = $this->fm->validation($cartId);
        $cartId = mysqli_real_escape_string($this->db->link, $cartId);
        $quantity = mysqli_real_escape_string($this->db->link, $quantity);

        $query = "UPDATE tbl_cart SET quantity = '$quantity' WHERE cartId = '$cartId'";
            $update_row = $this->db->update($query);
            
            if($update_row){
                header('Location:cart.php');
            }else{
                $msg = "<span class='error'>Quantity not updated!.</span>";
                return $msg;
            }
    }

    public function delProductByCart($delId)
    {
        $delId = mysqli_real_escape_string($this->db->link, $delId);
        $query = "DELETE FROM tbl_cart WHERE cartId = '$delId'";

        $del = $this->db->delete($query);

        if($del){
            header('Location:cart.php');
        }else{
            $msg = "<span class='error'>Product not deleted!.</span>";
            return $msg;
        }
    }

    public function checkTableCart()
    {
        $sId = session_id();
        $query = "SELECT * FROM tbl_cart where sId = '$sId'";
        $result = $this->db->select($query);
        return $result;
    }

    public function delCustomerCart()
    {
        $sId = session_id();
        $query = "DELETE FROM tbl_cart WHERE sId = '$sId'";
        $this->db->delete($query);


    }

    public function orderProduct($cmrId)
    {
        $sId = session_id();
        $query = "SELECT * FROM tbl_cart where sId = '$sId'";
        $getPro = $this->db->select($query);
        if($getPro)
        {
            while($result= $getPro->fetch_assoc())
            {
                $productId =  $result['productId'];
                $productName =  $result['productName'];
                $quantity =  $result['quantity'];
                $price =  $result['price'] * $quantity;
                $image =  $result['images'];

                $query = "INSERT INTO tbl_order(cmrId,productId, productName, quantity,price, images)
                VALUES('$cmrId', '$productId', '$productName', '$quantity','$price', '$image')";
                $this->db->insert($query);
            }
        }
    }

    public function payableAmount($cmrId)
    {
        $query = "SELECT * FROM tbl_order where cmrId = '$cmrId' AND date= now()";
        $result = $this->db->select($query);
        return $result;
    }

    public function getOrderProduct($cmrId)
    {
        $query = "SELECT * FROM tbl_order where cmrId = '$cmrId' ORDER BY date DESC";
        $result = $this->db->select($query);
        return $result;

    }

    public function checkOrder($cmrId)
    {
        $query = "SELECT * FROM tbl_order where cmrId = '$cmrId'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getAllOrderProduct()
    {
        $query = "SELECT * FROM tbl_order order by date desc";
        $result = $this->db->select($query);
        return $result;
    }

    public function productShifted($id,$time,$price)
    {
        $id     = mysqli_real_escape_string($this->db->link, $id);
        $time   = mysqli_real_escape_string($this->db->link, $time);
        $price  = mysqli_real_escape_string($this->db->link, $price);

        $query = "UPDATE tbl_order SET status = '1' WHERE cmrId = '$id' AND date='$time' AND price='$price'";
            $update_row = $this->db->update($query);
            
            if($update_row){
                $msg = "<span class='success'>Updated successfully!.</span>";
                return $msg;
            }else{
                $msg = "<span class='error'>Not Updated!.</span>";
                return $msg;
            }
    }

    public function delProductShifted($id,$time,$price)
    {
        $id     = mysqli_real_escape_string($this->db->link, $id);
        $time   = mysqli_real_escape_string($this->db->link, $time);
        $price  = mysqli_real_escape_string($this->db->link, $price);

        $query = "DELETE FROM tbl_order WHERE cmrId = '$id' AND date='$time' AND price='$price'";
        $delete_row= $this->db->delete($query);
        if($delete_row){
            $msg = "<span class='success'>Deleted successfully!.</span>";
            return $msg;
        }else{
            $msg = "<span class='error'>Not deleted!.</span>";
            return $msg;
        }

    }

    public function productShiftedConfirm($id,$time,$price)
    {
        $id     = mysqli_real_escape_string($this->db->link, $id);
        $time   = mysqli_real_escape_string($this->db->link, $time);
        $price  = mysqli_real_escape_string($this->db->link, $price);

        $query = "UPDATE tbl_order SET status = '2' WHERE cmrId = '$id' AND date='$time' AND price='$price'";
            $update_row = $this->db->update($query);
            
            if($update_row){
                $msg = "<span class='success'>Updated successfully!.</span>";
                return $msg;
            }else{
                $msg = "<span class='error'>Not Updated!.</span>";
                return $msg;
            }
    }
}
?>