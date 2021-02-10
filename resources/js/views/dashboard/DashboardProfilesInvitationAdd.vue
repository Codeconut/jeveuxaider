<template>
  <div class="structure-members max-w-2xl">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Inviter une personne à rejoindre la plateforme
        </div>
      </div>
    </div>
    <div class="px-12">
      <el-form
        ref="invitationForm"
        :model="form"
        label-position="top"
        :rules="rules"
      >
        <el-form-item label="Email" prop="email">
          <el-input v-model.trim="form.email" placeholder="Email" />
        </el-form-item>

        <el-form-item label="Sélectionner un rôle" prop="role">
          <el-radio-group
            v-model="form.role"
            class="radio-group-options"
            @change="form.properties = {}"
          >
            <el-radio label="responsable_organisation"
              >Responsable d'une organisation</el-radio
            >
            <el-radio label="responsable_collectivity"
              >Responsable d'une collectivité</el-radio
            >
            <el-radio label="referent_departemental"
              >Référent départemental</el-radio
            >
            <el-radio label="referent_regional">Référent régional</el-radio>
            <el-radio label="superviseur">Superviseur réseau national</el-radio>
            <el-radio label="analyste">Datas analyste</el-radio>
          </el-radio-group>
        </el-form-item>

        <template v-if="form.role == 'responsable_organisation'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Responsable d'une organisation
          </div>
          <item-description container-class="mb-6">
            Renseignez le nom de l'organisation. Vous permettez à cet
            utilisateur de visualiser les missions et bénévoles rattachés à
            cette organisation.
          </item-description>
          @TODO
        </template>

        <template v-if="form.role == 'responsable_collectivity'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Responsable d'une collectivité
          </div>
          <item-description container-class="mb-6">
            Renseignez le nom de la collectivité. Vous permettez à cet
            utilisateur de visualiser les missions et bénévoles rattachés à
            cette collectivité.
          </item-description>
          @TODO
        </template>

        <template v-if="form.role == 'superviseur'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Superviseur réseau national
          </div>
          <item-description container-class="mb-6">
            Renseignez le nom du réseau national. Vous permettez à cet
            utilisateur de visualiser les missions et bénévoles rattachés aux
            organisations de ce réseau national.
          </item-description>
          <el-form-item label="Réseau national" prop="reseau_id" class="flex-1">
            <el-select
              v-model="form.properties.reseau_id"
              clearable
              placeholder="Sélectionner un réseau national"
            >
              <el-option
                v-for="item in $store.getters.reseaux"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
        </template>
        <template v-if="form.role == 'referent_regional'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Référent régional
          </div>
          <item-description container-class="mb-6">
            Renseignez le nom de la région. Vous permettez à cet utilisateur de
            visualiser les missions et bénévoles rattachés aux organisations de
            cette région.
          </item-description>
          <el-form-item label="Région" prop="referent_regional" class="flex-1">
            <el-select
              v-model="form.properties.referent_regional"
              filterable
              clearable
              placeholder="Région"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.regions.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </template>
        <template v-if="form.role == 'referent_departemental'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Référent départemental
          </div>
          <item-description container-class="mb-6">
            Renseignez le nom du département. Vous permettez à cet utilisateur
            de visualiser les missions et bénévoles rattachés aux organisations
            de ce département.
          </item-description>
          <el-form-item
            label="Département"
            prop="referent_departemental"
            class="flex-1"
          >
            <el-select
              v-model="form.properties.referent_departemental"
              filterable
              clearable
              placeholder="Département"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.departments.terms"
                :key="item.value"
                :label="`${item.value} - ${item.label}`"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
        </template>
        <template v-if="form.role == 'analyste'">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Datas analyste
          </div>
          <item-description container-class="mb-6">
            Cet utilisateur aura accès au tableau de bord et à tous ses
            indicateurs.
          </item-description>
        </template>

        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Envoyer l'invitation
          </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
import { addInvitation } from '@/api/user'
import ItemDescription from '@/components/forms/ItemDescription'

export default {
  name: 'DashboardProfilesInvitationAdd',
  components: { ItemDescription },
  data() {
    return {
      loading: false,
      form: {
        user_id: this.$store.getters.user.id,
        properties: {},
      },
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
        role: [
          {
            required: true,
            message: 'Veuillez sélectionner un rôle',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  created() {},
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['invitationForm'].validate((valid) => {
        if (valid) {
          addInvitation(this.form)
            .then(() => {
              this.loading = false
              this.$router.push(`/dashboard/profiles/invitations`)
              this.$message({
                message: `Une notification email a été envoyée à ${this.form.email}.`,
                type: 'success',
              })
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.loading = false
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped>
.radio-group-options
  @apply flex flex-col
  label
    @apply my-1
</style>
