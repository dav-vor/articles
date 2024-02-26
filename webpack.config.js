const path = require('path');


module.exports = {
	devServer: {
		publicPath: '/assets/',
	},
	devtool: 'source-map',
	entry: {
		main: './www/js/front.js',
		naja: 'naja'
	},	mode: (process.env.NODE_ENV === 'production') ? 'production' : 'development',
	resolve: {
		extensions: ['.js', '.jsx']
	},
	output: {
		filename: '[name].bundle.js',
		path: path.join(__dirname, 'www', 'assets'),
	},
};
