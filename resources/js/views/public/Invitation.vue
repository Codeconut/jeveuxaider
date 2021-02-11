<template>
  <div class="relative bg-blue-800 overflow-hidden">
    <img
      class="z-1 object-cover absolute h-screen lg:h-auto"
      src="/images/bg-jva.jpg"
    />

    <div class="pb-12 mt-12 px-4 relative w-full lg:inset-y-0 text-center z-10">
      <div class="">
        <h2
          class="mt-6 mb-4 md:mb-0 text-center text-3xl font-bold text-white leading-8 px-4"
        >
          Invitation à rejoindre la Réserve Civique
        </h2>
        <p class="text-center text-base text-blue-200">
          Engagez-vous pour faire vivre les valeurs de la République
        </p>
      </div>
    </div>

    <div
      v-if="invitation"
      class="relative mt-2 pb-16 sm:mx-auto sm:w-full sm:max-w-md text-left z-10"
    >
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <div class="mb-8 text-center">
          <template v-if="invitation.role == 'responsable_organisation'">
            Vous avez été invité(e) à rejoindre l'organisation
            <strong>{{ invitation.invitable.name }}</strong> en tant que
            responsable.
          </template>
          <template v-if="invitation.role == 'responsable_collectivity'">
            Vous avez été invité(e) à rejoindre la collectivité
            <strong>{{ invitation.invitable.name }}</strong> en tant que
            responsable.
          </template>
          <template v-if="invitation.role == 'responsable_collectivity'">
            Vous avez été invité(e) à rejoindre le réseau
            <strong>{{ invitation.invitable.name }}</strong> en tant que
            superviseur.
          </template>
          <template v-if="invitation.role == 'referent_departemental'">
            Vous avez été invité(e) à rejoindre le département
            <strong>{{
              invitation.properties.referent_departemental
                | labelFromValue('departments')
            }}</strong>
            en tant que référent.
          </template>
          <template v-if="invitation.role == 'referent_regional'">
            Vous avez été invité(e) à rejoindre la région
            <strong>{{ invitation.properties.referent_departemental }}</strong>
            en tant que référent.
          </template>
        </div>

        <div v-if="!$store.getters.isLogged" class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-xl">
            <span class="px-2 bg-white font-bold text-gray-900">
              <template v-if="invitation.is_registered"
                >Je me connecte</template
              >
              <template v-else>Je créé mon compte</template>
            </span>
          </div>
        </div>

        <template v-if="invitation.is_registered">
          <InvitationLoginForm
            v-if="!$store.getters.isLogged"
            :invitation="invitation"
          />
          <template v-else>
            <div class="mt-4">
              <button
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-blue-800 hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
                @click="handleAcceptInvitation"
              >
                J'accepte l'invitation
              </button>
            </div>
          </template>
        </template>
        <template v-else>
          <InvitationRegisterForm :invitation="invitation" />
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import { getInvitation, acceptInvitation } from '@/api/user'
import InvitationRegisterForm from '@/components/forms/InvitationRegisterForm'
import InvitationLoginForm from '@/components/forms/InvitationLoginForm'

export default {
  name: 'Invitation',
  components: { InvitationRegisterForm, InvitationLoginForm },
  props: {
    token: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      invitation: null,
    }
  },
  created() {
    getInvitation(this.token).then((response) => {
      this.invitation = response.data
    })
  },
  methods: {
    handleAcceptInvitation() {
      if (this.$store.getters.user.email == this.invitation.email) {
        acceptInvitation(this.invitation.token).then(() => {
          this.$router.push('/dashboard')
        })
      } else {
        this.$message({
          message: 'Cette invitation ne vous est pas destinée !',
          type: 'error',
        })
      }
    },
  },
}
</script>

<style lang="sass" scoped></style>
