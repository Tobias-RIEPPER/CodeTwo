<?php
function stvc_menu() {
    add_menu_page(
        esc_html__( 'ValidateCertify ProPlus', 'stvc_validatecertify' ), // Título de la página
        esc_html__( 'ValidateCertify ProPlus', 'stvc_validatecertify' ), // Título del menú
        'manage_options', // Capacidad requerida para acceder a la página
        'ValidateCertify', // Slug del menú
        'stvc_basededatos', // Función que muestra la página
        'dashicons-awards', // Icono del menú
        30 // Posición del menú
    );
    add_submenu_page(
        'ValidateCertify',
        esc_html__( 'Nuevo Certificado CA', 'stvc_validatecertify' ),
        esc_html__( 'Nuevo Certificado CA', 'stvc_validatecertify' ),
        'manage_options',
        'new_certificates_a_stvc',
        'stvc_certificado_nuevo_ca'
    );
    add_submenu_page(
        'ValidateCertify',
        esc_html__( 'Nuevo Certificado CM', 'stvc_validatecertify' ),
        esc_html__( 'Nuevo Certificado CM', 'stvc_validatecertify' ),
        'manage_options',
        'new_certificates_m_stvc',
        'stvc_certificado_nuevo'
    );
    add_submenu_page(
        'ValidateCertify',
        esc_html__( 'Editar Certificado', 'stvc_validatecertify' ),
        esc_html__( 'Editar Certificado', 'stvc_validatecertify' ),
        'manage_options',
        'edit_certificates_stvc',
        'stvc_modificar_certificados'
    );
        add_submenu_page(
        'ValidateCertify',
        esc_html__( 'Eliminar Certificado', 'stvc_validatecertify' ),
        esc_html__( 'Eliminar Certificado', 'stvc_validatecertify' ),
        'manage_options',
        'delete_certificates_stvc',
        'stvc_eliminar_certificado'
    );
    add_submenu_page(
        'ValidateCertify', 
        esc_html__( 'Herramientas', 'stvc_validatecertify' ),
        esc_html__( 'Herramientas', 'stvc_validatecertify' ),
        'manage_options',
        'tools_validatecertify',
        'stvc_herramientas'
    );

    add_submenu_page(
        'ValidateCertify',
        esc_html__( 'Licencia', 'stvc_validatecertify' ),
        esc_html__( 'Licencia', 'stvc_validatecertify' ),
        'manage_options',
        'license_validatecertify',
        'stvc_licencia'
    );
}

add_action( 'admin_menu', 'stvc_menu' );

