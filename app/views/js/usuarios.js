$(document).ready(function() {

    // Inicializar DataTable

    var tabla = $('#tablaUsuarios').DataTable({
        "processing": true,
        "serverSide": false,
        "pageLength": 25,
        "order": [[0, "desc"]],
        "ajax": {
            "url": "app/ajax/usuarios/listar.php",
            "type": "POST"
        },
        "columns": [
            {"data": "id"},
            {"data": "nombre"},
            {"data": "usuario"},
            {"data": "perfil"},
            {"data": "estado"},
            {"data": "ultimo_login"},
            {
                "data": null,
                "render": function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info" onclick="editarUsuario(${row.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarUsuario(${row.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json"
        }
    });

    // Crear y editar registro

    $('#formUsuario').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var url = $('#usuarioId').val() ? 'app/ajax/usuarios/editar.php' : 'app/ajax/usuarios/crear.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#modalUsuario').modal('hide');
                    tabla.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#formUsuario')[0].reset();
                    $('#usuarioId').val('');
                    $('#tituloModal').text('Nueva Usuario');
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

    $('#modalUsuario').on('hidden.bs.modal', function() {
        $('#formUsuario')[0].reset();
        $('#usuarioId').val('');
        $('#tituloModal').text('Nueva Usuario');
    });

});

// Obtener datos para editar

function editarUsuario(id) {
    $.ajax({
        url: 'app/ajax/usuarios/obtener.php',
        type: 'POST',
        data: {id: id},
        dataType: 'json',
        success: function(data) {
            if(data.success) {
                var usuario = data.data;
                $('#usuarioId').val(usuario.id);
                $('#nombre').val(usuario.nombre);
                $('#usuario').val(usuario.usuario);
                $('#clave').val(usuario.clave);
                $('#perfil').val(usuario.perfil);
                $('#tituloModal').text('Editar Usuario');
                $('#modalUsuario').modal('show');
            }
        }
    });
}

// Función para eliminar

function eliminarUsuario(id) {
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
                url: 'app/ajax/usuarios/eliminar.php',
                type: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#tablaUsuarios').DataTable().ajax.reload(null, false);
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
