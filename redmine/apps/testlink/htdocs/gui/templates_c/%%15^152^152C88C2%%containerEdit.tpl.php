<?php /* Smarty version 2.6.26, created on 2013-06-26 14:31:39
         compiled from testcases/containerEdit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang_get', 'testcases/containerEdit.tpl', 12, false),array('function', 'config_load', 'testcases/containerEdit.tpl', 16, false),array('modifier', 'basename', 'testcases/containerEdit.tpl', 15, false),array('modifier', 'replace', 'testcases/containerEdit.tpl', 15, false),array('modifier', 'escape', 'testcases/containerEdit.tpl', 36, false),)), $this); ?>
<?php echo lang_get_smarty(array('var' => 'labels','s' => 'warning_empty_testsuite_name,title_edit_level,btn_save,tc_keywords,cancel,warning,
          warning_unsaved'), $this);?>

<?php $this->assign('cfg_section', ((is_array($_tmp=((is_array($_tmp='testcases/containerEdit.tpl')) ? $this->_run_mod_handler('basename', true, $_tmp) : basename($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, ".tpl", "") : smarty_modifier_replace($_tmp, ".tpl", ""))); ?>
<?php echo smarty_function_config_load(array('file' => "input_dimensions.conf",'section' => $this->_tpl_vars['cfg_section']), $this);?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array('openHead' => 'yes','jsValidate' => 'yes','editorType' => $this->_tpl_vars['editorType'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_del_onclick.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script language="javascript" src="gui/javascript/ext_extensions.js" type="text/javascript"></script>
<script language="JavaScript" src="gui/javascript/OptionTransfer.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
var <?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
 = new OptionTransfer("<?php echo $this->_tpl_vars['opt_cfg']->from->name; ?>
","<?php echo $this->_tpl_vars['opt_cfg']->to->name; ?>
");
<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
.saveRemovedLeftOptions("<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
_removedLeft");
<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
.saveRemovedRightOptions("<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
_removedRight");
<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
.saveAddedLeftOptions("<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
_addedLeft");
<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
.saveAddedRightOptions("<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
_addedRight");
<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
.saveNewLeftOptions("<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
_newLeft");
<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
.saveNewRightOptions("<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
_newRight");
</script>

<?php echo '
<script type="text/javascript">
'; ?>

var alert_box_title = "<?php echo ((is_array($_tmp=$this->_tpl_vars['labels']['warning'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var warning_empty_container_name = "<?php echo ((is_array($_tmp=$this->_tpl_vars['labels']['warning_empty_testsuite_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
<?php echo '
function validateForm(f)
{
  if (isWhitespace(f.container_name.value)) 
  {
      alert_message(alert_box_title,warning_empty_container_name);
      selectField(f, \'container_name\');
      return false;
  }
  
  /* Validation of a limited type of custom fields */
  var cf_designTime = document.getElementById(\'cfields_design_time\');
	if (cf_designTime)
 	{
 		var cfields_container = cf_designTime.getElementsByTagName(\'input\');
 		var cfieldsChecks = validateCustomFields(cfields_container);
		if(!cfieldsChecks.status_ok)
	  	{
	    	var warning_msg = cfMessages[cfieldsChecks.msg_id];
	      	alert_message(alert_box_title,warning_msg.replace(/%s/, cfieldsChecks.cfield_label));
	      	return false;
		}

 		cfields_container = cf_designTime.getElementsByTagName(\'textarea\');
 		cfieldsChecks = validateCustomFields(cfields_container);
		if(!cfieldsChecks.status_ok)
	  	{
	    	var warning_msg = cfMessages[cfieldsChecks.msg_id];
	      	alert_message(alert_box_title,warning_msg.replace(/%s/, cfieldsChecks.cfield_label));
	      	return false;
		}
	}
  // 20121222 - franciscom 
  // Found minor issue, if submit is done with input of type submit, the php script
  // will not receive it
  // 
  return Ext.ux.requireSessionAndSubmit(f);
}
</script>
'; ?>


<?php if ($this->_tpl_vars['tlCfg']->gui->checkNotSaved): ?>
  <script type="text/javascript">
  var unload_msg = "<?php echo ((is_array($_tmp=$this->_tpl_vars['labels']['warning_unsaved'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
  var tc_editor = "<?php echo $this->_tpl_vars['editorType']; ?>
";
  </script>
  <script src="gui/javascript/checkmodified.js" type="text/javascript"></script>
<?php endif; ?>

</head>

<body onLoad="<?php echo $this->_tpl_vars['opt_cfg']->js_ot_name; ?>
.init(document.forms[0]);focusInputField('name')">
<?php echo smarty_function_config_load(array('file' => "input_dimensions.conf",'section' => 'containerEdit'), $this);?>
 <h1 class="title"><?php echo lang_get_smarty(array('s' => $this->_tpl_vars['level']), $this);?>
<?php echo @TITLE_SEP; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1> 

<div class="workBack">
  <h1 class="title"><?php echo $this->_tpl_vars['labels']['title_edit_level']; ?>
 <?php echo lang_get_smarty(array('s' => $this->_tpl_vars['level']), $this);?>
</h1> 
	<form method="post" action="lib/testcases/containerEdit.php?testsuiteID=<?php echo $this->_tpl_vars['containerID']; ?>
" 
	      name="container_edit" id="container_edit"
        onSubmit="javascript:return validateForm(this);">
	
	<div>
		<input type="hidden" name="doAction" value="" />
		<input type="submit" name="update_testsuite" value="<?php echo $this->_tpl_vars['labels']['btn_save']; ?>
" 
		       onclick="show_modified_warning = false; doAction.value='update_testsuite'" />
		<input type="button" name="go_back" value="<?php echo $this->_tpl_vars['labels']['cancel']; ?>
" 
		       onclick="javascript: show_modified_warning = false; history.back();"/>
	</div>
	 
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "testcases/inc_testsuite_viewer_rw.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

      <?php if ($this->_tpl_vars['cf'] != ""): ?>
     <p>
     <div id="cfields_design_time" class="custom_field_container">
     <?php echo $this->_tpl_vars['cf']; ?>

     </div>
     <p>
   <?php endif; ?>
   
  <div>
   <a href=<?php echo $this->_tpl_vars['gsmarty_href_keywordsView']; ?>
><?php echo $this->_tpl_vars['labels']['tc_keywords']; ?>
</a>
	 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "opt_transfer.inc.tpl", 'smarty_include_vars' => array('option_transfer' => $this->_tpl_vars['opt_cfg'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	 </div>
	<br></br>
	<div>
		<input type="submit" name="update_testsuite" id="update_testsuite_bottom" value="<?php echo $this->_tpl_vars['labels']['btn_save']; ?>
" 
		       onclick="show_modified_warning = false; doAction.value='update_testsuite'" />
		<input type="button" name="go_back" value="<?php echo $this->_tpl_vars['labels']['cancel']; ?>
" 
		       onclick="javascript: show_modified_warning = false; history.back();"/>
	</div>
	</form>
</div>

</body>
</html>