function stvc_basededatos() {
    global $wpdb;
    $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';
    $registros_por_pagina = isset($_GET['registros']) && $_GET['registros'] === '50' ? 50 : 20; // Número de registros por página (predeterminado: 20)
    $pagina_actual = isset($_GET['pagina']) ? absint($_GET['pagina']) : 1; // Obtener el número de página de la URL (predeterminado: 1)
    $offset = ($pagina_actual - 1) * $registros_por_pagina; // Calcular el offset

    // Obtener el total de registros y páginas
    $total_registros = $wpdb->get_var("SELECT COUNT(*) FROM $tabla_stvc_validatecertify");
    $total_paginas = ceil($total_registros / $registros_por_pagina);

        if (isset($_POST['buscar_codigo'])) {
            $codigo_buscar = isset($_POST['codigo_buscar']) ? sanitize_text_field($_POST['codigo_buscar']) : '';
            $resultados = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tabla_stvc_validatecertify WHERE codigo = %s", $codigo_buscar));
        } else {
            $resultados = $wpdb->get_results("SELECT * FROM $tabla_stvc_validatecertify LIMIT $offset, $registros_por_pagina");
            }

        ?>
        <div id="encabezado-menu" class="#top-menu">
            <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Certificados Emitidos', 'stvc_validatecertify' ); ?></h1>
        </div>
        <div class="wp-heading-space"></div>
        <div class="title-page-st">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'Base de Certificados', 'stvc_validatecertify' ); ?></h1>
            <a href="admin.php?page=new_certificates_stvc" class="page-title-action"><?php esc_html_e( 'Añadir Certificado', 'stvc_validatecertify' ); ?></a>
        </div>
        <div class="wrap">
        <hr class="wp-header-end">
                <!-- Formulario de búsqueda -->
                <form method="post" >
                    <p class="search-box">
                    <label for="codigo_buscar"><strong><?php esc_html_e( 'Buscar por Código:', 'stvc_validatecertify' ); ?></strong></label>
                    <input type="text" name="codigo_buscar" id="codigo_buscar" required class="text" placeholder="<?php esc_attr_e( 'Ingrese el código aquí', 'stvc_validatecertify' ); ?>">
                    <input type="submit" name="buscar_codigo" class="button button-secondary" value="<?php esc_attr_e( 'Buscar', 'stvc_validatecertify' ); ?>">
                </form>
                </p>
                <!-- Texto de paginación -->
                <p style="align-self: center;">
                    <strong><?php esc_html_e( 'Mostrar grupos de:', 'stvc_validatecertify' ); ?></strong>
                    <a href="<?php echo esc_url(add_query_arg('registros', 20)); ?>">20</a> |
                    <a href="<?php echo esc_url(add_query_arg('registros', 50)); ?>">50</a>
                </p>
            </div>
            <div class="wrap">
            <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                <th><strong><?php esc_html_e( 'Nombre', 'stvc_validatecertify' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Apellidos', 'stvc_validatecertify' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Certificado', 'stvc_validatecertify' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Fecha', 'stvc_validatecertify' ); ?></strong></th>
                <th><strong><?php esc_html_e( 'Código', 'stvc_validatecertify' ); ?></strong></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($resultados as $fila) {
                    echo '<tr>';
                    echo '<td>' . esc_html($fila->nombre) . '</td>';
                    echo '<td>' . esc_html($fila->apellido) . '</td>';
                    echo '<td>' . esc_html($fila->curso) . '</td>';
                    echo '<td>' . esc_html($fila->fecha) . '</td>';
                    echo '<td>' . esc_html($fila->codigo) . '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
            <!-- Botones de Paginación -->
            <div class="tablenav">
                <div class="tablenav-pages">
                    <?php
                    // Texto "x certificados"
                    echo '<span class="certificados-total">' . sprintf(esc_html__('%02s certificados', 'stvc_validatecertify'), esc_html($total_registros)) . '</span>';
                    
                    // Botón para ir a la primera página
                    echo '<a class="first-page button' . ($pagina_actual <= 1 ? ' disabled' : '') . '" href="' . esc_url(add_query_arg('pagina', 1)) . '" style="margin-left: 5px;">&laquo; </a>';
                    
                    // Botón para ir a la página anterior
                    echo '<a class="prev-page button' . ($pagina_actual <= 1 ? ' disabled' : '') . '" href="' . esc_url(add_query_arg('pagina', max($pagina_actual - 1, 1))) . '" style="margin-left: 5px;">&lsaquo; </a>';
                    
                    // Mostrar el texto de la página actual
                    echo '<span class="current-page" style="margin: 0 5px;">' . sprintf(esc_html__('Página 0%s de 0%s', 'stvc_validatecertify'), esc_html($pagina_actual), esc_html($total_paginas)) . '</span>';

                    
                    // Botón para ir a la página siguiente
                    echo '<a class="next-page button' . ($pagina_actual >= $total_paginas ? ' disabled' : '') . '" href="' . esc_url(add_query_arg('pagina', min($pagina_actual + 1, $total_paginas))) . '" style="margin-left: 5px;"> &rsaquo;</a>';
                    
                    // Botón para ir a la última página
                    echo '<a class="last-page button' . ($pagina_actual >= $total_paginas ? ' disabled' : '') . '" href="' . esc_url(add_query_arg('pagina', $total_paginas)) . '" style="margin-left: 5px;"> &raquo;</a>';
                    ?>
                </div>
            </div>
        </div>
    <?php
}

    // Muestra la pagina Agregar Certificado
    function stvc_certificado_nuevo_ca() {
    if (isset($_POST['guardar_certificado'])) {
        // Verificar los permisos del usuario
        if (!current_user_can('manage_options')) {
            wp_die(__('Acceso denegado', 'stvc_validatecertify'));
        }

        // Validación y saneamiento de los datos
        $nombre = isset($_POST['nombre']) ? sanitize_text_field($_POST['nombre']) : '';
        $apellido = isset($_POST['apellido']) ? sanitize_text_field($_POST['apellido']) : '';
        $curso = isset($_POST['curso']) ? sanitize_text_field($_POST['curso']) : '';
        $fecha = isset($_POST['fecha']) ? sanitize_text_field($_POST['fecha']) : '';

        // Generar el código
        $codigo = generar_codigo_certificado($nombre, $apellido, $curso, $fecha);

        // Guardar los datos en la base de datos
        global $wpdb;
        $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';
        $wpdb->insert($tabla_stvc_validatecertify, array(
            'nombre' => $nombre,
            'apellido' => $apellido,
            'curso' => $curso,
            'fecha' => $fecha,
            'codigo' => $codigo
        ));

        // Mostrar mensaje de éxito utilizando add_notice
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('El certificado ha sido guardado correctamente.', 'stvc_validatecertify') . '</p></div>';
    }
    ?>
        <div id="encabezado-menu" class="#top-menu">
            <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Agregar Certificado con Código Automático', 'stvc_validatecertify' ); ?></h1>         
        </div>
        <div class="wp-heading-space"></div>
        <div class="ui form">
            <div class="title-page-st">
                <h1 class="wp-heading-inline"><?php esc_html_e( 'Agregar Certificado con Código Automático', 'stvc_validatecertify' ); ?></h1>
            </div>
                <p><?php esc_html_e( 'El código generado tiene la siguiente estructura: 04 dígitos - 03 primeras letras del apellido - 03 primeras letras del nombre - 02 últimos dígitos del año.', 'stvc_validatecertify', 'stvc_validatecertify' ); ?></p>
            <hr class="wp-header-end">
        </div>
            <form method="post" class="ui form">
                <div class="field">
                    <label><?php esc_html_e('Nombre', 'stvc_validatecertify'); ?></label>
                    <div class="ui labeled input">
                        <input name="nombre" type="text" id="nombre" required>
                    </div>
                </div>
                <div class="field">
                    <label><?php esc_html_e('Apellidos', 'stvc_validatecertify'); ?></label>
                    <div class="ui labeled input">
                        <input name="apellido" type="text" id="apellido" required>
                    </div>
                </div>
                <div class="field">
                    <label><?php esc_html_e('Certificado', 'stvc_validatecertify'); ?></label>
                    <div class="ui labeled input">
                        <input name="curso" type="text" id="curso" required>
                    </div>
                </div>
                <div class="field">
                    <label><?php esc_html_e('Fecha', 'stvc_validatecertify'); ?></label>
                    <div class="ui labeled input">
                        <input name="fecha" type="date" id="fecha" required>
                    </div>
                </div>
                <div class="field">
                    <label><?php esc_html_e('Código', 'stvc_validatecertify'); ?></label>
                    <div class="ui labeled input">
                        <input name="codigo" type="text" class="regular-text" id="codigo" value="<?php echo generar_codigo_certificado(); ?>" required readonly>
                    </div>
                </div>
                <p class="submit"><input type="submit" name="guardar_certificado" id="guardar_certificado" class="button button-primary" value="<?php esc_attr_e('Guardar Certificado', 'stvc_validatecertify'); ?>"></p>
            </form>

    <?php
}

// Muestra la pagina Agregar Certificado
function stvc_certificado_nuevo() {
    if (isset($_POST['guardar_certificado'])) {
        // Verificar los permisos del usuario
        if (!current_user_can('manage_options')) {
            wp_die(__('Acceso denegado', 'stvc_validatecertify'));
        }

        // Validación y saneamiento de los datos
        $nombre = isset($_POST['nombre']) ? sanitize_text_field($_POST['nombre']) : '';
        $apellido = isset($_POST['apellido']) ? sanitize_text_field($_POST['apellido']) : '';
        $curso = isset($_POST['curso']) ? sanitize_text_field($_POST['curso']) : '';
        $fecha = isset($_POST['fecha']) ? sanitize_text_field($_POST['fecha']) : '';
        $codigo = isset($_POST['codigo']) ? sanitize_text_field($_POST['codigo']) : '';

        // Guardar los datos en la base de datos
        global $wpdb;
        $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';
        $wpdb->insert($tabla_stvc_validatecertify, array(
            'nombre' => $nombre,
            'apellido' => $apellido,
            'curso' => $curso,
            'fecha' => $fecha,
            'codigo' => $codigo
        ));

        // Mostrar mensaje de éxito utilizando add_notice
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('El certificado ha sido guardado correctamente.', 'stvc_validatecertify') . '</p></div>';
    }
    ?>
    <div id="encabezado-menu" class="#top-menu">
        <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Agregar Certificados con Código Manual', 'stvc_validatecertify' ); ?></h1>         
    </div>
    <div class="wp-heading-space"></div>
    <div class="ui form">
        <div class="title-page-st">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'Agregar Certificados con Código Manual', 'stvc_validatecertify' ); ?></h1>
        </div>
            <p><?php esc_html_e( 'Añade un nuevo registro a la base de datos con', 'stvc_validatecertify' ); ?></p>
        <hr class="wp-header-end">
    </div>
    <div>
        <form method="post" class="ui form">
            <div class="field">
                <label><?php esc_html_e('Código', 'stvc_validatecertify'); ?></label>
                <div class="ui labeled input">
                    <input name="codigo" type="text" id="codigo" required>
                </div>
            </div>
            <div class="field">
                <label><?php esc_html_e('Nombre', 'stvc_validatecertify'); ?></label>
                <div class="ui labeled input">
                    <input name="nombre" type="text" id="nombre" required>
                </div>
            </div>
            <div class="field">
                <label><?php esc_html_e('Apellidos', 'stvc_validatecertify'); ?></label>
                <div class="ui labeled input">
                    <input name="apellido" type="text" id="apellido" required>
                </div>
            </div>
            <div class="field">
                <label><?php esc_html_e('Certificado', 'stvc_validatecertify'); ?></label>
                <div class="ui labeled input">
                    <input name="curso" type="text" id="curso" required>
                </div>
            </div>
            <div class="field">
                <label><?php esc_html_e('Fecha', 'stvc_validatecertify'); ?></label>
                <div class="ui labeled input">
                    <input name="fecha" type="date" id="fecha" required>
                </div>
            </div>
            <input type="submit" name="guardar_certificado" id="guardar_certificado" class="button button-primary" value="<?php esc_attr_e('Guardar Certificado', 'stvc_validatecertify'); ?>">
        </form>
    </div>
    <?php
}

