import Vue from "vue";
import VueCookies from 'vue-cookies';
import store from "./store";
import * as VueGoogleMaps from 'vue2-google-maps';

Vue.use(VueCookies);

Vue.use(VueGoogleMaps, {
  load: {
    key: process.env.MIX_GOOGLE_MAPS_API_KEY,
    libraries: "places",
    installComponents: true
  }
});

Vue.component(
    "stock-search-app",
    require("./components/stock-search/App.vue").default
);
Vue.component(
    "stock-item-category-tabs-panel",
    require("./components/page-panels/StockItemCategoryTabs.vue").default
);
Vue.component(
    "stock-items-comparison-bar",
    require("./components/comparison-bar/App.vue").default
);
Vue.component(
    "motorhome-comparison-app",
    require("./components/comparison/MotorhomeComparisonApp.vue").default
);
Vue.component(
    "caravan-comparison-app",
    require("./components/comparison/CaravanComparisonApp.vue").default
);
Vue.component(
    "article-filters",
    require("./components/listing-filters/ArticleFilters.vue").default
);
Vue.component(
    "review-filters",
    require("./components/listing-filters/ReviewFilters.vue").default
);
Vue.component(
    "video-filters",
    require("./components/listing-filters/VideoFilters.vue").default
);
Vue.component("google-map", require("./components/maps/Map").default);
Vue.component(
    "finance-calculator",
    require("./components/FinanceCalculator.vue").default
);
Vue.component("tabs", require("./components/Tabs.vue").default);
Vue.component(
    "compare-button",
    require("./components/stock-search/CompareButton.vue").default
);
Vue.component(
    "employment-history",
    require("./components/vacancy-applications/EmploymentHistory").default
);
Vue.component(
    "stock-form-modal-launcher",
    require("./components/StockFormModalLauncher.vue").default
);
Vue.component(
    "stock-form-modal",
    require("./components/StockFormModal.vue").default
);
Vue.component(
    "similar-stock-items-slider",
    require("./components/SimilarStockItemsSlider").default
);
Vue.component(
    "managers-specials",
    require("./components/ManagersSpecials").default
);
Vue.component(
    "special-offer-stock-items",
    require("./components/special-offer/StockItems").default
);
Vue.component(
    "pop-up",
    require("./components/PopUp.vue").default
);
Vue.component(
    "locations-map",
    require("./components/LocationsMap.vue").default
);
Vue.component(
    "range-models-tab",
    require("./components/vehicle-range-page-tabs/Models.vue").default
);
Vue.component(
    "range-buy-tab",
    require("./components/vehicle-range-page-tabs/Buy.vue").default
);
Vue.component(
    "range-technical-tab",
    require("./components/vehicle-range-page-tabs/Technical.vue").default
);
Vue.component(
    "range-videos-and-reviews-tab",
    require("./components/vehicle-range-page-tabs/VideosAndReviews.vue").default
);
Vue.component(
    "search-filters",
    require("./components/listing-filters/SearchFilters.vue").default
);
Vue.component(
    "used-range-stock-items",
    require("./components/vehicle-range-page-tabs/offers/UsedRangeStockItems.vue").default
);

document.addEventListener("DOMContentLoaded", function() {
    const app = new Vue({
        el: "#app",
        store
    });
});
