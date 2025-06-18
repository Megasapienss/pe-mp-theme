const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';

    return {
        entry: {
            main: './src/js/main.js',
            main: './src/scss/main.scss',
            'components/provider-single': './src/js/components/provider-single.js',
            'components/provider-archive': './src/js/components/provider-archive.js'
        },
        output: {
            filename: 'js/[name].js',
            path: path.resolve(__dirname, 'dist')
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    }
                },
                {
                    test: /\.(sa|sc|c)ss$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        'sass-loader'
                    ]
                },
                {
                    test: /\.(png|jpg|jpeg|gif)$/i,
                    type: 'asset/resource',
                    generator: {
                        filename: 'images/[name][ext]'
                    },
                    use: [
                        {
                            loader: 'image-webpack-loader',
                            options: {
                                mozjpeg: {
                                    progressive: true,
                                    quality: 65
                                },
                                optipng: {
                                    enabled: true,
                                },
                                pngquant: {
                                    quality: [0.65, 0.90],
                                    speed: 4
                                },
                                gifsicle: {
                                    interlaced: false,
                                },
                                webp: {
                                    quality: 75
                                }
                            }
                        }
                    ]
                },
                {
                    test: /\.svg$/,
                    type: 'asset/resource',
                    generator: {
                        filename: 'images/[name][ext]'
                    }
                }
            ]
        },
        optimization: {
            minimizer: [
                new CssMinimizerPlugin(),
                new TerserPlugin()
            ]
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: 'css/[name].css'
            })
        ],
        devtool: isProduction ? false : 'source-map'
    };
}; 