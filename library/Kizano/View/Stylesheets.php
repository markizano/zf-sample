<?php

class Kizano_View_Stylesheets implements Countable{

	protected $_data;

	public function __construct(){$this->_data = array();}
	public function __toString(){
		$array = $this->__toArray();
		return join(null, $array);
	}public function toString(){return $this->__toString();}
	
	public function __toArray(){
		$result = array();
		foreach($this->_data as $i => $data){
			$result[] = $this->_linkify($i);
		}
		return (array)$result;
#		return array_map(array($this, '_linkify'), array_keys($this->_data));
	}public function toArray(){return $this->__toArray();}

	public function count(){return count($this->_data);}
	public function getData(){return $this->_data;}
	public function prependStyleSheet($name){if(empty($this->_data)) $this->_data[] = $name; else array_unshift($this->_data, $name);return $this->_data;}
	public function appendStyleSheet($name){return $this->_data[] = $name;}
	protected function _linkify($index){
		return "\t\t<link rel='stylesheet' type='text/css' href='{$this->_data[$index]}' />\n";
	}
}

