<template>
	<div class="row tools-title">
		<div class="col">
			<div class="input-group input-group-sm mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroup-sizing-sm"></span>
				</div>
				<input 
				@keyup="setStoreSearchUsers" 
				v-model="searchUsersName" 
				type="search" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="Search"
				/>
			</div>
		</div>
	</div>
</template>

<script>

export default {
	name: 'UsersSearchComponent',
	data () {
	  return {
	    searchUsersName: '',
	  };
	},
	methods: {
	  setStoreSearchUsers () {
	  	this.$store.commit('chats/setSearchUsers', {
	  		searchUsersName: this.searchUsersName	
	  	});
	  },
	},
}
</script>