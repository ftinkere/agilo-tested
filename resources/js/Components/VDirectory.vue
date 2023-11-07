<script setup lang="ts">
    import { Directory } from "@/types";
    import VPassword from "@/Components/VPassword.vue";
    import AddButton from "@/Components/AddButton.vue";
    import CaretRightIcon from "@/Components/Icons/CaretRightIcon.vue";
    import {computed, nextTick, onMounted, ref} from "vue";
    import SimpleButton from "@/Components/SimpleButton.vue";
    import DirectoryPlusIcon from "@/Components/Icons/DirectoryPlusIcon.vue";

    defineProps<{
        directory: Directory
    }>()

    const details = ref<HTMLDetailsElement|null>(null)

    const emits = defineEmits(['addPassword', 'addDirectory'])
</script>

<template>
  <details ref="details">
    <summary class="mb-1 flex flex-row gap-2 items-center cursor-pointer dark:fill-white">
      <CaretRightIcon />
      {{ directory.name }}
      <AddButton @click="emits('addPassword', directory.id)" />
      <SimpleButton
        class="aspect-square w-6 h-6 items-center"
        @click="emits('addDirectory', directory.id)"
      >
        <DirectoryPlusIcon class="mx-auto" />
      </SimpleButton>
    </summary>

    <div class="flex flex-col gap-1">
      <div class="flex flex-col gap-1">
        <VPassword
          v-for="password in directory.passwords"
          :key="password.id"
          :password="password"
          class="ms-4"
        />
      </div>

      <div class="flex flex-col">
        <VDirectory
          v-for="child in directory.contents"
          :key="child.id"
          :directory="child"
          class="ms-4"
          @add-password="emits('addPassword', $event)"
          @add-directory="emits('addDirectory', $event)"
        />
      </div>
    </div>
  </details>
</template>

<style scoped>
  details[open] > summary > svg {
      @apply rotate-90;
  }
</style>