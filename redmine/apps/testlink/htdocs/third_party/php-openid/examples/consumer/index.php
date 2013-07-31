<?php
require_once "common.php";

global $pape_policy_uris;
?>
<html>
  <head><title>OpenID Authentication</title></head>
  <style type="text/css">
      * {
        font-family: verdana,sans-serif;
      }
      body {
        width: 50em;
        margin: 1em;
      }
      div {
        padding: .5em;
      }
      table {
        margin: none;
        padding: none;
      }
      .alert {
        border: 1px solid #e7dc2b;
        background: #fff888;
      }
      .success {
        border: 1px solid #669966;
        background: #88ff88;
      }
      .error {
        border: 1px solid #ff0000;
        background: #ffaaaa;
      }
      #verify-form {
        border: 1px solid #777777;
        background: #dddddd;
        margin-top: 1em;
        padding-bottom: 0em;
      }
  </style>
  <body>
    <h1>OpenID Authentication</h1>

    <?php if (isset($msg)) { print "<div class=\"alert\">$msg</div>"; } ?>
    <?php if (isset($error)) { print "<div class=\"error\">$error</div>"; } ?>
    <?php if (isset($success)) { print "<div class=\"success\">$success</div>"; } ?>

    <div id="verify-form" style="display:none;">
      <form method="get" action="try_auth.php">
        Identity&nbsp;URL:
        <input type="hidden" name="action" value="verify" />
        <input type="text" name="openid_identifier" value="" />

        <p>Optionally, request these PAPE policies:</p>
        <p>
        <?php foreach ($pape_policy_uris as $i => $uri) {
          print "<input type=\"checkbox\" name=\"policies[]\" value=\"$uri\" />";
          print "$uri<br/>";
        } ?>
        </p>

        <input type="submit" value="Verify" />
      </form>
    </div>
  </body>
</html>
