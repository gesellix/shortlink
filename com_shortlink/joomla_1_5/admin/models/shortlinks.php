<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

class ShortlinksModelShortlinks extends JModel
{
	/**
	 * Shortlinks data array
	 *
	 * @var array
	 */
	var $_data;


	function _buildQuery()
	{
		$query = ' SELECT * '
			. ' FROM #__shortlink '
		;

		return $query;
	}

	/**
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}

		return $this->_data;
	}
}