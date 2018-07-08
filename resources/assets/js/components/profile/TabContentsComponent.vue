<template>
	<div class="tab-content" id="settingsTabContent">
		
		<div class="tab-pane fade" id="settings" 
		role="tabpanel" aria-labelledby="settings-tab"
		:class="isActiveTab('settings')"
		>
			<profile-info-form-component></profile-info-form-component>	
		</div>
		<div class="tab-pane fade" id="change-password" 
		role="tabpanel" aria-labelledby="change-password-tab"
		:class="isActiveTab('change-password')"
		>
			<profile-change-pass-form-component></profile-change-pass-form-component>	
		</div>
	</div>
</template>

<script>
import infoFormComponent from './InfoFormComponent';
import changePassFormComponent from './ChangePassFormComponent';

export default {
	name: 'TabContentsComponent',
	components: {
		'profile-change-pass-form-component': changePassFormComponent,
		'profile-info-form-component': infoFormComponent,
	},
	created(){
		let queryTab = this.getQueryParameters('tab');
		this.$store.commit('chats/setActiveTab', queryTab);
	}
}
</script>