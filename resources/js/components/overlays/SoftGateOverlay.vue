<template>
  <div class="fixed inset-0 w-full h-full z-50">
    <div
      id="soft-gate"
      class="w-full h-full flex flex-col items-center justify-center"
    >
      <div class="flex flex-col w-full h-full px-4">
        <div
          class="p-4 -mr-4 lg:m-0 lg:p-8 cursor-pointer ml-auto lg:absolute lg:right-0"
          @click="$emit('closed')"
        >
          <img src="/images/close-white.svg" alt="Fermer" width="24px" />
        </div>

        <div
          v-scroll-lock="true"
          class="overflow-y-auto flex-1 flex flex-col lg:justify-center"
        >
          <div class="pb-32 lg:pb-0">
            <div class="text-center text-white text-lg">#ChacunPourTous</div>
            <div class="title text-center text-white font-extrabold mb-4">
              Participez à cette mission
            </div>
            <div
              class="bg-gray-100 rounded-lg max-w-full lg:max-w-2xl mx-auto p-10"
            >
              <SoftGateEmail />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SoftGateEmail from '@/components/overlays/soft-gate/SoftGateEmail'

export default {
  name: 'SoftGateOverlay',
  components: { SoftGateEmail },
  data() {
    return {}
  },
  methods: {
    onClose() {
      this.$emit('closed')
    },
  },
}
</script>

<style lang="sass" scoped>
#soft-gate
  background-color: rgba(25, 22, 130, .95)
  .title
    font-size: 24px
    @screen lg
      font-size: 50px
      letter-spacing: -1px
</style>
