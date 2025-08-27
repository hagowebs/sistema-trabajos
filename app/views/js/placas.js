$(document).ready(function() {
    // Inicializar DataTable
    var tabla = $('#tablaTrabajo').DataTable({
        "ajax": {
            "url": "app/ajax/pruebas/listar_trabajos.php",
            "type": "POST"
        },
        "columns": [
            {"data": "id"},
            {"data": "titulo"},
            {"data": "empresa"},
            {"data": "ubicacion"},
            {"data": "tipo_empleo"},
            {
                "data": "estado",
                "render": function(data) {
                    return data === 'Activo' ? 
                        '<span class="badge badge-success">Activo</span>' : 
                        '<span class="badge badge-danger">Inactivo</span>';
                }
            },
            {
                "data": "fecha_publicacion",
                "render": function(data) {
                    return new Date(data).toLocaleDateString('es-MX');
                }
            },
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info" onclick="editarTrabajo(${row.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarTrabajo(${row.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json"
        },
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false
    });

    // Manejar envío del formulario
    $('#formTrabajo').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = $('#trabajoId').val() ? 'app/ajax/pruebas/editar_trabajo.php' : 'app/ajax/pruebas/crear_trabajo.php';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#modalTrabajo').modal('hide');
                    tabla.ajax.reload(null, false); // Recargar sin resetear paginación
                    
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
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la solicitud'
                });
            }
        });
    });

    // Limpiar formulario al cerrar modal
    $('#modalTrabajo').on('hidden.bs.modal', function() {
        $('#formTrabajo')[0].reset();
        $('#trabajoId').val('');
        $('#tituloModal').text('Nuevo Trabajo');
    });
});

// Función para editar trabajo
function editarTrabajo(id) {
    $.ajax({
        url: 'app/ajax/pruebas/obtener_trabajo.php',
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if(data.success) {
                var trabajo = data.data;
                $('#trabajoId').val(trabajo.id);
                $('#titulo').val(trabajo.titulo);
                $('#empresa').val(trabajo.empresa);
                $('#ubicacion').val(trabajo.ubicacion);
                $('#tipo_empleo').val(trabajo.tipo_empleo);
                $('#salario').val(trabajo.salario);
                $('#estado').val(trabajo.estado);
                $('#descripcion').val(trabajo.descripcion);
                $('#tituloModal').text('Editar Trabajo');
                $('#modalTrabajo').modal('show');
            }
        }
    });
}

// Función para eliminar trabajo
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
                url: 'app/ajax/pruebas/eliminar_trabajo.php',
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
}
