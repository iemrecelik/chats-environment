require('./bootstrap');

import Vue from 'vue';
import store from './store';

/*GLOBAL COMPONENT START*/
import succeedMsgComponent from './components/SucceedMsgComponent';
import errorMsgListComponent from './components/ErrorMsgListComponent';
import errorMsgComponent from './components/ErrorMsgComponent';

Vue.component('succeed-msg-component', succeedMsgComponent);
Vue.component('error-msg-list-component', errorMsgListComponent);
Vue.component('error-msg-component', errorMsgComponent);
/*GLOBAL COMPONENT END*/

import components from './components';

import globalMixin from './globalMixin';
Vue.mixin(globalMixin);

import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

var app = new Vue({
	el: "#app",
	store,
	components,
})