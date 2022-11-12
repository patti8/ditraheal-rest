<?php
namespace RIS\hl7;
/*
 * Message
 * @author hariansy4h@gmail.com
 */
 
class Message {
    private $version;
    private $delimiter;
    
    private $segments = [];
	
	public function __construct($version = "2.3.1", $delimiter = "|") {
	    $this->version = $version;
	    $this->delimiter = $delimiter;
	}
		
	public function addSegment($name, $segment) {
		$this->segments[$name] = $segment;
	}
	
	public function toString($crlf = "\r\n") {
		$msg = "";
		foreach($this->segments as $key => $val) {
	       if(is_array($val)) {
	           foreach($val as $v) {
	               $msg .= $v->toString().$crlf;
	           }
		   } else {
		      $msg .= $val->toString().$crlf;
		   }
		}
		
		return $msg;
	}
	
	// in process develop 
	public function parse($string, $crlf = "\r\n") {	    
	    $segments = explode($crlf, $string);	    
	    foreach($segments as $seg) {
	        $cmds = explode($this->delimiter, $seg);	        
	        $o = $this->createSegment($cmds[0], $seg);
	        if($o) {
    	        if(isset($this->segments[$cmds[0]])) {	            
    	            $s = $this->segments[$cmds[0]];
    	            if(is_array($s)) {    	                
    	                array_push($s, $o);
    	                $this->segments[$cmds[0]] = $s;
    	            } else {
    	                $a = [];
    	                array_push($a, $s, $o);
    	                $this->segments[$cmds[0]] = $a;
    	            }    	            
    	        } else {
    	            $this->addSegment($cmds[0], $o);
    	        }
	        }
	    }
	}
	
	private function createSegment($name, $parse) {	    
	    $ver = "v_".str_replace(".", "_", $this->version);
	    $fn = "hl7\\".$ver."\\".$name;
	    if(file_exists($fn.".php")) {
	        if(!isset($this->segments[$name])) require_once $fn.".php";
	        $fn = "\\".str_replace("\\\\", "\\", $fn);
	        $s = new $fn();
	        $s->parse($parse);
	        return $s;
	    }
	        
	    return null;
	}
	
	public function getSegment($name) {
		return isset($this->segments[$name]) ? $this->segments[$name] : null;
	}
	
	public function getSegments() {
	    return $this->segments;
	}
}