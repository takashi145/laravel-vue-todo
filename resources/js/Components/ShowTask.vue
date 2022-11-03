<script setup>

defineProps({
  open: Boolean,
  task: Object
})

</script>

<template>
  <div v-if="open" class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full">
      <div class="fixed inset-0 bg-black opacity-50"></div>
      <div class="mx-auto mt-20 relative p-4 w-full md:max-w-3xl h-full md:h-auto">
          <div class="relative bg-gray-100 rounded shadow">
              <button type="button" @click="$emit('closeModal')" class="absolute top-3 right-2 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                  <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
              </button>
              <div class="py-6 px-6 lg:px-8">
                  <h3 class="mb-8 text-2xl font-medium text-gray-800">タスク詳細</h3>
                  <div class="space-y-8">
                      <div>
                          <label for="title" class="block mb-2 text-lg font-medium text-gray-900">Title <span class="text-red-500">*</span></label>
                          <div name="title" id="title" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3">{{ task.title }}</div>
                      </div>
                      <div>
                          <label for="description" class="block mb-2 text-lg font-medium text-gray-900">Description</label>
                          <div name="description" id="description" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 h-40">{{ task.description }}</div>
                      </div>
                      <div>
                          <label class="block mb-2 text-lg font-medium text-gray-900">Status</label>
                          <div v-if="task.completed" class="bg-green-400 border border-green-300 text-white text-sm font-bold rounded-lg p-3">
                            完了
                          </div>
                          <div v-else class="bg-red-400 border border-red-300 text-white text-sm font-bold rounded-lg p-3">
                            未完了
                          </div>
                      </div>
                      <div>
                        <label for="deadline" class="block mb-2 text-lg font-medium text-gray-900">Deadline<span class="text-red-500"> *</span></label>
                        <div name="deadline" id="deadline" class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3">{{ task.deadline }}</div>
                      </div>
                      <div class="text-center">
                        <button type="button" @click="$emit('closeModal')" class="w-1/2 mx-3 text-white bg-gray-500 hover:bg-gray-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3">Close</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>
