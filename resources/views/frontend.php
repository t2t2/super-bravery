<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?= trans('app.branding') ?></title>

	<link rel="stylesheet" href="<?= asset(versioned('assets/stylesheets/app.css')) ?>" />
</head>
<body data-config="<?= e(route('frontend.config')) ?>" data-base="<?= e(url()) ?>">
	<div id="application" class="no-js">
		Javascript not loaded
	</div>

	<script src="<?= asset(versioned('/assets/javascripts/main.js')) ?>"></script>
</body>
</html>