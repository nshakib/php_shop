<?php 
    $filepath = realpath(dirname(__FILE__));
    include_once($filepath.'/../lib/Database.php');
    include_once($filepath.'/../helper/Format.php');
?>

<?php
    class Brand{

    private $db;
    private $fm;

    function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }
    public function brandInsert($brandName)
    {
        $brandName = $this->fm->validation($brandName);
        $brandName = mysqli_real_escape_string($this->db->link, $brandName);

        if(empty($brandName)){
            $msg = "<span class='error'>Brand Name name must not empty</span>";
            return $msg;
        } else {
            $query = "INSERT INTO tbl_brand(brandName) VALUES('$brandName')";

            $brandResult = $this->db->insert($query);

            if($brandResult){
                $msg = "<span class='success'>Brand Name inserted successfully.</span>";
                return $msg;
            } else {
                $msg = "<span class='error'>Brand Name not inserted successfully.</span>";
                return $msg;
            }
        }
    }

    public function getAllBrand()
    {
        $query = "SELECT * FROM tbl_brand ORDER BY brandId DESC";
        $brandResult = $this->db->select($query);
        return $brandResult;
    }
    public function getBrandById($id)
    {
        $query = "SELECT * FROM tbl_brand WHERE brandId = '$id'";
        $brandResult = $this->db->select($query);
        return $brandResult;
    }

    public function brandUpdate($brandName, $id)
    {
        $brandName = $this->fm->validation($brandName);
        $brandName = mysqli_real_escape_string($this->db->link, $brandName);
        $id = mysqli_real_escape_string($this->db->link, $id);

        if(empty($brandName)){
            $msg = "<span class='error'>Brand name must not empty</span>";
            return $msg;
        }else{
            $query = "UPDATE tbl_brand SET brandName = '$brandName' WHERE brandId = '$id'";
            $update_brandName = $this->db->update($query);
            
            if($update_brandName){
                $msg = "<span class='success'>Brand updated successfully.</span>";
                return $msg;
            }else{
                $msg = "<span class='error'>Brand not updated!.</span>";
                return $msg;
            }
        }
    }

    public function delBrandById($id)
    {
        $query = "DELETE FROM tbl_brand WHERE brandId = '$id'";

        $delBrand = $this->db->delete($query);

        if($delBrand){
            $msg = "<span class='success'>Brandsuccessfully deleted.</span>";
            return $msg;
        }else{
            $msg = "<span class='error'>Brandnot deleted!.</span>";
            return $msg;
        }
    }

}