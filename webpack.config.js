const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');

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
            path: path.resolve(__dirname, 'dist'),
            clean: true
        },
        stats: {
            errorDetails: true
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
                    test: /\.(png|jpe?g|gif|webp|avif)$/i,
                    type: 'asset',
                    parser: {
                        dataUrlCondition: {
                            maxSize: 8192 // 8kb
                        }
                    },
                    generator: {
                        filename: (pathData) => {
                            // Keep images in their original path structure
                            return pathData.filename.replace('src/', '');
                        }
                    },
                    use: [
                        {
                            loader: 'image-webpack-loader',
                            options: {
                                mozjpeg: {
                                    progressive: true,
                                    quality: 75
                                },
                                optipng: {
                                    enabled: true,
                                    optimizationLevel: 5
                                },
                                pngquant: {
                                    quality: [0.65, 0.90],
                                    speed: 4
                                },
                                gifsicle: {
                                    interlaced: false,
                                    optimizationLevel: 3
                                },
                                webp: {
                                    quality: 75,
                                    method: 6
                                }
                            }
                        }
                    ]
                },
                {
                    test: /\.svg$/,
                    type: 'asset',
                    parser: {
                        dataUrlCondition: {
                            maxSize: 4096 // 4kb
                        }
                    },
                    generator: {
                        filename: (pathData) => {
                            // Keep SVGs in their original path structure
                            return pathData.filename.replace('src/', '');
                        }
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
            }),
            new CopyPlugin({
                patterns: [
                    {
                        from: 'src/images',
                        to: 'images',
                        noErrorOnMissing: true,
                        globOptions: {
                            ignore: ['**/*.DS_Store']
                        }
                    },
                    {
                        from: 'src/icons',
                        to: 'icons', // Changed from 'images/icons' to 'icons'
                        noErrorOnMissing: true,
                        globOptions: {
                            ignore: ['**/*.DS_Store']
                        }
                    }
                ]
            })
        ],
        devtool: isProduction ? false : 'source-map'
    };
}; 