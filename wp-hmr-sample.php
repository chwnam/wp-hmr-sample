<?php
/**
 * Plugin Name:       WP HMR Sample
 * Plugin URI:        https://github.com/chwnam/wp-hmr-sample
 * Description:       React HMR (Hot Module Replacemnet) Sample Plugin.
 * Author:            changwoo
 * Author URI:        https://blog.changwoo.pe.kr
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Requires at least: 5.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const WP_HMR_MAIN    = __FILE__;
const WP_HMR_VERSION = '1.0.0';

if ( ! function_exists( 'wp_hmr_register_scripts' ) ) {
	/**
	 * Register required scripts.
	 *
	 * Gutenberg plugin does not have to be activated.
	 * Two handles are registered: wp-react-refresh-runtime, wp-hmr-sample
	 *
	 * @return void
	 */
	function wp_hmr_register_scripts(): void {
		$gutenberg_path  = WP_PLUGIN_DIR . '/gutenberg/build/react-refresh-runtime';
		$gutenberg_asset = "$gutenberg_path/index.min.asset.php";

		if ( file_exists( $gutenberg_asset ) && is_readable( $gutenberg_asset ) ) {
			$asset = include $gutenberg_asset;
			if ( is_array( $asset ) && ! wp_script_is( 'wp-react-refresh-runtime', 'regustered' ) ) {
				wp_register_script(
					'wp-react-refresh-runtime',
					WP_PLUGIN_URL . '/gutenberg/build/react-refresh-runtime/index.min.js',
					$asset['dependencies'] ?? [],
					$asset['version'] ?? false,
					true
				);
			}
		}

		$index_asset = plugin_dir_path( WP_HMR_MAIN ) . 'build/index.asset.php';

		if ( file_exists( $index_asset ) && is_readable( $index_asset ) ) {
			$asset = include $index_asset;
			if ( is_array( $asset ) && ! wp_script_is( 'wp-hmr-sample', 'registered' ) ) {
				wp_register_script(
					'wp-hmr-sample',
					plugins_url( 'build/index.js', WP_HMR_MAIN ),
					$asset['dependencies'] ?? [],
					$asset['version'] ?? false,
					true
				);
			}
		}
	}
}

add_action( 'init', 'wp_hmr_register_scripts', 200 );


if ( ! function_exists( 'wp_hmr_activation' ) ) {
	/**
	 * Activation callback. Create a sample page.
	 *
	 * @return void
	 */
	function wp_hmr_activation() {
		$page = get_page_by_path( 'wp-hmr-sample' );
		if ( ! $page ) {
			wp_insert_post(
				[
					'post_title'   => 'WP HMR Sample',
					'post_content' => '',
					'post_status'  => 'publish',
					'post_type'    => 'page',
				]
			);
		}
	}
}

register_activation_hook( WP_HMR_MAIN, 'wp_hmr_activation' );


if ( ! function_exists( 'wp_hmr_deactivation' ) ) {
	/**
	 * Deactivation callback. Remove the sample page.
	 *
	 * @return void
	 */
	function wp_hmr_deactivation() {
		$page = get_page_by_path( 'wp-hmr-sample' );
		if ( $page ) {
			wp_delete_post( $page->ID, true );
		}
	}
}

register_deactivation_hook( WP_HMR_MAIN, 'wp_hmr_activation' );


if ( ! function_exists( 'wp_hmr_page_setup' ) ) {
	/**
	 * Kick off script and override post content for the sample page.
	 *
	 * @return void
	 */
	function wp_hmr_page_setup() {
		if ( is_page( 'wp-hmr-sample' ) ) {
			add_filter( 'the_content', 'wp_hmr_override_content' );

			$you  = wp_get_current_user();
			$l10n = [
				'you'   => $you->display_name,
				'title' => 'Mr',
			];

			wp_enqueue_script( 'wp-hmr-sample' );
			wp_localize_script( 'wp-hmr-sample', 'wpHmrSample', $l10n );
		}
	}
}


if ( ! function_exists( 'wp_hmr_override_content' ) ) {
	function wp_hmr_override_content(): string {
		return '<div id="wp-hmr-sample"></div>';
	}
}

add_action( 'wp', 'wp_hmr_page_setup' );
