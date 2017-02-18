var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

module.exports = {
	resolve: {
		extensions: ["", ".jsx", ".js", ".json"]
	},
	entry: [
		"babel-polyfill",
		"./assets/app.js"
	],
	output: {
		path: __dirname + "/web/",
		publicPath: "/",
		filename: "tributum.js"
	},
	plugins: [
		new ExtractTextPlugin('tributum.css'),
	],
	module: {
		loaders: [
			{ 
				test: /\.js?$/,
				exclude: /node_modules/,
				loader: 'babel',
				query: {
					plugins: ['transform-runtime', 'transform-decorators-legacy'],
					presets: ['es2015', 'stage-0', 'react']
				}
			},
			{
				test: /\.css$/, loader: ExtractTextPlugin.extract('style-loader', 'css-loader')
			},
			{
				test: /\.(png|jpg|gif)$/,
				loader: 'url?limit=25000'
			},
			{
				test: /\.(eot|svg|ttf|woff(2)?)(\?v=\d+\.\d+\.\d+)?/,
				loader: 'url'
			},
			{
				test: /\.json$/,
				loader: "json"
			}
		]
	}
}
