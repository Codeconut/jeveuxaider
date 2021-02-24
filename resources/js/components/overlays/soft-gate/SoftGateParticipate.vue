<template>
  <div>
    <div class="text-center mb-6">
      <div class="text-gray-900 font-extrabold text-3xl">
        Proposez votre aide
      </div>
      <div class="text-gray-500 text-xl">
        Vous allez être mis en relation avec
        {{ $store.getters.missionSelected.responsable.first_name }}, responsable
        de la mission chez {{ $store.getters.missionSelected.structure.name }}.
      </div>
    </div>
    <div class="mx-auto max-w-sm">
      <el-form
        ref="participateForm"
        :model="form"
        :rules="rules"
        class="mt-4"
        :hide-required-asterisk="true"
      >
        <el-form-item prop="content">
          <el-input
            v-model="form.content"
            placeholder=""
            :autosize="{ minRows: 3, maxRows: 8 }"
            type="textarea"
            :autofocus="true"
            autocomplete="off"
          ></el-input>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import { addParticipation } from '@/api/participation'

export default {
  name: 'SoftGateParticipate',
  data() {
    return {
      loading: true,
      form: {
        content: `Bonjour ${this.$store.getters.missionSelected.responsable.first_name},\nJe souhaite participer à cette mission et apporter mon aide. \nJe me tiens disponible pour échanger et débuter la mission 🙂\n${this.$store.getters.user.profile.first_name}`,
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
  methods: {
    onSubmit() {
      this.$refs['participateForm'].validate((valid) => {
        if (valid) {
          this.loading = true
          addParticipation(
            this.$store.getters.missionSelected.id,
            this.$store.getters.profile.id,
            this.form.content
          )
            .then(() => {
              this.$router.push('/messages')
              window.apieng && window.apieng('trackApplication')
              this.$message({
                message:
                  'Votre participation a été enregistrée et est en attente de validation !',
                type: 'success',
              })
              this.loading = false
            })
            .catch(() => {
              this.loading = false
            })
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped></style>
