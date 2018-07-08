const configPar = require('./configParameters');
const mongoose = require('mongoose');
mongoose.Promise = require('bluebird');

const url = configPar.mongoDB.url;
var options = { 
	promiseLibrary: mongoose.Promise,
};

mongoose.connect(url, options, function(err) {
  if (err)
    console.log(err);
  else
    console.log('MongoDB connection success');
});
