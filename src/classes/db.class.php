<?php

/**
 * Maneja las funciones relacionadas a la base de datos
 */
class db
{
	private $pdo;
	private $is_conected;
	private $connection_info = [
		"db_host" => "localhost",
		"db_name" => "new_library",
		"db_user" => "root",
		"db_password" => "",
		"options" => [
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
			'charset'=>'utf8mb4'
		],
	];

	/**
	 * Inicializa la conexión con la base de datos
	 */
	private function __connect()
	{
		$config = "mysql:host=" . $this->connection_info['db_host'] . ";dbname=" . $this->connection_info['db_name'];

		try {

			$this->pdo = new PDO(
				$config,
				$this->connection_info['db_user'],
				$this->connection_info['db_password'],
				$this->connection_info['options']
			);
			$this->pdo->exec("SET NAMES utf8mb4");

			$this->is_conected = True;

		} catch (Throwable $e) {

			$this->is_conected = False;

		}
	}

	/**
	 * Verifica si `$table_name` existe en la base de datos
	 * 
	 * @param $table_name El nombre de la tabla a validar su existencia
	 * @return bool
	 */
	private function table_exists($table_name)
	{

		if (!$this->is_conected)
			return False;

		$sql = "SHOW TABLES LIKE '$table_name'";
		$sth = $this->pdo->prepare($sql);

		$sth->execute();
		$result = $sth->rowCount();

		return $result > 0 ? True : False;
	}

	/**
	 * Importa un archivo sql a la base de datos.
	 * Si no se especifica un parámetro, por defecto se usará el fichero `default.sql`
	 * 
	 * @param $file_name Nombre del archivo a importar
	 */
	private function create_tables(string $file_name = "default")
	{

		if (!$this->is_conected)
			return;

		$path = "src/data/$file_name.sql";

		if (!file_exists($path))
			echo "El archivo no existe";

		$sql = file_get_contents($path);
		$sth = $this->pdo->prepare($sql);

		$sth->execute();
	}

	/**
	 * Inicializa la conexión con la base de datos
	 */
	public function __construct()
	{
		# Se conecta a la base de datos
		$this->__connect();

		# Si la tabla 'books' no existe, crea las tablas de la base de datos
		if (!$this->table_exists('books'))
			$this->create_tables();

	}

	/**
	 * Obtiene información de la base de datos
	 * 
	 * @param string $sql Sentencia sql a ejecutar
	 * @param array $params Array con los valores a bindear 
	 * 
	 * @return mixed 
	 */
	public function get_results(string $sql, mixed $params = null): mixed
	{
		$sth = $this->pdo->prepare($sql);

		if (gettype($params) === "array" && !empty($params)) {
			foreach ($params as $key => $param) {
				$sth->bindParam("$key", $param);
			}
		}

		$sth->execute();
		$result = $sth->fetchAll();

		return $result;
	}
}