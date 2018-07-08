<template>

	<form method="POST" :action="routes.passwordEmail" class="login">
    <div class="card text-white bg-dark mb-3">
      <h6 class="card-header">{{ langs.resetPassword }}</h6>
      <div class="card-body">
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

        <p class="email-password-submit">
          <button type="submit" 
          class="email-password-button btn btn-primary btn-sm">
            {{ langs.sendPasswordResetLink }}
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