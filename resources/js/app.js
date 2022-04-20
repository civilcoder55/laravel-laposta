require("./bootstrap");

import Vue from "vue";
import VueObserveVisibility from "vue-observe-visibility";
import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";

Vue.component(
    "MediaUploader",
    require("./components/MediaUploader.vue").default
);

Vue.component("PostCreator", require("./components/PostCreator.vue").default);
Vue.component("PostEditor", require("./components/PostEditor.vue").default);
Vue.component(
    "Notifications",
    require("./components/Notifications.vue").default
);

Vue.use(VueObserveVisibility);
Vue.use(VueToast);
const app = new Vue({
    el: "#app"
});
