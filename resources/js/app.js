require("./bootstrap");

window.Vue = require("vue");

import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";

Vue.component(
    "MediaUploader",
    require("./components/MediaUploader.vue").default
);

const app = new Vue({
    el: "#app"
});
