<?php 
    $filepath = realpath(dirname(__FILE__));
    include_once($filepath.'/../lib/Database.php');
    include_once($filepath.'/../helper/Format.php');
?>

<?php 
class Customer{
    private $db;
    private $fm;

    function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

    public function customerRegistration($data)
    {
        $name    	= $this->fm->validation($data['name']);
        $address    = $this->fm->validation($data['address']);
        $city    	= $this->fm->validation($data['city']);
        $zip     	= $this->fm->validation($data['zip']);
        $email   	= $this->fm->validation($data['email']);
        $country 	= $this->fm->validation($data['country']);
        $phone   	= $this->fm->validation($data['phone']);
        $pass  		= $this->fm->validation($data['pass']);

        $name 		= mysqli_real_escape_string($this->db->link, $name);
        $address 	= mysqli_real_escape_string($this->db->link, $address);
        $city 		= mysqli_real_escape_string($this->db->link, $city);
        $zip 		= mysqli_real_escape_string($this->db->link, $zip);
        $email 		= mysqli_real_escape_string($this->db->link, $email);
        $country 	= mysqli_real_escape_string($this->db->link, $country);
        $phone 		= mysqli_real_escape_string($this->db->link, $phone);
        $pass 		= mysqli_real_escape_string($this->db->link, md5($pass));


        //Field empty logic
        if ($name == "" || $address == "" || $city == "" || $zip == "" || $email == "" || $country == "" || $phone == "" || $pass == "") {
            $msg = "<span class='error'>Fields must not be empty!</span>";
            return $msg;
        }
        $mailquery = "SELECT * FROM tbl_customer WHERE email = '$email' LIMIT 1";
        $mailchk = $this->db->select($mailquery);

        if ($mailchk != false) {
            $msg = "<span class='error'>Email already Exist!</span>";
            return $msg;
        }
        else{
            $query = "INSERT INTO tbl_customer(name, address, city, country, zip, phone, email, pass)
            VALUES('$name','$address','$city','$country','$zip','$phone','$email', '$pass')";

            $cms_insert = $this->db->insert($query);

            if($cms_insert){
                $msg = "<span class='success'>You have been successfully registered</span>";
                return $msg;
            } else {
                $msg = "<span class='error'>Something went wrong !</span>";
                return $msg;
            }
        }
    }

    public function customerLogin($data)
    {
        $email 	= $this->fm->validation($data['email']);
        $pass  	= $this->fm->validation($data['pass']);

        $email 	= mysqli_real_escape_string($this->db->link, $email);
        $pass 	= mysqli_real_escape_string($this->db->link, md5($pass));

        if(empty($email) || empty($pass))
        {
            $msg = "<span class='error'>Fields must not be empty!</span>";
            return $msg;
        }

        $query = "SELECT * FROM tbl_customer WHERE email ='$email' AND pass ='$pass'";
        $result = $this->db->select($query);

        if($result != false)
        {
            $value = $result->fetch_assoc();
            Session::set("custLogin",true);
            Session::set("cmrId",$value['id']);
            Session::set("cmrName",$value['name']);
            header("Location:cart.php");
        }else{
            $msg = "<span class='error'>Email or password not match !</span>";
            return $msg;
        }

    }
    public function getCustomerData($id)
    {
        $query = "SELECT * FROM tbl_customer where id = '$id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function customerUpdate($data, $cmrId)
    {
        $name    	= $this->fm->validation($data['name']);
        $address    = $this->fm->validation($data['address']);
        $city    	= $this->fm->validation($data['city']);
        $zip     	= $this->fm->validation($data['zip']);
        $email   	= $this->fm->validation($data['email']);
        $country 	= $this->fm->validation($data['country']);
        $phone   	= $this->fm->validation($data['phone']);

        $name 		= mysqli_real_escape_string($this->db->link, $name);
        $address 	= mysqli_real_escape_string($this->db->link, $address);
        $city 		= mysqli_real_escape_string($this->db->link, $city);
        $zip 		= mysqli_real_escape_string($this->db->link, $zip);
        $email 		= mysqli_real_escape_string($this->db->link, $email);
        $country 	= mysqli_real_escape_string($this->db->link, $country);
        $phone 		= mysqli_real_escape_string($this->db->link, $phone);


        //Field empty logic
        if ($name == "" || $address == "" || $city == "" || $zip == "" || $email == "" || $country == "" || $phone == "") {
            $msg = "<span class='error'>Fields must not be empty!</span>";
            return $msg;
        }else{
            $query = "UPDATE tbl_customer
                    SET
                    name     = '$name',
                    address  = '$address',
                    city     = '$city',
                    zip      = '$zip',
                    email    = '$email',
                    country  = '$country',
                    phone    = '$phone'
                    WHERE id = '$cmrId' ";
    
            $product_updated = $this->db->update($query);
    
            if($product_updated)
            {
                $msg = "<span class='success'>Customer Data updated successfully.</span>";
                    return $msg;
            } else {
                $msg = "<span class='error'>Customer not updated successfully.</span>";
                return $msg;
            }
        }
    }

}
?>