// Función para generar el código del certificado
function generar_codigo_certificado($nombre = '', $apellido = '', $curso = '', $fecha = '') {
    // Generar los primeros 4 caracteres (números y letras aleatorias)
    $codigo = substr(str_shuffle('1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4);

    // Agregar un guion
    $codigo .= '-';

    // Agregar los siguientes 6 caracteres (iniciales del apellido y nombre)
    $nombre_inicial = substr($nombre, 0, 3);
    $apellido_inicial = substr($apellido, 0, 3);
    $codigo .= strtoupper($apellido_inicial . '-' . $nombre_inicial);

    // Agregar un guion
    $codigo .= '-';

    // Agregar los últimos 2 caracteres (año de registro del curso)
    $year = date('y', strtotime($fecha));
    $codigo .= $year;

    return $codigo;
}


// Muestra la pagina Editar Certificado
function stvc_modificar_certificados() {
    global $wpdb;
    if (isset($_POST['modificar_codigo'])) {
        $codigo = sanitize_text_field($_POST['modificar_codigo']);
        $certificado = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}stvc_validatecertify WHERE codigo = %s", $codigo));
        if ($certificado) {
            if (isset($_POST['guardar'])) {
                $nombre = sanitize_text_field($_POST['nombre']);
                $apellido = sanitize_text_field($_POST['apellido']);
                $curso = sanitize_text_field($_POST['curso']);
                $fecha = sanitize_text_field($_POST['fecha']);
                $wpdb->update(
                    "{$wpdb->prefix}stvc_validatecertify",
                    array(
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'Certificado' => $curso,
                        'fecha' => $fecha
                    ),
                    array('codigo' => $codigo)
                );

                // Obtener los datos actualizados después de la actualización
                $certificado = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}stvc_validatecertify WHERE codigo = %s", $codigo));
                ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo esc_html__('¡Certificado modificado con éxito!', 'stvc_validatecertify'); ?></p>
                </div>
                <?php
            }

            ?>
            <div id="encabezado-menu" class="#top-menu">
                <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Editar Certificados', 'stvc_validatecertify' ); ?></h1>         
            </div>
            <div class="wp-heading-space"></div>
            <div class="ui form">
                <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Editar Certificados', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <p><?php esc_html_e( 'Actualiza los datos correspondientes, recuerda que una vez realizado se perderán los datos anteriores', 'stvc_validatecertify' ); ?></p>
                    <hr class="wp-header-end">
                </div>
                <form method="post" class="ui form">
                    <input type="hidden" name="modificar_codigo" value="<?php echo esc_attr($codigo); ?>">
                    
                        <div class="field">
                            <label><?php echo esc_html__('Nombre:', 'stvc_validatecertify'); ?></label>
                            <input type="text" name="nombre" id="nombre" class="regular-text" value="<?php echo esc_attr($certificado->nombre); ?>">
                        </div>
                        <div class="field">
                            <label><?php echo esc_html__('Apellidos:', 'stvc_validatecertify'); ?></label>
                            <input type="text" name="apellido" id="apellido" class="regular-text" value="<?php echo esc_attr($certificado->apellido); ?>">
                        </div>
                        <div class="field">
                            <label><?php echo esc_html__('Certificado:', 'stvc_validatecertify'); ?></label>
                            <input type="text" name="curso" id="curso" class="regular-text" value="<?php echo esc_attr($certificado->curso); ?>">
                        </div>
                        <div class="field">
                            <label><?php echo esc_html__('Fecha:', 'stvc_validatecertify'); ?></label>
                            <input type="date" name="fecha" id="fecha" value="<?php echo esc_attr($certificado->fecha); ?>">
                        </div>
                    <input type="submit" name="guardar" class="button button-primary" value="<?php echo esc_attr__('Actualizar certificado', 'stvc_validatecertify'); ?>">
                    <a href="<?php echo admin_url('admin.php?page=edit_certificates_stvc'); ?>" class="ui secondary button"><?php echo esc_html__('Cancelar', 'stvc_validatecertify'); ?></a>
                </form>
            <?php
        } else {
            ?>
                <div id="encabezado-menu" class="#top-menu">
                    <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Editar Certificados', 'stvc_validatecertify' ); ?></h1>         
                </div>
                <div class="wp-heading-space"></div>
                <div class="ui form">
                    <div class="title-page-st">
                        <h1 class="wp-heading-inline"><?php esc_html_e( 'Editar Certificados', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <p><?php echo esc_html__('El certificado no es válido. Por favor, ingresa un', 'stvc_validatecertify'); ?> <strong><?php echo esc_html__('Código de Certificado Valido', 'stvc_validatecertify'); ?></strong> <?php echo esc_html__('para modificar los nombres, apellidos, cursos y/o fecha de emisión.', 'stvc_validatecertify'); ?></p>
                    <hr class="wp-header-end">
                </div>
                <form method="post" class="ui form">
                    <div class="field">
                    <label for="codigo"><strong><?php echo esc_html__('Código:', 'stvc_validatecertify'); ?> </strong></label>
                    <input type="text" name="modificar_codigo" id="codigo" class="regular-text" placeholder="<?php echo esc_attr__('Ingrese el código aquí', 'stvc_validatecertify'); ?>">
                </div>
                    <input type="submit" class="button button-primary" value="<?php echo esc_attr__('Buscar certificado', 'stvc_validatecertify'); ?>">
                    </form>
            <?php
        }
    } else {
        ?>
            <div id="encabezado-menu" class="#top-menu">
                <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Editar Certificados', 'stvc_validatecertify' ); ?></h1>         
            </div>
            <div class="wp-heading-space"></div>
            <div class="ui form">
                <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Editar Certificados', 'stvc_validatecertify' ); ?></h1>
                </div>
                <p><?php esc_html_e( 'Ingrese el código de Certificado a modificar, para actualizar los nombres, apellidos, certificado y/o fecha de emisión.', 'stvc_validatecertify' ); ?></p>
                <hr class="wp-header-end">
            </div>
            <form method="post" class="ui form">
                <div class="field">
                    <label><?php echo esc_html__('Código:', 'stvc_validatecertify'); ?></label>
                    <input type="text" name="modificar_codigo" id="codigo" class="text" placeholder="<?php echo esc_attr__('Ingrese el código aquí', 'stvc_validatecertify'); ?>">
                </div>
                <input type="submit" class="button button-primary" value="<?php echo esc_attr__('Buscar certificado', 'stvc_validatecertify'); ?>">
            </form>
        <?php
    }
}

function stvc_eliminar_certificado() {
    if (isset($_POST['eliminar_certificado_confirmar']) && isset($_POST['codigo_eliminar'])) {
        
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('Acceso denegado', 'stvc_validatecertify'));
        }
    
        // Validar y obtener el código del certificado a eliminar
        $codigo_eliminar = sanitize_text_field($_POST['codigo_eliminar']);
    
        // Eliminar el certificado de la base de datos
        global $wpdb;
        $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';
    
        $certificado_eliminado = $wpdb->delete($tabla_stvc_validatecertify, array('codigo' => $codigo_eliminar));
    
        if ($certificado_eliminado) {
            // El certificado se eliminó correctamente, muestra un mensaje de éxito y el botón Volver
            ?>
                <div id="encabezado-menu" class="#top-menu">
                    <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Eliminar Certificados', 'stvc_validatecertify' ); ?></h1>
                </div>
                <div class="wp-heading-space"></div>
                <div class="ui form">
                    <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Eliminar Certificados', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <p><?php esc_html_e( 'Se eliminó el certificado de la base.', 'stvc_validatecertify' ); ?></p>
                        <hr class="wp-header-end">
                    <a href="<?php echo admin_url('admin.php?page=delete_certificates_stvc'); ?>" class="ui primary button"><?php echo esc_html__('Volver', 'stvc_validatecertify'); ?></a>
                </div>
            <?php
            } else {
            ?>
                <div id="encabezado-menu" class="#top-menu">
                    <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Eliminar Certificados', 'stvc_validatecertify' ); ?></h1>
                </div>
                <div class="wp-heading-space"></div>
                <div class="ui form">
                    <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Eliminar Certificados', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <p><?php esc_html_e( 'No se pudo eliminar el certificado, ocurrió un error.', 'stvc_validatecertify' ); ?></p>
                    <hr class="wp-header-end">
                    <br>
                    <a href="<?php echo admin_url('admin.php?page=delete_certificates_stvc'); ?>" class="ui primary button"><?php echo esc_html__('Buscar nuevamente', 'stvc_validatecertify'); ?></a>
                </div>
            <?php
        }
        } elseif (isset($_POST['eliminar_certificado']) && isset($_POST['codigo_eliminar'])) {
            // Mostrar el formulario de confirmación antes de eliminar
            $codigo_eliminar = sanitize_text_field($_POST['codigo_eliminar']);
            global $wpdb;
            $tabla_stvc_validatecertify = $wpdb->prefix . 'stvc_validatecertify';
            $certificado = $wpdb->get_row("SELECT * FROM $tabla_stvc_validatecertify WHERE codigo = '$codigo_eliminar'");
    
            if ($certificado) {
                ?>
                    <div id="encabezado-menu" class="#top-menu">
                        <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Eliminar Certificados', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <div class="wp-heading-space"></div>
                    <div class="ui form">
                        <div class="title-page-st">
                        <h1 class="wp-heading-inline"><?php esc_html_e( 'Eliminar Certificados', 'stvc_validatecertify' ); ?></h1>
                        </div>
                        <p><?php esc_html_e( '¿Estás seguro de que deseas eliminar el siguiente certificado?', 'stvc_validatecertify' ); ?></p>
    
                        <p><strong><?php echo esc_html__('Nombre:', 'stvc_validatecertify'); ?></strong> <?php echo esc_html($certificado->nombre); ?></p>
                        <p><strong><?php echo esc_html__('Apellidos:', 'stvc_validatecertify'); ?></strong> <?php echo esc_html($certificado->apellido); ?></p>
                        <p><strong><?php echo esc_html__('Certificado:', 'stvc_validatecertify'); ?></strong> <?php echo esc_html($certificado->curso); ?></p>
                        <p><strong><?php echo esc_html__('Fecha:', 'stvc_validatecertify'); ?></strong> <?php echo esc_html($certificado->fecha); ?></p>
                        <p><strong><?php echo esc_html__('Código:', 'stvc_validatecertify'); ?></strong> <?php echo esc_html($certificado->codigo); ?></p>
    
                        <form method="post" class="ui form">
                            <input type="hidden" name="codigo_eliminar" value="<?php echo esc_attr($codigo_eliminar); ?>">
                            <button type="submit" class="ui primary button" name="eliminar_certificado_confirmar"><?php echo esc_html__('Eliminar Certificado', 'stvc_validatecertify'); ?></button>
                            <a href="<?php echo admin_url('admin.php?page=delete_certificates_stvc'); ?>" class="ui secondary button"><?php echo esc_html__('Cancelar', 'stvc_validatecertify'); ?></a>
                        </form>
                    </div>
                <?php
            } else {
                // No se encontró el certificado con el código proporcionado
                ?>
                    <div id="encabezado-menu" class="#top-menu">
                        <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Eliminar Certificados', 'stvc_validatecertify' ); ?></h1>
                    </div>
                    <div class="wp-heading-space"></div>
                    <div class="ui form">
                        <div class="title-page-st">
                        <h1 class="wp-heading-inline"><?php esc_html_e( 'Eliminar Certificados', 'stvc_validatecertify' ); ?></h1>
                        </div>
                        <p><?php esc_html_e( 'No se encontró ningún certificado con ese código, por favor ingrese un código válido.', 'stvc_validatecertify' ); ?></p>
                        <a href="<?php echo admin_url('admin.php?page=delete_certificates_stvc'); ?>" class="ui primary button"><?php echo esc_html__('Buscar nuevamente', 'stvc_validatecertify'); ?></a>
                    </div>
                <?php
            }
        } else {
            ?>
                <div id="encabezado-menu" class="#top-menu">
                    <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Eliminar Certificados', 'stvc_validatecertify' ); ?></h1>
                </div>
                <div class="wp-heading-space"></div>
                    <div class="ui form">
                        <div class="title-page-st">
                        <h1 class="wp-heading-inline"><?php esc_html_e( 'Eliminar Certificados', 'stvc_validatecertify' ); ?></h1>
                        </div>
                    <p><?php esc_html_e( 'Ingrese el código de Certificado a eliminar, una vez eliminado no se podrá recuperar.', 'stvc_validatecertify' ); ?></p>
                    <hr class="wp-header-end">
                </div>
                <form method="post" class="ui form">
                <div class="field">
                    <label><?php echo esc_html__('Código:', 'stvc_validatecertify'); ?></label>
                    <input type="text" name="codigo_eliminar" class="regular-text" id="codigo_eliminar" required placeholder="<?php echo esc_attr__('Ingrese el código aquí', 'stvc_validatecertify'); ?>">
                    </div>
                    <input type="submit" class="button button-primary" name="eliminar_certificado" value="<?php echo esc_attr__('Eliminar Certificado', 'stvc_validatecertify'); ?>">
                </form>
            <?php
        }
    }

    // Muestra la pagina Ajustes
    function stvc_herramientas() {
    // Verificar si la licencia está activada
    $license_activated = get_user_meta(get_current_user_id(), 'license_activated', true);

    // Mostrar el contenido de la página de herramientas solo si la licencia está activada
    if ($license_activated) {
        ?>
            <div id="encabezado-menu" class="#top-menu">
                <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Herramientas', 'stvc_validatecertify' ); ?></h1>         
            </div>
            <div class="wp-heading-space"></div>
            <div class="stvc form">
                <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Herramientas', 'stvc_validatecertify' ); ?></h1>
                    <br>
                    <hr class="wp-header-end">
            </div>
            <table class="form-table">
                <tr>
                    <td><strong><?php esc_html_e('Copia de seguridad de los certificados', 'stvc_validatecertify'); ?></strong><br><?php esc_html_e('Genera un archivo .csv de la base de certificados.', 'stvc_validatecertify'); ?></td>
                    <td><form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"><?php wp_nonce_field('backup_nonce', 'backup_nonce_field'); ?><input type="hidden" name="action" value="backup_action"><button type="submit" class="button button-primary" name="backup"><?php esc_html_e('Generar Backup', 'stvc_validatecertify'); ?></button></form></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e('Carga Masiva', 'stvc_validatecertify'); ?></strong><br>
                        <?php esc_html_e('Hasta 1000 certificados en un archivo con formato .csv', 'stvc_validatecertify'); ?><br>
                        <a href="<?php echo esc_url(plugins_url('../sample-data/plantilla_carga_masiva.csv', __FILE__)); ?>" download>
                        <?php esc_html_e('Descargar plantilla.', 'stvc_validatecertify'); ?></a></td>
                    <td>
                        <form method="post" enctype="multipart/form-data">
                            <input type="file" name="file">
                            <label for="auto-generate"><br>
                                <input type="checkbox" id="auto-generate" name="auto_generate" value="1">
                                <?php esc_html_e('Generar código automáticamente', 'stvc_validatecertify'); ?>
                            </label> 
                            <button type="submit" class="button button-primary" name="submit">
                                <?php esc_html_e('Cargar datos', 'stvc_validatecertify'); ?></button>
                        </form>
                        <?php
                            if (isset($_POST['submit'])) {
                                if ($_FILES['file']['name']) {
                                    $filename = explode(".", $_FILES['file']['name']);
                                    if ($filename[1] == 'csv') {
                                        $handle = fopen($_FILES['file']['tmp_name'], "r");
                                        $count = 0;
                                        $duplicate_codes = array();

                                        while ($data = fgetcsv($handle)) {
                                            $count++;
                                            if ($count == 1) { // saltar la primera fila (encabezados)
                                                continue;
                                            }
                                            // obtener los valores del csv
                                            $nombre = sanitize_text_field($data[0]);
                                            $apellido = sanitize_text_field($data[1]);
                                            $curso = sanitize_text_field($data[2]);
                                            $fecha = sanitize_text_field($data[3]);

                                            if (isset($_POST['auto_generate']) && $_POST['auto_generate'] == 1) {
                                                // generar el código del certificado automáticamente
                                                $codigo = generar_codigo_certificado($nombre, $apellido, $curso, $fecha);
                                            } else {
                                                // obtener el código ingresado por el usuario
                                                $codigo = sanitize_text_field($data[4]);
                                            }

                                            // verificar si el código ya existe en la base de datos
                                            global $wpdb;
                                            $table_name = $wpdb->prefix . 'stvc_validatecertify';
                                            $duplicate_code = $wpdb->get_var($wpdb->prepare("SELECT codigo FROM $table_name WHERE codigo = %s", $codigo));

                                            if ($duplicate_code) {
                                                // código duplicado encontrado, agregarlo a la lista de códigos duplicados
                                                $duplicate_codes[] = $duplicate_code;
                                            } else {
                                                // agregar el certificado a la base de datos
                                                $wpdb->insert(
                                                    $table_name,
                                                    array(
                                                        'codigo' => $codigo,
                                                        'nombre' => $nombre,
                                                        'apellido' => $apellido,
                                                        'curso' => $curso,
                                                        'fecha' => $fecha
                                                    )
                                                );
                                            }

                                            if ($count >= 1000) { // solo permitir la carga de 1000 registros por archivo
                                                break;
                                            }
                                        }
                                        fclose($handle);

                                        if (!empty($duplicate_codes)) {
                                            // mostrar mensaje de códigos duplicados encontrados
                                            echo '<div class="notice notice-error is-dismissible"><p>';
                                            echo esc_html__('No se pudo cargar el archivo debido a códigos duplicados encontrados:', 'stvc_validatecertify');
                                            echo '<br>';
                                            foreach ($duplicate_codes as $duplicate_code) {
                                                echo esc_html($duplicate_code) . '<br>';
                                            }
                                            echo '</p></div>';
                                        } else {
                                            // mostrar mensaje de éxito si no hay códigos duplicados
                                            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Los datos se han cargado correctamente en la base de datos.', 'stvc_validatecertify') . '</p></div>';
                                        }
                                    } else {
                                        echo "<p>" . esc_html__('El archivo debe ser un archivo CSV.', 'stvc_validatecertify') . "</p>";
                                    }
                                } else {
                                    echo "<p>" . esc_html__('Por favor seleccione un archivo.', 'stvc_validatecertify') . "</p>";
                                }
                            }
                        ?>
                    </td>
                <tr>
                    <td><strong><?php esc_html_e('ShortCode Validate Certify', 'stvc_validatecertify'); ?></strong><br><?php esc_html_e('Agregar el shortcode donde desee validar tus certificados,', 'stvc_validatecertify'); ?><br><?php esc_html_e('aquí se buscará por el código.', 'stvc_validatecertify'); ?></td>
                        <td><input id="shortcodeInput" type="text" class="centered-content-stvc" value="[ValidateCertify]" readonly>
                        <button class="button button-primary" type="button" onclick="copyToClipboard()">
                        <?php esc_html_e('Copiar', 'stvc_validatecertify'); ?>
                        </button></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e('Widget Elementor', 'stvc_validatecertify'); ?></strong><br><?php esc_html_e('Disponible desde Elementor v3.14.1.', 'stvc_validatecertify'); ?></td></td>
                    <td><strong>ValidateCertify</strong></td>
                </tr>
            </table>
                <h1 class="wp-heading-inline"><?php esc_html_e( 'Eliminar la base de datos', 'stvc_validatecertify' ); ?></h1>
                <table class="form-table">
                    <tr>
                        <td><strong><?php esc_html_e('Eliminar la base de datos', 'stvc_validatecertify'); ?></strong><br>
                        <?php esc_html_e('Elimina definitivamente la base. recuerda tener un backup.', 'stvc_validatecertify'); ?></td>
                        <td>
                            <?php
                                if (isset($_POST['borrar_base_datos'])) {
                                    check_admin_referer('borrar_base_datos_nonce', 'borrar_base_datos_nonce_field');

                                    if (isset($_POST['confirmar_eliminar']) && $_POST['confirmar_eliminar'] === 'si' && isset($_POST['confirmar_checkbox']) && $_POST['confirmar_checkbox'] === 'on') {
                                        global $wpdb;
                                        $tabla = $wpdb->prefix . 'stvc_validatecertifyProPx';
                                        $wpdb->query("TRUNCATE TABLE $tabla");

                                        echo "<div class='notice notice-success is-dismissible'><p>" . esc_html__('La base de datos ha sido eliminada exitosamente.', 'stvc_validatecertify') . "</p></div>";
                                    } else {
                                        echo "<div class='notice notice-warning is-dismissible'><p>" . esc_html__('No se ha eliminado la base de datos.', 'stvc_validatecertify') . "</p></div>";
                                    }
                                }
                            ?>
                                <div class="wrap">
                                    <form method="post">
                                        <?php wp_nonce_field('borrar_base_datos_nonce', 'borrar_base_datos_nonce_field'); ?>
                                        <input type="hidden" name="confirmar_eliminar" value="si">
                                        <label for="confirmar_checkbox"><?php esc_html_e('Confirmar eliminación:', 'stvc_validatecertify'); ?></label>
                                        <input type="checkbox" id="confirmar_checkbox" name="confirmar_checkbox">
                                        <button type="submit" class="button button-primary" name="borrar_base_datos" onclick="return confirmarEliminacion()"><?php esc_html_e('Eliminar Base', 'stvc_validatecertify'); ?></button>
                                    </form>
                                </div>

                                <script>
                                    // Función para confirmar la eliminación
                                    function confirmarEliminacion() {
                                        if (!document.getElementById("confirmar_checkbox").checked) {
                                            alert('<?php esc_html_e('Por favor, confirma la eliminación marcando la casilla.', 'stvc_validatecertify'); ?>');
                                            return false; // Evita enviar el formulario si el checkbox no está marcado
                                        }
                                        return confirm('<?php esc_attr_e('¿Estás seguro de que deseas eliminar la base de datos, recuerda crear un backup antes de eliminar?', 'stvc_validatecertify'); ?>');
                                    }
                                </script>
                        </td>
                    </tr>
                </table>
                </div>
                <script>
                    function copyToClipboard() {
                        const input = document.getElementById('shortcodeInput');
                        input.select();
                        document.execCommand('copy');
                        alert('<?php esc_html_e('Shortcode copiado al portapapeles', 'stvc_validatecertify'); ?>');
                    }
            </script>
            <?php
        } else {
            // Si la licencia no está activada, mostrar un mensaje indicando que las herramientas no están disponibles
            ?>
            <div id="encabezado-menu" class="#top-menu">
                <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Herramientas', 'stvc_validatecertify' ); ?></h1>         
            </div>
            <div class="wp-heading-space"></div>
            <div class="ui form">
                <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Herramientas', 'stvc_validatecertify' ); ?></h1>
                </div>
                <p><?php esc_html_e( 'Para poder utilizar todas las herramientas de ValidateCertify ingrese la licencia respectiva.' ); ?></p>
                <hr class="wp-header-end">
            </div>
            <form method="post" class="ui form">
                <div class="field">
                <a href="admin.php?page=license_validatecertify" class="page-title-action"><?php esc_html_e( 'Activar Licencia', 'stvc_validatecertify' ); ?></a>
            </form>
        <?php
        }
    }

