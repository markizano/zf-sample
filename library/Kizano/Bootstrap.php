<?php

/**
 *	Application's Bootstrap based on the Zend Appliction Bootstrap. Bootstraps the application.
 *	@property	view					Kizano_View				Public		The view for each module
 *	@property	_config					Zend_Config				Protected	The central configuration for this class
 *	@method		_bootstrap				void					Protected	Overrides the parent bootstrap to only execute
 *																			the resources and not the plugin as well.
 *	@method		run						Array					Public		Executes the dispatcher for this application
 *	@method		_initConfigs			Array					Protected	Returns the configuration for this application
 *	@method		_setResource			void					Protected	Shortcut function to $this->_options['resources']
 *	@method		getResource				Array					Public		Gets a resource from $this->_options['resources']
 *	@method		_initAutoLoader			Zend_Loader_AutoLoader	Protected	Inits the AutoLoader for namespaces
 *	@method		_initFrontController	Zend_Controller_Front	Protected	Inits the Front-end Controller
 *	@method		_initModules			Array					Protected	Inits the Module names into the AutoLoader
 *	@method		_initCache				Zend_Cache				Protected	Inits Zend's ability to cache
 *	@method		_initSession			Zend_Session_Namespace	Protected	Inits the Session for this user
 *	@method		_initLayout				Zend_Layout				Protected	Inits the Zend_Layout
 *	@method		_initView				Kizano_View				Protected	Inits the View for this app
 */
class Kizano_Bootstrap extends Zend_Application_Bootstrap_Bootstrap{

	public $view;
	protected $_config;

	protected function _bootstrap($resource = null){
		if(is_null($resource)){
			foreach($this->_options['resources'] as $name => $resource){
				$this->_setResource($name, $resource);
				$this->_executeResource($name);
			}
        }elseif (is_string($resource)){
            $this->_executeResource($resource);
        }elseif (is_array($resource)){
            foreach ($resource as $r){
                $this->_executeResource($r);
            }
        }else{
            throw new Zend_Application_Bootstrap_Exception(sprintf('%s::%s(): Invalid argument passed!', __CLASS__, __METHOD__));
        }
	}

	public function run(){
		$this->bootstrap('frontController');
		$this->_frontController->dispatch();
		return $this->_options;
	}

	protected function _initConfigs(){
		Zend_Registry::getInstance()->isRegistered('config') || Zend_Registry::getInstance()->set('config', $this->_options);
		return $this->_options;
	}

	protected function _setResource($name, $resource = null){
		$this->_options['resources'][$name] = $resource;
	}

	public function getResource($name){return isset($this->_options['resources'][$name])? (object)$this->_options['resources'][$name]: null;}

	protected function _initAutoLoader(){
		require_once 'Doctrine/Doctrine.php';
		spl_autoload_register(array('Doctrine', 'autoload'));
		$autoLoader = $this->getApplication()->getAutoLoader();
		$this->bootstrap('configs');
#		var_dump($this->getResource('autoloader'));die;
		foreach($this->getResource('autoloader')->Namespaces as $namespace)
			$autoLoader->registerNamespace($namespace);
		$this->registerPluginResource('autoloader', $this->getApplication()->getAutoLoader());
		return $autoLoader;
	}

	protected function _initFrontController(){
		$this->bootstrap('configs');
		$this->bootstrap('layout');
		$this->_frontController = Zend_Controller_Front::getInstance();
		$this->_frontController->setControllerDirectory($this->getResource('frontController')->controllers);
		$this->_frontController->registerPlugin(new Kizano_View_Plugins_Layout);
		$this->_frontController->unRegisterPlugin('Zend_Layout_Controller_Plugin_Layout'); # <- Get rid of this annoying-ass class! >:|
		return $this->_frontController;
	}

	protected function _initModules(){
		return array_map(
			array(
				$this->getApplication()->getAutoLoader(),
				'registerNamespace'
			),
			Current($this->getResource('modules'))
		);
	}

	/**
	 * Initialize the cache
	 *
	 * @return Zend_Cache_Core
	 */
	protected function _initCache(){
		$this->_options['resources']['cache']['frontendOptions']['automatic_serialization'] = 
			(bool)$this->_options['resources']['cache']['frontendOptions']['automatic_serialization'];
		$cache = Zend_Cache::factory(
			'Core',
			'File',
			$this->getResource('cache')->frontendOptions,
			$this->getResource('cache')->backendOptions
		);
		Zend_Registry::set('cache', $cache);
		return $cache;
	}

	protected function _initSession(){
		$this->_setResource('session', $session = new Zend_Session_Namespace(SESSION_NAME, true));
		Zend_Registry::getInstance()->set('session', $session);
		return $this->getResource('session');
	}

	protected function _initDB(){
		$this->bootstrap('AutoLoader');
		$DB = Doctrine_Manager::connection($this->getResource('db')->doctrine['dsn']);
		Zend_Registry::getInstance()->set('db', $DB);
		return $DB;
	}

	protected function _initLayout(){
		$this->bootstrap('view');
		$layout = (array)$this->getResource('layout');
		$layout['view'] = $this->view;
		$layout['layoutPath'] = $this->view->template_dir;
		$layout['inflector'] = new Zend_Filter_Inflector(':script.:suffix');
		$layout['inflector']->addRules(array(
			':script'=>array(
				'Word_CamelCaseToDash',
				'StringToLower'
			),
			'suffix'=>$layout['suffix']
		));
		$this->_layout = Zend_Layout::startMVC($layout);
		$this->_layout->setPluginClass('Kizano_Layout_Plugins_Layout');
		$this->_layout->getView()->docType('XHTML1_STRICT');
		$this->_layout->getView()->addHelperPath('Kizano/Layout/Helper/', 'Kizano_Layout_Helper');
#		$this->_layout->getView()->addHelperPath('Osash/Layout/Helper/', 'Osash_Layout_Helper');
		Zend_Registry::getInstance()->set('layout', $this->_layout);
		return $this->_layout;
	}

	protected function _initView(){
		$this->bootstrap('autoloader');
		$reg = Zend_Registry::getInstance();
		$this->view = Kizano_View::getInstance();
		$this->view->doctype('XHTML1_STRICT');
		$render = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$render->setView($this->view)
			->setViewBasePathSpec($this->view->template_dir)
			->setViewScriptPathSpec(':controller/:action.:suffix')
			->setViewScriptPathNoControllerSpec(':action.:suffix')
			->setViewSuffix('tpl');
		$this->view->addHelperPath('Kizano/View/Helper', 'Kizano_View_Helper');
		$reg->set('view', $this->view);
		return $this->view;
	}
}

