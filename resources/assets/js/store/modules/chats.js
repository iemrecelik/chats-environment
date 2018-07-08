const state = {
	onlRoomsCount: [],
	onlUsersInRooms: [],
	searchUsers: '',
	selectedRoom: 'general',
	selectedProfile: {},
	activeTab: 'settings',
	socket: null,
	onlineTimeList: {},
	chatMessagesList: [],
	privateMessagesList: [],
	usersInPrivateChats: [],
};

const getters = {
	usersListInCurrentRoom: state => {
    let userIDs = [];
  	let onlUsersInRooms = state.onlUsersInRooms.filter((user) => {
  		let acc = user.room_name === state.selectedRoom 
  			&& userIDs.indexOf(user.chatID) < 0;

  		userIDs.push(user.chatID);

  		return acc;
  	});
		
  	return onlUsersInRooms.sort(
      (a, b) => a.name.toString().localeCompare(b.name.toString())
    );
  }
};

const mutations = {
	setOnlRoomsCount(state, payload){
		state.onlRoomsCount = payload.rooms;
	},
	changeRoomsCount(state, roomsCount){
		Object.assign(state.onlRoomsCount,  roomsCount);
	},
	incrementRoomCount(state, room){
		state.onlRoomsCount[room]++;
	},
	decrementRoomCount(state, room){
		state.onlRoomsCount[room]--;
	},
	setOnlUsersInRooms(state, payload){
		state.onlUsersInRooms = payload.onlUsersInRooms;
	},
	setSearchUsers(state, payload){
		state.searchUsers = payload.searchUsersName;
	},
	setSelectedProfile(state, payload){
		state.selectedProfile = payload.user;
	},
	removeSelectedProfile(state){
		state.selectedProfile = {};
	},
	setActiveTab(state, queryTab){
		state.activeTab = queryTab || 'settings';
	},
	setSelectedRoom(state, selectedRoom){
		state.selectedRoom = selectedRoom;
	},
	setSocket(state, socket){
		state.socket = socket;
	},
	setOnlineTimeList(state, onlineTimeList){
		state.onlineTimeList = onlineTimeList;
	},
	setChatMessagesList(state, chatMessagesList){
		state.chatMessagesList = chatMessagesList;
	},
	setUsersInPrivateChats(state, usersInPrivateChats){
		state.usersInPrivateChats = usersInPrivateChats;
	},
	addPrivateMessagesList(state, message){
		state.privateMessagesList.push(message);
	},
};

const actions = {
  setSelectedProfile ({commit, state}, chatID) {

  	let user = state.onlUsersInRooms.find((user) => {
  		return user.chatID === chatID
  	});
    commit('setSelectedProfile', {user})
  },
  addOnlUsersInRooms({commit, state}, {newUser, type = false}) {

  	if(type){

  		let found = _.findIndex(state.onlUsersInRooms, (user) => {
	  		return user.chatID == newUser.chatID;
	  	});

	  	if(found < 0){
	 			
	 			state.onlUsersInRooms.push(newUser);

	  	}else if(type === 'overwrite'){

	  		state.onlUsersInRooms.splice(found, 1);
	  		state.onlUsersInRooms.push(newUser);
	  	}
	  	
  	}else
  		state.onlUsersInRooms.push(newUser);

  	commit('setOnlUsersInRooms', {
			'onlUsersInRooms': state.onlUsersInRooms
		});
  },
  delOnlUsersInRooms({commit, state}, chatID) {

  	let onlUsersInRooms = state.onlUsersInRooms.filter((user) => {
  		return user.chatID != chatID;
  	});
  	commit('setOnlUsersInRooms', {onlUsersInRooms});
  },
  addChatMessagesList({commit, state}, message){
  	
  	state.chatMessagesList.push(message);
  	commit('setChatMessagesList', state.chatMessagesList);
  },
  addUsersInPrivateChats({commit, state}, newUser){

  	let find = state.usersInPrivateChats.some((user) => {
  		return user.chatID === newUser.chatID
  	});

  	if(!find){
  		state.usersInPrivateChats.push(newUser);
  		commit('setUsersInPrivateChats', state.usersInPrivateChats);
  	}
  },
  delUsersInPrivateChats({commit, state}, delUser){

  	let usersInPrivateChats = state.usersInPrivateChats.filter((user) => {
  		user.chatID != delUser.chatID;
  	})
  	commit('setUsersInPrivateChats', usersInPrivateChats);
  },
};

export default {
	state,
	getters,
	mutations,
	actions,
	namespaced: true
}