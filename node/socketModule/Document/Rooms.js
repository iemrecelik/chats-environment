var mongoose = require('mongoose');
var Schema = mongoose.Schema;

mongoose.Promise = require('bluebird');

var roomsSchema = new Schema({
    roomName: String,
});

var Rooms = mongoose.model('rooms', roomsSchema);

module.exports = Rooms;