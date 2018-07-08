<template>
	<div class="row profile-row">
		<div class="col-4">
			<profile-user-info-component></profile-user-info-component>
		</div>
		<div class="col-8">
			<profile-settings-component></profile-settings-component>
		</div>
	</div>
</template>

<script>
import userInfoComponent from './UserInfoComponent';
import profileSettings from './ProfileSettingsComponent';

export default {
	name: 'ProfileComponent',
	props: {
	  pproutes: {
	    type: Object, // String, Number, Boolean, Function, Object, Array
	    required: true,
	  },
	  ppauthuser: {
	    type: Object,
	    required: true,
	  },
	  pperrors: {
	    type: Object,
	    required: true,
	  },
	  ppsucceed: {
	    type: String,
	    required: true,
	  },
	  ppcomponent: {
	    type: String,
	    required: true,
	  },
	  ppoldinput: {
	    type: String,
	    required: true,
	  },
	},
	components: {
		'profile-user-info-component': userInfoComponent,
		'profile-settings-component': profileSettings,
	},
	created(){
		this.$store.commit('setRoutes', this.pproutes);
		this.$store.commit('setAuthUser', this.ppauthuser);
  	this.$store.commit('setErrors', this.pperrors);
  	this.$store.commit('setSucceed', this.ppsucceed);
  	this.$store.commit('profile/setShowMsgToComponent', this.ppcomponent);
  	this.$store.commit('setOld', JSON.parse(this.ppoldinput));

  	const socket = io(
  		'37.148.210.93:3333',
  		{query: `chatID=${this.ppauthuser.chatID}`}
  	);
	},
}
</script>