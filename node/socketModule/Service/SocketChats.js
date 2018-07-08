const Users = require(__defined.moduleUrl + 'Document/Users.js');
const Rooms = require(__defined.moduleUrl + 'Document/Rooms.js');
const MsgRegisters = require(__defined.moduleUrl + 'Document/MsgRegisters.js');
const UsersInRoom = require(__defined.moduleUrl + 'Document/UsersInRoom.js');

const mysqlConnClass = require(__defined.baseUrl + '/config/MysqlDBConn.js')
    , mysqlConn = new mysqlConnClass()
    , client = require(__defined.baseUrl + '/config/RedisConn.js');

// const ChatsFuncSnippets = __ctrl.getClass('ChatsFuncSnippets');
const ChatsFuncSnippetsClass = require(__defined.moduleUrl + 'Service/ChatsFuncSnippets');
const ChatsFuncSnippets = new ChatsFuncSnippetsClass();

module.exports = class SocketChats{

    constructor(){
        this.userPeerSignals = [];
        this.waitSocketNames = {};
    }

    async leaveRoom(socket, userID){

        let oldRoomRes = await mysqlConn.query(
            `   
                SELECT rm.id roomsID, LCASE(rm.room_name) roomName
                FROM rooms rm
                INNER JOIN user_in_rooms uir 
                ON uir.rooms_id = rm.id
                WHERE uir.user_id = :userID
            `, 
            {userID}
        );

        let {roomName} = oldRoomRes.results[0];

        socket.leave(roomName.replace(" ", "_"));

        return roomName;        
    }

    enterRoom(io, socket, userSessInfo) {
        
        socket.on('enterRoom', async (data) => {

            await new Promise(function(resolve, reject) {
              setTimeout(() => {resolve()}, 1000);
            });

            let running = await client.hgetAsync(
                                    'chat:enter_room_running', 
                                    userSessInfo.rawChatID
                                );

            let existRooms = Object.keys(socket.rooms);
            
            data.roomName = data.roomName.toLowerCase();

            let noSpaceRoomName = data.roomName.replace(" ", "_");
            let isRunning = running === 'false' || !running;

            if (existRooms.indexOf(noSpaceRoomName) === -1 && isRunning) {

                client.hset(
                    'chat:enter_room_running', 
                    userSessInfo.rawChatID,
                    true,
                    function(err, result){ if(err) console.log(err); }
                );

                let isRoomQuery = ` SELECT * FROM rooms rm 
                                    WHERE rm.room_name = :roomName`;
                
                let isRoom = await mysqlConn.query(
                    isRoomQuery, 
                    {'roomName': data.roomName}
                );

                if(isRoom.results.length){

                    let oldRoomName = await this.leaveRoom(socket, userSessInfo.userID);
                    
                    socket.join(noSpaceRoomName);

                    let isRoomQuery = ` UPDATE user_in_rooms 
                                        SET rooms_id = :roomsID
                                        WHERE user_id = :userID`
                    
                    await mysqlConn.query(
                        isRoomQuery, 
                        {
                            'userID': userSessInfo.userID,
                            'roomsID': isRoom.results[0].id,
                        }
                    );

                    userSessInfo.user.room_name = data.roomName;
                    socket.broadcast.emit('changeRoom', {
                        user: userSessInfo.user, 
                        roomName: data.roomName, 
                        oldRoomName: oldRoomName
                    });
                }

                client.hset(
                    'chat:enter_room_running', 
                    userSessInfo.rawChatID,
                    false,
                    function(err, result){ if(err) console.log(err); }
                );
            }

            socket.emit('enter_room_running', data.roomName);
        });
    }

    broadcastMsg(io, socket, userSessInfo){
        socket.on('sendMessagBox', function(data) {
            
            let date = new Date();

            MsgRegisters.create({
                msgRegContent: data.msg,
                roomName: data.roomName,
                userName: userSessInfo.user.name,
                msgSend: date.getTime(),
            });

            io.of('chats').in(data.roomName.replace(' ', '_')).emit('takeMsg', {
                'user': userSessInfo.user,
                'msgSendTime': ChatsFuncSnippets.getNowTimeFormat(),
                'roomName': data.roomName,
                'msg': data.msg,
            });
        });
    }

    whoTyping(io, socket, userSessInfo){
        socket.on('typing', async (data) => {

            if(!data.value){
                client.zrevrange(
                    ['is_typing', 0, 0, 'WITHSCORES'], 
                    function(err, result){
                        if(err) console.log(err);
                        
                        if(userSessInfo.rawChatID == result[0]){
                            io.of('chats').in(data.roomName.replace(' ', '_')).emit('whoTyping', {
                                'nickname': userSessInfo.user.nickname,
                                'dontShowWhoTyping': true,
                            });            
                        }
                    }
                );

                
            }else{
                this.showWhoTyping(io, userSessInfo, data);   
            }
        });
    }

    async showWhoTyping(io, userSessInfo, data){
        let nowTime = new Date().getTime();
        let oldTime = await client.zscoreAsync(
            'is_typing', 
            userSessInfo.rawChatID
        ) || 0;
        
        if((nowTime - oldTime) > 3000){
            client.zadd(
                [
                    'is_typing', 
                    nowTime, 
                    userSessInfo.rawChatID, 
                ],
                function(err, result){ if(err) console.log(err); }
            );

            io.of('chats').in(data.roomName.replace(' ', '_')).emit('whoTyping', {
                'nickname': userSessInfo.user.nickname,
            });
        }
    }

    privateMsg(io, socket, userSessInfo, namespace){

        socket.on('sendPrivateMsg', (data) => {

            client.hget(`chat_unique_id:${data.chatID}`, 'socketID', (err, socketID) => {
                
                if(err) console.log(err);

                if(socketID){

                    let receiveSocketID = namespace + socketID.toString();
                    let time = ChatsFuncSnippets.getNowTimeFormat();

                    socket.to(receiveSocketID).emit('takePrivateMsg', {
                        'chatID': userSessInfo.user.chatID,
                        'user': userSessInfo.user,
                        'msg': data.msg,
                        'selfMsg': true,
                        'time': time
                    });

                    socket.emit('takePrivateMsg', {
                        'chatID': data.chatID,
                        'user': userSessInfo.user,
                        'msg': data.msg,
                        'time': time
                    });    
                }
            });
        });//socket on end
    }

    socketWait(socketName){

        this.waitSocketNames[socketName] = this.waitSocketNames[socketName] || 0;

        let wait =  Date.now() - this.waitSocketNames[socketName] > 5000?true:false;
        
        if(wait)
            this.waitSocketNames[socketName] = Date.now();

        return wait;
    }

    sendReqCamera(socket, session){

        socket.on('sendReqCamera', (data) => {

            if(this.socketWait('sendReqCamera')){

                this.getUserSocketIDAndAvatar(data.userName).then((reqUserInfo) => {
                
                    let receiveSocketID = '/chats#' + reqUserInfo.socketId.toString();

                    socket.to(receiveSocketID).emit('reqCamera', {
                        reqUserName: session.user.name,
                    });
                });
            }//end if sendreqwait
        })
    }
    //receive
    answerReqCamera(socket, userName){
        
        socket.on('answerToReq', (data) => {

            if(this.socketWait('answerToReq')){
            
                this.getUserSocketIDAndAvatar(data.reqUserName).then((reqUserInfo) => {

                    let receiveSocketID = '/chats#' + reqUserInfo.socketId.toString();

                    if(data.answer){
                        
                        if(this.duplicatePeerSignalControl(data.peerConnSignal, socket, data.reqUserName)){

                            socket.to(receiveSocketID).emit('accReqCamera', {
                                receiveUserName: userName,
                                peerConnSignal: data.peerConnSignal
                            });

                        }// end if duplicate peer signal

                    }else{

                        socket.to(receiveSocketID).emit('rejReqCamera', {
                            answer: 'false',
                            userName: userName
                        });
                    }//end if answer
                });
            }//end if socket wait
        })
    }
    //sender
    sendReceiveSignalCamera(socket, userName){
        
        socket.on('sendReceiveSignal', (data) => {
            
            if(this.duplicatePeerSignalControl(data.peerConnSignal, socket, data.receiveUserName)){

                this.getUserSocketIDAndAvatar(data.receiveUserName).then((reqUserInfo) => {

                    let receiveSocketID = '/chats#' + reqUserInfo.socketId.toString();                    

                    socket.to(receiveSocketID).emit('takeReceiveSignal', {
                        reqUserName: userName,
                        peerConnSignal: data.peerConnSignal
                    });
                });
            }// end if duplicate peer signal
        });
    }

    duplicatePeerSignalControl(signal, socket, userName = null){
        
        let control = false;
        
        if( this.userPeerSignals.indexOf(signal) > -1){

            let msg = `${userName} kullanıcısıyla bağlantınız vardır.
            Lütfen tarayıcınızı yenileyiniz. Ve tekrar istek göderin.`;

            control = false;
            socket.emit('socketErrorMsg', {msg: msg, userName: userName})
            
        }else if( this.userPeerSignals.length > 10 ){

            let msg = `10 taneden fazla video görüntü açamazsınız.`;

            control = false;
            socket.emit('socketErrorMsg', {msg: msg, userName: userName})

        }else{
            this.userPeerSignals.push(signal);
            control = true;
        }

        return control;
    }

    delPeerSignal(socket){
        socket.on('delPeerSignal', (data) => {
            this.userPeerSignals.splice(this.userPeerSignals.indexOf(data.signal), 1);
        })
    }

    getUserSocketIDAndAvatar(userName){
        
        return Users.findOne(
            { 
                'userName': userName, 
                $or: [
                    {'online': 1 },
                    {'online': 2 },
                ]
            },
            { 
                'socketId': 1,
                'avatar': 1
            }
        );
    }

    disconnect(io, socket, updateOnlUsrTime){

        socket.on('disconnect', () => {
            
            clearInterval(updateOnlUsrTime);
        });
    }
}