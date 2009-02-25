<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'ID' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>			
			<th>
				<?php echo JText::_( 'Phrase' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Link' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Description' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Created' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Last call' ); ?>
			</th>
			<th>
				<?php echo JText::_( '# hits' ); ?>
			</th>
		</tr>
	</thead>
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
				<a href="<?php echo $row->link; ?>" title="go to..." target="_blank" ><?php echo $row->link; ?></a>
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
<?php echo JHTML::_( 'form.token' ); ?>
</form>
