<?php
/**
 * Option page definition
 *
 * @package wp-KongLeads
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	echo 'This file should not be accessed directly!';
	exit; // Exit if accessed directly.
}

/**
 * HTML for the KongLeads option page
 */
function wpkongleads_options_page() {
	?>
	<div>
		<h2><?php esc_html_e( 'WP KongLeads', 'wp-kongleads' ); ?></h2>
		<p><?php esc_html_e( 'Ajoutez les capacités de suivi de KongLeads à votre site web.', 'wp-kongleads' ); ?></p>
		<form action="options.php" method="post">
			<?php settings_fields( 'wpkongleads' ); ?>
			<?php do_settings_sections( 'wpkongleads' ); ?>
			<?php submit_button(); ?>
		</form>
		<h3><?php esc_html_e( 'Exemples de Shortcode:', 'wp-kongleads' ); ?></h3>
		<ul>
			<li><?php esc_html_e( 'Formulaire intégré KonghLeads:', 'wp-kongleads' ); ?> <code>[kongleads type="form" id="1"]</code></li>
			<li><?php esc_html_e( 'Contenu dynamique KongLeads:', 'wp-kongleads' ); ?> <code>[kongleads type="content" slot="slot_name"]<?php esc_html_e( 'Texte par défaut', 'wp-kongleads' ); ?>[/kongleads]</code></li>
		</ul>
		<h3><?php esc_html_e( 'Liens rapides', 'wp-kongleads' ); ?></h3>
		<ul>
			<li>
				<a href="https://kongleads.com/" target="_blank"><?php esc_html_e( 'Documentation', 'wp-kongleads' ); ?></a>
			</li>
			<li>
				<a href="https://kongleads.com/" target="_blank"><?php esc_html_e( 'Abonnement', 'wp-kongleads' ); ?></a>
			</li>
			<li>
				<a href="https://kongleads.com/" target="_blank"><?php esc_html_e( 'Fonctionnalités', 'wp-kongleads' ); ?></a>
			
		</ul>
	</div>
	<?php
}

/**
 * Define admin_init hook logic
 */
function wpkongleads_admin_init() {
	register_setting( 'wpkongleads', 'wpkongleads_options', 'wpkongleads_options_validate' );

	add_settings_section(
		'wpkongleads_main',
		__( 'Main Settings', 'wp-kongleads' ),
		'wpkongleads_section_text',
		'wpkongleads'
	);

	add_settings_field(
		'wpkongleads_base_url',
		__( 'URL de votre compte CongLeads', 'wp-kongleads' ),
		'wpkongleads_base_url',
		'wpkongleads',
		'wpkongleads_main'
	);
	add_settings_field(
		'wpkongleads_script_location',
		__( 'Emplacement du script de suivi', 'wp-kongleads' ),
		'wpkongleads_script_location',
		'wpkongleads',
		'wpkongleads_main'
	);
	add_settings_field(
		'wpkongleads_fallback_activated',
		__( 'Image de suivi', 'wp-kongleads' ),
		'wpkongleads_fallback_activated',
		'wpkongleads',
		'wpkongleads_main'
	);
	add_settings_field(
		'wpkongleads_track_logged_user',
		__( 'Utilisateur connecté', 'wp-kongleads' ),
		'wpkongleads_track_logged_user',
		'wpkongleads',
		'wpkongleads_main'
	);
}
add_action( 'admin_init', 'wpkongleads_admin_init' );

/**
 * Section text
 */
function wpkongleads_section_text() {
}

/**
 * Define the input field for KongLeads base URL
 */
function wpkongleads_base_url() {
	$url = wpkongleads_option( 'base_url', '' );

	?>
	<input
		id="wpkongleads_base_url"
		name="wpkongleads_options[base_url]"
		size="40"
		type="text"
		placeholder="http://(votre numero de compte).kongleads.com"
		value="<?php echo esc_url_raw( $url, array( 'http', 'https' ) ); ?>"
	/>
	<?php
}

/**
 * Define the input field for KongLeads script location
 */
function wpkongleads_script_location() {
	$position = wpkongleads_option( 'script_location', '' );

	?>
	<fieldset id="wpkongleads_script_location">
		<label>
			<input
				type="radio"
				name="wpkongleads_options[script_location]"
				value="header"
				<?php if ( 'footer' !== $position ) : ?>checked<?php endif; ?>
			/>
			<?php esc_html_e( 'Embedded within the `wp_head` action.', 'wp-kongleads' ); ?>
		</label>
		<br/>
		<label>
			<input
				type="radio"
				name="wpkongleads_options[script_location]"
				value="footer"
				<?php if ( 'footer' === $position ) : ?>checked<?php endif; ?>
			/>
			<?php esc_html_e( 'Embedded within the `wp_footer` action.', 'wp-kongleads' ); ?>
		</label>
	</fieldset>
	<?php
}

/**
 * Define the input field for KongLeads fallback flag
 */
function wpkongleads_fallback_activated() {
	$flag = wpkongleads_option( 'fallback_activated', false );

	?>
	<input
		id="wpkongleads_fallback_activated"
		name="wpkongleads_options[fallback_activated]"
		type="checkbox"
		value="1"
		<?php if ( true === $flag ) : ?>checked<?php endif; ?>
	/>
	<label for="wpkongleads_fallback_activated">
		<?php esc_html_e( 'Activate it when JavaScript is disabled ?', 'wp-kongleads' ); ?>
	</label>
	<?php
}

/**
 * Define the input field for KongLeads logged user tracking flag
 */
function wpkongleads_track_logged_user() {
	$flag = wpkongleads_option( 'track_logged_user', false );

	?>
	<input
		id="wpkongleads_track_logged_user"
		name="wpkongleads_options[track_logged_user]"
		type="checkbox"
		value="1"
		<?php if ( true === $flag ) : ?>checked<?php endif; ?>
	/>
	<label for="wpkongleads_track_logged_user">
		<?php esc_html_e( 'Track user information when logged ?', 'wp-kongleads' ); ?>
	</label>
	<?php 
}

/**
 * Validate base URL input value
 *
 * @param  array $input Input data.
 * @return array
 */
function wpkongleads_options_validate( $input ) {
	$options = get_option( 'wpkongleads_options' );

	$input['base_url'] = isset( $input['base_url'] )
		? trim( $input['base_url'], " \t\n\r\0\x0B/" )
		: '';

	$options['base_url'] = esc_url_raw( trim( $input['base_url'], " \t\n\r\0\x0B/" ) );
	$options['script_location'] = isset( $input['script_location'] )
		? trim( $input['script_location'] )
		: 'header';
	if ( ! in_array( $options['script_location'], array( 'header', 'footer' ), true ) ) {
		$options['script_location'] = 'header';
	}

	$options['fallback_activated'] = isset( $input['fallback_activated'] ) && '1' === $input['fallback_activated']
		? true
		: false;
	$options['track_logged_user'] = isset( $input['track_logged_user'] ) && '1' === $input['track_logged_user']
		? true
		: false;

	return $options;
}
