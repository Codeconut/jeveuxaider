<template>
  <div class="relative">
    <button
      class="font-bold max-w-sm mx-auto w-full flex items-center justify-center px-5 py-3 border border-transparent text-2xl lg:text-xl leading-6 rounded-full text-white bg-green-400 hover:bg-green-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
      @click="onClick"
    >
      Je propose mon aide
    </button>
  </div>
</template>

<script>
export default {
  name: 'ButtonJeProposeMonAide',
  props: {
    mission: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {}
  },
  computed: {},
  methods: {
    onClick() {
      this.$store.commit('toggleSoftGateOverlay')
      this.$store.commit('setMissionSelected', this.mission)
    },
  },
}
</script>
