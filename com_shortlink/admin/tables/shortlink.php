<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class TableShortlink extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string
	 */
	var $phrase = null;

	/**
	 * @var string
	 */
	var $link = null;

		/**
	 * @var string
	 */
	var $target = null;

	/**
	 * @var int
	 */
	var $counter = null;

	/**
	 * @var string
	 */
	var $description = null;

	/**
	 * @var date
	 */
	var $create_date = null;

	/**
	 * @var date
	 */
	var $last_call = null;

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableShortlink(& $db) {
		parent::__construct('#__shortlink', 'id', $db);

		$now =& JFactory::getDate();
		$this->set( 'create_date', $now->toMySQL() );
	}

	function clicks()
	{
		$query = 'UPDATE #__shortlink'
		. ' SET counter = ( counter + 1 )'
		. ' WHERE id = ' . (int) $this->id
		;
		$this->_db->setQuery( $query );
		$this->_db->query();

		$now =& JFactory::getDate();
		$this->set( 'last_call', $now->toMySQL() );
	}
}