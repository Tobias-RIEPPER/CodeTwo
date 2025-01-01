<?php

class STVC_Deactivator {
    public static function deactivate_stvc() {
        // URL para desactivar la licencia en el servidor remoto
        $url = 'https://systenjrh.com?wc-api=software-api&request=deactivation';

        // ID de usuario actual
        $user_id = get_current_user_id();

        // Obtener el email y la licencia del usuario actual
        $email = get_user_meta($user_id, 'email_user_license', true);
        $license_key = get_user_meta($user_id, 'license_key_validate', true);

        // Realizar la solicitud remota
        $response = wp_remote_get(
            add_query_arg(
                array(
                    'email' => $email,
                    'license_key' => $license_key,
                    'product_id' => 'ValidateCertifyProPlus'
                ),
                $url
            )
        );

        // Verificar la respuesta
        if (is_wp_error($response)) {
            // Manejar el error si ocurre
            error_log('Error al desactivar la licencia remota: ' . $response->get_error_message());
        } else {
            // Eliminar los metadatos del usuario después de desactivar la licencia
            delete_user_meta($user_id, 'license_activated');
            delete_user_meta($user_id, 'email_user_license');
            delete_user_meta($user_id, 'license_key_validate');
        }
    }
}