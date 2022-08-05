const defaults = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');


module.exports = {
  ...defaults,
  externals: {

  },  
};

