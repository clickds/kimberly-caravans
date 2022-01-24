import axios from "axios";

export function getReviews() {
    let url = "/api/admin/reviews";

    return axios.get(url);
}

export function getEvents() {
    let url = "/api/admin/events";

    return axios.get(url);
}

export function getBrochures() {
    let url = "/api/admin/brochures";

    return axios.get(url);
}

export function getSpecialOffers() {
    let url = "/api/admin/special-offers";

    return axios.get(url);
}

export function getForms() {
    let url = "/api/admin/forms";

    return axios.get(url);
}

export function getVideos() {
    let url = "/api/admin/videos";

    return axios.get(url);
}

export function getPages(params) {
    let url = "/api/admin/pages";

    return axios.get(url, {
        params: params
    });
}

export function getPage(pageId) {
    let url = "/api/admin/pages/" + pageId;

    return axios.get(url);
}

export function searchPages(params) {
    let url = "/api/admin/search-pages";

    return axios.get(url, {
        params: params
    });
}

export function searchFeedStockItems(url, params) {
    return axios.get(url, {
        params: params
    });
}
