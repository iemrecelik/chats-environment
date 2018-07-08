var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var userSchema = new Schema({
    name: String,
    surname: String,
    nickname: String,
    bio: String,
    email: String,
    mobile: Number,
    password: String,
    hide: Boolean,
    user_id: { type: Schema.Types.ObjectId, unique: true },
});

var UserInfo = mongoose.model('userInfo', userSchema);

module.exports = UserInfo;