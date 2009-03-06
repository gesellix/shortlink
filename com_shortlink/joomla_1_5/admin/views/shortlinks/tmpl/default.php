<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
 		JHTML::_('behavior.tooltip');
?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
	<table>
	<tr>
		<td align="left" width="100%">
			<?php echo JText::_( 'Filter' ); ?>:
			<input type="text" name="search" id="search" value="<?php echo $this->options['search'];?>" class="text_area" onchange="document.adminForm.submit();" />
			<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Filter Reset' ); ?></button>
<?php /* <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_last_call').value='0';this.form.getElementById('filter_state').value='';this.form.submit();"><?php echo JText::_( 'Filter Reset' ); ?></button> */ ?>
		</td>
		<td nowrap="nowrap">
			<?php /*
			echo $lists['catid'];
			echo $lists['state'];
*/			?>
		</td>
	</tr>
	</table>
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'ID', 'id', @$this->options['order_Dir'], @$this->options['order'] ); ?>
			</th>
			<th width="20" nowrap="nowrap">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>			
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Phrase', 'phrase', @$this->options['order_Dir'], @$this->options['order'] ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Link', 'link', @$this->options['order_Dir'], @$this->options['order'] ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Description', 'description', @$this->options['order_Dir'], @$this->options['order'] ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Created', 'create_date', @$this->options['order_Dir'], @$this->options['order'] ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Last Call', 'last_call', @$this->options['order_Dir'], @$this->options['order'] ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',  'Hits', 'counter', @$this->options['order_Dir'], @$this->options['order'] ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="8">
				<?php echo $this->pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)	{
		$row      = &$this->items[$i];
		$checked  = JHTML::_('grid.id',   $i, $row->id );
		$editlink = JRoute::_( 'index.php?option=com_shortlink&controller=shortlink&task=edit&cid[]='. $row->id );
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $editlink; ?>" title="edit..." ><?php echo $row->phrase; ?></a>
			</td>
			<td>
			<?php
			  if ($row->is_article) {
		  	?>
				<a href="<?php echo JURI::root().'index.php?option=com_content&task=article&id='.$row->link; ?>" title="go to..." target="_blank" ><?php echo $row->link_text; ?></a>
			<?php 
			  } else {
		  	?>
				<a href="<?php echo $row->link; ?>" title="go to..." target="_blank" ><?php echo $row->link_text; ?></a>
			<?php
			  }
			?>
			</td>
			<td>
				<?php echo $row->description; ?>
			</td>
			<td>
				<?php echo $row->create_date; ?>
			</td>
			<td>
				<?php echo $row->last_call; ?>
			</td>
			<td>
				<?php echo $row->counter; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</table>
</div>

<input type="hidden" name="option" value="com_shortlink" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="shortlink" />
<input type="hidden" name="filter_order" value="<?php echo $this->options['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->options['order_Dir']; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
