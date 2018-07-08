const Users = require(__defined.moduleUrl + 'Document/Users.js');
const UsersInRoom = require(__defined.moduleUrl + 'Document/UsersInRoom.js');

const SocketChatsClass = require(__defined.moduleUrl + 'Service/SocketChats');
const SocketChats = new SocketChatsClass();

const ChatsFuncSnippetsClass = require(__defined.moduleUrl + 'Service/ChatsFuncSnippets');
const ChatsFuncSnippets = new ChatsFuncSnippetsClass();

const mysqlConnClass = require(__defined.baseUrl + '/config/MysqlDBConn.js')
		, mysqlConn = new mysqlConnClass()
    , client = require(__defined.baseUrl + '/config/RedisConn.js');

module.exports = class ChatSocket{
	
	constructor(io, userSessInfo){

		this.userSessInfo = userSessInfo;
		this.socketConnect(io);
	}

	async getFindRoomsQueryResult(userSessInfo){
		
		let queryResult;

		await client.hgetAsync(userSessInfo.chatID, 'userID').then((userID) => {
        	
    	let query = `SELECT LCASE(rm.room_name) room_name
    							FROM user_in_rooms uir 
    							INNER JOIN rooms rm ON rm.id = uir.rooms_id 
    							WHERE uir.user_id = :userID`;

			queryResult = mysqlConn.queryOn(query, {userID: userID});
    });

    return queryResult;
	}

	socketConnect(io){

		let nspChats = io.of('/chats');

    console.log('CHATS PAGE');

    nspChats.on('connection', async (socket) => {

    	let userSessInfo = await this.userSessInfo(socket);

    	let findRoomsQueryResult = await this.getFindRoomsQueryResult(userSessInfo);

      console.log( 'CHATS PAGE: ' +userSessInfo.user.name + ' - socket.id: ');
      console.log(socket.id);
      console.log(Object.keys(io.sockets.sockets));

      ChatsFuncSnippets.updateOnlUsrTime({io});
      
      findRoomsQueryResult.on('result', function(row) {
        socket.join(row.room_name.replace(' ', '_'));
      });

      /*enter room start*/
      SocketChats.enterRoom(io, socket, userSessInfo);

      /* take message and send */
      SocketChats.broadcastMsg(io, socket, userSessInfo);

      /* Is typing */
      SocketChats.whoTyping(io, socket, userSessInfo);

      /*take private message and send*/
      SocketChats.privateMsg(io, socket, userSessInfo, '/chats#');
        
    }); //io connection end
	}
}