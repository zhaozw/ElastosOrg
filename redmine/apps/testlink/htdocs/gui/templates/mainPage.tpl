{* 
 Testlink Open Source Project - http://testlink.sourceforge.net/ 
 $Id: mainPage.tpl,v 1.45 2009/12/07 20:12:18 franciscom Exp $     
 Purpose: smarty template - main page / site map                 
                                                                 
 rev :                                                 
       20070523 - franciscom - nifty corners
       20070113 - franciscom - truncate on test plan name combo box
       20060908 - franciscom - removed assign risk and ownership
                               added define priority
                               added tc exec assignment
                                   
       20060819 - franciscom - changed css classes name
                               removed old comments
       
-------------------------------------------------------------------------------------- *}
{assign var="cfg_section" value=$smarty.template|replace:".tpl":""}
{config_load file="input_dimensions.conf" section=$cfg_section}
{include file="inc_head.tpl" popup="yes" openHead="yes"}

{include file="inc_ext_js.tpl"}

<script language="JavaScript" src="{$basehref}gui/niftycube/niftycube.js" type="text/javascript"></script>
{literal}
<script type="text/javascript">
window.onload=function()
{
    // Nifty("div.menu_bubble");
    if( typeof display_left_block_1 != 'undefined')
    {
        display_left_block_1();
    }

    if( typeof display_left_block_2 != 'undefined')
    {
        display_left_block_2();
    }

    if( typeof display_left_block_3 != 'undefined')
    {
        display_left_block_3();
    }
    
    if( typeof display_left_block_4 != 'undefined')
    {
        display_left_block_4();
    }

    display_left_block_5();

    if( typeof display_right_block_1 != 'undefined')
    {
        display_right_block_1();
    }

    if( typeof display_right_block_2 != 'undefined')
    {
        display_right_block_2();
    }

    if( typeof display_right_block_3 != 'undefined')
    {
        display_right_block_3();
    }
   
}
</script>
{/literal}
</head>

<body>
{if $gui->securityNotes}
    {include file="inc_msg_from_array.tpl" array_of_msg=$gui->securityNotes arg_css_class="warning"}
{/if}

{* ----- Right Column ------------- *}
{include file="mainPageRight.tpl"}

{* ----- Left Column -------------- *}
{include file="mainPageLeft.tpl"}

<div id="elfooter">
<table frame="hsides" border="0" width="2048" style="float:left;">
<tr>
<td width="40">
  <a href="http://elastos.org" target="_top"><img src="/elorg_common/img/ElastosOrg_RedLogo.png" style="vertical-align:middle;"/></a>
</td><td width="168">
  <ul id="footernav1">
    <li>
      <a href="http://elastos.org/project/" target="_top">Project</a>
    </li>
    <li>
      <a href="http://elastos.org/wiki/" target="_top">Documentation</a>
    </li>
  </ul>
</td><td>
  <ul id="footernav2">
    <li>
      <a href="http://elastos.org/review/" target="_top">Code Review</a>
    </li>
    <li>
      <a href="http://elastos.org/jenkins/" target="_top">CI, Continuous Integration</a>
    </li>
  </ul>
</td>
</tr>
</table>
</div>

</body>
</html>