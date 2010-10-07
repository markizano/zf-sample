<?php

class Kizano_Record extends Doctrine_Record{

	const COLUMNS = '*';
	protected static $_instance = null;
	protected $_primary;
	public $table;

	/**
	 *	Introduces a hook for the init() function. When this model is instantiated, call the init() function.
	 *	@param	$table		String		The name of the table to load
	 *	@param	$isNewEntry	String		Is this a new table entry?
	 *	@return				self
	 */
    public function __construct($table = null, $isNewEntry = false){
		parent::__construct($table, $isNewEntry);
		$this->init();
		$this::$_instance = $this;
		return $this;
	}

	/**
	 *	Empty function to populate in a derived class
	 */
	public function init(){}

	public function getQuery(){
		if($this::COLUMNS == '*') trigger_error(sprintf("Select * is EVIL! ._.  Please review class `%s' and be sure to override %s::COLUMNS", get_class($this)), E_USER_WARNING);
		return Doctrine_Query::create()
			->select($this::COLUMNS)
			->from(get_class($this));
	}

	public function getAll(){
		return $this->getQuery()
			->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
	}

	public function getAllBy($key, $what = null){
		$q = $this->getQuery();
		foreach($what as $key => $val)
			$q->where("$key = ?", $val);
		return $q->orderBy("$this->_primary ASC")
			->fetchOne(Doctrine_Core::HYDRATE_ARRAY);
	}

	/**
	 *	Creates a new record and adds it to the DB based on the stuff given in $info
	 *	@param	info	Array|Zend_Config		The stuff to put in the DB
	 *	@return			$this
	 */
	protected function _create($info){
		$info instanceof Zend_Config && $info = $info->toArray();
		if(!is_array($info)) throw new Kizano_Exception(sprintf('%s::%s(): Argument $info expected type (string), received `%s\'', __CLASS__, __FUNCTION__, get_type($info)));
		foreach($info as $name => $value)
			$this[$name] = $value;
		return $this->save();
	}

	/**
	 *	Updates a record in the DB according to the info
	 *	@param	id		Int					The ID of the primary key in the DB to update
	 *	@param	infos	Array|Zend_Config	The info to update the DB
	 *	@return			$this;
	 */
	protected function _update(double $id, $infos){
		$update = $this->getTable()->find($id);
		$info instanceof Zend_Config && $info = $info->toArray();
		foreach($info as $name => $value)
			$update[$name] = $value;
		return $update->save();
	}

	/**
	 *	Removes an entry from the DB
	 *	@param	id		Int					The ID of the primary key to remove
	 *	@return			$this
	 */
	protected function _remove(double $id){
		return Doctrine_Query::create()
			->delete(get_class($this))
			->where("$this->_primary = ?", $id)
			->execute();
	}
}

