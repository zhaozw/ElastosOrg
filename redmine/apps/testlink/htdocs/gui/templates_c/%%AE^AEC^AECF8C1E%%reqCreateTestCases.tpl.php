<?php /* Smarty version 2.6.26, created on 2013-06-27 14:16:46
         compiled from requirements/reqCreateTestCases.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'basename', 'requirements/reqCreateTestCases.tpl', 18, false),array('modifier', 'replace', 'requirements/reqCreateTestCases.tpl', 18, false),array('modifier', 'escape', 'requirements/reqCreateTestCases.tpl', 36, false),array('function', 'config_load', 'requirements/reqCreateTestCases.tpl', 19, false),array('function', 'lang_get', 'requirements/reqCreateTestCases.tpl', 21, false),)), $this); ?>
<?php $this->assign('req_module', 'lib/requirements/'); ?>
<?php $this->assign('cfg_section', ((is_array($_tmp=((is_array($_tmp='requirements/reqCreateTestCases.tpl')) ? $this->_run_mod_handler('basename', true, $_tmp) : basename($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, ".tpl", "") : smarty_modifier_replace($_tmp, ".tpl", ""))); ?>
<?php echo smarty_function_config_load(array('file' => "input_dimensions.conf",'section' => $this->_tpl_vars['cfg_section']), $this);?>


<?php echo lang_get_smarty(array('s' => 'select_at_least_one_req','var' => 'check_msg'), $this);?>

<?php echo lang_get_smarty(array('var' => 'labels','s' => "req_doc_id,title,scope,coverage_number,expected_coverage,needed,warning,
             current_coverage,coverage,req_msg_norequirement,req_select_create_tc,
             requirement,status,type,toggle_create_testcase_amount,requirement"), $this);?>
 


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array('openHead' => 'yes')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_jsCheckboxes.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_del_onclick.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php echo '
<script type="text/javascript">
'; ?>

// BUGID 3943: Escape all messages (string)
var alert_box_title = "<?php echo ((is_array($_tmp=$this->_tpl_vars['labels']['warning'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
<?php echo '
/*
  function: check_action_precondition

  args :
  
  returns: 

*/
function check_action_precondition(form_id,action,msg)
{
 if( checkbox_count_checked(form_id) > 0) 
 {
    switch(action)
    {
      case \'create\':
      return true;
      break;
      
      default:
      return true;
      break
    
    }
 }
 else
 {
    alert_message(alert_box_title,msg);
    return false; 
 }  
}

/* BUGID 4317 - CONTRIB FRL:
  function:  cs_all_coverage_in_div (copied from cs_all_checkbox_in_div)
	Change values of all text inputs with a id prefix with values from another (hidden) text with matching id on a div.
	Note : IDs matching based equality of last part of inputs id (without prefixes)
  args :
	div_id: id of the div container of elements 
	input_id_prefix: input text id prefix (for inputs to be fill)
	default_id_prefix : input hidden id prefix (for inputs containing default values)
	memory_id: id of hidden input used to hold old check value.
   returns:  - 

*/
function cs_all_coverage_in_div(div_id, input_id_prefix, default_id_prefix, memory_id)
{
	var inputs = document.getElementById(div_id).getElementsByTagName(\'input\');
	var memory = document.getElementById(memory_id);

	for(var idx = 0; idx < inputs.length; idx++)
	{
		// look for text input whose id starts with input_id_prefix
		if(inputs[idx].type == "text" && (inputs[idx].id.indexOf(input_id_prefix)==0) )
		{
		// set the value to 1 (if coverage ignored) or default value (retrieved from hidden input with matching id)
		inputs[idx].value = (memory.value == "1") ? "1" : document.getElementById(default_id_prefix+inputs[idx].id.substring(default_id_prefix.length)).value;
		}	
	} // for
	// switch coverage_count flag value 
	memory.value = (memory.value == "1") ? "0" : "1";
}

</script>
'; ?>

</head>

<body>


<?php $this->assign('cfg_section', ((is_array($_tmp='requirements/reqCreateTestCases.tpl')) ? $this->_run_mod_handler('replace', true, $_tmp, ".tpl", "") : smarty_modifier_replace($_tmp, ".tpl", ""))); ?>
<?php echo smarty_function_config_load(array('file' => "input_dimensions.conf",'section' => $this->_tpl_vars['cfg_section']), $this);?>


<h1 class="title">
 	<?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->main_descr)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
   
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_help.tpl", 'smarty_include_vars' => array('helptopic' => 'hlp_requirementsCoverage','show_help_icon' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</h1>



