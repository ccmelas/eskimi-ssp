<template>
  <div class="relative min-h-screen bg-gray-100 dark:bg-gray-900 py-4 sm:pt-0">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="header flex justify-between items-center pt-8 sm:pt-0">
        <h1 class="text-gray-600 dark:text-gray-400">Campaigns</h1>
        <button @click="createCampaign" v-if="actions.view">Create New</button>
      </div>

      <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
        <campaign-list
          v-if="action === actions.view"
          :campaigns="campaigns"
          @campaign:edit="editCampaign"
          @campaign:preview="previewCampaign"
        />
        <campaign-form
          v-else
          @cancel="cancelManage"
          :campaign="managedCampaign"
          @campaign:saved="updateList"
        />
      </div>
    </div>
    <modal v-if="previewing" @close="stopPreview">
      <div slot="header">
        Creative Preview
      </div>
      <creative-preview :images="managedCampaign.images" />
    </modal>
  </div>
</template>
<script>
import axios from "axios";
import CampaignList from "../components/CampaignList";
import CampaignForm from "../components/CampaignForm";
import Modal from "../components/Modal";
import CreativePreview from "../components/CreativePreview";

const CampaignActions = {
  manage: 'manage',
  view: 'view',
};

export default {
  name: 'Campaigns',
  components: {CreativePreview, Modal, CampaignForm, CampaignList },
  data() {
    return {
      campaigns: [],
      action: CampaignActions.view,
      actions: CampaignActions,
      campaignId: '',
      previewing: false,
    };
  },
  mounted() {
    this.fetchCampaigns();
  },

  computed: {
    managedCampaign() {
      return this.campaignId
        ? this.campaigns.find(c => c.id === this.campaignId)
        : null;
    },
  },

  methods: {
    async fetchCampaigns() {
      try {
        const response = await axios.get('/api/campaigns');
        this.campaigns = response.data.data;
      } catch(e) {}
    },

    createCampaign() {
      this.campaignId = 0;
      this.action = this.actions.manage;
    },

    editCampaign(id) {
      this.campaignId = id;
      this.action = this.actions.manage;
    },

    previewCampaign(id) {
      this.campaignId = id;
      this.previewing = true;
    },

    stopPreview(id) {
      this.previewing = false;
      this.campaignId = id;
    },

    updateList(data) {
      if (this.campaignId) {
        const index = this.campaigns.findIndex(c => c.id === this.campaignId);
        if (index !== -1) {
          this.campaigns.splice(index, 1, data);
        }
      } else {
        this.campaigns.unshift(data);
      }
      this.cancelManage();
    },

    cancelManage() {
      this.action = this.actions.view;
      this.campaignId = 0;
    },
  }
}
</script>
<style scoped>
.header {
    width: 100%;
}
</style>
