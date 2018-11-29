<?php
define('RDBROOT', dirname(dirname(__FILE__)));
include_once RDBROOT . "/db/AbsConnection.class.php";
include_once RDBROOT . "/db/Error.class.php";

/**
 * Database connection class
 * @author Adler Brediks Medrado
 * @email adler@neshertech.net
 * @copyright 2005 - Nesher Technologies - www.neshertech.net
 * @date 30/11/2005
 */
class MySQLConnection extends AbsConnection {

	private $connection;
	private $username;
	private $password;
	private $dsn;
	protected static $_instance = array();


	/**
	 * To connect to another database, change the "mysql" in the dsn attribute to
	 * the database you want.
	 * for example: pgsql:dbname...
	 */
	public function __construct($dbname, $host, $username, $password) {
		$this->dsn = "mysql:dbname=$dbname;host=$host";
		$this->username = $username;
		$this->password = $password;
	}

	public function setConnection($conn) {
		$this->connection = $conn;
	}

	public function getConnection() {
		return $this->connection;
	}

	public static function getInstance($db)
    {
      if(!isset(self::$_instance[$db])) {
        self::$_instance[$db] = new MySQLConnection(TclConfig::config($db. '.dbname'),
        								  TclConfig::config($db. '.host'),
        								  TclConfig::config($db. '.username'),
        								  TclConfig::config($db. '.password'));
		self::$_instance[$db]->connect();
      }
      return self::$_instance[$db];
    }

	/**
	 * Make a connection with a database using PDO Object.
	 *
	 */
	public function connect() {
		try {
			$pdoConnect = new PDO($this->dsn, $this->username, $this->password);
			//$pdoConnect->exec("set time_zone = '+5:30'");
			$this->connection = $pdoConnect;
		} catch (PDOException $e) {
			throw new Exception("Connection::connect(): ".$e->getMessage());
			//die("Error connecting database: Connection::connect(): ".$e->getMessage());
		}
	}
	/**
	 * Execute a DML
	 *
	 * @param String $query
	 */
	public function executeDML($query) {
		if (!$this->getConnection()->query($query)) {
			throw new ErrorCl($this->getConnection()->errorInfo());
		} else {
			if($this->getConnection()->lastInsertId())
				return $this->getConnection()->lastInsertId();
			else
				return true;
		}
	}
	/**
	 * Execute a query
	 *
	 * @param String $query
	 * @return PDO ResultSet Object
	 */
	public function executeQuery($query) {
		$rs = null;
		if ($stmt = $this->getConnection()->prepare($query)) {
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			if ($this->executePreparedStatement($stmt, $rs)) {
				return $rs;
			}
		} else {
			throw new ErrorCl($this->getConnection()->errorInfo());
		}
	}

	/**
	 * Execute a prepared statement
	 * it is used in executeQuery method
	 *
	 * @param PDOStatement Object $stmt
	 * @param Array $row
	 * @return boolean
	 */
	private function executePreparedStatement($stmt, & $row = null) {
		$boReturn = false;
		if ($stmt->execute()) {
			if ($row = $stmt->fetchAll()) {
				$boReturn = true;
			} else {
				$boReturn = false;
			}
		} else {
			$boReturn = false;
		}
		return $boReturn;
	}

	/**
	 * Init a PDO Transaction
	 */
	public function beginTransaction() {
		if (!$this->getConnection()->beginTransaction()) {
			throw new ErrorCl($this->getConnection()->errorInfo());
		}
	}
	/**
	 * Commit a transaction
	 *
	 */
	public function commit() {
		if (!$this->getConnection()->commit()) {
			throw new ErrorCl($this->getConnection()->errorInfo());
		}
	}
	/**
	 * Rollback a transaction
	 *
	 */
	public function rollback() {
		if (!$this->getConnection()->rollback()) {
			throw new ErrorCl($this->getConnection()->errorInfo());
		}
	}
}
?>
