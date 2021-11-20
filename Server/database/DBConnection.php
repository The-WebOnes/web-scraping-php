<?php	
class DBConnection
{
  private $serverName = "localhost";
  private $userName = "root";
  private $password = "";
  private $databaseName = "textos2";
  private $connection;

  public function __construct()
  {
    try {
      $connectionString = "mysql:host=".$this->serverName.";dbname=".$this->databaseName;
      $this->connection = new PDO($connectionString, $this->userName, $this->password);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exception) {
      echo "Connection failed: " . $exception->getMessage();
    }
  }

  public function get_connection()
  {
    return $this->connection;
  }

}
?>