const { series, parallel } = require('gulp');
const { styles } = require('./.gulp/styles');
const { scripts } = require('./.gulp/scripts');
const { server } = require('./.gulp/server');
const { watch } = require('./.gulp/watch');

const build = parallel(styles, scripts);
const serve = parallel(server, watch);

module.exports = {
  default: series(build, serve),
  build,
  serve,
  scripts,
  styles,
  server,
  watch,
};
