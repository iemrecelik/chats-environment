<template>
	<div class="alert alert-success" role="alert"
		v-if="isSucceed"
	>
	  {{ succeed }}
	</div>
</template>

<script>
export default {
  name: 'SucceedMsgComponent',
  props: {
    ppcomponent: {
      type: String, // String, Number, Boolean, Function, Object, Array
      required: false,
    }
  },
  computed: {
  	showMsgToComponent: function(){
  		return this.$store.state.profile.showMsgToComponent;
  	},
  	succeed: function(){
  		return 	this.ppcomponent === '' || 
  						this.showMsgToComponent === this.ppcomponent
  							?	this.$store.state.succeed
  							: '';
  	},
		isSucceed: function(){
			return !_.isEmpty(this.succeed);
		},
	},
}
</script>