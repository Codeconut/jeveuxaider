<template>
  <div>
    <div class="text-center mb-6">
      <div class="text-gray-900 font-extrabold text-3xl">
        Proposez votre aide
      </div>
      <div class="text-gray-500 text-xl max-w-md mx-auto">
        Vous allez être mis en relation avec
        <strong>{{
          $store.getters.missionSelected.responsable.first_name
        }}</strong
        >, responsable de la mission chez
        <strong>{{ $store.getters.missionSelected.structure.name }}</strong
        >.
      </div>
    </div>
    <div class="mx-auto max-w-sm">
      <el-form
        ref="participateForm"
        :model="form"
        :rules="rules"
        :hide-required-asterisk="true"
        class="mt-4 mb-0 form-center"
      >
        <el-form-item prop="content">
          <textarea
            v-model="form.content"
            placeholder=""
            class="input-shadow w-full bg-white rounded-lg border-0 p-6 leading-6 text-gray-500"
            rows="6"
            autocomplete="off"
          ></textarea>
        </el-form-item>
        <el-button
          :loading="loading"
          class="font-bold max-w-sm mx-auto w-full flex items-center justify-center px-5 py-3 border border-transparent text-2xl lg:text-xl leading-6 rounded-full text-white bg-green-400 hover:bg-green-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
          @click.prevent="onSubmit"
        >
          Envoyer
        </el-button>
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
      loading: false,
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
              window.apieng && window.apieng('trackApplication')
              this.$message({
                message:
                  'Votre participation a été enregistrée et est en attente de validation !',
                type: 'success',
              })
              this.loading = false
              this.$emit('next')
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
