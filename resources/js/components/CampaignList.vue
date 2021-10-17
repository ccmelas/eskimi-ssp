<template>
    <div class="grid">
        <campaign
          v-for="campaign in campaigns"
          :campaign="campaign"
          :key="campaign.id"
          @campaign:edit="(id) => $emit('campaign:edit', id)"
          @campaign:preview="(id) => $emit('campaign:preview', id)"
        />
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
