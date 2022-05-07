const defaultConfig = require('@wordpress/scripts/config/webpack.config')
const isProduction = process.env.NODE_ENV === 'production';

if (!isProduction) {
    module.exports = {
        ...defaultConfig,
        devServer: {
            ...defaultConfig.devServer,
            allowedHosts: [
                '127.0.0.1',
                'localhost',
                '.dev.site', // NOTE: match your domain.
            ],
            hot: true
        }
    }
} else {
    module.exports = defaultConfig;
}
