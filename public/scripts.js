$(document).ready(function () {
	
	// Esconder Sidebar
	if (window.matchMedia("(max-width:767px)").matches) {
		$("body").removeClass('sidebar-collapse');
	} else {
		$("body").addClass('sidebar-collapse');
	}

	// Inicializar InputMask
	$('#telefono').inputmask('(999) 999-9999');

	// Fix Bootstrap 4 focus para que CKEditor funcione en modales
	$.fn.modal.Constructor.prototype._enforceFocus = function () {
	var modal = this._element;
	$(document).off('focusin.bs.modal')
		.on('focusin.bs.modal', function (e) {
		if (
			modal !== e.target &&
			!modal.contains(e.target) &&
			!$(e.target).closest('.cke_dialog, .select2-dropdown').length
		) {
			modal.focus();
		}
		});
	};

	// Fix doble click en boton enviar
	$(function () {
	$(document).on('submit', 'form', function (e) {
		let $form = $(this);
		if ($form.data('enviado') === true) {
			e.preventDefault();
			return false;
		}
		if (!this.checkValidity()) {
			return;
		}
		$form.data('enviado', true);
		$form.find('button[type="submit"], input[type="submit"]')
			.prop('disabled', true)
			.each(function () {
			let $btn = $(this);
			if ($btn.is('#btnEnviar')) {
				$btn.text('Enviando...');
			}
		});
	});

  // Reset boton al cerrar un modal
	$(document).on('hidden.bs.modal', '.modal', function () {
		let $form = $(this).find('form');
		if ($form.length) {
			$form.data('enviado', false);
			$form.find('button[type="submit"], input[type="submit"]')
			.prop('disabled', false)
			.each(function () {
				let $btn = $(this);
				if ($btn.is('#btnEnviar')) {
					$btn.text('Registrar');
				}
			});
		}
		});
	});

	// Omitir warning ckeditor4
	CKEDITOR.config.removePlugins = 'notification';
	CKEDITOR.config.versionCheck = false;

});

// Obtener numero de trabajos por estado
function cargarContadores() {
    fetch("app/api/trabajos/contar.php")
        .then(response => response.json())
        .then(data => {
            document.getElementById("contador-pendiente").innerText = data["pendiente"];
            document.getElementById("contador-diseno").innerText = data["diseño"];
            document.getElementById("contador-produccion").innerText = data["producción"];
            document.getElementById("contador-taller").innerText = data["taller"];
        })
        .catch(error => console.error("Error al cargar contadores:", error));
}

// Actualizar contadores al cargar la pagina
cargarContadores();

// Actualizar tabla y contadores cada 10 minutos
setInterval(function () {
    $.fn.dataTable.tables({ visible: true, api: true }).ajax.reload(null, false);
	cargarContadores();
}, 10 * 60 * 1000);
