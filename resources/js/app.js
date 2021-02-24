import Vue from 'vue'

import App from './App.vue'
import router from './router'
import './plugins/element.js'
import store from './store'
import './router/permission'
import PortalVue from 'portal-vue'
import './plugins/sentry.js'
import Bowser from 'bowser'
import './plugins/numeral.js'
import './plugins/dayjs.js'
import './plugins/font-awesome.js'
import './plugins/textarea-autosize.js'
import './plugins/nl2br.js'
import './plugins/utils.js'
import './plugins/prettyBytes.js'
import Vue2Filters from 'vue2-filters'
import VueAnalytics from 'vue-analytics'
import VueTheMask from 'vue-the-mask'
import VScrollLock from 'v-scroll-lock'
import VTooltip from 'v-tooltip'
import vClickOutside from 'v-click-outside'

import AppHeader from '@/components/AppHeader'
import AppFooter from '@/components/AppFooter'
import Breadcrumb from '@/components/Breadcrumb'
import MobileMenu from '@/components/MobileMenu'
import DropdownFrontUser from '@/components/DropdownFrontUser'
import VClamp from 'vue-clamp'
import CKEditor from '@ckeditor/ckeditor5-vue'
import VueMeta from 'vue-meta'
import VueTypedJs from 'vue-typed-js'
import VueClipboard from 'vue-clipboard2'

import DefaultLayout from '@/layouts/DefaultLayout'
import NoHeaderLayout from '@/layouts/NoHeaderLayout'
import NoFooterLayout from '@/layouts/NoFooterLayout'
import TwoColsLayout from '@/layouts/TwoColsLayout'
import DashboardLayout from '@/layouts/DashboardLayout'
import ProfileLayout from '@/layouts/ProfileLayout'
import RegisterStepsLayout from '@/layouts/RegisterStepsLayout'
import MessagesLayout from '@/layouts/MessagesLayout'

import SearchOverlay from '@/components/overlays/SearchOverlay.vue'
import SoftGateOverlay from '@/components/overlays/SoftGateOverlay'

Vue.component('DefaultLayout', DefaultLayout)
Vue.component('NoHeaderLayout', NoHeaderLayout)
Vue.component('NoFooterLayout', NoFooterLayout)
Vue.component('TwoColsLayout', TwoColsLayout)
Vue.component('DashboardLayout', DashboardLayout)
Vue.component('ProfileLayout', ProfileLayout)
Vue.component('RegisterStepsLayout', RegisterStepsLayout)
Vue.component('MessagesLayout', MessagesLayout)

Vue.component('AppHeader', AppHeader)
Vue.component('AppFooter', AppFooter)
Vue.component('SearchOverlay', SearchOverlay)
Vue.component('SoftGateOverlay', SoftGateOverlay)
Vue.component('Breadcrumb', Breadcrumb)
Vue.component('MobileMenu', MobileMenu)
Vue.component('DropdownFrontUser', DropdownFrontUser)
Vue.component('VClamp', VClamp)

Vue.use(VueMeta, {
  refreshOnceOnNavigation: true,
})

Vue.use(vClickOutside)
Vue.use(VueClipboard)
Vue.use(VueTypedJs)
Vue.use(Vue2Filters)
Vue.use(PortalVue)
Vue.use(CKEditor)
Vue.use(VueAnalytics, {
  id: 'UA-81479189-7',
  router,
  autoTracking: {
    pageviewTemplate(route) {
      return {
        page: route.path,
        title: document.title,
        location: window.location.href,
      }
    },
  },
})

Vue.use(VueTheMask)
Vue.use(VScrollLock)
Vue.use(VTooltip)

new Vue({
  router,
  store,
  async created() {
    await this.$store.dispatch('bootstrap')
    // Non supported version of browser (IE < 11)
    const browserInfos = Bowser.parse(window.navigator.userAgent)
    if (
      browserInfos.browser.name == 'Internet Explorer' &&
      browserInfos.browser.version != '11.0'
    ) {
      this.$router.push('/browser-outdated')
    }

    Crisp.on_load = function() { // eslint-disable-line
      if (typeof store.getters.profile !== 'undefined') {
          $crisp.push(["set", "user:email", [store.getters.profile.email]]); // eslint-disable-line
          $crisp.push(["set", "user:nickname", [store.getters.profile.full_name]]); // eslint-disable-line
      }
      if (typeof store.getters.profile.zip !== 'undefined') {
          $crisp.push(["set", "session:data", ["code_postal",store.getters.profile.zip]]); // eslint-disable-line
      }
      if (
        typeof store.getters.contextRole !== 'undefined' &&
        store.getters.contextRole != null
      ) {
          $crisp.push(["set", "session:data", ["role",store.getters.contextRole]]); // eslint-disable-line
      }
    }

    router.afterEach(() => {
      store.commit('volet/hide')
      store.commit('setLoading', false)
    })
    router.beforeEach((to, from, next) => {
      store.commit('setLoading', true)
      next()
    })
  },
  render: (h) => h(App),
}).$mount('#app')
