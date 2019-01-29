const path = require('path');
const entry = require('./webpack.entry');
const webpack = require('webpack');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
    devtool: 'cheap-module-eval-source-map',//开发环境推荐：cheap-module-eval-source-map  生产环境推荐：cheap-module-source-map
    entry: entry,
    output: {
        path: path.resolve(__dirname, 'public/static/js/dist'),
        filename: '[name].js'
    },
    module: {},
    plugins: [
        new webpack.DefinePlugin({
            'process.env': { NODE_ENV: '"production"' }
        }),
        new UglifyJsPlugin({
            uglifyOptions: {
                compress: {
                    warnings: false
                }
            },
            sourceMap: true,
            parallel: true
        })
    ],
    devServer: {}
};