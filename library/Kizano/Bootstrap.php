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

	/**
	 *	Overrides the default bootstrapping function to prevent autoloading plugins
	 *	if they don't exist.
	 *	@return void
	 */
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

	/**
	 *	Execution of the main dispatch from the front controller.
	 *	@return array
	 */
	public function run(){
		$this->bootstrap('frontController');
		$this->_frontController->dispatch();
		return $this->_options;
	}

	/**
	 *	Initialize the configurations and store them in the registry.
	 *	@return array
	 */
	protected function _initConfigs(){
		Zend_Registry::getInstance()->isRegistered('config') || Zend_Registry::getInstance()->set('config', $this->_options);
		return $this->_options;
	}

	/**
	 *	Internal function to set a given resource given by the resource object in the
	 *	application.ini configuration file.
	 *	@return void
	 */
	protected function _setResource($name, $resource = null){
		$this->_options['resources'][$name] = $resource;
	}

	/**
	 *	Retrieves a resource defined by the application.ini
	 *	@return Mixed
	 */
	public function getResource($name){
		return isset($this->_options['resources'][$name])? 
			(object)$this->_options['resources'][$name]: null;
	}

	/**
	 *	Initializes the main autoloader and includes the native Doctrine autoloader.
	 *	@return Zend_Loader_AutoLoader
	 */
	protected function _initAutoLoader(){
		require_once 'Doctrine.php';
		spl_autoload_register(array('Doctrine', 'autoload'));
		$autoLoader = $this->getApplication()->getAutoLoader();
		$this->bootstrap('configs');
		$this->registerPluginResource('autoloader', $autoLoader);
		return $autoLoader;
	}

	/**
	 *	Sets up the resource autoloader for the modules.
	 *	@return		Zend_Loader_Autoloader_Resource
	 */
#	protected function _initModules(){
#		$resourceLoader = new Zend_Loader_Autoloader_Resource();
#	}

	/**
	 *	Initalizes the front controller before it dispatches
	 *	@return Zend_Controller_Front
	 */
	protected function _initFrontController(){
		$this->bootstrap('configs');
		$this->bootstrap('layout');
		$this->_frontController = Zend_Controller_Front::getInstance();
		$this->_frontController->setControllerDirectory($this->getResource('frontController')->controllers);
		$this->_frontController->setDefaultModule('default');
		$this->_frontController->setParam('useDefaultControllerAlways', true);
		$this->_frontController->registerPlugin(new Kizano_View_Plugins_Layout);
		$this->_frontController->unRegisterPlugin('Zend_Layout_Controller_Plugin_Layout');
		return $this->_frontController;
	}

	/**
	 *	Initializes the modules and registers their namespaces to ensure easy loading.
	 *	@return void
	 */
	protected function _initModules(){
		$this->bootstrap('autoloader');
		$modules = (array)$this->getResource('modules');
		array_map(
			array(
				$this->getApplication()->getAutoLoader(),
				'registerNamespace'
			),
			$modules
		);
	}

	/**
	 * Initialize the cache
	 * @return Zend_Cache_Core
	 */
	protected function _initCache(){
		$this->_options['resources']['cache']['frontendOptions']['automatic_serialization'] = 
			(bool)$this->_options['resources']['cache']['frontendOptions']['automatic_serialization'];
		$cache = Zend_Cache::factory(
			'Core',
			'File',
			$this->getResource('cache')->frontendOptions
		);
		Zend_Registry::set('cache', $cache);
		return $cache;
	}

	/**
	 *	Initializes the sessions for storing user data over multiple page requests
	 *	return array
	 */
	protected function _initSession(){
		$sess = $this->getResource('session');
		$session = new Zend_Session_Namespace($sess->name, true);
		$this->_setResource('session', $session);
		Zend_Registry::getInstance()->set('session', $session);
		return $this->getResource('session');
	}

	/**
	 *	Initialize the database.
	 *	@return Doctrine_Manager
	 */
	protected function _initDB(){
		$this->bootstrap('AutoLoader');
		$config = $this->getResource('db');
		$dsn = "mysql://$config->user:$config->pass@$config->host/$config->dbase";
		$DB = Doctrine_Manager::connection($dsn);
		Zend_Registry::getInstance()->set('db', $DB);
		return $DB;
	}

	/**
	 *	Initialize the main layout handler.
	 *	@return Zend_Layout
	 */
	protected function _initLayout(){
		$layout = (array)$this->getResource('layout');
		$this->_layout = Zend_Layout::startMVC($layout);
		Zend_Registry::getInstance()->set('layout', $this->_layout);
		return $this->_layout;
	}

	/**
	 *	Initializes the module's view handler
	 *	@return Zend_View
	 */
	protected function _initView(){
		$this->bootstrap('autoloader');
		$this->bootstrap('layout');
		$reg = Zend_Registry::getInstance();
		$this->view = $this->_layout->getView();
		$this->view
			->setBasePath(DIR_APPLICATION.'modules'.DS.':module/views/')
			->addHelperPath('Kizano/View/Helper/', 'Kizano_View_Helper')
			->doctype('XHTML1_STRICT')
		;
		$render = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$render->setView($this->view)
			->setViewBasePathSpec(DIR_APPLICATION.'modules'.DS.':module/views')
			->setViewScriptPathSpec(':controller/:action.:suffix')
			->setViewScriptPathNoControllerSpec(':action.:suffix');
		$this->_layout->setView($this->view);
		$reg->set('view', $this->view);
		return $this->view;
	}
}

