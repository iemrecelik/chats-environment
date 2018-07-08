const state = {
	existAuth: '',
};
const getters = {};
const mutations = {
	setExistAuth(state, existAuth){
		state.existAuth = existAuth;
	},
};
const actions = {};

export default {
	state,
	getters,
	mutations,
	actions,
	namespaced: true
}