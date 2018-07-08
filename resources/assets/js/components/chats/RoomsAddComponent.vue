<template>
	<div class="row room-title">
		<div class="col"><b>Rooms</b></div>
		<div class="col">
			<div class="float-right add-room" 
			v-tooltip="'Add private room'"></div>
		</div>
	</div>
</template>

<script>
export default {
  name: 'RoomsAddComponent',
}
</script>