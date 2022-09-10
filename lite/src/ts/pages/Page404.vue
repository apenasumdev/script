<template>
    <div class="page-404">
        <h1>
            <span>4</span>
            <img src="/assets/images/404/404_emoji.png" alt="404">
            <span>4</span>
        </h1>
        <p class="desc">
            Couldn't find this page
        </p>
        <p class="footer">
            <router-link :to="{name: 'home'}">
                Take me back home
            </router-link>
        </p>
    </div>
</template>

<script>
    export default {
        name: "Page404"
    }
</script>