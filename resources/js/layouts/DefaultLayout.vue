<template>
  <div class="bg-gray-100">
    <AppHeader />
    <router-view :key="$route.fullPath" />
    <AppFooter />
    <transition name="fade">
      <SearchOverlay
        v-if="$store.getters.searchOverlay"
        @submitted="$store.commit('toggleSearchOverlay')"
        @closed="$store.commit('toggleSearchOverlay')"
      />
      <SoftGateOverlay
        v-if="$store.getters.softGateOverlay"
        @submitted="$store.commit('toggleSoftGateOverlay')"
        @closed="$store.commit('toggleSoftGateOverlay')"
      />
    </transition>
  </div>
</template>

<script>
export default {
  name: 'DefaultLayout',
}
</script>

<script>
window.axeptioSettings = {
  clientId: '5fc77c519875a6702af31184',
  cookiesVersion: 'ga_fb',
}
;(function (d, s) {
  var t = d.getElementsByTagName(s)[0],
    e = d.createElement(s)
  e.async = true
  e.src = '//static.axept.io/sdk.js'
  t.parentNode.insertBefore(e, t)
})(document, 'script')
</script>
