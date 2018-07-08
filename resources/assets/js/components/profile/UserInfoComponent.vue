<template>
	<div class="user-info">
		<div>
			<img :src="getImageFiltUrl(authUser.path, '_2')" :alt="authUser.fullName + ' profile image'">
		</div>
		<div><h5 v-html="fullName"></h5></div>
		<div>
			<p v-html="authUser.bio">.</p>
		</div>
		<div>
			<form :action="routes.updateImage" method="POST" 
			enctype="multipart/form-data">
	
				<div class="input-group mb-3">
				  <div class="custom-file">
				    <input type="file" 
				    	class="custom-file-input" 
				    	id="inputUploadImage" 
				    	name="profileImg"
				    	@change="fileInputChangeName($event)"
				    />
				    <label class="custom-file-label text-left" for="inputUploadImage">
				    	Choose file
				    </label>
				  </div>
				  <div class="input-group-append">
				    <button class="btn btn-primary" type="submit">Upload</button>
				  </div>
				</div>
				<input type="hidden" name="_token" :value="token"/>
			</form>

		</div>
		<succeed-msg-component :ppcomponent="'userInfoComp'">
		</succeed-msg-component>
		
    <error-msg-list-component :ppcomponent="'userInfoComp'">
    </error-msg-list-component>
	</div>
</template>

<script>
import { mapState } from 'vuex';

export default {
	name: 'UserInfoComponent',
	computed: {
		...mapState([
			'authUser',
			'routes',
			'token',
		]),
	  fullName: function(){
			let surname = this.authUser.surname || '';
			return `${this.authUser.name} ${surname}`;
		}
	},
	methods: {
	  fileInputChangeName: function (event) {
	  	let fileName = event.target.value.match(/\w+\.\w+/g)[0];
	  	event.target.nextElementSibling.innerText = fileName;
	  },
	}
}
</script>