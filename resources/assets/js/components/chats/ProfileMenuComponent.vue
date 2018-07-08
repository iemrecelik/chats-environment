<template>
	<div class="row tools-title">
		<div class="col">
			<div class="float-left info-bell">
				<div class="info-mark"></div>
				<div class="float-right">
					<img src="/img/bell.png" alt="bell">	
				</div>
			</div>
			<chats-settings-dropdown-component :class="'float-right'"></chats-settings-dropdown-component>
		</div>
	</div>
</template>

<script>
import settingsDropdownComponent from './SettingsDropdownComponent';

export default {
	name: 'ProfileMenuComponent',
	components: {
		'chats-settings-dropdown-component': settingsDropdownComponent,
	}
}
</script>