<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord - Participations
        </div>
      </div>
    </div>
    <div class="px-12 mb-12">
      <dashboard-menu index="participations" />
    </div>
    <div class="px-12">
      <card-participation-count
        label="Participations"
        name="participations"
        link="/dashboard/participations"
      />
      <chart-models-created type="participations" class="max-w-4xl" />
    </div>
  </div>
</template>

<script>
import DashboardMenu from '@/components/DashboardMenu'
import CardParticipationCount from '@/components/CardParticipationCount'
import ChartModelsCreated from '@/components/charts/ChartModelsCreated'

export default {
  name: 'DashboardStatsParticipations',
  components: {
    DashboardMenu,
    CardParticipationCount,
    ChartModelsCreated,
  },
  data() {
    return {
      activeIndex: 'participations',
      loading: false,
    }
  },
  computed: {},
  methods: {},
}
</script>
