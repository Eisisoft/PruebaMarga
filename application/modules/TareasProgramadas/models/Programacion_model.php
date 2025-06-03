<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Programacion_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        // $this->load->database(); // Uncomment when DB is ready
    }

    // --- Métodos para Selectores y Filtros (Pestaña Gestión) ---

    public function get_establecimientos() {
        // TODO: Reemplazar con consulta real cuando el esquema de BD esté disponible
        // Ejemplo:
        // if ($this->db->conn_id) { // Check if DB connection exists
        //     $this->db->select('id_establecimiento AS id, nombre_establecimiento AS nombre');
        //     $this->db->order_by('nombre_establecimiento', 'ASC');
        //     $query = $this->db->get('sstt_establecimientos'); // Tabla hipotética
        //     return $query->result_array();
        // }
        // return []; // Return empty if no DB

        // Mantener datos de ejemplo temporalmente para no romper la vista
        return [
            ['id' => 1, 'nombre' => 'Establecimiento Alpha (BD placeholder)'],
            ['id' => 2, 'nombre' => 'Establecimiento Beta (BD placeholder)']
        ];
    }

    public function get_edificios($establecimiento_id = null) {
        // TODO: Reemplazar con consulta real
        // Ejemplo:
        // if ($this->db->conn_id) {
        //     $this->db->select('id_edificio AS id, nombre_edificio AS nombre');
        //     if ($establecimiento_id) {
        //         $this->db->where('id_establecimiento', $establecimiento_id);
        //     }
        //     $this->db->order_by('nombre_edificio', 'ASC');
        //     $query = $this->db->get('sstt_edificios'); // Tabla hipotética
        //     return $query->result_array();
        // }
        // return [];
        return [
            ['id' => 10, 'nombre' => 'Edificio A (BD placeholder)'],
            ['id' => 11, 'nombre' => 'Edificio B (BD placeholder)']
        ];
    }

    public function get_bloques($edificio_id = null) {
        // TODO: Reemplazar con consulta real
        return [
            ['id' => 100, 'nombre' => 'Bloque 1 (BD placeholder)'],
            ['id' => 101, 'nombre' => 'Bloque 2 (BD placeholder)']
        ];
    }

    public function get_departamentos($establecimiento_id = null) {
        // TODO: Reemplazar con consulta real
        return [
            ['id' => 1000, 'nombre' => 'Depto. Operaciones (BD placeholder)'],
            ['id' => 1001, 'nombre' => 'Depto. Mantenimiento (BD placeholder)']
        ];
    }

    public function get_planes_filtros() {
        // TODO: Reemplazar con consulta real (ej. sstt_planes_mantenimiento)
        return [
            ['id' => 'plan1', 'nombre' => 'Plan Anual 2024 (BD placeholder)'],
            ['id' => 'plan2', 'nombre' => 'Plan Correctivo (BD placeholder)']
        ];
    }

    public function get_grupos_filtros() {
        // TODO: Reemplazar con consulta real (ej. sstt_grupos_elementos)
        return [
            ['id' => 'grupoA', 'nombre' => 'Climatización (BD placeholder)'],
            ['id' => 'grupoB', 'nombre' => 'Electricidad (BD placeholder)']
        ];
    }

    public function get_subgrupos_filtros($grupo_id = null) {
        // TODO: Reemplazar con consulta real (ej. sstt_subgrupos_elementos)
        return [
            ['id' => 'subA1', 'nombre' => 'UTA (BD placeholder)'],
            ['id' => 'subB1', 'nombre' => 'Cuadros Eléctricos (BD placeholder)']
        ];
    }

    public function get_tipos_elementos_acciones_filtros($subgrupo_id = null) {
        // TODO: Reemplazar con consulta real (ej. sstt_tipos_elementos_acciones)
        return [
            ['id' => 'tea1', 'nombre' => 'Revisión Filtros UTA (BD placeholder)'],
            ['id' => 'tea2', 'nombre' => 'Termografía Cuadro (BD placeholder)']
        ];
    }

    public function get_tipos_tareas_filtros() {
        // TODO: Reemplazar con consulta real (ej. sstt_tipos_tareas)
        return [
            ['id' => 'fija', 'nombre' => 'Fijas (BD placeholder)'],
            ['id' => 'flexible', 'nombre' => 'Flexibles (BD placeholder)']
        ];
    }

    public function get_frecuencias_filtros() {
        // TODO: Reemplazar con consulta real (ej. sstt_frecuencias)
        return [
            ['id' => 'mensual', 'nombre' => 'Mensual (BD placeholder)'],
            ['id' => 'anual', 'nombre' => 'Anual (BD placeholder)']
        ];
    }

    public function get_realizado_por_filtros() {
        // TODO: Reemplazar con consulta real (ej. sstt_tipos_realizacion)
        return [
            ['id' => 'interno', 'nombre' => 'Interno (BD placeholder)'],
            ['id' => 'externo', 'nombre' => 'Empresa Externa (BD placeholder)']
        ];
    }

    // --- Método para la Visualización Global (Pestaña Gestión) ---

    public function get_vista_global_data($filters = array()) {
        // Simulación de grupos y meses para la estructura de datos
        $grupos_simulados = [
            ['id_grupo' => 'grupoA', 'nombre_grupo' => 'Grupo A (Climatización)'],
            ['id_grupo' => 'grupoB', 'nombre_grupo' => 'Grupo B (Electricidad)'],
            ['id_grupo' => 'grupoC', 'nombre_grupo' => 'Grupo C (Fontanería)']
        ];
        $meses_calendario_keys = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        $resultado_vista_global = [];

        // En una implementación real, la consulta SQL principal agruparía por grupo y mes.
        // SELECT
        //   g.id_grupo, g.nombre_grupo,
        //   MONTH(t.fecha_programada_inicio) as mes_numero,
        //   YEAR(t.fecha_programada_inicio) as anio,
        //   COUNT(CASE WHEN t.estado_registro IN ('Correcto', 'Incorrecto') THEN t.id_tarea END) as count_registradas_ok_incorrecto,
        //   COUNT(CASE WHEN t.estado_registro = 'No Realizada' AND t.motivo_no_realizada != 'Caducada' THEN t.id_tarea END) as count_no_realizadas_no_caducadas,
        //   COUNT(CASE WHEN t.estado_registro = 'No Realizada' AND t.motivo_no_realizada = 'Caducada' THEN t.id_tarea END) as count_caducadas,
        //   COUNT(CASE WHEN t.estado_registro IS NULL OR t.estado_registro = 'Pendiente' THEN t.id_tarea END) as count_no_registradas
        // FROM tareas_programadas t
        // JOIN elementos e ON t.id_elemento = e.id_elemento
        // JOIN sstt_grupos_elementos g ON e.id_grupo = g.id_grupo
        // WHERE YEAR(t.fecha_programada_inicio) = $anio_seleccionado (o filtro actual)
        //   AND (filter conditions from $filters)
        // GROUP BY g.id_grupo, g.nombre_grupo, MONTH(t.fecha_programada_inicio), YEAR(t.fecha_programada_inicio)
        // ORDER BY g.nombre_grupo, anio, mes_numero;
        //
        // Luego, se necesitaría otra consulta para el total anual por grupo para 'porcentaje_total_pendiente'.

        foreach ($grupos_simulados as $grupo) {
            $meses_data = [];
            $total_anual_registradas_ok_incorrecto = 0;
            $total_anual_no_realizadas_no_caducadas = 0;
            $total_anual_caducadas = 0;
            $total_anual_no_registradas = 0;

            foreach ($meses_calendario_keys as $index_mes => $mes_key) {
                // Simulación de conteos por mes para este grupo
                // Estos valores cambiarían aleatoriamente o según una lógica más compleja en una simulación real
                $count_registradas_ok_incorrecto_sim = rand(5, 20);
                $count_no_realizadas_no_caducadas_sim = rand(0, 3);
                $count_caducadas_sim = rand(0, 2);
                $count_no_registradas_sim = rand(1, 10);

                $total_anual_registradas_ok_incorrecto += $count_registradas_ok_incorrecto_sim;
                $total_anual_no_realizadas_no_caducadas += $count_no_realizadas_no_caducadas_sim;
                $total_anual_caducadas += $count_caducadas_sim;
                $total_anual_no_registradas += $count_no_registradas_sim;

                $meses_data[$mes_key] = $this->calculate_porcentaje_pendientes(
                    $count_registradas_ok_incorrecto_sim,
                    $count_no_realizadas_no_caducadas_sim,
                    $count_caducadas_sim,
                    $count_no_registradas_sim
                );
            }

            $porcentaje_total_pendiente = $this->calculate_porcentaje_pendientes(
                $total_anual_registradas_ok_incorrecto,
                $total_anual_no_realizadas_no_caducadas,
                $total_anual_caducadas,
                $total_anual_no_registradas
            );

            $resultado_vista_global[] = [
                'nombre_grupo' => $grupo['nombre_grupo'] . " (Sim)",
                'id_grupo' => $grupo['id_grupo'],
                'porcentaje_total_pendiente' => $porcentaje_total_pendiente,
                'meses' => $meses_data
            ];
        }
        return $resultado_vista_global;
    }

    // --- Métodos para Pestaña "Programación Actual" (Calendario y Mapa de Calor) ---

    public function get_datos_programacion_actual($filtros = []) {
        // (Este método ya fue refactorizado en la tarea anterior, se mantiene como está)
        $eventos_calendario_ejemplo = [
            ['id' => 1, 'title' => 'Revisión Bomba X (Ej.)', 'start' => '2024-03-10', 'estado' => 'pendiente', 'color' => '#3a87ad', 'allDay' => true],
            ['id' => 2, 'title' => 'Inspección Enfriadora (Ej.)', 'start' => '2024-03-12', 'estado' => 'completada', 'color' => '#468847', 'allDay' => true],
            ['id' => 3, 'title' => 'Calibración Sensor (Ej.)', 'start' => '2024-03-15', 'end' => '2024-03-16', 'estado' => 'pendiente', 'color' => '#3a87ad'],
        ];
        $datos_mapa_calor_ejemplo = [
            '2024-03-10' => ['total_tareas' => 1, 'estados_cuenta' => ['pendiente' => 1]],
            '2024-03-12' => ['total_tareas' => 1, 'estados_cuenta' => ['completada' => 1]],
            '2024-03-15' => ['total_tareas' => 1, 'estados_cuenta' => ['pendiente' => 1]],
            '2024-03-16' => ['total_tareas' => 1, 'estados_cuenta' => ['pendiente' => 1]],
        ];
         $eventos_filtrados = $eventos_calendario_ejemplo;
        if (!empty($filtros)) {
            $eventos_filtrados = array_filter($eventos_calendario_ejemplo, function($tarea) use ($filtros) {
                $passes_filter = true;
                if (isset($filtros['estado']) && isset($tarea['estado']) && $tarea['estado'] != $filtros['estado']) {
                    $passes_filter = false;
                }
                return $passes_filter;
            });
        }
        return [
            'eventos_calendario' => $eventos_filtrados,
            'datos_mapa_calor' => $datos_mapa_calor_ejemplo
        ];
    }

    // --- Método de Cálculo de Porcentaje (Existente) ---

    public function calculate_porcentaje_pendientes($count_registradas_ok_incorrecto, $count_no_realizadas_no_caducadas, $count_caducadas, $count_no_registradas) {
        $registradas_validas = $count_registradas_ok_incorrecto + $count_no_realizadas_no_caducadas;
        $total_acciones = $registradas_validas + $count_caducadas + $count_no_registradas;
        if ($total_acciones == 0) return 0.00;
        $pendientes = $count_no_registradas + $count_caducadas;
        return round(($pendientes / $total_acciones) * 100, 2);
    }

    // --- Métodos para el Modal de Acciones ---

    public function get_acciones_del_dia_ubicacion($id_ubicacion, $dia, $mes, $anio, $filtros = []) {
        // TODO: Implementar consulta SQL real.
        // SELECT id_tarea AS id_accion, nombre_tarea AS nombre_accion, frecuencia, margen, plan_mantenimiento, departamento_responsable
        // FROM tareas_programadas
        // WHERE id_elemento = $id_ubicacion
        //   AND DAY(fecha_programada_inicio) = $dia
        //   AND MONTH(fecha_programada_inicio) = $mes
        //   AND YEAR(fecha_programada_inicio) = $anio
        //   AND (otras condiciones de $filtros si aplican a este nivel)

        // Ejemplo de datos de retorno:
        $acciones_ejemplo = [
            [
                'id_accion' => $id_ubicacion.'-'.$anio.$mes.$dia.'-A001',
                'nombre_accion' => 'Verificar Nivel Aceite Compresor A (Ubic: '.$id_ubicacion.')',
                'frecuencia' => 'Semanal',
                'margen' => '3 días',
                'plan' => 'MP-'.($anio),
                'departamento_responsable' => 'Mantenimiento Mecánico',
                'estado_actual' => 'Pendiente' // Podría venir de la tarea
            ],
            [
                'id_accion' => $id_ubicacion.'-'.$anio.$mes.$dia.'-A002',
                'nombre_accion' => 'Limpieza Filtros UTA-03 (Ubic: '.$id_ubicacion.')',
                'frecuencia' => 'Mensual',
                'margen' => '5 días',
                'plan' => 'MP-'.($anio),
                'departamento_responsable' => 'Operaciones HVAC',
                'estado_actual' => 'Completada'
            ]
        ];
        // Simular que a veces hay menos o ninguna tarea, o varias
        $num_acciones_a_devolver = rand(0,3);
        if ($num_acciones_a_devolver == 0) return [];
        return array_slice($acciones_ejemplo, 0, $num_acciones_a_devolver);
    }


    // --- Nuevos Métodos para Drill-down y Acciones del Modal (Pestaña Gestión) ---

    /**
     * Obtiene los tipos de elementos (y sus KPIs) para un grupo específico, aplicando filtros.
     */
    public function get_tipos_elementos_por_grupo($id_grupo, $filtros = []) {
        // TODO: Implementar consulta SQL
        // SELECT tea.id_tipo_elemento_accion, tea.nombre_tipo_elemento, COUNT(t.id_tarea) as total_tareas, ...
        // FROM sstt_tipos_elementos_acciones tea
        // JOIN elementos e ON tea.id_tipo_elemento = e.id_tipo_elemento (o similar)
        // JOIN tareas_programadas t ON e.id_elemento = t.id_elemento
        // WHERE e.id_grupo = $id_grupo AND (condiciones de $filtros)
        // GROUP BY tea.id_tipo_elemento_accion, tea.nombre_tipo_elemento

        // Ejemplo de datos de retorno:
        $meses_ejemplo = [];
        $meses_calendario_keys = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        foreach ($meses_calendario_keys as $mes) {
            $meses_ejemplo[$mes] = rand(0, 100); // Porcentaje placeholder
        }

        if ($id_grupo == 'grupoA') { // Simular datos diferentes por grupo
            return [
                ['id_tipo_elemento_accion' => 'tea1_grupoA', 'nombre_tipo_elemento' => 'UTA Revisión Filtros (A)', 'total_tareas_tipo' => rand(10,50), 'meses_porcentajes' => $meses_ejemplo],
                ['id_tipo_elemento_accion' => 'tea2_grupoA', 'nombre_tipo_elemento' => 'Enfriadora Nivel Aceite (A)', 'total_tareas_tipo' => rand(5,30), 'meses_porcentajes' => $meses_ejemplo],
            ];
        } elseif ($id_grupo == 'grupoB') {
             return [
                ['id_tipo_elemento_accion' => 'tea3_grupoB', 'nombre_tipo_elemento' => 'Cuadro Eléctrico Termografía (B)', 'total_tareas_tipo' => rand(15,60), 'meses_porcentajes' => $meses_ejemplo],
            ];
        }
        // Default empty or specific message
        return [];
    }

    /**
     * Obtiene las ubicaciones/elementos específicos para un tipo de elemento, aplicando filtros.
     */
    public function get_ubicaciones_por_tipo_elemento($id_tipo_elemento_accion, $filtros = []) {
        // TODO: Implementar consulta SQL
        // SELECT e.id_elemento, e.nombre_elemento, e.ubicacion_tecnica, COUNT(t.id_tarea) as total_tareas_elemento, ...
        // FROM elementos e
        // JOIN tareas_programadas t ON e.id_elemento = t.id_elemento
        // WHERE t.id_tipo_elemento_accion = $id_tipo_elemento_accion AND (condiciones de $filtros)
        // GROUP BY e.id_elemento, e.nombre_elemento, e.ubicacion_tecnica

        // Ejemplo de datos de retorno:
        if ($id_tipo_elemento_accion == 'tea1_grupoA') { // Simular datos para un tipo de elemento específico
            return [
                ['id_elemento' => 'ELEM001', 'nombre_elemento' => 'UTA-01 Sótano', 'ubicacion_tecnica' => 'SSTT.EDF-A.SOT.CLI.UTA01', 'total_tareas_elemento' => rand(5,15) ],
                ['id_elemento' => 'ELEM002', 'nombre_elemento' => 'UTA-02 Planta 1', 'ubicacion_tecnica' => 'SSTT.EDF-A.P01.CLI.UTA02', 'total_tareas_elemento' => rand(3,10) ],
            ];
        } elseif ($id_tipo_elemento_accion == 'tea2_grupoA') {
            return [
                ['id_elemento' => 'ELEM003', 'nombre_elemento' => 'Enfriadora E01 Cubierta', 'ubicacion_tecnica' => 'SSTT.EDF-A.CUB.CLI.ENF01', 'total_tareas_elemento' => rand(8,20)],
            ];
        } elseif ($id_tipo_elemento_accion == 'tea3_grupoB') {
             return [
                ['id_elemento' => 'ELEM004', 'nombre_elemento' => 'Cuadro General Baja Tensión', 'ubicacion_tecnica' => 'SSTT.EDF-B.BT.ELE.CGBT01', 'total_tareas_elemento' => rand(10,25)],
                ['id_elemento' => 'ELEM005', 'nombre_elemento' => 'Cuadro Secundario Planta 2', 'ubicacion_tecnica' => 'SSTT.EDF-B.P02.ELE.CS01', 'total_tareas_elemento' => rand(4,12)],
            ];
        }
        return [];
    }

    /**
     * Obtiene las tareas diarias para una ubicación/elemento en un mes específico, aplicando filtros.
     */
    public function get_tareas_diarias_por_ubicacion($id_elemento, $mes_anio, $filtros = []) {
        // TODO: Implementar consulta SQL
        // $mes_anio debe ser algo como 'YYYY-MM'
        // SELECT t.id_tarea, t.descripcion, t.fecha_programada_inicio, t.estado_registro, ...
        // FROM tareas_programadas t
        // WHERE t.id_elemento = $id_elemento
        //   AND DATE_FORMAT(t.fecha_programada_inicio, '%Y-%m') = $mes_anio
        //   AND (condiciones de $filtros)
        // GROUP BY DATE_FORMAT(t.fecha_programada_inicio, '%e') // Group by day of month
        // ORDER BY dia;
        //
        // The result would be an array like: [['dia' => 1, 'num_tareas' => 2, 'pendientes' => 1], ['dia' => 5, 'num_tareas' => 3, 'pendientes' => 0]...]

        // Example data generation:
        $dias_con_tareas = [];
        $total_tareas_mes_simulado = 0;
        $hay_pendientes_mes_simulado = false;

        // $mes_anio is expected as 'YYYY-MM' by current model comment, but controller sends mes, anio separately.
        // Let's assume $mes_anio is passed correctly or adapted by controller.
        // For this example, we'll use $id_elemento to vary results a bit.
        // And parse $mes_anio if it comes as 'YYYY-MM'
        if (strpos($mes_anio, '-') !== false) {
            list($anio, $mes) = explode('-', $mes_anio);
            $num_dias_mes = cal_days_in_month(CAL_GREGORIAN, (int)$mes, (int)$anio);
        } else { // Fallback if $mes_anio is not YYYY-MM, maybe just mes. This part needs to be robust.
            $num_dias_mes = 30; // Default
            $mes = (int)$mes_anio; // Assuming $mes_anio is just month number if not YYYY-MM
        }


        for ($i = 0; $i < rand(5, 15); $i++) { // Simulate some days with tasks
            $dia = rand(1, $num_dias_mes);
            $num_tareas_dia = rand(1, 5);
            $dias_con_tareas[$dia] = isset($dias_con_tareas[$dia]) ? $dias_con_tareas[$dia] + $num_tareas_dia : $num_tareas_dia;
            $total_tareas_mes_simulado += $num_tareas_dia;
            if (rand(0,1) == 1) { // Simulate some pending
                $hay_pendientes_mes_simulado = true;
            }
        }
        ksort($dias_con_tareas); // Sort by day

        return [
            'dias_tareas' => $dias_con_tareas, // e.g. [1 => 2, 5 => 3] (Day => Num Tareas)
            'hay_pendientes_mes' => $hay_pendientes_mes_simulado,
            'total_tareas_mes' => $total_tareas_mes_simulado,
            'mes_procesado' => $mes, // For debugging or display
            'anio_procesado' => isset($anio) ? $anio : date('Y') // For debugging or display
        ];
    }

    public function reprogramar_accion($id_accion, $nueva_fecha, $motivo, $id_usuario_modifica) {
        // TODO: Implementar lógica de BD para reprogramar la tarea con id $id_accion
        // UPDATE tareas_programadas SET fecha_programada_inicio = $nueva_fecha, fecha_modificacion = NOW(), id_usuario_modifica = $id_usuario_modifica, motivo_reprogramacion = $motivo WHERE id_tarea = $id_accion;
        // INSERT INTO historial_reprogramaciones (id_tarea, fecha_anterior, fecha_nueva, motivo, id_usuario_modifica, fecha_modificacion) VALUES (...)

        // Simular éxito
        log_message('debug', "Simulando reprogramación para accion ID: $id_accion a fecha: $nueva_fecha, motivo: $motivo, por usuario: $id_usuario_modifica");
        return ['status' => 'success', 'message' => 'Acción reprogramada con éxito (simulado).'];
    }

    public function reprogramar_acciones_masivo($acciones_ids, $nueva_fecha, $motivo, $id_usuario_modifica) {
        // TODO: Implementar lógica de BD real
        log_message('debug', count($acciones_ids) . " acciones (IDs: " . implode(', ', $acciones_ids) . ") reprogramadas masivamente a $nueva_fecha por usuario $id_usuario_modifica debido a: $motivo (simulado).");
        return ['status' => 'success', 'message' => count($acciones_ids) . ' acciones reprogramadas con éxito (simulado).'];
    }

    public function registrar_acciones_masivo($acciones_ids, $estado, $datos_registro, $id_usuario_registra) {
        // TODO: Implementar lógica de BD real
        log_message('debug', count($acciones_ids) . " acciones (IDs: " . implode(', ', $acciones_ids) . ") registradas como '$estado' por usuario $id_usuario_registra con datos: '$datos_registro' (simulado).");
        return ['status' => 'success', 'message' => count($acciones_ids) . ' acciones registradas como ' . $estado . ' (simulado).'];
    }

    // Se modifica asignar_accion para que sea asignar_acciones_masivo
    public function asignar_acciones_masivo($acciones_ids, $asignatario_id, $id_usuario_modifica) {
        // TODO: Implementar lógica de BD real
        log_message('debug', count($acciones_ids) . " acciones (IDs: " . implode(', ', $acciones_ids) . ") asignadas a '$asignatario_id' por usuario $id_usuario_modifica (simulado).");
        return ['status' => 'success', 'message' => count($acciones_ids) . ' acciones asignadas a ' . $asignatario_id . ' (simulado).'];
    }

    public function get_ultimo_registro($accion_id) {
        // TODO: Implementar consulta SQL real
        // SELECT id_registro, fecha_registro, realizado_por_nombre_usuario, estado_registrado, valores_medidos_texto, observaciones
        // FROM registros_tareas rt
        // LEFT JOIN usuarios u ON rt.id_usuario_realiza = u.id_usuario
        // WHERE id_tarea = $accion_id
        // ORDER BY fecha_registro DESC LIMIT 1;
        // Y luego otra consulta para los comentarios de ESE registro.

        // Simulación de datos
        if (strpos($accion_id, 'A001') !== false) { // Simular que solo A001 tiene registro
            $fecha_registro_base = strtotime("-".rand(1,5)." days");
            return [
                'id_registro' => 'REG_'.rand(1000,9999),
                'accion_id' => $accion_id,
                'fecha_registro' => date('Y-m-d H:i:s', $fecha_registro_base),
                'realizado_por' => 'Juan Perez (Simulado)',
                'estado_registrado' => 'Realizado Correctamente',
                'valores_medidos' => 'Presión: 15PSI, Temp: 22°C',
                'observaciones' => 'Funcionamiento normal verificado. Sin anomalías detectadas.',
                'comentarios' => [
                    ['id_comentario' => 'COM001', 'usuario' => 'Supervisor A', 'fecha_comentario' => date('Y-m-d H:i:s', strtotime("+1 hour", $fecha_registro_base)), 'texto' => 'Buen trabajo.'],
                    ['id_comentario' => 'COM002', 'usuario' => 'Juan Perez (Simulado)', 'fecha_comentario' => date('Y-m-d H:i:s', strtotime("+2 hours", $fecha_registro_base)), 'texto' => 'Gracias!'],
                ],
                'archivos_adjuntos' => [
                    ['id_archivo' => 'ARC001', 'nombre_archivo' => 'foto_evidencia_compresor.jpg', 'url_archivo' => '#fakelink_foto1', 'fecha_subida' => date('Y-m-d H:i:s', $fecha_registro_base)],
                    ['id_archivo' => 'ARC002', 'nombre_archivo' => 'lecturas_presion.pdf', 'url_archivo' => '#fakelink_pdf1', 'fecha_subida' => date('Y-m-d H:i:s', strtotime("+5 minutes", $fecha_registro_base))]
                ],
                'ordenes_trabajo_vinculadas' => [
                    ['id_ot' => 'OT-'.($anio-1).'-001', 'descripcion_ot' => 'Instalación inicial Compresor A', 'estado_ot' => 'Cerrada'],
                    ['id_ot' => 'OT-'.$anio.'-'.rand(10,50), 'descripcion_ot' => 'Revisión por ruido anómalo', 'estado_ot' => 'En progreso']
                ]
            ];
        } elseif (strpos($accion_id, 'A002') !== false) { // Simular otro registro para otra acción
             $fecha_registro_base = strtotime("-".rand(2,7)." days");
            return [
                'id_registro' => 'REG_'.rand(1000,9999),
                'accion_id' => $accion_id,
                'fecha_registro' => date('Y-m-d H:i:s', $fecha_registro_base),
                'realizado_por' => 'Ana Gómez (Simulado)',
                'estado_registrado' => 'Realizado con Incidencias',
                'valores_medidos' => 'Filtro Primario: Sucio, Filtro Secundario: OK',
                'observaciones' => 'Se requiere cambio de filtro primario. Se ha notificado para generar OT.',
                'comentarios' => [
                    ['id_comentario' => 'COM003', 'usuario' => 'Ana Gómez (Simulado)', 'fecha_comentario' => date('Y-m-d H:i:s', strtotime("+10 minutes", $fecha_registro_base)), 'texto' => 'Necesario generar OT para cambio de filtro.'],
                ],
                'archivos_adjuntos' => [], // Sin archivos para este ejemplo
                'ordenes_trabajo_vinculadas' => [
                     ['id_ot' => 'OT-'.$anio.'-'.rand(51,99), 'descripcion_ot' => 'Cambio filtro primario UTA-03', 'estado_ot' => 'Abierta']
                ]
            ];
        }
        return null; // Simular que no hay registro para otras acciones
    }

    public function modificar_registro($registro_id, $nuevos_datos, $id_usuario_modifica) {
        // TODO: Implementar lógica de BD real
        // UPDATE registros_tareas SET estado_registrado = $nuevos_datos['estado_registrado'], valores_medidos_texto = $nuevos_datos['valores_medidos'], observaciones = $nuevos_datos['observaciones'], ...
        // WHERE id_registro = $registro_id;
        log_message('debug', "Modificando registro ID $registro_id con datos: " . json_encode($nuevos_datos) . " por usuario $id_usuario_modifica (simulado).");
        return ['status' => 'success', 'message' => 'Registro modificado con éxito (simulado).'];
    }

    public function get_comentarios_registro($id_registro_tarea) {
        // Este método podría no ser necesario si get_ultimo_registro ya devuelve los comentarios.
        // O podría ser para cargar comentarios de forma independiente si son muchos.
        // TODO: Implementar consulta SQL
        // SELECT * FROM comentarios_registros WHERE id_registro_tarea = $id_registro_tarea ORDER BY fecha_comentario ASC;
        log_message('debug', "get_comentarios_registro llamado para $id_registro_tarea, pero los comentarios ya se incluyen en get_ultimo_registro.");
        return []; // Placeholder, ya que los comentarios se incluyen en get_ultimo_registro
    }

    public function add_comentario_registro($registro_id, $texto_comentario, $id_usuario) {
        // TODO: Implementar lógica de BD real
        // INSERT INTO comentarios_registros (id_registro_tarea, texto_comentario, id_usuario, fecha_comentario) VALUES (...);
        $nuevo_comentario_ejemplo = [
            'id_comentario' => 'COM' . rand(100,999),
            'usuario' => 'Usuario Sim ' . $id_usuario, // Simular nombre de usuario
            'fecha_comentario' => date('Y-m-d H:i:s'),
            'texto' => $texto_comentario
        ];
        log_message('debug', "Añadiendo comentario a registro ID $registro_id: '$texto_comentario' por usuario $id_usuario (simulado).");
        return ['status' => 'success', 'message' => 'Comentario añadido con éxito (simulado).', 'nuevo_comentario' => $nuevo_comentario_ejemplo];
    }

    public function get_archivos_registro($id_registro_tarea) {
        // TODO: Implementar consulta SQL
        // SELECT * FROM archivos_adjuntos_registros WHERE id_registro_tarea = $id_registro_tarea;
        return []; // Placeholder
    }

    public function get_ordenes_trabajo_accion($id_tarea) {
        // TODO: Implementar consulta SQL
        // SELECT * FROM ordenes_trabajo WHERE id_tarea_origen = $id_tarea;
        return []; // Placeholder
    }
}
?>
