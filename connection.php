

<?php


class Database
{

    private  $Server = "localhost";
    private  $dbname  = "botola_mabghribiya";
    private $root = "root";
    private $password = "";

    public $conn;

    public function getConnection()
    {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host={$this->Server};dbname={$this->dbname};charset=utf8", $this->root, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Eroor" . $e->getMessage());
        }

        return $this->conn;
    }
}





?>