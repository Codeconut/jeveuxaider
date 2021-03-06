<template>
  <div
    v-if="!$store.getters.loading"
    class="profile-form max-w-2xl pl-12 pb-12"
  >
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">Domaine d'action</div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl">{{ form.name }}</div>
      </div>
    </template>
    <div v-else class="mb-12 font-bold text-2xl text-gray-800">
      Nouveau domaine d'action
    </div>

    <el-form
      ref="thematiqueForm"
      :model="form"
      label-position="top"
      :rules="rules"
    >
      <div class="mb-6 text-xl text-gray-800">Informations générales</div>

      <el-form-item label="Nom du domaine d'action" prop="name">
        <el-input
          v-model="form.name"
          placeholder="Ex: Solidarité et instertion"
        />
        <item-description>
          Accessible à l'adresse : {{ baseUrl }}/domaines-action/{{
            form.name | slugify
          }}
        </item-description>
      </el-form-item>

      <el-form-item label="Titre du domaine d'action" prop="title">
        <el-input
          v-model="form.title"
          placeholder="Ex: Rejoignez JeVeuxAider.gouv.fr pour la solidarité et l'insertion"
        />
      </el-form-item>

      <el-form-item label="Description" prop="description" class="flex-1">
        <el-input
          v-model="form.description"
          type="textarea"
          :autosize="{ minRows: 2, maxRows: 6 }"
          placeholder="Description de ce domaine d'action"
        />
      </el-form-item>

      <el-form-item label="Couleur" prop="color">
        <el-input v-model="form.color" placeholder="Ex: blue-800, green-500" />
      </el-form-item>

      <el-form-item label="Domaine d'action" prop="domaine_id" class="flex-1">
        <el-select
          v-model="form.domaine_id"
          clearable
          placeholder="Sélectionner un domaine d'action"
        >
          <el-option
            v-for="item in tags"
            :key="item.id"
            :label="item.name.fr"
            :value="item.id"
          ></el-option>
        </el-select>
      </el-form-item>

      <ImageField
        :model="model"
        :model-id="form.id ? form.id : null"
        :min-width="1600"
        :min-height="600"
        :aspect-ratio="8 / 3"
        :field="form.image"
        label="Photo du domaine d'action"
        @add-or-crop="photo = $event"
        @delete="photo = null"
      ></ImageField>

      <div class="mb-6 flex text-xl text-gray-800">Visibilité</div>
      <item-description container-class="mb-6">
        Si vous souhaitez rendre ce domaine d'action visible, cochez la case.
      </item-description>
      <el-form-item prop="published" class="flex-1">
        <el-checkbox v-model="form.published">En ligne</el-checkbox>
      </el-form-item>

      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit"
          >Enregistrer</el-button
        >
      </div>
    </el-form>
  </div>
</template>

<script>
import {
  getThematique,
  addOrUpdateThematique,
  uploadImage,
  fetchTags,
} from '@/api/app'
import ItemDescription from '@/components/forms/ItemDescription'
import ImageField from '@/components/forms/ImageField.vue'

export default {
  name: 'DashboardThematiqueForm',
  components: { ItemDescription, ImageField },
  props: {
    mode: {
      type: String,
      required: true,
    },
    id: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      baseUrl: process.env.MIX_API_BASE_URL,
      loading: false,
      tags: [],
      form: {
        published: true,
      },
      model: 'thematique',
      photo: null,
    }
  },
  computed: {
    rules() {
      let rules = {
        name: [
          {
            required: true,
            message: "Veuillez renseigner le nom du domaine d'action",
            trigger: 'blur',
          },
        ],
      }

      return rules
    },
  },
  created() {
    fetchTags({ 'filter[type]': 'domaine' }).then((response) => {
      this.tags = response.data.data
    })
    if (this.mode == 'edit') {
      this.$store.commit('setLoading', true)
      getThematique(this.id)
        .then((response) => {
          this.$store.commit('setLoading', false)
          this.form = response.data
        })
        .catch(() => {
          this.loading = false
        })
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['thematiqueForm'].validate((valid) => {
        if (valid) {
          addOrUpdateThematique(this.id, this.form)
            .then((response) => {
              this.form = response.data
              if (this.photo) {
                uploadImage(
                  this.form.id,
                  this.model,
                  this.photo.blob,
                  this.photo.cropSettings
                ).then(() => {
                  this.onSubmitEnd()
                })
              } else {
                this.onSubmitEnd()
              }
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.loading = false
        }
      })
    },
    onSubmitEnd() {
      this.loading = false
      this.$router.push('/dashboard/contents/domaines-action')
      this.$message({
        message: "Le domaine d'action a été enregistré !",
        type: 'success',
      })
    },
  },
}
</script>
