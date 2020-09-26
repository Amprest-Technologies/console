<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="container mx-auto px-6 py-8 flex flex-row">
      <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"> -->
      <inertia-link
        v-for="project in projects"
        :key="`project-${project.id}`"
        :href="$route('dashboard.projects.show', { project: project.uuid })"
      >
        <project-card :project="project"></project-card>
      </inertia-link>
      <!-- </div>
      </div> -->
    </div>
  </app-layout>
</template>

<script>
import AppLayout from './../Layouts/AppLayout'
import ProjectCard from './../Components/ProjectCard'

export default {
  components: { AppLayout, ProjectCard },
  props: {
    projects: { type: Array, default: () => [] }
  }
}
</script>
