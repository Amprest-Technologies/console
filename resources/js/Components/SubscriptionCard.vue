<template>
  <div class="subscription bg-white flex flex-row rounded shadow mb-3 p-3">
    <div class="subscription-item flex flex-col mr-auto">
      <h5 class="title">Service</h5>
      <p class="content italic text-sm text-blue-500">
        {{ subscription.tier.service.name }}
      </p>
    </div>
    <div class="subscription-item flex flex-col mr-auto">
      <h5 class="title">Expiry Date</h5>
      <p class="content italic text-sm text-blue-500">
        {{ subscription.expires_at | fromTime }}
      </p>
    </div>
    <div class="subscription-item flex flex-col mr-auto">
      <h5 class="title">Expiry Date</h5>
      <p class="content italic text-sm text-blue-500">
        {{ status }}
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    subscription: { type: Object, default: () => { } }
  },

  computed: {
    status: function () {
      return this.subscription.is_active ? 'active' : 'inactive'
    }
  }
}
</script>

<style>
</style>