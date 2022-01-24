<template>
  <div class="tabs">
    <div class="-mx-2 mb-4 flex flex-wrap lg:flex-no-wrap items-center font-heading font-bold">
      <div class="px-2 text-tundora w-1/3 lg:w-auto flex-shrink">Showing:</div>
      <div
        class="px-2 hidden lg:block flex-auto tabs__navigation tabs__navigation--text tabs__navigation--text-endeavour"
      >
        <ul class="flex flex-wrap">
          <li
            v-for="tab in tabs"
            class="flex-auto"
            :class="{ active: isActive(tab.id) }"
            :key="'nav-' + tab.id"
            @click="setActive(tab.id)"
          >
            <div class="name">{{ tab.name }}</div>
          </li>
        </ul>
      </div>
      <div class="px-2 block lg:hidden w-2/3">
        <div class="stock-category-select">
          <label>
            <select class="w-full p-1 border border-gallery" v-model="activeTabId">
              <option v-for="tab in tabs" :value="tab.id" :key="tab.id">{{ tab.name }}</option>
            </select>
          </label>
        </div>
      </div>
    </div>

    <div class="tabs__content py-2 md:py-0">
      <div
        v-for="tab in tabs"
        :key="'content-' + tab.id"
        class="tab"
        :class="isActive(tab.id) ? 'flex flex-col' : 'hidden'"
      >
        <div class="w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-10">
          <StockItem
            v-for="stockItem in stockItemsForTab(tab.id)"
            :key="stockItem.stock_type + '-' + stockItem.id"
            :id="stockItem.id"
            :stock-type="stockItem.stock_type"
            :attention-grabber="stockItem.attention_grabber"
            :detail-page-url="stockItem.detail_page_url"
            :images="stockItem.images"
            :year="stockItem.year"
            :berths="stockItem.berths"
            :condition="stockItem.condition"
            :currency-symbol="stockItem.currency_symbol"
            :managers-special="stockItem.managers_special"
            :price="stockItem.price"
            :recommended-price="stockItem.recommended_price"
            :initial-delivery-date="stockItem.delivery_date"
            :special-offers="stockItem.special_offers"
            :show-add-to-comparison-button="shouldShowComparisonButton(stockItem.stock_type)"
            :mileage="stockItem.mileage"
            :transmission="stockItem.transmission"
            :manufacturer="stockItem.manufacturer"
            :model="stockItem.model"
            :unique-code="stockItem.unique_code"
          />
        </div>

        <div class="mt-4 w-full grid grid-cols-1 md:grid-cols-2 gap-10">
          <a
            v-for="link in linksForTab(tab.id)"
            :key="link.url"
            :href="link.url"
            class="p-2 bg-shiraz hover:bg-monarch text-xl text-white text-center font-heading font-semibold"
          >{{ link.text }}</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { stockSearch } from "../../utilities/api";
import StockItem from "../stock-search/stock-items/StockItem";
import { mapActions, mapState } from "vuex";

