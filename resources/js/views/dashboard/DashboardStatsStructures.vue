<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord - Organisations
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <dashboard-menu index="structures" />
    </div>
    <div class="px-12">
      <card-structure-count
        label="Organisations"
        name="structures"
        link="/dashboard/structures"
      />
      <chart-models-created type="structures" class="max-w-4xl" />
    </div>
  </div>
</template>

<script>
import DashboardMenu from '@/components/DashboardMenu'
import CardStructureCount from '@/components/CardStructureCount'
import ChartModelsCreated from '@/components/charts/ChartModelsCreated'

export default {
  name: 'DashboardStatsStructures',
  components: {
    DashboardMenu,
    CardStructureCount,
    ChartModelsCreated,
  },
  data() {
    return {
      activeIndex: 'structures',
      loading: false,
    }
  },
  computed: {},
  methods: {},
}
</script>
