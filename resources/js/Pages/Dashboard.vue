<template>
  <app-layout>
    <template #header>
      <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h1>
    </template>

    <div class="container mx-auto px-6 py-8 flex flex-col md:flex-row">
      <inertia-link
        v-for="project in projects"
        :key="`project-${project.id}`"
        :href="$route('dashboard.projects.show', { project: project.uuid })"
      >
        <project-card :project="project"></project-card>
      </inertia-link>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '../Layouts/AppLayout.vue';
import ProjectCard from '../Components/ProjectCard.vue';

export default {
  components: { AppLayout, ProjectCard },
  props: {
    projects: { type: Array, default: () => [] },
  },
};
</script>
