<template>
  <el-card class="mb-5 p-5" shadow="never">
    <template v-if="data">
      <div class="w-full">
        <el-table :data="data" style="width: 100%;" @row-click="onClickedRow">
          <el-table-column label="" width="70" align="center">
            <template slot-scope="scope">
              <div
                v-if="scope.row.image"
                class="bg-primary rounded-md p-2 inline-block"
                style="width: 40px; height: 40px;"
              >
                <img :src="scope.row.image" :alt="scope.row.name" />
              </div>
            </template>
          </el-table-column>
          <el-table-column prop="label" label="Nom">
            <template slot-scope="scope">
              <span class="text-gray-900">{{ scope.row.name }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="missions_count"
            label="Miss."
            width="90"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.missions_count | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="participations_count"
            label="Partic."
            width="100"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.participations_count | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="volontaires_count"
            label="Benev."
            width="100"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.volontaires_count | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="missions_available"
            label="Miss. dispos."
            width="140"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.missions_available | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="places"
            label="Places"
            width="100"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500">{{
                scope.row.places | formatNumber
              }}</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="taux_occupation"
            label="Occupation"
            width="130"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <span class="text-gray-500"
                >{{ scope.row.taux_occupation }}%</span
              >
            </template>
          </el-table-column>
          <el-table-column
            prop="places_available"
            label="Places dispos."
            width="150"
            align="center"
            sortable
          >
            <template slot-scope="scope">
              <el-tag :type="type(scope.row.places_available)">
                <template v-if="scope.row.places_available > 0">
                  {{ scope.row.places_available | formatNumber }}
                  {{
                    scope.row.places_available | pluralize(['place', 'places'])
                  }}
                </template>
                <template v-else>
                  Aucune place
                </template>
              </el-tag>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </template>
    <template v-else>
      <i class="el-icon-loading" />
    </template>
  </el-card>
</template>

<script>
import { statistics } from '../api/app'
export default {
  props: {
    label: {
      type: String,
      required: true,
    },
    name: {
      type: String,
      required: true,
    },
    link: {
      type: String,
      required: false,
      default: null,
    },
  },
  data() {
    return {
      data: null,
    }
  },
  created() {
    statistics(this.name).then((response) => {
      // console.log('domaines', response.data)
      this.data = response.data
    })
  },
  methods: {
    type(places) {
      if (places < 10) {
        return 'danger'
      } else if (places < 500) {
        return 'warning'
      } else {
        return 'info'
      }
    },
    onClickedRow(row) {
      this.$router.push(`/dashboard/missions?filter[domaine]=${row.key}`)
    },
  },
}
</script>
