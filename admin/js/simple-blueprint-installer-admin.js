var sbi_installer = sbi_installer || {};

function checkValue( value, array ){
	for ( var i = 0; i < array.length; i++ ) {
		if ( array[i] == value ) {
			return true;
			break;
		}
	}
		return false;
}

jQuery( document ).ready(
	function($) {

		"use strict";

		$( '#sbi-permalink-list .button' ).click(
			function(){
					var currentPermalink = $( '#permalink' ),
					currentButton        = $( this );

				if ( '' === currentPermalink.val() ) {
					currentPermalink.val( '/' );
				}

				if ( -1 !== currentPermalink.val().search( currentButton.html() ) ) {
					currentPermalink.val( currentPermalink.val().replace( currentButton.html() + '/', '' ) );
				} else {
					currentPermalink.val( currentPermalink.val() + currentButton.html() + '/' );
				}

			}
		);

		var isLoading = false;

		$( '#sbi-danger-button' ).click(
			function(){
					$( '.sbi-danger-buttons' ).toggle();
			}
		);

		$( '#sbi-install-button' ).click(
			function(){
					sbi_installer.operate_plugins( $( this ), 'sbi_plugins_installer', 'install' );
			}
		);

		$( '#sbi-delete-button' ).click(
			function(){
					sbi_installer.operate_plugins( $( this ), 'sbi_plugins_operate', 'delete' );
			}
		);

		$( '#sbi-activate-button' ).click(
			function(){
					sbi_installer.operate_plugins( $( this ), 'sbi_plugins_operate', 'activate' );
			}
		);

		$( '#sbi-deactivate-button' ).click(
			function(){
					sbi_installer.operate_plugins( $( this ), 'sbi_plugins_operate', 'deactivate' );
			}
		);

		/**
		 *  Install all the plugins set in the blueprint
		 *
		 *  @since 1.0.0
		 */
		sbi_installer.operate_plugins = function( button, $action, $make ){
			isLoading = true;
			button.addClass( 'installing' );

			$.ajax(
				{
					type: 'POST',
					url: sbi_installer_localize.ajax_url,
					data: {
						action: $action,
						nonce: sbi_installer_localize.admin_nonce,
						make: $make,
						dataType: 'json'
					},
					success: function(data) {
						if ( data ) {
							button.removeClass( 'installing' );
						}
						isLoading = false;
						switch ( $make ) {
							case 'install':
									$( '#the-list-blueprint .plugin-card .action-links a.button' ).each(
										function( index ) {
											var currentPlugin = $( this ),
											pluginSlug        = currentPlugin.data( 'slug' );
											if ( checkValue( pluginSlug, data.installed ) ) {
												currentPlugin.attr( 'class', 'activate button button-primary' );
												currentPlugin.html( sbi_installer_localize.activate_btn );
											}
										}
									);
								break;
							case 'delete':
								$( '.plugin-card' ).remove();
								break;
							case 'activate':
								$( '#the-list-blueprint .plugin-card .action-links a.button' ).each(
									function( index ) {
											var currentPlugin = $( this ),
											pluginSlug        = currentPlugin.data( 'slug' );
										if ( checkValue( pluginSlug, data.activate ) && currentPlugin.hasClass( 'activate' ) ) {
											currentPlugin.attr( 'class', 'installed button disabled' );
											currentPlugin.html( sbi_installer_localize.installed_btn );
										}
									}
								);
								break;
							case 'deactivate':
								$( '#the-list-blueprint .plugin-card .action-links a.button' ).each(
									function( index ) {
											var currentPlugin = $( this ),
											pluginSlug        = currentPlugin.data( 'slug' );
										if ( currentPlugin.hasClass( 'disabled' ) ) {
											currentPlugin.attr( 'class', 'activate button button-primary' );
											currentPlugin.html( sbi_installer_localize.activate_btn );
										}
									}
								);
								break;
							default:
								break;
						}
					},
					error: function( xhr, status, error ) {
						console.log( status );
						button.removeClass( 'installing' );
						isLoading = false;
						location.reload(true);
					}
				}
			);
		}

		/**
		*  Install the plugin
		*
		*  @param object currentPlugin Button element
		*  @param string pluginSlug Plugin slug
		*  @since 1.0.0
		*/
		sbi_installer.install_plugin = function( currentPlugin, pluginSlug ){

			isLoading = true;
			currentPlugin.addClass( 'installing' );

			$.ajax(
				{
					type: 'POST',
					url: sbi_installer_localize.ajax_url,
					data: {
						action: 'sbi_plugin_installer',
						plugin: pluginSlug,
						nonce: sbi_installer_localize.admin_nonce,
						dataType: 'json'
					},
					success: function(data) {
						if ( data ) {
							if ( data.status === 'success' ) {
								currentPlugin.attr( 'class', 'activate button button-primary' );
								currentPlugin.html( sbi_installer_localize.activate_btn );
							} else {
								currentPlugin.removeClass( 'installing' );
							}
						} else {
							currentPlugin.removeClass( 'installing' );
						}
						isLoading = false;
					},
					error: function( xhr, status, error ) {
						console.log( status );
						currentPlugin.removeClass( 'installing' );
						isLoading = false;
					}
				}
			);
		}

		/**
		*  Activate the plugin
		*
		*  @param object currentPlugin Button element
		*  @param string pluginSlug Plugin slug
		*  @since 1.0.0
		*/
		sbi_installer.activate_plugin = function( currentPlugin, pluginSlug ){

			$.ajax(
				{
					type: 'POST',
					url: sbi_installer_localize.ajax_url,
					data: {
						action: 'sbi_plugin_activation',
						plugin: pluginSlug,
						nonce: sbi_installer_localize.admin_nonce,
						dataType: 'json'
					},
					success: function(data) {
						if ( data ) {
							if ( data.status === 'success' ) {
								currentPlugin.attr( 'class', 'installed button disabled' );
								currentPlugin.html( sbi_installer_localize.installed_btn );
							}
						}
						isLoading = false;
					},
					error: function( xhr, status, error ) {
						console.log( status );
						isLoading = false;
					}
				}
			);

		}

		/*
		*  Install/Activate Button Click
		*
		*  @since 1.0.0
		*/
		$( document ).on(
			'click', '#the-list-blueprint .plugin-card .action-links a.button', function(event){
				var currentPlugin = $( this ),
				pluginSlug        = currentPlugin.data( 'slug' );

				event.preventDefault();

				if ( ! currentPlugin.hasClass( 'disabled' ) ) {

					if ( isLoading ) {
						return false;
					}

					// Installation
					if ( currentPlugin.hasClass( 'install' ) ) {
						sbi_installer.install_plugin( currentPlugin, pluginSlug );
					}

					// Activation
					if ( currentPlugin.hasClass( 'activate' ) ) {
						sbi_installer.activate_plugin( currentPlugin, pluginSlug );
					}

				}
			}
		);

	}
);
