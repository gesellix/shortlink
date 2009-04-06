<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pagination' );

class ShortlinksViewShortlinks extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title( JText::_( 'Shortlinks Manager' ), 'generic.png' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::preferences('com_shortlink', '350');
		JToolBarHelper::help( 'screen.shortlink', true ); // TODO

		$document = &JFactory::getDocument();
		$document->addScript(JURI::root(true).'/administrator/components/com_shortlink/assets/helper.js');

		$model = &$this->getModel();

		// get options from request
		$options = $this->getOptions();

		// apply options to model
		$model->setOptions($options);

		$counter = $model->getItemCount();
		$lastCallSelections = $this->getLastCallSelections($counter, $options);
		
		$pageNav = new JPagination( $counter['total'], $options['limitstart'], $options['limit'] );
		
		// Get data from the model
		$items = &$model->getData();

		$this->assignRef('items',		$items);
		$this->assignRef('options',		$options);
		$this->assignRef('pageNav',		$pageNav);
		$this->assignRef('lastCallSel',	$lastCallSelections);
		
		parent::display($tpl);
	}

	function getLastCallSelections($counters, $options)
	{
		$javascript	= 'onchange="document.adminForm.submit();"';
		$active 	= $options['last_call'];

		$texts = ShortlinksFilter::getLastCallSelections();
		
    	$texts['total'] .= " (".$counters['total'].")";
		$texts['never'] .= " (".$counters['never']."/".$counters['total'].")";
		$texts['last_weeks_1'] .= " (".$counters['last_weeks_1']."/".$counters['total'].")";
		$texts['last_weeks_2'] .= " (".$counters['last_weeks_2']."/".$counters['total'].")";
		$texts['last_weeks_3'] .= " (".$counters['last_weeks_3']."/".$counters['total'].")";
		$texts['last_weeks_4'] .= " (".$counters['last_weeks_4']."/".$counters['total'].")";
		$texts['last_months_1'] .= " (".$counters['last_months_1']."/".$counters['total'].")";
		$texts['last_months_2'] .= " (".$counters['last_months_2']."/".$counters['total'].")";
		$texts['last_months_3'] .= " (".$counters['last_months_3']."/".$counters['total'].")";
		$texts['last_months_4'] .= " (".$counters['last_months_4']."/".$counters['total'].")";
		$texts['last_months_5'] .= " (".$counters['last_months_5']."/".$counters['total'].")";
		$texts['last_months_6'] .= " (".$counters['last_months_6']."/".$counters['total'].")";
		$texts['last_year'] .= " (".$counters['last_year']."/".$counters['total'].")";
				
		foreach($counters as $key => $value)
		{
			$selections[] = array('value' => $key, 'text' => $texts[$key]);
		}
		$selections = JHTML::_('select.genericlist',   $selections, 'filter_last_call', 'class="inputbox" size="1" '. $javascript, 'value', 'text', $active );
		
		return $selections;
	}

	function getOptions()
	{
		global $mainframe;

		$params = &JComponentHelper::getParams( 'com_shortlink' );
		$context			= 'com_shortlink.shortlinks.list.';
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		$params->def('filter_order', 'id'),		'cmd' );
		$filter_order_dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_dir', 	'filter_order_Dir', 	$params->def('filter_dir', 'asc'),		'word' );
		$filter_last_call	= $mainframe->getUserStateFromRequest( $context.'filter_last_call', 	'filter_last_call', 	$params->def('filter_last_call', 'total'),	'string' );
		$search				= $mainframe->getUserStateFromRequest( $context.'search', 		'search', 		'',						'string' );

		$limit				= $mainframe->getUserStateFromRequest( 'global.list.limit', 		'limit', 		$mainframe->getCfg('list_limit'),		'int' );
		$limitstart 		= $mainframe->getUserStateFromRequest( $context.'limitstart', 		'limitstart', 		0,						'int' );
		
		$options['order'] = $filter_order;
		$options['order_Dir'] = $filter_order_dir;
		$options['last_call'] = $filter_last_call;
		$options['search'] = $search;
		$options['limit'] = $limit;
		$options['limitstart'] = $limitstart;
		
		return $options;
	}
}
