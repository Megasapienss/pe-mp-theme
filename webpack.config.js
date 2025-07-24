const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const sharp = require('sharp');
const fs = require('fs').promises;

// Custom plugin to convert images to WebP
class WebPConverterPlugin {
    constructor(options = {}) {
        this.options = options;
    }

    apply(compiler) {
        compiler.hooks.afterEmit.tapAsync('WebPConverterPlugin', async (compilation, callback) => {
            try {
                const outputPath = compilation.outputOptions.path;
                const imagesDir = path.join(outputPath, 'images');
                
                // Check if images directory exists
                try {
                    await fs.access(imagesDir);
                } catch {
                    callback();
                    return;
                }

                // Get all files in images directory
                const files = await this.getFilesRecursively(imagesDir);
                
                for (const file of files) {
                    const ext = path.extname(file).toLowerCase();
                    if (['.jpg', '.jpeg', '.png', '.gif'].includes(ext)) {
                        try {
                            const webpPath = file.replace(ext, '.webp');
                            
                            // Convert to WebP
                            await sharp(file)
                                .webp({ quality: 75, effort: 6 })
                                .toFile(webpPath);
                            
                            console.log(`Converted ${path.basename(file)} to WebP`);
                            
                            // Remove original file
                            await fs.unlink(file);
                            
                        } catch (error) {
                            console.warn(`Failed to convert ${file} to WebP:`, error.message);
                        }
                    }
                }
                
                callback();
            } catch (error) {
                console.error('WebP conversion error:', error);
                callback();
            }
        });
    }

    async getFilesRecursively(dir) {
        const files = [];
        const items = await fs.readdir(dir, { withFileTypes: true });
        
        for (const item of items) {
            const fullPath = path.join(dir, item.name);
            if (item.isDirectory()) {
                files.push(...await this.getFilesRecursively(fullPath));
            } else {
                files.push(fullPath);
            }
        }
        
        return files;
    }
}

module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';

    return {
        entry: {
            scripts: './src/js/main.js',
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
                    }
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
                        to: 'icons',
                        noErrorOnMissing: true,
                        globOptions: {
                            ignore: ['**/*.DS_Store']
                        }
                    }
                ]
            }),
            new WebPConverterPlugin()
        ],
        devtool: isProduction ? false : 'source-map'
    };
}; 