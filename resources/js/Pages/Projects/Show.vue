<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ project.name }}
      </h2>
    </template>

    <!-- Project details. -->
    <div class="project-details container p-6">
      <h1 class="font-bold">{{ project.description }}</h1>
      <p class="font-mono text-gray-800">
        <span class="font-bold">UUID</span>: {{ project.uuid }}
      </p>
      <p class="font-mono text-gray-800">
        <span class="font-bold">API Key</span>: {{ project.api_key }}
      </p>
    </div>

    <!-- Project subscription history. -->
    <h1 class="text-2xl mx-6">Project Subscription History</h1>
    <div class="project-subscription-history container pb-6 px-6">
      <subscription-card
        v-for="subscription in project.subscriptions"
        :key="`subscription-${subscription.id}`"
        :subscription="subscription"
      ></subscription-card>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import SubscriptionCard from "../../Components/SubscriptionCard"

export default {
  components: { AppLayout, SubscriptionCard },
  props: {
    project: { type: Object, default: () => { } }
  },
}
</script>

<style>
</style>