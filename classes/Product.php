<?php 
    $filepath = realpath(dirname(__FILE__));
    include_once($filepath.'/../lib/Database.php');
    include_once($filepath.'/../helper/Format.php');
?>

<?php
    class Product{

    private $db;
    private $fm;

    function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function productInsert($data, $file)
    {
        // $productName = $this->fm->validation($data['productName']);
        $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
        $catId = mysqli_real_escape_string($this->db->link, $data['catId']);
        $brandId = mysqli_real_escape_string($this->db->link, $data['brandId']);
        $body = mysqli_real_escape_string($this->db->link, $data['body']);
        $price = mysqli_real_escape_string($this->db->link, $data['price']);
        $type = mysqli_real_escape_string($this->db->link, $data['type']);



        $permited  = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $file['images']['name'];
        $file_size = $file['images']['size'];
        $file_temp = $file['images']['tmp_name'];

        $div = explode('.', $file_name);
        $file_ext = strtolower(end($div));
        $unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
        $uploaded_image = "uploads/".$unique_image;

        //Empty Field check logic
        if($productName == "" || $catId =="" || $brandId =="" || $body =="" || $price =="" || $file_name =="" || $type ==""){
            $msg = "<span class='error'>Field must not empty</span>";
            return $msg;

        }
        //File Size logic
        elseif ($file_size >1048567) 
        {
            echo "<span class='error'>Image Size should be less then 1MB!</span>";
        }
        //file type logic
        elseif (in_array($file_ext, $permited) === false) 
        {
            echo "<span class='error'>You can upload only:-"
            .implode(', ', $permited)."</span>";
        }
        else{

            move_uploaded_file($file_temp, $uploaded_image);

            $query = "INSERT INTO tbl_product(productName, catId, brandId, body, price, images, type)
            VALUES('$productName','$catId','$brandId','$body','$price','$uploaded_image','$type')";

            $product_insert = $this->db->insert($query);

            if($product_insert){
                $msg = "<span class='success'>Product inserted successfully.</span>";
                return $msg;
            } else {
                $msg = "<span class='error'>Product not inserted successfully.</span>";
                return $msg;
            }
        }
    }

    public function getAllProduct()
    {
        $query = "SELECT tbl_product.*, tbl_category.catName, tbl_brand.brandName
        FROM tbl_product 

        INNER JOIN tbl_category ON tbl_product.catId = tbl_category.catId

        INNER JOIN tbl_brand ON tbl_product.brandId = tbl_brand.brandId

         ORDER BY tbl_product.productId DESC";

        $result = $this->db->select($query);
        return $result;
    }

    public function getProById($id)
    {
        $query = "SELECT * FROM tbl_product where productId = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function productUpdate($data, $file, $id)
    {
        // $productName = $this->fm->validation($data['productName']);
        $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
        $catId       = mysqli_real_escape_string($this->db->link, $data['catId']);
        $brandId     = mysqli_real_escape_string($this->db->link, $data['brandId']);
        $body        = mysqli_real_escape_string($this->db->link, $data['body'])    ;
        $price       = mysqli_real_escape_string($this->db->link, $data['price']);
        $type        = mysqli_real_escape_string($this->db->link, $data['type']);



        $permited  = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $file['images']['name'];
        $file_size = $file['images']['size'];
        $file_temp = $file['images']['tmp_name'];

        $div            = explode('.', $file_name);
        $file_ext       = strtolower(end($div));
        $unique_image   = substr(md5(time()), 0, 10).'.'.$file_ext;
        $uploaded_image = "uploads/".$unique_image;

        //Field empty logic
        if($productName == "" || $catId =="" || $brandId =="" || $body == "" || $price ==""|| $type ==""){
            $msg = "<span class='error'>Field must not empty</span>";
            return $msg;

        }
        else
        {   
            if(!empty($file_name))
                {

                    if ($file_size >1048567) 
                    {
                        echo "<span class='error'>Image Size should be less then 1MB!</span>";
                    }
                    elseif (in_array($file_ext, $permited) === false) 
                    {
                        echo "<span class='error'>You can upload only:-"
                        .implode(', ', $permited)."</span>";
                    }   
                    else
                    {
        
                        move_uploaded_file($file_temp, $uploaded_image);

                        $query = "UPDATE tbl_product
                                        SET
                                        productName = '$productName',
                                        catId       = '$catId',
                                        brandId     = '$brandId',
                                        body        = '$body',

                                        price       = '$price',
                                        images      = '$uploaded_image',
                                        type        = '$type'

                                        WHERE productId = '$id' ";
        
                        $product_updated = $this->db->update($query);
        
                        if($product_updated)
                            {
                                $msg = "<span class='success'>Product updated successfully.</span>";
                                return $msg;
                            } else {
                            $msg = "<span class='error'>Product not updated successfully.</span>";
                            return $msg;
                            }
                    } 
                }
            else
                {

                    $query = "UPDATE tbl_product
                                    SET
                                    productName = '$productName',
                                    catId       = '$catId',
                                    brandId     = '$brandId',
                                    body        = '$body',
                                    price       = '$price',
                                    type        = '$type'

                                WHERE productId = '$id' ";
    
                    $product_updated = $this->db->update($query);
    
                    if($product_updated)
                        {
                            $msg = "<span class='success'>Product updated successfully.</span>";
                            return $msg;
                        } else {
                        $msg = "<span class='error'>Product not updated successfully.</span>";
                        return $msg;
                        }
                }
        }
    }

    public function productDelete($id)
    {
        $query = "SELECT * FROM tbl_product where productId = '$id'";
        $getData = $this->db->select($query);
        if($getData)
        {
            while($result = $getData->fetch_assoc())
            {
                $imglink = $result['images'];
                unlink($imglink);
            }
        }

        $delquery = "DELETE FROM tbl_product WHERE productId = '$id'";
        $delData = $this->db->delete($delquery);

        if($delData){
            $msg = "<span class='success'>Product successfully deleted.</span>";
            return $msg;
        }else{
            $msg = "<span class='error'>product not deleted!.</span>";
            return $msg;
        }
        
    }

    public function getFeaturedProduct()
    {
        $query = "SELECT * FROM tbl_product where type='0' order by productId desc LIMIT 4";
        $result = $this->db->select($query);
        return $result;
    }

    public function getNewProduct()
    {
        $query = "SELECT * FROM tbl_product order by productId desc LIMIT 4";
        $result = $this->db->select($query);
        return $result;
    }

    public function getSingleProduct($id)
    {
        $query = "SELECT tbl_product.*, tbl_category.catName, tbl_brand.brandName
        FROM tbl_product 

        INNER JOIN tbl_category ON tbl_product.catId = tbl_category.catId

        INNER JOIN tbl_brand ON tbl_product.brandId = tbl_brand.brandId AND tbl_product.productId = '$id' ";
         
        $result = $this->db->select($query);
        return $result;
    }

    public function latestFromIphone()
    {
        $query = "SELECT * FROM tbl_product where brandId = '3' order by productId desc LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function latestFromSamsung()
    {
        $query = "SELECT * FROM tbl_product where brandId = '2' order by productId desc LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function latestFromAcer()
    {
        $query = "SELECT * FROM tbl_product where brandId = '1' order by productId desc LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }
    public function latestFromCanon()
    {
        $query = "SELECT * FROM tbl_product where brandId = '5' order by productId desc LIMIT 1";
        $result = $this->db->select($query);
        return $result;
    }

    public function productByCat($id)
    {
        $catId  = mysqli_real_escape_string($this->db->link, $id);

        $query  = "SELECT * FROM tbl_product WHERE catId = '$catId'";
        $result = $this->db->select($query);
        return $result;
    }
}

?>