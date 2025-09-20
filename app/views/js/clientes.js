$(document).ready(function() {

    // Inicializar DataTable
    var tabla = $('#tablaClientes').DataTable({
        "ajax": {
            "url": "app/api/clientes/listar.php",
            "type": "POST"
        },
        "columns": [
            {"data": "id"},
            {"data": "nombre"},
            {"data": "documento"},
            {"data": "email"},
            {"data": "telefono"},
            {"data": "direccion"},
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-info" onclick="editarCliente(${row.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarCliente(${row.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json"
        },
        "processing": true,
        "serverSide": false,
        "pageLength": 25,
        "order": [[0, "desc"]],
        "responsive": true,
        "autoWidth": false,
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
        $('#tablaClientes_filter input').focus();
    });

    // Crear y editar registro
    $('#formCliente').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var url = $('#clienteId').val() ? 'app/api/clientes/editar.php' : 'app/api/clientes/crear.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#modalCliente').modal('hide');
                    tabla.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#formCliente')[0].reset();
                    $('#clienteId').val('');
                    $('#tituloModal').text('Nueva Cliente');
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
    $('#modalCliente').on('hidden.bs.modal', function() {
        $('#formCliente')[0].reset();
        $('#clienteId').val('');
        $('#tituloModal').text('Nueva Cliente');
    });

});

// Obtener datos para editar
function editarCliente(id) {
    $.ajax({
        url: 'app/api/clientes/obtener.php',
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if(data.success) {
                var cliente = data.data;
                $('#clienteId').val(cliente.id);
                $('#nombre').val(cliente.nombre);
                $('#documento').val(cliente.documento);
                $('#email').val(cliente.email);
                $('#telefono').val(cliente.telefono);
                $('#direccion').val(cliente.direccion);
                $('#tituloModal').text('Editar Cliente');
                $('#modalCliente').modal('show');
            }
        }
    });
}

// Función para eliminar
function eliminarCliente(id) {
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
                url: 'app/api/clientes/eliminar.php',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#tablaClientes').DataTable().ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message,
                            timer: 1500,
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

// Mantiene actualizados los registros en segundo plano
setInterval(function () {
    $('#tablaClientes').DataTable().ajax.reload(null, false); 
}, 10 * 60 * 1000);