<template>
	<div class="error-list alert alert-danger" role="alert"
		v-if="isErrors"
	>
		<ol>
			<li v-for="error in errors">{{ error[0] }}</li>	
		</ol>
	</div>
</template>

<script>
export default {
  name: 'ErrorMsgListComponent',
  props: {
    ppcomponent: {
      type: String, // String, Number, Boolean, Function, Object, Array
      required: false,
      default: ''
    }
  },
  computed: {
  	showMsgToComponent: function(){
  		return this.$store.state.profile.showMsgToComponent;
  	},
  	errors: function(){
  		return this.ppcomponent === '' || 
  						this.showMsgToComponent === this.ppcomponent
  							?	this.$store.state.errors
  							: '';
  	},
		isErrors: function(){
			return !_.isEmpty(this.errors);
		},
	},
}
</script>