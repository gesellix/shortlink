<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );

require_once(JPATH_COMPONENT.DS.'models'.DS.'filter.php');

class ShortlinksModelShortlinks extends JModel
{
	/**
	 * Shortlinks data array
	 *
	 * @var array
	 */
	var $_data;
	
	/**
	 * @var ShortlinksFilter
	 */
	var $_filter;


	function _buildQuery()
	{
		$where = $this->_filter->getWhere();
		$order_by = $this->_filter->getOrderBy();
		
		$query = ' SELECT * FROM #__shortlink ';
		$query .= $where;
		$query .= $order_by;

		return $query;
	}

	function getItemCount()
	{
		$counter = array();
		
		$where_main = $this->_filter->getWhereMain();
		if (empty($where_main))
		{
			$where_main = ' WHERE 1=1 ';
		}
		
		$query = ' SELECT COUNT(*) FROM #__shortlink ';

		$wheres = $this->_filter->appendLastCallClauses($where_main);

		foreach($wheres as $key => $value)
		{
			$this->_db->setQuery( $query.$value );
			$counter[$key] = $this->_db->loadResult();
		}
	
		return $counter;
	}

	function setOptions($options)
	{
		$filter = new ShortlinksFilter();

		$filter->setOrder($options['order'], $options['order_Dir']);
		$filter->setKeyword($options['search']);
		$filter->setLastCall($options['last_call']);
		$filter->setPageLimits($options['limitstart'], $options['limit']);

		$this->setFilter($filter);

		return $filter;
	}

	function setFilter(&$filter)
	{
		// invalidate $_data when $_filter is updated
		$this->_data = null;
		$this->_filter = $filter;
	}
	
	/**
	 * @return array Array of objects containing the data from the database
	 */
	function &getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query, $this->_filter->getLimitstart(), $this->_filter->getLimit() );
			
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
			$artrows = $this->_getList( $query );
			
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