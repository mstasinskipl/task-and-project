<template>
  <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 pr-10 lg:px-8">
    <div class="align-middle inline-block min-w-full shadow overflow-hidden bg-white shadow-dashboard px-8 pt-3 rounded-bl-lg rounded-br-lg">
      <button @click="backToProjectList" class="px-5 py-2 border-blue-500 border text-blue-500 rounded transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">Back To Project List</button>
      <button v-if="isAdmin" @click="openModelCreateTask" class="px-5 py-2 border-green-500 border text-green-500 rounded transition duration-300 hover:bg-green-700 hover:text-white focus:outline-none">Add new task</button>
      <table class="min-w-full">
        <thead>
        <tr>
          <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Name</th>
          <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Description</th>
          <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Status</th>
          <th v-if="isAdmin" class="px-6 py-3 border-b-2 border-gray-300"></th>
        </tr>
        </thead>
        <tbody class="bg-white">
        <tr v-for="task in tasks" v-bind:key="task.id">
          <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
            <div class="flex items-center">
              <div>
                <div class="text-sm leading-5 text-gray-800">{{ task.title }}</div>
              </div>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
            <div class="flex items-center">
              <div>
                <div class="text-sm leading-5 text-gray-800">{{ task.description }}</div>
              </div>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
            <div class="flex items-center">
              <div>
                <div class="text-sm leading-5 text-gray-800" v-if="task.status === 1">To Do</div>
                <div class="text-sm leading-5 text-gray-800" v-else-if="task.status === 2">In Progress</div>
                <div class="text-sm leading-5 text-gray-800" v-else-if="task.status === 3">Done</div>
              </div>
            </div>
          </td>

          <td v-if="isAdmin" class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-500 text-sm leading-5">
            <button @click="openEditTask(task)" class="px-5 py-2 border-green-500 border text-green-500 rounded transition duration-300 hover:bg-green-700 hover:text-white focus:outline-none">Edit</button>
            <button @click="removeTask(task.id)" class="px-5 py-2 border-red-500 border text-red-500 rounded transition duration-300 hover:bg-red-700 hover:text-white focus:outline-none">Remove</button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <vue-tailwind-modal
        :showing="showModalCreate"
        @close="showModalCreate = false"
        :showClose="true"
        :backgroundClose="true"
    >
      Dodaj<br/>
      <span class="text-gray-700">Title</span>
      <input
          type="text"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          v-model="task.title"
          required
          autofocus
      /><br/>
      <span class="text-gray-700">Description</span>
      <input
          type="text"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          v-model="task.description"
          required
          autofocus
      /><br/>
      <button @click="createTask" class="px-5 py-2 border-green-500 border text-green-500 rounded transition duration-300 hover:bg-green-700 hover:text-white focus:outline-none">Zapisz zmiany</button>
    </vue-tailwind-modal>
    <vue-tailwind-modal
        :showing="showModalEdit"
        @close="showModalEdit = false"
        :showClose="true"
        :backgroundClose="true"
    >
      Edycja<br/>
      <span class="text-gray-700">Title</span>
      <input
          type="text"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          v-model="task.title"
          required
          autofocus
      /><br/>
      <span class="text-gray-700">Description</span>
      <input
          type="text"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          v-model="task.description"
          required
          autofocus
      /><br/>
      <span class="text-gray-700">Status</span>
      <select v-model="task.status" class="form-select mt-1 block w-full">
        <option value="1">To Do/option</option>
        <option value="2">In progress</option>
        <option value="3">Done</option>
      </select><br/>
      <button @click="saveEdit" class="px-5 py-2 border-green-500 border text-green-500 rounded transition duration-300 hover:bg-green-700 hover:text-white focus:outline-none">Zapisz zmiany</button>
    </vue-tailwind-modal>
  </div>
</template>

<script>
import * as API from "./../services/API.js";
import VueTailwindModal from 'vue-tailwind-modal'
export default {
  components: {
    VueTailwindModal
  },
  name: "TaskList",
  data: function () {
    return {
      isAdmin: null,
      user: null,
      tasks: null,
      showModalCreate: false,
      showModalEdit: false,
      task: {
        id: null,
        title: null,
        description: null,
        status: null,
      }
    }
  },
  created() {
    this.user = JSON.parse(window.localStorage.getItem('user'));
    this.isAdmin = this.user.type === 'admin';
    this.getTaskList();
  },
  methods: {
    getTaskList() {
      API.apiClient.get('api/tasks/' + this.$route.params.projectId).then(response => {
         this.setTasksList(response.data.tasks)
      });
    },
    setTasksList(tasksList) {
      this.tasks = tasksList;
    },
    removeTask(id) {
      API.apiClient.delete('/api/task/' + id).then(() => {
          this.getTaskList()
      })
    },
    openEditTask(task) {
      this.task.id = task.id
      this.task.title = task.title
      this.task.description = task.description
      this.task.status = task.status
      this.showModalEdit = true;
    },
    saveEdit() {
      this.showModalEdit = false;
      API.apiClient.patch('/api/task/' + this.task.id, {
        title: this.task.title,
        description: this.task.description,
        status: this.task.status
      }).then(() => {
        this.resetTask()
        this.getTaskList()
      })
    },
    openModelCreateTask() {
      this.showModalCreate = true;
      this.resetTask()
    },
    createTask() {
      this.showModalCreate = false;
      API.apiClient.post('/api/task', {
        title: this.task.title,
        description: this.task.description,
        status: this.task.status,
        project_id: this.$route.params.projectId
      }).then(() => {
        this.resetTask()
        this.getTaskList()
      })
    },
    resetTask() {
      this.task.id =  null
      this.task.title = null
      this.task.description = null
      this.task.status =  null
    },
    backToProjectList() {
      this.$router.push({
        name: 'dashboard'
      });
    }
  }
}
</script>
