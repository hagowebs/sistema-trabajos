/* Esconder Sidebar */

if (window.matchMedia("(max-width:767px)").matches) {
	$("body").removeClass('sidebar-collapse');
} else {
	$("body").addClass('sidebar-collapse');
}