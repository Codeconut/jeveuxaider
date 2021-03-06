<template>
  <div class="profiles has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Invitations en attente
        </div>
      </div>
      <div>
        <router-link to="/dashboard/profiles/invitations/add">
          <el-button type="primary"> Inviter un utilisateur </el-button>
        </router-link>
      </div>
    </div>
    <div class="px-12 mb-12">
      <profiles-menu index="/dashboard/profiles/invitations"></profiles-menu>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="search"
          placeholder="Rechercher par nom, prénom, email..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
    >
      <el-table-column width="70" align="center">
        <el-avatar
          class="bg-primary text-white flex items-center justify-center"
          icon="el-icon-s-promotion"
        >
        </el-avatar>
      </el-table-column>
      <el-table-column label="Email" min-width="300">
        <template slot-scope="scope">
          <div class="text-gray-900 flex items-center">
            {{ scope.row.email }}
          </div>
          <div class="font-light text-gray-600 text-xs">
            {{ scope.row.role | labelFromValue('roles') }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Invitation" min-width="300">
        <template slot-scope="scope">
          <div
            v-if="scope.row.invitable"
            class="text-gray-900 flex items-center"
          >
            {{ scope.row.invitable.name }}
          </div>
          <template v-else>
            <template v-if="scope.row.properties">
              <div v-if="scope.row.properties.referent_departemental">
                {{
                  scope.row.properties.referent_departemental
                    | labelFromValue('departments')
                }}
              </div>
              <div v-else-if="scope.row.properties.referent_regional">
                {{ scope.row.properties.referent_regional }}
              </div>
            </template>
            <div v-else>N/A</div>
          </template>
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="Crée le" min-width="150">
        <template slot-scope="scope">
          <div class="text-sm text-gray-600">
            {{ scope.row.created_at | fromNow }}
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="last_sent_at" label="Envoyé le" min-width="150">
        <template slot-scope="scope">
          <div class="text-sm text-gray-600">
            {{ scope.row.last_sent_at | fromNow }}
          </div>
        </template>
      </el-table-column>
      <el-table-column
        v-if="$store.getters.contextRole === 'admin'"
        label="Actions"
        width="250"
      >
        <template slot-scope="scope">
          <el-dropdown split-button size="small" @command="handleCommand">
            Choisissez une action
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item
                :command="{ action: 'copy', invitation: scope.row }"
              >
                Copier le lien d'invitation
              </el-dropdown-item>
              <el-dropdown-item
                :command="{ action: 'resend', invitation: scope.row }"
              >
                Renvoyer le mail
              </el-dropdown-item>
              <el-dropdown-item
                :command="{ action: 'delete', invitation: scope.row }"
              >
                Supprimer l'invitation
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </template>
      </el-table-column>
    </el-table>
    <div class="m-3 flex items-center">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="totalRows"
        :page-size="15"
        :current-page="Number(query.page)"
        @current-change="onPageChange"
      />
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
      <div class="ml-auto"></div>
    </div>
  </div>
</template>

<script>
import {
  fetchInvitations,
  resendInvitation,
  deleteInvitation,
} from '@/api/user'
import TableWithVolet from '@/mixins/TableWithVolet'
import TableWithFilters from '@/mixins/TableWithFilters'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import ProfilesMenu from '@/components/ProfilesMenu.vue'

export default {
  name: 'DashboardProfilesReferentsDepartements',
  components: {
    QueryMainSearchFilter,
    ProfilesMenu,
  },
  mixins: [TableWithVolet, TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
      totalRows: 0,
    }
  },
  computed: {},
  created() {},
  methods: {
    handleCommand(command) {
      if (command.action == 'copy') {
        console.log(command)
        this.onCopyInvitationLink(command.invitation)
      }
      if (command.action == 'resend') {
        this.onResendInvitationLink(command.invitation)
      }
      if (command.action == 'delete') {
        this.onDeleteInvitation(command.invitation)
      }
    },
    onCopyInvitationLink(invitation) {
      this.$copyText(
        process.env.MIX_API_BASE_URL + '/invitation/' + invitation.token
      ).then(() => {
        this.$message({
          message:
            "Le lien d'invitation a été copié dans votre presse papier (CTRL+V)",
          type: 'success',
        })
      })
    },
    onResendInvitationLink(invitation) {
      resendInvitation(invitation.token).then(() => {
        this.fetchDatas()
        this.$message({
          message: `Un email a été renvoyé à ${invitation.email}`,
          type: 'success',
        })
      })
    },
    onDeleteInvitation(invitation) {
      this.$confirm(
        `L'invitation pour ${invitation.email} sera supprimée de la plateforme. Voulez-vous continuer ?`,
        "Supprimer l'invitation",
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        deleteInvitation(invitation.token).then(() => {
          this.fetchDatas()
          this.$message({
            type: 'success',
            message: `L'invitation pour ${invitation.email} a été supprimée.`,
          })
        })
      })
    },
    fetchRows() {
      return fetchInvitations({
        ...this.query,
      })
    },
  },
}
</script>
