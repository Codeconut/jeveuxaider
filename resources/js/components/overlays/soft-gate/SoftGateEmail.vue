<template>
  <div class="mx-auto max-w-sm">
    <div class="">
      <FranceConnect is-dark class="text-center" />
    </div>
    <div class="relative my-7">
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-300"></div>
      </div>
      <div class="relative flex justify-center">
        <span class="px-2 bg-gray-100 text-gray-400">
          Ou renseignez votre e-mail
        </span>
      </div>
    </div>
    <el-form
      ref="emailForm"
      :model="form"
      :rules="rules"
      class="mb-0 form-center"
    >
      <el-form-item prop="email" class="mb-5">
        <input
          v-model.trim="form.email"
          class="input-shadow text-center bg-white px-5 py-1 w-full rounded-full text-gray-900 placeholder-gray-400 focus:outline-none focus:shadow-outline"
          placeholder="Votre e-mail"
          @keyup.enter="onSubmit"
        />
      </el-form-item>

      <button
        class="font-bold max-w-sm mx-auto w-full flex items-center justify-center px-5 py-3 border border-transparent text-xl leading-6 rounded-full text-white bg-green-400 hover:bg-green-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
        @click.prevent="onSubmit"
      >
        Continuer
      </button>
    </el-form>
  </div>
</template>

<script>
import FranceConnect from '@/components/FranceConnect.vue'
import { getUserFirstname } from '@/api/user'

export default {
  name: 'SoftGateEmail',
  components: { FranceConnect },
  data() {
    return {
      form: {},
      rules: {
        email: [
          {
            type: 'email',
            message: "Le format de l'email n'est pas correct",
            trigger: 'blur',
          },
          {
            required: true,
            message: 'Veuillez renseigner votre email',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  methods: {
    onSubmit() {
      this.$refs['emailForm'].validate((valid) => {
        if (valid) {
          getUserFirstname(this.form.email).then((res) => {
            if (!res.data) {
              this.$emit('register', { email: this.form.email })
            } else {
              this.$emit('login', res.data)
            }
          })
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped>
::placeholder
  font-weight: 500
</style>
