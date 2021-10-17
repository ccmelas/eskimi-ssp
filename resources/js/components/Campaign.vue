<template>
  <div class="p-6 flex">
    <div class="campaign__info flex-1">
      <div class="flex items-center">
        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
        <div class="ml-4 text-lg leading-7 font-semibold text-gray-900 dark:text-white">
          {{ campaign.name }}
        </div>
      </div>

      <div class="ml-12">
        <div class="mt-2 text-gray-600 dark:text-gray-400 text-sm">
          Starts from <strong>{{ campaign.from }}</strong> and ends on <strong>{{ campaign.to }}</strong>
        </div>
        <div class="mt-2">
          <button @click="() => $emit('campaign:edit', campaign.id)">Edit</button>
          <button @click="() => $emit('campaign:preview', campaign.id)">Creative Preview</button>
        </div>
      </div>
    </div>
    <div class="campaign__budget text-center">
        <h1 class="ml-4 text-lg leading-7 font-semibold text-gray-900 dark:text-white">
            USD {{ campaign.daily_budget }} Daily
        </h1>
        <div class="ml-4 text-sm text-gray-900 dark:text-white">
            (Total Budget: USD {{ campaign.total_budget }})
        </div>
    </div>
  </div>
</template>
<script>
export default {
  name: 'Campaign',
  props: {
    campaign: {
      type: Object,
      required: true,
    }
  },
}
</script>
<style scoped>
.campaign__budget {
    width: 20%;
}
</style>
