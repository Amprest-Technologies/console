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

    <div class="container p-6">
      <h2>Available Services</h2>
      <div class="services">
        <div
          class="max-w-sm rounded overflow-hidden shadow-lg bg-white"
          v-for="service in project.available_services"
          :key="`service-${service.slug}`"
        >
          <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">{{ service.name }}</div>
            <p class="text-gray-700 text-base">{{ service.description }}</p>
            <inertia-link
              :href="
                $route('dashboard.projects.subscribe', {
                  project: project.uuid,
                  service: service.slug,
                })
              "
            >
              <button
                class="bg-white hover:bg-gray-100 text-gray-800 font-semibold mt-2 py-2 px-4 border border-gray-400 rounded shadow"
              >
                Renew
              </button>
            </inertia-link>
          </div>
        </div>
      </div>
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