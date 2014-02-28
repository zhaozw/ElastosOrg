<div class="wrap">
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32 icon32-posts-post"><br>
    </div>
    <h2><?php _e('Popup contact form', 'popup-contact'); ?></h2>
    <?php
	$PopupContact_title = get_option('PopupContact_title');
	$PopupContact_On_Homepage = get_option('PopupContact_On_Homepage');
	$PopupContact_On_Posts = get_option('PopupContact_On_Posts');
	$PopupContact_On_Pages = get_option('PopupContact_On_Pages');
	$PopupContact_On_Search = get_option('PopupContact_On_Search');
	$PopupContact_On_Archives = get_option('PopupContact_On_Archives');
	$PopupContact_On_MyEmail = get_option('PopupContact_On_MyEmail');
	$PopupContact_On_Subject = get_option('PopupContact_On_Subject');
	$PopupContact_Caption = get_option('PopupContact_Caption');
	
	if (isset($_POST['PopupContact_form_submit']) && $_POST['PopupContact_form_submit'] == 'yes')
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('PopupContact_form_setting');
			
		$PopupContact_title = stripslashes($_POST['PopupContact_title']);
		$PopupContact_On_Homepage = stripslashes($_POST['PopupContact_On_Homepage']);
		$PopupContact_On_Posts = stripslashes($_POST['PopupContact_On_Posts']);
		$PopupContact_On_Pages = stripslashes($_POST['PopupContact_On_Pages']);
		$PopupContact_On_Search = stripslashes($_POST['PopupContact_On_Search']);
		$PopupContact_On_Archives = stripslashes($_POST['PopupContact_On_Archives']);
		$PopupContact_On_MyEmail = stripslashes($_POST['PopupContact_On_MyEmail']);
		$PopupContact_On_Subject = stripslashes($_POST['PopupContact_On_Subject']);
		$PopupContact_Caption = stripslashes($_POST['PopupContact_Caption']);
		
		update_option('PopupContact_title', $PopupContact_title );
		update_option('PopupContact_On_Homepage', $PopupContact_On_Homepage );
		update_option('PopupContact_On_Posts', $PopupContact_On_Posts );
		update_option('PopupContact_On_Pages', $PopupContact_On_Pages );
		update_option('PopupContact_On_Search', $PopupContact_On_Search );
		update_option('PopupContact_On_Archives', $PopupContact_On_Archives );
		update_option('PopupContact_On_MyEmail', $PopupContact_On_MyEmail );
		update_option('PopupContact_On_Subject', $PopupContact_On_Subject );
		update_option('PopupContact_Caption', $PopupContact_Caption );
		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'popup-contact'); ?></strong></p>
		</div>
		<?php
	}
	?>
	<h3><?php _e('Popup email setting', 'popup-contact'); ?></h3>
	<form name="sdp_form" method="post" action="">
	
		<label for="tag-image"><?php _e('Email address', 'popup-contact'); ?></label>
		<input name="PopupContact_On_MyEmail" type="text" id="PopupContact_On_MyEmail" value="<?php echo $PopupContact_On_MyEmail; ?>" size="75" />
		<p><?php _e('Please enter admin email address to receive mails.', 'popup-contact'); ?></p>
		
		<label for="tag-image"><?php _e('Email subject', 'popup-contact'); ?></label>
		<input name="PopupContact_On_Subject" type="text" id="PopupContact_On_Subject" value="<?php echo $PopupContact_On_Subject; ?>" size="75"  />
		<p><?php _e('Please enter mail subject.', 'popup-contact'); ?></p>
		
		<label for="tag-image"><?php _e('Link Button / Text', 'popup-contact'); ?></label>
		<input name="PopupContact_Caption" type="text" id="PopupContact_Caption" value="<?php echo $PopupContact_Caption; ?>" size="100"  />
		<p><?php _e('This box is to add the contact us Image Button or Text, Entered value will display in the front end.', 'popup-contact'); ?></p>
	
		<div style="height:5px;"></div>
		<h3><?php _e('Popup widget setting', 'popup-contact'); ?></h3>
		
		<label for="tag-title"><?php _e('Popup title', 'popup-contact'); ?></label>
		<input name="PopupContact_title" type="text" id="PopupContact_title" value="<?php echo $PopupContact_title; ?>" />
		<p><?php _e('Please enter popup box title.', 'popup-contact'); ?></p>
		
		<label for="tag-title"><?php _e('On home page display', 'popup-contact'); ?></label>
		<select name="PopupContact_On_Homepage" id="PopupContact_On_Homepage">
			<option value='YES' <?php if($PopupContact_On_Homepage == 'YES') { echo 'selected' ; } ?>>YES</option>
			<option value='NO' <?php if($PopupContact_On_Homepage == 'NO') { echo 'selected' ; } ?>>NO</option>
		</select>
		<p><?php _e('Select YES if you need to display on home page.', 'popup-contact'); ?></p>
		
		<label for="tag-title"><?php _e('On posts display', 'popup-contact'); ?></label>
		<select name="PopupContact_On_Posts" id="PopupContact_On_Posts">
			<option value='YES' <?php if($PopupContact_On_Posts == 'YES') { echo 'selected' ; } ?>>YES</option>
			<option value='NO' <?php if($PopupContact_On_Posts == 'NO') { echo 'selected' ; } ?>>NO</option>
		</select>
		<p><?php _e('Select YES if you need to display on posts.', 'popup-contact'); ?></p>
		
		<label for="tag-title"><?php _e('On pages display', 'popup-contact'); ?></label>
		<select name="PopupContact_On_Pages" id="PopupContact_On_Pages">
			<option value='YES' <?php if($PopupContact_On_Pages == 'YES') { echo 'selected' ; } ?>>YES</option>
			<option value='NO' <?php if($PopupContact_On_Pages == 'NO') { echo 'selected' ; } ?>>NO</option>
		</select>
		<p><?php _e('Select YES if you need to display on wordpress pages.', 'popup-contact'); ?></p>
		
		<label for="tag-title"><?php _e('On search page display', 'popup-contact'); ?></label>
		<select name="PopupContact_On_Search" id="PopupContact_On_Search">
			<option value='YES' <?php if($PopupContact_On_Search == 'YES') { echo 'selected' ; } ?>>YES</option>
			<option value='NO' <?php if($PopupContact_On_Search == 'NO') { echo 'selected' ; } ?>>NO</option>
		</select>
		<p><?php _e('Select YES if you need to display on search pages.', 'popup-contact'); ?></p>
		
		<label for="tag-title"><?php _e('On archive page display', 'popup-contact'); ?></label>
		<select name="PopupContact_On_Archives" id="PopupContact_On_Archives">
			<option value='YES' <?php if($PopupContact_On_Archives == 'YES') { echo 'selected' ; } ?>>YES</option>
			<option value='NO' <?php if($PopupContact_On_Archives == 'NO') { echo 'selected' ; } ?>>NO</option>
		</select>
		<p><?php _e('Select YES if you need to display on archive pages.', 'popup-contact'); ?></p>
		
		<br />		
		<input type="hidden" name="PopupContact_form_submit" value="yes"/>
		<input name="PopupContact_submit" id="PopupContact_submit" class="button add-new-h2" value="<?php _e('Update All Details', 'popup-contact'); ?>" type="submit" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="window.open('http://www.gopiplus.com/work/2012/05/18/popup-contact-form-wordpress-plugin/');" value="<?php _e('Help', 'popup-contact'); ?>" type="button" />
		<?php wp_nonce_field('PopupContact_form_setting'); ?>
	</form>
  </div>
  <h3><?php _e('Plugin configuration option', 'popup-contact'); ?></h3>
	<ol>
		<li><?php _e('Drag and drop the plugin widget to your sidebar.', 'popup-contact'); ?></li>
		<li><?php _e('Add plugin in the posts or pages using short code.', 'popup-contact'); ?></li>
		<li><?php _e('Add directly in to the theme using PHP code.', 'popup-contact'); ?></li>
	</ol>
  <p class="description"><?php _e('Check official website for more information', 'popup-contact'); ?> 
  <a target="_blank" href="http://www.gopiplus.com/work/2012/05/18/popup-contact-form-wordpress-plugin/"><?php _e('click here', 'popup-contact'); ?></a></p>
</div>
