<template>
  <div class="mx-auto max-w-sm">
    <!-- <div class="">
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
    </div> -->
    <div class="text-center mb-6">
      <div
        class="text-gray-900 font-extrabold text-2xl lg:text-3xl leading-8 mb-2 lg:mb-3"
      >
        Avant toute chose
      </div>
      <div class="text-gray-500 font-semibold text-lg lg:text-xl">
        Renseignez votre e-mail
      </div>
    </div>
    <div class="mx-auto max-w-sm">
      <el-form
        ref="emailForm"
        :model="form"
        :rules="rules"
        class="mb-0 form-center"
        @submit.prevent.native="onSubmit"
      >
        <el-form-item prop="email" class="mb-5">
          <input
            v-model.trim="form.email"
            :autofocus="true"
            class="input-shadow text-center bg-white px-5 py-1 w-full rounded-full text-gray-900 placeholder-gray-400 focus:outline-none focus:shadow-outline"
            placeholder="Votre e-mail"
            @keyup.enter.prevent="onSubmit"
          />
        </el-form-item>

        <el-button
          :loading="loading"
          class="font-bold max-w-sm mx-auto w-full flex items-center justify-center px-5 py-3 border border-transparent text-xl leading-6 rounded-full text-white bg-green-400 hover:bg-green-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
          @click.prevent="onSubmit"
        >
          Continuer
        </el-button>
      </el-form>
    </div>
  </div>
</template>

<script>
// import FranceConnect from '@/components/FranceConnect.vue'
import { getUserFirstname } from '@/api/user'

export default {
  name: 'SoftGateEmail',
  // components: { FranceConnect },
  data() {
    return {
      loading: false,
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
          this.loading = true
          getUserFirstname(this.form.email)
            .then((res) => {
              this.loading = false
              if (!res.data) {
                this.$emit('register', { email: this.form.email })
              } else {
                this.$emit('login', res.data)
              }
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

<style lang="sass" scoped>
::placeholder
  font-weight: 500
</style>
