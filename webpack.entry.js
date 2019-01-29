const path = require("path");
const resolve = url => path.resolve(__dirname, url);

module.exports = {
    index: resolve('public/static/js/ints/index.js'),
    details: resolve('public/static/js/ints/details.js')
}