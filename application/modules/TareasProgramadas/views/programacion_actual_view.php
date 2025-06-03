<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title : 'Programación Actual'; ?></title>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; color: #333; }
        .container { padding: 15px; }
        #calendario { max-width: 1100px; margin: 25px auto; background-color: #fff; padding: 15px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }

        .nav-tabs { background-color: #333; padding: 10px 15px; margin-bottom: 0; }
        .nav-tabs a { padding: 10px 15px; text-decoration: none; border: 1px solid #333; margin-right: 5px; color: #fff; border-radius: 4px 4px 0 0; }
        .nav-tabs a.active { background-color: #007bff; color: white; border-color: #007bff;}
        .nav-tabs a:hover { background-color: #555; }
        hr.tab-divider { display: none; } /* Hide default hr if nav is styled as a bar */

        h1 { color: #333; margin-top: 20px; }
        h2 { color: #555; margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 5px;}

        /* Heatmap Styles */
        #mapa-calor-container { margin: 25px auto; padding: 20px; background-color: #fff; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 1100px; }
        .heatmap-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .heatmap-table th, .heatmap-table td { border: 1px solid #ddd; padding: 5px; text-align: center; height: 70px; vertical-align: top; }
        .heatmap-table th { background-color: #f0f0f0; font-size: 0.9em; }
        .heatmap-table td .day-number { font-size: 0.8em; color: #777; display: block; text-align: left; margin-bottom:3px;}
        .heatmap-table td .task-count { font-size: 1em; font-weight: bold; display: block; margin-top: 5px; }
        .heatmap-table td .task-details { font-size: 0.75em; color: #555; display: block; margin-top: 3px; }


        /* Default cell, can be overridden by specific status */
        .heatmap-cell { background-color: #f9f9f9; }
        .heatmap-none { background-color: #efefef; } /* No tasks */
        .heatmap-low { background-color: #d4edda; } /* Completadas predominan o pocas tareas */
        .heatmap-medium { background-color: #fff3cd; } /* Pendientes moderadas */
        .heatmap-high { background-color: #f8d7da; } /* Muchas pendientes o retrasadas */
        .heatmap-critical { background-color: #dc3545; color: white; } /* Mayoría retrasadas/caducadas */

        .heatmap-legend { margin-top: 15px; }
        .legend-item { display: inline-block; margin-right: 15px; font-size: 0.9em;}
        .legend-color { width: 15px; height: 15px; display: inline-block; vertical-align: middle; margin-right: 5px; border: 1px solid #ccc;}

        /* Temporary data dump styling */
        .debug-dump { background-color: #222; color: #eee; padding: 15px; margin: 15px; border-radius: 5px; font-size: 0.9em; overflow-x: auto; }
        .debug-dump pre { white-space: pre-wrap; word-wrap: break-word; }
    </style>
</head>
<body>
    <nav class="nav-tabs">
        <a href="<?php echo site_url('TareasProgramadas/Programacion/view_tab/actual'); ?>" class="<?php echo (!isset($current_tab) || $current_tab == 'actual') ? 'active' : ''; ?>">Programación Actual</a>
        <a href="<?php echo site_url('TareasProgramadas/Programacion/view_tab/gestion'); ?>" class="<?php echo (isset($current_tab) && $current_tab == 'gestion') ? 'active' : ''; ?>">Gestión de Programación</a>
    </nav>

    <div class="container">
        <h1><?php echo isset($title) ? $title : 'Programación Actual'; ?></h1>

        <?php
        /*
            // Temporary data dump for verification
            if (isset($eventos_calendario) || isset($datos_mapa_calor)) {
                echo '<div class="debug-dump"><pre>';
                echo '<strong>Eventos Calendario:</strong><br>';
                print_r($eventos_calendario ?? []);
                echo '<br><br><strong>Datos Mapa Calor:</strong><br>';
                print_r($datos_mapa_calor ?? []);
                echo '</pre></div>';
            }
        */
        ?>

        <div id="calendario">
            <!-- FullCalendar will be initialized here -->
        </div>

        <div id="mapa-calor-container">
            <h2>Mapa de Calor (Marzo 2024 - Ejemplo)</h2>

            <?php
            // --- Heatmap Generation (Basic Example for March 2024) ---
            $year = 2024;
            $month = 3;
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $first_day_of_month = date('N', strtotime("$year-$month-01")); // 1 (Mon) to 7 (Sun)

            $heatmap_data = isset($datos_mapa_calor) ? $datos_mapa_calor : [];

            // Define thresholds and corresponding CSS classes
            // This is a simple logic, can be greatly expanded
            function get_heatmap_class($day_data) {
                if (!$day_data || $day_data['total_tareas'] == 0) return 'heatmap-none';

                $pendientes = $day_data['estados_cuenta']['pendiente'] ?? 0;
                $completadas = $day_data['estados_cuenta']['completada'] ?? 0;
                $retrasadas = $day_data['estados_cuenta']['retrasada'] ?? 0;
                $caducadas = $day_data['estados_cuenta']['caducada'] ?? 0;

                if ($retrasadas > 0 || $caducadas > 0) {
                     if (($retrasadas + $caducadas) >= $day_data['total_tareas'] / 2) return 'heatmap-critical'; // Half or more are critical
                     return 'heatmap-high'; // Some critical tasks
                }
                if ($pendientes > 0) {
                    if ($pendientes >= $day_data['total_tareas'] / 2) return 'heatmap-medium'; // Half or more are pending
                    return 'heatmap-low'; // Some pending
                }
                if ($completadas == $day_data['total_tareas']) return 'heatmap-low'; // All completed

                return 'heatmap-none'; // Default if no specific condition met
            }
            ?>

            <table class="heatmap-table">
                <thead>
                    <tr>
                        <th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th><th>Dom</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <?php
                    // Add empty cells for days before the first of the month
                    for ($i = 1; $i < $first_day_of_month; $i++): ?>
                        <td></td>
                    <?php endfor; ?>

                    <?php for ($day = 1; $day <= $days_in_month; $day++): ?>
                        <?php
                        $current_date_str = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $day_data = $heatmap_data[$current_date_str] ?? ['total_tareas' => 0, 'estados_cuenta' => []];
                        $cell_class = get_heatmap_class($day_data);
                        $task_details_str = "";
                        if($day_data['total_tareas'] > 0) {
                            foreach($day_data['estados_cuenta'] as $estado => $count) {
                                if ($count > 0) $task_details_str .= htmlspecialchars(ucfirst($estado)) . ": $count<br>";
                            }
                        }
                        ?>
                        <td class="<?php echo $cell_class; ?>">
                            <span class="day-number"><?php echo $day; ?></span>
                            <?php if ($day_data['total_tareas'] > 0): ?>
                                <span class="task-count"><?php echo $day_data['total_tareas']; ?> Tarea(s)</span>
                                <span class="task-details"><?php echo rtrim($task_details_str, "<br>"); ?></span>
                            <?php endif; ?>
                        </td>
                        <?php if (($day + $first_day_of_month - 1) % 7 == 0 && $day != $days_in_month): ?>
                            </tr><tr>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php
                    // Add empty cells for days after the last of the month to complete the row
                    $remaining_cells = 7 - (($days_in_month + $first_day_of_month - 1) % 7);
                    if ($remaining_cells < 7) {
                        for ($i = 0; $i < $remaining_cells; $i++): ?>
                            <td></td>
                        <?php endfor;
                    }
                    ?>
                    </tr>
                </tbody>
            </table>
            <div class="heatmap-legend">
                <span class="legend-item"><span class="legend-color" style="background-color: #efefef;"></span> Sin Tareas</span>
                <span class="legend-item"><span class="legend-color" style="background-color: #d4edda;"></span> Carga Baja / Completadas</span>
                <span class="legend-item"><span class="legend-color" style="background-color: #fff3cd;"></span> Carga Media (Pendientes)</span>
                <span class="legend-item"><span class="legend-color" style="background-color: #f8d7da;"></span> Carga Alta (Pendientes/Retrasadas)</span>
                <span class="legend-item"><span class="legend-color" style="background-color: #dc3545; color:white;"></span> Carga Crítica (Retrasadas/Caducadas)</span>
            </div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendario');
        var eventsData = <?php echo isset($eventos_calendario) ? json_encode($eventos_calendario) : '[]'; ?>;

        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          locale: 'es',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
          },
          events: eventsData,
          editable: true,
          selectable: true,
          eventDidMount: function(info) {
            // Example: Add a tooltip with more info (requires a tooltip library like Tippy.js or custom CSS)
            // For now, just a simple title attribute
            info.el.setAttribute('title', `Estado: ${info.event.extendedProps.estado || 'N/A'}\nTipo: ${info.event.extendedProps.tipo || 'N/A'}`);
          }
          // eventClick: function(info) {
          //   alert('Evento: ' + info.event.title + '\\nID: ' + info.event.id + '\\nEstado: ' + (info.event.extendedProps.estado || 'N/A'));
          // },
        });
        calendar.render();
      });
    </script>
</body>
</html>
