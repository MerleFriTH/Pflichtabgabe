<?php

/**
 * Description of MySQLi
 * @author MBlock
 * mit Ihrer Erlaubnis übernommen
 */
class My_MySQLi {
	
	private $_host = null;
	private $_username = null;
	private $_password = null;
	private $_database = null;
	private $_charset = null;
	private $_port = 3306;
	
	private $_isConnected = false;
	private $_connection = null;
	
	public function __construct($host, $username, $password, $database, $charset = "UTF8") {
		$this->setHost($host);
		$this->setUsername($username);
		$this->setPassword($password);
		$this->setDatabase($database);
		$this->setCharset($charset);
	}
	
	protected function connect() {
		// Wenn bereits eine Verbindung besteht soll keine neue Verbindung erstellt werden.
		if (!is_null($this->_connection)) {
			return;
		}
		
		// Initialisiert MySQLi und gibt eine Ressource des Moduls zurück.
		$this->_connection = mysqli_init();
		
		// @ Verhindert Fehlermeldungen die mit Funktionsaufruf möglicherweise erzeugt werden.
		$this->_isConnected = @mysqli_real_connect($this->_connection, $this->_host, $this->_username, $this->_password, $this->_database, $this->_port);
		
		// Werfen einer Fehlermeldung wenn keine Verbindung hergestellt werden konnte.
		if (!$this->_isConnected) {
			throw new Exception("Fehler beim DB-Verbindungsaufbau.");
		}
		
		// Anwendung der Zeichenkodierung für die Verbindung.
		mysqli_set_charset($this->_connection, $this->_charset);
	}
	
	public function query($query) {
		$this->connect();
		
		return mysqli_query($this->_connection, $query);
	}
	
	/**
	 * 
	 * @param String $sql
	 * @return mysqli_stmt Statement
	 */
	public function prepare($sql) {
		$this->connect();
		
		return mysqli_prepare($this->_connection, $sql);
	}

	public function setHost($host) {
		$this->_host = $host;
	}

	public function setUsername($username) {
		$this->_username = $username;
	}

	public function setPassword($password) {
		$this->_password = $password;
	}

	public function setDatabase($database) {
		$this->_database = $database;
	}

	public function setCharset($charset) {
		$this->_charset = $charset;
	}

}
