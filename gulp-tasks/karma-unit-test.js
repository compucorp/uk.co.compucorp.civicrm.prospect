/**
 * @file
 * Exports Gulp "test" task
 */

const karma = require('karma');
const path = require('path');

module.exports = (done) => {
  new karma.Server({
    configFile: path.resolve(__dirname, '../ang/test/karma.conf.js'),
    singleRun: true
  }, done).start();
};
