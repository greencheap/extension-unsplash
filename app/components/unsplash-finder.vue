<template>
    <div>
        <div class="uk-margin-remove">
            <div class="uk-flex uk-flex-middle uk-flex-between">
                <div>
                    <ul class="uk-grid-small" uk-grid>
                        <li>
                            <div class="uk-inline">
                                <button @click.prevent="loadSearch" class="uk-form-icon" uk-icon="search"></button>
                                <input @keyup.enter="loadSearch" class="uk-input uk-form-blank" v-model="config.filter.search" type="search" :placeholder="'Search Photo' | trans" />
                            </div>
                        </li>
                        <li>
                            <button
                                class="uk-button un-button"
                                @click.prevent="
                                    config.filter.count = 10;
                                    loadSearch();
                                "
                                :class="{ 'un-button-active': config.filter.count == 10 }"
                            >
                                10
                            </button>
                        </li>
                        <li>
                            <button
                                class="uk-button un-button"
                                @click.prevent="
                                    config.filter.count = 20;
                                    loadSearch();
                                "
                                :class="{ 'un-button-active': config.filter.count == 20 }"
                            >
                                20
                            </button>
                        </li>
                        <li>
                            <button
                                class="uk-button un-button"
                                @click.prevent="
                                    config.filter.count = 40;
                                    loadSearch();
                                "
                                :class="{ 'un-button-active': config.filter.count == 40 }"
                            >
                                40
                            </button>
                        </li>
                        <li>
                            <button
                                class="uk-button un-button"
                                @click.prevent="
                                    config.filter.count = 80;
                                    loadSearch();
                                "
                                :class="{ 'un-button-active': config.filter.count == 80 }"
                            >
                                80
                            </button>
                        </li>
                        <li>
                            <button
                                class="uk-button un-button"
                                @click.prevent="
                                    config.filter.count = 160;
                                    loadSearch();
                                "
                                :class="{ 'un-button-active': config.filter.count == 160 }"
                            >
                                160
                            </button>
                        </li>
                        <li>
                            <button
                                class="uk-button un-button"
                                @click.prevent="
                                    config.filter.count = 320;
                                    loadSearch();
                                "
                                :class="{ 'un-button-active': config.filter.count == 320 }"
                            >
                                320
                            </button>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="uk-iconnav">
                        <li><a @click.prevent="setList(0)" uk-icon="box-1"></a></li>
                        <li><a @click.prevent="setList(1)" uk-icon="box-2"></a></li>
                        <li><a @click.prevent="setList(2)" uk-icon="box-3"></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div v-if="!isLoader" class="uk-margin uk-height-large uk-overflow-auto">
            <div :class="config.filter.list" uk-grid="masonry:true;parallax:250">
                <div v-for="(photo, id) in photos" :key="id">
                    <div class="uk-inline-clip uk-transition-toggle uk-width-expand">
                        <img :data-src="photo.urls.thumb" width="100%" uk-img />
                        <div class="uk-transition-fade uk-position-cover uk-position-small uk-flex uk-flex-center uk-flex-middle uk-text-center">
                            <ul class="uk-grid uk-grid-small uk-flex-center" uk-grid>
                                <li>
                                    <button @click.prevent="setImage(photo.urls.full)" :class="{ 'uk-button-primary': photo.urls.full == source.src }" class="uk-button uk-button-secondary uk-button-small">Full</button>
                                </li>
                                <li>
                                    <button @click.prevent="setImage(photo.urls.raw)" :class="{ 'uk-button-primary': photo.urls.raw == source.src }" class="uk-button uk-button-secondary uk-button-small">Raw</button>
                                </li>
                                <li>
                                    <button @click.prevent="setImage(photo.urls.regular)" :class="{ 'uk-button-primary': photo.urls.regular == source.src }" class="uk-button uk-button-secondary uk-button-small">Regular</button>
                                </li>
                                <li>
                                    <button @click.prevent="setImage(photo.urls.small)" :class="{ 'uk-button-primary': photo.urls.small == source.src }" class="uk-button uk-button-secondary uk-button-small">Small</button>
                                </li>
                                <li>
                                    <button @click.prevent="setImage(photo.urls.thumb)" :class="{ 'uk-button-primary': photo.urls.thumb == source.src }" class="uk-button uk-button-secondary uk-button-small">Thumb</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isLoader" class="uk-height-large uk-flex uk-flex-middle uk-flex-center">
            <span uk-spinner="ratio:3"></span>
        </div>
    </div>
</template>

<script>
import { createApi } from "unsplash-js";
const UnsplashFinder = {
    props: {
        source: Object,
        selected: Array,
        count: Number,
    },

    multiFinder: {
        label: "Unsplash",
        icon: "packages/greencheap/unsplash/icon.svg",
        priority: 5,
    },

    data() {
        return {
            unsplashLib: false,
            photos: [],
            isLoader: true,
            config: {
                filter: this.$session.get("unsplashv2.filter", { search: "", list: "uk-child-width-1-4@m uk-child-width-1-2 uk-grid-small", count: 20 }),
            },
            grids: ["uk-child-width-1-2 uk-grid-small", "uk-child-width-1-3@s uk-child-width-1-2 uk-grid-small", "uk-child-width-1-4@m uk-child-width-1-2 uk-grid-small"],
        };
    },

    watch: {
        "config.filter": {
            handler(filter) {
                this.$session.set("unsplashv2.filter", filter);
            },
            deep: true,
        },
    },

    created() {
        this.unsplashLib = createApi({
            accessKey: "9JkYiVP--j3_PN0O8FUxF3KPhc0c1l5Jfne7Tm4N-Zk",
        });

        this.loadSearch();
    },

    methods: {
        loadSearch() {
            this.isLoader = true;
            this.unsplashLib.photos.getRandom({ query: this.config.filter.search, count: this.config.filter.count }).then((res) => {
                this.photos = res.response;
                this.isLoader = false;
                this.$emit("recount", res.response.length);
            });
        },

        setImage(src) {
            this.source.src = src;
        },

        setList(id) {
            this.config.filter.list = this.grids[id];
            this.isLoader = true;
            setTimeout(() => (this.isLoader = false), 300);
        },
    },
};
export default UnsplashFinder;

window.MultiFinder.components["unsplash"] = UnsplashFinder;
</script>

<style scoped>
.un-button {
    background-color: #f9f9f9;
    background: transparent;
    border: 1px solid #e4e4e4;
    color: #333;
    border-radius: 0px;
    padding: 0 15px;
    height: 40px;
    line-height: 40px;
    font-size: 12px;
}

.un-button-active {
    border-color: #3bb873;
}

.tm-darkmode .un-button,
.tm-darkmode .un-button-active {
    color: #cecece !important;
    border: 1px solid #e4e4e461;
}
</style>
