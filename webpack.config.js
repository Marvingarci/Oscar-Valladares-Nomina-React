const path = require('path');

module.exports = {
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),       
        },    
    },
    resolve: {
        fallback: {
          fs: false,
          crypto: false
        }
      }
};


