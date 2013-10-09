<?php /* Smarty version 2.6.26, created on 2013-06-27 13:56:30
         compiled from requirements/reqSearchForm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'basename', 'requirements/reqSearchForm.tpl', 18, false),array('modifier', 'replace', 'requirements/reqSearchForm.tpl', 18, false),array('modifier', 'escape', 'requirements/reqSearchForm.tpl', 35, false),array('function', 'config_load', 'requirements/reqSearchForm.tpl', 19, false),array('function', 'lang_get', 'requirements/reqSearchForm.tpl', 21, false),array('function', 'html_options', 'requirements/reqSearchForm.tpl', 67, false),)), $this); ?>

<?php $this->assign('cfg_section', ((is_array($_tmp=((is_array($_tmp='requirements/reqSearchForm.tpl')) ? $this->_run_mod_handler('basename', true, $_tmp) : basename($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, ".tpl", "") : smarty_modifier_replace($_tmp, ".tpl", ""))); ?>
<?php echo smarty_function_config_load(array('file' => "input_dimensions.conf",'section' => $this->_tpl_vars['cfg_section']), $this);?>


<?php echo lang_get_smarty(array('var' => 'labels','s' => 'caption_search_form, custom_field, search_type_like,
             custom_field_value,btn_find,requirement_document_id, req_expected_coverage,
             title_search_req, reqid, reqversion, caption_search_form_req, title, scope,
             coverage, status, type, version, th_tcid, has_relation_type,
             modification_date_from,modification_date_to,creation_date_from,creation_date_to,
             show_calender,clear_date,log_message,'), $this);?>



<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_head.tpl", 'smarty_include_vars' => array('openHead' => 'yes','jsValidate' => 'yes')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_ext_js.tpl", 'smarty_include_vars' => array('bResetEXTCss' => 1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
<body>

<h1 class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['gui']->mainCaption)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h1>

<div style="margin: 1px;">
<form method="post" action="lib/requirements/reqSearch.php" target="workframe">
	<table class="smallGrey" style="width:100%">
		<caption><?php echo $this->_tpl_vars['labels']['caption_search_form_req']; ?>
</caption>
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['requirement_document_id']; ?>
</td>
			<td><input type="text" name="requirement_document_id" size="<?php echo $this->_config[0]['vars']['REQDOCID_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['REQDOCID_MAXLEN']; ?>
" /></td>
		</tr>
		
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['version']; ?>
</td>
			<td><input type="text" name="version" 
			           size="<?php echo $this->_config[0]['vars']['VERSION_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['VERSION_MAXLEN']; ?>
" /></td>
		</tr>
		
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['title']; ?>
</td>
			<td><input type="text" name="name" size="<?php echo $this->_config[0]['vars']['REQNAME_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['REQNAME_MAXLEN']; ?>
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
			<td><?php echo $this->_tpl_vars['labels']['status']; ?>
</td>
     		<td><select name="reqStatus">
     		<option value="">&nbsp;</option>
  			<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gui']->reqStatus), $this);?>

  			</select></td>
  		</tr>
		
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['type']; ?>
</td>
			<td>
				<select name="reqType" id="reqType">
					<option value="">&nbsp;</option>
  					<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gui']->types), $this);?>

  				</select>
  			</td>
		</tr>
	
		<?php if ($this->_tpl_vars['gui']->filter_by['expected_coverage']): ?>
			<tr>
				<td><?php echo $this->_tpl_vars['labels']['req_expected_coverage']; ?>
</td>
				<td><input type="text" name="coverage" size="<?php echo $this->_config[0]['vars']['COVERAGE_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['COVERAGE_MAXLEN']; ?>
" /></td>
			</tr>
		<?php endif; ?>		
		
		<?php if ($this->_tpl_vars['gui']->filter_by['relation_type']): ?>
			<tr>
				<td><?php echo $this->_tpl_vars['labels']['has_relation_type']; ?>
</td>
				<td>
					<select id="relation_type" name="relation_type" />
						<option value="">&nbsp;</option>
						<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gui']->req_relation_select['items']), $this);?>

					</select>
				</td>				
			</tr>
		<?php endif; ?>
		
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['creation_date_from']; ?>
</td>
			<td>
				                <input type="text" 
                       name="creation_date_from" id="creation_date_from" 
				       value="<?php echo $this->_tpl_vars['gui']->creation_date_from; ?>
" 
				       onclick="showCal('creation_date_from-cal','creation_date_from','<?php echo $this->_tpl_vars['gsmarty_datepicker_format']; ?>
');" 
				       readonly />
				<img title="<?php echo $this->_tpl_vars['labels']['show_calender']; ?>
" src="<?php echo @TL_THEME_IMG_DIR; ?>
/calendar.gif"
				     onclick="showCal('creation_date_from-cal','creation_date_from','<?php echo $this->_tpl_vars['gsmarty_datepicker_format']; ?>
');" >
				<img title="<?php echo $this->_tpl_vars['labels']['clear_date']; ?>
" src="<?php echo @TL_THEME_IMG_DIR; ?>
/trash.png"
			         onclick="javascript:var x = document.getElementById('creation_date_from'); x.value = '';" >
				<div id="creation_date_from-cal" style="position:absolute;width:240px;left:300px;z-index:1;"></div>
		  </td>
		</tr>
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['creation_date_to']; ?>
</td>
			<td>
           	    <input type="text" 
                       name="creation_date_to" id="creation_date_to" 
				       value="<?php echo $this->_tpl_vars['gui']->creation_date_to; ?>
" 
				       onclick="showCal('creation_date_to-cal','creation_date_to','<?php echo $this->_tpl_vars['gsmarty_datepicker_format']; ?>
');"
				       readonly />
				<img title="<?php echo $this->_tpl_vars['labels']['show_calender']; ?>
" src="<?php echo @TL_THEME_IMG_DIR; ?>
/calendar.gif"
				     onclick="showCal('creation_date_to-cal','creation_date_to','<?php echo $this->_tpl_vars['gsmarty_datepicker_format']; ?>
');" >
				<img title="<?php echo $this->_tpl_vars['labels']['clear_date']; ?>
" src="<?php echo @TL_THEME_IMG_DIR; ?>
/trash.png"
			         onclick="javascript:var x = document.getElementById('creation_date_to'); x.value = '';" >
				<div id="creation_date_to-cal" style="position:absolute;width:240px;left:300px;z-index:1;"></div>
		  </td>
		</tr>
		
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['modification_date_from']; ?>
</td>
			<td>
            	<input type="text" 
                       name="modification_date_from" id="modification_date_from" 
				       value="<?php echo $this->_tpl_vars['gui']->modification_date_from; ?>
" 
				       onclick="showCal('modification_date_from-cal','modification_date_from','<?php echo $this->_tpl_vars['gsmarty_datepicker_format']; ?>
');"
				       readonly />
				<img title="<?php echo $this->_tpl_vars['labels']['show_calender']; ?>
" src="<?php echo @TL_THEME_IMG_DIR; ?>
/calendar.gif"
				     onclick="showCal('modification_date_from-cal','modification_date_from','<?php echo $this->_tpl_vars['gsmarty_datepicker_format']; ?>
');" >
				<img title="<?php echo $this->_tpl_vars['labels']['clear_date']; ?>
" src="<?php echo @TL_THEME_IMG_DIR; ?>
/trash.png"
			         onclick="javascript:var x = document.getElementById('modification_date_from'); x.value = '';" >
				<div id="modification_date_from-cal" style="position:absolute;width:240px;left:300px;z-index:1;"></div>
		  </td>
		</tr>
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['modification_date_to']; ?>
</td>
			<td>
         	    <input type="text" 
                       name="modification_date_to" id="modification_date_to" 
				       value="<?php echo $this->_tpl_vars['gui']->modification_date_to; ?>
" 
				       onclick="showCal('modification_date_to-cal','modification_date_to','<?php echo $this->_tpl_vars['gsmarty_datepicker_format']; ?>
');"
				       readonly />
				<img title="<?php echo $this->_tpl_vars['labels']['show_calender']; ?>
" src="<?php echo @TL_THEME_IMG_DIR; ?>
/calendar.gif"
				     onclick="showCal('modification_date_to-cal','modification_date_to','<?php echo $this->_tpl_vars['gsmarty_datepicker_format']; ?>
');" >
				<img title="<?php echo $this->_tpl_vars['labels']['clear_date']; ?>
" src="<?php echo @TL_THEME_IMG_DIR; ?>
/trash.png"
			         onclick="javascript:var x = document.getElementById('modification_date_to'); x.value = '';" >
				<div id="modification_date_to-cal" style="position:absolute;width:240px;left:300px;z-index:1;"></div>
		  </td>
		</tr>
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['th_tcid']; ?>
</td>
			<td><input type="text" name="tcid" value="<?php echo $this->_tpl_vars['gui']->tcasePrefix; ?>
" 
			           size="<?php echo $this->_config[0]['vars']['TC_ID_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['TC_ID_MAXLEN']; ?>
" /></td>
		</tr>
		<tr>
			<td><?php echo $this->_tpl_vars['labels']['log_message']; ?>
</td>
			<td><input type="text" name="log_message" id="log_message" 
					   size="<?php echo $this->_config[0]['vars']['LOGMSG_SIZE']; ?>
" maxlength="<?php echo $this->_config[0]['vars']['LOGMSG_MAXLEN']; ?>
" /></td>
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
	  
		
	  		
		
  			      
	</table>
	
	<p style="padding-left: 20px;">
		
		<input type="submit" name="doSearch" value="<?php echo $this->_tpl_vars['labels']['btn_find']; ?>
" />
	</p>
</form>

</div>
</body>
</html>