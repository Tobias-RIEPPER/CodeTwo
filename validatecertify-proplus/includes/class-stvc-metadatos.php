<?php

/*
 * ValidateCertify Pro Metadatos
 *
 * @class    stvc-stvc-netadatos
 * @package  ValidateCertify
 *
 */

// Función para actualizar la metadata del usuario
function update_license_activated() {
    // Verificar si la solicitud es segura
    if (isset($_POST['user_id']) && isset($_POST['value']) && isset($_POST['email']) && isset($_POST['license_key'])) {
        $user_id = $_POST['user_id'];
        $value = $_POST['value'];
        $email = $_POST['email'];
        $license_key = $_POST['license_key'];
        
        // Actualizar la metadata del usuario
        update_user_meta($user_id, 'license_activated', $value);
        update_user_meta($user_id, 'email_user_license', $email);
        update_user_meta($user_id, 'license_key_validate', $license_key);

        // Responder con un mensaje de éxito
        wp_send_json_success('License activated updated successfully.');
    } else {
        // Responder con un mensaje de error si la solicitud no es válida
        wp_send_json_error('Invalid request.');
    }

    // Asegurarse de que la respuesta se envíe
    wp_die();
}
?>