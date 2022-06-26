<?php

/**
 * PDO Database class
 * Connect to database.
 */
class Database
{
    // constants set in 'config/config.php'
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    /**
     * Sets data source name (DSN). PDO needs it in order to connect to database.
     * Creates PDO object and assigns it to 'dbh' attribute of Database object (database handler).
     */
    public function __construct()
    {
        // Sets DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        // Create PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    /**
     * Shortcut method that returns string value of the last id that was inserted into database
     * @return false|string ID of the previous element inserted into database
     */
    public function getId(){
        return $this->dbh->lastInsertId();
    }

    /**
     * Prepares SQL query passed as a parameter
     * @param string $sql SQL query to be prepared
     * @return void
     */
    public function query(string $sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Binds values to respective parameters in the SQL query,
     * automatically choosing the parameter's type between 'int', 'bool'
     * and the default 'str'
     * @param string $param The name of the parameter in the query
     * @param string|int|float $value The value to be bound to the parameter in the query
     * @param int|null $type Type of value to be bound to the parameter, as per PDO 'bindValue' method
     * @return void
     */
    public function bind(string $param, $value, int $type = null)
    {
        if (is_null($type)) {
            switch (true) {
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

    /**
     * Returns the results of the executed SQL query
     * @return array
     */
    public function resultSet(): array
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Shortcut method that executes the prepared SQL statement
     * @return bool
     */
    public function execute(): bool
    {
        return $this->stmt->execute();
    }

}