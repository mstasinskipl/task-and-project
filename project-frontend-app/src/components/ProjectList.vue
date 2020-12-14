<template>
  <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 pr-10 lg:px-8">
    <div class="align-middle inline-block min-w-full shadow overflow-hidden bg-white shadow-dashboard px-8 pt-3 rounded-bl-lg rounded-br-lg">
      <button v-if="isAdmin" @click="openModelCreateProject" class="px-5 py-2 border-green-500 border text-green-500 rounded transition duration-300 hover:bg-green-700 hover:text-white focus:outline-none">Add new project</button>
      <table class="min-w-full">
        <thead>
        <tr>
          <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Id</th>
          <th class="px-6 py-3 border-b-2 border-gray-300 text-left leading-4 text-blue-500 tracking-wider">Name</th>
          <th v-if="isAdmin" class="px-6 py-3 border-b-2 border-gray-300"></th>
        </tr>
        </thead>
        <tbody class="bg-white">
        <tr v-for="project in projects" v-bind:key="project.id">
          <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
            <div class="flex items-center">
              <div>
                <div class="text-sm leading-5 text-gray-800">{{ project.id }}</div>
              </div>
            </div>
          </td>
          <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
            <div class="flex items-center">
              <div>
                <div class="text-sm leading-5 text-gray-800">{{ project.name }}</div>
              </div>
            </div>
          </td>
          <td v-if="isAdmin" class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-500 text-sm leading-5">
            <button @click="viewTasks(project.id)" class="px-5 py-2 border-blue-500 border text-blue-500 rounded transition duration-300 hover:bg-blue-700 hover:text-white focus:outline-none">View Tasks</button>
            <button @click="openEditProject(project.name, project.id)" class="px-5 py-2 border-green-500 border text-green-500 rounded transition duration-300 hover:bg-green-700 hover:text-white focus:outline-none">Edit</button>
            <button @click="removeProject(project.id)" class="px-5 py-2 border-red-500 border text-red-500 rounded transition duration-300 hover:bg-red-700 hover:text-white focus:outline-none">Remove</button>
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
      <input
          type="text"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          v-model="project.name"
          required
          autofocus
      /><br/>
      <button @click="createProject" class="px-5 py-2 border-green-500 border text-green-500 rounded transition duration-300 hover:bg-green-700 hover:text-white focus:outline-none">Zapisz zmiany</button>
    </vue-tailwind-modal>
    <vue-tailwind-modal
        :showing="showModalEdit"
        @close="showModalEdit = false"
        :showClose="true"
        :backgroundClose="true"
    >
      Edycja<br/>
      <input
          type="text"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          v-model="project.name"
          required
          autofocus
      /><br/>
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
  name: "ProjectList",
  data: function () {
    return {
      isAdmin: null,
      user: null,
      projects: null,
      showModalCreate: false,
      showModalEdit: false,
      project: {
        id: null,
        name: null
      }
    }
  },
  created() {
    this.user = JSON.parse(window.localStorage.getItem('user'));
    this.isAdmin = this.user.type === 'admin';
    this.getProjectList();
  },
  methods: {
    getProjectList() {
      API.apiClient.get('api/project').then(response => {
         this.setProjectsList(response.data.projects)
      });
    },
    setProjectsList(projectsList) {
      this.projects = projectsList;
    },
    removeProject(id) {
      API.apiClient.delete('/api/project/' + id).then(() => {
          this.getProjectList()
      })
    },
    openEditProject(name, id) {
      this.project.id = id
      this.project.name = name
      this.showModalEdit = true;
    },
    saveEdit() {
      this.showModalEdit = false;
      API.apiClient.patch('/api/project/' + this.project.id, {
        name: this.project.name
      }).then(() => {
        this.resetProject()
        this.getProjectList()
      })
    },
    openModelCreateProject() {
      this.showModalCreate = true;
      this.resetProject()
    },
    createProject() {
      this.showModalCreate = false;
      API.apiClient.post('/api/project/', {
        name: this.project.name
      }).then(() => {
        this.resetProject()
        this.getProjectList()
      })
    },
    resetProject() {
      this.project.name = null
      this.project.id = null
    },
    viewTasks(id) {
      this.$router.push({
        name: 'taskList',
        params: { projectId: id }
      });
    }
  }
}
</script>
