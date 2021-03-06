<template>
  <div>
    <template v-if="!loading">
      <div class="absolute" style="height: 360px">
        <img
          src="/images/bg_header_mission.jpg"
          class="object-cover w-full h-full"
        />
        <div class="bg-blue-900 opacity-25 absolute inset-0"></div>
      </div>

      <div class="relative mb-12">
        <breadcrumb
          theme="transparent"
          :items="[
            { label: 'Missions de bénévolat', link: '/missions' },
            {
              label: domainName(mission),
              link: `/missions?refinementList[domaines][0]=${domainName(
                mission
              )}`,
            },
            {
              label: `Bénévolat ${mission.structure.name} à ${mission.city}`,
              h1: true,
            },
          ]"
        />
        <div class="container mx-auto px-4">
          <div class="bg-white rounded-lg shadow-lg">
            <div class="lg:flex">
              <div class="flex-grow px-6 py-8 lg:flex-shrink-1 lg:p-12">
                <div class="mb-4">
                  <div class="-m-2 flex flex-wrap">
                    <span
                      v-if="domainName(mission)"
                      class="m-2 inline-flex px-3 py-1 rounded-full text-sm leading-5 font-semibold tracking-wide uppercase bg-indigo-100 text-blue-900"
                      >{{ domainName(mission) }}</span
                    >
                    <template v-if="mission.tags">
                      <span
                        v-for="tag in mission.tags"
                        :key="tag.id"
                        class="m-2 inline-flex px-3 py-1 rounded-full text-sm leading-5 font-semibold tracking-wide uppercase bg-gray-100 text-gray-900"
                      >
                        {{ tag.name.fr }}
                      </span>
                    </template>
                  </div>
                </div>

                <h2
                  class="mt-4 pb-3 text-2xl sm:text-4xl leading-7 sm:leading-10 font-bold text-gray-900"
                >
                  {{ mission.name }}
                </h2>

                <div class="mb-8">
                  <ul
                    class="mt-5 lg:grid lg:grid-cols-12 lg:gap-x-6 lg:gap-y-5"
                  >
                    <li class="flex items-start lg:col-span-6">
                      <div class="flex-shrink-0">
                        <template
                          v-if="mission.type == 'Mission en présentiel'"
                        >
                          <img
                            src="/images/picker.svg"
                            width="29"
                            class="mt-2"
                          />
                        </template>
                        <template v-else>
                          <img
                            src="/images/maison.svg"
                            width="29"
                            class="mt-2"
                          />
                        </template>
                      </div>
                      <p class="ml-4 text-md font-bold leading-5 text-gray-900">
                        <span
                          class="uppercase text-sm text-gray-500 font-medium"
                        >
                          <template
                            v-if="mission.type == 'Mission en présentiel'"
                          >
                            Mission en présentiel
                          </template>
                          <template v-else> Mission à distance </template>
                        </span>

                        <br />

                        <template
                          v-if="mission.type == 'Mission en présentiel'"
                        >
                          {{ mission.full_address }}
                        </template>
                        <template v-else
                          >Réalisez cette mission depuis chez vous</template
                        >
                      </p>
                    </li>

                    <li class="mt-5 flex items-start lg:col-span-6 lg:mt-0">
                      <div class="flex-shrink-0">
                        <img src="/images/public.svg" width="22" class="mt-2" />
                      </div>

                      <div
                        class="ml-4 text-md font-bold leading-5 text-gray-900"
                      >
                        <span
                          class="uppercase text-sm text-gray-500 font-medium"
                          >Bénéficiaires</span
                        ><br />
                        <div
                          v-for="(
                            publicBeneficiaire, key
                          ) in mission.publics_beneficiaires"
                          :key="key"
                        >
                          {{
                            publicBeneficiaire
                              | labelFromValue('mission_publics_beneficiaires')
                          }}
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>

                <hr class="border-gray-200 mb-8" />

                <div>
                  <h2 class="text-lg font-medium">
                    <span>L'organisation</span>
                    <b class="text-blue-800">
                      {{ structure.name }}
                    </b>
                  </h2>
                </div>

                <div
                  v-if="structure.description"
                  class="mt-2 text-base leading-7 text-gray-600"
                >
                  <ReadMore
                    more-str="Lire plus"
                    :text="structure.description"
                    :max-chars="250"
                  ></ReadMore>
                </div>
              </div>

              <div
                class="aside text-center bg-blue-800 rounded-b-lg lg:rounded-b-none lg:rounded-r-lg lg:flex-shrink-0 lg:flex lg:flex-col lg:justify-center"
              >
                <div class="py-8 px-6 lg:p-12">
                  <div
                    class="text-base leading-6 text-indigo-200 mb-2"
                    v-html="formattedDate"
                  />
                  <div
                    class="text-base leading-6 text-indigo-200 mb-4 lg:mb-12"
                    v-text="mission.format"
                  />

                  <div
                    class="inline-flex px-5 py-1 rounded-full text-sm leading-5 font-semibold tracking-wide uppercase bg-indigo-100 text-blue-800"
                  >
                    <template v-if="mission.has_places_left === true">
                      {{ mission.places_left | formatNumber }}
                      {{
                        mission.places_left
                          | pluralize([
                            'place disponible',
                            'places disponibles',
                          ])
                      }}
                    </template>
                    <template v-else-if="mission.has_places_left === false">
                      Complet
                    </template>
                    <template v-else>Nombre de places non défini</template>
                  </div>
                  <div class="text-indigo-300 text-sm mt-1">
                    {{ mission.participations_max | formatNumber }}
                    {{
                      mission.participations_max
                        | pluralize([
                          'bénévole recherché',
                          'bénévoles recherchés',
                        ])
                    }}
                  </div>

                  <div
                    class="mt-4 flex items-center justify-center text-5xl leading-none font-extrabold text-gray-900"
                  />

                  <div class="mt-6">
                    <ButtonJeProposeMonAide :mission="mission" />
                  </div>

                  <!-- <p
                    v-if="mission.structure.response_time"
                    class="text-sm leading-6 text-indigo-300"
                  >
                    Délai de réponse:
                    {{ responseTime }}
                  </p> -->

                  <template v-if="mission.state && mission.state == 'Validée'">
                    <div
                      class="mt-8 lg:mt-12 block text-center text-sm leading-2 font-medium text-indigo-300 max-w-xs mx-auto"
                    >
                      À plusieurs on est meilleur ! Et si vous partagiez cette
                      mission à votre entourage ?
                    </div>

                    <div class="mt-5">
                      <div class="-m-3 flex justify-center">
                        <!-- Mail -->
                        <a
                          :href="`mailto:?&subject=${mission.name}&body=${baseUrl}${$router.currentRoute.fullPath}`"
                          class="m-3 text-indigo-300 hover:text-white transition"
                        >
                          <svg
                            x="0px"
                            y="0px"
                            viewBox="0 0 41 38"
                            style="enable-background: new 0 0 41 38"
                            xml:space="preserve"
                            class="h-6 w-7"
                          >
                            <path
                              fill="currentColor"
                              d="M37.6,7.9v22.2H3.4V7.9H37.6z M41,4.8H0v28.5h41V4.8z M37.6,30.1L26.4,19.6l-5.9,5.5l-5.8-5.6L3.4,30.1L12,17.4 L3.4,7.9l17.1,11.8l17-11.8L29,17.4L37.6,30.1z"
                            />
                          </svg>
                        </a>

                        <!-- Facebook -->
                        <a
                          target="_blank"
                          :href="`https://www.facebook.com/sharer/sharer.php?u=${baseUrl}${$router.currentRoute.fullPath}`"
                          class="m-3 text-indigo-300 hover:text-white transition"
                        >
                          <svg class="h-6 w-6" viewBox="0 0 155.139 155.139">
                            <path
                              d="m89.584 155.139v-70.761h23.742l3.562-27.585h-27.304v-17.609c0-7.984 2.208-13.425 13.67-13.425l14.595-.006v-24.673c-2.524-.328-11.188-1.08-21.272-1.08-21.057 0-35.473 12.853-35.473 36.452v20.341h-23.814v27.585h23.814v70.761z"
                              fill="currentColor"
                            />
                          </svg>
                        </a>

                        <!-- Twitter -->
                        <a
                          target="_blank"
                          :href="`https://twitter.com/intent/tweet?url=${baseUrl}${$router.currentRoute.fullPath}`"
                          class="m-3 text-indigo-300 hover:text-white transition"
                        >
                          <svg
                            class="w-6 h-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path
                              d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84"
                            ></path>
                          </svg>
                        </a>

                        <!-- Linkedin -->
                        <a
                          target="_blank"
                          :href="`https://www.linkedin.com/shareArticle?mini=true&url=${baseUrl}${$router.currentRoute.fullPath}`"
                          class="m-3 text-indigo-300 hover:text-white transition"
                        >
                          <svg
                            class="w-6 h-6"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M16.338 16.338H13.67V12.16c0-.995-.017-2.277-1.387-2.277-1.39 0-1.601 1.086-1.601 2.207v4.248H8.014v-8.59h2.559v1.174h.037c.356-.675 1.227-1.387 2.526-1.387 2.703 0 3.203 1.778 3.203 4.092v4.711zM5.005 6.575a1.548 1.548 0 11-.003-3.096 1.548 1.548 0 01.003 3.096zm-1.337 9.763H6.34v-8.59H3.667v8.59zM17.668 1H2.328C1.595 1 1 1.581 1 2.298v15.403C1 18.418 1.595 19 2.328 19h15.34c.734 0 1.332-.582 1.332-1.299V2.298C19 1.581 18.402 1 17.668 1z"
                              clip-rule="evenodd"
                            ></path>
                          </svg>
                        </a>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>

          <div
            v-if="
              (mission.state == 'Validée' ||
                mission.user_id == $store.getters.user.id ||
                $store.getters.contextRole == 'admin') &&
              mission.information
            "
            class="comment-wrapper mt-20 lg:text-center"
          >
            <div class="comment-wrapper--icon absolute mt-1">
              <svg
                class="z-1 mr-28 h-32 w-32 text-blue-100"
                stroke="currentColor"
                fill="none"
                viewBox="0 0 144 144"
              >
                <path
                  stroke-width="2"
                  d="M41.485 15C17.753 31.753 1 59.208 1 89.455c0 24.664 14.891 39.09 32.109 39.09 16.287 0 28.386-13.03 28.386-28.387 0-15.356-10.703-26.524-24.663-26.524-2.792 0-6.515.465-7.446.93 2.327-15.821 17.218-34.435 32.11-43.742L41.485 15zm80.04 0c-23.268 16.753-40.02 44.208-40.02 74.455 0 24.664 14.891 39.09 32.109 39.09 15.822 0 28.386-13.03 28.386-28.387 0-15.356-11.168-26.524-25.129-26.524-2.792 0-6.049.465-6.98.93 2.327-15.821 16.753-34.435 31.644-43.742L121.525 15z"
                ></path>
              </svg>
            </div>
            <h3
              class="z-10 relative text-2xl leading-8 font-bold tracking-tight text-gray-900 sm:text-4xl sm:leading-10"
            >
              Le mot de l'organisation
            </h3>

            <div
              class="mt-4 relative max-w-4xl text-xl sm:text-2xl leading-9 text-gray-500 lg:mx-auto"
            >
              <ReadMore
                more-str="Lire plus"
                :text="mission.information"
                :max-chars="235"
              ></ReadMore>
            </div>
            <div class="mt-6">
              <span class="text-lg font-medium">
                <span>L'organisation</span>
                <b class="text-blue-800">
                  {{ structure.name }}
                </b>
              </span>
            </div>
          </div>

          <hr class="border-gray-200 my-12" />

          <ul class="md:grid md:grid-cols-2 gap-x-12">
            <li>
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-blue-800 rounded-lg"
                  >
                    <svg
                      class="h-6 w-6 text-white"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                      ></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-4">
                  <h4 class="text-lg leading-6 font-bold text-gray-900">
                    Objectifs de votre mission
                  </h4>
                  <div class="mt-2 text-base leading-7 text-gray-500">
                    <ReadMore
                      more-str="Lire plus"
                      :text="mission.objectif"
                      :max-chars="380"
                    ></ReadMore>
                  </div>
                </div>
              </div>
            </li>
            <li class="mt-10 md:mt-0">
              <div class="flex">
                <div class="flex-shrink-0">
                  <div
                    class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-blue-800 rounded-lg"
                  >
                    <svg
                      class="h-6 w-6 text-white"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                      ></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-4">
                  <h4 class="text-lg leading-6 font-bold text-gray-900">
                    Description et règles à appliquer
                  </h4>
                  <div class="mt-2 text-base leading-7 text-gray-500">
                    <ReadMore
                      more-str="Lire plus"
                      :text="mission.description"
                      :max-chars="380"
                    ></ReadMore>
                  </div>
                </div>
              </div>
            </li>
          </ul>

          <div
            v-if="mission.template && [4].includes(mission.template.id)"
            class="mt-16 text-center"
          >
            <h4 class="text-lg leading-6 font-bold text-gray-900">
              Quelques pistes pour l'écoute téléphonique
            </h4>

            <div class="mt-2 text-gray-500">
              <p>
                <a
                  class="inline-flex items-center hover:underline"
                  target="_blank"
                  href="/files/PFP_Asso_Fiche_telephonie_COVID19__2020_03_23_.pdf"
                >
                  Télécharger la fiche pratique
                  <svg
                    style="margin-top: 2px"
                    data-v-18b25a0c
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    class="h-4 w-4 text-gray-400"
                  >
                    <path
                      data-v-18b25a0c
                      fill-rule="evenodd"
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </a>
              </p>
            </div>
          </div>

          <template v-if="mission.state && mission.state == 'Validée'">
            <hr class="border-gray-200 my-12" />

            <div class="text-center pb-20">
              <h2
                class="text-4xl leading-9 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-10"
              >
                Prêt à rejoindre le mouvement&nbsp;?
              </h2>
              <p
                class="mt-4 relative max-w-4xl text-xl sm:text-2xl leading-7 sm:leading-9 text-gray-500 lg:mx-auto"
              >
                Inscrivez-vous à la mission et l'organisation vous recontactera
                dans les plus brefs délais. Vous pourrez aussi échanger avec
                elle pour obtenir plus de précisions.
              </p>
              <div class="mt-10 flex justify-center z-10 relative">
                <ButtonJeProposeMonAide :mission="mission" size="big" />
              </div>
              <div class="mt-8 z-1">
                <div class="text-center justify-center">
                  <img
                    class="mx-auto w-full h-auto md:w-auto md:h-full opacity-50"
                    src="/images/chacunpourtous.png"
                    style="max-height: 7rem"
                  />
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </template>
    <template v-else>
      <front-mission-loading />
    </template>

    <div v-if="otherMissions.total > 0" class="container mx-auto px-4 pb-12">
      <div class="bg-white shadow overflow-hidden rounded-lg">
        <div
          class="bg-white px-4 py-3 flex items-center justify-between sm:px-6"
        >
          <div>
            <p class="text-2xl sm:leading-10 font-bold text-gray-900">
              Autres missions proposées par cette organisation
            </p>
          </div>
        </div>
        <ul>
          <li
            v-for="otherMission in otherMissions.data"
            :key="otherMission.id"
            class="border-t border-gray-200"
          >
            <router-link
              class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out"
              :to="`/missions/${otherMission.id}/${otherMission.slug}`"
            >
              <div class="p-4 sm:p-6 md:p-8">
                <div class="flex items-center">
                  <div
                    class="hidden sm:block flex-shrink-0 bg-primary rounded-md p-3 text-center"
                  >
                    <img
                      v-if="otherMission.template"
                      alt=""
                      :src="otherMission.template.image"
                      style="width: 28px"
                    />
                    <img
                      v-else-if="
                        otherMission.domaine && otherMission.domaine.image
                      "
                      alt=""
                      :src="otherMission.domaine.image"
                      style="width: 28px"
                    />
                  </div>
                  <div class="min-w-0 flex-1 sm:pl-4">
                    <div
                      class="flex items-center justify-between flex-wrap sm:flex-no-wrap -m-2"
                    >
                      <div class="m-2 min-w-0 flex-shrink">
                        <div
                          class="text-sm leading-5 uppercase font-medium text-gray-500 truncate"
                        >
                          {{ mission.structure.name }}
                        </div>
                        <div
                          class="text-sm md:text-base lg:text-lg xl:text-xl font-semibold text-gray-900 truncate"
                        >
                          {{ otherMission.name }}
                        </div>
                      </div>

                      <div
                        v-if="
                          otherMission.has_places_left &&
                          otherMission.places_left > 0
                        "
                        class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
                        style="background: #31c48d"
                      >
                        <template
                          v-if="
                            otherMission.has_places_left &&
                            otherMission.places_left > 0
                          "
                        >
                          {{ otherMission.places_left | formatNumber }}
                          {{
                            otherMission.places_left
                              | pluralize([
                                'bénévole recherché',
                                'bénévoles recherchés',
                              ])
                          }}
                        </template>
                        <template v-else>Complet</template>
                      </div>
                      <div
                        v-else
                        class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
                        style="background: #d2d6dc"
                      >
                        <span v-if="otherMission.has_places_left === false"
                          >Complet</span
                        >
                        <span v-else>Nombre de places non défini</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div
                  class="flex items-center flex-wrap text-s leading-5 text-gray-500 mt-4"
                >
                  <template
                    v-if="
                      otherMission.city &&
                      otherMission.type == 'Mission en présentiel'
                    "
                  >
                    <span
                      v-if="otherMission.department"
                      class="mr-3 mt-1 px-2.5 py-1.5 border border-gray-200 text-xs leading-4 font-medium rounded-full text-gray-500 bg-white"
                      >Mission en présentiel - {{ otherMission.city }} ({{
                        otherMission.department
                      }})</span
                    >
                    <span
                      v-else
                      class="mr-3 mt-1 px-2.5 py-1.5 border border-gray-200 text-xs leading-4 font-medium rounded-full text-gray-500 bg-white"
                      >Mission en présentiel - {{ otherMission.city }}</span
                    >
                  </template>
                  <template v-else>
                    <span
                      class="mr-3 mt-1 px-2.5 py-1.5 border border-gray-200 text-xs leading-4 font-medium rounded-full text-gray-500 bg-white"
                      >Mission à distance</span
                    >
                  </template>

                  <span
                    v-if="otherMission.domaines[0]"
                    class="mr-3 mt-1 px-2.5 py-1.5 border border-gray-200 text-xs leading-4 font-medium rounded-full text-gray-500 bg-white"
                    >{{ otherMission.domaines[0].name.fr }}</span
                  >
                </div>
              </div>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { getMission } from '@/api/mission'
