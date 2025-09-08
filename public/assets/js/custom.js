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

	// Fix doble click en botón enviar

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

  // Reset botón al cerrar un modal

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

});
