var mongoose = require('mongoose');
var Schema = mongoose.Schema;

mongoose.Promise = require('bluebird');

var usersInRoomSchema = new Schema({
    userName: { type: String, required: true },
    roomName: { type: String, required: true },
});

var UsersInRoom = mongoose.model('usersInRoom', usersInRoomSchema);

module.exports = UsersInRoom;