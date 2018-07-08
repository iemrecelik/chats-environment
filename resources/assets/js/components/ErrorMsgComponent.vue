<template>
	<div class="error-msg" 
    v-if="isFieldError(fieldName)" 
    v-html="renderError(fieldName)"
  ></div>
</template>

<script>
export default {
  name: 'ErrorMsgComponent',
  data () {
    return {
      fieldName: this.ppfieldname,
      renderType: 'renderType' + this.pprendertype,
    };
  },
  props: {
    ppfieldname: {
      type: String,
      required: true,
    },
    pprendertype: {
    	type: Number,
    	default: 0,
    },
  },
  computed: {
    errors: function () {
      return this.$store.state.errors;
    }
  },
  methods: {
    isFieldError: function (name) {
      return !_.isEmpty(this.errors[name]);
    },
    renderError: function(name){
    	return this[this.renderType](name);
    },
    renderType0: function(name){
      return `
        <div class="row">
          <div class="col-sm-10 offset-sm-2">
            <span class="text-danger">
                ${this.errors[name][0]}
              </span>
          </div>  
        </div>
      `;
    }
  }

}
</script>