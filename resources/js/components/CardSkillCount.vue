<template>
  <el-card shadow="never" class="mb-5 p-5 uppercase">
    <div>
      <div class="label mb-5 text-lg text-secondary">
        <span class="font-bold">TOP 10</span> - Compétences
      </div>
      <template v-if="data">
        <div v-for="skill in data" :key="skill.id" class="te text-sm mb-2">
          <v-clamp :max-lines="1" autoresize class="flex-1">
            {{ skill.profiles_count | formatNumber }} -
            {{ skill.name.fr }}
          </v-clamp>
        </div>
      </template>
      <template v-else>
        <i class="el-icon-loading" />
      </template>
    </div>
  </el-card>
</template>

<script>
import { statistics } from '../api/app'
export default {
  props: {
    name: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      data: null,
    }
  },
  created() {
    statistics(this.name).then((response) => {
      this.data = response.data
    })
  },
  methods: {},
}
</script>
