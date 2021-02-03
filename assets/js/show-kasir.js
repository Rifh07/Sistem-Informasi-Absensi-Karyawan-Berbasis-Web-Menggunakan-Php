$(document).ready(function() {
	setInterval(function () {
		$('#show-kasir').load('admin/show-kasir.php')
	}, 500);
});

$(document).ready(function() {
	setInterval(function () {
		$('#show-koki').load('admin/show-koki.php')
	}, 500);
});

$(document).ready(function() {
	setInterval(function () {
		$('#show-waiters').load('admin/show-waiters.php')
	}, 500);
});

$(document).ready(function() {
	setInterval(function () {
		$('#tracking').load('show-tracking.php')
	}, 500);
});

$(document).ready(function() {
	setInterval(function () {
		$('#cart').load('cart().php')
	}, 500);
});