<template>
  <div class="max-w-sm mx-auto flex mb-3 p-6 bg-white rounded-lg shadow-xl">
    <div class="flex-shrink-0">
      <img
        class="h-12 w-12"
        src="/img/logo.png"
        :alt="`${project.name} Logo`"
      />
    </div>
    <div class="ml-6 pt-1">
      <h4 class="text-xl text-gray-900 leading-tight">{{ project.name }}</h4>
      <p class="text-base text-gray-600 leading-normal">
        {{ project.description }}
      </p>
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