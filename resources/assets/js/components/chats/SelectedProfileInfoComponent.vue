<template>
	<div>
		<div class="row selected-profile-img">
			
			<button type="button" class="close" aria-label="Close"
				v-if="isEmptySelectedProfile" 
				v-tooltip="'Close Selected Profile'"
				@click="removeSelectedProfile"
			>
			  <span aria-hidden="true">&times;</span>
			</button>	

			<div>
				<img alt="b1-profile-jpg"
					:src="getImageFiltUrl(userProfile.path, '_1')" 
				/>
				<div class="text-center">
					<h6 class="mb-0 mt-1"><b>{{ fullName }}</b></h6>
					<p class="mb-0">{{ cityAndCountry }}</p>
				</div>
			</div>
		</div>
		<div class="row selected-profile-info">
			<ul class="list-group">
				<li class="list-group-item">
					<span class="float-left"><b>Nickname: </b></span>
					<span class="float-right">{{ userProfile.nickname }}</span>
				</li>
				<li class="list-group-item">
					<span class="float-left"><b>Tel: </b></span>
					<span class="float-right">{{ userProfile.mobile }}</span>
				</li>
				<li class="list-group-item">
					<span class="float-left"><b>Date Of Birth: </b></span>
					<span class="float-right">{{ timeToDate(userProfile.date_of_birth) }}</span>
				</li>
				<li class="list-group-item">
					<span class="float-left"><b>Gender: </b></span>
					<span class="float-right">{{ userProfile.gender | genderLongName }}</span>
				</li>
				<li class="list-group-item">
					<span class="float-left"><b>Language: </b></span>
					<span class="float-right">{{ userProfile.language }}</span>
				</li>
			</ul>
		</div>
	</div>
</template>

<script>
import { createNamespacedHelpers } from 'vuex'

const { mapState } = createNamespacedHelpers('chats')

export default {
	name: 'SelectedProfileInfoComponent',
	computed: {
	  ...mapState([
			'selectedProfile',
	  ]),
	  authUser: function(){
	  	return this.$store.state.authUser;
	  },
	  userProfile: function(){
	  	return !_.isEmpty(this.selectedProfile)
	  		?this.selectedProfile
	  		:this.authUser;
	  },
	  fullName(){
	  	let surname = this.userProfile.surname || '';
	  	return this.userProfile.name + ' ' + surname;
	  },
	  cityAndCountry(){

	  	if(	!_.isEmpty(this.userProfile.province) || 
					!_.isEmpty(this.userProfile.country)){

				return 	this.userProfile.province + ', ' + 
								this.userProfile.country.toUpperCase();
	  	}else
	  		return '';
	  },
	  isEmptySelectedProfile: function(){
	  	return !_.isEmpty(this.selectedProfile);
	  }
	},
	methods: {
	  timeToDate(unix){
	  	return moment.unix(unix).format('DD MM YYYY');
	  },
	  removeSelectedProfile: function(){
	  	this.$store.commit('chats/removeSelectedProfile');
	  }
	},
	filters: {
		genderLongName: function (value) {
	    return value === 'm'?'Male':'Famale';
	  }
	},
}
</script>