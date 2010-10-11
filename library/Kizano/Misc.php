<?php

/**
 *	Misc functions and routines
 */
class Kizano_Misc{

	/**
	 *	Implements the Visual-Basic style iif function.
	 *	@param	condition	Boolean		The boolean condition to test
	 *	@param	true		Mixed		The value to return if $condition evals to true
	 *	@param	false		Mixed		The value to return if $condition evals to false
	 *	@return				Mixed		The result of the evaulation
	 */
	public static function iif($condition, $true, $false){
		return $condition? $true: $false;
	}

	/**
	 *	Adds a message to the stack to flash the user.
	 *	@param	message		String		A message to add.
	 *	@return	void
	 */
	public static function flash($message){
		$session = Zend_Registry::getInstance()->get('session');
		$session->flash[] = $message;
		return null;
	}

	/**
	 *	Concatenates the messages to flash the user in a string format.
	 *	@return		String		The messages in the flash queue
	 */
	public static function getFlash(){
		$session = Zend_Registry::getInstance()->get('session');
		$result = join(chr(10), (array)$session->flash);
		$session->flash = (array)null;
		return nl2br($result);
	}
}

