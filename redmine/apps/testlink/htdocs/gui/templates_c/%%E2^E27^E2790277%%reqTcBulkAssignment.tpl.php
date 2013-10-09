<?php /* Smarty version 2.6.26, created on 2013-10-09 10:52:01
         compiled from requirements/reqTcBulkAssignment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'basename', 'requirements/reqTcBulkAssignment.tpl', 14, false),array('modifier', 'replace', 'requirements/reqTcBulkAssignment.tpl', 14, false),array('modifier', 'escape', 'requirements/reqTcBulkAssignment.tpl', 31, false),array('modifier', 'strip_tags', 'requirements/reqTcBulkAssignment.tpl', 105, false),array('modifier', 'strip', 'requirements/reqTcBulkAssignment.tpl', 105, false),array('modifier', 'truncate', 'requirements/reqTcBulkAssignment.tpl', 105, false),array('function', 'config_load', 'requirements/reqTcBulkAssignment.tpl', 15, false),array('function', 'lang_get', 'requirements/reqTcBulkAssignment.tpl', 17, false),array('function', 'html_options', 'requirements/reqTcBulkAssignment.tpl', 62, false),)), $this); ?>
<?php $this->assign('cfg_section', ((is_array($_tmp=((is_array($_tmp='requirements/reqTcBulkAssignment.tpl')) ? $this->_run_mod_handler('basename', true, $_tmp) : basename($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, ".tpl", "") : smarty_modifier_replace($_tmp, ".tpl", ""))); ?>
<?php echo smarty_function_config_load(array('file' => "input_dimensions.conf",'section' => $this->_tpl_vars['cfg_section']), $this);?>


<?php echo lang_get_smarty(array('var' => 'labels','s' => "req_doc_id,scope,req,req_title_bulk_assign,no_req_spec_available,
             please_select_a_req,test_case,req_title_assign,btn_close,
             req_spec,warning,req_title_available,req_title_assigned,
             check_uncheck_all_checkboxes,req_msg_norequirement,btn_unassign,
             req_title_unassigned,check_uncheck_all_checkboxes,
             req_msg_norequirement,btn_assign,requirement"), $this);?>


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

<script type="text/javascript">
//BUGID 3943: Escape all messages (string)
	var please_select_a_req="<?php echo ((is_array($_tmp=$this->_tpl_vars['labels']['please_select_a_req'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
	var alert_box_title = "<?php echo ((is_array($_tmp=$this->_tpl_vars['labels']['warning'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
<?php echo '

function check_action_precondition(form_id,action)
{
	if(checkbox_count_checked(form_id) <= 0)
	{
		alert_message(alert_box_title,please_select_a_req);
		return false;
	}
	return true;
}
</script>
'; ?>

</head>
<body>
<h1 class="title">
	<?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->pageTitle)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_help.tpl", 'smarty_include_vars' => array('helptopic' => 'hlp_requirementsCoverage','show_help_icon' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</h1>

<?php if ($this->_tpl_vars['gui']->has_req_spec): ?>

    <div class="workBack">
      <h2><?php echo $this->_tpl_vars['labels']['req_title_bulk_assign']; ?>
</h2>
      <form id="SRS_switch" name="SRS_switch" method="post">
 	      <input type="hidden" name="doAction" id="doAction" value="switchspec" />
 	      <input type="hidden" name="id" id="id" value="<?php echo $this->_tpl_vars['gui']->tsuite_id; ?>
" />
        <p><span class="labelHolder"><?php echo $this->_tpl_vars['labels']['req_spec']; ?>
</span>
      	<select name="idSRS" onchange="form.submit()">
      	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gui']->req_specs,'selected' => $this->_tpl_vars['gui']->selectedReqSpec), $this);?>
</select>
      </form>
      <?php if ($this->_tpl_vars['gui']->user_feedback != ''): ?><br /><br /><?php endif; ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_update.tpl", 'smarty_include_vars' => array('user_feedback' => $this->_tpl_vars['gui']->user_feedback)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div class="workBack">
      <?php if ($this->_tpl_vars['gui']->requirements != ""): ?>

                <?php echo $this->_tpl_vars['gui']->bulkassign_warning_msg; ?>
<br />
        <?php if ($this->_tpl_vars['gui']->tcase_number > 0): ?>
          <h2><?php echo $this->_tpl_vars['labels']['req_title_available']; ?>
</h2>
          <form id="reqList" method="post" action="lib/requirements/reqTcAssign.php">
             <input type="hidden" name="id" id="id"  value="<?php echo $this->_tpl_vars['gui']->tsuite_id; ?>
" />
          
          <div id="div_assigned_req">
     	                   <input type="hidden" name="memory_assigned_req"
                                  id="memory_assigned_req"  value="0" />
          
          <input type="hidden" name="idSRS" value="<?php echo $this->_tpl_vars['gui']->selectedReqSpec; ?>
" />
          <table class="simple_tableruler">
          	<tr>
            		<th align="center"  style="width: 5px;background-color:#005498;">
            		    <img src="<?php echo @TL_THEME_IMG_DIR; ?>
/toggle_all.gif"
            		             onclick='cs_all_checkbox_in_div("div_assigned_req","assigned_req","memory_assigned_req");'
            		             title="<?php echo $this->_tpl_vars['labels']['check_uncheck_all_checkboxes']; ?>
" />
            		</th>
          		<th><?php echo $this->_tpl_vars['labels']['req_doc_id']; ?>
</th>
          		<th><?php echo $this->_tpl_vars['labels']['req']; ?>
</th>
          		<th><?php echo $this->_tpl_vars['labels']['scope']; ?>
</th>
          	</tr>
          	<?php unset($this->_sections['row']);
$this->_sections['row']['name'] = 'row';
$this->_sections['row']['loop'] = is_array($_loop=$this->_tpl_vars['gui']->requirements) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
          		<td><input type="checkbox" id="assigned_req<?php echo $this->_tpl_vars['gui']->requirements[$this->_sections['row']['index']]['id']; ?>
"
          		                           name="req_id[<?php echo $this->_tpl_vars['gui']->requirements[$this->_sections['row']['index']]['id']; ?>
]" /></td>
          		<td><span><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->requirements[$this->_sections['row']['index']]['req_doc_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
          		<td>
          			<img class="clickable" src="<?php echo @TL_THEME_IMG_DIR; ?>
/edit_icon.png"
          			     onclick="javascript:openLinkedReqWindow(<?php echo $this->_tpl_vars['gui']->requirements[$this->_sections['row']['index']]['id']; ?>
);"
          			     title="<?php echo $this->_tpl_vars['labels']['requirement']; ?>
" />
          			<?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->requirements[$this->_sections['row']['index']]['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

          		</td>
          	  <td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['gui']->requirements[$this->_sections['row']['index']]['scope'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)))) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_config[0]['vars']['SCOPE_SHORT_TRUNCATE']) : smarty_modifier_truncate($_tmp, $this->_config[0]['vars']['SCOPE_SHORT_TRUNCATE'])); ?>
</td>
          	</tr>
          	<?php endfor; else: ?>
          	<tr><td></td><td><span class="bold"><?php echo $this->_tpl_vars['labels']['req_msg_norequirement']; ?>
</span></td></tr>
          	<?php endif; ?>
          </table>
       	  </div>
          
          <?php if ($this->_sections['row']['total'] > 0): ?>
          	<div class="groupBtn">
          	  	<input type="hidden" name="doAction" id="doAction" value="bulkassign" />
          		<input type="submit" name="actionButton" value="<?php echo $this->_tpl_vars['labels']['btn_assign']; ?>
"
 		      		       onclick="return check_action_precondition('reqList');"/>
          	</div>
          <?php endif; ?>
          </form>
       <?php endif; ?>     
    <?php endif; ?>
    
    </div>
<?php else: ?>
    <?php echo $this->_tpl_vars['labels']['no_req_spec_available']; ?>

<?php endif; ?>
</body>
</html>