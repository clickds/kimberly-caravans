import Vue from "vue";
import Vuex from "vuex";
import { stockSearch } from "../utilities/api";
import { store } from "./modules/comparison";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        comparison: {
            namespaced: true,
            ...store
        }
    },
    state: {
        stockType: "",
        filters: [],
        error: false,
        loading: true,
        order: "price_asc",
        orderOptions: [
            {
                value: "price_asc",
                label: "Price Low"
            },
            {
                value: "price_desc",
                label: "Price High"
            }
        ],
        perPageOptions: [12, 24, 48],
        pagination: {
            per_page: 12
        },
        stockItems: [],
        searchTerm: '',
        selectedOptions: {},
        selectedRanges: {},
        url: ""
    },
    mutations: {
        setFilters(state, filters) {
            state.filters = filters;
        },
        setStockType(state, stockType) {
            state.stockType = stockType;
        },
        setError(state, error) {
            state.error = error;
        },
        setLoading(state, loading) {
            state.loading = loading;
        },
        setStockItems(state, stockItems) {
            state.stockItems = stockItems;
        },
        setOrder(state, order) {
            state.order = order;
        },
        setPagination(state, pagination) {
            state.pagination = pagination;
        },
        setSelectedOptions(state, selectedOptions) {
            state.selectedOptions = {
                ...state.selectedOptions,
                ...selectedOptions
            };
        },
        setSelectedRanges(state, selectedRanges) {
            state.selectedRanges = {
                ...state.selectedRanges,
                ...selectedRanges
            };
        },
        setSearchTerm(state, searchTerm) {
            state.searchTerm = searchTerm;
        },
        setUrl(state, url) {
          state.url = url;
        }
    },
    actions: {
        initialise({ commit, state, getters, dispatch }, initialisationData) {
            commit("setStockType", initialisationData.stockType);

            commit("setFilters", initialisationData.filters);

            commit("setUrl", initialisationData.url);

            dispatch("createDefaultSelectedOptionsFromAvailableFilters");

            dispatch("createDefaultSelectedRangesFromAvailableFilters");

            dispatch("syncSelectedOptionsToUrlParams");

            dispatch("retrieveStockItems");
        },

        reinitialiseAfterHistoryChange({ dispatch }) {
            dispatch("syncSelectedOptionsToUrlParams");

            dispatch("retrieveStockItems");
        },

        createDefaultSelectedOptionsFromAvailableFilters({ commit, state }) {
            let selectedOptions = state.selectedOptions;

            state.filters
                .filter(filter => filter.type === "options")
                .forEach(filter => (selectedOptions[filter.name] = []));

            commit("setSelectedOptions", selectedOptions);
        },

        createDefaultSelectedRangesFromAvailableFilters({ commit, state }) {
            let selectedRanges = state.selectedRanges;

            state.filters
                .filter(filter => filter.type === "range")
                .forEach(filter => (selectedRanges[filter.name] = {}));

            commit("setSelectedRanges", selectedRanges);
        },

        // Used on initialisation to populate state from url params
        syncSelectedOptionsToUrlParams({ commit, state, getters }) {
            let url = new URL(window.location.href);
            let params = url.searchParams;

            let selectedOptions = state.selectedOptions;
            let selectedRanges = state.selectedRanges;

            let pagination = {
                per_page: state.pagination.per_page
            };

            let optionFilters = getters.getOptionFilters;
            let rangeFilters = getters.getRangeFilters;

            let pageParameter = params.get("page");
            if (null !== pageParameter) {
                pagination.current_page = pageParameter;
            }

            // Loop through the available filters, check if any url params match the name, set the state if so.
            optionFilters.forEach(optionFilter => {
                let matchingParameters = params.getAll(optionFilter.name);
                let filteredParameters = matchingParameters.filter(
                    parameterValue => {
                        return (
                            typeof optionFilter.options.find(
                                option => option.name === parameterValue
                            ) != "undefined"
                        );
                    }
                );

                selectedOptions[optionFilter.name] = [
                    ...new Set(filteredParameters)
                ];
            });

            rangeFilters.forEach(rangeFilter => {
                let matchingMinFilter = params.get(`${rangeFilter.name}[min]`);
                let matchingMaxFilter = params.get(`${rangeFilter.name}[max]`);
                let rangeFilterData = {};

                if (null !== matchingMaxFilter) {
                    let max = parseInt(matchingMaxFilter);

                    if (max > rangeFilter.max) {
                        console.log(
                            "Maximum parameter is above the maximum value for the filter, resetting"
                        );
                        max = rangeFilter.max;
                    }

                    rangeFilterData.max = max;
                }

                if (null !== matchingMinFilter) {
                    let min = parseInt(matchingMinFilter);

                    if (min < rangeFilter.min) {
                        console.log(
                            "Minimum parameter is below the minimum value for the filter, resetting"
                        );
                        min = rangeFilter.min;
                    }

                    rangeFilterData.min = min;
                }

                selectedRanges[rangeFilter.name] = rangeFilterData;
            });

            commit("setSelectedOptions", selectedOptions);

            commit("setPagination", pagination);

            let selectedOrder = params.get("order");
            if (null !== selectedOrder) {
                commit("setOrder", selectedOrder);
            }

            let urlSearchTerm = params.get("search-term");
            if (null !== urlSearchTerm) {
                commit("setSearchTerm", urlSearchTerm);
            }

            commit("setSelectedRanges", selectedRanges);
        },

        // Used when setting a filter property to update the url query params to allow bookmarking etc.
        syncUrlParamsToSelectedOptions({ state }) {
            window.history.pushState(
                null,
                null,
                window.location.pathname +
                    buildUrlParams(
                        state.selectedOptions,
                        state.selectedRanges,
                        state.pagination,
                        state.order,
                        state.searchTerm
                    )
            );
        },

        retrieveStockItems({ commit, state }) {
            commit("setLoading", true);

            stockSearch(
                state.url,
                state.selectedOptions,
                state.selectedRanges,
                {
                    page: state.pagination.current_page,
                    per_page: state.pagination.per_page
                },
                {
                    order: state.order,
                    search: state.searchTerm
                }
            )
                .then(response => {
                    commit("setStockItems", response.data.data);
                    commit("setPagination", response.data.meta);
                    commit("setLoading", false);
                })
                .catch(() => {
                    commit("setLoading", false);
                    commit("setError", true);
                });
        },

        applyOptionFilter({ commit, state, dispatch }, optionFilter) {
            commit("setLoading", true);

            let selectedOptions = state.selectedOptions;
            let selectedFilterOptions = [];

            if (selectedOptions.hasOwnProperty(optionFilter.name)) {
                selectedFilterOptions = selectedOptions[optionFilter.name];
            } else {
                selectedFilterOptions = [optionFilter.value];
            }

            selectedFilterOptions.push(optionFilter.value);

            selectedOptions[optionFilter.name] = [
                ...new Set(selectedFilterOptions)
            ];

            commit("setSelectedOptions", selectedOptions);

            commit("setPagination", {
                per_page: state.pagination.per_page
            });

            commit("setLoading", false);

            dispatch("syncUrlParamsToSelectedOptions");
        },

        removeOptionFilter({ commit, state, dispatch }, optionFilter) {
            let selectedOptions = state.selectedOptions;

            selectedOptions[optionFilter.name] = selectedOptions[
                optionFilter.name
            ].filter(selectedOption => selectedOption !== optionFilter.value);

            commit("setSelectedOptions", selectedOptions);

            commit("setPagination", {
                per_page: state.pagination.per_page
            });

            commit("setLoading", false);

            dispatch("syncUrlParamsToSelectedOptions");
        },

        clearOptionFilter({ commit, state, dispatch }, optionFilter) {
            let selectedOptions = state.selectedOptions;

            selectedOptions[optionFilter] = [];

            commit("setSelectedOptions", selectedOptions);

            commit("setPagination", {
                per_page: state.pagination.per_page
            });

            commit("setLoading", false);

            dispatch("syncUrlParamsToSelectedOptions");
        },

        applyRangeFilter({ commit, state, dispatch }, rangeFilter) {
            commit("setLoading", true);

            let selectedRanges = state.selectedRanges;
            let rangeFilterData = {};

            if (rangeFilter.hasOwnProperty("min")) {
                rangeFilterData.min = rangeFilter.min;
            }

            if (rangeFilter.hasOwnProperty("max")) {
                rangeFilterData.max = rangeFilter.max;
            }

            selectedRanges[rangeFilter.name] = rangeFilterData;

            commit("setSelectedRanges", selectedRanges);

            commit("setPagination", {
                per_page: state.pagination.per_page
            });

            commit("setLoading", false);

            dispatch("syncUrlParamsToSelectedOptions");
        },

        removeRangeFilter({ commit, state, dispatch }, rangeFilterName) {
            let selectedRanges = state.selectedRanges;

            selectedRanges[rangeFilterName] = {};

            commit("setSelectedRanges", selectedRanges);

            commit("setLoading", false);

            dispatch("syncUrlParamsToSelectedOptions");
        },

        resetAllFilters({ state, commit, dispatch }) {
            commit("setLoading", true);

            commit("setSearchTerm", "");

            commit("setPagination", {
                per_page: state.pagination.per_page
            });

            dispatch("createDefaultSelectedOptionsFromAvailableFilters");
            dispatch("createDefaultSelectedRangesFromAvailableFilters");
            dispatch("syncUrlParamsToSelectedOptions");
        },

        selectPage({ commit, state, dispatch }, page) {
            commit("setLoading", true);

            let pagination = state.pagination;

            pagination.current_page = page;

            commit("setPagination", pagination);

            dispatch("syncUrlParamsToSelectedOptions");

            dispatch("retrieveStockItems");
        },

        selectPerPage({ commit, state, dispatch }, perPage) {
            commit("setLoading", true);

            let pagination = state.pagination;

            pagination.per_page = perPage;

            commit("setPagination", pagination);

            dispatch("syncUrlParamsToSelectedOptions");

            dispatch("retrieveStockItems");
        },

        selectOrder({ commit, state, dispatch }, order) {
            commit("setLoading", true);
            commit("setOrder", order);

            dispatch("syncUrlParamsToSelectedOptions");
            dispatch("retrieveStockItems");
        },

        applySearchTerm({ commit, dispatch }, searchTerm) {
            commit("setLoading", true);
            commit("setSearchTerm", searchTerm);
            commit("setLoading", false);
            dispatch("syncUrlParamsToSelectedOptions");
        },
    },
    getters: {
        getOptionFilters: state => {
            return state.filters.filter(filter => filter.type === "options");
        },
        getRangeFilters: state => {
            return state.filters.filter(filter => filter.type === "range");
        },
        getSelectedOptionsForFilter: state => name => {
            return state.selectedOptions[name];
        },
        getSelectedRangeForFilter: state => name => {
            return state.selectedRanges[name];
        },
        getFilterByName: state => name => {
            return state.filters.find(filter => filter.name === name);
        },
        getSelectedFiltersCount: state => {
            let filterCount = 0;

            Object.keys(state.selectedOptions).map((selectedOptionKey) => {
                filterCount += state.selectedOptions[selectedOptionKey].length;
            });

            filterCount += Object.keys(state.selectedRanges)
              .filter(range => state.selectedRanges[range].hasOwnProperty('min') || state.selectedRanges[range].hasOwnProperty('max'))
              .length;

            filterCount += (state.searchTerm !== '') ? 1 : 0;

            return filterCount;
        },
    }
});

export const buildUrlParams = (
    selectedOptions,
    selectedRanges,
    pagination,
    order,
    searchTerm
) => {
    let params = new URLSearchParams();

    Object.keys(selectedOptions).forEach(key => {
        selectedOptions[key].forEach(selectedOption => {
            params.append(key, selectedOption);
        });
    });

    Object.keys(selectedRanges).forEach(key => {
        if (selectedRanges[key].hasOwnProperty("min")) {
            params.append(`${key}[min]`, selectedRanges[key].min);
        }
        if (selectedRanges[key].hasOwnProperty("max")) {
            params.append(`${key}[max]`, selectedRanges[key].max);
        }
    });

    if (pagination.hasOwnProperty("current_page")) {
        params.append("page", pagination.current_page);
    }
    if (pagination.hasOwnProperty("per_page")) {
        params.append("per_page", pagination.per_page);
    }

    params.append("order", order);

    params.append("search-term", searchTerm);

    return "?" + decodeURI(params.toString());
};
