<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');

class ShortlinksModelShortlink extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getArticles()
	{
		// Load articles
		if (empty( $this->_articles )) {
  			$query = ' SELECT id as value, title as text FROM #__content WHERE state=\'1\' ORDER BY title ';
			$this->_db->setQuery( $query );
			$this->_articles = $this->_db->loadObjectList();
		}
		return $this->_articles;
	}

	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__shortlink '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->phrase = null;
			$this->_data->link = null;
			$this->_data->description = null;
			$this->_data->target = null;
			$this->_data->counter = null;
			$this->_data->create_date = null;
			$this->_data->last_call = null;
		}
		if ($this->_data->link) {
			if ($this->string_is_int($this->_data->link) && $this->_data->link > 0) {
				$this->_data->link_article = $this->_data->link;
			} else {
				$this->_data->link_url = $this->_data->link;
			}
		}
		return $this->_data;
	}

	function store()
	{	
		$row =& $this->getTable();

		$data = JRequest::get( 'post' );

		if ($this->string_is_int($data['link_article']) && $data['link_article'] > 0) {
			$data['link'] = $data['link_article'];
		} else {
			$data['link'] = $data['link_url'];
		}
		
		// Bind the form fields to the table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}

	function string_is_int($a){
	    return ((string) $a) === ((string)(int) $a);
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

}