export default {
  components: {
    StockItem,
  },

  props: {
    caravanType: {
      type: String,
      default: "caravan",
    },
    motorhomeType: {
      type: String,
      default: "motorhome",
    },
    caravanApiSearchUrl: {
      type: String,
      required: true,
    },
    caravanSearchPageUrl: {
      type: String,
      default: "",
    },
    motorhomeApiSearchUrl: {
      type: String,
      required: true,
    },
    motorhomeSearchPageUrl: {
      type: String,
      default: "",
    },
    newArrivalsFilter: {
      required: true,
      type: String,
    },
    newStockFilter: {
      required: true,
      type: String,
    },
    usedStockFilter: {
      required: true,
      type: String,
    },
    type: {
      required: true,
      type: String,
    },
  },

  data: function () {
    return {
      activeTabId: null,
      newArrivalCaravanStockItems: [],
      newArrivalMotorhomeStockItems: [],
      newCaravanStockItems: [],
      usedCaravanStockItems: [],
      newMotorhomeStockItems: [],
      usedMotorhomeStockItems: [],
    };
  },

  created() {
    this.fetchNewArrivals().then(() => {
      this.fetchNewCaravans();
      this.fetchUsedCaravans();
      this.fetchNewMotorhomes();
      this.fetchUsedMotorhomes();
    });
    this.initialiseComparison();
  },

  computed: {
    ...mapState("comparison", [
      "motorhomeComparisonPageUrl",
      "caravanComparisonPageUrl",
    ]),
    tabs: function () {
      let tabs = [];
      if (this.newArrivalsStockItems.length > 0) {
        tabs.push({
          name: "New Arrivals",
          id: "new-arrivals",
        });
      }
      if (this.newMotorhomeStockItems.length > 0) {
        tabs.push({
          name: "New Motorhomes",
          id: "new-motorhomes",
        });
      }
      if (this.usedMotorhomeStockItems.length > 0) {
        tabs.push({
          name: "Used Motorhomes",
          id: "used-motorhomes",
        });
      }
      if (this.newCaravanStockItems.length > 0) {
        tabs.push({
          name: "New Caravans",
          id: "new-caravans",
        });
      }
      if (this.usedCaravanStockItems.length > 0) {
        tabs.push({
          name: "Used Caravans",
          id: "used-caravans",
        });
      }

      if (tabs.length > 0) {
        let activeTab = tabs[0];
        this.setActive(activeTab.id);
      }

      return tabs;
    },

    showCaravans: function () {
      if (this.type === this.motorhomeType) {
        return false;
      }
      return true;
    },

    showMotorhomes: function () {
      if (this.type === this.caravanType) {
        return false;
      }
      return true;
    },

    dualTypeStockItemPerPage: function () {
      const vehicleTypes = [this.caravanType, this.motorhomeType];
      if (vehicleTypes.includes(this.type)) {
        return 4;
      }
      return 2;
    },

    newArrivalsStockItems: function () {
      return this.newArrivalCaravanStockItems.concat(
        this.newArrivalMotorhomeStockItems
      );
    },

    newArrivalCaravanStockItemIds: function () {
      return this.newArrivalCaravanStockItems.map((item) => item.id);
    },

    newArrivalMotorhomeStockItemIds: function () {
      return this.newArrivalMotorhomeStockItems.map((item) => item.id);
    },
  },

  methods: {
    ...mapActions("comparison", {
      initialiseComparison: "initialise",
    }),
    shouldShowComparisonButton: function (stockType) {
      switch (stockType) {
        case "motorhome":
          return null !== this.motorhomeComparisonPageUrl;
        case "caravan":
          return null !== this.caravanComparisonPageUrl;
        default:
          return false;
      }
    },
    linksForTab: function (tabId) {
      let links = [];

      switch (tabId) {
        case "new-arrivals":
          if (this.showCaravans) {
            var link = this.caravanSearchPageLink(
              this.newArrivalsFilter,
              "View Caravan New Arrivals"
            );
            links.push(link);
          }
          if (this.showMotorhomes) {
            var link = this.motorhomeSearchPageLink(
              this.newArrivalsFilter,
              "View Motorhome New Arrivals"
            );
            links.push(link);
          }
          break;
        case "new-motorhomes":
          var link = this.motorhomeSearchPageLink(
            this.newStockFilter,
            "View all New Motorhomes"
          );
          links.push(link);
          break;
        case "used-motorhomes":
          var link = this.motorhomeSearchPageLink(
            this.usedStockFilter,
            "View all Used Motorhomes"
          );
          links.push(link);
          break;
        case "new-caravans":
          var link = this.caravanSearchPageLink(
            this.newStockFilter,
            "View all New Caravans"
          );
          links.push(link);
          break;
        case "used-caravans":
          var link = this.caravanSearchPageLink(
            this.usedStockFilter,
            "View all Used Caravans"
          );
          links.push(link);
          break;
        default:
          break;
      }

      return links;
    },

    caravanSearchPageLink: function (filter, linkText) {
      let url = this.generateUrl(this.caravanSearchPageUrl, filter);

      return {
        url: url,
        text: linkText,
      };
    },

    motorhomeSearchPageLink: function (filter, linkText) {
      let url = this.generateUrl(this.motorhomeSearchPageUrl, filter);

      return {
        url: url,
        text: linkText,
      };
    },

    generateUrl: function (url, filter) {
      let params = new URLSearchParams();
      params.append("status", filter);

      return url + "?" + params.toString();
    },

    stockItemsForTab: function (tabId) {
      switch (tabId) {
        case "new-arrivals":
          return this.newArrivalsStockItems;
        case "new-motorhomes":
          return this.newMotorhomeStockItems;
        case "used-motorhomes":
          return this.usedMotorhomeStockItems;
        case "new-caravans":
          return this.newCaravanStockItems;
        case "used-caravans":
          return this.usedCaravanStockItems;
        default:
          return [];
      }
    },

    setActive: function (tabId) {
      this.activeTabId = tabId;
    },

    isActive: function (tabId) {
      return this.activeTabId == tabId;
    },

    fetchNewArrivals: async function () {
      if (this.showCaravans) {
        await stockSearch(
          this.caravanApiSearchUrl,
          {
            status: [this.newArrivalsFilter],
          },
          {},
          {
            per_page: this.dualTypeStockItemPerPage,
          },
          {}
        )
          .then((response) => {
            this.newArrivalCaravanStockItems = response.data.data;
          })
          .catch((error) => {
            console.error(error);
          });
      }
      if (this.showMotorhomes) {
        await stockSearch(
          this.motorhomeApiSearchUrl,
          {
            status: [this.newArrivalsFilter],
          },
          {},
          {
            per_page: this.dualTypeStockItemPerPage,
          },
          {}
        )
          .then((response) => {
            this.newArrivalMotorhomeStockItems = response.data.data;
          })
          .catch((error) => {
            console.error(error);
          });
      }
    },

    fetchNewMotorhomes: function () {
      if (this.showMotorhomes === false) {
        return;
      }

      stockSearch(
        this.motorhomeApiSearchUrl,
        {
          status: [this.newStockFilter],
        },
        {},
        {
          per_page: 4,
        },
        {
          exclude_ids: this.newArrivalMotorhomeStockItemIds,
        }
      )
        .then((response) => {
          this.newMotorhomeStockItems = response.data.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },

    fetchUsedMotorhomes: function () {
      if (this.showMotorhomes === false) {
        return;
      }

      stockSearch(
        this.motorhomeApiSearchUrl,
        {
          status: [this.usedStockFilter],
        },
        {},
        {
          per_page: 4,
        },
        {
          exclude_ids: this.newArrivalMotorhomeStockItemIds,
        }
      )
        .then((response) => {
          this.usedMotorhomeStockItems = response.data.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },

    fetchNewCaravans: function () {
      if (this.showCaravans === false) {
        return;
      }

      stockSearch(
        this.caravanApiSearchUrl,
        {
          status: [this.newStockFilter],
        },
        {},
        {
          per_page: 4,
        },
        {
          exclude_ids: this.newArrivalCaravanStockItemIds,
        }
      )
        .then((response) => {
          this.newCaravanStockItems = response.data.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },

    fetchUsedCaravans: function () {
      if (this.showCaravans === false) {
        return;
      }

      stockSearch(
        this.caravanApiSearchUrl,
        {
          status: [this.usedStockFilter],
        },
        {},
        {
          per_page: 4,
        },
        {
          exclude_ids: this.newArrivalCaravanStockItemIds,
        }
      )
        .then((response) => {
          this.usedCaravanStockItems = response.data.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
