const mongoose = require('mongoose');
const Schema = mongoose.Schema;

var msgRegisterSchema = new Schema({
	msgRegContent: String,
	roomName: String,
	userName: String,
	msgSend: Number,
});

var MsgRegister = mongoose.model('MsgRegister', msgRegisterSchema);

module.exports = MsgRegister;