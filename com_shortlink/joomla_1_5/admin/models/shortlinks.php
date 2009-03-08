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

		$one_day = 60 * 60 * 24;
    	$time_now = time();

    	$wheres['total'] = $where_main;
		$wheres['never'] = $where_main.' AND last_call = \'0000-00-00 00:00:00\'';
		$wheres['last_year'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 365).'\'';
		$wheres['last_months_6'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 183).'\'';
		$wheres['last_months_5'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 152).'\'';
		$wheres['last_months_4'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 122).'\'';
		$wheres['last_months_3'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 91).'\'';
		$wheres['last_months_2'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 61).'\'';
		$wheres['last_months_1'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 31).'\'';
		$wheres['last_weeks_4'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 28).'\'';
		$wheres['last_weeks_3'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 21).'\'';
		$wheres['last_weeks_2'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 14).'\'';
		$wheres['last_weeks_1'] = $where_main.' AND last_call > \''.date("Y-m-d H:i:s", $time_now - $one_day * 7).'\'';

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

		// TODO set filter criteria and update model
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