const ClientsLog = require(__defined.moduleUrl + 'Document/ClientsLog.js');

const mysqlConnClass = require(__defined.baseUrl + '/config/MysqlDBConn.js')
    , mysqlConn = new mysqlConnClass()
    , client = require(__defined.baseUrl + '/config/RedisConn.js');

const ChatsFuncSnippetsClass = require(__defined.moduleUrl + 'Service/ChatsFuncSnippets');
const ChatsFuncSnippets = new ChatsFuncSnippetsClass();


module.exports = class SocketCheckInAndOut{

    constructor(io, userSessInfo){
        this.allConnectProcesses(io);
        this.socketPermission(io);

        setInterval(() => {
            ChatsFuncSnippets.updateOnlUsrTime({io});
        }, 60000);
    }

    async setUserInfo(socket){

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

    async allConnectProcesses(io){

        io.on('connection', async (socket) => {

            let {rawChatID, chatID, user, loginTime} = await this.setUserInfo(socket);

            console.log('BASE SOCKET');

            await this.otherSocketsDisconn(io, chatID);

            /*run when connection start*/
            this.firstConnect(io, socket, {user, chatID, rawChatID, loginTime});

            /*run when disconnect start*/
            this.disconnect(io, socket, {user, chatID, rawChatID});
        });
    }

    async otherSocketsDisconn(io, chatID){

        await client.hgetAsync(chatID, 'socketID').then((socketID) => {

            if(io.sockets.sockets[socketID])
                io.sockets.sockets[socketID].disconnect(true);

        }).catch((err) => {
             console.log('ERROR : ', err);
        });
    }

    async firstConnect(io, socket, userInfo){

        client.hmset(userInfo.chatID, {
            'onConnectTime': new Date().getTime(),
            'socketID': socket.id,
        });

        client.hset(
            'chat:online', 
            userInfo.rawChatID, 
            userInfo.loginTime, 
            function(err, result){ if(err) console.log(err); }
        );

        io.of('chats').emit('setUser', userInfo.user, userInfo.user.room_name);
    }

    disconnect(io, socket, userInfo){

        socket.on('disconnect', async () => {

            client.hset(userInfo.chatID, 'disconnectTime', new Date().getTime());
            client.hdel('chat:online', userInfo.rawChatID);

            io.of('chats').emit('unsetUser', userInfo.rawChatID, userInfo.user.room_name);
        }); //io disconnect end
    }

    disconnectSetTimeout(session, io, socket){

        setTimeout(() => {

            let UserTime = Users.findOne(
                {
                    'userName': session.user.name 
                }, 
                {
                    'connectTime': 1, 
                    'disconnectTime': 1,
                }
            );
            UserTime.then((userTimeData) => {

                let nowTime = new Date().getTime()

                console.log('--------')
                console.log(nowTime - userTimeData.disconnectTime);
                console.log(nowTime - userTimeData.connectTime);
                console.log(this.connectControlTime);
                console.log('--------')
                if( (nowTime - userTimeData.disconnectTime) > this.connectControlTime &&
                    (nowTime - userTimeData.connectTime) > this.connectControlTime ){

                    Users.update(
                        { 'userName': session.user.name }, 
                        { $set: { 'online': 0, 'logOutTime': nowTime }}, (err) => {

                        if (err) console.log(err);
                        
                        UsersInRoom.deleteMany(
                            { 
                                'userName': session.user.name, 
                            }, 
                            (err) => {
                                if (err) console.log(err);
                                io.of('/chats').emit('offUser',session.user.name);
                                ChatsFuncSnippets.emitRoomCount(null, io);
                                this.sessStoreProcesses.sessionStoreDestroy(socket);
                                console.log('Done exit');
                            }
                        );// UsersInRoom deleteMany end
                    });// Update end
                }//if end
                else{
                    console.log('it is not exit');
                }

            });//findOne end

            global.__stopRun[session.user.name] = true;

        }, (this.connectControlTime + 100) );

        global.__stopRun[session.user.name] = false;
    }

    socketPermission(io){

        io.origins((origin, callback) => {
            
            if (['http://37.148.210.93:8282', 'http://37.148.210.93:8282/profile'].indexOf(origin) < 0) {
                return callback('origin not allowed', false);
            }
            callback(null, true);
        });
    }
}
