<?
class Database
{
    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;
    private $connection;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function getConnection()
    {
  		return $this->connection;
  	}

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
      try {
  		$this->connection = new PDO('sqlite:database/LTWProj.db');
  		$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		function my_sqlite_regexp($x, $y){
			return (int)preg_match('/'.$y.'/', $x);
		}
		$this->connection->sqliteCreateFunction('regexp','my_sqlite_regexp',2);
  		} catch (PDOException $e) {
     			die($e->getMessage());
  		}
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}
?>
