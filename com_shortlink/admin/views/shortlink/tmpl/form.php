<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php
function getArticleSelectList($articles, $active = NULL) {
   $articleList[] = JHTML::_('select.option', '0', '- ' . JText::_('Select Article') . ' -');
   $articleList = array_merge($articleList, $articles);

   $result = JHTML::_('select.genericlist', $articleList, 'link_article', 'class="inputbox" size="1" onchange="javascript:document.adminForm.link_url.value = \'\'; return false;"', 'value', 'text', $active);

   return $result;
}

?>

<script language="javascript" type="text/javascript">
   //<!--
   function submitbutton(pressbutton) {
      if (pressbutton == 'cancel') {
         submitform(pressbutton);
         return;
      }

      var form = document.adminForm;
      var ownUrl = "<?php echo $this->baseURL . '/'; ?>";

      // do field validation
      if (form.phrase.value == "") {
         alert("<?php echo JText::_('Please fill in a phrase.', true); ?>");
      }
      else if ((form.link_article.value == "0") && (form.link_url.value == "")) {
         alert("<?php echo JText::_('Please specify an article or a url.', true); ?>");
      }
      else {
         if (form.link_url.value.indexOf(ownUrl) == 0)
            form.link_url.value = form.link_url.value.substring(ownUrl.length - 1);
         submitform(pressbutton);
      }
   }

   function updateUserlink() {
      document.adminForm.link_user.value = "<?php echo $this->fullshortLink; ?>" + document.adminForm.phrase.value;
   }
   //-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
   <div class="col100">
      <fieldset class="adminform">
         <legend><?php echo JText::_('Details'); ?></legend>

         <table class="admintable">
            <tr>
               <td width="100" align="right" class="key">
                  <label for="phrase">
                     <?php echo JText::_('Phrase to shortlink'); ?>:
                  </label>
               </td>
               <td>
                  <input class="inputbox" type="text" name="phrase" id="phrase" size="100" maxlength="100"
                         value="<?php echo $this->shortlink->phrase;?>" onchange="javascript:updateUserlink();"/>
               </td>
               <td>
                  <a href="#"
                     onclick="javascript:document.forms['adminForm'].phrase.value='<?php echo uniqid(""); ?>'; updateUserlink();"><?php echo JText::_('Set random phrase'); ?></a>
               </td>
            </tr>
            <tr>
               <td width="100" align="right" class="key">
                  <label for="description">
                     <?php echo JText::_('Description'); ?>:
                  </label>
               </td>
               <td colspan="2">
                  <input class="inputbox" type="text" name="description" id="description" size="100" maxlength="255"
                         value="<?php echo $this->shortlink->description;?>"/>
               </td>
            </tr>
            <tr>
               <td width="100" align="right" class="key">
                  <label for="link_article">
                     <?php echo JText::_('Article to link to'); ?>:
                  </label>
               </td>
               <td colspan="2">
                  <?php echo getArticleSelectList($this->articles, $this->shortlink->link_article); ?>
               </td>
            </tr>
            <tr>
               <td width="100" align="right" class="key">
                  <label for="link_url">
                     <?php echo JText::_('Or type explicit link URL'); ?>:
                  </label>
               </td>
               <td colspan="2">
                  <input class="inputbox" type="text" name="link_url" id="link_url" size="100" maxlength="255"
                         value="<?php echo $this->shortlink->link_url;?>"
                         onchange="javascript:document.adminForm.link_article.value = 0; return false;"/>
               </td>
            </tr>
            <tr>
               <td width="100" align="right" class="key">
                  <label for="link_user">
                     <?php echo JText::_('Link for User'); ?>:
                  </label>
               </td>
               <td>
                  <input readonly="readonly" class="inputbox" type="text" name="link_user" id="link_user" size="100" maxlength="255"
                         value=""/>
               </td>
               <td>
                  <a href="#" onclick="updateUserlink();"><?php echo JText::_('Update'); ?></a>
               </td>
            </tr>
         </table>
      </fieldset>
   </div>
   <div class="clr"></div>

   <input type="hidden" name="id" value="<?php echo $this->shortlink->id; ?>"/>
   <input type="hidden" name="last_call" value="<?php echo $this->shortlink->last_call; ?>"/>
   <input type="hidden" name="counter" value="<?php echo $this->shortlink->counter; ?>"/>
   <input type="hidden" name="create_date"
          value="<?php echo (($this->shortlink->create_date) ? $this->shortlink->create_date : date("Y-m-d H:i:s")) ?>"/>
   <input type="hidden" name="task" value=""/>
   <input type="hidden" name="option" value="com_shortlink"/>
   <input type="hidden" name="controller" value="shortlink"/>
   <?php echo JHTML::_('form.token'); ?>
</form>
