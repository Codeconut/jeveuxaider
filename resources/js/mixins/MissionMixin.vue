<script>
export default {
  name: 'MissionMixin',
  props: {
    mission: {
      type: Object,
      default: null,
    },
  },
  computed: {
    thumbnail() {
      let thumbnail = this.mission.template_id
        ? `templates/${this.mission.template_id}`
        : this.mission.thumbnail
        ? `domaines/${this.mission.thumbnail}`
        : `domaines/${this.mission.domaine_id}_1`

      return {
        default: `/images/${thumbnail}.jpg`,
        x2: `/images/${thumbnail}@2x.jpg`,
      }
    },
  },
  methods: {
    defaultThumbnail(e) {
      // @todo vraie image par défaut
      e.target.src = '/images/domaines/3_3.jpg'
      e.target.srcset = '/images/domaines/3_3@2x.jpg 2x'
    },
  },
}
</script>
