module.exports = {
  "extends": "airbnb-base",
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
