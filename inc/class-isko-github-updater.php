<?php
/**
 * Theme updates delivered from GitHub Releases.
 *
 * @package ISKO
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Integrates the theme with WordPress' native update system. */
final class ISKO_GitHub_Updater {
	private const REPOSITORY     = 'ReneFGJr/ISKO-Brasil-Layout---WordPress';
	private const API_URL        = 'https://api.github.com/repos/' . self::REPOSITORY . '/releases/latest';
	private const REPOSITORY_URL = 'https://github.com/' . self::REPOSITORY;
	private const CACHE_KEY      = 'isko_github_latest_release';
	private const CACHE_LIFETIME = 6 * HOUR_IN_SECONDS;

	/** Register updater hooks. */
	public static function init() {
		add_filter( 'pre_set_site_transient_update_themes', array( __CLASS__, 'check_for_update' ) );
		add_filter( 'themes_api', array( __CLASS__, 'theme_information' ), 20, 3 );
		add_filter( 'upgrader_source_selection', array( __CLASS__, 'normalize_package_directory' ), 10, 4 );
		add_action( 'upgrader_process_complete', array( __CLASS__, 'clear_cache_after_update' ), 10, 2 );
		add_action( 'load-update-core.php', array( __CLASS__, 'clear_cache_on_manual_check' ) );
	}

	/** Allow the "Check again" button to bypass the release cache. */
	public static function clear_cache_on_manual_check() {
		if ( current_user_can( 'update_themes' ) && isset( $_GET['force-check'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			delete_site_transient( self::CACHE_KEY );
		}
	}

	/** Add a GitHub release to WordPress' theme update data. */
	public static function check_for_update( $transient ) {
		if ( ! is_object( $transient ) ) {
			$transient = new stdClass();
		}

		$release = self::get_release();
		if ( is_wp_error( $release ) ) {
			return $transient;
		}

		$theme   = wp_get_theme( get_template() );
		$slug    = get_template();
		$current = $theme->get( 'Version' );
		$data    = array(
			'theme'        => $slug,
			'new_version'  => $release['version'],
			'url'          => $release['html_url'],
			'package'      => $release['package'],
			'requires'     => $theme->get( 'RequiresWP' ),
			'requires_php' => $theme->get( 'RequiresPHP' ),
		);

		if ( version_compare( $release['version'], $current, '>' ) ) {
			$transient->response[ $slug ] = $data;
		} else {
			$data['new_version']          = $current;
			$data['package']              = '';
			$transient->no_update[ $slug ] = $data;
		}

		return $transient;
	}

	/** Supply details for the update modal in wp-admin. */
	public static function theme_information( $result, $action, $args ) {
		if ( 'theme_information' !== $action || empty( $args->slug ) || get_template() !== $args->slug ) {
			return $result;
		}

		$release = self::get_release();
		if ( is_wp_error( $release ) ) {
			return $result;
		}

		$theme = wp_get_theme( get_template() );

		return (object) array(
			'name'          => $theme->get( 'Name' ),
			'slug'          => get_template(),
			'version'       => $release['version'],
			'author'        => $theme->get( 'Author' ),
			'homepage'      => self::REPOSITORY_URL,
			'download_link' => $release['package'],
			'requires'      => $theme->get( 'RequiresWP' ),
			'requires_php'  => $theme->get( 'RequiresPHP' ),
			'last_updated'  => $release['published_at'],
			'sections'      => array(
				'description' => $theme->get( 'Description' ),
				'changelog'   => nl2br( esc_html( $release['body'] ) ),
			),
		);
	}

	/** Rename GitHub's generated directory to the installed theme slug. */
	public static function normalize_package_directory( $source, $remote_source, $upgrader, $hook_extra ) {
		unset( $upgrader );

		if ( is_wp_error( $source ) || empty( $hook_extra['theme'] ) || get_template() !== $hook_extra['theme'] ) {
			return $source;
		}

		global $wp_filesystem;
		$desired_source = trailingslashit( $remote_source ) . get_template() . '/';

		if ( trailingslashit( $source ) === $desired_source ) {
			return $source;
		}

		if ( ! $wp_filesystem || ! $wp_filesystem->exists( trailingslashit( $source ) . 'style.css' ) ) {
			return new WP_Error( 'isko_invalid_update_package', __( 'O pacote de atualização do tema ISKO é inválido.', 'isko' ) );
		}

		if ( $wp_filesystem->exists( $desired_source ) ) {
			$wp_filesystem->delete( $desired_source, true );
		}

		if ( ! $wp_filesystem->move( $source, $desired_source, true ) ) {
			return new WP_Error( 'isko_update_directory', __( 'Não foi possível preparar o diretório da atualização do tema ISKO.', 'isko' ) );
		}

		return $desired_source;
	}

	/** Clear release cache after this theme is updated. */
	public static function clear_cache_after_update( $upgrader, $options ) {
		unset( $upgrader );

		if ( 'update' === ( $options['action'] ?? '' ) && 'theme' === ( $options['type'] ?? '' ) ) {
			$themes = $options['themes'] ?? array( $options['theme'] ?? '' );
			if ( in_array( get_template(), $themes, true ) ) {
				delete_site_transient( self::CACHE_KEY );
			}
		}
	}

	/** Read and cache the latest stable GitHub release. */
	private static function get_release() {
		$cached = get_site_transient( self::CACHE_KEY );
		if ( is_array( $cached ) ) {
			return $cached;
		}

		$response = wp_remote_get(
			self::API_URL,
			array(
				'timeout' => 15,
				'headers' => array(
					'Accept'               => 'application/vnd.github+json',
					'X-GitHub-Api-Version' => '2022-11-28',
					'User-Agent'           => 'ISKO-WordPress-Theme/' . wp_get_theme( get_template() )->get( 'Version' ),
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return new WP_Error( 'isko_github_api', __( 'Não foi possível consultar a versão do tema no GitHub.', 'isko' ) );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $data ) ) {
			return new WP_Error( 'isko_github_json', __( 'O GitHub retornou uma resposta inválida.', 'isko' ) );
		}

		$version = isset( $data['tag_name'] ) ? preg_replace( '/^v/i', '', $data['tag_name'] ) : '';
		if ( ! preg_match( '/^\d+(?:\.\d+){1,3}(?:[-+][0-9A-Za-z.-]+)?$/', $version ) || empty( $data['zipball_url'] ) ) {
			return new WP_Error( 'isko_github_release', __( 'A release mais recente do tema ISKO não possui uma versão válida.', 'isko' ) );
		}

		$release = array(
			'version'      => $version,
			'package'      => esc_url_raw( $data['zipball_url'] ),
			'html_url'     => esc_url_raw( $data['html_url'] ?? self::REPOSITORY_URL . '/releases' ),
			'published_at' => sanitize_text_field( $data['published_at'] ?? '' ),
			'body'         => sanitize_textarea_field( $data['body'] ?? '' ),
		);

		set_site_transient( self::CACHE_KEY, $release, self::CACHE_LIFETIME );

		return $release;
	}
}

ISKO_GitHub_Updater::init();
