<?php
/*
Plugin Name: BuddyPress login redirect
Description: allows the buddypress site admins to decide where to redirect their users after login
Author: Jatinder Pal Singh
Author URI: http://www.appinstore.com
Plugin URI: http://www.appinstore.com/buddypress-login-redirect/
Version: 1.0
*/
function bp_login_redirect($redirect_url,$request_url,$user)
{
	global $bp;
	$selected_option = get_option('blr_select_redirection');
	if($selected_option == 'one')
	{
		return bp_core_get_user_domain($user->ID);
	}
	elseif($selected_option=='two')
	{
		$activity_slug = bp_get_activity_root_slug();
		$redirect_url = $bp->root_domain."/".$activity_slug;
		return $redirect_url;
	}
	else
	{
		$activity_slug = bp_get_activity_root_slug();
		$friends_activity = bp_core_get_user_domain($user->ID).$activity_slug."/friends/";
		return $friends_activity;
	}
}
function bp_login_redirect_menu()
{
	add_options_page(__('BP Login Redirect Settings','blr-menu'), __('BP Login Redirect Settings','blr-menu'), 'manage_options', 'blrmenu', 'blr_settings_page');
}
function blr_settings_page()
{
	if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }
	$opt_name = 'blr_select_redirection';
	$hidden_field_name = 'blr_submit_hidden';
	$data_field_name = 'blr_select_redirection';
	
	$opt_val = get_option($opt_name);
	
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' )
	{
		$opt_val = $_POST[ $data_field_name ];
		update_option( $opt_name, $opt_val );
?>
<div class="updated"><p><strong><?php _e('settings saved.', 'blr-menu' ); ?></strong></p></div>
<?php

    }
	    echo '<div class="wrap">';
		echo "<h2>" . __( 'BuddyPress Login Redirect Settings', 'blr-menu' ) . "</h2>";
?>
<p>Using following option, you can decide where to redirect the users after login.</p>
<form name="bpahp-settings-form" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<p><b>You have selected:</b> 
<?php 
	if($opt_val=='one')
		echo 'Personal Profile / Personal Activity';
	elseif($opt_val=='two')
		echo 'Site Wide Activity';
	else
		echo "Friends' Activity";
	
?><br /> <hr />
<?php _e("Where to redirect:", 'bpahp-menu' ); ?> 
<select name="<?php echo $data_field_name; ?>">
	<option value="one">Personal Profile / Personal Activity</option>
	<option value="two">Site Wide Activity</option>
    <option value="three">Friends' Activity</option>
</select>
</p>
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>
</form>
<hr />
<b> If you like my work, kindly support me to keep my blog working by donating a small amount. For helping me and donation, <a href="http://www.appinstore.com/donate-please/">click here<img src="http://www.appinstore.com/wp-content/uploads/2012/04/donate.png" alt="donate now" /></a></b>
<p><h2><u>My other plugins:</u></h2></p>
<ul>
<li><a href="http://www.appinstore.com/force-post-category-selection/">Force Post Category Selection</a></li>
<li><a href="http://www.appinstore.com/force-post-title/">Force Post Title</a></li>
<li><a href="http://www.appinstore.com/bp-profie-as-homepage-0-6/">BP Profile as Homepage</li>
<li><a href="http://www.appinstore.com/schedule-your-content/">Schedule your content</a></li>
</ul>
</div>
<?php
}	
add_action('admin_menu','bp_login_redirect_menu');
add_filter("login_redirect","bp_login_redirect",100,3);
?>