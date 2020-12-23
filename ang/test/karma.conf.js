const cv = require('civicrm-cv')({ mode: 'sync' });

module.exports = (config) => {
  const civicrmPath = cv("path -d '[civicrm.root]'")[0].value;
  var civicasePath = cv('path -x uk.co.compucorp.civicase')[0].value;
  const extPath = cv('path -x uk.co.compucorp.civicrm.prospect')[0].value;

  config.set({
    basePath: civicrmPath,
    frameworks: ['jasmine'],
    files: [
      // The global dependencies
      'bower_components/jquery/dist/jquery.min.js',
      'bower_components/jquery-ui/jquery-ui.js',
      'bower_components/lodash-compat/lodash.min.js',
      'bower_components/select2/select2.min.js',
      'bower_components/jquery-validation/dist/jquery.validate.min.js',
      'packages/jquery/plugins/jquery.blockUI.js',
      'js/Common.js',

      'bower_components/angular/angular.min.js',
      'bower_components/angular-mocks/angular-mocks.js',
      'bower_components/angular-route/angular-route.min.js',

      civicasePath + '/packages/moment.min.js',

      // Global variables that need to be accessible in the test environment
      `${extPath}/ang/test/global.js`,

      // Civicase files
      civicasePath + '/ang/civicase-base.js',
      civicasePath + '/ang/civicase.js',
      { pattern: civicasePath + '/ang/test/mocks/modules.mock.js' },
      { pattern: civicasePath + '/ang/test/mocks/**/*.js' },
      { pattern: civicasePath + '/ang/civicase-base/**/*.js' },

      // Source Files
      `${extPath}/ang/prospect.js`,
      { pattern: `${extPath}/ang/prospect/**/*.js` },

      // Spec files
      { pattern: civicasePath + '/ang/test/mocks/modules.mock.js' },
      { pattern: civicasePath + '/ang/test/mocks/**/*.js' },
      { pattern: `${extPath}/ang/test/mocks/modules.mock.js` },
      { pattern: `${extPath}/ang/test/mocks/**/*.js` },
      { pattern: `${extPath}/ang/test/prospect/**/*.js` }
    ],
    exclude: [
    ],
    reporters: ['progress'],
    // Web server port
    port: 9876,
    colors: true,
    logLevel: config.LOG_INFO,
    autoWatch: true,
    browsers: ['ChromeHeadlessBrowser'],
    customLaunchers: {
      ChromeHeadlessBrowser: {
        base: 'ChromeHeadless',
        flags: [
          '--no-sandbox',
          '--disable-dev-shm-usage'
        ]
      }
    }
  });
};
