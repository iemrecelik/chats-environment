import Vue from 'vue';
import Vuex from 'vuex';

import mainStore from './mainStore';
import chats from './modules/chats';
import profile from './modules/profile';
import auth from './modules/auth';

Vue.use(Vuex);

export default new Vuex.Store({
	state: mainStore.state,
	mutations: mainStore.mutations,
	actions: mainStore.actions,
	modules: {
		chats,
		profile,
		auth,
	},
	// strict: true,
})