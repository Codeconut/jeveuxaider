import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/auth'
import user from './modules/user'
import volet from './modules/volet'
import conversation from './modules/conversation'
import getters from './getters'
import { bootstrap, reminders } from '../api/app'

Vue.use(Vuex)

const state = {
  isAppLoaded: false,
  searchOverlay: false,
  softGateOverlay: false,
  missionSelected: null,
  collectivities: null,
  sidebar: true,
  loading: false,
  taxonomies: null,
  thematiques: null,
  reseaux: null,
  release: null,
  reminders: null,
  showAvisBenevole: false,
}

// actions
const actions = {
  async bootstrap({ commit }) {
    const { data } = await bootstrap()
    commit('setTaxonomies', data.taxonomies)
    commit('setReseaux', data.reseaux)
    commit('setCollectivities', data.collectivities)
    commit('setThematiques', data.thematiques)
    if (data.user) {
      commit('user/setUser', data.user)
      if (
        data.user.profile.participations.filter((participation) =>
          ['Validée', 'Effectuée'].includes(participation.state)
        ).length > 1
      ) {
        commit('setShowAvisBenevole', true)
      }
    } else {
      // Access token plus valide
      commit('auth/deleteTokens')
      commit('user/deleteUser', null, { root: true })
    }
    commit('setAppLoadingStatus', true)
    return data
  },
  async reminders({ commit }) {
    const { data } = await reminders()
    commit('setReminders', data)
    return data
  },
}

// mutations
const mutations = {
  setAppLoadingStatus(state, isAppLoaded) {
    state.isAppLoaded = isAppLoaded
  },
  setTaxonomies: (state, taxonomies) => {
    state.taxonomies = taxonomies
  },
  setThematiques: (state, thematiques) => {
    state.thematiques = thematiques
  },
  setCollectivities: (state, collectivities) => {
    state.collectivities = collectivities
  },
  setReseaux: (state, reseaux) => {
    state.reseaux = reseaux
  },
  setReminders: (state, reminders) => {
    state.reminders = reminders
  },
  setRelease: (state, release) => {
    state.release = release
  },
  setLoading: (state, loading) => {
    state.loading = loading
  },
  toggleSidebar: (state) => {
    state.sidebar = !state.sidebar
  },
  toggleSearchOverlay: (state) => {
    state.searchOverlay = !state.searchOverlay
  },
  toggleSoftGateOverlay: (state) => {
    state.softGateOverlay = !state.softGateOverlay
  },
  setMissionSelected: (state, mission) => {
    state.missionSelected = mission
  },
  setShowAvisBenevole: (state, value) => {
    state.showAvisBenevole = value
  },
}

export default new Vuex.Store({
  modules: {
    auth,
    user,
    volet,
    conversation,
  },
  state,
  getters,
  mutations,
  actions,
})
