<template>
  <div>
    <div class="float-left">
      <p class="text-white">Select Account : </p>
      <ul class="p-0 text-white">
        <li><a @click.stop.prevent="selectAccount" href="">chats@chats.com</a></li>
        <li><a @click.stop.prevent="selectAccount" href="">ygerlach@example.com</a></li>
        <li><a @click.stop.prevent="selectAccount" href="">grant.erica@example.org</a></li>
        <li><a @click.stop.prevent="selectAccount" href="">bhegmann@example.org</a></li>
      </ul>
    </div>
  	<form method="POST" :action="routes.login" class="login">
      <p>
        <p class="text-danger">{{ existAuth }}</p>
        <label for="email">{{ langs.email }}:</label>
        <v-popover
          trigger="manual"
          placement="left"
          offset="10"
          :open="isOpen(errorsField('email'))"
          :auto-hide="false"
        >
          <input id="email" type="email" 
          name="email" placeholder="Email" 
          :value="inputVal('email')"/>

          <template slot="popover">
            <span class="text-danger">{{ errorsField('email')[0] }}</span>
          </template>
        </v-popover>
      </p>

      <p>
        <label for="password">{{ langs.password }}:</label>
        <v-popover
          trigger="manual"
          placement="left"
          offset="10"
          :open="isOpen(errorsField('password'))"
          :auto-hide="false"
        >
          <input id="password" type="password" 
          name="password" placeholder="Password" 
          :value="inputVal('password')"/>

          <template slot="popover">
            <span class="text-danger">{{ errorsField('password')[0] }}</span>
          </template>
        </v-popover>
      </p>

      <p class="login-submit">
        <button type="submit" class="login-button" 
        v-tooltip.right="langs.login"
        >
        	{{ langs.login }}
      	</button>
      </p>
  		
  		<input type="hidden" name="_token" :value="token" />

      <p class="forgot-password">
        <a :href="routes.reset">{{ langs.resetPassword}}</a> <span class="m-1">|</span> 
        <a :href="routes.register">{{ langs.register}}</a>
      </p>

    </form>
  </div>

</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'LoginComponent',
  data () {
    return {
      email: '',
      password: '',
    };
  },
  computed: {
  	...mapState([
		  'routes',
		  'langs',
		  'errors',
      'old',
		  'token',
		]),
    existAuth: function(){
      return this.$store.state.auth.existAuth;
    },
  },
  methods: {
    isOpen: function(fieldVal){
      return fieldVal === ''?false:true;
    },
    errorsField: function (field) {
      return this.errors[field] || '';
    },
    inputVal: function(key){
      return this[key] || this.old[key];
    },
    selectAccount: function(event){
      this.email = event.target.innerHTML;
      this.password = 123;
    },
  },
}
</script>

<style>
  .tooltip.popover .popover-inner {
    padding: 7px;
  }
</style>