<?php
/**
 *	@Name: ~/library/Kizano/Validate/Valid.php
 *	@Depends: None
 *	@Description: Validation class to verify various types of data
 *	@Notes: Edit with care
 *	
 */

class Kizano_Validate_Valid{
	/**
	 *	Validates an eMail against a regular expression. Credits to Dionyziz@irc.freenode.net#PHP
	 *	@param email	String	The eMail address to validate.
	 *	@return			Mixed	$email on Valid; FALSE On Fail/Error
	 */
	function valideMail($email){
		return (bool)preg_match('/^[a-z0-9%_+.-]+@(([a-z0-9][a-z0-9-]{0,62}(?<!-)\.)*([a-z]{2,4}|museum)|(([0-9]|[1-9][0-9]|(1[0-9][0-9]|2([0-4][0-9]|5[0-5])))\.){3}([0-9]|[1-9][0-9]|(1[0-9][0-9]|2([0-4][0-9]|5[0-5])))(?<!0\.0\.0\.0))$/i', $email)? $email: false;
	}

	/**
	 *	Checks to make sure the deposited string is indeed an IPv4 Address
	 *	@param IP		String. The IP to check
	 *	@return			Boolean. True if VALID IP; False if INVALID IP
	 */
	function is_IP($IP){
		return (bool)preg_match('/(([0-9]|[1-9][0-9]|(1[0-9][0-9]|2([0-4][0-9]|5[0-5])))\.){3}([0-9]|[1-9][0-9]|(1[0-9][0-9]|2([0-4][0-9]|5[0-5])))(?<!0\.0\.0\.0)/', $IP);
	}

	/**
	 *	Verifies a string contains only hexadecimal characters
	 *	@param Hex		String.		The HEX string to test
	 *	@param len		Int.		The number of characters to verify. Can be a range if DELIMIT ","
	 *	@return 		Boolean.	$Hex; FALSE if NOT HEX
	 */
	function is_Hex($Hex, $len = 0){
		return (bool)preg_match('/[a-fA-F0-9]'.($len == 0? '*': "{{$len}}").'/i', $Hex)? $Hex: false;
	}

	/**
	 *	Sanitizes Input for output
	 *	@old-url			http://pastie.org/621895
	 *	@version			2.4
	 *	@param Data			String		The data to sanitize
	 *	@param link			Resource	The MySQL Resource to use for mysql_real_escape_string()
	 *	@param Length		Int			The length of the data to return
	 *	@param FilterType	Int			The FILTER_VAR constant filter type to use
	 *									One of:
	 *			FILTER_SANITIZE_STRING	- Validates the input as a string
	 *			FILTER_VALIDATE_INT		- Validates the input as an integer (Finds digits 0-9)
	 *			FILTER_VALIDATE_FLOAT	- Validates the input as a floating-point integer (Finds digits 0-9 and decimals["."])
	 *			FITLER_VALIDATE_NUMBER	- Validates the input as a number (Finds digits 0-9, decimals["."], and delimiters[","])
	 *			FILTER_VALIDATE_EMAIL	- Validates the input as a valid input
	 *	@param Transform	Boolean		TRUE to change characters to HTML Entities
	 *									FALSE to remove disallowed characters
	 *	@return 			Mixed		A clean version of $Data if it validates properly.
	 *									FALSE if validation fails.
	 */
	define('ALLOWED_HTML', '<p><br><em><strong><b><i><u><span><a>', true);
	define('FILTER_VALIDATE_HEX', 40, true);
	define('FILTER_VALIDATE_NUMBER', 100, true);
	define('FILTER_VALIDATE_COMPARE', 120, true);
	define('FILTER_VALIDATE_ALPHANUMBER', 130, true);
	define('FILTER_VALIDATE_ALPHA', 140, true);
	function Sanitize($Data, $link = null, $Length = 0, $FilterType = FILTER_SANITIZE_STRING, $Transform = false){
		if(function_exists('filter_var')){
			if($FilterType == FILTER_VALIDATE_HEX)
				$result = is_hex($Data, "1,$Length");
			elseif($FilterType == FILTER_VALIDATE_NUMBER)
				$result = preg_match('/^([0-9\\.]+)$/', $Data) != false? (double)$Data: false;
			elseif($FilterType == FILTER_VALDIATE_COMPARE)
				$result = preg_match('/^\s?(=|\<=|\>=|\<|\>|==|\!=)\s?$/', $Data) != false? $Data: false;
			elseif($FilterType == FILTER_VALIDATE_ALPHANUMBER)
				$result = preg_match('/^([A-Za-z0-9\\.\\, ]+)$/i', $Data) != false? (string)$Data: false;
			elseif($FilterType == FILTER_VALIDATE_ALPHA)
				$result = preg_match('/^([A-Za-z]+)$/i', $Data) != false? (string)$Data: false;
			else
				$result = filter_var($Data, $FilterType);
		}else{
			switch($FilterType){
			case FILTER_SANITIZE_STRING:
				$result = preg_match('/^([A-Za-z0-9_\.\,\!\$\#\@\\\/\%\^\s=\+:;]+)$/i', $Data) != false? (string)$Data: 0;
			break;	case FILTER_VALIDATE_INT:
			  $result = preg_match('/^[0-9]*$/', $Data) != false? (int)$Data: false;
			break;	case FILTER_VALIDATE_EMAIL:
				$result = valideMail($Data);
			break;	case FILTER_VALIDATE_HEX:
				$result = is_hex($Data, $Length);
			break;	case FILTER_VALIDATE_FLOAT:
				$result = preg_match('/^[0-9\\.]*$/', $Data) != false? (float)$Data: false;
			break;	case FILTER_VALIDATE_NUMBER:
				$result = preg_match('/^[0-9\\.]*$/', $Data) != false? (double)$Data: false;
			break;	case FILTER_VALDIATE_COMPARE:
				$result = preg_match('/^\s?(=|\<=|\>=|\<|\>|==|\!=)\s?$/', $Data) != false? (string)$Data: false;
			break;	case FILTER_VALIDATE_ALPHANUMBER:
				$result = preg_match('/^([A-Za-z0-9\\.\\, ]+)$/i', $Data) != false? (string)$Data: false;
			break;	case FILTER_VALIDATE_ALPHA:
				$result = preg_match('/^([A-Za-z]+)$/i', $Data) != false? (string)$Data: false;
			break; default:
				$result = $Data;
			}
		}
		if($Length > 0)
			$result = SubStr($result, 0, $Length);
		$result = stripslashes($result);
		$result = strip_tags($result, self::Allowed_HTML);
		if($Transform)
			$result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
		if(isset($link) && $link != null)
			$result = mysql_real_escape_string($result, $link);
		return (string)$result;
	}
}

