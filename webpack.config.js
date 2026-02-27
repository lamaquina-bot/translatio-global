/**
 * Webpack Configuration - Translatio Global Theme
 * Compiles SCSS to CSS, ES6 to ES5, with minification and source maps
 * 
 * @version 1.0.0
 */

const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const WebpackNotifierPlugin = require('webpack-notifier');

// Environment
const isProduction = process.env.NODE_ENV === 'production';
const isDevelopment = !isProduction;

// Paths
const themePath = path.resolve(__dirname, 'wp-content/themes/translatio');
const assetsPath = path.resolve(themePath, 'assets');
const srcPath = path.resolve(__dirname, 'src');

module.exports = {
    // Context
    context: __dirname,
    
    // Entry points
    entry: {
        // JavaScript bundles
        'js/main.min': path.resolve(srcPath, 'js/main.js'),
        'js/navigation.min': path.resolve(srcPath, 'js/navigation.js'),
        'js/forms.min': path.resolve(srcPath, 'js/forms.js'),
        
        // CSS bundles
        'css/main.min': path.resolve(srcPath, 'scss/main.scss'),
    },
    
    // Output configuration
    output: {
        path: assetsPath,
        filename: '[name].js',
        // Clean output folder before build
        clean: {
            keep: /images/, // Keep images folder
        },
    },
    
    // Mode-specific settings
    mode: isProduction ? 'production' : 'development',
    devtool: isProduction ? 'source-map' : 'eval-source-map',
    
    // Module rules
    module: {
        rules: [
            // JavaScript
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: [
                    {
                        loader: 'babel-loader',
                        options: {
                            presets: [
                                ['@babel/preset-env', {
                                    targets: {
                                        browsers: ['> 1%', 'last 2 versions', 'not dead']
                                    },
                                    useBuiltIns: 'usage',
                                    corejs: 3
                                }]
                            ],
                            plugins: [
                                '@babel/plugin-proposal-class-properties',
                                '@babel/plugin-transform-runtime'
                            ]
                        }
                    },
                    {
                        loader: 'eslint-loader',
                        options: {
                            fix: true,
                            emitWarning: isDevelopment
                        }
                    }
                ]
            },
            
            // SCSS/CSS
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    // Extract CSS to separate file
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            publicPath: '../'
                        }
                    },
                    // CSS loader
                    {
                        loader: 'css-loader',
                        options: {
                            sourceMap: true,
                            importLoaders: 2
                        }
                    },
                    // PostCSS (autoprefixer, etc.)
                    {
                        loader: 'postcss-loader',
                        options: {
                            sourceMap: true,
                            postcssOptions: {
                                plugins: [
                                    require('autoprefixer')({
                                        grid: true
                                    }),
                                    require('postcss-preset-env')({
                                        stage: 3
                                    })
                                ]
                            }
                        }
                    },
                    // SCSS loader
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true,
                            sassOptions: {
                                outputStyle: 'expanded'
                            }
                        }
                    }
                ]
            },
            
            // Images
            {
                test: /\.(png|jpe?g|gif|webp|svg)$/i,
                type: 'asset/resource',
                generator: {
                    filename: 'images/[name].[hash:8][ext]'
                }
            },
            
            // Fonts
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/i,
                type: 'asset/resource',
                generator: {
                    filename: 'fonts/[name].[hash:8][ext]'
                }
            }
        ]
    },
    
    // Plugins
    plugins: [
        // Clean dist folder
        new CleanWebpackPlugin({
            cleanStaleWebpackAssets: false
        }),
        
        // Extract CSS
        new MiniCssExtractPlugin({
            filename: '[name].css',
            chunkFilename: '[id].css'
        }),
        
        // Notifications (development only)
        isDevelopment && new WebpackNotifierPlugin({
            title: 'Translatio Global',
            emoji: true,
            alwaysNotify: false
        })
    ].filter(Boolean),
    
    // Optimization
    optimization: {
        minimize: isProduction,
        minimizer: [
            // Minify JavaScript
            new TerserPlugin({
                terserOptions: {
                    parse: {
                        ecma: 8
                    },
                    compress: {
                        ecma: 5,
                        warnings: false,
                        comparisons: false,
                        inline: 2
                    },
                    mangle: {
                        safari10: true
                    },
                    output: {
                        ecma: 5,
                        comments: false,
                        ascii_only: true
                    }
                },
                parallel: true,
                extractComments: false
            }),
            
            // Minify CSS
            new CssMinimizerPlugin({
                minimizerOptions: {
                    preset: [
                        'default',
                        {
                            discardComments: { removeAll: true },
                            normalizeWhitespace: false
                        }
                    ]
                }
            })
        ],
        
        // Split chunks (for shared code)
        splitChunks: {
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'js/vendor',
                    chunks: 'all'
                }
            }
        }
    },
    
    // Performance hints
    performance: {
        hints: isProduction ? 'warning' : false,
        maxEntrypointSize: 512000,
        maxAssetSize: 512000
    },
    
    // Watch options
    watchOptions: {
        ignored: /node_modules/,
        aggregateTimeout: 300,
        poll: 1000
    },
    
    // Stats
    stats: {
        colors: true,
        modules: false,
        children: false,
        chunks: false,
        chunkModules: false
    },
    
    // Resolve
    resolve: {
        extensions: ['.js', '.jsx', '.scss', '.css'],
        alias: {
            '@js': path.resolve(srcPath, 'js'),
            '@scss': path.resolve(srcPath, 'scss'),
            '@images': path.resolve(assetsPath, 'images')
        }
    },
    
    // Externals (don't bundle these)
    externals: {
        jquery: 'jQuery'
    }
};

/**
 * Development server configuration (optional)
 * For use with webpack-dev-server
 */
module.exports.devServer = {
    static: {
        directory: path.join(__dirname, 'wp-content/themes/translatio'),
    },
    compress: true,
    port: 3000,
    hot: true,
    open: false,
    watchFiles: [
        'src/**/*',
        'wp-content/themes/translatio/**/*.php'
    ],
    client: {
        overlay: {
            errors: true,
            warnings: false
        }
    }
};
