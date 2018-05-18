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
	<form id= "sbi_setup_form" class="sbi_setup_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<h2 class="title"><?php esc_html_e( 'Cleaning Tasks', 'simple-blueprint-installer' ); ?></h2>
		<p class="sbi-danger"><?php esc_html_e( 'All checked items will be permanently deleted.', 'simple-blueprint-installer' ); ?></p>
		<table class="form-table">
			<?php if ( ! empty( $default_post ) ) : ?>
			<tr>
				<td><label><input name="hello" type="checkbox" checked /><code><?php echo esc_html( $default_post ); ?></code></label></td>
			</tr>
			<?php
				else :
					esc_html_e( '- Post with id=1 already deleted.', 'simple-blueprint-installer' );
					echo '<br>';
				endif;
			?>
			<?php if ( ! empty( $default_page ) ) : ?>
			<tr>
				<td><label><input name="sample" type="checkbox" checked /><code><?php echo esc_html( $default_page ); ?></code></label></td>
			</tr>
			<?php
				else :
					esc_html_e( '- Page with id=2 already deleted.', 'simple-blueprint-installer' );
					echo '<br>';
				endif;
			?>
			<?php if ( $themes ) : ?>
			<tr>
				<td><label><input name="themes" type="checkbox" checked /><?php esc_html_e( '- All themes to delete:', 'simple-blueprint-installer' ); ?><?php echo wp_kses( $themes_names, $allow_html ); ?></label></td>
			</tr>
			<?php
				else :
					esc_html_e( '- All themes already deleted. Only current theme available: ', 'simple-blueprint-installer' );
					echo '<code>' . esc_html( $current_theme ) . '</code><br>';
				endif;
			?>
			<?php if ( '' !== $files_to_delete ) : ?>
			<tr>
				<td><label><input name="files" type="checkbox" checked /><?php esc_html_e( '- WordPress unnecessary core files to deleted:', 'simple-blueprint-installer' ); ?><?php echo wp_kses( $files_to_delete, $allow_html ); ?></label></td>
			</tr>
			<?php endif; ?>
			<?php
			if ( '' !== $files_already_delete ) :
				esc_html_e( '- WordPress unnecessary core files already deleted: ', 'simple-blueprint-installer' );
				echo wp_kses( $files_already_delete, $allow_html );
				endif;
				?>
		</table>

		<h2 class="title"><?php esc_html_e( 'Other Tasks', 'simple-blueprint-installer' ); ?></h2>
		<table class="form-table">
			<?php if ( ! empty( $languages ) || ! empty( $translations ) ) : ?>
			<tr>
				<th scope="row"><label for="WPLANG"><?php esc_html_e( 'Site Language', 'simple-blueprint-installer' ); ?></label></th>
				<td>
				<?php
				wp_dropdown_languages(
					array(
						'name'                        => 'WPLANG',
						'id'                          => 'WPLANG',
						'selected'                    => $locale,
						'languages'                   => $languages,
						'translations'                => $translations,
						'show_available_translations' => current_user_can( 'install_languages' ) && wp_can_install_language_pack(),
					)
				);
					?>
				</td>
			</tr>
			<?php endif; ?>
			<tr>
				<th scope="row"><label for="timezone_string"><?php esc_html_e( 'Timezone', 'simple-blueprint-installer' ); ?></label></th>
				<td>
					<select id="timezone_string" name="timezone_string" aria-describedby="timezone-description">
						<?php echo wp_kses( wp_timezone_choice( $tzstring, get_user_locale() ), $allowed_html ); ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Date Format', 'simple-blueprint-installer' ); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php esc_html_e( 'Date Format', 'simple-blueprint-installer' ); ?></span></legend>
						<?php
						foreach ( $date_formats as $format ) {
							echo "\t<label><input type='radio' name='date_format' value='" . esc_attr( $format ) . "'";
							if ( get_option( 'date_format' ) === $format ) { // checked() uses "==" rather than "===".
								echo " checked='checked'";
								$custom = false;
							}
							echo ' /> <span class="date-time-text format-i18n">' . esc_html( date_i18n( $format ) ) . '</span><code>' . esc_html( $format ) . "</code></label><br />\n";
						}
						?>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Time Format', 'simple-blueprint-installer' ); ?></th>
				<td>
					<fieldset>
						<legend class="screen-reader-text"><span><?php esc_html_e( 'Time Format', 'simple-blueprint-installer' ); ?></span></legend>
						<?php
						foreach ( $time_formats as $format ) {
							echo "\t<label><input type='radio' name='time_format' value='" . esc_attr( $format ) . "'";
							if ( get_option( 'time_format' ) === $format ) { // checked() uses "==" rather than "===".
								echo " checked='checked'";
								$custom = false;
							}
							echo ' /> <span class="date-time-text format-i18n">' . esc_html( date_i18n( $format ) ) . '</span><code>' . esc_html( $format ) . "</code></label><br />\n";
						}
						?>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th><label for="category"><?php esc_html_e( 'Default post category', 'simple-blueprint-installer' ); ?></label></th>
				<td><input type="text" id="category" name="category" value="<?php echo esc_html( $category_name ); ?>" class="regular-text code"></td>
			</tr>
			<tr>
				<th><label for="category_base"><?php esc_html_e( 'Category base', 'simple-blueprint-installer' ); ?></label></th>
				<td><input type="text" id="category_base" name="category_base" value="<?php echo esc_html( $category_base ); ?>" class="regular-text code"></td>
			</tr>
			<tr>
				<th><label for="tag_base"><?php esc_html_e( 'Tag base', 'simple-blueprint-installer' ); ?></label></th>
				<td>
				<input type="text" id="tag_base" name="tag_base" value="<?php echo esc_html( $tag_base ); ?>" class="regular-text code">
			</tr>
			<tr>
				<th><label for="permalink"><code><?php echo esc_url( get_site_url() ); ?></code></label></th>
				<td>
				<input type="text" id="permalink" name="permalink" value="<?php echo esc_html( $permalink ); ?>" class="regular-text code">
			</tr>
		</table>
		<ul role="list" id="sbi-permalink-list">
			<?php foreach ( $available_tags as $tag ) : ?>
				<li><button type="button" class="button button-secondary"><?php echo '%' . esc_html( $tag ) . '%'; ?></button></li>
			<?php endforeach; ?>
		</ul>
		<table class="form-table">
			<?php if ( $pings ) : ?>
			<tr>
				<td><label><input name="pings" type="checkbox" checked /><?php esc_html_e( 'Disable pings, trackbacks and comments on new articles.', 'simple-blueprint-installer' ); ?></label></td>
			</tr>
			<?php
				else :
					esc_html_e( '- Already disabled pings, trackbacks and comments on new articles.', 'simple-blueprint-installer' );
				endif;
			?>
			<tr>
				<td><label><input name="media" type="checkbox" value="1" <?php checked( '1', get_option( 'uploads_use_yearmonth_folders' ) ); ?> /><?php esc_html_e( 'Organize my uploads into month- and year-based folders.', 'simple-blueprint-installer' ); ?></label></td>
			</tr>
			<tr>
				<td><label class="sbi-danger"><input name="indexing" type="checkbox" value="0" <?php checked( '0', get_option( 'blog_public' ) ); ?> /><?php esc_html_e( 'Discourage search engines from indexing this site.', 'simple-blueprint-installer' ); ?></label></td>
			</tr>
			<tr>
				<td><label class="sbi-danger"><input name="deactivate" type="checkbox"><?php esc_html_e( 'Deactivate this plugin upon completion. ( You need to manually delete this plugin after it is deactivated to remove it. )', 'simple-blueprint-installer' ); ?></label></td>
			</tr>
		</table>
		<p class="sbi-danger"><a href="plugin-install.php?tab=sbi_blueprint"><?php esc_html_e( 'Wait a minute! Have you already installed all the plugins in this WordPress?', 'simple-blueprint-installer' ); ?></a></p>
		<input type="hidden" name="action" value="sbi_setup_form">
		<?php wp_nonce_field( 'sbi_setup_form', 'sbi_setup_nonce' ); ?>
		<?php submit_button( esc_html__( 'Do these actions', 'simple-blueprint-installer' ) ); ?>
	</form>
</div>

