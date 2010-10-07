<?php

class Kizano_View_Scripts implements Countable{

	protected $_data;

	public function __construct(){$this->_data = array();}
	public function __toString(){
		return join(null, $this->__toArray());
	}public function toString(){return $this->__toString();}
	
	public function __toArray(){
		return array_map(array($this, '_scriptify'), array_keys($this->_data));
	}public function toArray(){return $this->__toArray();}

	public function count(){return count($this->_data);}
	public function getData(){return $this->_data;}
	public function prependScript($name){array_unshift($this->_data, $name); return $this->_data;}
	public function appendScript($name){return $this->_data[] = $name;}
	protected function _scriptify($index){
		return "\t\t<script type='text/javascript' src='{$this->_data[$index]}'></script>\n";
	}
}

