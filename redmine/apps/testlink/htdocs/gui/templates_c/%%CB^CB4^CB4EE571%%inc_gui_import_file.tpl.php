<?php /* Smarty version 2.6.26, created on 2013-10-08 16:48:26
         compiled from inc_gui_import_file.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang_get', 'inc_gui_import_file.tpl', 7, false),array('function', 'html_options', 'inc_gui_import_file.tpl', 15, false),)), $this); ?>
<?php echo lang_get_smarty(array('var' => 'local_labels','s' => 'file_type,view_file_format_doc,local_file,btn_cancel,btn_upload_file,
             action_for_duplicates,skip_frozen_req'), $this);?>


<table>
<tr>
<td> <?php echo $this->_tpl_vars['local_labels']['file_type']; ?>
 </td>
<td> <select name="importType">
     <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['args']->importTypes), $this);?>

     </select>
	   <a href=<?php echo $this->_tpl_vars['basehref']; ?>
<?php echo @PARTIAL_URL_TL_FILE_FORMATS_DOCUMENT; ?>
><?php echo $this->_tpl_vars['local_labels']['view_file_format_doc']; ?>
</a>
</td>
</tr>
<tr>
  <td><?php echo $this->_tpl_vars['local_labels']['local_file']; ?>
 </td>
  <td><input type="file" name="uploadedFile" size="<?php echo $this->_config[0]['vars']['FILENAME_SIZE']; ?>
" 
             maxlength="<?php echo $this->_config[0]['vars']['FILENAME_MAXLEN']; ?>
"/></td>
</tr>

<tr>
  <td><?php echo $this->_tpl_vars['local_labels']['skip_frozen_req']; ?>
</td>
  <td><input type="checkbox" name="skip_frozen_req" <?php echo $this->_tpl_vars['args']->skip_frozen_req_checked; ?>
 /></td>
</tr>

<?php if ($this->_tpl_vars['gui']->hitOptions != ''): ?>
  <tr><td><?php echo $this->_tpl_vars['gui']->duplicate_criteria_verbose; ?>
 </td>
      <td><select name="hitCriteria" id="hitCriteria">
  			  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gui']->hitOptions,'selected' => $this->_tpl_vars['gui']->hitCriteria), $this);?>

  		    </select>
    </td>
  </tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['gui']->actionOptions != ''): ?>
<tr><td><?php echo $this->_tpl_vars['local_labels']['action_for_duplicates']; ?>
 </td>
    <td><select name="actionOnHit">
			  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gui']->actionOptions,'selected' => $this->_tpl_vars['gui']->actionOnHit), $this);?>

		    </select>
  </td>
</tr>
<?php endif; ?>
</table>
<p><?php echo $this->_tpl_vars['args']->fileSizeLimitMsg; ?>
</p>
<div class="groupBtn">
	<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $this->_tpl_vars['args']->maxFileSize; ?>
" /> 	<input type="submit" name="uploadFile" value="<?php echo $this->_tpl_vars['local_labels']['btn_upload_file']; ?>
" />
	<input type="button" name="cancel" value="<?php echo $this->_tpl_vars['local_labels']['btn_cancel']; ?>
"
		     onclick="javascript: location.href='<?php echo $this->_tpl_vars['args']->return_to_url; ?>
';" />
</div>
