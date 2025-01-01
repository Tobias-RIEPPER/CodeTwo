<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Validate_Certify_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'validate-certify-widget'; 
    }

    public function get_title() {
        return 'ValidateCertify-ProPlus'; 
    }

    public function get_icon() {
        return 'eicon-site-identity'; 
    }

	public function get_custom_help_url() {
		return 'https://systenjrh.com/plugin-validatecertify/documentacion-validatecertify/';
	}

    public function get_categories() {
        return ['general']; 
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $button_color = $settings['button_color'];
        $text_color = $settings['text_color'];
        $button_hover_color = $settings['button_hover_color']; 
        $label_width = $settings['label_width']; 
    
        $codigo = isset( $_POST['codigomuestra'] ) ? sanitize_text_field( $_POST['codigomuestra'] ) : '';
    
        // Obtener los datos del certificado desde la base de datos
        global $wpdb;
        $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';
        $certificado = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tabla_stvc_validatecertify WHERE codigo = %s", $codigo ) );
    
        // Generar el HTML del widget
        $output = '';
    
        // Renderizar los datos del certificado si existe
        if ( $certificado ) {
            $nombre = $certificado->nombre;
            $apellido = $certificado->apellido;
            $curso = $certificado->curso;
            $fecha = $certificado->fecha;
    
            $output .= '<table class="certificado-table">';
            $output .= '<tr><td><strong>' . esc_html__( 'Código:', 'stvc_validatecertify' ) . '</strong></td><td>' . $codigo . '</td></tr>';
            $output .= '<tr><td><strong>' . esc_html__( 'Nombre:', 'stvc_validatecertify' ) . '</strong></td><td>' . $nombre . '</td></tr>';
            $output .= '<tr><td><strong>' . esc_html__( 'Apellidos:', 'stvc_validatecertify' ) . '</strong></td><td>' . $apellido . '</td></tr>';
            $output .= '<tr><td><strong>' . esc_html__( 'Certificado:', 'stvc_validatecertify' ) . '</strong></td><td>' . $curso . '</td></tr>';
            $output .= '<tr><td><strong>' . esc_html__( 'Fecha de Emisión:', 'stvc_validatecertify' ) . '</strong></td><td>' . $fecha . '</td></tr>';
            $output .= '</table>';

            // Renderizar el mensaje "¿Desea realizar otra busqueda?"
            $output .= '<div class="centered-content-stvc">';
            $output .= '<span class="invalid-code" style="color: ' . $settings['invalid_code_color'] . ';">' . esc_html__( '¿Desea realizar otra busqueda?', 'stvc_validatecertify' ) . '</span>';
            $output .= '</div>';
            
        }
        // Renderizar el mensaje de código inválido
        if ( ! $certificado ) {
            $output .= '<div class="centered-content-stvc">';
            $output .= '<span class="invalid-code" style="color: ' . $settings['invalid_code_color'] . ';">' . esc_html__( 'Por favor ingresar un Código de Certificado Valido', 'stvc_validatecertify' ) . '</span>';
            $output .= '</div>';
        }

        // Renderizar el formulario
        $output .= '<div class="centered-content-stvc">';
        $output .= '<form method="post">';
        $output .= '<input type="text" id="codigomuestra" name="codigomuestra" class="regular-text" placeholder="' . esc_attr__( 'Ingrese el código aquí', 'stvc_validatecertify' ) . '" style="width: ' . $label_width . 'px;"> ';
        $output .= '<input type="submit" class="button-primary" style="background-color: ' . $button_color . '; color: ' . $text_color . '; border-radius: ' . $button_border_radius . 'px;" value="' . esc_attr__( 'Consultar', 'stvc_validatecertify' ) . '" onmouseover="this.style.backgroundColor=\'' . $button_hover_color . '\';" onmouseout="this.style.backgroundColor=\'' . $button_color . '\';">';
        $output .= '</form>';
        $output .= '</div>';
        
        echo $output;
    }
    
    protected function _register_controls() {
        // Define los controles de configuración del widget
        
        $this->start_controls_section(
            'section_content_1',
            [
                'label' => esc_html__('Formulario', 'stvc_validatecertify'),
            ]
        );

        // Agrega control para la tipografía del texto del span
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'invalid_code_typography',
                'label' => esc_html__('Tipografía', 'stvc_validatecertify'),
                'selector' => '{{WRAPPER}} span.invalid-code',
            ]
        );

        // Agrega control para el color del texto del span
        $this->add_control(
            'invalid_code_color',
            [
                'label' => esc_html__('Color del Texto', 'stvc_validatecertify'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} span.invalid-code' => 'color: {{VALUE}};',
                ],
                'default' => '#000000',
            ]
        );

        // Agrega control para el ancho del label
        $this->add_responsive_control(
            'label_width',
            [
                'label' => esc_html__('Ancho del Label', 'stvc_validatecertify'), 
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 600,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 900,
                    ],
                ],
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} #codigomuestra' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'tablet_default' => [
                    'size' => 295,
                ],
                'tablet_size_units' => [ 'px' ],
                'mobile_default' => [
                    'size' => 295,
                ],
                'mobile_size_units' => [ 'px' ],
            ]
        );

        $this->end_controls_section();
        
        //Seccion de configuracion de boton
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Botón de Formulario', 'stvc_validatecertify'),
            ]
        );
        // Agrega control para el color del botón
        $this->add_control(
            'button_color',
            [
                'label' => esc_html__('Color del Botón','stvc_validatecertify'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0073e6', 
            ]
        );

        // Agrega control para el color de hover del botón
        $this->add_control(
            'button_hover_color',
            [
                'label' => esc_html__('Color de Hover del Botón', 'stvc_validatecertify'), 
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0056b3', 
            ]
        );
    // Agrega control para el color del texto del botón
    $this->add_control(
        'text_color',
        [
            'label' => esc_html__('Color del Texto del Botón', 'stvc_validatecertify'), 
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff', 
        ]
    );
        $this->add_control(
            'button_border_radius',
            [
                'label' => esc_html__('Radio del Borde del Botón','stvc_validatecertify'),
                'type' => \Elementor\Controls_Manager::NUMBER, 
                'default' => 4, 
                'selectors' => [
                    '{{WRAPPER}} .button-primary' => 'border-radius: {{VALUE}}px;', 
                ],
            ]
        );
        
        $this->end_controls_section();
        
    }

}

// function registrar_widget_elementor_validate_certify() {
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Validate_Certify_Widget());
// }
