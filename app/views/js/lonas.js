$(document).ready(function() {

    // Inicializar Date Range Picker

    $('#daterange').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Limpiar',
            applyLabel: 'Aplicar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Rango Personalizado',
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            firstDay: 1,
            format: 'DD/MM/YYYY'
        },
        ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });
    
    // Eventos del Date Range Picker

    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        tabla.ajax.reload();
    });
    
    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        tabla.ajax.reload();
    });
    
    // Inicializar DataTable

    var tabla = $('#tablaLona').DataTable({
        "ajax": {
            "url": "app/modules/lonas/listar.php",
            "type": "POST",
            "data": function(d) {

                // Revisar si hay algun filtro activo

                var isAnyFilterActive = $('#daterange').val() !== '' || $('#estado_filter').val() !== '';
                
                // Agregar el parámetro que oculta los 'Entregados' por defecto si no hay filtros activos

                d.ocultar_entregados = !isAnyFilterActive;

                // Agregar parámetros adicionales de filtro

                d.fecha_inicio = '';
                d.fecha_fin = '';
                d.estado = $('#estado_filter').val();
                
                // Procesar el rango de fechas

                if ($('#daterange').val() !== '') {
                    var fechas = $('#daterange').val().split(' - ');
                    if (fechas.length === 2) {
                        d.fecha_inicio = moment(fechas[0], 'DD/MM/YYYY').format('YYYY-MM-DD');
                        d.fecha_fin = moment(fechas[1], 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }
                }
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "lona" },
            { "data": "diseno" },
            { "data": "tamano" },
            { "data": "estado" },
            { 
                "data": "fecha",
                "render": function(data, type, row) {
                    if (type === 'display' || type === 'type') {
                        return moment(data).format('DD/MM/YYYY');
                    }
                    return data;
                }
            },
            {
                "data": "id",
                "orderable": false,
                "render": function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-warning" onclick="editarLona(${data})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="eliminarLona(${data})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        "processing": true,
        "serverSide": false,
        "pageLength": 25,
        "order": [[0, "desc"]],
        "dom": 'Bfrtip', // B = Buttons, f = filter, r = processing, t = table, i = info, p = pagination
        "buttons": [
            {
                "extend": 'excelHtml5',
                "text": '<i class="fas fa-file-excel"></i> Exportar a Excel',
                "exportOptions": {
                    "columns": ':not(:last-child)' // Excluye la última columna
                }
            },
            {
                "extend": 'print',
                "text": '<i class="fas fa-print"></i> Imprimir',
                "exportOptions": {
                    "columns": ':not(:last-child)' // Excluye la última columna
                }
            }
        ]
    });

    // Focus el input de búsqueda
    
    tabla.on('init.dt', function () {
        $('#tablaLona_filter input').focus();
    });
    
    // Limpiar filtros

    $('#btn_limpiar').click(function() {
        $('#daterange').val('');
        $('#estado_filter').val('');
        tabla.ajax.reload();
    });
    
    // Filtrar automáticamente cuando cambien los selects

    $('#estado_filter').change(function() {
        tabla.ajax.reload();
    });
    
    // Crear y editar registro

    $('#formLona').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var url = $('#lonaId').val() ? 'app/modules/lonas/editar.php' : 'app/modules/lonas/crear.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#modalLona').modal('hide');
                    tabla.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#formLona')[0].reset();
                    $('#lonaId').val('');
                    $('#tituloModal').text('Nueva Lona');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar'
                });
            }
        });
    });

    // Limpiar formulario al cerrar modal

    $('#modalLona').on('hidden.bs.modal', function() {
        $('#formLona')[0].reset();
        $('#lonaId').val('');
        $('#tituloModal').text('Nueva Lona');
    });
});

// Obtener datos para editar

function editarLona(id) {
    $.ajax({
        url: 'app/modules/lonas/obtener.php',
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if(data.success) {
                var lona = data.data;
                $('#lonaId').val(lona.id);
                $('#lona').val(lona.lona);
                $('#diseno').val(lona.diseno);
                $('#tamano').val(lona.tamano);
                $('#estado').val(lona.estado);
                $('#fecha').val(lona.fecha);
                $('#tituloModal').text('Editar Lona');
                $('#modalLona').modal('show');
            }
        }
    });
}

// Eliminar registro

function eliminarLona(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'app/modules/lonas/eliminar.php',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#tablaLona').DataTable().ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                }
            });
        }
    });
}
