<?php

/**
 *	Validation class to ensure the supplied string is a valid URL.
 *	@metod isValid		Checks to ensure the supplied string is a valid URL.
 *	@credits			http://blancer.com/tutorials/40338/10-compelling-reasons-to-use-zend-framework/
 */
class Kizano_Validate_Url extends Zend_Validate_Abstract
{
	const INVALID_URL = 'invalidUrl';

	protected $_messageTemplates = array(
		self::INVALID_URL   => "'%value%' is not a valid URL.",
	);

	public function isValid($value)
	{
		$valueString = (string) $value;
		$this->_setValue($valueString);

		if (!Zend_Uri::check($value)) {
			$this->_error(self::INVALID_URL);
			return false;
		}
		return true;
	}
}

