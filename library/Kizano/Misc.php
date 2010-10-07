<?php

class Kizano_Misc{
	function iif($condition, $true, $false){return $condition? $true: $false;}
	function _isset($var, $default = null){return iif(isset($var), $var, $default);}

	function _null(){
		$args = func_get_args();
		if(count($args))
			foreach($args as &$var)
				$var = (unset)null;
		return $args;
	}
}

