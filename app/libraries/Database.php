<?php
  /*
   * Database()
   *
   * This is the PDO Database class. This handles the connection with the database and the queries. It creates
   * prepared statements.
   * PDO Database class.
   */
  class Database{
    // Set default values to the defined values in config.php.
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbName = DB_NAME;
    
    // Set handling
    private $dbh;
    private $stmt;
    private $error;

    /*
     * __construct()
     *
     * This function creates and returns the database object.
     */
    public function __construct(){
      // Set DSN
      $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbName;
      $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      );
      
      // Create PDO Instance.
      try{
        $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
      } catch(PDOException $e){
        $this->error = $e->getMessage();
        echo $this->error;
      }
    }
    
    /*
     * query()
     *
     * This function prepares the given query.
     *
     * Usage (In the Controller/Model):
     *    $this->db->query('SELECT * FROM users WHERE id = :id');
     */
    public function query($sql){
      $this->stmt = $this->dbh->prepare($sql);
    }
    
    /*
     * bind()
     *
     * This function binds the parameters to the given values.
     *
     * Usage (In the Controller/Model):
     *    $this->db->bind(':id', $userId);
     */
    public function bind($param, $value, $type = NULL){
      if(is_null($type)){
        switch(true){
          case is_int($value):
            $type = PDO::PARAM_INT;
            break;
          case is_bool($value):
            $type = PDO::PARAM_BOOL;
            break;
          case is_null($value):
            $type = PDO::PARAM_NULL;
            break;
          default:
            $type = PDO::PARAM_STR;
        }
      }
      $this->stmt->bindValue($param, $value, $type);
    }
    
    /*
     * execute()
     *
     * This function executes the query with the binded parameters.
     *
     * Usage (In the Controller/Model):
     *    $this->db->execute();
     */
    public function execute(){
      return $this->stmt->execute();
    }
    
    /*
     * fetchAll()
     *
     * This function fetches all objects from the executed query in an array with objects.
     *
     * Usage (In the Controller/Model):
     *    $resultsArray = $this->db->fetchAll();
     */
    public function fetchAll(){
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /*
     * fetchSingle()
     *
     * This function fetches a single object from the executed query as object.
     *
     * Usage (In the Controller/Model):
     *    $user = $this->db->fetchSingle();
     */
    public function fetchSingle(){
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_OBJ);
    }
    
    /*
     * rowCount()
     *
     * This function returns the rowcount of the executed query.
     *
     * Usage (In the Controller/Model):
     *    $rowCount = $this->db->rowCount();
     */
    public function rowCount(){
      return $this->stmt->rowCount();
    }
    
  }