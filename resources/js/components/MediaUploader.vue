<template>
  <div>
    <div class="card-header d-flex align-items-baseline">
      <h3 class="card-title">Media</h3>
      <div style="margin-left: auto">
        <label @click="deleteMedia" class="btn btn-sm btn-primary">
          <i class="nav-icon fas fa-trash-alt"></i
        ></label>
        <label for="file" class="btn btn-sm btn-primary">
          <i class="nav-icon fas fa-upload"></i> upload
        </label>
        <input
          id="file"
          type="file"
          multiple
          @change="uploadMedia"
          style="display: none"
          accept=".png, .jpg, .jpeg"
        />
      </div>
    </div>
    <div class="card-body">
      <vue-select-image
        ref="picker"
        :data-images="this.userMedia"
        :is-multiple="true"
        :selected-images="selectedMedia"
        @onselectmultipleimage="selectedImages"
      >
      </vue-select-image>
    </div>
  </div>
</template>

<script>
import VueSelectImage from "vue-select-image";
require("vue-select-image/dist/vue-select-image.css");
import Bus from "./EventBus";
export default {
  components: {
    VueSelectImage,
  },
  props: ["userMedia", "alreadySelectedMedia"],
  data() {
    return {
      selectedMedia: [],
    };
  },
  methods: {
    selectedImages: function (v) {
      this.selectedMedia = v;
      Bus.$emit("media-selected", this.selectedMedia);
    },
    uploadMedia: function (e) {
      let data = new FormData();
      for (var i = 0; i < e.target.files.length; i++) {
        data.append("media[" + i + "]", e.target.files[i]);
      }
      axios
        .post("/media", data, {
          headers: {
            "content-type": "multipart/form-data",
          },
        })
        .then((res) => {
          this.userMedia = this.userMedia.concat(res.data.media);
        });
    },
    deleteMedia: function () {
      this.selectedMedia.forEach((media) => {
        axios.delete(`/media/delete/${media.id}`).then((res) => {
          if (res.data.success) {
            this.removeMedia(media.id);
          } else {
            $(document).Toasts("create", {
              class: "bg-danger",
              title: "alert",
              body: res.data.message,
            });
          }
        });
      });
    },
    removeMedia: function (id) {
      this.userMedia = this.userMedia.filter((obj) => {
        return obj.id != id;
      });
      this.$refs.picker.removeFromMultipleSelected(id);
      Bus.$emit("media-selected", this.selectedMedia);
    },
  },
  created() {
    let result = [];
    let alreadySelectedMedia = this.alreadySelectedMedia || [];
    alreadySelectedMedia.forEach((element) => {
      if (typeof element === "object") {
        result.push(element);
      } else {
        result.push({ id: parseInt(element) });
      }
    });
    this.selectedMedia = result;
    Bus.$emit("media-selected", this.selectedMedia);
  },
};
</script>
