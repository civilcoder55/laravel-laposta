require("./bootstrap");

import Vue from "vue";

Vue.component(
    "MediaUploader",
    require("./components/MediaUploader.vue").default
);

Vue.component("PostCreator", require("./components/PostCreator.vue").default);
Vue.component("PostEditor", require("./components/PostEditor.vue").default);
const app = new Vue({
    el: "#app"
});
