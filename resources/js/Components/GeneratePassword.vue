<script setup lang="ts">
    function generate() {
        return (window.crypto.getRandomValues(new BigUint64Array(4)).reduce(
            (prev: string|bigint, curr: bigint, index: number) => (
                !index ? prev : prev.toString(36)
            ) + (
                index % 2 ? curr.toString(36).toUpperCase() : curr.toString(36)
            ), '') as string)
            .split('').sort(() => 128 - window.crypto.getRandomValues(new Uint8Array(1))[0])
            .join('')
    }

    const emits = defineEmits(['generate'])
</script>

<template>
 <span
   class="underline text-blue-500 hover:text-black hover:dark:text-white cursor-pointer"
   @click="emits('generate', generate())"
 >
   Generate
 </span>
</template>

<style scoped>

</style>