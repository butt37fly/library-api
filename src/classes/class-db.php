<?php 

/**
 * Maneja las funciones relacionadas a la base de datos
 */
class db
{
	private $pdo;

	/**
	 * Inicializa la conexión con la base de datos
	 */
	public function __construct()
	{
		$db_name = "library";
		$db_user = "root";
		$db_password = "";
		$options = array(
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
		);

		try{

			$this->pdo = new PDO( "mysql:host=localhost;dbname=$db_name", $db_user, $db_password, $options);

		} catch ( \troweable $e){
			
			$this->print_r($e);

		}
	}

	/**
	 * Obtiene información de la base de datos
	 * 
	 * @param string $sql Sentencia sql a ejecutar
	 * @param array $params Array con los valores a bindear 
	 * 
	 * @return mixed 
	 */
	public function get_results( string $sql, mixed $params = null ) : mixed
	{
		$sth = $this->pdo->prepare( $sql ); 

		if( gettype($params) === "array" && ! empty($params) ){
			foreach ($params as $key => $param) {
				$sth->bindParam( "$key", $param );
			}
		}

		$sth->execute();
		$result = $sth->fetchAll();

		return $result;
	}
}