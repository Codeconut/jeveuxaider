<template>
  <div class="profiles has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Utilisateurs - Référents départementaux
        </div>
      </div>
      <div v-if="$store.getters.contextRole === 'admin'" class="">
        <router-link to="/dashboard/profiles/invitations/add">
          <el-button type="primary"> Inviter un utilisateur </el-button>
        </router-link>
      </div>
    </div>
    <div class="px-12 mb-12">
      <profiles-menu
        index="/dashboard/profiles/referents-departements"
      ></profiles-menu>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="search"
          placeholder="Rechercher par nom, prénom, email..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
        <el-badge v-if="activeFilters" :value="activeFilters" type="primary">
          <el-button
            icon="el-icon-s-operation"
            class="ml-4"
            @click="showFilters = !showFilters"
          >
            Filtres avancés
          </el-button>
        </el-badge>
        <el-button
          v-else
          icon="el-icon-s-operation"
          class="ml-4"
          @click="showFilters = !showFilters"
        >
          Filtres avancés
        </el-button>
      </div>
      <div v-if="showFilters" class="flex flex-wrap">
        <query-filter
          v-if="$store.getters.contextRole === 'admin'"
          name="referent_department"
          label="Département"
          multiple
          :value="query['filter[referent_department]']"
          :options="
            $store.getters.taxonomies.departments.terms.map((term) => {
              return {
                label: `${term.value} - ${term.label}`,
                value: term.value,
              }
            })
          "
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
      @row-click="onClickedRow"
    >
      <el-table-column width="70" align="center">
        <template slot-scope="scope">
          <el-avatar
            :src="scope.row.image ? scope.row.image.thumb : null"
            class="bg-primary text-white"
          >
            {{ scope.row.short_name }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column label="Email" min-width="300">
        <template slot-scope="scope">
          <div class="text-gray-900 flex items-center">
            {{ scope.row.full_name }}
            <UserOnlineIndicator
              class="ml-3"
              :last-online-at="scope.row.last_online_at"
            />
          </div>
          <div class="font-light text-gray-600 text-xs">
            {{ scope.row.email }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Département" width="200">
        <template slot-scope="scope">
          <div class="text-gray-600">
            {{ scope.row.referent_department | fullDepartmentFromValue }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="# Structures" width="150" align="center">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.referent_waiting_actions.structures | formatNumber }}
          </div>
          <div class="font-light text-gray-600 text-xs">en attente</div>
        </template>
      </el-table-column>
      <el-table-column label="# Missions" width="150" align="center">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.referent_waiting_actions.missions | formatNumber }}
          </div>
          <div class="font-light text-gray-600 text-xs">en attente</div>
        </template>
      </el-table-column>
      <el-table-column label="# Actions" width="150" align="center">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.referent_waiting_actions.total | formatNumber }}
          </div>
          <div class="font-light text-gray-600 text-xs">en attente</div>
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="Crée le" min-width="150">
        <template slot-scope="scope">
          <div class="text-sm text-gray-600">
            {{ scope.row.created_at | fromNow }}
          </div>
        </template>
      </el-table-column>
      <el-table-column
        v-if="$store.getters.contextRole === 'admin'"
        label="Actions"
        width="250"
      >
        <template slot-scope="scope">
          <el-dropdown
            v-if="$store.getters.contextRole === 'admin' && scope.row.user_id"
            split-button
            size="small"
            @command="handleCommand"
          >
            Choisissez une action
            <el-dropdown-menu slot="dropdown">
              <router-link
                :to="{
                  name: 'DashboardProfile',
                  params: { id: scope.row.id },
                }"
                ><el-dropdown-item command=""
                  >Visualiser le profil</el-dropdown-item
                >
              </router-link>
              <router-link
                :to="{
                  name: 'DashboardProfileFormEdit',
                  params: { id: scope.row.id },
                }"
              >
                <el-dropdown-item command=""
                  >Modifier le profil</el-dropdown-item
                >
              </router-link>
              <el-dropdown-item
                :command="{ action: 'impersonate', id: scope.row.user_id }"
              >
                Prendre sa place
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
          <router-link
            v-else
            :to="{
              name: 'DashboardProfileFormEdit',
              params: { id: scope.row.id },
            }"
          >
            <el-button icon="el-icon-edit" size="mini" class="m-1">
              Modifier
            </el-button>
          </router-link>
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
      <div class="ml-auto">
        <el-button icon="el-icon-download" size="small" @click="onExport">
          Export
        </el-button>
      </div>
    </div>
    <portal to="volet">
      <profile-volet @updated="onUpdatedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchProfiles, exportProfilesReferentsDepartements } from '@/api/user'
import TableWithVolet from '@/mixins/TableWithVolet'
import TableWithFilters from '@/mixins/TableWithFilters'
import QueryFilter from '@/components/QueryFilter.vue'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import ProfileVolet from '@/layouts/components/Volet/ProfileVolet.vue'
import fileDownload from 'js-file-download'
import ProfilesMenu from '@/components/ProfilesMenu.vue'
import UserOnlineIndicator from '@/components/UserOnlineIndicator.vue'
import { Message } from 'element-ui'

export default {
  name: 'DashboardProfilesReferentsDepartements',
  components: {
    UserOnlineIndicator,
    ProfileVolet,
    QueryFilter,
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
    fetchRows() {
      return fetchProfiles(
        {
          ...this.query,
          'filter[role]': 'referent',
        },
        [
          'last_online_at',
          'roles',
          'has_user',
          'skills',
          'domaines',
          'referent_waiting_actions',
        ]
      )
    },
    handleCommand(command) {
      if (command.action == 'impersonate') {
        this.$store.dispatch('auth/impersonate', command.id)
      }
    },
    onExport() {
      this.loading = true
      exportProfilesReferentsDepartements({
        ...this.query,
        'filter[role]': 'referent',
      })
        .then((response) => {
          this.loading = false
          fileDownload(response.data, 'referents-departements.csv')
        })
        .catch((error) => {
          Message({
            message: error.response.data.message,
            type: 'error',
          })
        })
    },
  },
}
</script>
