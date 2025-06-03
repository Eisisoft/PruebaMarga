<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programación de Tareas - Gestión</title>
    <style>
        body { font-family: sans-serif; margin: 20px; font-size: 0.9em; }
        /* ... (other styles collapsed for brevity) ... */
        .filter-group, .selector-group { margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 5px; background-color: #f9f9f9;}
        .visualization-group { margin-top: 20px; padding: 15px; border: 1px solid #eee; border-radius: 5px; background-color: #fff;}
        .global-visualization-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .global-visualization-table th, .global-visualization-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .nav-tabs { margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px;}
        .nav-tabs a { padding: 10px 15px; text-decoration: none; border: 1px solid transparent; margin-right: 5px; color: #007bff; border-radius: 4px 4px 0 0;}
        .nav-tabs a.active { border-color: #ddd #ddd #fff #ddd; background-color: #fff; color: #495057;}
        .tipos-elementos-container, .ubicaciones-container, .tareas-diarias-container { padding: 10px; margin-top: 5px; border-radius: 4px; }
        .tipos-elementos-container { background-color: #e9f7ff; border-top: 2px solid #007bff;}
        .ubicaciones-container { background-color: #d1e7dd; border-top: 2px solid #198754;}
        .tareas-diarias-container { font-size: 0.9em; background-color: #f8f9fa; border: 1px dashed #ccc; }
        .drilldown-table, .sub-drilldown-table, .acciones-list-table { width: 100%; margin-top: 5px; font-size: 0.95em; border-collapse: collapse; }
        .drilldown-table th, .drilldown-table td, .sub-drilldown-table th, .sub-drilldown-table td, .acciones-list-table th, .acciones-list-table td { border: 1px solid #dee2e6; padding: 6px; text-align: left; }
        .drilldown-table th { background-color: #d1ecf1;}
        .sub-drilldown-table th { background-color: #c3e6cb; }
        .acciones-list-table th { background-color: #f8f9fa; }
        .clickable-month:hover { background-color: #e9ecef; }
        .dia-con-acciones, .tipo-elemento-link, .group-name-link { cursor: pointer; text-decoration: underline; color: #007bff; }

        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); }
        .modal-content { background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 750px; border-radius: 5px; position: relative; }
        .modal-close { color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
        .modal-tabs { border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px; }
        .modal-tab-button { background-color: #f0f0f0; border: 1px solid #ccc; padding: 8px 12px; cursor: pointer; margin-right: 5px; border-radius: 4px 4px 0 0; }
        .modal-tab-button.active { background-color: #007bff; color: white; border-bottom-color: #007bff; }
        .modal-tab-content { display: none; padding: 10px 0; }
        .modal-tab-content.active { display: block; }
        .modal-section { margin-bottom: 15px; }
        .modal-section h4 { margin-top: 0; margin-bottom: 10px; color: #333; border-bottom: 1px solid #eee; padding-bottom: 5px;}
        .modal-section p { margin: 0 0 8px 0; line-height: 1.6; }
        .modal-section label {font-weight: normal; display: inline-block; margin-right: 5px;}
        .modal-section input[type="date"], .modal-section select, .modal-section textarea, .modal-section input[type="text"], .modal-section input[type="email"], .modal-section input[type="number"] {
            width: calc(100% - 10px); padding: 8px; margin-bottom:10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
         .modal-section .form-field-group p strong { display: inline-block; min-width: 120px; font-weight:600; }
        .modal-actions button, .modal-sub-actions button { margin-right: 10px; margin-top: 5px; margin-bottom: 5px; }
        .modal-sub-actions { margin-top:15px; padding:15px; background-color:#f0f0f0; border-radius:4px; }
        #modal-comentarios-list ul, .attachments-ots-list ul { list-style-type: none; padding-left: 0; margin-top:5px;}
        #modal-comentarios-list li, .attachments-ots-list li { background-color:#f9f9f9; padding:8px; border-radius:3px; margin-bottom:5px; border:1px solid #eee;}
        #modal-comentarios-list li small { display:block; color:#777; }
        .attachments-ots-list li small {font-size:0.8em; color:#555;}
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Nav tabs and Form (collapsed for brevity) -->
    <div class="nav-tabs">...</div>
    <h1>Programación de Tareas - Gestión</h1>
    <form id="filtrosForm" method="POST" action="<?php echo site_url('TareasProgramadas/Programacion/view_tab/gestion'); ?>">...</form>

    <div id="actions-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close" id="btn_modal_close_x">&times;</span>
            <h3 id="modal-title-main">Acciones Programadas para <span id="modal-title-context"></span></h3>
            <div class="modal-tabs">
                <button class="modal-tab-button active" data-tab="modal-tab-info">Acciones</button>
                <button class="modal-tab-button" data-tab="modal-tab-ultimo-registro">Último Registro</button>
            </div>

            <div id="modal-tab-info" class="modal-tab-content active">
                <!-- ... (content from previous step - action list and mass action forms) ... -->
            </div>

            <div id="modal-tab-ultimo-registro" class="modal-tab-content">
                <div id="modal-ultimo-registro-display">
                    <h4>Detalles del Último Registro</h4>
                    <p><strong>ID Registro:</strong> <span id="modal-registro-id">N/A</span></p>
                    <p><strong>Fecha Registro:</strong> <span id="modal-registro-fecha">N/A</span></p>
                    <p><strong>Realizado por:</strong> <span id="modal-registro-realizado-por">N/A</span></p>
                    <p><strong>Estado Registrado:</strong> <span id="modal-registro-estado-registrado">N/A</span></p>
                    <p><strong>Valores Medidos:</strong> <span id="modal-registro-valores">N/A</span></p>
                    <p><strong>Observaciones:</strong> <span id="modal-registro-observaciones">N/A</span></p>
                    <button type="button" id="modal-btn-modificar-registro" class="button" style="display:none;">Modificar Último Registro</button>
                </div>

                <div id="modal-modificar-registro-form" class="modal-sub-actions" style="display:none;">
                    <h4>Modificar Último Registro</h4>
                    <p><label for="edit-registro-estado">Estado Registrado:</label>
                        <select id="edit-registro-estado">
                            <option value="Realizado Correctamente">Realizado Correctamente</option>
                            <option value="Realizado con Incidencias">Realizado con Incidencias</option>
                            <option value="No Realizado">No Realizado</option>
                        </select>
                    </p>
                    <p><label for="edit-registro-valores">Valores Medidos:</label><input type="text" id="edit-registro-valores"></p>
                    <p><label for="edit-registro-observaciones">Observaciones:</label><textarea id="edit-registro-observaciones" rows="3"></textarea></p>
                    <button type="button" id="modal-btn-guardar-cambios-registro" class="button">Guardar Cambios</button>
                    <button type="button" id="modal-btn-cancelar-modificacion" class="button" style="background-color:#6c757d;">Cancelar</button>
                </div>

                <div class="modal-section" style="margin-top: 20px;">
                    <h4>Comentarios del Registro</h4>
                    <div id="modal-comentarios-list" style="max-height: 150px; overflow-y: auto; margin-bottom:10px;"></div>
                    <div>
                        <label for="modal-nuevo-comentario-texto">Nuevo Comentario:</label>
                        <textarea id="modal-nuevo-comentario-texto" rows="2"></textarea>
                        <button type="button" id="modal-btn-anadir-comentario" class="button">Añadir Comentario</button>
                    </div>
                </div>

                <div class="modal-section" style="margin-top: 20px;">
                    <h4>Archivos Adjuntos al Registro</h4>
                    <div id="modal-archivos-list" class="attachments-ots-list">
                        <p>No hay archivos adjuntos.</p>
                    </div>
                </div>
                <div class="modal-section" style="margin-top: 20px;">
                    <h4>Órdenes de Trabajo Vinculadas</h4>
                    <div id="modal-ots-list" class="attachments-ots-list">
                        <p>No hay órdenes de trabajo vinculadas.</p>
                    </div>
                </div>
            </div>
            <button type="button" id="btn_modal_close_button" class="button" style="margin-top:15px;">Cerrar</button>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // ... (Existing L1, L2, L3 drilldown, filter logic, and Modal Tab 1 logic - collapsed for brevity) ...
        var mesesCalendario = <?php echo json_encode($meses_calendario); ?>;
        var currentJsDate = new Date();
        var currentJsMonth = currentJsDate.getMonth() + 1;
        var currentJsYear = currentJsDate.getFullYear();

        function getSelectedActionIds() { /* ... */ return $('#modal-lista-acciones .accion-checkbox:checked').map(function(){ return $(this).val(); }).get(); }
        function hideAllModalSubActions() { /* ... */ $('#modal-reprogramar-section, #modal-registro-masivo-section, #modal-asignar-accion-section, #modal-modificar-registro-form').hide(); $('#modal-main-actions, #modal-ultimo-registro-display').show(); }

        // --- Modal Logic ---
        // (Open modal and Tab 1 population - from previous step)
        $('#tabla-visualizacion-global-body').on('click', '.dia-con-acciones', function(e) {
            /* ... (existing code to open modal and load Tab 1) ... */
             $('#modal-tab-ultimo-registro').data('current-accion-id', '').data('current-registro-id', '');
             $('#modal-tab-ultimo-registro').html('<p>Seleccione una única acción en la Pestaña 1 para ver/modificar su último registro.</p>');
             // Ensure original structure is there if it was replaced by the message
             if ($('#modal-tab-ultimo-registro').find('#modal-ultimo-registro-display').length === 0) {
                $('#modal-tab-ultimo-registro').html(getUltimoRegistroTabHtmlStructure()); // Function to return the HTML structure
             }
        });

        // (Modal Tab 1: Select All, Reprogram, Register, Assign - from previous step)

        // Modal Tab Change Logic
        $('.modal-tab-button').on('click', function() {
            var tabId = $(this).data('tab');
            $('.modal-tab-button').removeClass('active'); $('.modal-tab-content').removeClass('active');
            $(this).addClass('active'); $('#' + tabId).addClass('active');
            hideAllModalSubActions();

            if (tabId === 'modal-tab-ultimo-registro') {
                var selectedIds = getSelectedActionIds();
                if (selectedIds.length === 1) {
                    var accionId = selectedIds[0];
                    $('#modal-tab-ultimo-registro').data('current-accion-id', accionId);
                    loadUltimoRegistro(accionId);
                } else {
                    // Ensure original structure is there before showing message
                    if ($('#modal-tab-ultimo-registro').find('#modal-ultimo-registro-display').length === 0) {
                         $('#modal-tab-ultimo-registro').html(getUltimoRegistroTabHtmlStructure());
                    }
                    $('#modal-ultimo-registro-display p span').text('N/A'); // Clear old data
                    $('#modal-comentarios-list, #modal-archivos-list, #modal-ots-list').html('<p>No hay datos.</p>');
                    $('#modal-tab-ultimo-registro > div:not(#modal-ultimo-registro-display, #modal-modificar-registro-form, .modal-section)').html('<p>Por favor, seleccione una única acción en la pestaña "Acciones" para ver su último registro.</p>');
                    $('#modal-btn-modificar-registro').hide();
                }
            }
        });

        function getUltimoRegistroTabHtmlStructure() {
            return `
                <div id="modal-ultimo-registro-display"><h4>Detalles del Último Registro</h4><p><strong>ID Registro:</strong> <span id="modal-registro-id">N/A</span></p><p><strong>Fecha Registro:</strong> <span id="modal-registro-fecha"></span></p><p><strong>Realizado por:</strong> <span id="modal-registro-realizado-por"></span></p><p><strong>Estado Registrado:</strong> <span id="modal-registro-estado-registrado"></span></p><p><strong>Valores Medidos:</strong> <span id="modal-registro-valores"></span></p><p><strong>Observaciones:</strong> <span id="modal-registro-observaciones"></span></p><button type="button" id="modal-btn-modificar-registro" class="button" style="display:none;">Modificar</button></div>
                <div id="modal-modificar-registro-form" class="modal-sub-actions" style="display:none;"><h4>Modificar Último Registro</h4><p><label>Estado:</label><select id="edit-registro-estado"><option value="Realizado Correctamente">Correcto</option><option value="Realizado con Incidencias">Con Incidencias</option><option value="No Realizado">No Realizado</option></select></p><p><label>Valores:</label><input type="text" id="edit-registro-valores"></p><p><label>Obs.:</label><textarea id="edit-registro-observaciones" rows="3"></textarea></p><button type="button" id="modal-btn-guardar-cambios-registro" class="button">Guardar</button><button type="button" id="modal-btn-cancelar-modificacion" class="button" style="background-color:#6c757d;">Cancelar</button></div>
                <div class="modal-section" style="margin-top:10px;"><h4>Comentarios</h4><div id="modal-comentarios-list" style="max-height:120px; overflow-y:auto; margin-bottom:10px;"></div><div><label for="modal-nuevo-comentario-texto">Nuevo:</label><textarea id="modal-nuevo-comentario-texto" rows="2"></textarea><button type="button" id="modal-btn-anadir-comentario" class="button">Añadir</button></div></div>
                <div class="modal-section"><h4>Archivos Adjuntos</h4><div id="modal-archivos-list" class="attachments-ots-list"><p>No hay archivos.</p></div></div>
                <div class="modal-section"><h4>Órdenes de Trabajo Vinculadas</h4><div id="modal-ots-list" class="attachments-ots-list"><p>No hay OTs.</p></div></div>`;
        }


        function loadUltimoRegistro(accionId) {
            var container = $('#modal-tab-ultimo-registro');
            if (container.find('#modal-ultimo-registro-display').length === 0) { // If structure was replaced by message
                container.html(getUltimoRegistroTabHtmlStructure());
            }

            $('#modal-ultimo-registro-display p span').text('Cargando...');
            $('#modal-comentarios-list, #modal-archivos-list, #modal-ots-list').html('<p>Cargando...</p>');
            $('#modal-btn-modificar-registro').hide();

            $.ajax({
                url: "<?php echo site_url('TareasProgramadas/Programacion/ajax_get_ultimo_registro'); ?>",
                type: 'POST', data: { id_accion: accionId }, dataType: 'json',
                success: function(response) {
                    if (response.status === 'success' && response.data) {
                        var reg = response.data;
                        container.data('current-registro-id', reg.id_registro);
                        $('#modal-registro-id').text(reg.id_registro || 'N/A');
                        $('#modal-registro-fecha').text(reg.fecha_registro || 'N/A');
                        $('#modal-registro-realizado-por').text(reg.realizado_por || 'N/A');
                        $('#modal-registro-estado-registrado').text(reg.estado_registrado || 'N/A');
                        $('#modal-registro-valores').text(reg.valores_medidos || 'N/A');
                        $('#modal-registro-observaciones').text(reg.observaciones || 'N/A');
                        $('#modal-btn-modificar-registro').show();

                        var comentariosHtml = '';
                        if (reg.comentarios && reg.comentarios.length > 0) {
                            reg.comentarios.forEach(function(com) { comentariosHtml += '<li><strong>' + com.usuario + '</strong> (' + com.fecha_comentario + '):<br>' + com.texto + '</li>'; });
                        } else { comentariosHtml = '<li>No hay comentarios.</li>'; }
                        $('#modal-comentarios-list').html(comentariosHtml);

                        var archivosHtml = '';
                        if (reg.archivos_adjuntos && reg.archivos_adjuntos.length > 0) {
                            archivosHtml = '<ul>';
                            reg.archivos_adjuntos.forEach(function(arc) { archivosHtml += '<li><a href="' + arc.url_archivo + '" target="_blank">' + arc.nombre_archivo + '</a> <small>(Subido: ' + arc.fecha_subida + ')</small></li>'; });
                            archivosHtml += '</ul>';
                        } else { archivosHtml = '<p>No hay archivos adjuntos.</p>'; }
                        $('#modal-archivos-list').html(archivosHtml);

                        var otsHtml = '';
                        if (reg.ordenes_trabajo_vinculadas && reg.ordenes_trabajo_vinculadas.length > 0) {
                            otsHtml = '<ul>';
                            reg.ordenes_trabajo_vinculadas.forEach(function(ot) { otsHtml += '<li>OT ID: ' + ot.id_ot + ' - ' + ot.descripcion_ot + ' (Estado: ' + ot.estado_ot + ')</li>'; });
                            otsHtml += '</ul>';
                        } else { otsHtml = '<p>No hay órdenes de trabajo vinculadas.</p>'; }
                        $('#modal-ots-list').html(otsHtml);

                    } else {
                        $('#modal-ultimo-registro-display p span').text('No disponible');
                        $('#modal-comentarios-list').html('<li>' + (response.message || 'No hay registro para esta acción.') + '</li>');
                        $('#modal-archivos-list').html('<p>No disponible.</p>');
                        $('#modal-ots-list').html('<p>No disponible.</p>');
                        container.data('current-registro-id', '');
                    }
                },
                error: function() {
                    $('#modal-ultimo-registro-display p span').text('Error');
                    $('#modal-comentarios-list, #modal-archivos-list, #modal-ots-list').html('<p>Error al cargar datos.</p>');
                }
            });
        }

        // (Modify, Save, Cancel, Add Comment logic - from previous step, ensure correct selectors if tab structure was re-added)
        // ... All the #modal-btn-modificar-registro, #modal-btn-guardar-cambios-registro etc. listeners

        $('#btn_modal_close_x, #btn_modal_close_button').on('click', function() { $('#actions-modal').hide(); });
    });
    </script>
</body>
</html>
