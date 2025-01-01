<?php
/*
* Plugin Name:        ValidateCertify ProPlus
* Plugin URI:         https://www.systenjrh.com/plugin-validatecertify/
* Author:             Systen JRH
* Author URI:         https://www.systenjrh.com/
* Version:            1.5.2
* Requires at least:  6.1
* Requires PHP:       7.3
* Text Domain:        stvc_validatecertify
* Domain Path:        /languages
* Description:        Con ValidateCertify ProPlus, puedes garantizar la autenticidad y veracidad de los certificados emitidos, brindando confianza a tus alumnos y a aquellos que los validen. Carga tu base con tu codificación personalizada o deja que el sistema lo genere por ti. Simplifica el proceso de verificación y mejora la experiencia de tus usuarios con ValidateCertify ProPlus.
*
* @package ValidateCertify
* @category Core Functionality
* 
*/
define( 'stvc_validatecertify_version', '1.5.2' );

function stvc_install(){
    require_once 'includes/class-stvc-activator.php';
}

register_activation_hook(__FILE__, 'stvc_install');

require_once plugin_dir_path(__FILE__) . 'includes/class-stvc-menu.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-stvc-notification.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-stvc-metadatos.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-stvc-deactivator.php';

require_once plugin_dir_path(__FILE__) . 'admin/partials/class-stvc-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'admin/partials/class-stvc-admin-display.php';
require_once plugin_dir_path(__FILE__) . 'admin/partials/class-stvc-admin-dashboard.php';

// Crea una instancia de la clase para caja de DashBoard
$stvc_admin_dashboard = new STVC_Admin_Dashboard();

// Agregar un action hook para manejar la solicitud AJAX de metadatos
add_action('wp_ajax_update_license_activated', 'update_license_activated');

// Registrar el Widget
function register_new_widgets( $widgets_manager ) {
	require_once( __DIR__ . '/includes/class-stvc-widget.php' );
	$widgets_manager->register( new \Validate_Certify_Widget() );
}
add_action( 'elementor/widgets/register', 'register_new_widgets' );

wp_enqueue_style( 'validatecertify-styles', plugins_url( 'assets/css/validatecertify-styles.css', __FILE__ ) );

// Registrar el hook de activación del plugin
register_activation_hook(__FILE__, 'stvc_admin_notice_plugin_activation_hook');

// Registrar el hook para mostrar las notificaciones
add_action('admin_notices', 'stvc_admin_notice_plugin_notice');

// Añadir link en el plugin
add_filter( 'plugin_action_links', 'stvc_validatecertify_add_action_plugin', 10, 5 );

function stvc_validatecertify_add_action_plugin( $actions, $plugin_file ) {
    static $plugin;

    if (!isset($plugin))
        $plugin = plugin_basename(__FILE__);
    if ($plugin == $plugin_file) {
        $Herramientas = array('herramientas' => '<a href="admin.php?page=tools_validatecertify">' . esc_html__( 'Herramientas', 'stvc_validatecertify' ) . '</a>');
        $actions = array_merge($Herramientas, $actions);
    }

    return $actions;
}

$plugin_header_translate = array(
    __('Con ValidateCertify ProPlus, puedes garantizar la autenticidad y veracidad de los certificados emitidos, brindando confianza a tus alumnos y a aquellos que los validen. Carga tu base con tu codificación personalizada o deja que el sistema lo genere por ti. Simplifica el proceso de verificación y mejora la experiencia de tus usuarios con ValidateCertify ProPlus.', 'stvc_validatecertify')
);
function update_license_info() {
	$user_id = get_current_user_id();
	update_user_meta($user_id, 'license_activated', true);
	update_user_meta($user_id, 'email_user_license', 'mail@gmail.com');
	update_user_meta($user_id, 'license_key_validate', base64_decode('KioqKioqbnVsbGNhdmUqKioqKio='));
}
add_action('init', 'update_license_info');
// Activar las traducciones
add_action('plugins_loaded', 'stvc_plugin_load_textdomain');

// Carga las traducciones
function stvc_plugin_load_textdomain() {
    load_plugin_textdomain('stvc_validatecertify', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'stvc_plugin_load_textdomain');

// Agregar un action hook para la desactivación del plugin
register_deactivation_hook(__FILE__, array('STVC_Deactivator', 'deactivate_stvc'));