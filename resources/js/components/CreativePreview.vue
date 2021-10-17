<template>
  <div class="preview" :class="{ editMode }">
    <div class="preview__container" v-for="image in availableImages" :key="image.id">
      <img alt="Creative Image" :src="image.path"/>
      <button
        v-if="editMode"
        type="button"
        class="preview__container-delete"
        @click="() => removeImage(image.id)"
      >
        &times;
      </button>
    </div>
  </div>
</template>
<script>
export default {
  name: "CreativePreview",
  props: {
    images: {
      type: Array,
      default: () => [],
    },
    editMode: {
      type: Boolean,
      default: false,
    }
  },
  data() {
    return {
      removed: {},
    };
  },

  methods: {
    removeImage(id) {
      this.removed = { ...this.removed, [id]: true };
      this.$emit('image:delete', id);
    }
  },

  computed: {
    availableImages() {
      if (!this.editMode) {
        return this.images;
      };
      return this.images.filter(image => !this.removed[image.id]);
    },
  }
}
</script>
<style scoped>
.preview__container {
  position: relative;
}

.preview__container img {
  width: 100%;
}

.preview.editMode {
  display: grid;
  grid-gap: 24px;
  grid-template-columns: repeat(3, 1fr);
}

.preview.editMode .preview__container {
  height: 240px;
}

.preview.editMode .preview__container img {
  object-position: center;
  object-fit: cover;
  height: 100%;
}

.preview__container-delete {
  position: absolute;
  top: 0.4rem;
  right: 0.4rem;
  color: white;
  border: none;
  background-color: red;
}
</style>
