<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Collectivités</div>
      </div>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="search"
          placeholder="Rechercher par mots clés..."
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
          name="state"
          label="Statut"
          multiple
          :value="query['filter[state]']"
          :options="$store.getters.taxonomies.collectivities_states.terms"
          @changed="onFilterChange"
        />
        <query-filter
          name="published"
          label="Publiée"
          :value="query['filter[published]']"
          :options="[
            { label: 'Oui', value: true },
            { label: 'Non', value: false },
          ]"
          @changed="onFilterChange"
        />
        <query-filter
          v-if="$store.getters.contextRole === 'admin'"
          name="department"
          label="Département"
          multiple
          :value="query['filter[department]']"
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
      <el-table-column width="70" label="Id" align="center">
        <template slot-scope="scope">
          <div class="text-secondary text-sm">{{ scope.row.id }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Collectivité" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">{{ scope.row.name }}</div>
          <div class="font-light text-gray-600 text-xs flex items-center">
            <div
              :class="scope.row.published ? 'bg-green-500' : 'bg-red-500'"
              class="rounded-full h-2 w-2 mr-2"
            ></div>
            <router-link
              :to="{
                name: 'Collectivity',
                params: { slug: scope.row.slug },
              }"
              target="_blank"
              >/territoires/{{ scope.row.slug }}</router-link
            >
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="updated_at" label="Créée le" width="120">
        <template slot-scope="scope">
          <div class="text-sm text-gray-600">
            {{ scope.row.created_at | fromNow }}
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="state" label="Statut" width="250">
        <template slot-scope="scope">
          <collectivity-dropdown-state
            :form="scope.row"
            @updated="onUpdatedRow"
          ></collectivity-dropdown-state>
        </template>
      </el-table-column>
      <!-- <el-table-column
        v-if="!$store.getters['volet/active']"
        label="Actions"
        width="250"
      >
        <template slot-scope="scope">
          <el-dropdown size="small" split-button @command="handleCommand">
            Choisissez une action
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item
                :command="{
                  name: 'DashboardCollectivity',
                  params: { id: scope.row.id },
                }"
                >Visualiser la collectivité</el-dropdown-item
              >
              <template v-if="$store.getters.contextRole === 'admin'">
                <el-dropdown-item
                  :command="{
                    name: 'CollectivityFormEdit',
                    params: { id: scope.row.id },
                  }"
                  >Modifier la collectivité</el-dropdown-item
                >
                <el-dropdown-item
                  :command="{ action: 'delete', id: scope.row.id }"
                  >Supprimer la collectivité
                </el-dropdown-item>
              </template>
            </el-dropdown-menu>
          </el-dropdown>
        </template>
      </el-table-column> -->
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
    <portal to="volet">
      <collectivity-volet @updated="onUpdatedRow" @deleted="onDeletedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchCollectivities } from '@/api/app'
import TableWithVolet from '@/mixins/TableWithVolet'
import TableWithFilters from '@/mixins/TableWithFilters'
import QueryFilter from '@/components/QueryFilter.vue'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import CollectivityDropdownState from '@/components/CollectivityDropdownState'
import CollectivityVolet from '@/layouts/components/Volet/CollectivityVolet'

export default {
  name: 'DashboardCollectivities',
  components: {
    QueryFilter,
    QueryMainSearchFilter,
    CollectivityDropdownState,
    CollectivityVolet,
  },
  mixins: [TableWithFilters, TableWithVolet],
  data() {
    return {
      loading: true,
      tableData: [],
    }
  },
  methods: {
    fetchRows() {
      return fetchCollectivities(this.query)
    },
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleClickDelete(command.id)
      } else {
        this.$router.push(command)
      }
    },
  },
}
</script>
