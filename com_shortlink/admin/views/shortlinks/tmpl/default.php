<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
JHTML::_('behavior.tooltip');

function formatDate($date) {
   $time_string = "never";
   if ($date != "0000-00-00 00:00:00") {
      $time = strtotime($date);
      $time_string = date("Y-m-d", $time);
      $today = mktime();
      $today_begin = mktime(0, 0, 0, date("n", $today), date("j", $today), date("Y", $today));
      $yesterday_begin = mktime(0, 0, 0, date("n", $today), date("j", $today) - 1, date("Y", $today));
      $daybeforeyesterday_begin = mktime(0, 0, 0, date("n", $today), date("j", $today) - 2, date("Y", $today));
      if ($today_begin < $time)
         $time_string = "today";
      else if ($yesterday_begin < $time)
         $time_string = "yesterday";
      //else if ($daybeforeyesterday_begin < $time)
      //	$time_string = "day before yesterday";
   }

   return $time_string;
}

function getAvgHits($create_date, $last_call, $counter) {
   $one_day = 60 * 60 * 24;

   $create_date_time = strtotime(substr($create_date, 0, 10));
   $last_call_time = strtotime(substr($last_call, 0, 10));
   $today = mktime();

   //$call_days = round((($last_call_time - $create_date_time) / $one_day) +1, 0);
   $call_days = round((($today - $create_date_time) / $one_day) + 1, 0);

   $call_daily = round($counter / $call_days, 2);
   $call_daily = ($call_daily > 0) ? $call_daily . " (" . $call_days . ")" : "";
   return $call_daily;
}

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
   <div id="editcell">
      <table>
         <tr>
            <td align="left" width="100%">
               <?php echo JText::_('Filter'); ?>:
               <input type="text" name="search" id="search" value="<?php echo $this->options['search'];?>" class="text_area"
                      onchange="document.adminForm.submit();"/>
               <button onclick="this.form.submit();"><?php echo JText::_('Go'); ?></button>
               <button
                  onclick="document.getElementById('search').value='';this.form.getElementById('filter_last_call').value='total';this.form.submit();"><?php echo JText::_('Reset'); ?></button>
            </td>
            <td nowrap="nowrap">
               <?php echo $this->lastCallSel; ?>
            </td>
         </tr>
      </table>
      <table class="adminlist">
         <thead>
         <tr>
            <th width="5" nowrap="nowrap">
               <?php echo JHTML::_('grid.sort', 'ID', 'id', @$this->options['order_Dir'], @$this->options['order']); ?>
            </th>
            <th width="20" nowrap="nowrap">
               <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);"/>
            </th>
            <th nowrap="nowrap">
               <?php echo JHTML::_('grid.sort', 'Phrase', 'phrase', @$this->options['order_Dir'], @$this->options['order']); ?>
            </th>
            <th nowrap="nowrap">
               <?php echo JHTML::_('grid.sort', 'Link', 'link', @$this->options['order_Dir'], @$this->options['order']); ?>
            </th>
            <th nowrap="nowrap">
               <?php echo JHTML::_('grid.sort', 'Description', 'description', @$this->options['order_Dir'], @$this->options['order']); ?>
            </th>
            <th nowrap="nowrap">
               <?php echo JHTML::_('grid.sort', 'Created', 'create_date', @$this->options['order_Dir'], @$this->options['order']); ?>
            </th>
            <th nowrap="nowrap">
               <?php echo JHTML::_('grid.sort', 'LAST_CALL', 'last_call', @$this->options['order_Dir'], @$this->options['order']); ?>
            </th>
            <th nowrap="nowrap">
               <?php echo JHTML::_('grid.sort', 'Hits', 'counter', @$this->options['order_Dir'], @$this->options['order']); ?>
            </th>
            <th nowrap="nowrap">
               <?php echo JHTML::_('grid.sort', 'AVG_HITS_DAYS', 'avg_hits', @$this->options['order_Dir'], @$this->options['order']); ?>
            </th>
         </tr>
         </thead>
         <tfoot>
         <tr>
            <td colspan="9">
               <?php echo $this->pageNav->getListFooter(); ?>
            </td>
         </tr>
         </tfoot>
         <?php
         $k = 0;
         for ($i = 0, $n = count($this->items); $i < $n; $i++) {
            $row = &$this->items[$i];
            $checked = JHTML::_('grid.id', $i, $row->id);
            $editlink = JRoute::_('index.php?option=com_shortlink&controller=shortlink&task=edit&cid[]=' . $row->id);
            ?>
            <tr class="<?php echo "row$k"; ?>">
               <td>
                  <?php echo $row->id; ?>
               </td>
               <td>
                  <?php echo $checked; ?>
               </td>
               <td>
                  <a href="<?php echo $editlink; ?>" title="edit..."><?php echo $row->phrase; ?></a>
               </td>
               <td>
                  <?php
                  if ($row->is_article) {
                     ?>
                     <a href="<?php echo JURI::root() . 'index.php?option=com_content&task=article&id=' . $row->link; ?>" title="go to..."
                        target="_blank"><?php echo $row->link_text; ?></a>
                     <?php
                  } else {
                     ?>
                     <a href="<?php echo $row->link; ?>" title="go to..." target="_blank"><?php echo $row->link_text; ?></a>
                     <?php
                  }
                  ?>
               </td>
               <td>
                  <?php echo $row->description; ?>
               </td>
               <td title="<?php $row->create_date ?>">
                  <?php echo formatDate($row->create_date); ?>
               </td>
               <td title="<?php $row->last_call ?>">
                  <?php echo formatDate($row->last_call); ?>
               </td>
               <td>
                  <?php echo $row->counter; ?>
               </td>
               <td>
                  <?php echo getAvgHits($row->create_date, $row->last_call, $row->counter); ?>
               </td>
            </tr>
            <?php
            $k = 1 - $k;
         }
         ?>
      </table>
   </div>

   <input type="hidden" name="option" value="com_shortlink"/>
   <input type="hidden" name="task" value=""/>
   <input type="hidden" name="boxchecked" value="0"/>
   <input type="hidden" name="controller" value="shortlink"/>
   <input type="hidden" name="filter_order" value="<?php echo $this->options['order']; ?>"/>
   <input type="hidden" name="filter_order_Dir" value="<?php echo $this->options['order_Dir']; ?>"/>
   <?php echo JHTML::_('form.token'); ?>
</form>
