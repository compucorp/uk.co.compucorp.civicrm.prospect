module.exports = {
  "extends": "airbnb-base",
  "globals": {
    "CRM": 'writable',
    "angular": 'readonly',
    "inject": 'readonly',
    "moment": 'readonly'
  },
  "rules": {
    "import/no-extraneous-dependencies": ["error", {
      "devDependencies": true
    }]
  },
  "env": {
    "commonjs": true,
    "amd": true
  }
};
