{* 
TestLink Open Source Project - http://testlink.sourceforge.net/ 
@filesource login.tpl
smarty template - login page 
*}
{lang_get var='labels' s='login_name,password,btn_login,new_user_q,lost_password_q'}
{config_load file="input_dimensions.conf" section="login"}
{include file="inc_head.tpl" title="TestLink - Login" openHead='yes'}

<script language="JavaScript" src="{$basehref}gui/niftycube/niftycube.js" type="text/javascript"></script>
{literal}
<script type="text/javascript">
window.onload=function()
{
  Nifty("div#login_div","big");
  Nifty("div.messages","normal");
  
  focusInputField('login');
}
</script>
{/literal}

</head>
<body>

<div class="login_form" id="login_div">
  {include file="inc_login_title.tpl"}

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc_login_title.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <div id="verify-form">
	      <form method="get" action="third_party/php-openid/examples/consumer/try_auth.php">
		          ElastosID:
				          <input type="hidden" name="action" value="verify" />
						          <input type="text" name="openid_identifier" <?php echo 'value="'.$_COOKIE['ElastosID'].'"'?> style="width:50%;"/>

								          <p>Openid Like: http://******.elastos.org</p>

		        <input type="submit" value="Login" />
				      </form>
  </div>

  <form method="post" name="login_form" action="login.php" style="display:none;">
    {if $gui->login_disabled eq 0}
      <div class="messages" style="width:100%;text-align:center;">{$gui->note}</div>
    <input type="hidden" name="reqURI" value="{$gui->reqURI|escape:'url'}"/>
    <input type="hidden" name="destination" value="{$gui->destination|escape:'url'}"/>
      <p class="label">{$labels.login_name}<br />
      <input type="text" name="tl_login" id="login" size="{#LOGIN_SIZE#}" maxlength="{#LOGIN_MAXLEN#}" required />
    </p>
      <p class="label">{$labels.password}<br />
      <input type="password" name="tl_password" size="{#PASSWD_SIZE#}" maxlength="{#PASSWD_SIZE#}" required />
    </p>
    <input type="submit" name="login_submit" value="{$labels.btn_login}" />
  {/if}
  </form>
  
  <p>
  {if $gui->user_self_signup}
    <a href="firstLogin.php">{$labels.new_user_q}</a><br />
  {/if}
  
  {* the configured authentication method don't allow users to reset his/her password *}    
  {if $gui->external_password_mgmt eq 0}
    <a href="lostPassword.php">{$labels.lost_password_q}</a>
  </p>
  {/if}
  
  {include file="inc_copyrightnotice.tpl"}

  {if $gui->securityNotes}
      {include file="inc_msg_from_array.tpl" array_of_msg=$gui->securityNotes arg_css_class="messages"}
  {/if}
  
  {if $tlCfg->login_info != ""}
    <div>{$tlCfg->login_info}</div>
  {/if}

</div>
</body>
</html>