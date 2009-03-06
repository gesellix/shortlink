<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class ShortlinksViewShortlinks extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'Shortlinks Manager' ), 'generic.png' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::preferences('com_shortlink', '200');
		//JToolBarHelper::help( 'screen.shortlink' );

		$model = &$this->getModel();

		// get options from request
		$options = $this->getOptions();

		// apply options to model
		$model->setOptions($options);

		$total = $model->getItemCount();
		$pageNav = new JPagination( $total, $options['limitstart'], $options['limit'] );
		
		// Get data from the model
		$items = &$model->getData();

		$this->assignRef('items',		$items);
		$this->assignRef('options',		$options);
		$this->assignRef('pageNav',		$pageNav);
		
		parent::display($tpl);
	}

	function getOptions()
	{
		global $mainframe;

		$context			= 'com_shortlink.shortlinks.list.';
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'id',	'cmd' );
		$filter_order_dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_dir',	'filter_order_Dir',	'',			'word' );
		$filter_last_call	= $mainframe->getUserStateFromRequest( $context.'filter_lastcall',	'filter_lastcall',	'',			'int' );
		$search				= $mainframe->getUserStateFromRequest( $context.'search',			'search',			'',			'string' );

		$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0, 'int' );
		
		$options['order'] = $filter_order;
		$options['order_Dir'] = $filter_order_dir;
		$options['last_call'] = $filter_last_call;
		$options['search'] = $search;
		$options['limit'] = $limit;
		$options['limitstart'] = $limitstart;
		
		return $options;
	}
}