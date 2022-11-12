<?php
namespace RIS\hl7;

/**
 * Segment Abstract Class
 * @author hariansy4h@gmail.com
 */
 
abstract class Segment {
	protected $delimiter = "|";
	protected $fields = [];
	
	public function __construct() {
		$this->initFields();
	}
	
	protected abstract function initFields();
	
	public function toString() {
		$data = [];
		foreach($this->fields as $f) {
			//if($f->get("SEQ") === 0) continue; // MSA Segment field
			$data[] =  $f->get("VALUE");
		}
		
		return implode($this->delimiter, $data);
	}
	
	public function getFields() {
		return $this->fields;
	}
	
	public function getFieldByIndex($index) {
		return $this->fields[$index];
	}
	
	public function getFieldByName($name) {
		foreach($this->fields as $f) {
			if($f->get("NAME") == $name) return $f;
		}
		
		return null;
	}
	
	public function setDelimiter($deli) {
		$this->delimiter = $deli;
	}
	
	public function getClassNameWithoutNamespace() {
		$class = explode("\\", get_class($this));
		return $class[count($class) - 1];
	}
	
	public function parse($string) {
		$class = $this->getClassNameWithoutNamespace();
		$segs = explode($this->delimiter, $string);
		if($segs == false) throw new \Exception("Message is not ".$class);
		//echo count($segs)."!=".count($this->fields);
		//if(count($segs) != count($this->fields)) throw new \Exception($class."String not match");
		if($segs[0] != $class) throw new \Exception("Message is not ".$class);		
				
		$idx = -1;
		foreach($this->fields as $f) {
			$idx++;
			if($idx < 1) continue;
			if(isset($segs[$idx])) $f->set("VALUE", $segs[$idx]);
		}
	}
	
	public function toArray() {
		$data = [];
		foreach($this->fields as $f) {
			$data[$f->get("NAME")] = $f->get("VALUE");
		}
		
		return $data;
	}
}