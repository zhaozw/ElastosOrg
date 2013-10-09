<?php /* Smarty version 2.6.26, created on 2013-10-09 10:51:12
         compiled from requirements/reqSpecSearchForm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'basename', 'requirements/reqSpecSearchForm.tpl', 9, false),array('modifier', 'replace', 'requirements/reqSpecSearchForm.tpl', 9, false),array('modifier', 'escape', 'requirements/reqSpecSearchForm.tpl', 22, false),array('function', 'config_load', 'requirements/reqSpecSearchForm.tpl', 10, false),array('function', 'lang_get', 'requirements/reqSpecSearchForm.tpl', 12, false),array('function', 'html_options', 'requirements/reqSpecSearchForm.tpl', 46, false),)), $this); ?>

<?php $this->assign('cfg_section', ((is_array($_tmp=((is_array($_tmp='requirements/reqSpecSearchForm.tpl')) ? $this->_run_mod_handler('basename', true, $_tmp) : basename($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, ".tpl", "") : smarty_modifier_replace($_tmp, ".tpl", ""))); ?>
<?php echo smarty_function_config_load(array('file' => "input_dimensions.conf",'section' => $this->_tpl_vars['cfg_section']), $this);?>


<?php echo lang_get_smarty(array('var' => 'labels','s' => 'caption_search_form, custom_field, search_type_like,
             custom_field_value,btn_find,req_spec_document_id,log_message, 
             title_search_req_spec, reqid, reqversion, caption_search_form_req_spec,
             title, scope, coverage, status, type'), $this);?>



<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<body>

<h1 class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->mainCaption)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>

<div style="margin: 1px;">
<form method="post" action="lib/requirements/reqSpecSearch.php" target="workframe">
	<table class="smallGrey" style="width:100%">
		<caption><?php echo $this->_tpl_vars['labels']['caption_search_form_req_spec']; ?>
</caption>
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['req_spec_document_id']; ?>
</td>
			<td><input type="text" name="requirement_document_id" size="<?php echo $this->_config[0]['vars']['REQSPECDOCID_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['REQSPECDOCID_MAXLEN']; ?>
" /></td>
		</tr>
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['title']; ?>
</td>
			<td><input type="text" name="name" size="<?php echo $this->_config[0]['vars']['REQSPECNAME_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['REQSPECNAME_MAXLEN']; ?>
" /></td>
		</tr>
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['scope']; ?>
</td>
			<td><input type="text" name="scope" 
			           size="<?php echo $this->_config[0]['vars']['SCOPE_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['SCOPE_MAXLEN']; ?>
" /></td>
		</tr>
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['type']; ?>
</td>
			<td>
				<select name="reqSpecType" id="reqSpecType">
					<option value="notype">&nbsp;</option>
  					<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gui']->types), $this);?>

  				</select>
  			</td>
		</tr>
		
		<?php if ($this->_tpl_vars['gui']->filter_by['design_scope_custom_fields']): ?>
		    <tr>
   	    	<td><?php echo $this->_tpl_vars['labels']['custom_field']; ?>
</td>
		    	<td><select name="custom_field_id">
		    			<option value="0">&nbsp;</option>
		    			<?php $_from = $this->_tpl_vars['gui']->design_cf; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cf_id'] => $this->_tpl_vars['cf']):
?>
		    				<option value="<?php echo $this->_tpl_vars['cf_id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['cf']['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
		    			<?php endforeach; endif; unset($_from); ?>
		    		</select>
		    	</td>
	      	</tr>
		    <tr>
	       		<td><?php echo $this->_tpl_vars['labels']['custom_field_value']; ?>
</td>
         		<td>
		    		<input type="text" name="custom_field_value" 
		    	         size="<?php echo $this->_config[0]['vars']['CFVALUE_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['CFVALUE_MAXLEN']; ?>
"/>
		    	</td>
	      </tr>
	  <?php endif; ?>
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['log_message']; ?>
</td>
			<td><input type="text" name="log_message" id="log_message" 
					   size="<?php echo $this->_config[0]['vars']['LOGMSG_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['LOGMSG_MAXLEN']; ?>
" /></td>
		</tr>
	  		
		
  			      
	</table>
	
	<p style="padding-left: 20px;">
		
		<input type="submit" name="doSearch" value="<?php echo $this->_tpl_vars['labels']['btn_find']; ?>
" />
	</p>
</form>

</div>



</body>
</html>