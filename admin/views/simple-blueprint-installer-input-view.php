<?php
/**
 * Provide a input view for the installer
 *
 * This file should primarily consist of HTML with a little bit of PHP.
 *
 * @link       https://pablocianes.com/
 * @since      1.0.0
 *
 * @package    Simple_Blueprint_Installer
 * @subpackage Simple_Blueprint_Installer/admin/views
 */

?>
<p class="install-help"><?php esc_html_e( 'Set your own list of plugins by writing theirs slugs separated by commas.', 'simple-blueprint-installer' ); ?></p>
<form action="#" method="post">
	<input type="search" id="blueprint_plugins" name="blueprint_plugins" value="<?php echo esc_html( $plugins_string ); ?>">
	<input type="submit" class="button button-primary" value="<?php esc_html_e( 'Set plugins', 'simple-blueprint-installer' ); ?>">
	<input type="hidden" id="blueprint_register_nonce" name="blueprint_register_nonce" value="<?php echo wp_create_nonce('blueprint_generate_nonce'); ?>" />
</form>
<div id="the-list-blueprint">
