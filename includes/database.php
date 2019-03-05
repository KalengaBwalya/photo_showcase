<?php
/**
 * Created by PhpStorm.
 * User: Kalenga
 * Date: 19/03/05
 * Time: 10:38
 */

require_once("config.php");

class MySQLDatabase {

    private $connection;

    public function __construct() {
        $this->open_connection();
    }

    public function open_connection(){
        $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
        if(!$this->connection){
            die("DATABASE CONNECTION FAILED ".mysqli_error($this->connection));
        } else {
            $db_select = mysqli_select_db($this->connection, DB_NAME);
            if(!$db_select){
                die("DATABASE SELECTION FAILED ".mysqli_error($this->connection));
            }
        }
    }

    public function  close_connection(){
        if(isset($this->connection)){
            mysqli_close($this->connection);
        }
        unset($this->connection);
    }

    public function query($sql){
        $result = mysqli_query($sql, $this->connection);
        $this->confirm_query($result);
        return $result;
    }

    public function mysql_prep ($value) {
        $magic_quotes_active = get_magic_quotes_gpc();
        $new_enough_php = function_exists("mysqli_real_escape_string"); //i.e. PHP >= v4.3.0
        if($new_enough_php) { //PHP v4.3.0 or higher
            // undo any magic quote effects so mysqli_real_escape_string can do the work
            if($magic_quotes_active) {
                $value = stripslashes($value);
            }
            $value = mysqli_real_escape_string($value);
        } else { // before PHP v4.3.0
            //if magic quotes aren't already an the add slashes manually
            if(!$magic_quotes_active) {
                $value = addslashes($value);
            }
            //if magic quotes are active then the slashes already exist
        }
        return $value;
    }

    private function confirm_query($result){
        if(!$result){
            die("DATABASE QUERY FAILED:".mysqli_error($this->connection));
        }
    }
}

$database = new MySQLDatabase();
$db =& $database;

