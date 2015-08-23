module.exports = function (env) {
	var path = require('path'),
		paths = require('./'),
		webpack = require('webpack'),
		webpackManifest = require('../lib/webpackManifest');

	var jsSrc = path.resolve(paths.sourceAssets + '/javascripts/'),
		jsDest = paths.publicAssets + '/javascripts/',
		publicPath = '/assets/javascripts/'

	var webpackConfig = {
		context: jsSrc,

		plugins: [],

		resolve: {
			extensions: ['', '.js']
		},

		module: {
			loaders: [
				{
					test:    /\.js$/,
					loader:  'babel-loader?stage=1',
					exclude: /node_modules/
				},
				{
					test: /\.html$/,
					loader: 'html-loader',
					exclude: /node_modules/
				}
			]
		}
	}

	if (env !== 'test') {
		// Karma doesn't need entry points or output settings
		webpackConfig.entry = {
			main: ['./main.js'],
		}

		webpackConfig.output = {
			path:       jsDest,
			filename:   env === 'production' ? '[name]-[hash].js' : '[name].js',
			publicPath: publicPath
		}

		// Factor out common dependencies into a shared.js
		/*
		webpackConfig.plugins.push(
			new webpack.optimize.CommonsChunkPlugin({
				name:     'shared',
				filename: env === 'production' ? '[name]-[hash].js' : '[name].js',
			})
		)
		*/
	}

	if (env === 'development') {
		webpackConfig.devtool = 'source-map'
		webpack.debug = true
	}

	if (env === 'production') {
		webpackConfig.plugins.push(
			new webpackManifest(publicPath, 'public'),
			new webpack.DefinePlugin({
				'process.env': {
					'NODE_ENV': JSON.stringify('production')
				}
			}),
			new webpack.optimize.DedupePlugin(),
			new webpack.optimize.UglifyJsPlugin(),
			new webpack.NoErrorsPlugin()
		)
	}

	return webpackConfig
}
