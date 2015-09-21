<?php
/*ARCHIVO PARA GESTIONAR CONEXION A LA BD y acciones guardar,modificar 
y eliminar Implementando PDO*/
class Esqueletor {
	private $hostConnection = "localhost";
	private $dbConnection = "myBD";
	private $pswConnect = "pswd";
	private $userConnection = "user";
	private $myConnect;

	//array con opciones de configuracion mysql para pdo.
	private $pdoOptions = array(
		//PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
		1002 => 'SET NAMES utf8',
	);

	function _Construct( $hostConnection, $userConnection, $pswConnect, $dbConnection) {
		$this->hostConnection = $hostConnection;
		$this->userConnection = $userConnection;
		$this->pswConnect = $pswConnect;
		$this->dbConnection = $dbConnection;
	}
        
	function setPassword( $password ) {
		$this->pswConnect = $password;
	}
	
	function setServer( $server ) {
		$this->hostConnection = $server;
	}
	
	function setDb( $dataBase ) {
		$this->dbConnection =$dataBase;
	}
	
	function setUser( $user ) {
		$this->userConnection  = $user;
	}
	
	function getPassword( ) {
		return $this->pswConnect;
	}
	
	function getServer( ) {
		return $this->hostConnection;
	}
	
	function getDb( ) {
		return $this->dbConnection;
	}
	
	function getUser( ) {
		return $this->userConnection;
	}	
	
	function connect( ) {
		$this->myConnect = new PDO(
                    'mysql:host='.$this->hostConnection.';dbname='.$this->dbConnection,
                    $this->userConnection, 
                    $this->pswConnect, 
                    $this->pdoOptions 
                );
                $this->myConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	
	function disconnect() {
		$this->myConnect = null;
	}
	/**
	 * run query SQL
	 * @param  string $strSql  String of the query
	 * @param  array $arrayParams array of clausules
	 * @return array array with query result.
	 */
	
	function queryFree( $strSql, $arrayParams ){
		//This function returns an array with the query result
		try {
			$stament = $this->myConnect->prepare($strSql);
			foreach( $arrayParams as $key=> &$value ){
				$stament->bindParam($key, $value);
			}
			$stament->execute();
			return $stament->fetchAll(PDO::FETCH_ASSOC);
		} catch ( PDOException $Ex) {
			return $Ex->getMessage();
		}
	}
	
	function queryInsert( $table, $arrayElements ){
		//this function require on array key's  and columns of table having the same name
		//return true or false
		try {
			$arrayColumns = array();
			$arrayValues = array();
			foreach( $arrayElements as $key => &$value ) {
				array_push($arrayColumns,$key);
				array_push($arrayValues,":".$key);
			}
			$strColumns = implode(",", $arrayColumns);
			$strValues = implode(",", $arrayValues);
			$strSql ="INSERT INTO ".$table ."(".$strColumns.") VALUES (" . $strValues . ")";
            // print_r($arrayElements);
			$stament = $this->myConnect->prepare($strSql);
			foreach( $arrayElements as $key => &$value ) {
				$stament->bindParam( $key, $value);
			}
			return $stament->execute();
		} catch ( PDOException $Ex) {
			return $Ex->getMessage();
		}  
	}
	
	function querySelect( $table, $strColumns, $strClause, $arrayParams ) {
		//This function returns an array with the query result
		try {
			$strSql = "SELECT " . $strColumns . " FROM " . $table . " WHERE " . $strClause;
			$stament = $this->myConnect->prepare($strSql);
			//echo $strSql;
			//print_r($arrayParams);
			foreach( $arrayParams as $key => &$value ){
				$stament->bindParam($key, $value);
			}
			$stament->execute();
			return $stament->fetchAll(PDO::FETCH_ASSOC);
		} catch ( PDOException $Ex) {
			return $Ex->getMessage();
		}
	}
	
	function queryDelete( $table, $strClause, $arrayParams ) {
		//This function returns row count.
		try {
			$strSql = "DELETE FROM " . $table . " WHERE " . $strClause;
			$stament = $this->myConnect->prepare( $strSql );
			foreach( $arrayParams as $key => &$value ){
				$stament->bindParam($key, $value);
			}
			$stament->execute();
			return $stament->rowCount();
		} catch ( PDOException $Ex) {
			return $Ex->getMessage();
		}
	}
	
	function queryUpdate( $table, $strSet, $strClause, $arrayParams ) {
		//this function returns  row count.
		try {
			$strSql = "UPDATE " . $table . " SET " . $strSet . " WHERE " .$strClause;
			$stament = $this->myConnect->prepare( $strSql );
			foreach( $arrayParams as $key => &$value ){
				$stament->bindParam($key, $value);
			}
			$stament->execute();
			return $stament->rowCount();
		} catch ( PDOException $Ex) {
			return $Ex->getMessage();
		}
	}	
}
?>