<div class="workBack">
  <h2><?php echo $this->_tpl_vars['gui']->action_descr; ?>
</h2>
  
  <?php if ($this->_tpl_vars['gui']->array_of_msg != ''): ?>
    <br />
 	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_msg_from_array.tpl", 'smarty_include_vars' => array('array_of_msg' => $this->_tpl_vars['gui']->array_of_msg,'arg_css_class' => 'messages')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  
  <form id="frmReqList" enctype="multipart/form-data" method="post">
    <input type="hidden" name="doAction"  id="doAction"  value="doCreateTestCases" />
    <input type="hidden" name="req_spec_id"  id="req_spec_id"  value="<?php echo $this->_tpl_vars['gui']->req_spec_id; ?>
" />
 
 
    <?php if ($this->_tpl_vars['gui']->all_reqs != ''): ?>  

	 <div id="req_div"  style="margin:0px 0px 0px 0px;">
				<input type="hidden" name="toggle_req"  id="toggle_req"  value="0" />
				<input type="hidden" name="tc_cov_set"  id="tc_cov_set"  value="0" />
		
	 <table class="simple_tableruler">
	<tr>
		<?php if ($this->_tpl_vars['gui']->grants->req_mgmt == 'yes'): ?>
			<th style="width: 15px;">
				<img src="<?php echo @TL_THEME_IMG_DIR; ?>
/toggle_all.gif" 
					onclick='cs_all_checkbox_in_div("req_div","req_id_cbox","toggle_req");'
					title="<?php echo lang_get_smarty(array('s' => 'check_uncheck_all_checkboxes'), $this);?>
" class="clickable"/></th>
		<?php endif; ?>
		<th><?php echo $this->_tpl_vars['labels']['requirement']; ?>
</th>
		<th><?php echo $this->_tpl_vars['labels']['status']; ?>
</th>
		<th><?php echo $this->_tpl_vars['labels']['type']; ?>
</th>
			
						<th><?php echo $this->_tpl_vars['labels']['coverage_number']; ?>

			<?php if ($this->_tpl_vars['gui']->req_cfg->expected_coverage_management): ?>
				<img src="<?php echo @TL_THEME_IMG_DIR; ?>
/insert_step.png" width="12" height="12"
					onclick='cs_all_coverage_in_div("req_div","testcase_count","coverage_count","tc_cov_set");'
					title="<?php echo lang_get_smarty(array('s' => 'toggle_create_testcase_amount'), $this);?>
" class="clickable"/></th>
			<th><?php echo $this->_tpl_vars['labels']['needed']; ?>

		<?php endif; ?></th>
				<th><?php echo $this->_tpl_vars['labels']['current_coverage']; ?>
</th>
		<th><?php echo $this->_tpl_vars['labels']['coverage']; ?>
</th>
			
	</tr>
	<?php unset($this->_sections['row']);
$this->_sections['row']['name'] = 'row';
$this->_sections['row']['loop'] = is_array($_loop=$this->_tpl_vars['gui']->all_reqs) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['row']['show'] = true;
$this->_sections['row']['max'] = $this->_sections['row']['loop'];
$this->_sections['row']['step'] = 1;
$this->_sections['row']['start'] = $this->_sections['row']['step'] > 0 ? 0 : $this->_sections['row']['loop']-1;
if ($this->_sections['row']['show']) {
    $this->_sections['row']['total'] = $this->_sections['row']['loop'];
    if ($this->_sections['row']['total'] == 0)
        $this->_sections['row']['show'] = false;
} else
    $this->_sections['row']['total'] = 0;
