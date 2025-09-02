$(document).ready(function() {

    // Configurar moment.js en español

    moment.locale('es');
    
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
    });
    
    // Inicializar DataTable

    var tabla = $('#tablaPlacas').DataTable({
        "processing": true,
        "serverSide": false,
        "pageLength": 25,
        "order": [[0, "desc"]],
        "ajax": {
            "url": "app/ajax/placas/listar.php",
            "type": "POST",
            "data": function(d) {

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
            { "data": "placa" },
            { "data": "diseno" },
            { "data": "cantidad" },
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
                "render": function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-warning" onclick="editarPlaca(${data})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="eliminarPlaca(${data})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
    });
    
    // Limpiar parámetros de filtros

    $('#btn_limpiar').click(function() {
        $('#daterange').val('');
        $('#estado_filter').val('');
        tabla.ajax.reload();
    });
    
    // Filtrar automáticamente al cambiar los selects

    $('#estado_filter').change(function() {
        tabla.ajax.reload();
    });
    
    // Crear y editar registro

    $('#formPlaca').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var url = $('#placaId').val() ? 'app/ajax/placas/editar.php' : 'app/ajax/placas/crear.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#modalPlaca').modal('hide');
                    tabla.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#formPlaca')[0].reset();
                    $('#placaId').val('');
                    $('#tituloModal').text('Nueva Placa');
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

    $('#modalPlaca').on('hidden.bs.modal', function() {
        $('#formPlaca')[0].reset();
        $('#placaId').val('');
        $('#tituloModal').text('Nueva Placa');
    });

});

// Obtener datos para editar

function editarPlaca(id) {
    $.ajax({
        url: 'app/ajax/placas/obtener.php',
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if(data.success) {
                var placa = data.data;
                $('#placaId').val(placa.id);
                $('#placa').val(placa.placa);
                $('#diseno').val(placa.diseno);
                $('#cantidad').val(placa.cantidad);
                $('#tamano').val(placa.tamano);
                $('#estado').val(placa.estado);
                $('#fecha').val(placa.fecha);
                $('#tituloModal').text('Editar Placa');
                $('#modalPlaca').modal('show');
            }
        }
    });
}

// Eliminar registro

function eliminarPlaca(id) {
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
                url: 'app/ajax/placas/eliminar.php',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#tablaPlacas').DataTable().ajax.reload(null, false);
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
