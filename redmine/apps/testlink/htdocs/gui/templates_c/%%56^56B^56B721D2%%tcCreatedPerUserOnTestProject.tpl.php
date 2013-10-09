<?php /* Smarty version 2.6.26, created on 2013-09-09 17:30:41
         compiled from results/tcCreatedPerUserOnTestProject.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'results/tcCreatedPerUserOnTestProject.tpl', 38, false),)), $this); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array('openHead' => 'yes')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_ext_js.tpl", 'smarty_include_vars' => array('bResetEXTCss' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                          
                          
<?php $_from = $this->_tpl_vars['gui']->tableSet; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['initializer'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['initializer']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['matrix']):
        $this->_foreach['initializer']['iteration']++;
?>
  <?php $this->assign('tableID', "table_".($this->_tpl_vars['idx'])); ?>
  <?php if (($this->_foreach['initializer']['iteration'] <= 1)): ?>
    <?php echo $this->_tpl_vars['matrix']->renderCommonGlobals(); ?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_ext_js.tpl", 'smarty_include_vars' => array('bResetEXTCss' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_ext_table.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  <?php endif; ?>
  <?php echo $this->_tpl_vars['matrix']->renderHeadSection($this->_tpl_vars['tableID']); ?>

<?php endforeach; endif; unset($_from); ?>
</head>

<body>
  <h1 class="title"><?php echo $this->_tpl_vars['gui']->pageTitle; ?>
</h1>
  <div class="workBack">
  <?php if ($this->_tpl_vars['gui']->warning_msg == ''): ?>
    <?php if ($this->_tpl_vars['gui']->resultSet): ?>
      <?php $_from = $this->_tpl_vars['gui']->tableSet; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['idx'] => $this->_tpl_vars['matrix']):
?>
        <p>
        <?php $this->assign('tableID', "table_".($this->_tpl_vars['idx'])); ?>
        <?php echo $this->_tpl_vars['matrix']->renderBodySection($this->_tpl_vars['tableID']); ?>

        <br />
        </p>
      <?php endforeach; endif; unset($_from); ?>
      <br />
      <?php echo $this->_tpl_vars['gui']->l18n['generated_by_TestLink_on']; ?>
 <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, $this->_tpl_vars['gsmarty_timestamp_format']) : smarty_modifier_date_format($_tmp, $this->_tpl_vars['gsmarty_timestamp_format'])); ?>

    <?php else: ?>
      <?php echo $this->_tpl_vars['gui']->l18n['no_records_found']; ?>

    <?php endif; ?>
  <?php else: ?>
    <div class="user_feedback">
    <?php echo $this->_tpl_vars['gui']->warning_msg; ?>

    </div>
  <?php endif; ?>   
  </div>
</body>
</html>