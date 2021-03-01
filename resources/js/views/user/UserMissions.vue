<template>
  <div class="bg-gray-100 flex flex-col flex-grow">
    <div v-if="!loading">
      <div class="bg-primary pb-32">
        <div class="container mx-auto px-4">
          <div class="pt-10">
            <h1 class="text-3xl font-bold text-white">
              Missions auxquelles j'ai candidaté
            </h1>
          </div>
        </div>
      </div>
      <div class="-mt-32">
        <div class="container mx-auto px-4 my-12">
          <template v-if="participations.data && !participations.data.length">
            <div
              class="bg-white rounded-lg shadow-lg overflow-hidden px-6 py-8 lg:p-12"
            >
              Vous n'avez aucune participation pour le moment.
            </div>
          </template>
          <template v-else>
            <div class="flex flex-wrap -m-3">
              <div
                v-for="participation in participations.data"
                :key="participation.id"
                class="ais-Hits-item"
              >
                <router-link
                  class="flex flex-col flex-1 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out"
                  :to="
                    participation.conversation
                      ? `/messages/${participation.conversation.id}`
                      : `/missions/${participation.mission.id}/${participation.mission.slug}`
                  "
                >
                  <CardMission
                    :participation="participation"
                    :mission="participation.mission"
                    show-state
                  />
                </router-link>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { fetchProfileParticipations } from '@/api/user'
import CardMission from '@/components/CardMission'

export default {
  name: 'FrontUserMissions',
  components: { CardMission },
  data() {
    return {
      loading: true,
      participations: {},
    }
  },
  created() {
    this.$store.commit('setLoading', true)
    fetchProfileParticipations(this.$store.getters.user.profile.id)
      .then((response) => {
        this.form = response.data
        this.participations = { ...response.data }
        this.$store.commit('setLoading', false)
        this.loading = false
      })
      .catch(() => {
        this.loading = false
      })
  },
  methods: {
    // onClickCancel(participation) {
    //   this.$confirm(
    //     `Vous êtes sur le point d'annuler votre candidature. Voulez-vous continuer ?`,
    //     'Annuler ma candidature',
    //     {
    //       confirmButtonText: 'Annuler ma candidature',
    //       confirmButtonClass: 'el-button--danger',
    //       cancelButtonText: 'Retour',
    //     }
    //   ).then(() => {
    //     cancelParticipation(participation.id).then(() => {
    //       participation.state = 'Annulée'
    //       this.$message({
    //         type: 'success',
    //         message: `Votre candidature a été annulée.`,
    //       })
    //     })
    //   })
    // },
  },
}
</script>

<style lang="sass" scoped>
.ais-Hits-item
  width: 100%
  @apply border-0 shadow-none p-0 m-3
  @screen sm
    width: 292px
  @screen lg
    width: 294px
    @apply flex flex-col
</style>
