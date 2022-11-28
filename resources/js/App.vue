<script setup>
import { ref } from 'vue';
import axios from 'axios';
import _ from 'lodash';

const loading = ref(false)
const noResults = ref(false)
const name = ref(null)
const price_from = ref(null)
const price_to = ref(null)
const bedrooms = ref(null)
const bathrooms = ref(null)
const stories = ref(null)
const garages = ref(null)
const errorAlert = ref(false)

const data = ref([])

const getDataOnLoad = async () => {
    errorAlert.value = false
    loading.value = true
    noResults.value = false
    await axios.get('/api/properties').then((res) => {
        if (res.status == 200 && res.statusText === 'OK') {
            data.value = res.data.data
            if (!data.value.length) {
                noResults.value = true
            }
        } else {
            console.log(res)
            errorAlert.value = true
        }
    }).catch((error) => {
        console.log(error)
        errorAlert.value = true
    }).finally(() => {
        loading.value = false
    })
}

getDataOnLoad()

const search = _.debounce(async () => {
    noResults.value = false
    loading.value = true
    errorAlert.value = false
    const requestObj = ref({})
    requestObj.value = {
        name: name.value,
        price_from: price_from.value,
        price_to: price_to.value,
        bedrooms: bedrooms.value,
        bathrooms: bathrooms.value,
        stories: stories.value,
        garages: garages.value,
    }

    await axios.post('/api/properties/search', requestObj.value).then((res) => {
        if (res.status == 200 && res.statusText === 'OK') {
            data.value = res.data.data
            if (!data.value.length) {
                noResults.value = true
            }
        } else {
            console.log(res)
            errorAlert.value = true
        }
    }).catch((error) => {
        console.log(error.response)
        errorAlert.value = true
    }).finally(() => {
        loading.value = false
    })
}, 500)
</script>
<template>
    <div>
        <div class="loading my-custom-loading" v-if="loading">Loading&#8230;</div>
        <div class="container mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-show="errorAlert">
                        An error occured while loading data! Please try again later.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Search Filters
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <label for="name">
                                                    <strong>Name</strong>
                                                </label>
                                                <input type="text" name="name" id="name" class="form-control" v-model="name" @input="search" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <label for="price-from">
                                                    <strong>Price From</strong>
                                                </label>
                                                <input type="text" name="price_from" id="price-from"
                                                    class="form-control" v-maska="'#*.##'" v-model="price_from"
                                                    @input="search" />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-12">
                                                <label for="price-to">
                                                    <strong>Price To</strong>
                                                </label>
                                                <input type="text" name="price_to" id="price-to" class="form-control" v-maska="'#*.##'" v-model="price_to" @input="search" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <label for="bedrooms">
                                                    <strong>Bedrooms</strong>
                                                </label>
                                                <input type="text" v-maska="'#'" name="bedrooms" id="bedrooms" class="form-control" v-model="bedrooms" @input="search" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <label for="bathrooms">
                                                    <strong>Bathrooms</strong>
                                                </label>
                                                <input type="text" name="bathrooms"
                                                    id="bathrooms" class="form-control" v-maska="'#'" v-model="bathrooms"
                                                    @input="search" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-4">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <label for="stories">
                                                    <strong>Stories</strong>
                                                </label>
                                                <input type="text" name="stories"
                                                    id="stories" class="form-control" v-model="stories" v-maska="'#'"
                                                    @input="search" />
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <label for="garages">
                                                    <strong>Garages</strong>
                                                </label>
                                                <input type="text" name="garages"
                                                    id="garages" class="form-control" v-model="garages" v-maska="'#'"
                                                    @input="search" />
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="d-grid gap-2 mt-4">
                                        <button class="btn btn-primary" type="button" @click="search">Search</button>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Bedrooms</th>
                                    <th scope="col">Bathrooms</th>
                                    <th scope="col">Stories</th>
                                    <th scope="col">Garages</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="noResults">
                                    <tr class="text-center">
                                        <th scope="col" colspan="7">No Results Found!</th>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr v-for="(item, index) in data" :key="index">
                                        <th scope="row">{{ 1 + index }}</th>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.price }}</td>
                                        <td>{{ item.bedrooms }}</td>
                                        <td>{{ item.bathrooms }}</td>
                                        <td>{{ item.stories }}</td>
                                        <td>{{ item.garages }}</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
/* Absolute Center Spinner */
.my-custom-loading {
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    overflow: show;
    margin: auto;
    top: 50%;
    left: 0;
    bottom: 0;
    right: 0;
}

/* Transparent Overlay */
.my-custom-loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));

    background: -webkit-radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));
}

/* :not(:required) hides these rules from IE9 and below */
.my-custom-loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
}

.my-custom-loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.5em;
    -webkit-animation: spinner 150ms infinite linear;
    -moz-animation: spinner 150ms infinite linear;
    -ms-animation: spinner 150ms infinite linear;
    -o-animation: spinner 150ms infinite linear;
    animation: spinner 150ms infinite linear;
    border-radius: 0.5em;
    -webkit-box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
    box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@-moz-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@-o-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
</style>