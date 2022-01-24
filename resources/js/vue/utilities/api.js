import axios from "axios";

export function getCaravans(ids) {
    let url = "/api/caravan-stock-items";
    let params = new URLSearchParams();

    ids.forEach(id => params.append("ids[]", id));

    return axios.get(url, { params: params });
}

export function getMotorhomes(ids) {
    let url = "/api/motorhome-stock-items";
    let params = new URLSearchParams();

    ids.forEach(id => params.append("ids[]", id));

    return axios.get(url, { params: params });
}

export function stockSearch(
    url,
    //stockType,
    optionFilters,
    rangeFilters,
    paginationOptions,
    otherParameters = {}
) {
    // let url = "/api/caravan-stock-items/search";

    // if (stockType === "motorhome") {
    //     url = "/api/motorhome-stock-items/search";
    // }

    let params = buildUrlParametersFromFilters(optionFilters, rangeFilters);

    for (let [name, value] of Object.entries(paginationOptions)) {
        appendItemToParams(params, name, value);
    }

    for (let [name, value] of Object.entries(otherParameters)) {
        appendItemToParams(params, name, value);
    }

    return axios.get(url, { params: params });
}

export const appendItemToParams = (params, name, value) => {
    if (Array.isArray(value)) {
        value.forEach(item => {
            params.append(`${name}[]`, item);
        });
    } else {
        params.append(name, value);
    }
};

export const buildUrlParametersFromFilters = (optionFilters, rangeFilters) => {
    let params = new URLSearchParams();

    if (optionFilters) {
        Object.keys(optionFilters).forEach(key => {
            if (0 === optionFilters[key].length) {
                return;
            }

            optionFilters[key].forEach(selectedOption =>
                params.append(`filter[${key}][in][]`, selectedOption)
            );
        });
    }

    if (rangeFilters) {
        Object.keys(rangeFilters).forEach(key => {
            if (rangeFilters[key].hasOwnProperty("min")) {
                params.append(`filter[${key}][gte]`, rangeFilters[key].min);
            }
            if (rangeFilters[key].hasOwnProperty("max")) {
                params.append(`filter[${key}][lte]`, rangeFilters[key].max);
            }
        });
    }

    return params;
};
