<template>
  <header class="bg-white relative z-20 shadow-lg">
    <jdma-benevole
      v-if="
        $store.getters.isAppLoaded &&
        $store.getters.isLogged &&
        $store.getters.participationsValidated > 1
      "
    />
    <div id="header-wrapper" class="flex justify-between items-center">
      <div class="flex h-full">
        <div class="hidden md:flex items-center px-2 shadow-lg">
          <router-link to="/">
            <img
              class="mx-auto lg:mx-0"
              src="/images/republique-francaise-logo.svg"
              alt="République Française"
              style="height: 110px"
            />
          </router-link>
        </div>
        <div class="flex items-center px-8">
          <router-link to="/">
            <img
              class=""
              src="/images/jeveuxaider-logo.svg"
              alt="JeVeuxAider.gouv.fr"
            />
          </router-link>
        </div>
      </div>

      <!-- DESKTOP -->
      <div class="hidden md:flex md:flex-col h-full">
        <div class="flex justify-end">
          <slot name="top-menu">
            <router-link
              v-if="
                $store.getters.isLogged &&
                $store.getters.hasRoles &&
                $store.getters.hasRoles.length > 0
              "
              to="/dashboard"
              class="font-semibold tracking-wide uppercase bg-gray-50 text-xxs text-gray-400 hover:text-blue-800 px-12 py-2 transition ease-in-out duration-150"
            >
              Tableau de bord
            </router-link>
            <router-link
              v-if="!$store.getters.isLogged"
              to="/collectivite"
              class="ml-1 font-semibold tracking-wide uppercase bg-gray-50 text-xxs text-gray-400 hover:text-blue-800 px-12 py-2 transition ease-in-out duration-150"
            >
              Inscrire ma collectivité
            </router-link>
            <router-link
              to="/territoires"
              class="font-semibold tracking-wide uppercase bg-gray-50 text-xxs text-gray-400 hover:text-blue-800 px-12 py-2 transition ease-in-out duration-150"
            >
              Territoires engagés
            </router-link>
            <a
              target="_blank"
              href="https://reserve-civique.crisp.help/fr/"
              class="ml-1 font-semibold tracking-wide uppercase bg-gray-50 text-xxs text-gray-400 hover:text-blue-800 px-12 py-2 transition ease-in-out duration-150"
            >
              Centre d'aide
            </a>
            <div
              class="flex justify-center items-center ml-1 bg-gray-50 text-xxs text-gray-400 hover:text-blue-800 px-4 py-2 transition ease-in-out duration-150"
            >
              <a
                target="_blank"
                href="https://www.facebook.com/jeveuxaider.gouv.fr"
                class="px-2"
                ><img src="/images/icones/facebook.svg" alt="Facebook"
              /></a>
              <a
                target="_blank"
                href="https://twitter.com/reservecivique"
                class="px-2"
                ><img src="/images/icones/twitter.svg" alt="Twitter"
              /></a>
              <!-- <a
                target="_blank"
                href="https://www.instagram.com/jeveuxaider"
                class="px-2"
                ><img src="/images/icones/instagram.svg" alt="Instagram"
              /></a> -->
              <a
                target="_blank"
                href="https://www.linkedin.com/company/reservecivique/"
                class="px-2"
                ><img src="/images/icones/linkedin.svg" alt="Linkedin"
              /></a>
            </div>
          </slot>
        </div>
        <div class="flex h-full items-center justify-end px-4">
          <slot name="menu">
            <nav class="flex space-x-12 text-sm lg:text-base">
              <button
                class="flex items-center leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                @click="$store.commit('toggleSearchOverlay')"
              >
                <img
                  class="mr-2"
                  src="/images/icones/search.svg"
                  alt="Trouver une mission"
                />
                Trouver une mission
              </button>
              <router-link
                v-if="!$store.getters.isLogged"
                to="/register/responsable"
                class="leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
              >
                Publier une mission
              </router-link>
              <el-dropdown>
                <button
                  type="button"
                  aria-label="S'informer"
                  class="flex items-center leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
                >
                  <span class="flex-none text-base">S'informer</span>
                  <svg
                    class="text-blue-300 hover:text-blue-800 h-5 w-5 group-hover:text-gray-500 group-focus:text-gray-500 transition ease-in-out duration-150 flex-none"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </button>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item>
                    <a
                      href="https://jeveuxaider.gouv.fr/engagement/actualites/"
                    >
                      Le blog de l'engagement</a
                    >
                  </el-dropdown-item>
                  <el-dropdown-item>
                    <a
                      href="https://jeveuxaider.gouv.fr/engagement/dispositifs/"
                    >
                      Les dispositifs publics d’engagement civique
                    </a>
                  </el-dropdown-item>
                  <el-dropdown-item>
                    <a href="https://jeveuxaider.gouv.fr/engagement/">
                      Toutes les initiatives solidaires
                    </a>
                  </el-dropdown-item>
                  <el-dropdown-item>
                    <a href="/regles-de-securite">
                      Les 5 règles de sécurité des bénévoles
                    </a>
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
              <router-link
                v-if="$store.getters.isLogged"
                to="/user/missions"
                class="leading-6 font-semibold text-gray-800 hover:text-blue-800 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150"
              >
                Mes missions
              </router-link>
            </nav>
          </slot>
          <div class="ml-12">
            <template v-if="!$store.getters.isLogged">
              <router-link
                to="/login"
                class="flex border border-gray-200 cursor-pointer rounded-full px-4 py-2 text-xs font-semibold text-gray-800 hover:bg-gray-50 hover:text-blue-800 focus:text-gray-900 transition ease-in-out duration-150"
              >
                <img
                  class="mr-2"
                  src="/images/icones/mon-espace.svg"
                  alt="Mon espace"
                />
                Mon espace
              </router-link>
            </template>
            <template v-else>
              <div class="flex items-center space-x-4">
                <router-link
                  v-if="
                    $store.getters.isLogged &&
                    $store.getters.reminders &&
                    $store.getters.contextRole != 'volontaire' &&
                    $store.getters.reminders.total > 0
                  "
                  to="/dashboard"
                >
                  <el-badge
                    :value="$store.getters.reminders.total"
                    is-dot
                    :max="99"
                  >
                    <svg
                      class="h-6 w-6 text-blue-300 hover:text-blue-800 transition ease-in-out duration-150"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                      />
                    </svg>
                  </el-badge>
                </router-link>
                <router-link v-if="$store.getters.isLogged" to="/messages">
                  <el-badge
                    :value="$store.getters.user.nbUnreadConversations"
                    :hidden="!$store.getters.user.nbUnreadConversations"
                    is-dot
                  >
                    <svg
                      class="h-6 w-6 text-blue-300 hover:text-blue-800 transition ease-in-out duration-150"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                      />
                    </svg>
                  </el-badge>
                </router-link>
                <dropdown-front-user></dropdown-front-user>
              </div>
            </template>
          </div>
        </div>
      </div>

      <!-- MOBILE -->
      <mobile-menu
        class="flex h-full items-center md:hidden"
        @mission-search-clicked="$store.commit('toggleSearchOverlay')"
      />
    </div>
  </header>
</template>

<script>
import JdmaBenevole from '@/components/JdmaBenevole.vue'

export default {
  name: 'AppHeader',
  components: { JdmaBenevole },
  props: {
    showMenu: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {}
  },
  created() {
    if (
      this.$store.getters.isLogged &&
      this.$store.getters.hasRoles &&
      this.$store.getters.hasRoles.length > 0
    ) {
      this.$store.dispatch('reminders')
    }
  },
  methods: {},
}
</script>

<style lang="sass" scoped>
#header-wrapper
  height: 80px
  @screen md
    height: 110px
.links-wrapper
  @screen sm
    transform: translateX(-.5rem)
  @screen lg
    transform: inherit
</style>
