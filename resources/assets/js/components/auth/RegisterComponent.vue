<template>

	<form method="POST" :action="routes.register" class="login">
    <div class="card text-white bg-dark mb-3">
      <h6 class="card-header">{{ langs.registerForm }}</h6>
      <div class="card-body">
        <p>
          <label for="name">{{ langs.name }}:</label>
          <v-popover
            trigger="manual"
            placement="left"
            offset="10"
            :open="isOpen(errorsField('name'))"
            :auto-hide="false"
          >
            <input id="name" type="text" 
            name="name" placeholder="Name" 
            :value="old.name"/>

            <template slot="popover">
              <span class="text-danger">{{ errorsField('name')[0] }}</span>
            </template>
          </v-popover>
        </p>

        <p>
          <label for="nickname">{{ langs.nickname }}:</label>
          <v-popover
            trigger="manual"
            placement="left"
            offset="10"
            :open="isOpen(errorsField('nickname'))"
            :auto-hide="false"
          >
            <input id="nickname" type="text" 
            name="nickname" placeholder="Nickname" 
            :value="old.nickname"/>

            <template slot="popover">
              <span class="text-danger">{{ errorsField('nickname')[0] }}</span>
            </template>
          </v-popover>
        </p>
        
        <p>
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
            :value="old.email"/>

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
            value=""/>

            <template slot="popover">
              <span class="text-danger">{{ errorsField('password')[0] }}</span>
            </template>
          </v-popover>
        </p>

        <p>
          <label for="password-confirmation">
            {{ langs.confirmPassword }}:
          </label>
          <v-popover
            trigger="manual"
            placement="left"
            offset="10"
            :open="isOpen(errorsField('password_confirmation'))"
            :auto-hide="false"
          >
            <input id="password-confirmation" type="password" 
            name="password_confirmation" placeholder="Password Confirm" 
            value=""/>

            <template slot="popover">
              <span class="text-danger">
                {{ errorsField('password_confirmation')[0] }}
              </span>
            </template>
          </v-popover>
        </p>

        <p class="register-submit">
          <button type="submit" class="register-button btn btn-primary btn-sm">
            {{ langs.register }}
          </button>
          
          <a :href="routes.login">
            <button type="button" class="btn btn-primary btn-sm">
            	{{ langs.signUp }}
          	</button>
          </a>
        </p>
    		
    		<input type="hidden" name="_token" :value="token" />
      </div><!--/div.card-body-->
    </div><!--/div.card-->
  </form>

</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'LoginComponent',
  computed: {
  	...mapState([
		  'routes',
		  'langs',
		  'errors',
      'old',
		  'token',
		]),
  },
  methods: {
    isOpen: function(fieldVal){
      return fieldVal === ''?false:true;
    },
    errorsField: function (field) {
      return this.errors[field] || '';
    }
  },
}
</script>

<style>
  .tooltip.popover .popover-inner {
    padding: 7px;
  }
</style>