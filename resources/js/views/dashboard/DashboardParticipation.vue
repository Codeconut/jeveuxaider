<template>
  <div class="participation-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Participation</div>
        <div v-if="participation.profile" class="flex flex-wrap mb-8">
          <div class="font-bold text-2xl text-gray-800 mr-2">
            {{ participation.profile.full_name }}
          </div>
          <state-tag v-if="participation.state" :state="participation.state" />
        </div>
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/participation/${id}`">
        Informations
      </el-menu-item>
      <el-menu-item :index="`/dashboard/participation/${id}/history`">
        Historique
      </el-menu-item>
    </el-menu>

    <div v-if="!tab" class="px-12 grid grid-cols-1 gap-4 xl:grid-cols-2">
      <el-card shadow="never" class="p-4">
        <div class="flex justify-between">
          <div class="mb-6 text-xl">Informations</div>
        </div>
        <participation-infos :participation="participation" />
      </el-card>
      <el-card v-if="mission" shadow="never" class="p-4">
        <div class="flex justify-between">
          <div class="mb-6 text-xl">Mission</div>
        </div>
        <mission-infos :mission="mission" />
      </el-card>
    </div>
    <div v-else-if="tab == 'history'">
      <TableActivities :table-data="activities" />
    </div>
  </div>
</template>

<script>
import { getParticipation } from '@/api/participation'
import { getMission } from '@/api/mission'
import { fetchActivities } from '@/api/app'
import TableActivities from '@/components/TableActivities'
import StateTag from '@/components/StateTag'
import ParticipationInfos from '@/components/infos/ParticipationInfos'
import MissionInfos from '@/components/infos/MissionInfos'

export default {
  name: 'DashboardParticipation',
  components: {
    StateTag,
    TableActivities,
    ParticipationInfos,
    MissionInfos,
  },
  props: {
    id: {
      type: Number,
      default: null,
    },
    tab: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      participation: {},
      mission: null,
      activities: [],
    }
  },
  async created() {
    const responseParticipation = await getParticipation(this.id)
    this.participation = responseParticipation.data

    const responseMission = await getMission(this.participation.mission_id)
    this.mission = responseMission.data

    if (this.tab == 'history') {
      const { data } = await fetchActivities({
        'filter[subject_id]': this.id,
        'filter[subject_type]': 'Participation',
      })
      this.activities = data.data
    }
  },
}
</script>

<style scoped lang="sass">
.el-menu--horizontal
  @apply px-12
  > .el-menu-item
    @apply mr-8 p-0 font-medium
    border-bottom: solid 3px #070191
</style>
