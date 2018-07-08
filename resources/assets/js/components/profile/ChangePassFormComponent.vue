<template>
	<form method="POST" :action="routes.changePasswordPost">

		<succeed-msg-component :ppcomponent="'changePassFormComp'">
		</succeed-msg-component>
    <error-msg-list-component :ppcomponent="'changePassFormComp'">
    </error-msg-list-component>
		
		<error-msg-component v-if="isActiveTab()" :ppfieldname="'password'">
		</error-msg-component>
		<div class="form-group row">
			<label for="inputChangePassword" class="col-sm-2 col-form-label">Password</label>
			<div class="col-sm-10">
				<input type="password" class="form-control form-control-sm" 
				id="inputChangePassword" name="password" placeholder="Password">
			</div>
		</div>
		
		<error-msg-component v-if="isActiveTab()" :ppfieldname="'new_password'">
		</error-msg-component>
		<div class="form-group row">
			<label for="inputChangeNewPassword" class="col-sm-2 col-form-label">New Password</label>
			<div class="col-sm-10">
				<input type="password" class="form-control form-control-sm" 
				id="inputChangeNewPassword" name="new_password" 
				placeholder="New Password">
			</div>
		</div>
		
		<error-msg-component 
			v-if="isActiveTab()" 
			:ppfieldname="'new_password_confirmation'"
		>
		</error-msg-component>
		<div class="form-group row">
			<label for="inputChangeRepeatPassword" class="col-sm-2 col-form-label">Repeat Password</label>
			<div class="col-sm-10">
				<input type="password" class="form-control form-control-sm" 
				id="inputChangeRepeatPassword" name="new_password_confirmation" 
				placeholder="Repeat Password">
			</div>
		</div>
		
		<input type="hidden" name="_token" :value="token"/>

		<div class="form-group row">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">        
        <button type="submit" class="btn btn-sm btn-primary change-password-btn">Update</button>
      </div>
    </div>
	</form>
</template>

<script>
import { mapState } from 'vuex';

export default {
	name: 'ChangePassFormComponent',
	computed: {
    ...mapState([
      'routes',
      'token',
    ]),
  },
  methods: {
    isActiveTab: function(){
      return this.$store.state.chats.activeTab === 'change-password';
    }
  }
}
</script>