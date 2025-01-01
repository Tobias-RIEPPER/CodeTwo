<?php

class STVC_Admin_Dashboard {
    public function __construct() {
        add_action('wp_dashboard_setup', array($this, 'add_custom_dashboard_widget'));
        add_action('admin_enqueue_scripts', array($this, 'load_custom_styles'));
    }

    public function add_custom_dashboard_widget() {
        // Registra la caja personalizada en el dashboard
        wp_add_dashboard_widget(
            'custom_dashboard_widget_stvc', // ID único de la caja personalizada
            __('ValidateCertify ProPlus', 'stvc_validatecertify') . ' ' . stvc_validatecertify_version, 
            array($this, 'render_custom_dashboard_widget_stvc')
        );
    }
    // Función para cargar estilos personalizados
    public function load_custom_styles() {
        // Carga el archivo CSS desde la ubicación de tu plugin
        wp_enqueue_style('stvc-custom-styles', plugins_url('assets/css/validatecertify-styles.css', __FILE__));
    }
    
    // Función para renderizar el contenido de la caja personalizada
    public function render_custom_dashboard_widget_stvc() {
        ?>
        <div class="custom-dashboard-widget-stvc">
            <p><?php _e('Visualizar mi base de certificados.', 'stvc_validatecertify'); ?></p>
            <a href="<?php echo admin_url('admin.php?page=new_certificates_stvc'); ?>" class="button button-primary"><?php echo esc_html__('Ver Certificados', 'stvc_validatecertify'); ?></a>
        </div>

        <div class="custom-dashboard-widget-stvc">
            <p><?php _e('Añadir un certificado con Código Automático.', 'stvc_validatecertify'); ?></p>
            <a href="<?php echo admin_url('admin.php?page=new_certificates_stvc'); ?>" class="button button-primary"><?php echo esc_html__('Añadir Certificado', 'stvc_validatecertify'); ?></a>
        </div>
        
        <div class="custom-dashboard-widget-stvc">
            <p><?php _e('Revisar Herramientas y Carga Masiva.', 'stvc_validatecertify'); ?></p>
            <a href="<?php echo admin_url('admin.php?page=tools_validatecertify'); ?>" class="button button-primary"><?php echo esc_html__('Herramientas', 'stvc_validatecertify'); ?></a>
        </div>
        <?php
    }
    
}

