<?php
/**
 * Provide a single plugin view for the installer
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
<div class="plugin-card">
	<div class="plugin-card-top">
		<div class="name column-name">
			<h3><?php echo esc_html( $api->name ); ?><img class="plugin-icon" src="<?php echo esc_url( $icon ); ?>" alt="<?php echo esc_attr( $api->slug ); ?>"></h3>
		</div>
		<div class="action-links">
			<ul class="plugin-action-buttons"><li><a class="install-now button <?php echo $button_classes; ?>" data-slug="<?php echo $api->slug; ?>" data-name="<?php echo $api->name; ?>" href="<?php echo get_admin_url(); ?>/update.php?action=install-plugin&amp;plugin=<?php echo $api->slug; ?>&amp;_wpnonce=<?php echo wp_create_nonce( 'install-plugin_' . $api->slug ); ?>"><?php echo $button_text; ?></a></li><li><a href="https://wordpress.org/plugins/<?php echo $api->slug; ?>/" target="_blank"><?php _e( 'More Details', 'simple-blueprint-installer' ); ?></a></li></ul>
		</div>
		<div class="desc column-description">
			<p><?php echo esc_html( $api->short_description ); ?></p>
			<p class="authors"><cite><?php esc_html_e( 'By', 'simple-blueprint-installer' ); ?> <?php echo $api->author; ?></cite></p>
		</div>
	</div>
</div>
