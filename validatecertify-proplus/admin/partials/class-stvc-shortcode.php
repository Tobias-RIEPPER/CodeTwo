<?php

/*
 *
 * @link       https://https://www.systenjrh.com/
 * @since      1.5.0
 *
 * @package    ValidateCertify
 * @subpackage ValidateCertify/admin/partials
 */


// ShortCode ValidateCertify
function stvc_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'button_color' => '#0073e6',
            'text_color' => '#ffffff',
        ),
        $atts,
        'ValidateCertify'
    );

    return stvc_validate_certify_html( $atts );
}

add_shortcode( 'ValidateCertify', 'stvc_shortcode' );

// Función para generar el HTML de ValidateCertify
function stvc_validate_certify_html( $atts ) {
    // Obtener el código ingresado por el usuario
    $codigo = isset( $_POST['codigomuestra'] ) ? sanitize_text_field( $_POST['codigomuestra'] ) : '';

    // Obtener los datos del certificado desde la base de datos
    global $wpdb;
    $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';
    $certificado = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $tabla_stvc_validatecertify WHERE codigo = %s", $codigo ) );

    // Generar el HTML del shortcode
    $output = '';

// Si el certificado existe, mostrar los datos en una tabla
    if ( $certificado ) {
        $nombre = $certificado->nombre;
        $apellido = $certificado->apellido;
        $curso = $certificado->curso;
        $fecha = $certificado->fecha;

        $output .= '<table class="certificado-table" "centered-content-stvc">';
        $output .= '<tr><td><strong>' . esc_html__( 'Código:', 'stvc_validatecertify' ) . '</strong></td><td>' . $codigo . '</td></tr>';
        $output .= '<tr><td><strong>' . esc_html__( 'Nombre:', 'stvc_validatecertify' ) . '</strong></td><td>' . $nombre . '</td></tr>';
        $output .= '<tr><td><strong>' . esc_html__( 'Apellidos:', 'stvc_validatecertify' ) . '</strong></td><td>' . $apellido . '</td></tr>';
        $output .= '<tr><td><strong>' . esc_html__( 'Certificado:', 'stvc_validatecertify' ) . '</strong></td><td>' . $curso . '</td></tr>';
        $output .= '<tr><td><strong>' . esc_html__( 'Fecha de Emisión:', 'stvc_validatecertify' ) . '</td><td>' . $fecha . '</td></tr>';
        $output .= '</table>';
        $output .= '<div class="centered-content-stvc">';
        $output .= '<span class="invalid-code">' . esc_html__( '¿Desea realizar otra búsqueda?', 'stvc_validatecertify' ) . '</span>';
        $output .= '</div>';

        } else {
            $output .= '<div class="centered-content-stvc">';
            $output .= '<span class="invalid-code">' . esc_html__( 'Por favor ingresar un Código de Certificado válido', 'stvc_validatecertify' ) . '</span>';
            $output .= '</div>';
        }

        // Mostrar el formulario para ingresar el código
        $output .= '<div class="centered-content-stvc">';
        $output .= '<form method="post">';
        $output .= '<input type="text" id="codigomuestra" name="codigomuestra" class="regular-text" placeholder="' . esc_attr__( 'Ingrese el código aquí', 'stvc_validatecertify' ) . '" > ';
        $output .= '<input type="submit" class="button-primary" value="' . esc_attr__( 'Consultar', 'stvc_validatecertify' ) . '">';
        $output .= '</div>';

        wp_enqueue_style( 'validatecertify-styles', plugins_url( 'assets/css/validatecertify-styles.css', __FILE__ ) );

    return $output;

}

add_shortcode( 'ValidateCertify', 'stvc_shortcode' );