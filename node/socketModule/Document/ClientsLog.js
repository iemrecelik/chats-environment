const mongoose = require('mongoose');
const Schema = mongoose.Schema;

var clientsLogSchema = new Schema({
	uniqueID: String,
	ip: String,
	email: { type: String, unique: true },
	method: String,
	status: Number,
	path: { type: String, required: true },
	enteredAt: Date,
	exitedAt: Date,
});

var ClientsLog = mongoose.model('ClientsLog', clientsLogSchema);

module.exports = ClientsLog;