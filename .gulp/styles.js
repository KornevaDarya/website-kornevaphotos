const { src, dest, parallel, lastRun } = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const sourcemaps = require("gulp-sourcemaps");
const postcss = require("gulp-postcss");
const autoprefixer = require("autoprefixer");
const cssnano = require("cssnano");
const webp = require("webp-in-css/plugin");
const queries = require("gulp-group-css-media-queries");
const { sync } = require("./server");
const { styles: stylesPath } = require("./files");
const { rename, filterChanged, deletePreviosVersion } = require("./utilities");
const webpOptions = { webpClass: "page_webp", noWebpClass: "page_no-webp" };

const scssDevelopment = () =>
    src(stylesPath.input)
        .pipe(sourcemaps.init())
        .pipe(sass({ outputStyle: "expanded" }))
        .pipe(queries())
        .pipe(postcss([webp(webpOptions)]))
        .pipe(sourcemaps.write())
        .pipe(filterChanged(stylesPath.output.dev))
        .pipe(dest(stylesPath.output.dev))
        .pipe(sync.stream());

const scssProduction = () =>
    src(stylesPath.input)
        .pipe(sass())
        .pipe(queries())
        .pipe(postcss([webp(webpOptions), autoprefixer(), cssnano()]))
        .pipe(filterChanged(stylesPath.output.prod))
        .pipe(deletePreviosVersion(stylesPath.output.prod))
        .pipe(rename.addRandomHash())
        .pipe(dest(stylesPath.output.prod));

module.exports = {
    styles: parallel(scssDevelopment, scssProduction)
};
