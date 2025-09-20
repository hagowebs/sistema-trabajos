$(document).ready(function() {

    // Inicializar DataTable
    var tabla = $('#tablaProducto').DataTable({
        "ajax": {
            "url": "app/api/productos/listar.php",
            "type": "POST"
        },
        "columns": [
            {"data": "id"},
            {"data": "producto"},
            {"data": "categoria"},
            {"data": "precio"},
            {"data": "maquila"},
            {"data": "mayoreo"},
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-info" onclick="editarProducto(${row.id})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${row.id})">
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

    // Crear y editar registro
    $('#formProducto').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var url = $('#productoId').val() ? 'app/api/productos/editar.php' : 'app/api/productos/crear.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#modalProducto').modal('hide');
                    tabla.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#formProducto')[0].reset();
                    $('#productoId').val('');
                    $('#tituloModal').text('Nuevo Producto');
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

    // Focus el input de búsqueda
    tabla.on('init.dt', function () {
        $('#tablaProducto_filter input').focus();
    });

    // Limpiar formulario al cerrar modal
    $('#modalProducto').on('hidden.bs.modal', function() {
        $('#formProducto')[0].reset();
        $('#productoId').val('');
        $('#tituloModal').text('Nueva Producto');
    });

});

// Obtener datos para editar
function editarProducto(id) {
    $.ajax({
        url: 'app/api/productos/obtener.php',
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if(data.success) {
                var producto = data.data;
                $('#productoId').val(producto.id);
                $('#producto').val(producto.producto);
                $('#categoria').val(producto.categoria);
                $('#precio').val(producto.precio);
                $('#maquila').val(producto.maquila);
                $('#mayoreo').val(producto.mayoreo);
                $('#tituloModal').text('Editar Producto');
                $('#modalProducto').modal('show');
            }
        }
    });
}

// Función para eliminar
function eliminarProducto(id) {
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
                url: 'app/api/productos/eliminar.php',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#tablaProducto').DataTable().ajax.reload(null, false);
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
    $('#tablaProducto').DataTable().ajax.reload(null, false); 
}, 10 * 60 * 1000);