<?php
/*
Plugin Name: Autoclose Comments
Plugin URI: http://www.milchrausch.de/wordpress-plugin-autoclose-comments/
Description: Auto Close Comments closed the comment function of your posts after a freely definable time intervals. This prevents the old posts of your blogs will be overwhelmed with spam.
Version: 1.0
Author: Hauke Leweling
Author URI: http://www.milchrausch.de
Update Server: http://wordpress.org/extend/plugins/autoclose-comments/
Min WP Version: 2.7.0
Max WP Version: 2.7.1
*/

if(isset($_POST['speichern_autoclose_comments']))
{
	update_option("autoclose_comments_after",$_POST['autoclose_comments_after']);
}
function autoclose_comments_option_page()
{

	$ifs_comments_admin_page = "
			<div class=\"wrap\">
				<h2>Wordpress Autoclose Comments</h2>
				<form name=\"autoclose_comments_config\" action=\"".get_settings("siteurl")."/wp-admin/options-general.php?page=autoclose_comments/autoclose_comments.php\" method=\"post\">
					<table>
						<tr>
							<td>close Comments after:</td>
							<td><input type=\"text\" name=\"autoclose_comments_after\" value=\"".get_option("autoclose_comments_after")."\"/>days</td>
						</tr>
						<tr>
							<td colspan=\"2\"><input style=\"width:400px;\" type=\"submit\" name=\"speichern_autoclose_comments\" value=\"speichern\"/></td>
						</tr>
					</table>
				</form>
			</div>
	";
	echo $ifs_comments_admin_page;
}
function autoclose_comments_add_menu()
{
	add_options_page('autoclose_comments', 'Autoclose Comments', 9, __FILE__, 'autoclose_comments_option_page');
}
function autoclose_comments_activate()
{
	add_option("autoclose_comments_after","30");
}
function autoclose_comments($posts) 
{
	if(!is_single())
	{
		return $posts;
	}
	
	if(time() - strtotime( $posts[0]->post_date_gmt ) > (get_option("autoclose_comments_after") * 24 * 60 * 60 )) 
	{
		$posts[0]->comment_status = 'closed';
		$posts[0]->ping_status    = 'closed';
	}
	return $posts;
}
add_filter( 'the_posts', 'autoclose_comments' );

add_action("admin_menu", "autoclose_comments_add_menu");
?>