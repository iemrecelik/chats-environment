<template>
	<div class="row chats-row">
		<div class="col-2 rooms">
			<rooms-add-component></rooms-add-component>
			<rooms-list-component></rooms-list-component>		
		</div>
		<div class="col-3 users">
			<users-search-component></users-search-component>
			<users-list-component></users-list-component>
		</div>
		<div class="col-5 chats">
			<chats-title-component></chats-title-component>
			<chats-content-component></chats-content-component>
		</div>
		<div class="col-2 profile">
			<profile-menu-component></profile-menu-component>
			<selected-profile-info-component></selected-profile-info-component>
		</div>
	</div>
</template>

<script>
import roomsAddComponent from './RoomsAddComponent';
import roomsListComponent from './RoomsListComponent';
import usersSearchComponent from './UsersSearchComponent';
import usersListComponent from './UsersListComponent';
import chatsTitleComponent from './ChatsTitleComponent';
import chatsContentComponent from './ChatsContentComponent';
import profileMenuComponent from './ProfileMenuComponent';
import selectedProfileInfoComponent from './SelectedProfileInfoComponent';

import { createNamespacedHelpers } from 'vuex'

const { mapState, mapMutations, mapActions } = createNamespacedHelpers('chats')

export default {
	name: 'ChatsComponent',
	props: {
	  pponlroomscount: {
	    type: Object,
	    required: true,
	  },
	  pponlusersinrooms: {
	    type: Array,
	    required: true,
	  },
	  pproutes: {
	    type: Object,
	    required: true,
	  },
	  ppauthuser: {
	    type: Object,
	    required: true,
	  },
	},
	computed: {
	  ...mapState([
		  'onlUsersInRooms'
		]),
	},
	methods: {
    ...mapMutations([
      'setSelectedRoom',
      'setOnlUsersInRooms',
      'setOnlRoomsCount',
      'setOnlineTimeList',
      'incrementRoomCount',
      'decrementRoomCount',
    ]),
    ...mapActions([
      'addOnlUsersInRooms', 
      'delOnlUsersInRooms', 
    ]),
    isOnlUser(chatID){
			return _.findIndex(this.onlUsersInRooms, (user) => {
	  		return user.chatID == chatID;
	  	});
    },
  },
  created(){
  	this.setOnlUsersInRooms({ onlUsersInRooms: this.pponlusersinrooms });
  	this.setOnlRoomsCount({ rooms: this.pponlroomscount });

  	this.$store.commit('setRoutes', this.pproutes);
  	this.$store.commit('setAuthUser', this.ppauthuser);
  	this.setSelectedRoom(this.ppauthuser.room_name);

  	const socket = io(
  		'37.148.210.93:3333/chats', 
  		{query: `chatID=${this.ppauthuser.chatID}`}
  	);

  	this.$store.commit('chats/setSocket', socket);

	  socket.on('updateOnlUsrTime', (onlineTimeList) => {
	  	this.setOnlineTimeList(onlineTimeList);
	  });

		socket.on('setUser', (user, roomName) => {

	  	if(this.isOnlUser(user.chatID) < 0){
	 			this.incrementRoomCount(roomName);
				this.addOnlUsersInRooms(
					{newUser: user, type: 'overwrite'}
				);
	  	}	
	  });

	  socket.on('changeRoom', ({user, roomName, oldRoomName}) => {

 			this.incrementRoomCount(roomName);
 			this.decrementRoomCount(oldRoomName);
			this.addOnlUsersInRooms(
				{newUser: user, type: 'overwrite'}
			);
	  });

		socket.on('unsetUser', (chatID, roomName) => {

			if(this.isOnlUser(chatID) > -1){
				this.decrementRoomCount(roomName);
				this.delOnlUsersInRooms(chatID);
			}
	  });	  

  },
	components: {
		'rooms-add-component': roomsAddComponent,
		'rooms-list-component': roomsListComponent,
		'users-search-component': usersSearchComponent,
		'users-list-component': usersListComponent,
		'chats-title-component': chatsTitleComponent,
		'chats-content-component': chatsContentComponent,
		'profile-menu-component': profileMenuComponent,
		'selected-profile-info-component': selectedProfileInfoComponent,
	}
}
</script>