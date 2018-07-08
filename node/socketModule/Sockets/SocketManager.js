const ChatSocket = require(__defined.moduleUrl + 'Sockets/ChatSocket.js')
	, SocketCheckInAndOut = require(__defined.moduleUrl + 'Sockets/SocketCheckInAndOut.js')
	, client = require(__defined.baseUrl + '/config/RedisConn.js');


module.exports = function({io}){

	this.userSessInfo = async (socket) => {
		
    let userInfo = {};
        
    userInfo.rawChatID = socket.handshake.query.chatID;
    userInfo.chatID = `chat_unique_id:${userInfo.rawChatID}`;

    await client.hgetallAsync(userInfo.chatID).then((userInfoCache) => {

        userInfo = Object.assign(userInfo, userInfoCache);
        userInfo.user = JSON.parse(userInfoCache.user);
        userInfo.user['room_name'] = userInfoCache.rooms.split(',')[0];
    });

    return userInfo;
	}

	new ChatSocket(io, this.userSessInfo);
	new SocketCheckInAndOut(io, this.userSessInfo);
}