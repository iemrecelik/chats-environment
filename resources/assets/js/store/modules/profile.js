const state = {
	showMsgToComponent: '',
};
const getters = {};
const mutations = {
	setShowMsgToComponent(state, componentName){
		state.showMsgToComponent = componentName;
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