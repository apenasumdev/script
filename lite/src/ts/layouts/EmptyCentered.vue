<template>
    <main class="empty-centered">
            <router-view />
            <x-element-snack-bar />
    </main>
</template>

<script>
    export default {
        name: "EmptyCentered"
    }
</script>