<template>
  <el-dialog
    :close-on-click-modal="false"
    title="Décliner la participation"
    width="100%"
    :visible="isVisible"
    style="max-width: 600px; margin: auto"
    @close="$emit('close')"
  >
    <div class="mb-2">
      Vous êtes sur le point de décliner la participation de
      {{ participation.profile.full_name }}.
    </div>
    <el-form
      ref="form"
      :model="form"
      :rules="rules"
      class="mt-4"
      :hide-required-asterisk="true"
    >
      <el-form-item label="Raison" prop="reason">
        <el-radio-group v-model="form.reason">
          <el-radio
            v-for="item in $store.getters.taxonomies
              .participation_declined_reasons.terms"
            :key="item.value"
            :label="item.value"
            class="w-full mb-2"
            >{{ item.label }}</el-radio
          >
        </el-radio-group>
      </el-form-item>
      <el-form-item label="Message" prop="content">
        <el-input
          v-model="form.content"
          placeholder="Plus d'explications si nécéssaire"
          :autosize="{ minRows: 3, maxRows: 8 }"
          type="textarea"
          :autofocus="true"
          autocomplete="off"
        ></el-input>
      </el-form-item>
    </el-form>
    <span slot="footer" class="dialog-footer">
      <el-button @click="$emit('close')"> Annuler </el-button>
      <el-button :loading="loading" type="primary" @click="handleDeclineSubmit"
        >Décliner la participation</el-button
      >
    </span>
  </el-dialog>
</template>

<script>
import { declineParticipation } from '@/api/participation'

export default {
  name: 'DeclineParticipationDialog',
  props: {
    isVisible: {
      type: Boolean,
      required: true,
    },
    participation: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      form: {},
      rules: {
        reason: [
          {
            required: true,
            message: 'Merci de sélectionner une raison',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  methods: {
    handleDeclineSubmit() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          this.loading = true
          declineParticipation(this.participation.id, this.form)
            .then(() => {
              this.loading = false
              this.$message({
                type: 'success',
                message: 'La participation a été déclinée',
              })
              this.$emit('updated')
              this.$emit('close')
            })
            .catch((error) => {
              this.errors = error.response.data.errors
            })
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped>
.el-form-item
  @apply mb-2
  .el-form-item__label
    @apply font-semibold
  ::v-deep
    .el-radio
      .el-radio__inner
        width: 20px
        height: 20px
        border-color: #F3F3F3
        background: #F3F3F3
        transition: all .25s
        box-shadow: none !important
        &::after
          background: #F3F3F3
          width: 11px
          height: 100%
          background-repeat: no-repeat
          background-position: center
          transform: translate(-50%, -50%) scale(1)
      .el-radio__input.is-checked
        .el-radio__inner
          border-color: #E6EAF5
          background: #E6EAF5
          &::after
            background: url(/images/check-primary.svg)
            background-repeat: no-repeat
            background-position: center
</style>
