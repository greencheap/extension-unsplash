<template>
    <div><button @click.prevent="setImage('https://greencheap.net/storage/home-her1o.jpg')">Ekle</button></div>
</template>

<script>
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

    created() {},

    methods: {
        setImage(src) {
            this.source.src = src;
        },
    },
};
export default UnsplashFinder;

window.MultiFinder.components["unsplash"] = UnsplashFinder;
</script>
