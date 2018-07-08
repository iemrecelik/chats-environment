var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var userSchema = new Schema({
    userName: { type: String, unique: true },
    userIp: String,
    loginTime: Number,
    logOutTime: Number,
    role: String,
    avatar: String,
    socketId: String,
    nps: Array,
    online: { type: Number, default: 0 },
    connectTime: Number,
    disconnectTime: Number,
});

var User = mongoose.model('user', userSchema);

module.exports = User;