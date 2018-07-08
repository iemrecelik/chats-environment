<template>
	<auth-login-component 
		v-if="this.page === 'login'"
	>
	</auth-login-component>

	<auth-register-component 
		v-else-if="this.page === 'register'"
	>
	</auth-register-component>

	<auth-reset-component 
		v-else-if="this.page === 'resetPassword'"
	>
	</auth-reset-component>

	<auth-email-component 
		v-else-if="this.page === 'email'"
	>
	</auth-email-component>
</template>

<script>
import loginComponent from './LoginComponent';
import registerComponent from './RegisterComponent';
import resetComponent from './ResetComponent';
import emailComponent from './EmailComponent';

export default {
	name: 'AuthComponent',
	data () {
	  return {
	    page: this.pppage,
	  };
	},
	props: {
	  pppage: {
	    type: String,
	    required: true,
	  },
	  pproutes: {
	    type: Object,
	    required: true,
	  },
	  pplangs: {
	  	type: Object,
	  	required: true,
	  },
	  pperrors: {
	  	type: Object,
	  	required: true,
	  },
	  ppold: {
	  	type: Object,
	  	required: true,
	  },
	  ppexistauth: {
	  	type: String,
	  	required: true,
	  },
	},
	components: {
		'auth-login-component': loginComponent,
		'auth-register-component': registerComponent,
		'auth-reset-component': resetComponent,
		'auth-email-component': emailComponent,
	},
	created(){
		this.$store.commit('setRoutes', this.pproutes);
		this.$store.commit('setLangs', this.pplangs);
		this.$store.commit('setErrors', this.pperrors);
		this.$store.commit('setOld', this.ppold);
		this.$store.commit('auth/setExistAuth', this.ppexistauth);
	}
}
</script>

<style>
	body {
	  font: 14px/20px 'Helvetica Neue', Helvetica, Arial, sans-serif;
	  color: #404040;
	  background: #373737 url('/images/bg.png') 0 0 repeat;
	}

	#app{
		box-shadow: none;
	}
</style>