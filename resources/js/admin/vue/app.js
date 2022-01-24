import Vue from "vue";
import { Datetime } from "vue-datetime";
import vSelect from "vue-select";

Vue.component("v-select", vSelect);
Vue.component("datetime", Datetime);
Vue.component("tabs", require("./components/Tabs.vue").default);
Vue.component(
    "feed-stock-item-selection",
    require("./components/FeedStockItemSelection.vue").default
);
Vue.component(
    "single-page-field",
    require("./components/SinglePageField.vue").default
);
Vue.component(
    "multiple-page-field",
    require("./components/MultiplePageField.vue").default
);
Vue.component(
    "site-page-fields",
    require("./components/SitePageFields.vue").default
);
Vue.component(
    "fieldset-selection",
    require("./components/FieldsetSelection.vue").default
);
Vue.component("form-fields", require("./components/FormFields.vue").default);
Vue.component("page-panel", require("./components/PagePanel.vue").default);
Vue.component("pop-up", require("./components/PopUp.vue").default);
Vue.component(
    "uppy-uploader",
    require("./components/UppyUploader.vue").default
);
Vue.component(
    "wysiwyg-field",
    require("./components/WysiwygField.vue").default
);
Vue.component(
    "navigation-items-tree",
    require("./components/NavigationItemsTree.vue").default
);
Vue.component(
    "admin-table",
    require("./components/AdminTable.vue").default
);

window.onload = function() {
    const app = new Vue({
        el: "#app"
    });
};
