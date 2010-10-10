<?php

class Default_Bootstrap extends Zend_Application_Module_Bootstrap{
    public function __construct(){
		var_dump(sprintf('Default_%s::%s', __CLASS__, __FUNCTION__));die;
    }
}

