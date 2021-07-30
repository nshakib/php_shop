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
}
?>