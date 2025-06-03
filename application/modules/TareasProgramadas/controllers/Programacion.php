<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Programacion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('TareasProgramadas/Programacion_model', 'programacion_model');
        $this->load->helper('url');
        $this->load->library('session'); // Load session library
    }

    public function index($tab_name = 'actual') {
        $this->view_tab($tab_name);
    }

    public function view_tab($tab_name = 'actual') {
        $data = array();
        $data['current_tab'] = $tab_name;

        if ($tab_name == 'gestion') {
            $current_filters = [];
            $is_new_filter_request = !empty($this->input->post()) || !empty($this->input->get()); // Check if new filters are being applied

            if ($is_new_filter_request) {
                // Define expected filter keys from POST/GET
                $expected_filters = [
                    'establecimiento', 'edificio', 'bloque', 'departamento', // Selectors
                    'filtro_plan', 'filtro_grupo', 'filtro_subgrupo',
                    'filtro_tipo_elemento_accion', 'filtro_tipo_tarea',
                    'filtro_frecuencia', 'filtro_realizado_por',
                    'filtro_fecha_desde', 'filtro_fecha_hasta',
                    'quick_filter_tareas_diarias', 'quick_filter_obligatorio_cumplimiento' // Quick filters
                ];
                foreach ($expected_filters as $key) {
                    $value = $this->input->post_get($key); // Gets from POST or GET
                    if ($value !== null) {
                        // Special handling for potentially multiple select fields if needed
                        // For now, direct assignment. Ensure your view/JS sends arrays correctly if applicable.
                        $current_filters[$key] = $value;
                    }
                }
            } else {
                // No new filters, try to load from session
                $session_filters = $this->session->userdata('filtros_programacion_predeterminados');
                if ($session_filters) {
                    $current_filters = $session_filters;
                }
            }

            $data['current_filters'] = $current_filters; // Pass current filters to the view

            // Fetch data for selectors
            $data['establecimientos'] = $this->programacion_model->get_establecimientos();
            $data['edificios'] = $this->programacion_model->get_edificios(isset($current_filters['establecimiento']) ? $current_filters['establecimiento'] : null);
            $data['bloques'] = $this->programacion_model->get_bloques(isset($current_filters['edificio']) ? $current_filters['edificio'] : null); // Assuming edificio can be multiple, adjust model if needed
            $data['departamentos'] = $this->programacion_model->get_departamentos(isset($current_filters['establecimiento']) ? $current_filters['establecimiento'] : null);

            // Default selections for selectors (can be overridden by $current_filters in the view)
            $data['default_establecimiento_id'] = isset($data['establecimientos'][0]['id']) ? $data['establecimientos'][0]['id'] : null;
            $data['default_departamento_id'] = isset($data['departamentos'][0]['id']) ? $data['departamentos'][0]['id'] : null;

            // Fetch data for filter dropdowns
            $data['planes_filtros'] = $this->programacion_model->get_planes_filtros();
            $data['grupos_filtros'] = $this->programacion_model->get_grupos_filtros();
            $data['subgrupos_filtros'] = $this->programacion_model->get_subgrupos_filtros(isset($current_filters['filtro_grupo']) ? $current_filters['filtro_grupo'] : null);
            $data['tipos_elementos_acciones_filtros'] = $this->programacion_model->get_tipos_elementos_acciones_filtros(isset($current_filters['filtro_subgrupo']) ? $current_filters['filtro_subgrupo'] : null);
            $data['tipos_tareas_filtros'] = $this->programacion_model->get_tipos_tareas_filtros();
            $data['frecuencias_filtros'] = $this->programacion_model->get_frecuencias_filtros();
            $data['realizado_por_filtros'] = $this->programacion_model->get_realizado_por_filtros();

            $data['vista_global_data'] = $this->programacion_model->get_vista_global_data($current_filters);
            $data['meses_calendario'] = array('Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
            $this->load->view('TareasProgramadas/gestion_programacion_view', $data);

        } elseif ($tab_name == 'actual') {
            $data['title'] = "Programación Actual - Calendario y Mapa de Calor";
            $programacion_actual_filtros = []; // TODO: Implement filter handling for 'actual' tab if needed
            $programacion_data = $this->programacion_model->get_datos_programacion_actual($programacion_actual_filtros);
            $data['eventos_calendario'] = $programacion_data['eventos_calendario'];
            $data['datos_mapa_calor'] = $programacion_data['datos_mapa_calor'];
            $this->load->view('TareasProgramadas/programacion_actual_view', $data);
        } else {
            redirect('TareasProgramadas/Programacion/view_tab/actual');
        }
    }

    public function ajax_guardar_filtros_predeterminados() {
        if (!$this->input->is_ajax_request()) {
            show_404(); return;
        }
        $filtros_seleccionados = $this->input->post(); // Get all POST data
        // Basic sanitization/filtering could be done here if needed
        // For example, only keep known filter keys

        $this->session->set_userdata('filtros_programacion_predeterminados', $filtros_seleccionados);
        $response = ['status' => 'success', 'message' => 'Filtros predeterminados guardados correctamente.'];
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    // --- AJAX Endpoints for Pestaña 2 (Gestión) ---

    public function ajax_get_tipos_elementos_por_grupo() {
        if (!$this->input->is_ajax_request()) { show_404(); return; }
        $grupo_id = $this->input->post('id_grupo'); // Changed from grupo_id to id_grupo to match view
        $filtros = $this->input->post('filtros'); // Optional: pass other active filters

        if (!$grupo_id) {
            $response = ['status' => 'error', 'message' => 'ID de grupo no proporcionado.'];
        } else {
            $data = $this->programacion_model->get_tipos_elementos_por_grupo($grupo_id, $filtros ?: []);
            $response = ['status' => 'success', 'data' => $data];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ajax_get_ubicaciones_por_tipo_elemento() {
        if (!$this->input->is_ajax_request()) { show_404(); return; }
        $tipo_elemento_id = $this->input->post('id_tipo_elemento_accion'); // Matching model
        $filtros = $this->input->post('filtros');

        if (!$tipo_elemento_id) {
            $response = ['status' => 'error', 'message' => 'ID de tipo de elemento no proporcionado.'];
        } else {
            $data = $this->programacion_model->get_ubicaciones_por_tipo_elemento($tipo_elemento_id, $filtros ?: []);
            $response = ['status' => 'success', 'data' => $data];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ajax_get_tareas_diarias_por_ubicacion() {
        if (!$this->input->is_ajax_request()) { show_404(); return; }
        $ubicacion_id = $this->input->post('id_elemento'); // Matching model parameter name
        $mes = $this->input->post('mes'); // e.g., 1 for January, 12 for December
        $anio = $this->input->post('anio'); // e.g., 2024
        $filtros = $this->input->post('filtros');

        if (!$ubicacion_id || !$mes || !$anio) {
            $response = ['status' => 'error', 'message' => 'ID de ubicación, Mes o Año no proporcionados.'];
        } else {
            // Format mes_anio for the model if it expects YYYY-MM
            $mes_formateado = str_pad($mes, 2, '0', STR_PAD_LEFT); // Ensure two digits for month
            $mes_anio_formateado = $anio . '-' . $mes_formateado;

            $data = $this->programacion_model->get_tareas_diarias_por_ubicacion($ubicacion_id, $mes_anio_formateado, $filtros ?: []);
            $response = ['status' => 'success', 'data' => $data];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ajax_get_datos_accion_modal() {
        if (!$this->input->is_ajax_request()) { show_404(); return; }

        $id_ubicacion = $this->input->post('id_ubicacion');
        $dia = $this->input->post('dia');
        $mes = $this->input->post('mes');
        $anio = $this->input->post('anio');
        $filtros = $this->input->post('filtros') ?: []; // Asegurar que filtros sea un array

        if (!$id_ubicacion || !$dia || !$mes || !$anio) {
            $response = ['status' => 'error', 'message' => 'Datos de ubicación o fecha no proporcionados.'];
        } else {
            $acciones_del_dia = $this->programacion_model->get_acciones_del_dia_ubicacion($id_ubicacion, $dia, $mes, $anio, $filtros);

            // Devolver la lista completa de acciones
            $response = ['status' => 'success', 'data' => $acciones_del_dia];
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ajax_reprogramar_accion() {
        if (!$this->input->is_ajax_request()) { show_404(); return; }
        $id_accion = $this->input->post('id_accion');
        $nueva_fecha = $this->input->post('nueva_fecha');
        $motivo_reprogramacion = $this->input->post('motivo_reprogramacion');
        $id_usuario_modifica = 1; // Placeholder $this->session->userdata('user_id');

        if (!$id_accion || !$nueva_fecha || !$motivo_reprogramacion) {
            $response = ['status' => 'error', 'message' => 'Datos incompletos para reprogramar: ID, nueva fecha o motivo.'];
        } else {
            $response = $this->programacion_model->reprogramar_accion($id_accion, $nueva_fecha, $motivo_reprogramacion, $id_usuario_modifica);
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ajax_reprogramar_acciones_masivo() {
        if (!$this->input->is_ajax_request()) { show_404(); return; }
        $acciones_ids = $this->input->post('acciones_ids'); // Array de IDs
        if (!is_array($acciones_ids)) $acciones_ids = []; // Asegurar que sea un array
        $nueva_fecha = $this->input->post('nueva_fecha');
        $motivo_reprogramacion = $this->input->post('motivo_reprogramacion'); // Corregido
        $id_usuario_modifica = 1; // Placeholder $this->session->userdata('user_id');

        if (empty($acciones_ids) || !$nueva_fecha || !$motivo_reprogramacion) { // Añadido motivo
            $response = ['status' => 'error', 'message' => 'Datos incompletos para reprogramación masiva: IDs, nueva fecha o motivo.'];
        } else {
            $response = $this->programacion_model->reprogramar_acciones_masivo($acciones_ids, $nueva_fecha, $motivo_reprogramacion, $id_usuario_modifica);
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ajax_registrar_acciones_masivo() {
        if (!$this->input->is_ajax_request()) { show_404(); return; }
        $acciones_ids = $this->input->post('acciones_ids');
        if (!is_array($acciones_ids)) $acciones_ids = [];
        $estado_registro = $this->input->post('estado_registro');
        $datos_adicionales_registro = $this->input->post('datos_adicionales_registro'); // Corregido nombre
        $id_usuario_registra = 1; // Placeholder $this->session->userdata('user_id');

        if (empty($acciones_ids) || !$estado_registro) {
            $response = ['status' => 'error', 'message' => 'Datos incompletos para registro masivo: IDs o estado.'];
        } else {
            $response = $this->programacion_model->registrar_acciones_masivo($acciones_ids, $estado_registro, $datos_adicionales_registro ?: '', $id_usuario_registra);
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ajax_asignar_acciones_masivo() { // Nuevo método
        if (!$this->input->is_ajax_request()) { show_404(); return; }
        $acciones_ids = $this->input->post('acciones_ids');
        if (!is_array($acciones_ids)) $acciones_ids = [];
        $asignatario_id = $this->input->post('asignatario_id');
        $id_usuario_modifica = 1; // Placeholder $this->session->userdata('user_id');

        if (empty($acciones_ids) || !$asignatario_id) {
            $response = ['status' => 'error', 'message' => 'Datos incompletos para asignación masiva: IDs o asignatario.'];
        } else {
            $response = $this->programacion_model->asignar_acciones_masivo($acciones_ids, $asignatario_id, $id_usuario_modifica);
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ajax_get_ultimo_registro() {
        if (!$this->input->is_ajax_request()) { show_404(); return; }
        $accion_id = $this->input->post('id_accion');
        if (!$accion_id) {
            $response = ['status' => 'error', 'message' => 'ID de acción no proporcionado.'];
        } else {
            $data = $this->programacion_model->get_ultimo_registro($accion_id);
            if ($data) {
                $response = ['status' => 'success', 'data' => $data];
            } else {
                $response = ['status' => 'success', 'data' => null, 'message' => 'No hay registro previo para esta acción.'];
            }
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ajax_modificar_registro() {
        if (!$this->input->is_ajax_request()) { show_404(); return; }
        $registro_id = $this->input->post('registro_id'); // Corregido
        $nuevos_datos = $this->input->post('nuevos_datos'); // Array
        $id_usuario_modifica = 1; // Placeholder $this->session->userdata('user_id');

        if (!$registro_id || empty($nuevos_datos)) {
            $response = ['status' => 'error', 'message' => 'Datos incompletos para modificar registro: falta ID de registro o nuevos datos.'];
        } else {
            $response = $this->programacion_model->modificar_registro($registro_id, $nuevos_datos, $id_usuario_modifica);
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function ajax_add_comentario_registro() {
        if (!$this->input->is_ajax_request()) { show_404(); return; }
        $registro_id = $this->input->post('registro_id'); // Corregido
        $texto_comentario = $this->input->post('texto_comentario'); // Corregido
        $id_usuario = 1; // Placeholder $this->session->userdata('user_id');

        if (!$registro_id || empty($texto_comentario)) {
            $response = ['status' => 'error', 'message' => 'Datos incompletos: falta ID de registro o texto del comentario.'];
        } else {
            $response = $this->programacion_model->add_comentario_registro($registro_id, $texto_comentario, $id_usuario);
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }
}
?>
