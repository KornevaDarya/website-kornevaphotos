const { series, watch: watchFiles } = require("gulp");
const { reload } = require("./server");
const { styles } = require("./styles");
const { vendor, inline, entries } = require("./scripts");
const {
    styles: stylesPath,
    scripts: scriptsPath,
    php,
    images
} = require("./files");

const watch = () => {
    watchFiles(stylesPath.watch, styles);
    watchFiles(scriptsPath.vendor, vendor);
    watchFiles(scriptsPath.inline, inline);
    watchFiles(scriptsPath.entries.watch, entries);
    watchFiles([...php, images], reload);
}

module.exports = {
    watch
};
