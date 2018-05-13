<?php
/**
 * Provide a input view for the settings page
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
<div class="wrap">
	<h1><?php esc_html_e( 'Quick global settings', 'simple-blueprint-installer' ); ?></h1>
	<form id= "sbi_setup_form" class="sbi_setup_form" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
		<h2 class="title"><?php esc_html_e( 'Cleaning Tasks', 'simple-blueprint-installer' ); ?></h2>
		<p><?php esc_html_e( 'All checked items will be permanently deleted.', 'simple-blueprint-installer' ); ?></p>
		<table class="form-table">
			<?php if (  ! empty( $default_post ) ) : ?>
			<tr>
				<td><label><input name="hello" type="checkbox" checked><code><?php echo esc_html( $default_post ); ?></code></label></td>
			</tr>
			<?php
				else :
				esc_html_e( '- Post with id=1 already deleted.', 'simple-blueprint-installer' );
				echo '<br>';
				endif;
			?>
			<?php if ( ! empty( $default_page ) ) : ?>
			<tr>
				<td><label><input name="sample" type="checkbox" checked><code><?php echo esc_html( $default_page ); ?></code></label></td>
			</tr>
			<?php
				else :
				esc_html_e( '- Page with id=2 already deleted.', 'simple-blueprint-installer' );
				echo '<br>';
				endif;
			?>
			<?php if ( $themes ) : ?>
			<tr>
				<td><label><input name="themes" type="checkbox" checked><?php esc_html_e( '- All themes to delete:', 'simple-blueprint-installer' ); ?><?php echo wp_kses( $themes_names, $allow_html ); ?></label></td>
			</tr>
			<?php
				else :
				esc_html_e( '- All themes already deleted. Only current theme available: ', 'simple-blueprint-installer' );
				echo '<code>' . $current_theme . '</code><br>';
				endif;
			?>
			<?php if ( '' != $files_to_delete ) : ?>
			<tr>
				<td><label><input name="files" type="checkbox" checked><?php esc_html_e( '- WordPress unnecessary core files to deleted:', 'simple-blueprint-installer' ); ?><?php echo wp_kses( $files_to_delete, $allow_html ); ?></label></td>
			</tr>
			<?php endif; ?>
			<?php if ( '' != $files_already_delete ) :
				esc_html_e( '- WordPress unnecessary core files already deleted: ', 'simple-blueprint-installer' ); ?><?php echo wp_kses( $files_already_delete, $allow_html );
				endif; ?>
		</table>

		<h2 class="title"><?php esc_html_e( 'Other Tasks', 'simple-blueprint-installer' ); ?></h2>
		<table class="form-table">
			<?php if ( $pings ) : ?>
			<tr>
				<td><label><input name="pings" type="checkbox" checked><?php esc_html_e( 'Disable pings, trackbacks and comments on new articles.', 'simple-blueprint-installer' ); ?></label></td>
			</tr>
			<?php endif; ?>
			<tr>
				<td><label><input name="deactivate" type="checkbox"><?php esc_html_e( 'Deactivate this plugin upon completion. ( You need to manually delete this plugin after it is deactivated to remove it. )', 'simple-blueprint-installer' ); ?></label></td>
			</tr>
		</table>
		<table class="form-table">
			<tr>
				<th><label for="category"><?php esc_html_e( 'Default post category', 'simple-blueprint-installer' ); ?></label></th>
				<td><input type="text" id="category" name="category" value="<?php echo esc_html( $category_name ); ?>" class="regular-text code"></td>
			</tr>
			<tr>
				<th><label for="tag_base"><code><?php echo get_site_url(); ?></code></label></th>
				<td>
				<input type="text" id="permalink" name="permalink" value="<?php echo esc_html( $permalink ); ?>" class="regular-text code">
			</tr>
		</table>
		<ul role="list" id="sbi-permalink-list">
			<?php foreach ( $available_tags as $tag ) : ?>
				<li><button type="button" class="button button-secondary"><?php echo '%' . $tag . '%'; ?></button></li>
			<?php endforeach; ?>
		</ul>
		<input type="hidden" name="action" value="sbi_setup_form">
		<?php wp_nonce_field( 'sbi_setup_form', 'sbi_setup_nonce' ); ?>
		<?php submit_button( esc_html__( 'Do these actions', 'simple-blueprint-installer' ) ); ?>
	</form>
</div>
