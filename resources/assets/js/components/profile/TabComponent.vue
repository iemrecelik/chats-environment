<template>	
	<ul class="nav nav-tabs" id="settingsTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link" id="settings-tab" data-toggle="tab" 
			href="#settings" role="tab" aria-controls="settings" 
			aria-selected="true"
			:class="isActiveTab('settings')"
			>Settings</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" id="change-password-tab" data-toggle="tab" 
			href="#change-password" role="tab" 
			aria-controls="change-password" aria-selected="false"
			:class="isActiveTab('change-password')"
			>Change Password</a>
		</li>
	</ul>
</template>

<script>
export default {
	name: 'TabComponent',
}
</script>