var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');

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
		filename: "reditus.js"
	},
	plugins: [
		new ExtractTextPlugin('reditus.css'),
		new webpack.optimize.UglifyJsPlugin({
			compress: {
				warnings: false
			}
		}),
		new OptimizeCssAssetsPlugin({
			assetNameRegExp: /\.css$/,
			cssProcessor: require('cssnano'),
			cssProcessorOptions: {
				discardComments: {
					removeAll: true
				}
			},
			canPrint: true
		}),
		new webpack.DefinePlugin({
			'process.env':{
				'NODE_ENV': JSON.stringify('production')
			}
		})
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
