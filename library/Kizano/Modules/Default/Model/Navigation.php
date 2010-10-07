<?php

class Kizano_Modules_Default_Model_Navigation extends Kizano_Modules_Default_Model_Base_Navigation{

	const COLUMNS = 'n.id, n.parent_id, n.section, n.title, n.target';
	protected $_view;

	public function init(){
		$this->_view = Zend_Registry::getInstance()->get('view');
		$navs = ADMIN_REQUEST? $this->AdminNavigation(): $this->getNavs();
		$header = array(); $footer = array();
		foreach($navs as $i => $nav){
			if($nav['parent_id'] == 0){
				if($nav['section'] == 'Header')
					$header[$nav['title']] = $nav['target'];
				elseif($nav['section'] == 'Footer')
					$footer[$nav['title']] = $nav['target'];
			}else{
				if($nav['section'] == 'Header')
					$header[$nav['parent_id']][$nav['title']] = $nav['target'];
				elseif($nav['section'] == 'Footer')
					$footer[$nav['parent_id']][$nav['title']] = $nav['target'];
			}
		}
		$this->_view->navigation = Current($header);
		$this->_view->footer = Current($footer);
		return array_merge($header, $footer);
	}

	public function getNavs(){
		return Doctrine_Query::create()
			->select(self::COLUMNS)
			->from(__CLASS__.' n')
			->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	}

	public function getNav($id = 0){
		return Doctrine_Query::create()
			->select(self::COLUMNS)
			->from(__CLASS__.' n')
			->where('id = ?', $id)
			->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	}

	public function getNavsBy($where, $what = null){
		$_where = null;
		if(is_array($where)){
			foreach($where as $key => $val){
				$_where .= (empty($_where)? null: ' AND ')."$key = $val";
			}
		}else{
			return Doctrine_Query::create()
				->select(self::COLUMNS)
				->from(__CLASS__.' n')
				->where("$where = ?", $what)
				->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		}
	}

	public function AdminNavigation(){
		$config = new Zend_Config_Xml(DIR_APPLICATION.'configs'.DS.'admin-nav.xml');
		return $config->toArray();
	}

	public function Footer(){
		$footer = $this->getFooter();
		$result = array();
	}
}