// Función que realiza la acción de backup
add_action('admin_post_backup_action', 'backup_datos');
function backup_datos() {
    // Verificar el nonce para evitar ataques CSRF
    if (!isset($_POST['backup_nonce_field']) || !wp_verify_nonce($_POST['backup_nonce_field'], 'backup_nonce')) {
        wp_die('Acceso no autorizado.', 'Error de seguridad');
    }
    global $wpdb;

        // Obtener la fecha actual
        $fecha_actual = date('d_m_Y');
    
        // Obtener los datos de la base de datos
        $stvc_validatecertify = $wpdb->get_results('SELECT nombre, apellido, curso, fecha, codigo FROM '.$wpdb->prefix.'stvc_validatecertify', ARRAY_A);
        // Crear el archivo CSV
        $file = fopen('Backup_ValidateCertifyProPlus_' . $fecha_actual . '.csv', 'w');
        // Especificar la codificación UTF-8 para admitir caracteres de diferentes idiomas
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));// BOM para UTF-8

        fputcsv($file, array('Nombre', 'Apellido', 'Certificado', 'Fecha','Codigo'));

        foreach ($stvc_validatecertify as $row) {
            // Convertir los valores a UTF-8
            $sanitized_row = array_map(function($value) {
                return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            }, $row);
            fputcsv($file, $sanitized_row);
        }

        fclose($file);
        // Descargar el archivo CSV
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="Backup_ValidateCertifyProPlus_' . $fecha_actual . '.csv";');
            readfile('Backup_ValidateCertifyProPlus_' . $fecha_actual . '.csv');
            unlink('Backup_ValidateCertifyProPlus_' . $fecha_actual . '.csv');
        exit;
}

    function stvc_licencia() {
        $license_activated = get_user_meta(get_current_user_id(), 'license_activated', true);
        ?>
            <div id="encabezado-menu" class="#top-menu">
                <h1 class="mi-plugin-titulo"><?php esc_html_e( 'Licencia ValidateCertify', 'stvc_validatecertify' ); ?></h1>         
            </div>
            <div class="wp-heading-space"></div>
            <div class="ui form">
                <div class="title-page-st">
                    <h1 class="wp-heading-inline"><?php esc_html_e( 'Licencia ValidateCertify', 'stvc_validatecertify' ); ?></h1>
                </div>
                <p><?php esc_html_e('Por favor ingrese el License Email y la License Key para poder utilizar todas las funciones de ValidateCertify, recuerde que puede encontrarlo en el correo de compra que le enviamos. Una vez activo podrá ver el menú de Herramientas', 'stvc_validatecertify'); ?></p>
                <hr class="wp-header-end">
                </div>
                    <div class="ui form">
                        <div class="field">
                            <label><strong><?php esc_html_e('License Email', 'stvc_validatecertify'); ?></strong></label>
                            <div class="ui labeled input">
                                <input type="text" id="stvc_license_mail_key" name="stvc_license_mail_key" placeholder="<?php echo esc_attr__('Ingresa el correo de activación', 'stvc_validatecertify'); ?>">
                            </div>
                        </div>
                        <div class="field">
                            <label><strong><?php esc_html_e('License Key', 'stvc_validatecertify'); ?></strong></label>
                            <div class="ui labeled input">
                                <input type="text" id="stvc_license_key" name="stvc_license_key" placeholder="<?php echo esc_attr__('Ingresa tu licencia', 'stvc_validatecertify'); ?>">
                            </div>
                        </div>
                        <br>
                        <button class="button button-primary" id="stvc_validate_license_btn">Validar Licencia</button>
                        <div id="stvc_license_message"></div>
                        <div id="stvc_license_status"><?php esc_html_e('Estado de la Licencia: ', 'stvc_validatecertify'); ?><?php echo $license_activated ? esc_html__('Activado', 'stvc_validatecertify') : esc_html__('No activado', 'stvc_validatecertify'); ?></div>
                        
                </div>
    
                <script>
                    jQuery(document).ready(function($) {
                        $('#stvc_validate_license_btn').click(function() {
                            var email = $('#stvc_license_mail_key').val();
                            var license_key = $('#stvc_license_key').val();
                            var product_id = 'ValidateCertifyProPlus';
                            var platform = window.location.href;
    
                            $.ajax({
                                url: 'https://systenjrh.com?wc-api=software-api&request=activation',
                                type: 'GET',
                                dataType: 'json',
                                data: {
                                    email: email,
                                    license_key: license_key,
                                    product_id: product_id,
                                    platform: platform
                                },
                                success: function(response) {
                                    if (response && response.activated === true) {
                                        $('#stvc_license_message').html('<?php esc_html_e("¡La licencia se ha validado correctamente!", "stvc_validatecertify"); ?>');
                                        $('#stvc_license_status').html('<?php esc_html_e("Estado de la Licencia: Activado", "stvc_validatecertify"); ?>');
                                        // Activar opción de menú una vez validada la licencia
                                        $('#tools_validatecertify').removeClass('hidden'); 
                                        // Bloquear campos de entrada
                                        $('#stvc_license_mail_key, #stvc_license_key').prop('disabled', true);
                                        // Realizar una solicitud AJAX para actualizar la metadata del usuario
                                        $.ajax({
                                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                            type: 'POST',
                                            dataType: 'json',
                                            data: {
                                                action: 'update_license_activated',
                                                user_id: <?php echo get_current_user_id(); ?>,
                                                value: true,
                                                email: email,
                                                license_key: license_key
                                            },
                                            success: function(response) {
                                                // Manejar la respuesta si es necesario
                                            },
                                            error: function(xhr, status, error) {
                                                // Manejar errores si es necesario
                                            }
                                        });
                                    } else {
                                        if (response && response.error === "Exceeded maximum number of activations") {
                                            // Mostrar mensaje de máximo número de activaciones alcanzado
                                            $('#stvc_license_message').html('<?php esc_html_e("Excedido el número máximo de activaciones", "stvc_validatecertify"); ?>');
                                        } else {
                                            // Mostrar mensaje de error genérico
                                            $('#stvc_license_message').html('<?php esc_html_e("¡Error al validar la licencia!", "stvc_validatecertify"); ?>');
                                        }
                                        $('#stvc_license_status').html('<?php esc_html_e("Estado de la Licencia: No activado", "stvc_validatecertify"); ?>');
                                        // Eliminar mensaje de licencia activa en caso de error
                                        $('#stvc_license_active_message').empty();
                                    }
                                },
                                error: function(xhr, status, error) {
                                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                                    $('#stvc_license_message').html('<?php esc_html_e("Error al conectar con el servidor:"); ?> ' + errorMessage);
                                    $('#stvc_license_status').html('<?php esc_html_e("Estado de la Licencia: No activado"); ?>');
                                    // Eliminar mensaje de licencia activa en caso de error
                                    $('#stvc_license_active_message').empty();
                                }
                            });
                        });
    
                        // Verificar el estado de la licencia al cargar la página
                        $(document).ready(function() {
                        // Comprueba si la licencia está activada en user_meta
                        var licenseActivated = <?php echo get_user_meta(get_current_user_id(), 'license_activated', true) ? 'true' : 'false'; ?>;
                        if (licenseActivated) {
                            $('#stvc_license_status').html('<?php esc_html_e("Estado de la Licencia: Activado", "stvc_validatecertify"); ?>');
                            $('#stvc_license_mail_key, #stvc_license_key').prop('disabled', true);
                            // Obtener los valores de los metadatos de usuario
                            var email = '<?php echo esc_attr(get_user_meta(get_current_user_id(), 'email_user_license', true)); ?>';
                            var licenseKey = '<?php echo esc_attr(get_user_meta(get_current_user_id(), 'license_key_validate', true)); ?>';
    
                            // Establecer los valores de los campos de entrada
                            $('#stvc_license_mail_key').val(email);
                            $('#stvc_license_key').val(licenseKey);     
    
                            // Activar opción de menú si la licencia está activada
                            $('#tools_validatecertify').removeClass('hidden'); 
                            } else {
                                $('#stvc_license_status').html('<?php esc_html_e("Estado de la Licencia: No activado", "stvc_validatecertify"); ?>');
                            }
                        });
                    });
                </script>
        <?php
    }