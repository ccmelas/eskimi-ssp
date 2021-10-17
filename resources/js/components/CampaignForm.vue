<template>
  <div class="campaign-form p-6">
    <div class="header flex justify-between items-center">
      <h4 class="text-gray-600 dark:text-gray-400">{{ form.id ? 'Update' : 'Create' }} Campaign</h4>
      <div class="error">
        <span v-if="errors && errors.general">{{ errors.general[0] }}</span>
      </div>
    </div>
    <form @submit.prevent="saveCampaign">
      <div class="form-control">
        <label>Campaign Name</label>
        <input type="text" required="required" placeholder="Enter campaign name" v-model="form.name">
        <span class="error" v-if="errors && errors.name">{{ errors.name[0] }}</span>
      </div>
      <div class="form-control">
        <label>Campaign Start (format: YYYY-MM-DD)</label>
        <input type="text" placeholder="Format: YYYY-MM-DD" required="required" v-model="form.from">
        <span class="error" v-if="errors && errors.from">{{ errors.from[0] }}</span>
      </div>
      <div class="form-control">
        <label>Campaign End (Format: YYYY-MM-DD)</label>
        <input type="text" placeholder="Format: YYYY-MM-DD" required="required" v-model="form.to">
        <span class="error" v-if="errors && errors.to">{{ errors.to[0] }}</span>
      </div>
      <div class="form-control">
        <label>Campaign Daily Budget</label>
        <input type="number" required="required" v-model="form.daily_budget">
        <span class="error" v-if="errors && errors.daily_budget">{{ errors.daily_budget[0] }}</span>
      </div>
      <div class="form-control">
        <label>Campaign Total Budget</label>
        <input type="text" readonly="readonly" required :value="totalBudget">
      </div>
      <div class="form-control">
        <label>Select Campaign Creatives</label>
        <input accept="image/png,image/jpg,image/jpeg" type="file" multiple :required="form.id === 0" @change="updateFiles">
        <span class="error" v-if="errors && errors.images">{{ errors.images[0] }}</span>
      </div>
      <!-- Removable images -->
      <creative-preview
        v-if="campaign"
        :images="campaign.images"
        edit-mode
        @image:delete="(id) => form.imagesToRemove.push(id)"
      />
      <div class="flex items-center justify-between mt-2">
        <button :disabled="saving" type="button" @click="$emit('cancel')">Cancel</button>
        <button :disabled="saving">Sav{{ saving ? 'ing': 'e' }}</button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import CreativePreview from "./CreativePreview";
export default {
  components: {CreativePreview},
  props: {
    campaign: null,
  },

  data() {
    return {
      saving: false,
      errors: {},
      form: {
        id: 0,
        name: 'Test Campaign',
        from: '2021-11-01',
        to: '2021-11-10',
        daily_budget: '100',
        imagesToUpload: [],
        imagesToRemove: [],
      },
    };
  },

  mounted() {
    this.loadCampaign();
  },

  methods: {
    loadCampaign() {
      if (this.campaign) {
        this.form.id = this.campaign.id;
        this.form.name = this.campaign.name;
        this.form.from = this.campaign.from;
        this.form.to = this.campaign.to;
        this.form.daily_budget = this.campaign.daily_budget;
        this.form.images = this.campaign.images;
      }
    },

    updateFiles(e) {
      this.form.imagesToUpload = Array.from(e.target.files);
    },

    async saveCampaign() {
      try {
        this.saving = true;
        this.errors = {};
        const formData = new FormData();
        formData.append('name', this.form.name);
        formData.append('from', this.form.from);
        formData.append('to', this.form.to);
        formData.append('daily_budget', this.form.daily_budget);
        this.form.imagesToUpload.forEach(file => formData.append('images[]', file));
        this.form.imagesToRemove.forEach(id => formData.append('imagesToRemove[]', id));
        const response = await axios.post(`/api/campaigns/${ this.form.id || ''}`, formData, {
          headers: { "Content-Type": "multipart/form-data" }
        });
        this.$emit('campaign:saved', response.data.data);
      } catch (e) {
        if (e.response && e.response.data) {
          this.errors = e.response.data.errors || {};
          this.errors.general = [e.response.data.message];
        } else {
          this.errors = { general: [e.message] };
        }
      } finally {
        this.saving = false;
      }
    },
  },

  computed: {
    totalBudget() {
      const from = moment(this.form.from, 'YYYY-MM-DD');
      const to = moment(this.form.to, 'YYYY-MM-DD');
      if (!from.isValid() || !to.isValid() || to.isBefore(from)) {
        return 'Invalid Date Range';
      }
      return (Number(this.form.daily_budget || 0) * to.diff(from, 'days'))
        .toFixed(2);
    }
  }
}
</script>
<style scoped>
.error {
  color: red;
}
.form-control {
  margin-bottom: 1.6rem;
}
.form-control label {
  display: block;
  margin-bottom: 0.2rem;
  color: white;
  font-size: .8rem;
}

.form-control input {
  width: 100%;
  padding: 0.8rem 1.6rem;
}
</style>
