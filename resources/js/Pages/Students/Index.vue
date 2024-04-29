<template>
    <div>
      <input v-model="filters.search" type="text" placeholder="Search...">
      <select v-model="filters.status">
        <option value="">All</option>
        <option value="1">Active</option>
        <option value="0">Inactive</option>
      </select>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Age</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="student in filteredStudents" :key="student.id">
            <td>{{ student.name }}</td>
            <td>{{ student.age }}</td>
            <td>{{ student.status ? 'Active' : 'Inactive' }}</td>
            <td>
              <router-link :to="{ name: 'students.edit', params: { student: student.id } }">Edit</router-link> |
              <button @click="deleteStudent(student)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="card">
        <div class="card-header">
          <h3>Add Student</h3>
        </div>
        <div class="card-body">
          <form @submit.prevent="createStudent">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" id="name" v-model="form.name" class="form-control" />
            </div>
            <div class="form-group">
              <label for="age">Age</label>
              <input type="number" id="age" v-model.number="form.age" class="form-control" />
            </div>
            <div class="form-group">
              <label for="status">Status</label>
              <select id="status" v-model="form.status" class="form-control">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Student</button>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue';
  
  export default {
    setup() {
      const students = ref([]);
      const filters = ref({ search: '', status: '' });
      const form = ref({ name: '', age: '', status: '1' });
  
      const filteredStudents = computed(() => {
        return students.value.filter(student => {
          const { search, status } = filters.value;
          return student.name.includes(search) && (status === '' || student.status === parseInt(status));
        });
      });
  
      const createStudent = () => {
        // Implement logic to create a new student
        students.value.push({
          id: Date.now(), // Generate a unique ID
          name: form.value.name,
          age: parseInt(form.value.age),
          status: parseInt(form.value.status) === 1,
        });
        // Reset form fields after submission
        form.value.name = '';
        form.value.age = '';
        form.value.status = '1';
      };
  
      const deleteStudent = (student) => {
        // Implement logic to delete a student
        const index = students.value.findIndex(s => s.id === student.id);
        if (index !== -1) {
          students.value.splice(index, 1);
        }
      };
  
      return { students, filters, form, filteredStudents, createStudent, deleteStudent };
    }
  };
  </script>
  