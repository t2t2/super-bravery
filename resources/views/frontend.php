<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?= trans('app.branding') ?></title>

	<link rel="stylesheet" href="<?= asset(versioned('assets/stylesheets/app.css')) ?>" />
</head>
<body data-config="<?= e(route('frontend.config')) ?>" data-base="<?= e(url()) ?>">
	<div id="application" class="no-js">
		<header class="header container">
			<a>Super Ultimate Bravery</a>
		</header>

		<div class="content">
			<div class="container">
				<p><strong>Loading the site...</strong></p>

				<noscript><p>Please Enable Javascript!</p></noscript>

			</div>
		</div>


	</div>

	<script src="<?= asset(versioned('/assets/javascripts/main.js')) ?>"></script>
</body>
</html>