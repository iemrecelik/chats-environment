<template>
	<div class="profile-dropbox">
		<div class="dropdown">
			<button class="btn btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				{{ fullName }}
			</button>
			<div class="dropdown-menu dropdown-menu-right p-2 text-muted">
				<div class="profile-img">
					<img :src="getImageFiltUrl(authUser.path)" alt=""/>
				</div>
				
				<div class="my-0">
					<a :href="routes.chats" class="badge badge-primary">
						<i class="icon ion-chatboxes"></i> Chats
					</a>
					<a :href="routes.profile" class="badge badge-primary">
						<i class="icon ion-person"></i> Profile
					</a>
				</div>
				<div class="mb-0">
					<a :href="routes.changePassword" 
					class="badge badge-primary">
						<i class="icon ion-locked"></i> Change Password
					</a>
				</div>
				<div class="mb-0">
					<a href="#" class="badge badge-danger"
						v-on:click.stop.prevent="onSubmit"
					>
						<i class="icon ion-locked"></i> Logout
					</a>
					<form id="logout-form" :action="routes.logout" method="POST" style="display: none;">
	          <input type="hidden" name="_token" :value="token">
	        </form>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapState } from 'vuex';

export default {
	name: 'SettingsDropdownComponent',
	computed: {
		...mapState([
			'routes',
			'authUser',
			'token',
		]),
		fullName: function(){
			let surname = this.authUser.surname || '';
			return `${this.authUser.name} ${surname}`;
		}
	},
	methods: {
	  onSubmit: function () {
	    document.getElementById('logout-form').submit();
	  }
	},
}
</script>