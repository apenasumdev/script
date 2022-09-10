//MODE SETUP
const isDevelopment = process.env.NODE_ENV === 'development';

//PLUGINS
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
//HELPERS
const path = require('path');
const webpack = require('webpack');

//PUBLIC PATH
let publicPath = '/';
let resolvedPublicPath = path.join(`${__dirname}/../`, publicPath);

//FNC
function src(path){
    return `src/${path}`;
}

//WEBPACK CONFIG
// This is main configuration object.
// Here you write different options and tell Webpack what to do
module.exports = {

    // Path to your entry point. From this file Webpack will begin his work
    entry: [
        "babel-polyfill",
        path.resolve(src('ts/app.ts')),
        path.resolve(src('sass/app.sass'))
    ],

    // Path and filename of your result bundle.
    // Webpack will bundle all JavaScript into this file
    output: {
        path: resolvedPublicPath,
        filename: 'assets/js/[name].js'
    },

    // Default mode for Webpack is production.
    // Depending on mode Webpack will apply different things
    // on final bundle. For now we don't need production's JavaScript
    // minifying and other thing so let's set mode to development
    mode: isDevelopment?'development':'production',

    optimization: {
        splitChunks: {
            chunks: "all",
        }
    },


    //PLUGINS
    plugins: [
        new webpack.HashedModuleIdsPlugin(),
        new VueLoaderPlugin(),
        new MiniCssExtractPlugin({
            filename: "assets/css/app.css"
        })
    ],

    //MODULES
    module: {
        rules: [
            //Typescript Loader
            {
                test: /\.tsx?$/,
                use: 'ts-loader',
                exclude: /node_modules/,
            },
            //VUE Loader
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            // this will apply to both plain `.js` files
            // AND `<script>` blocks in `.vue` files
            {
                test: /\.js$/,
                loader: 'babel-loader',
                exclude: file => (
                    /node_modules/.test(file) &&
                    !/\.vue\.js/.test(file)
                )
            },
            // this will apply to both plain `.css` files
            // AND `<style>` blocks in `.vue` files
            {
                test: /\.s(a|c)ss$/,
                use: [
                    'vue-style-loader',
                    {
                        // After all CSS loaders we use plugin to do his work.
                        // It gets all transformed CSS and extracts it into separate
                        // single bundled file
                        loader: MiniCssExtractPlugin.loader
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            url: false,
                        }
                    },
                    'resolve-url-loader',
                    {
                        /*
                        Currently sass-loader causes problem when we try to compile
                        sass and scss files at the same time...
                        to fix this we decided to use scss as default compiler
                         */
                        loader: 'sass-loader',
                        options: {
                            sassOptions: {
                                indentedSyntax: true
                            }
                        }
                    }
                ]
            },
            //Loader for Image Files
            {
                test: /\.(png|jpe?g|gif|webp|bmp)$/,
                use: [
                    {
                        // Using file-loader too
                        loader: "file-loader",
                        options: {
                            outputPath: 'assets/images'
                        }
                    }
                ]
            },
            //Loader for Font Files
            {
                // Apply rule for fonts files
                test   : /\.(ttf|eot|svg|woff(?:2)?)(\?[a-z0-9]+)?$/,
                use: [
                    {
                        // Using file-loader too
                        loader: "file-loader",
                        options: {
                            outputPath: 'assets/fonts/[name].[ext]'
                        }
                    }
                ]
            }
        ]
    },
    resolve: {
        extensions: [ '.tsx', '.ts', '.js', '.vue', '.css', '.js' ,'.sass', '.scss'],
        'alias':{
            '@':path.resolve(__dirname,'src/ts')
        }
    },
};
