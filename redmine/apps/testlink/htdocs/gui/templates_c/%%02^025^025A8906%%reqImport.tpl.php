<?php /* Smarty version 2.6.26, created on 2013-10-08 16:48:26
         compiled from requirements/reqImport.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang_get', 'requirements/reqImport.tpl', 8, false),array('function', 'config_load', 'requirements/reqImport.tpl', 21, false),array('modifier', 'basename', 'requirements/reqImport.tpl', 20, false),array('modifier', 'replace', 'requirements/reqImport.tpl', 20, false),array('modifier', 'escape', 'requirements/reqImport.tpl', 27, false),)), $this); ?>

<?php echo lang_get_smarty(array('var' => 'labels','s' => 'note_keyword_filter,check_uncheck_all_checkboxes_for_add,
             th_id,th_test_case,version,scope,check_status,type,doc_id_short,
             btn_save_custom_fields,title_req_import,expected_coverage,
             check_req_file_structure,req_msg_norequirement,status,
             req_import_option_skip,req_import_option_overwrite,
             title_req_import_check_input,req_import_check_note,
             req_import_dont_empty,btn_import,btn_cancel,Result,
             req_doc_id,title,req_import_option_header,warning,
             check_uncheck_all_checkboxes,remove_tc,show_tcase_spec,
             check_uncheck_all_checkboxes_for_rm'), $this);?>


<?php $this->assign('cfg_section', ((is_array($_tmp=((is_array($_tmp='requirements/reqImport.tpl')) ? $this->_run_mod_handler('basename', true, $_tmp) : basename($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, ".tpl", "") : smarty_modifier_replace($_tmp, ".tpl", ""))); ?>
<?php echo smarty_function_config_load(array('file' => "input_dimensions.conf",'section' => $this->_tpl_vars['cfg_section']), $this);?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array('openHead' => 'yes')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_del_onclick.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
<body>
<h1 class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->main_descr)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>

<div class="workBack">
<?php if ($this->_tpl_vars['gui']->doAction == 'askFileName' || $this->_tpl_vars['gui']->file_check['status_ok'] == 0): ?>
  <form method="post" enctype="multipart/form-data" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
?req_spec_id=<?php echo $this->_tpl_vars['gui']->req_spec_id; ?>
">
  	<input type="hidden" name="scope" id="scope" value="<?php echo $this->_tpl_vars['gui']->scope; ?>
" />
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_gui_import_file.tpl", 'smarty_include_vars' => array('args' => $this->_tpl_vars['gui']->importFileGui)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </form>
<?php else: ?>
  <?php if ($this->_tpl_vars['gui']->importResult != '' && $this->_tpl_vars['gui']->file_check['status_ok']): ?>
  	<p class="info"><?php echo $this->_tpl_vars['gui']->importResult; ?>
</p>
  	<table class="simple">
  	<tr>
  		<th><?php echo $this->_tpl_vars['labels']['doc_id_short']; ?>
</th>
  		<th><?php echo $this->_tpl_vars['labels']['title']; ?>
</th>
  		<th style="width: 20%;"><?php echo $this->_tpl_vars['labels']['Result']; ?>
</th>
  	</tr>
  	<?php if ($this->_tpl_vars['gui']->items != ''): ?>
 	    <?php $_from = $this->_tpl_vars['gui']->items; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['import_feedback']):
?>
  	  <tr>
  	    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['import_feedback']['doc_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  	    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['import_feedback']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  	    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['import_feedback']['import_status'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
  	  </tr>
  	  <?php endforeach; endif; unset($_from); ?>
  	<?php else: ?>
  	  <tr><td><?php echo $this->_tpl_vars['labels']['req_msg_norequirement']; ?>
</td></tr>
  	<?php endif; ?>
  	</table>
  <?php endif; ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['gui']->refreshTree): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_refreshTree.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['gui']->file_check['status_ok'] == 0): ?>
  <script type="text/javascript">
//BUGID 3943: Escape all messages (string)
  alert_message("<?php echo ((is_array($_tmp=$this->_tpl_vars['labels']['warning'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
","<?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->file_check['msg'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
");
  </script>
<?php endif; ?>  


</div>

</body>
</html>