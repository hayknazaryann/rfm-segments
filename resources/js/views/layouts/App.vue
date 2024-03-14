<template>
    <div class="h-screen flex items-center justify-center bg-gray-100">
        <div class="container mx-auto sm:w-3/4 md:w-1/2 lg:w-2/3 xl:w-5/6">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    name: "App",
    data: () => ({

    }),
}
</script>

<style scoped>

</style>
