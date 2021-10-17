<template>
    <div class="grid">
      <template v-if="campaigns.length">
        <campaign
          v-for="campaign in campaigns"
          :campaign="campaign"
          :key="campaign.id"
          @campaign:edit="(id) => $emit('campaign:edit', id)"
          @campaign:preview="(id) => $emit('campaign:preview', id)"
        />
      </template>
      <div class="p-6 flex items-center justify-center" v-else>
        <h4 class="text-gray-600 dark:text-gray-400">No campaigns created yet</h4>
      </div>
    </div>
</template>
<script>
import Campaign from "./Campaign";
export default {
  name: 'CampaignList',
  components: { Campaign },
  props: {
    campaigns: {
      type: Array,
      default: () => [],
    }
  }
}
</script>
