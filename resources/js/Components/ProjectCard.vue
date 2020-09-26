<template>
  <div
    class="max-w-sm rounded overflow-hidden shadow-lg bg-white mb-4 mx-3 h-full"
  >
    <!-- <img class="w-full" src="/img/logo.png" alt="Sunset in the mountains" /> -->
    <div class="px-6 py-4">
      <div class="font-bold font-sans text-xl mb-2">{{ project.name }}</div>
      <p class="text-gray-700 text-base">{{ project.description }}</p>
    </div>

    <!-- Services. -->
    <div class="px-6">
      <span
        v-for="service in project.available_services"
        :key="`service-${service.slug}`"
        class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
        >#{{ service.name }}</span
      >
    </div>
  </div>
</template>

<script>
export default {
  props: {
    project: { type: Object, default: () => [] }
  }
}
</script>

<style lang="scss" scoped>
</style>