const path = require( 'path' );
const webpack = require( 'webpack' );

module.exports = {
	entry: {
		view:'./assets/js/src/View/app.js',
	},
	output: {
		filename:'./assets/js/dist/[name].js'
	},
	module: {
		rules: [
			{
				use: {
					loader: 'babel-loader',
				},
			}
		],
	}
};
