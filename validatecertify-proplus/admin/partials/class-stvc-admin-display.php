<?php

// Añade enlaces en la información de la versión del plugin
function info_plugin($plugin_meta, $plugin_file) {
    // Verifica si se trata del archivo de tu plugin
    if (strpos($plugin_file, 'ValidateCertify-ProPlus.php') !== false) {
        // Añade los enlaces a los metadatos del plugin
        $plugin_meta[] = '<a href="https://systenjrh.com/plugin-validatecertify/documentacion-validatecertify/" target="_blank">' . esc_html__( 'Documentación', 'stvc_validatecertify' ) . '</a>';
        $plugin_meta[] = '<a href="https://wordpress.org/support/plugin/validar-certificados-de-cursos/reviews/#new-post" target="_blank">' . esc_html__( 'Valora el plugin ★★★★★', 'stvc_validatecertify' ) . '</a>';
    }
    return $plugin_meta;
}

add_filter('plugin_row_meta', 'info_plugin', 10, 2);

//valida las paginas del plugin
function validar_pagina_vtvc() {
    $plugin_pages = array('ValidateCertify', 'new_certificates_a_stvc', 'new_certificates_m_stvc', 'edit_certificates_stvc', 'delete_certificates_stvc', 'tools_validatecertify', 'license_validatecertify');

    // Verificar si la página actual tiene uno de los slugs de tu plugin
    if (isset($_GET['page']) && in_array($_GET['page'], $plugin_pages)) {
        return true;
    }
    return false;
}

// Eliminar el mensaje "Gracias por crear con WordPress" solo en páginas de tu plugin
if (validar_pagina_vtvc()) {
    remove_filter( 'update_footer', 'core_update_footer' );

    // Agregar nuestra info en el pie de página
    function custom_plugin_footer_text( $text ) {
        // Agregar el mensaje personalizado con la valoración de estrellas y el enlace
        $text = __('¿Has disfrutado ValidateCertify? Por favor, déjanos una valoración de ', 'stvc_validatecertify' );
        $text .= '<a href="https://wordpress.org/support/plugin/validar-certificados-de-cursos/reviews/#new-post" target="_blank">★★★★★</a>. ';
        $text .= __('¡De verdad que agradecemos tu apoyo!', 'stvc_validatecertify' );
        return $text;
    }
    add_filter( 'admin_footer_text', 'custom_plugin_footer_text' );

    // Reemplazar el texto "Versión" seguido de la versión del plugin solo en páginas de tu plugin
    function custom_plugin_version_text( $footer_text ) {
        // Obtener la versión del plugin
        $plugin_version = defined( 'stvc_validatecertify_version' ) ? stvc_validatecertify_version : '';

        // Reemplazar el texto "Versión" seguido de la versión del plugin
        $footer_text = str_replace( __('Versión', 'stvc_validatecertify'), __('Versión ValidateCertify ProPlus', 'stvc_validatecertify'), $footer_text );

        // Reemplazar la versión del plugin con la versión definida
        $footer_text = str_replace( '6.4.5', $plugin_version, $footer_text );

        // Reemplazar cualquier versión en el pie de página con la versión definida
        $footer_text = preg_replace( '/\d+\.\d+\.\d+/', $plugin_version, $footer_text );

        return $footer_text;
    }
    add_filter( 'update_footer', 'custom_plugin_version_text', 11 );
}