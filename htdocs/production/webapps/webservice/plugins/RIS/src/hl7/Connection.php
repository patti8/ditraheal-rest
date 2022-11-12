<?php 
namespace RIS\hl7;

/**
 * Connection Class for connect to HL7 Server
 * @author hariansy4h@gmail.com
 */

class Connection {
	private $host;
	private $port;
	private $socket;
	private $conn;
	
	CONST MESSAGE_PREFIX = "\013";
	CONST MESSAGE_SUFFIX = "\034\015";
	
	public function __construct($host = "localhost", $port = 2575) {	    
		if(!isset($host) || !isset($port)) {
			throw new \Exception("Can't null host or port\n");
		}		
		
		$this->host = $host;
		$this->port = $port;
	}
	
	public function connect() {
		// create socket
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		// create connection;
		$this->conn = socket_connect($this->socket, $this->host, $this->port);
	}
	
	public function send($message) {
		socket_write($this->socket, Connection::MESSAGE_PREFIX.$message.Connection::MESSAGE_SUFFIX);
		
		$data = "";
        while(($buf = socket_read($this->socket, 256, PHP_BINARY_READ)) !== false) {
            $data .= $buf;
            if(preg_match("/" . Connection::MESSAGE_SUFFIX . "$/", $buf))
                break;
		}
		
        // Remove message prefix and suffix
        $data = preg_replace("/^" . Connection::MESSAGE_PREFIX . "/", "", $data);
        $data = preg_replace("/" . Connection::MESSAGE_SUFFIX . "$/", "", $data);
		return $data;
	}
	
	public function close() {
	    socket_close($this->socket);
	}	
}