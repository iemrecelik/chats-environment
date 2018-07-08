<template>
	<div class="row tools-title">
		<div class="col typing">
		</div>
		<div class="col">
		</div>
	</div>
</template>

<script>
export default {
  name: 'ChatsTitleComponent',
  computed: {
    socket: function () {
      return this.$store.state.chats.socket;
    }
  },
	mounted(){
		this.socket.on('whoTyping', (data) => {
			if(data.dontShowWhoTyping){
				document.querySelector('div.typing').innerHTML = '';
			}else{
				let html = `<b>${data.nickname}</b> is typing...`;
				document.querySelector('div.typing').innerHTML = html;	
			}
		});
	},
}
</script>