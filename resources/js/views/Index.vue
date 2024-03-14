<script setup>
import { ref, onMounted } from 'vue'

let segments = ref([]),
    total = ref(0),
    loading = ref(true)
// mounted
onMounted(() => {
    axios
        .get('/api/rfm-segments')
        .then((res) => {
            segments.value = res.data.segments;
            total.value = res.data.total;
            loading.value = false;
        })
        .catch((error) => {
            loading.value = false;
        });
});

</script>

<template>
    <div v-if="loading" class="flex justify-center items-center space-x-1 text-sm text-gray-700">

        <svg fill='none' class="w-6 h-6 animate-spin" viewBox="0 0 32 32" xmlns='http://www.w3.org/2000/svg'>
            <path clip-rule='evenodd'
                  d='M15.165 8.53a.5.5 0 01-.404.58A7 7 0 1023 16a.5.5 0 011 0 8 8 0 11-9.416-7.874.5.5 0 01.58.404z'
                  fill='currentColor' fill-rule='evenodd' />
        </svg>


        <div>Loading ...</div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-md" v-for="segment in segments">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-medium">
                    {{segment.title}}
                </h2>
                <div>
                    <span class="bg-gray-100 text-sm font-medium me-2 px-2.5 py-1 rounded-full dark:bg-gray-200">
                        Всего: {{segment.total}}
                    </span>
                </div>
            </div>
            <div class="flex flex-col gap-2 justify-between flex-grow">
                <div>
                    <span class="bg-gray-100 text-sm font-medium me-2 px-2.5 py-1 rounded-full dark:bg-gray-200">
                        Кол-во клиентов: {{segment.count_clients}}
                    </span>

                </div>
                <div>
                    <span class="bg-gray-100 text-sm font-medium me-2 px-2.5 py-1 rounded-full dark:bg-gray-200">
                        Сумма заказов: {{segment.sum_orders}}
                    </span>

                </div>
                <div>
                    <span class="bg-gray-100 text-sm font-medium me-2 px-2.5 py-1 rounded-full dark:bg-gray-200">
                        Средний чек: {{segment.avg_bill}}
                    </span>

                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
