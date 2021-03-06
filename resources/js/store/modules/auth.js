import {
  registerVolontaire,
  registerResponsable,
  registerInvitation,
} from '../../api/user'
import {
  login,
  logout,
  refreshToken,
  impersonate,
  stopImpersonate,
} from '../../api/auth'
import router from '../../router'
import Cookies from 'js-cookie'
import dayjs from 'dayjs'

const AccessTokenKey = 'access-token'
const RefreshTokenKey = 'refresh-token'
const ExpiresIn = 'date-expire'
const AccessTokenImpersonateKey = 'access-token-impersonate'
const TokenIdImpersonateKey = 'token-id-impersonate'

const state = {
  accessToken: Cookies.get(AccessTokenKey) || '',
  refreshToken: Cookies.get(RefreshTokenKey) || '',
  dateExpire: Cookies.get(ExpiresIn) || '',
  accessTokenImpersonate: Cookies.get(AccessTokenImpersonateKey) || '',
  tokenIdImpersonate: Cookies.get(TokenIdImpersonateKey) || '',
}

// actions
const actions = {
  login({ commit, dispatch }, user) {
    return new Promise((resolve, reject) => {
      login(user.email, user.password)
        .then((response) => {
          commit('setTokens', response.data)
          dispatch('user/get', null, { root: true }).then((response) => {
            resolve(response)
          })
        })
        .catch((error) => {
          commit('deleteTokens')
          reject(error)
        })
    })
  },
  registerResponsable({ dispatch }, user) {
    return new Promise((resolve, reject) => {
      registerResponsable(
        user.email,
        user.password,
        user.first_name,
        user.last_name,
        user.structure_name
      )
        .then(() => {
          dispatch('login', user).then((response) => {
            resolve(response)
          })
        })
        .catch((error) => {
          if (error.response.data.errors && error.response.data.errors.email) {
            if (
              error.response.data.errors.email ==
              'Cet email est déjà pris. Merci de vous connecter avec vos identifiants.'
            ) {
              router.push('/login?email=' + user.email)
            }
          }
          reject(error)
        })
    })
  },
  registerInvitation({ dispatch }, params) {
    return new Promise((resolve, reject) => {
      registerInvitation(params.form, params.invitation.token)
        .then(() => {
          dispatch('login', params.form).then((response) => {
            resolve(response)
          })
        })
        .catch((error) => {
          reject(error)
        })
    })
  },
  registerVolontaire({ dispatch }, user) {
    return new Promise((resolve, reject) => {
      registerVolontaire(
        user.email,
        user.password,
        user.first_name,
        user.last_name,
        user.mobile,
        user.birthday,
        user.zip,
        user.service_civique
      )
        .then(() => {
          dispatch('login', user).then((response) => {
            resolve(response)
          })
        })
        .catch((error) => {
          if (error.response.data.errors && error.response.data.errors.email) {
            if (
              error.response.data.errors.email ==
              'Cet email est déjà pris. Merci de vous connecter avec vos identifiants.'
            ) {
              router.push('/login?email=' + user.email)
            }
          }
          reject(error)
        })
    })
  },
  logout({ commit }) {
    return new Promise((resolve, reject) => {
      logout()
        .then((res) => {
          commit('deleteTokens')
          commit('user/deleteUser', null, { root: true })
          if (res.data.franceConnectLogoutUrl) {
            window.location.href = res.data.franceConnectLogoutUrl
          }
          router.push('/')
        })
        .catch((error) => {
          commit('deleteTokens')
          router.push('/')
          reject(error)
        })
    })
  },
  async refreshToken({ state, commit }) {
    try {
      const response = await refreshToken(state.refreshToken)
      commit('setTokens', response.data)
    } catch (e) {
      commit('deleteTokens')
      commit('user/deleteUser', null, { root: true })
      router.push('/')
    }
  },
  async impersonate({ commit, dispatch }, user_id) {
    const response = await impersonate(user_id)
    commit('setTokenImpersonate', response.data)
    await dispatch('user/get', null, { root: true })
    router.push('/')
  },
  async stopImpersonate({ commit, dispatch }) {
    await stopImpersonate(state.tokenIdImpersonate)
    commit('deleteTokenImpersonate')
    await dispatch('user/get', null, { root: true })
    router.push('/')
  },
}

// mutations
const mutations = {
  setTokens: (state, tokens) => {
    Cookies.set(AccessTokenKey, tokens.access_token, {
      expires: 365,
      secure: true,
    })
    Cookies.set(RefreshTokenKey, tokens.refresh_token, {
      expires: 365,
      secure: true,
    })
    const dateExpire = dayjs().add(tokens.expires_in, 'seconds').format()
    Cookies.set(ExpiresIn, dateExpire, {
      expires: 365,
      secure: true,
    })
    state.accessToken = tokens.access_token
    state.refreshToken = tokens.refresh_token
    state.dateExpire = dateExpire
  },
  setTokenImpersonate: (state, tokens) => {
    Cookies.set(AccessTokenImpersonateKey, tokens.accessToken, {
      expires: 365,
      secure: true,
    })
    Cookies.set(TokenIdImpersonateKey, tokens.token.id, {
      expires: 365,
      secure: true,
    })
    state.accessTokenImpersonate = tokens.accessToken
    state.tokenIdImpersonate = tokens.token.id
  },
  deleteTokenImpersonate: (state) => {
    Cookies.remove(AccessTokenImpersonateKey)
    Cookies.remove(TokenIdImpersonateKey)
    state.accessTokenImpersonate = ''
    state.tokenIdImpersonate = ''
  },
  deleteTokens: (state) => {
    Cookies.remove(AccessTokenKey)
    Cookies.remove(RefreshTokenKey)
    Cookies.remove(ExpiresIn)
    Cookies.remove(AccessTokenImpersonateKey)
    Cookies.remove(TokenIdImpersonateKey)
    state.accessToken = ''
    state.refreshToken = ''
    state.dateExpire = ''
    state.accessTokenImpersonate = ''
    state.tokenIdImpersonate = ''
  },
}

export default {
  namespaced: true,
  state,
  actions,
  mutations,
}