import { fetchStructureAvailableMissions } from '@/api/structure'
import FrontMissionLoading from '@/components/loadings/FrontMissionLoading'
import ButtonJeProposeMonAide from '@/components/ButtonJeProposeMonAide'
import ReadMore from '@/components/ReadMore'
import dayjs from 'dayjs'

export default {
  name: 'Mission',
  metaInfo() {
    return {
      title: this.mission.name
        ? 'Bénévolat pour ' + this.structure.name + ' | ' + this.mission.name
        : this.mission.name,
      meta: [
        {
          name: 'description',
          content:
            this.structure && this.structure.description
              ? this.$options.filters.truncate(
                  this.structure.description.replace(/<\/?[^>]+>/gi, ' '),
                  156
                )
              : '',
        },
      ],
    }
  },
  components: {
    FrontMissionLoading,
    ReadMore,
    ButtonJeProposeMonAide,
  },
  props: {
    id: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      loading: true,
      mission: {},
      otherMissions: {},
      baseUrl: process.env.MIX_API_BASE_URL,
      dialogLoading: false,
      form: {
        content: `Bonjour,\nJe souhaite participer à cette mission et apporter mon aide. \nJe me tiens disponible pour échanger et débuter la mission 🙂\n`,
      },
      rules: {
        content: [
          {
            required: true,
            message: 'Entrez un message.',
            trigger: 'blur',
          },
          {
            min: 10,
            message: 'Votre message est trop court.',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  computed: {
    structure() {
      return this.mission.structure
    },
    structureType() {
      const status = this.$options.filters
        .labelFromValue(
          this.structure.statut_juridique,
          'structure_legal_status'
        )
        .toLowerCase()
      return `L'${status}`
    },
    responseTime() {
      let daysDelay = this.$options.filters.daysFromTimestamp(
        this.mission.structure.response_time
      )
      if (daysDelay < 5) {
        return 'Moins de 5 jours'
      } else if (daysDelay < 10) {
        return 'Entre 5 et 10 jours'
      }
      return 'Moins de 15 jours'
    },
    formattedDate() {
      const startDate = this.mission.start_date
      const endDate = this.mission.end_date

      if (!endDate) {
        return null
      }

      if (dayjs(startDate).format('YYYY') != dayjs(endDate).format('YYYY')) {
        return `Du <b class="text-white">${dayjs(startDate).format(
          'D MMMM YYYY'
        )}</b> au <b class="text-white">${dayjs(endDate).format(
          'D MMMM YYYY'
        )}</b>`
      } else {
        return `Du <b class="text-white">${dayjs(startDate).format(
          'D MMMM'
        )}</b> au <b class="text-white">${dayjs(endDate).format(
          'D MMMM YYYY'
        )}</b>`
      }
    },
  },
  created() {
    getMission(this.id)
      .then((response) => {
        this.mission = { ...response.data }
        this.loading = false
        if (this.mission.responsable && this.$store.getters.profile) {
          this.form.content = `Bonjour ${this.mission.responsable.first_name},\nJe souhaite participer à cette mission et apporter mon aide. \nJe me tiens disponible pour échanger et débuter la mission 🙂\n${this.$store.getters.profile.first_name}`
        }
        fetchStructureAvailableMissions(this.mission.structure.id, {
          exclude: this.id,
          append: 'domaines',
        })
          .then((response) => {
            this.otherMissions = response.data
          })
          .catch(() => {
            this.loading = false
          })
      })
      .catch(() => {
        this.loading = false
      })
  },
  methods: {
    domainName(mission) {
      return mission.domaine && mission.domaine.name && mission.domaine.name.fr
        ? mission.domaine.name.fr
        : mission.template &&
          mission.template.domaine &&
          mission.template.domaine.name &&
          mission.template.domaine.name.fr
        ? mission.template.domaine.name.fr
        : null
    },
  },
}
</script>

<style lang="sass" scoped>
.aside
  @screen lg
    max-width: 410px
    @apply flex-none w-full

.comment-wrapper
  min-height: 200px
  ::v-deep ul,::v-deep ol
    @apply flex flex-col items-center
  @screen lg
    @apply relative
  .comment-wrapper--icon
    @apply hidden
    @screen lg
      @apply block
    @screen xl
      left: 5%

::v-deep .el-dialog__title
  @apply text-gray-800 text-xl font-bold
</style>
