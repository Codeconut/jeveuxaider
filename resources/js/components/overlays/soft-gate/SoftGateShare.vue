<template>
  <div>
    <div class="text-center mb-6">
      <div class="text-gray-900 font-extrabold text-3xl">
        Votre candidature est enregistrée
      </div>
      <div class="text-gray-500 text-xl max-w-md mx-auto">
        Et si vous invitiez vos proches à participer eux aussi à cette mission ?
      </div>
    </div>
    <div class="">
      <div class="text-xl text-center">Partagez la mission autour de vous</div>
      <div
        class="flex flex-wrap text-gray-900 font-semibold justify-center space-x-3 my-10"
      >
        <a
          target="_blank"
          :href="`https://www.facebook.com/sharer/sharer.php?u=${baseUrl}${$router.currentRoute.fullPath}`"
          class="input-shadow bg-white h-24 w-24 rounded-full flex justify-center items-center cursor-pointer hover:shadow-lg"
        >
          <img
            src="/images/icones/share-facebook.svg"
            alt="Partagez la mission sur Facebook"
          />
        </a>
        <a
          target="_blank"
          :href="`https://twitter.com/intent/tweet?url=${baseUrl}${$router.currentRoute.fullPath}`"
          class="input-shadow bg-white h-24 w-24 rounded-full flex justify-center items-center cursor-pointer hover:shadow-lg"
        >
          <img
            src="/images/icones/share-twitter.svg"
            alt="Partagez la mission sur Twitter"
          />
        </a>
        <a
          target="_blank"
          :href="`https://www.linkedin.com/shareArticle?mini=true&url=${baseUrl}${$router.currentRoute.fullPath}`"
          class="input-shadow bg-white h-24 w-24 rounded-full flex justify-center items-center cursor-pointer hover:shadow-lg"
        >
          <img
            src="/images/icones/share-linkedin.svg"
            alt="Partagez la mission sur Linkedin"
          />
        </a>
        <a
          :href="`mailto:?&subject=${$store.getters.missionSelected.name}&body=${baseUrl}${$router.currentRoute.fullPath}`"
          class="input-shadow bg-white h-24 w-24 rounded-full flex justify-center items-center cursor-pointer hover:shadow-lg"
        >
          <img
            src="/images/icones/share-mail.svg"
            alt="Partagez la mission par mail"
          />
        </a>
      </div>
      <el-button
        class="font-bold text-gray-500 max-w-sm mx-auto w-full flex items-center justify-center px-5 py-3 border border-transparent text-2xl lg:text-xl leading-6 rounded-full bg-white focus:outline-none focus:shadow-outline transition duration-150 ease-in-out hover:shadow-lg"
        @click.prevent="onSkip"
      >
        Passer
      </el-button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SoftGateShare',
  data() {
    return {
      baseUrl: process.env.MIX_API_BASE_URL,
    }
  },
  methods: {
    onSkip() {
      this.$emit('next')
    },
  },
}
</script>

<style lang="sass" scoped></style>
