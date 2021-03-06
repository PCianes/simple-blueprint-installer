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
<div class="sbi-input-actions">
	<p class="install-help"><?php esc_html_e( 'Set your own list of plugins by writing theirs slugs separated by commas.', 'simple-blueprint-installer' ); ?></p>
	<form action="#" method="post">
		<textarea rows="2" type="search" id="blueprint_plugins" name="blueprint_plugins"><?php echo esc_html( $plugins_string ); ?></textarea>
		<input type="submit" class="button button-primary" value="<?php esc_html_e( 'Set plugins', 'simple-blueprint-installer' ); ?>">
		<input type="hidden" id="blueprint_register_nonce" name="blueprint_register_nonce" value="<?php echo esc_attr( wp_create_nonce( 'blueprint_generate_nonce' ) ); ?>" />
	</form>
	<p class="sbi-danger"><a href="plugin-install.php?tab=sbi_setup"><?php esc_html_e( 'Have you already cleaned and configured this WordPress?', 'simple-blueprint-installer' ); ?></a></p>
</div>
<div class="sbi-button-actions">
	<form action="#" method="post">
		<input type="submit" class="button button-primary" value="<?php esc_html_e( 'Reset & Update', 'simple-blueprint-installer' ); ?>">
		<input type="hidden" id="blueprint_register_nonce_update" name="blueprint_register_nonce_update" value="<?php echo esc_attr( wp_create_nonce( 'blueprint_generate_nonce_update' ) ); ?>" />
		<input type="hidden" id="sbi-update" name="sbi-update" value="sbi-update"/>
	</form>
	<a href="#" id="sbi-danger-button" class="button button-primary"><?php esc_html_e( 'Show & hide \'danger\' buttons', 'simple-blueprint-installer' ); ?></a>
</div>
<div class="sbi-button-actions sbi-danger-buttons" style="display: none;">
	<a href="#" id="sbi-delete-button" class="button sbi-delete-button"><?php esc_html_e( '¡Delete all plugins!', 'simple-blueprint-installer' ); ?></a>
	<a href="#" id="sbi-install-button" class="button"><?php esc_html_e( 'Install all plugins set here', 'simple-blueprint-installer' ); ?></a>
	<a href="#" id="sbi-activate-button" class="button"><?php esc_html_e( 'Activate all plugins set here', 'simple-blueprint-installer' ); ?></a>
	<a href="#" id="sbi-deactivate-button" class="button sbi-delete-button"><?php esc_html_e( '¡Deactivate all plugins!', 'simple-blueprint-installer' ); ?></a>
</div>
<div class="sbi-button-actions sbi-danger-buttons" style="display: none;">
	<p class="install-help"><?php esc_html_e( 'Be careful when using these buttons. It depends on the number of plugins established and the behaviors they have when they install the behavior of these actions can be unpredictable, and even generate errors. Please make a backup befor use it. It´s safer to go step by step.', 'simple-blueprint-installer' ); ?></p>
</div>
<div id="the-list-blueprint">
