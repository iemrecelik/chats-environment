const express = require('express')
  , app = express()
  , path = require('path')
  , mongoDB = require(path.join(__dirname, '/config/MongoDBConn.js'))

const defined = require(path.join(__dirname, '/config/defined.js'));
global.__defined = new defined(__dirname);

const SocketManager = require(path.join(__dirname, 'socketModule/Sockets/SocketManager.js'));

const server = app.listen('3333', function() {
  console.log('Application run like success : ' + new Date());
});

app.io = require('socket.io')(server);

const socketNeedVals = {
  io: app.io,
}

SocketManager(socketNeedVals);