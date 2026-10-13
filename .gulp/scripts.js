const { parallel, src, dest, lastRun } = require("gulp");
const terser = require("gulp-terser");
const concat = require("gulp-concat");
const babel = require("gulp-babel");
const webpack = require("webpack-stream");
const compiler = require("webpack");
const named = require("vinyl-named");
const config = require("./webpack.config");
const { sync } = require("./server");
const { scripts: scriptsPath } = require("./files");
const { rename, filterChanged, deletePreviosVersion } = require("./utilities");

const vendor = () =>
    src(scriptsPath.vendor, { since: lastRun(vendor) })
        .pipe(concat("vendor.js", { newLine: ";" }))
        .pipe(filterChanged(scriptsPath.output))
        .pipe(deletePreviosVersion(scriptsPath.output))
        .pipe(rename.addRandomHash())
        .pipe(dest(scriptsPath.output))
        .pipe(sync.stream());

const inline = () =>
    src(scriptsPath.inline, { since: lastRun(inline) })
        .pipe(concat("inline.js", { newLine: ";" }))
        .pipe(babel({ presets: ["@babel/preset-env"] }))
        .pipe(terser())
        .pipe(filterChanged(scriptsPath.output))
        .pipe(deletePreviosVersion(scriptsPath.output))
        .pipe(rename.addRandomHash())
        .pipe(dest(scriptsPath.output))
        .pipe(sync.stream());

const entries = () =>
    src(scriptsPath.entries.input)
        .pipe(named())
        .pipe(webpack(config, compiler).on("error", function(err) {
            this.emit("end");
        }))
        .pipe(filterChanged(scriptsPath.output))
        .pipe(deletePreviosVersion(scriptsPath.output))
        .pipe(rename.addRandomHash())
        .pipe(dest(scriptsPath.output))
        .pipe(sync.stream());

module.exports = {
    scripts: parallel(vendor, inline, entries),
    vendor,
    inline,
    entries
};
