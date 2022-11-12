<?php
namespace RIS\hl7\v_2_3_1;
/*
 * ORM
 * @author hariansy4h@gmail.com
 */
 
use RIS\hl7\Connection;
use RIS\hl7\Message;
use RIS\hl7\v_2_3_1\MSH;
use RIS\hl7\v_2_3_1\PID;
use RIS\hl7\v_2_3_1\PV1;
use RIS\hl7\v_2_3_1\ORC;
use RIS\hl7\v_2_3_1\OBR;
use RIS\hl7\v_2_3_1\ZDS;
 
class ORM extends Message {
	private $hl7conn;
	private $message;
	
	public function __construct() {
		$this->message = new Message();
		$this->message->addSegment("MSH", new MSH());
		$this->message->addSegment("PID", new PID());
		$this->message->addSegment("PV1", new PV1());
		$this->message->addSegment("ORC", new ORC());
		$this->message->addSegment("OBR", new OBR());
		$this->message->addSegment("ZDS", new ZDS());
		
		$this->hl7conn = new Connection("172.16.21.99");

		$this->initMSH();
	}
	
	private function initMSH() {
		$msh = $this->message->getSegment("MSH");
		$msh->set("MessageType", "ORM^O01");
		$msh->set("SendingApplication", "PINISI");
		$msh->set("SendingFacility", "PINISI_SERVICE");
		$msh->set("ReceivingApplication", "PINISI");
		$msh->set("ReceivingFacility", "PINISI_SERVICE");
		$msh->set("MessageDateAndTime", date('YmdHi'));
		$msh->set("MessageControlID", 0);
		$msh->set("ProcessingID", "P");
	}
	
	public function getSegment($name) {
		$segment = $this->message->getSegment($name);
		if(isset($segment)) return $segment;
		return null;
	}
	
	public function send() {
		try {
			$this->hl7conn->connect();
			$this->hl7conn->send($this->message->toString());
		} catch(\Exception $e) {
			echo $e->getMessage()."<br/>";
		} finally {
			$this->hl7conn->close();
		}
	}
}