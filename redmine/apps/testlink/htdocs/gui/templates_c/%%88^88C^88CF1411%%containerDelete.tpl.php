<?php /* Smarty version 2.6.26, created on 2013-06-27 14:59:24
         compiled from testcases/containerDelete.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang_get', 'testcases/containerDelete.tpl', 12, false),array('modifier', 'escape', 'testcases/containerDelete.tpl', 17, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo lang_get_smarty(array('var' => 'labels','s' => 'test_case,th_link_exec_status,question_del_testsuite,
          	 btn_yes_del_comp,btn_no'), $this);?>


<body>
<h1 class="title"><?php echo $this->_tpl_vars['page_title']; ?>
<?php echo @TITLE_SEP; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['objectName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1> 

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_update.tpl", 'smarty_include_vars' => array('result' => $this->_tpl_vars['sqlResult'],'item' => $this->_tpl_vars['level'],'action' => 'delete','refresh' => $this->_tpl_vars['gui']->refreshTree)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<div class="workBack">

<?php if ($this->_tpl_vars['sqlResult'] == '' && $this->_tpl_vars['objectID'] != ''): ?>
	<?php if ($this->_tpl_vars['warning'] != ""): ?>
		<?php if ($this->_tpl_vars['system_message'] != ""): ?>
		      <div class="user_feedback"><?php echo $this->_tpl_vars['system_message']; ?>
</div>
		      <br />
		<?php endif; ?>
		<table class="link_and_exec">
		<tr>
			<th><?php echo $this->_tpl_vars['labels']['test_case']; ?>
</th>
			<th><?php echo $this->_tpl_vars['labels']['th_link_exec_status']; ?>
</th>
		</tr>
		<?php unset($this->_sections['idx']);
$this->_sections['idx']['name'] = 'idx';
$this->_sections['idx']['loop'] = is_array($_loop=$this->_tpl_vars['warning']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['idx']['show'] = true;
$this->_sections['idx']['max'] = $this->_sections['idx']['loop'];
$this->_sections['idx']['step'] = 1;
$this->_sections['idx']['start'] = $this->_sections['idx']['step'] > 0 ? 0 : $this->_sections['idx']['loop']-1;
if ($this->_sections['idx']['show']) {
    $this->_sections['idx']['total'] = $this->_sections['idx']['loop'];
    if ($this->_sections['idx']['total'] == 0)
        $this->_sections['idx']['show'] = false;
} else
    $this->_sections['idx']['total'] = 0;
if ($this->_sections['idx']['show']):

            for ($this->_sections['idx']['index'] = $this->_sections['idx']['start'], $this->_sections['idx']['iteration'] = 1;
                 $this->_sections['idx']['iteration'] <= $this->_sections['idx']['total'];
                 $this->_sections['idx']['index'] += $this->_sections['idx']['step'], $this->_sections['idx']['iteration']++):
$this->_sections['idx']['rownum'] = $this->_sections['idx']['iteration'];
$this->_sections['idx']['index_prev'] = $this->_sections['idx']['index'] - $this->_sections['idx']['step'];
$this->_sections['idx']['index_next'] = $this->_sections['idx']['index'] + $this->_sections['idx']['step'];
$this->_sections['idx']['first']      = ($this->_sections['idx']['iteration'] == 1);
$this->_sections['idx']['last']       = ($this->_sections['idx']['iteration'] == $this->_sections['idx']['total']);
?>
			<tr>
				<td><?php echo ((is_array($_tmp=$this->_tpl_vars['warning'][$this->_sections['idx']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&nbsp;</td>
				<td><?php echo lang_get_smarty(array('s' => $this->_tpl_vars['link_msg'][$this->_sections['idx']['index']]), $this);?>
<td>
			</tr>
		<?php endfor; endif; ?>
		</table>
		<?php if ($this->_tpl_vars['delete_msg'] != ''): ?>  
			<h2><?php echo $this->_tpl_vars['delete_msg']; ?>
</h2>
		<?php endif; ?>
	<?php endif; ?>
  
	<form method="post" action="lib/testcases/containerEdit.php?sure=yes&amp;objectID=<?php echo $this->_tpl_vars['objectID']; ?>
">
		<?php if ($this->_tpl_vars['can_delete']): ?>
			<p><?php echo $this->_tpl_vars['labels']['question_del_testsuite']; ?>
</p>
			<input type="submit" name="delete_testsuite" value="<?php echo $this->_tpl_vars['labels']['btn_yes_del_comp']; ?>
" />
		
			<input  type="button" name="cancel_delete_testsuite" value="<?php echo $this->_tpl_vars['labels']['btn_no']; ?>
"
					onclick='javascript: location.href=fRoot+
					"lib/testcases/archiveData.php?&amp;edit=testsuite&amp;id=<?php echo $this->_tpl_vars['objectID']; ?>
";' />
		<?php endif; ?>
	</form>
<?php endif; ?>

<?php if ($this->_tpl_vars['refreshTree']): ?>
   	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_refreshTreeWithFilters.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>

</div>
</body>
</html>