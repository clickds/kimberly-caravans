import { getCaravans, getMotorhomes } from "../../utilities/api";

export const store = {
  state: {
    loading: true,
    error: false,
    motorhomes: [],
    motorhomeComparisonPageUrl: null,
    caravans: [],
    caravanComparisonPageUrl: null,
  },
  mutations: {
    setCaravans(state, caravans) {
      state.caravans = caravans;
    },
    setCaravanComparisonPageUrl(state, caravanComparisonPageUrl) {
      state.caravanComparisonPageUrl = caravanComparisonPageUrl;
    },
    setMotorhomes(state, motorhomes) {
      state.motorhomes = motorhomes;
    },
    setMotorhomeComparisonPageUrl(state, motorhomeComparisonPageUrl) {
      state.motorhomeComparisonPageUrl = motorhomeComparisonPageUrl;
    },
    setLoading(state, loading) {
      state.loading = loading;
    },
    setError(state, error) {
      state.error = error;
    }
  },
  actions: {
    initialise({ dispatch, commit }) {
      // Get the list of IDs from local storage.
      let caravanIds = JSON.parse(fetchItemFromLocalStorage('caravanComparisonIds', "[]"));
      let motorhomeIds = JSON.parse(fetchItemFromLocalStorage('motorhomeComparisonIds', "[]"));

      // If both local storage items either don't exist, or they're empty, set loading to false.
      if (caravanIds.length === 0 && motorhomeIds.length === 0) {
        commit('setLoading', false);
      }

      // Fetch caravan data from the API if local storage contains IDs.
      if (caravanIds.length !== 0) {
        dispatch('retrieveCaravansForComparison', caravanIds);
      }

      // Fetch motorhome data from the API if local storage contains IDs.
      if (motorhomeIds.length !== 0) {
        dispatch('retrieveMotorhomesForComparison', motorhomeIds);
      }
    },
    initialiseComparisonUrls({ commit }, comparisonUrls) {
      commit('setMotorhomeComparisonPageUrl', comparisonUrls.motorhomeComparisonPageUrl);
      commit('setCaravanComparisonPageUrl', comparisonUrls.caravanComparisonPageUrl);
    },
    retrieveCaravansForComparison({ commit }, caravanIds) {
      commit('setLoading', true);

      if (caravanIds.length === 0) {
        commit('setLoading', false);
        return;
      }

      getCaravans(caravanIds)
        .then(response => {
          commit('setCaravans', response.data.data);
          commit('setLoading', false);
        })
        .catch(() => {
          commit('setLoading', false);
          commit('setError', true);
        });
    },
    addCaravan({ dispatch }, caravanId) {
      let caravanIds = JSON.parse(fetchItemFromLocalStorage('caravanComparisonIds', "[]"));

      caravanIds.push(caravanId);

      localStorage.setItem('caravanComparisonIds', JSON.stringify(caravanIds));

      dispatch('retrieveCaravansForComparison', caravanIds);
    },
    removeCaravan({ state, dispatch, commit }, caravanId) {
      let caravans = state.caravans;

      commit('setCaravans', caravans.filter(caravan => caravan.id !== caravanId));

      let caravanIds = JSON.parse(localStorage.getItem('caravanComparisonIds'));

      caravanIds = caravanIds.filter(existingId => existingId !== caravanId);

      localStorage.setItem('caravanComparisonIds', JSON.stringify(caravanIds));
    },
    removeAllCaravans({ commit }) {
      commit('setCaravans', []);

      localStorage.removeItem('caravanComparisonIds');
    },
    retrieveMotorhomesForComparison({ commit }, motorhomeIds) {
      commit('setLoading', true);

      if (motorhomeIds.length === 0) {
        commit('setLoading', false);
        return;
      }

      getMotorhomes(motorhomeIds)
        .then(response => {
          commit('setMotorhomes', response.data.data);
          commit('setLoading', false);
        })
        .catch(() => {
          commit('setLoading', false);
          commit('setError', true);
        });
    },
    addMotorhome({ dispatch }, motorhomeId) {
      let motorhomeIds = JSON.parse(fetchItemFromLocalStorage('motorhomeComparisonIds', "[]"));

      motorhomeIds.push(motorhomeId);

      localStorage.setItem('motorhomeComparisonIds', JSON.stringify(motorhomeIds));

      dispatch('retrieveMotorhomesForComparison', motorhomeIds);
    },
    removeMotorhome({ dispatch, state, commit }, motorhomeId) {
      let motorhomes = state.motorhomes;

      commit('setMotorhomes', motorhomes.filter(motorhome => motorhome.id !== motorhomeId));

      let motorhomeIds = JSON.parse(localStorage.getItem('motorhomeComparisonIds'));

      motorhomeIds = motorhomeIds.filter(existingId => existingId !== motorhomeId);

      localStorage.setItem('motorhomeComparisonIds', JSON.stringify(motorhomeIds));
    },
    removeAllMotorhomes({ commit }) {
      localStorage.removeItem('motorhomeComparisonIds');

      commit('setMotorhomes', []);
    },
  },
};

const fetchItemFromLocalStorage = (itemName, defaultValue) => {
  return localStorage.getItem(itemName) || defaultValue;
};