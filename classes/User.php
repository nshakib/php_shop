<?php 
    $filepath = realpath(dirname(__FILE__));
    include_once($filepath.'/../lib/Database.php');
    include_once($filepath.'/../helper/Format.php');
?>

<?php 
class User{
    private $db;
    private $fm;

    function __construct()
    {
        $this->db = new Database();
        $this->fm = new Format();
    }

}
?>