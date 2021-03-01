<template>
  <div class="relative">
    <button
      :class="
        size == 'big'
          ? 'px-12 py-3 pb-4 text-2xl leading-9 font-medium'
          : 'font-bold text-xl px-5 py-3 pb-4'
      "
      class="max-w-sm mx-auto w-full flex items-center justify-center border border-transparent rounded-full text-white bg-green-400 hover:bg-green-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
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
    size: {
      type: String,
      default: 'medium',
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
