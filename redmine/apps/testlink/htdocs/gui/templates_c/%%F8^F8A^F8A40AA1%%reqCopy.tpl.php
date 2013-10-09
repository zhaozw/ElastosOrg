<?php /* Smarty version 2.6.26, created on 2013-10-08 20:06:20
         compiled from requirements/reqCopy.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang_get', 'requirements/reqCopy.tpl', 14, false),array('function', 'html_options', 'requirements/reqCopy.tpl', 71, false),array('modifier', 'escape', 'requirements/reqCopy.tpl', 29, false),)), $this); ?>
<?php echo lang_get_smarty(array('var' => 'labels','s' => 'title_move_cp,title_move_cp_testcases,sorry_further,req_doc_id,
             check_uncheck_all_checkboxes,title,copy_testcase_assignments,
             choose_target,btn_cp,warning'), $this);?>


<?php echo lang_get_smarty(array('s' => 'select_at_least_one_req','var' => 'check_msg'), $this);?>


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
function check_action_precondition(container_id,action,msg)
{
	var containerSelect = document.getElementById(\'containerID\');
	if(checkbox_count_checked(container_id) > 0 && containerSelect.value > 0)
	{
	     return true;
	}
	else
	{
	   alert_message(alert_box_title,msg);
	   return false;
	}
}
</script>
'; ?>

</head>

<body>
<h1 class="title"><?php echo $this->_tpl_vars['gui']->main_descr; ?>
</h1>
<div class="workBack">
<h1 class="title"><?php echo $this->_tpl_vars['gui']->action_descr; ?>
</h1>
<?php if ($this->_tpl_vars['gui']->array_of_msg != ''): ?>
  <br />
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_msg_from_array.tpl", 'smarty_include_vars' => array('array_of_msg' => $this->_tpl_vars['gui']->array_of_msg,'arg_css_class' => 'messages')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <br />
<?php endif; ?>

	<form id="copy_req" name="copy_req" method="post" action="<?php echo $this->_tpl_vars['gui']->page2call; ?>
">
    <input type="hidden" name="req_spec_id"  id="req_spec_id"  value="<?php echo $this->_tpl_vars['gui']->req_spec_id; ?>
" />
	  
		<p><?php echo $this->_tpl_vars['labels']['choose_target']; ?>
:
			<select name="containerID" id="containerID">
				  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gui']->containers), $this);?>

			</select>
		</p>
		<br />
		<p>
    <input type="checkbox" name="copy_testcase_assignment" id='copy_testcase_assignment' checked="checked">
     <?php echo $this->_tpl_vars['labels']['copy_testcase_assignments']; ?>

    </p>
		<br />

		        <input type="hidden" name="add_value_memory"  id="add_value_memory"  value="0" />
		<div id="checkbox_region">
        <table class="simple_tableruler">
          <tr>
          <th class="clickable_icon">
			         <img src="<?php echo @TL_THEME_IMG_DIR; ?>
/toggle_all.gif"
			              onclick='cs_all_checkbox_in_div("checkbox_region","itemSet_","add_value_memory");'
                    title="<?php echo $this->_tpl_vars['labels']['check_uncheck_all_checkboxes']; ?>
" />
			    </th>
          <th style="width:15%"><?php echo $this->_tpl_vars['labels']['req_doc_id']; ?>
</th>
          <th><?php echo $this->_tpl_vars['labels']['title']; ?>
</th>
          </tr>
        <?php $_from = $this->_tpl_vars['gui']->items; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['rowid'] => $this->_tpl_vars['item_info']):
?>
            <tr>
                <td>
                    <input type="checkbox" name="itemSet[]" id="itemSet_<?php echo $this->_tpl_vars['item_info']['id']; ?>
" 
                           value="<?php echo $this->_tpl_vars['item_info']['id']; ?>
" <?php if (count ( $this->_tpl_vars['gui']->items ) == 1): ?> checked="checked" <?php endif; ?>/>
                </td>
                <td>
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['item_info']['req_doc_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;&nbsp;
                </td>
                <td>
                    <?php echo ((is_array($_tmp=$this->_tpl_vars['item_info']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

                </td>
            </tr>
        <?php endforeach; endif; unset($_from); ?>
        </table>
        <br />
    </div>
		<div>
      <input type="hidden" name="doAction" id="doAction"  value="<?php echo $this->_tpl_vars['gui']->doActionButton; ?>
" />
			<input type="submit" name="copy" value="<?php echo $this->_tpl_vars['labels']['btn_cp']; ?>
"
			       onclick="return check_action_precondition('checkbox_region','copy','<?php echo $this->_tpl_vars['check_msg']; ?>
');"  />
		</div>

	</form>

		<?php if (isset ( $this->_tpl_vars['gui']->refreshTree ) && $this->_tpl_vars['gui']->refreshTree): ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_refreshTreeWithFilters.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>

</div>
</body>
</html>