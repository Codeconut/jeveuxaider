<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Contenus - Domaines d'action
        </div>
      </div>
      <div class>
        <new-content-dropdown></new-content-dropdown>
      </div>
    </div>
    <div class="px-12 mb-12">
      <contents-menu index="/dashboard/contents/domaines-action"></contents-menu>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="name"
          placeholder="Rechercher par mots clés..."
          :initial-value="query['filter[name]']"
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
    >
      <el-table-column label="#" min-width="70" align="center">
        <template slot-scope="scope">
          <div>{{ scope.row.id }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Domaine d'action" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">{{ scope.row.name }}</div>
          <div class="font-light text-gray-600 text-xs">
            <router-link
              :to="{
                name: 'Thematique',
                params: { slug: scope.row.slug },
              }"
              target="_blank"
              >/domaines-action/{{ scope.row.slug }}</router-link
            >
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Contexte" min-width="320">
        <template slot-scope="scope">
          <el-tag
            v-if="scope.row.published"
            type="success"
            class="m-1 ml-0"
            size="small"
            >En ligne</el-tag
          >
          <el-tag
            v-if="!scope.row.published"
            type="info"
            class="m-1 ml-0"
            size="small"
            >Non publié</el-tag
          >
        </template>
      </el-table-column>
      <el-table-column prop="updated_at" label="Modifiée le" min-width="120">
        <template slot-scope="scope">
          <div class="text-sm text-gray-600">
            {{ scope.row.updated_at | fromNow }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Actions" width="165">
        <template slot-scope="scope">
          <el-dropdown
            size="small"
            split-button
            trigger="click"
            @click="handleClickEdit(scope.row.id)"
            @command="handleCommand"
          >
            <i class="el-icon-edit mr-2"></i>Modifier
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item
                :command="{ action: 'delete', id: scope.row.id }"
                >Supprimer</el-dropdown-item
              >
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
      ></el-pagination>
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>
  </div>
</template>

<script>
import { fetchThematiques, deleteThematique } from '@/api/app'
import TableWithFilters from '@/mixins/TableWithFilters'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import ContentsMenu from '@/components/ContentsMenu'
import NewContentDropdown from '@/components/NewContentDropdown'

export default {
  name: 'DashboardThematiques',
  components: {
    QueryMainSearchFilter,
    ContentsMenu,
    NewContentDropdown,
  },
  mixins: [TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
    }
  },
  methods: {
    fetchRows() {
      return fetchThematiques(this.query)
    },
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleClickDelete(command.id)
      } else {
        this.$router.push(command)
      }
    },
    handleClickEdit(id) {
      this.$router.push({
        name: `DashboardThematiqueFormEdit`,
        params: { id: id },
      })
    },
    handleClickDelete(id) {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer ce domaine d'action ?`,
        'Supprimer ce domaine d\'action',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        deleteThematique(id).then(() => {
          this.$message({
            type: 'success',
            message: `Le domaine d'action a été supprimé.`,
          })
          this.fetchDatas()
        })
      })
    },
  },
}
</script>
