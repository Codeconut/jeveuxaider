<template>
  <div v-if="thematique">
    <AppHeader :show-menu="false">
      <template v-slot:append-logo>
        <div class="hidden sm:block ml-2 mr-auto w-auto order-2">
          <div
            class="text-xl md:text-xl font-medium text-gray-500 leading-none"
          >
            • {{ thematique.name }}
          </div>
        </div>
      </template>
    </AppHeader>

    <div>
      <breadcrumb
        :items="[{ label: 'Domaines d\'action' }, { label: thematique.name }]"
      />
      <div class="relative">
        <img
          v-if="thematique.image && thematique.image.large"
          :src="thematique.image.large"
          :alt="thematique.title"
          class="absolute object-cover object-center w-full h-full"
        />

        <div
          :class="[
            `bg-${thematique.color}`,
            'absolute',
            'inset-0',
            { 'opacity-75': thematique.image },
          ]"
        />

        <div class="relative pt-1 pb-12 lg:py-12">
          <div class="container mx-auto px-4">
            <div class="py-8 text-center sm:text-left">
              <h2
                class="text-4xl max-w-4xl leading-none font-bold text-white sm:text-5xl md:text-6xl"
              >
                {{ thematique.title }}
              </h2>

              <p
                class="mt-5 text-base text-gray-100 max-w-xl sm:text-lg md:text-xl"
              >
                {{ thematique.description }}
              </p>

              <div
                class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start"
              >
                <div class="text-center">
                  <p class="text-xs leading-6 font-medium text-white">
                    Je veux aider
                  </p>
                  <router-link
                    v-if="!$store.getters.isLogged"
                    to="/register/volontaire"
                    :class="`text-${thematique.color}`"
                    class="shadow-lg w-full flex items-center justify-center px-10 py-3 text-base leading-6 font-medium rounded-full bg-white hover:bg-gray-100 hover:bg-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-15"
                  >
                    Devenir réserviste
                  </router-link>
                  <a
                    v-else
                    href="#search"
                    :class="`text-${thematique.color}`"
                    class="shadow-lg w-full flex items-center justify-center px-10 py-3 text-base leading-6 font-medium rounded-full bg-white hover:bg-gray-100 hover:bg-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-15"
                  >
                    Trouver une mission
                  </a>
                </div>
                <div class="mt-3 sm:mt-0 sm:ml-6 text-center">
                  <p class="text-xs leading-6 font-medium text-white">
                    Mon organisation a besoin de renfort
                  </p>
                  <router-link
                    :to="
                      $store.getters.isLogged &&
                      $store.getters.contextRole == 'responsable'
                        ? {
                            name: 'DashboardMissionFormAdd',
                            params: {
                              structureId:
                                $store.getters.structure_as_responsable.id,
                            },
                          }
                        : '/login'
                    "
                    class="shadow-lg w-full flex items-center justify-center px-8 py-3 border border-transparent border text-base leading-6 font-medium rounded-full text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-9"
                  >
                    Proposer une mission
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white">
        <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
          <div class="grid grid-cols-2 gap-8 lg:grid-cols-4">
            <div class="col-span-1 flex justify-center lg:col-span-1">
              <p
                class="text-center text-base leading-6 mt-2 font-semibold uppercase text-gray-800 tracking-wider"
              >
                En partenariat avec
              </p>
            </div>
            <div class="col-span-1 flex justify-center lg:col-span-1">
              <img
                class="h-14"
                alt="logo-partenaire-thematique"
                :src="`/images/thematiques/${thematique.slug}-partenaire-1.jpg`"
              />
            </div>

            <div class="col-span-1 flex justify-center lg:col-span-1">
              <img
                class="h-14"
                alt="logo-partenaire-thematique"
                :src="`/images/thematiques/${thematique.slug}-partenaire-2.jpg`"
              />
            </div>

            <div class="col-span-1 flex justify-center lg:col-span-1">
              <img
                class="h-14"
                alt="logo-partenaire-thematique"
                :src="`/images/thematiques/${thematique.slug}-partenaire-3.jpg`"
              />
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white z-10">
        <div>
          <div class="grid grid-cols-2 gap-0 md:grid-cols-6 lg:grid-cols-6">
            <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
              <img
                :src="`/images/thematiques/${thematique.slug}-1.jpg`"
                alt=""
                height="auto"
                width="auto"
              />
            </div>
            <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
              <img
                :src="`/images/thematiques/${thematique.slug}-6.jpg`"
                alt=""
                height="auto"
                width="auto"
              />
            </div>
            <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
              <img
                :src="`/images/thematiques/${thematique.slug}-5.jpg`"
                alt=""
                height="auto"
                width="auto"
              />
            </div>
            <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
              <img
                :src="`/images/thematiques/${thematique.slug}-3.jpg`"
                alt=""
                height="auto"
                width="auto"
              />
            </div>
            <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
              <img
                :src="`/images/thematiques/${thematique.slug}-4.jpg`"
                alt=""
                height="auto"
                width="auto"
              />
            </div>
            <div class="col-span-1 justify-center md:col-span-2 lg:col-span-1">
              <img
                :src="`/images/thematiques/${thematique.slug}-2.jpg`"
                alt=""
                height="auto"
                width="auto"
              />
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="!$store.getters.loading"
        id="search"
        :class="`bg-${thematique.color}`"
      >
        <div
          class="container mx-auto py-12 pt-16 px-4 sm:py-16 sm:px-6 lg:px-8"
        >
          <div class="max-w-6xl mx-auto text-center">
            <h2
              class="text-3xl leading-9 font-extrabold text-white sm:text-4xl sm:leading-10"
            >
              Rejoignez le mouvement #JeVeuxAider
            </h2>
            <p class="text-xl max-w-2xl mx-auto leading-8 text-gray-200 mt-2">
              Sur l’ensemble du territoire français, des milliers de bénévoles
              et d’organisations se sont déjà ralliés à JeVeuxAider.gouv.fr pour
              soutenir ce domaine d'action.
            </p>
            <dl
              class="mt-12 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8"
            >
              <div class="flex flex-col">
                <dd class="text-5xl leading-none font-bold text-white">
                  {{ statistics.volontaires_count | formatNumber }}
                </dd>
                <dt class="mt-2 text-lg font-medium text-white">Réservistes</dt>
              </div>
              <div class="flex flex-col mt-10 sm:mt-0">
                <dd class="text-5xl leading-none font-bold text-white">
                  {{ statistics.structures_count | formatNumber }}
                </dd>
                <dt class="mt-2 text-lg font-medium text-white">
                  Organisations
                </dt>
              </div>
              <div class="flex flex-col mt-10 sm:mt-0">
                <dd class="text-5xl leading-none font-bold text-white">
                  {{ statistics.participations_count | formatNumber }}
                </dd>
                <dt class="mt-2 text-lg font-medium text-white">
                  Mises en relation
                </dt>
              </div>
            </dl>
          </div>
        </div>
      </div>

      <MissionsSearch
        v-if="thematique.domaine"
        :facets="['template_title', 'department_name', 'structure.name']"
        :filters="`domaines:&quot;${thematique.domaine.name.fr}&quot;`"
        :color="thematique.color"
      />

      <div class="bg-gray-50 border-b border-gray-200">
        <div
          class="container mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between"
        >
          <h2
            class="text-3xl leading-9 font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
          >
            Votre organisation a besoin de
            <span :class="`text-${thematique.color}`">bénévoles</span> ?
          </h2>
          <div class="mt-8 flex lg:flex-shrink-0 lg:mt-0">
            <div class="inline-flex rounded-full shadow">
              <router-link
                :to="
                  $store.getters.isLogged &&
                  $store.getters.contextRole == 'responsable'
                    ? {
                        name: 'DashboardMissionFormAdd',
                        params: {
                          structureId:
                            $store.getters.structure_as_responsable.id,
                        },
                      }
                    : '/register/responsable'
                "
                :class="`bg-${thematique.color}`"
                class="inline-flex items-center justify-center px-7 py-3 border border-transparent text-base leading-6 font-medium rounded-full text-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
              >
                Rejoignez JeVeuxAider.gouv.fr
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-white border-gray-200 border-b">
        <div class="container mx-auto py-12 px-4 sm:px-6 lg:px-8">
          <div
            class="text-center pb-12 text-base leading-6 font-semibold uppercase text-gray-400 tracking-wider"
          >
            Parmi les organisations déjà actives sur JeVeuxAider.gouv.fr
          </div>
          <div class="grid grid-cols-2 gap-8 lg:grid-cols-5">
            <div class="col-span-1 flex justify-center lg:col-span-1">
              <img
                alt="logo-partenaire-thematique"
                class="h-14"
                :src="`/images/thematiques/${thematique.slug}-active-1.jpg`"
              />
            </div>
            <div class="col-span-1 flex justify-center lg:col-span-1">
              <img
                alt="logo-partenaire-thematique"
                class="h-14"
                :src="`/images/thematiques/${thematique.slug}-active-2.jpg`"
              />
            </div>
            <div class="col-span-1 flex justify-center lg:col-span-1">
              <img
                alt="logo-partenaire-thematique"
                class="h-14"
                :src="`/images/thematiques/${thematique.slug}-active-3.jpg`"
              />
            </div>
            <div class="col-span-1 flex justify-center lg:col-span-1">
              <img
                alt="logo-partenaire-thematique"
                class="h-14"
                :src="`/images/thematiques/${thematique.slug}-active-4.jpg`"
              />
            </div>
            <div class="col-span-1 flex justify-center lg:col-span-1">
              <img
                alt="logo-partenaire-thematique"
                class="h-14"
                :src="`/images/thematiques/${thematique.slug}-active-5.jpg`"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getThematique, getThematiqueStatistics } from '@/api/app'
import MissionsSearch from '@/components/MissionsSearch'
import metadatas from '@/utils/metadatas.json'

export default {
  name: 'FrontThematique',
  metaInfo() {
    return metadatas
      .filter((item) => item.name == 'Thematiques')[0]
      .pages.filter((item) => item.slug == this.slug)[0].metaInfo
  },
  components: {
    MissionsSearch,
  },
  props: {
    slug: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      baseUrl: process.env.MIX_API_BASE_URL,
      thematique: {},
      statistics: null,
    }
  },
  created() {
    this.$store.commit('setLoading', true)
    getThematique(this.slug)
      .then((response) => {
        this.thematique = { ...response.data }
        if (!this.thematique.published) {
          this.$message({
            message: "Ce domaine d'action n'est pas encore accessible !",
            type: 'warning',
          })
          this.$router.push('/403')
        } else {
          getThematiqueStatistics(this.thematique.id).then((response) => {
            this.statistics = { ...response.data }
            this.$store.commit('setLoading', false)
          })
        }
      })
      .catch(() => {})
  },
  methods: {},
}
</script>
