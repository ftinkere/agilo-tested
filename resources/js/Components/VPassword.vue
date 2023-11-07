<script setup lang="ts">
    import { Password } from "@/types";

    defineProps<{
        password: Password
    }>()
</script>

<template>
<span>
  {{ password.project }}:
  login: <span class="px-2 inline-block bg-gray-300 dark:bg-gray-700 rounded">{{ password.login }}</span>
  password: <span class="px-2 group inline-block hover:text-black text-gray-300 bg-gray-300 hover:dark:text-white dark:text-gray-700 dark:bg-gray-700 rounded">{{ password.password }}</span>
</span>
</template>

<style scoped>

</style>