if ($this->_sections['row']['show']):

            for ($this->_sections['row']['index'] = $this->_sections['row']['start'], $this->_sections['row']['iteration'] = 1;
                 $this->_sections['row']['iteration'] <= $this->_sections['row']['total'];
                 $this->_sections['row']['index'] += $this->_sections['row']['step'], $this->_sections['row']['iteration']++):
$this->_sections['row']['rownum'] = $this->_sections['row']['iteration'];
$this->_sections['row']['index_prev'] = $this->_sections['row']['index'] - $this->_sections['row']['step'];
$this->_sections['row']['index_next'] = $this->_sections['row']['index'] + $this->_sections['row']['step'];
$this->_sections['row']['first']      = ($this->_sections['row']['iteration'] == 1);
$this->_sections['row']['last']       = ($this->_sections['row']['iteration'] == $this->_sections['row']['total']);
?>
	<tr>
					<?php if ($this->_tpl_vars['gui']->grants->req_mgmt == 'yes'): ?>
			<td style="padding:2px;"><input type="checkbox" id="req_id_cbox<?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['id']; ?>
"
					   name="req_id_cbox[<?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['id']; ?>
]" 
													   value="<?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['id']; ?>
"/></td><?php endif; ?>
			<td style="padding:2px;">
				<img class="clickable" src="<?php echo @TL_THEME_IMG_DIR; ?>
/edit_icon.png"
				     onclick="javascript:openLinkedReqWindow(<?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['id']; ?>
);"
				     title="<?php echo $this->_tpl_vars['labels']['requirement']; ?>
" />
				<?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['req_doc_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php echo $this->_tpl_vars['gsmarty_gui']->title_separator_1; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

			</td>
			<?php $this->assign('req_status', $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['status']); ?>
			<td style="padding:2px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->reqStatusDomain[$this->_tpl_vars['req_status']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<?php $this->assign('req_type', $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['type']); ?>
			<td style="padding:2px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->reqTypeDomain[$this->_tpl_vars['req_type']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
			<td style="padding:2px;"><input name="testcase_count[<?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['id']; ?>
]" id="testcase_count<?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['id']; ?>
" type="text" size="3" maxlength="3" value="1"></td>
			<?php if ($this->_tpl_vars['gui']->req_cfg->expected_coverage_management): ?>
								<td align="center" style="padding:2px;"><?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['expected_coverage']; ?>

				<input name="coverage_count[<?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['id']; ?>
]" id="coverage_count<?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['id']; ?>
" type="hidden"
					value="<?php if ($this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['expected_coverage'] >= $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['coverage']): ?><?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['expected_coverage']-$this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['coverage']; ?>
<?php else: ?>0<?php endif; ?>"></td>
							<?php endif; ?>  	
			<td align="center" style="padding:2px;"><?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['coverage']; ?>
</td>
			<td align="center" style="padding:2px;"><?php echo $this->_tpl_vars['gui']->all_reqs[$this->_sections['row']['index']]['coverage_percent']; ?>
%</td>
			
		</tr>
		<?php endfor; else: ?>
		<tr><td></td><td><span class="bold"><?php echo $this->_tpl_vars['labels']['req_msg_norequirement']; ?>
</span></td></tr>
		<?php endif; ?>
	 </table>
	 </div>
	 	
	 	 <?php if ($this->_tpl_vars['gui']->grants->req_mgmt == 'yes'): ?>
	  <div class="groupBtn">
	   <input type="submit" name="create_tc_from_req" value="<?php echo $this->_tpl_vars['labels']['req_select_create_tc']; ?>
" 
			  onclick="return check_action_precondition('frmReqList','create','<?php echo $this->_tpl_vars['check_msg']; ?>
');"/>
	  </div>
	 <?php endif; ?>
	 	
  <?php endif; ?>  
  </form>
</div>
</body>
</html>