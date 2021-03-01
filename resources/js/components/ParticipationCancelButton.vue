<template>
  <div>
    <el-button type="danger" @click="handleCancel"
      >Annuler ma participation</el-button
    >

    <cancel-participation-dialog
      :participation="form"
      :is-visible="cancelParticipationDialog"
      @close="cancelParticipationDialog = false"
      @updated="onCancelSubmit"
    />
  </div>
</template>

<script>
import CancelParticipationDialog from '@/components/dialogs/CancelParticipationDialog'

export default {
  name: 'ParticipationCancelButton',
  components: { CancelParticipationDialog },
  props: {
    form: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      cancelParticipationDialog: false,
    }
  },
  computed: {},
  methods: {
    onCancelSubmit() {
      this.form.state = 'Annulée'
      this.$emit('updated')
    },
    handleCancel() {
      //this.form.state = 'Annulée'
      this.cancelParticipationDialog = true
    },
  },
}
</script>
