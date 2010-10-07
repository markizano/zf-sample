<?php
/**
 *	@Name: ~/includes/library/Kizano/Models/Pages.php
 *	@Date: 2010-07-24
 *	@Depends: ~/includes/library/Kizano/Models/Bases/Pages/
 *	@Description: Handles the page administration and client-side business logic.
 *	@Notes: Edit with care
 *	
 *	Skillet Cafe
 *	@CopyRight: (c) 2010 Markizano Draconus
 */

class Kizano_Modules_Default_Model_Pages extends Kizano_Modules_Default_Model_Base_Pages{

	const COLUMNS = 'p.id, p.parent_id, p.meta_keywords, p.meta_description, p.meta_tags, p.head_title, p.content, p.slug, p.published, p.created, p.updated';
	protected $_view;

	public function init(){
		$this->_view = Zend_Registry::getInstance()->get('view');
		$this->_view->headLink()->appendStylesheet('/assets/css/default.css');
		$this->_view->headLink()->appendStylesheet('/assets/css/layout.css');
		$this->_view->headLink()->appendStylesheet('/assets/css/color.css');
		$styles = $this->_view->headLink()->toString();
		$this->_view->placeholder('styles')->set($styles);
		$this->_view->headScript()->prependFile('/assets/js/default.js');
		$this->_view->placeholder('scripts')->set($this->_view->headScript()->toString());
		$this->_view->placeholder('flash')->set(Kizano_Strings::getFlash());
		return $this;
	}

	public function getPages(){
		return Doctrine_Query::create()
			->select(self::COLUMNS)
			->from(__CLASS__.' p')
			->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	}

	public function getPage($id = 0){
		return Doctrine_Query::create()
			->select(self::COLUMNS)
			->from(__CLASS__.' p')
			->where('id = ?', $id)
			->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	}

	public function getPageBySlug($slug){
		return $this->getPageBy('slug', $slug);
	}

	public function getPageBy($id, $value = null){
		return Doctrine_Query::create()
			->select(self::COLUMNS)
			->from(__CLASS__.' p')
			->where("$id = ?", $value)
			->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	}

	public function createPage($infos = array()){
		foreach($infos as $i => $info){
			if(in_array($i, array('meta_keywords', 'meta_description', 'meta_tags', 'head_title', 'content', 'published'))){
				$this->$i = $info;
			}
		}
		$this->slug = Kizano_Strings::sluggify($this->head_title);
		$exist = $this->getPageBySlug($this->slug);
		if(count($exist))
			$this->slug .= mt_rand(0,16);
		$this->save();
		return $this;
	}

	public function updatePage($infos = array()){
		$q = $this->getTable()->find($infos['id']);
		foreach($infos as $i => $info){
			if(in_array($i, array('meta_keywords', 'meta_description', 'meta_tags', 'head_title', 'content', 'published'))){
				$q->$i = $info;
			}
		}
		$q->save();
		return $q;
	}

	protected function _removePage($id){
		return Doctrine_Query::create()
			->delete(__CLASS__.' p')
			->where('id = ?', $id)
			->execute();
	}

	public function deletePage($id, $controller){
		$this->_removePage($id);
		Kizano_Strings::flash('The page was removed.');
		return header("Location: /content/$controller/list");
	}

	public function Content(){
		$slug = (string)$this->_view->placeholder('subslug');
		$page = Current($this->getPageBySlug($slug));
		$this->_view->placeholder('content')->set($page['content']);
		return $page['content'];
	}

	public function handleContent(Zend_Controller_Request_Abstract $request, Zend_Form $form){
		$content = $form->adminContent($this->_view->placeholder('slug'), $request->getActionName());
		if($request->isPost()){
			if($form->isValid(array())){
				$id = $request->getParam('id');
				$params = $request->getParams();
				$params['content'] = $params['_CONTENT'];
				unset($params['_CONTENT']);
				if(empty($id))
					$result = $this->createPage($params);
				else
					$result = $this->updatePage(array_merge(array('id'=>1), $params));
				Kizano_Strings::flash('Thanks! Your entry has been saved.<br />'.chr(10));
				die(header("Location: /content/{$request->getControllerName()}/view"));
			}else{
				$this->_view->placeholder('content')->set($content);
			}
		}else{
			$this->_view->placeholder('content')->set($content);
		}
	}
}

