<template>
	<nav class="main-top-bar navbar sticky-top navbar-light bg-light">
		<span></span>
		<chats-settings-dropdown-component></chats-settings-dropdown-component>
	</nav>
</template>

<script>
import settingsDropdownComponent from './chats/SettingsDropdownComponent';

export default {
	name: 'NavbarComponent',
	components: {
		'chats-settings-dropdown-component': settingsDropdownComponent,
	}
}
</script>