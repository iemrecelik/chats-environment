<template>
	<div class="row chats-list">
		<div class="col-12 room-chats-box">
			<ul class="list-unstyled">

				<li class="media my-3"
					v-for="message in chatMessagesList" 
				>
					<div class="row w-100 no-gutters" 
						v-if="message.user.chatID === authUser.chatID"
					>
						<div class="mr-3">
							<img class="bubble-img" alt="avatar"
								:src="getImageFiltUrl(message.user.path, '_3')"
							/>
							<p class="text-center m-0">{{ message.user.nickname }}</p>
						</div>
						<div class="media-body">
							<div class="bubble">
								{{ message.msg }}
								<p class="text-right mb-0 mt-2">
									{{message.msgSendTime}}
								</p>
							</div>
						</div>
					</div>
					<div class="row w-100 no-gutters" v-else>
						<div class="media-body">
							<div class="bubble-left">
								{{ message.msg }}
								<p class="text-right mb-0 mt-2 text-white">
									{{message.msgSendTime}}
								</p>
							</div>
						</div>
						<div class="ml-3">
							<img class="bubble-img" alt="avatar"
								:src="getImageFiltUrl(message.user.path, '_3')"
							/>
							<p class="text-center m-0">{{ message.user.nickname }}</p>
						</div>
					</div>
				</li>
			</ul>	
		</div>
		<div class="col-12 p-3 sender">
			<div class="row">
				<!--<div class="col-1 float-left">
					<i class="icon ion-android-attach"></i>
				</div>-->
				<div class="col-10 float-left">
					<input id="message-box" type="text" 
						placeholder="Type your message..."
						@keyup.enter="sendMessage($event)"
						@keyup="isTyping"
					/>
				</div>
				<div class="col-2 float-left send-btn"
					@click="sendMessage($event, 'message-box')"
				>
					<div><i class="icon ion-android-send"></i></div>
				</div>	
			</div>
		</div>
	</div>
</template>

<script>
import { createNamespacedHelpers } from 'vuex'

const { mapState, mapActions } = createNamespacedHelpers('chats')

export default {
	name: 'ChatsContentComponent',
	computed: {
	  ...mapState([
	  	'selectedRoom',
	  	'socket',
	  	'chatMessagesList',
	  ]),
	  authUser: function(){
	  	return this.$store.state.authUser;
	  }
	},
	methods: {
		...mapActions([
      'addChatMessagesList',
    ]),
	  sendMessage: function (event = null, takeMesInElement = null) {
	  	
	  	let element;
	  	if(takeMesInElement)
	  		element = document.getElementById(takeMesInElement);
	  	else
	  		element = event.target;

	    this.socket.emit('sendMessagBox', {
	    	msg: element.value,
	    	roomName: this.selectedRoom,
	    });

	    element.value = '';
	  },
	  isTyping: function(event){
	  	if(event.which != 8 || !event.target.value ){
	  		this.socket.emit('typing', {
		  		keyCode: event.which,
		  		value: event.target.value,
		  		roomName: this.selectedRoom,
		  	});	
	  	}
	  }
	},
	mounted(){
		this.socket.on('takeMsg', (data) => {
			this.addChatMessagesList(data);
		});

		$('div.room-chats-box').bind("DOMSubtreeModified",function(){
      $(this).animate({scrollTop: $(this).prop("scrollHeight")}, 500);
    });
	},
}
</script>