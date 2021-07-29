<?php 
    $filepath = realpath(dirname(__FILE__));
    include_once($filepath.'/../lib/Database.php');
    include_once($filepath.'/../helper/Format.php');
?>

<?php
 class Category{

    private $db;
    private $fm;

    function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function catInsert($catName)
    {
        $catName = $this->fm->validation($catName);
        $catName = mysqli_real_escape_string($this->db->link, $catName);

        if(empty($catName)){
            $msg = "<span class='error'>Category name must not empty</span>";
            return $msg;
        } else {
            $query = "INSERT INTO tbl_category(catName) VALUES('$catName')";

            $result = $this->db->insert($query);

            if($result){
                $msg = "<span class='success'>Category inserted successfully.</span>";
                return $msg;
            } else {
                $msg = "<span class='error'>Category not inserted successfully.</span>";
                return $msg;
            }
        }
    }

    public function getAllCat()
    {
        $query = "SELECT * FROM tbl_category ORDER BY catId DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function getCatById($id)
    {
        $query = "SELECT * FROM tbl_category where catId = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function catUpdate($catName, $id)
    {
        $catName = $this->fm->validation($catName);
        $catName = mysqli_real_escape_string($this->db->link, $catName);

        $id = mysqli_real_escape_string($this->db->link, $id);

        if(empty($catName)){
            $msg = "<span class='error'>Category name must not empty</span>";
            return $msg;
        }else{
            $query = "UPDATE tbl_category SET catName = '$catName' WHERE catId = '$id'";
            $update_row = $this->db->update($query);
            
            if($update_row){
                $msg = "<span class='success'>Category updated successfully.</span>";
                return $msg;
            }else{
                $msg = "<span class='error'>Category not updated!.</span>";
                return $msg;
            }
        }
    }

    public function delCatById($id)
    {
        $query = "DELETE FROM tbl_category WHERE catId = '$id'";

        $del = $this->db->delete($query);

        if($del){
            $msg = "<span class='success'>Category successfully deleted.</span>";
            return $msg;
        }else{
            $msg = "<span class='error'>Category not deleted!.</span>";
            return $msg;
        }
    }
 }
?>