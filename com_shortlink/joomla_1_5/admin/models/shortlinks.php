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
			
			$articles = $this->getArticles();
			
			foreach($this->_data as $data)
			{
				$text = $articles[$data->link];
				if ($text) {
					$data->is_article = true;
					$data->link_text = $text;
				} else {
					$data->is_article = false;
					$data->link_text = $data->link;
				}
			}
		}

		return $this->_data;
	}

	function &getArticles()
	{
		// Load articles
		if (empty( $this->_articles )) {
  			$query = ' SELECT id as value, title as text FROM #__content ';
			$this->_db->setQuery( $query );
			$artrows = $this->_db->loadObjectList();

			$articles = array();
			foreach ($artrows as $artrow)
		    {
		      $articles[intval($artrow->value)] = $artrow->text;
		    }
			
			$this->_articles = $articles;
		}
		return $this->_articles;
	}
}