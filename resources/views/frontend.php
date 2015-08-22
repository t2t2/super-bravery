<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?= trans('app.branding') ?></title>

	<link rel="stylesheet" href="<?= asset('assets/stylesheets/app.css') ?>" />
</head>
<body data-config="<?= e(route('frontend.config')) ?>">
	<div id="application" class="no-js">
		Javascript not loaded
	</div>

	<script src="<?= asset('assets/javascripts/main.js') ?>"></script>
</body>
</html>