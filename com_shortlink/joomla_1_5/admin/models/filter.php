<?php
/**
 * Helper class to build parts of the SQL clause
 */
class ShortlinksFilter
{
	var $keyword;
	
	var $last_call_min;
	var $last_call_max;

	var $filter_order;
	var $filter_order_dir;
	
	var $limitstart;
	var $limit;
	

	function setOrder($filter_order, $filter_order_dir)
	{
		$this->filter_order = $filter_order;
		$this->filter_order_dir = $filter_order_dir;
	}

	function setPageLimits($limitstart, $limit)
	{
		$this->limitstart = $limitstart;
		$this->limit = $limit;
	}
	
	function getLimitstart()
	{
		return $this->limitstart;
	}

	function getLimit()
	{
		return $this->limit;
	}

	function setKeyword($keyword)
	{
		$this->keyword = $keyword;
	}
	
	/**
	 * Min and Max are inclusive
	 */
	function setLastCallBetween($min, $max)
	{
		$this->last_call_min = $min;
		$this->last_call_max = $max;
	}

	function getWhere()
	{
		$db =& JFactory::getDBO();
		
		$where = array();

		if ($this->keyword)
		{
			$keyword_escaped = $db->quote( '%'.$db->getEscaped( $this->keyword, true ).'%', false );

			// TODO what about the article ids in column 'link'?!
			$where_keyword  = '( LOWER(`phrase`) like '.$keyword_escaped.' OR LOWER(`description`) like '.$keyword_escaped.' OR LOWER(`link`) like '.$keyword_escaped.')';
			$where[] = $where_keyword;
		}

		if ($this->last_call_min)
		{
			$where[] = '`last_call` >= '.$db->quote( $db->getEscaped( $this->last_call_min ), false );
		}
		
		if ($this->last_call_max)
		{
			$where[] = '`last_call` <= '.$db->quote( $db->getEscaped( $this->last_call_max ), false );
		}

		$where = count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '';
		
		return $where;
	}
	
	function getOrderBy()
	{
		$order_by = '';

		if ($this->filter_order)
		{
			// TODO what about the article ids in column 'link'?!
			$order_by = ' ORDER BY '.$this->filter_order;

			if ($this->filter_order_dir)
			{
				$order_by .= ' '.$this->filter_order_dir.' ';
			}
		}
		
		return $order_by;
	}
}
?>