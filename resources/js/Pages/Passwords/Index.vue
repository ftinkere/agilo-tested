<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Directory, Password } from '@/types';
import {computed, ref} from 'vue';
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import route from 'ziggy-js'
import InputError from "@/Components/InputError.vue";
import VDirectory from "@/Components/VDirectory.vue";
import VPassword from "@/Components/VPassword.vue";
import SimpleButton from "@/Components/SimpleButton.vue";
import PlusIcon from "@/Components/Icons/PlusIcon.vue";
import AddButton from "@/Components/AddButton.vue";
import DirectoryPlusIcon from "@/Components/Icons/DirectoryPlusIcon.vue";
import FilterIcon from "@/Components/Icons/FilterIcon.vue";
import GeneratePassword from "@/Components/GeneratePassword.vue";

const { passwords, directories } = defineProps<{
    passwords: Password[],
    directories: Directory[],
}>()

const showAddPasswordModal = ref(false);
const addPasswordForm = useForm({
    project: '',
    login: '',
    password: '',
    directory: -1,
})

function addPassword() {
    addPasswordForm.post(route('passwords'), {
        onSuccess: () => {
            showAddPasswordModal.value = false
            addPasswordForm.reset()
        }
    })
}

function openAddPasswordModal(directory: number) {
    addPasswordForm.directory = directory
    showAddPasswordModal.value = true
}


const showAddDirectoryModal = ref(false);
const addDirectoryForm = useForm({
    name: '',
    parent: -1,
})

function addDirectory() {
    addDirectoryForm.post(route('directories'), {
        onSuccess: () => {
            showAddDirectoryModal.value = false
            addDirectoryForm.reset()
        }
    })
}

function openAddDirectoryModal(directory: number) {
    addDirectoryForm.parent = directory
    showAddDirectoryModal.value = true
}


const showFilterModal = ref(false);

const projects = computed(() => {
    const res = new Set();

    const resolve = (passes: Password[]) => {
        passes.forEach(pass => {
            res.add(pass.project)
        })
    }
    resolve(passwords)

    const recur = (dir: Directory) => {
        resolve(dir.passwords)
        dir.contents.forEach(recur)
    }
    directories.forEach(recur)

    return res;
})

const filterProjects = ref([])
const filterDirectories = ref([])

const filteredPasswords = computed(() => {
    if (filterProjects.value.length === 0) {
        return passwords
    }
    return passwords.filter(pass => filterProjects.value.includes(pass.project))
})

const filteredDirectories = computed(() => {
    if (filterProjects.value.length === 0) {
        return directories
    }
    const filter = (dir: Directory) => {
        const fpasswords = dir.passwords.filter((pass: Password) => filterProjects.value.includes(pass.project))
        const fcontents = dir.contents.map(filter).filter((dir: Directory|null) => dir !== null)

        if (fpasswords.length === 0 && fcontents.length === 0) {
            return null
        }

        return {
            id: dir.id,
            name: dir.name,
            parent_id: dir.parent_id,
            passwords: fpasswords,
            contents: fcontents,
        }
    }
    return directories.map(filter).filter((dir: Directory|null) => dir !== null)
})
function test() {
    console.log(filteredDirectories.value)
}

</script>

<template>
  <Head title="Passwords" />

  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 dark:text-gray-100" @click="test">
            <h3 class="flex flex-row gap-2 text-lg font-semibold items-center">
              Passwords:
              <AddButton @click="openAddPasswordModal(-1)" />
              <SimpleButton
                class="aspect-square w-6 h-6 items-center"
                @click="openAddDirectoryModal(-1)"
              >
                <DirectoryPlusIcon class="mx-auto" />
              </SimpleButton>
              <SimpleButton
                class="aspect-square w-6 h-6 items-center"
                @click="showFilterModal = true"
              >
                <FilterIcon class="mx-auto" />
              </SimpleButton>
            </h3>

            <div>
              <div class="flex flex-col gap-1">
                <VPassword
                  v-for="password in filteredPasswords"
                  :key="password.id"
                  :password="password"
                />
              </div>

              <div class="mt-1 lex flex-col gap-1">
                <VDirectory
                  v-for="directory in filteredDirectories"
                  :key="directory.id"
                  :directory="directory"
                  @add-password="openAddPasswordModal($event)"
                  @add-directory="openAddDirectoryModal($event)"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Modal
      :show="showAddPasswordModal"
      @close="showAddPasswordModal = false"
    >
      <div class="p-4 flex flex-col gap-1 dark:text-white">
        <div class="form-control">
          <InputLabel required>Project</InputLabel>
          <TextInput
            v-model="addPasswordForm.project"
            class="w-full"
            required
          />
          <InputError :message="addPasswordForm.errors.project" />
        </div>
        <div class="form-control">
          <InputLabel required>Login</InputLabel>
          <TextInput
            v-model="addPasswordForm.login"
            class="w-full"
            required
          />
          <InputError :message="addPasswordForm.errors.login" />
        </div>
        <div class="form-control">
          <InputLabel required>Password</InputLabel>
          <TextInput
            v-model="addPasswordForm.password"
            class="w-full"
            required
          />
          <GeneratePassword @generate="addPasswordForm.password = $event" />
          <InputError :message="addPasswordForm.errors.password" />
        </div>

        <div class="mt-2 self-end">
          <PrimaryButton
            @click="addPassword"
          >
            Добавить
          </PrimaryButton>
        </div>
      </div>
    </Modal>

    <Modal
      :show="showAddDirectoryModal"
      @close="showAddDirectoryModal = false"
    >
      <div class="p-4 flex flex-col gap-1 dark:text-white">
        <div class="form-control">
          <InputLabel required>Name</InputLabel>
          <TextInput
            v-model="addDirectoryForm.name"
            class="w-full"
            required
          />
          <InputError :message="addDirectoryForm.errors.name" />
        </div>
        <InputError :message="addDirectoryForm.errors.parent" />

        <div class="mt-2 self-end">
          <PrimaryButton
            @click="addDirectory"
          >
            Добавить
          </PrimaryButton>
        </div>
      </div>
    </Modal>

    <Modal
      :show="showFilterModal"
      @close="showFilterModal = false"
    >
      <div class="p-4 flex flex-col gap-1 dark:text-white">
        <div class="flex flex-row flex-wrap gap-4">
          <div class="flex flex-col gap-1">
            Projects:
            <div
              v-for="project in projects"
              :key="project as string"
              class="ms-2"
            >
              <label class="flex flex-row gap-2 items-center w-fit">
                {{ project }}
                <input
                  v-model="filterProjects"
                  :value="project"
                  type="checkbox"
                  class="rounded border border-white"
                >
              </label>
            </div>
          </div>

          <div class="flex flex-col gap-1">
            Directories:

          </div>
        </div>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>
