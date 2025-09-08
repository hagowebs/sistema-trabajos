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

    var tabla = $('#tablaTrabajo').DataTable({
        "ajax": {
            "url": "app/modules/trabajos/listar.php",
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
            { "data": "id_cliente" },
            { "data": "trabajo" },
            { "data": "fecha_inicial" },
            { "data": "fecha_final" },
            { "data": "estado" },
            {
                "data": "id",
                "orderable": false,
                "render": function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-warning" onclick="editarTrabajo(${data})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="eliminarTrabajo(${data})" title="Eliminar">
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

    // Mantiene actualizados los registros en segundo plano

    setInterval(function () {
        $('#tablaTrabajo').DataTable().ajax.reload(null, false); 
    }, 10 * 60 * 1000);

    // Obtener lista de usuarios

    $.ajax({
        url: 'app/modules/usuarios/seleccionar.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                var select = $('#id_encargado');
                select.empty();
                select.append('<option value="">Seleccionar</option>');
                response.data.forEach(function (usuario) {
                    select.append('<option value="' + usuario.id + '">' + usuario.nombre + '</option>');
                });
            } else {
                console.error("Error cargando usuarios: " + response.message);
            }
        },
        error: function () {
            console.error("Error AJAX al cargar usuarios");
        }
    });

    // Obtener lista de clientes

    $('#id_cliente').select2({
        placeholder: "Seleccionar",
        allowClear: true
    });
    $.ajax({
        url: 'app/modules/clientes/seleccionar.php',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                var select = $('#id_cliente');
                select.empty().append('<option value="">Seleccionar</option>');
                response.data.forEach(function (cliente) {
                    var option = new Option(cliente.nombre, cliente.id, false, false);
                    select.append(option);
                });
                select.trigger('change');
            } else {
                console.error("Error cargando clientes: " + response.message);
            }
        },
        error: function () {
            console.error("Error AJAX al cargar clientes");
        }
    });

    // Focus el input de búsqueda
    
    tabla.on('init.dt', function () {
        $('#tablaTrabajo_filter input').focus();
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

    // Inicializar CKEditor

    CKEDITOR.replace('trabajo', {
        toolbar: [
            { name: 'clipboard', items: ['Undo', 'Redo', '-', 'Cut', 'Copy', 'Paste'] },
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Strike'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule'] },
            { name: 'document', items: ['Source', '-', 'Maximize'] }
        ]
    });
    
    // Crear y editar registro

    $('#formTrabajo').on('submit', function (e) {
        e.preventDefault();

        // Actualizar el contenido de CKEditor

        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }

        var formData = $(this).serialize();
        var url = $('#trabajoId').val() ? 'app/modules/trabajos/editar.php' : 'app/modules/trabajos/crear.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $('#modalTrabajo').modal('hide');
                    tabla.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#formTrabajo')[0].reset();
                    $('#trabajoId').val('');
                    $('#tituloModal').text('Nuevo Trabajo');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar'
                });
            }
        });
    });

    // Limpiar formulario al cerrar modal

    $('#modalTrabajo').on('hidden.bs.modal', function () {
        $('#formTrabajo')[0].reset();
        $('#trabajoId').val('');
        $('#tituloModal').text('Nuevo Trabajo');
        CKEDITOR.instances['trabajo'].setData('');
    });
});

// Obtener datos para editar

function editarTrabajo(id) {
    $.ajax({
        url: 'app/modules/trabajos/obtener.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                var trabajo = data.data;
                $('#trabajoId').val(trabajo.id);
                CKEDITOR.instances['trabajo'].setData(trabajo.trabajo);
                $('#id_cliente').val(trabajo.id_cliente);
                $('#id_vendedor').val(trabajo.id_vendedor);
                $('#id_encargado').val(trabajo.id_encargado);
                $('#categoria').val(trabajo.categoria);
                $('#fecha_inicial').val(trabajo.fecha_inicial);
                $('#fecha_final').val(trabajo.fecha_final);
                $('#envio').val(trabajo.envio);
                $('#estado').val(trabajo.estado);
                $('#precio').val(trabajo.precio);
                $('#anticipo').val(trabajo.anticipo);
                $('#restante').val(trabajo.restante);
                $('#metodo_pago').val(trabajo.metodo_pago);
                $('#fecha').val(trabajo.fecha);
                $('#anticipo2').val(trabajo.anticipo2);
                $('#restante2').val(trabajo.restante2);
                $('#metodo_pago2').val(trabajo.metodo_pago2);
                $('#fecha2').val(trabajo.fecha2);
                $('#anticipo3').val(trabajo.anticipo3);
                $('#restante3').val(trabajo.restante3);
                $('#metodo_pago3').val(trabajo.metodo_pago3);
                $('#fecha3').val(trabajo.fecha3);
                $('#tituloModal').text('Editar Trabajo');
                $('#modalTrabajo').modal('show');
            }
        }
    });
}

// Eliminar registro

function eliminarTrabajo(id) {
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
                url: 'app/modules/trabajos/eliminar.php',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#tablaTrabajo').DataTable().ajax.reload(null, false);
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

    /*=============================================
    PAGOS
    =============================================*/

    function pagos() {
        var x = document.getElementById("pagos");
        if (x.style.display === "none") {
        x.style.display = "block";
        } else {
        x.style.display = "none";
        }
    }
    document.getElementById("precio").onchange = function() {calcularRestante();};
        function calcularRestante() {
        var x = document.getElementById("restante");
        var y = document.getElementById("precio");
        var z = document.getElementById("anticipo"); 
        x.value = y.value-z.value;
    }
    document.getElementById("anticipo").onchange = function() {calcularRestante2();};
    function calcularRestante2() {
        var x = document.getElementById("restante");
        var y = document.getElementById("precio");
        var z = document.getElementById("anticipo"); 
        x.value = y.value-z.value;
    }
    document.getElementById("anticipo2").onchange = function() {calcularRestante3();};
    function calcularRestante3() {
        var x = document.getElementById("restante2");
        var y = document.getElementById("restante");
        var z = document.getElementById("anticipo2");
        x.value = y.value-z.value;
    }
    document.getElementById("anticipo3").onchange = function() {calcularRestante4();};
    function calcularRestante4() {
        var x = document.getElementById("restante3");
        var y = document.getElementById("restante2");
        var z = document.getElementById("anticipo3");
        x.value = y.value-z.value;
    }

}
