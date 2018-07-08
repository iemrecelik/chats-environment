const state = {
	routes: {},
	langs: {},
	errors: {},
	succeed: '',
	old: {},
	authUser: {},
	token: document.head.querySelector('meta[name="csrf-token"]').content,
};
const getters = {
	profileErr: state => {
    return state.errors.profile;
  },
  changePasswordErr: state => {
    return state.errors.changePassword;
  },
};
const mutations = {
	setRoutes(state, routes){
		state.routes = routes
	},
	setLangs(state, langs){
		state.langs = langs
	},
	setErrors(state, errors){
		state.errors = errors
	},
	setSucceed(state, succeed){
		state.succeed = succeed
	},
	setOld(state, old){
		state.old = old
	},
	setAuthUser(state, authUser){
		state.authUser = authUser
	},
};
const actions = {
	addDataToAuthUser({commit, state}, addData/*path*/){

		if (!_.isEmpty(state.authUser)) {

			let authUser = state.authUser;
			// authUser['path'] = path;
			authUser[addData.key] = addData.val;
			
			commit('setAuthUser', authUser);
		}
	},
};

export default {
	state,
	getters,
	mutations,
	actions,
	namespaced: true
}