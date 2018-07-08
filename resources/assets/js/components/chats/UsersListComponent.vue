<template>
	<div class="row users-list">
		
		<chats-private-dialog-box 
			v-for="user in usersInPrivateChats" 
			:key="user.chatID"
			:ppuser="user"
		>
		</chats-private-dialog-box>

		<div class="col">
			<ul class="list-unstyled">
				<li class="media my-3"
					v-if="userShow(user.name)" 
					v-for="user in usersListInCurrentRoom"
				>
					<img class="mr-3" :src="getImageFiltUrl(user.path, '_3')" alt="Generic placeholder image"/>
					<!-- Sign online start-->
					<!--<div :class="onlineStatus(user.online)"></div>-->
					<!-- Sign online end-->
					<div class="media-body">
						<div>
							<b>{{ user.name }}</b>
							<div class="float-right">
								<div class="dropdown">
									<a id="userOptions" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									  <i class="icon ion-more" 
									  v-tooltip="'Show profile options'"></i>
									</a>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									  
									  <span class="dropdown-item" href="#" 
									  	@click="changeSelectedProfile(user.chatID)"
									  >
											Profile show
										</span>
									  
									  <span class="dropdown-item" href="#" 
									  	@click="openPrivateDialogBox(user)"
									  >
									  	Private chats
										</span>
									</div>
								</div>
							</div>
						</div>
						<div>
							<span class="brief float-left">{{ user.brief }}</span>
							<div class="float-right user-online-time">{{ getOnlineTime(user.chatID) }}</div>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
</template>

<script>
import privateDialogBoxComponent from './PrivateDialogBoxComponent';

import { createNamespacedHelpers } from 'vuex'

const { mapState, mapMutations, mapActions, mapGetters } = createNamespacedHelpers('chats')

export default {
	name: 'UsersListComponent',
	computed: {
	  ...mapState([
	  	'searchUsers',
	  	'onlineTimeList',
	  	'onlUsersInRooms',
	  	'usersInPrivateChats',
	  	'socket',
	  ]),
	  ...mapGetters([
      'usersListInCurrentRoom',
    ]),
	},
	methods: {
		...mapActions([
	  	'setSelectedProfile',
	  	'addUsersInPrivateChats',
	  	'delUsersInPrivateChats',
	  ]),
		...mapMutations([
	  	'addPrivateMessagesList',
	  ]),
	  onlineStatus (online) {
	    return online > 0 ?'online-mark':'disconnect-mark';
	  },
	  userShow(userName, path){

	  	let uname = userName.toLowerCase();
	  	let search = this.searchUsers.toLowerCase();

	  	return search === '' || uname.search(search) > -1?true:false;
	  },
	  changeSelectedProfile(chatID){
	  	this.setSelectedProfile(chatID);
	  },
	  aLinkDisable(event){
	  	event.preventDefault()
	  },
	  getOnlineTime: function(chatID) {
    	return this.convertTime(this.onlineTimeList[chatID]);
    },
    openPrivateDialogBox: function(user){
    	this.addUsersInPrivateChats(user);
    }
	},
	mounted(){
		this.socket.on('takePrivateMsg', async (data) => {

			if(data.selfMsg){
				let find = await this.usersInPrivateChats.some((user) => {
					return user.chatID == data.user.chatID;
				});

				if(!find)
					await this.openPrivateDialogBox(data.user);	
			}
			
      this.addPrivateMessagesList(data);
    });
	},
	components: {
		'chats-private-dialog-box': privateDialogBoxComponent,
	},
}
</script>