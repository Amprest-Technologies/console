<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ project.name }}
      </h2>
    </template>

    <!-- Project details. -->
    <div class="project-details container mx-auto px-4 p-6">
      <h1 class="font-bold">{{ project.description }}</h1>
      <p class="font-mono text-gray-800">
        <span class="font-bold">UUID</span>: {{ project.uuid }}
      </p>
      <p class="font-mono text-gray-800">
        <span class="font-bold">API Key</span>: {{ project.api_key }}
      </p>
    </div>

    <div class="container mb-10 mx-auto px-4">
      <h2 class="playfair-font text-2xl font-semibold mb-2">
        Available Services
      </h2>
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
              v-if="!isServiceActive(service.id)"
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
    <div class="container mb-10 mx-auto px-4">
      <h1 class="text-2xl">Project Subscription History</h1>
      <div class="project-subscription-history container pb-6">
        <div class="flex flex-col">
          <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div
              class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
            >
              <div
                class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
              >
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th
                        class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Service Name
                      </th>
                      <th
                        class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Expires At
                      </th>
                      <th
                        class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Status
                      </th>
                      <th
                        class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                      >
                        Tier
                      </th>
                      <th class="px-6 py-3 bg-gray-50"></th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr
                      v-for="subscription in project.subscriptions"
                      :key="`subscription-${subscription.id}`"
                    >
                      <td class="px-6 py-4 whitespace-no-wrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-10 w-10">
                            <img
                              class="h-10 w-10 rounded-full"
                              src="/img/logo.png"
                              alt=""
                            />
                          </div>
                          <div class="ml-4">
                            <div
                              class="text-sm leading-5 font-medium text-gray-900"
                            >
                              {{ subscription.tier.service.name }}
                            </div>
                            <div class="text-sm leading-5 text-gray-500">
                              <!-- {{ subscription.tier.service.description }} -->
                            </div>
                          </div>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-no-wrap">
                        <div class="text-sm leading-5 text-gray-900">
                          {{ subscription.expires_at | fromTime }}
                        </div>
                        <div class="text-sm leading-5 text-gray-500">
                          <!-- Optimization -->
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-no-wrap">
                        <span
                          class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                        >
                          {{ subscription.is_active ? "Active" : "Inactive" }}
                        </span>
                      </td>
                      <td
                        class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500"
                      >
                        {{ subscription.tier.name }}
                      </td>
                      <td
                        class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium"
                      >
                        <a
                          href="#"
                          class="text-indigo-600 hover:text-indigo-900"
                          >View</a
                        >
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";

export default {
  components: { AppLayout },

  props: {
    project: { type: Object, default: () => { } }
  },

  methods: {
    isServiceActive: function (service_id) {
      return this.project.subscriptions.filter((sub) => {
        return sub.is_active && sub.tier.service.id === service_id
      }).length > 0
    }
  }
}
</script>

<style>
</style>