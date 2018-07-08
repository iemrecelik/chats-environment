<template>
	<div class="row room-list">
		<div class="col">
			<ul>
				<li :class="roomClassName(roomName)" 
				v-for="(total, roomName) in onlRoomsCount"
				@click="usersList(roomName)"
				>
					<div 
						:class="['btn btn-sm p-0 ld-ext-right', {'running': isEnterRoomLoading(roomName)}]"
					>
					  {{ roomName }}
					  <div class="ld ld-ring ld-spin"></div>
					  <i class="icon ion-android-checkmark-circle" 
					  	v-if="isEnteredRoom(roomName)"
					  ></i>
					</div>
					<span class="float-right">{{ total }}</span></li>
				</li>
			</ul>

			<hr/>
			
			<ul>
				<!--<li>Help</li>-->
				<li>Settings</li>
			</ul>

		</div>
	</div>
</template>

<script>
import { createNamespacedHelpers } from 'vuex'

const { mapState, mapMutations } = createNamespacedHelpers('chats')

export default {
  name: 'RoomsListComponent',
  data () {
    return {
      running: false,
    };
  },
  computed: {
	  ...mapState([
	  	'selectedRoom',
	  	'onlRoomsCount',
	  	'socket',
	  ]),
	  activeRoom: function(){
	  	return this.$store.state.authUser.room_name;
	  },
	},
	methods: {
		...mapMutations([
      'setSelectedRoom',
      'setChatMessagesList',
    ]),
	  usersList: function (roomName) {
	  	if(!this.running){
	  		this.setSelectedRoom(roomName);
		    this.running = true;
		    this.socket.emit('enterRoom', {roomName});

		    document.querySelector(`li.active-room`).classList.remove('active-room');
		    document.querySelector(
		    	`li.rm-${this.classNameConvert(roomName)}`
		    ).classList.add('active-room')

		    this.$store.commit('chats/setSearchUsers', {searchUsersName: ''	});
		    document.querySelector('input[type="search"]').value = '';

		    this.setChatMessagesList([]);
	  	}
	  },

	  roomClassName: function(roomName){
	  	let roomClass = `rm-${this.classNameConvert(roomName)}`;
	  	return this.activeRoom === roomName?`${roomClass} active-room`:roomClass;
	  },

	  classNameConvert: function(rawName){
	  	return rawName.replace(' ', '-').toLowerCase();
	  },

	  isEnterRoomLoading: function(roomName){
	  	return this.selectedRoom == roomName && this.running;
	  },

	  isEnteredRoom: function(roomName){
	  	return this.selectedRoom == roomName && !this.running;	
	  },
	},
	mounted(){
		this.socket.on('enter_room_running', async (data) => {
			this.running = false;
		});
	}
}
</script>