<template>
	<div class="profile-settings">
		<profile-tab-component></profile-tab-component>
		<profile-tab-contents-component></profile-tab-contents-component>
	</div>
</template>

<script>
import tabComponent from './TabComponent';
import tabContentsComponent from './TabContentsComponent';

export default {
  name: 'ProfileSettingsComponent',
  components: {
		'profile-tab-component': tabComponent,
		'profile-tab-contents-component': tabContentsComponent,
	}
}
</script>