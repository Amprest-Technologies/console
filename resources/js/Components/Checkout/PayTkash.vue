<template>
  <div id="pay-tkash" class="accordion container bg-white rounded shadow">
    <button
      class="accordion-intro flex items-center w-full p-2 lg:p-3 focus:outline-none"
      @click.prevent="toggleAccordion"
    >
      <img src="/img/payments/tkash.png" class="mr-4 w-20" />
      <h2 class="font-semibold avenir-font text-lg">Pay with T-Kash</h2>
      <img src="/img/loader.svg" class="ml-auto mr-4" width="40" />
    </button>

    <div class="accordion-body hidden transition-all duration-500 ease-in-out">
      <div class="bg-wheat mb-4 px-5 py-4">
        <ol class="list-decimal px-5">
          <li class="mb-3 work-sans-font">Go to your T-Kash Menu.</li>
          <li class="mb-3 work-sans-font">Go to Lipa na T-Kash.</li>
          <li class="mb-3 work-sans-font">Select Pay Bill.</li>
          <li class="mb-3 work-sans-font">
            Enter
            <strong class="avenir-font text-md">{{ 204440 }}</strong>
            as the Business Number.
          </li>
          <li class="mb-3 work-sans-font">
            Enter
            <strong class="avenir-font text-md">{{ 501042345 }}</strong>
            as the Account Number.
          </li>
          <li class="mb-3 work-sans-font">
            Enter
            <strong class="avenir-font text-md"
              >KES {{ "25000".toLocaleString() }}</strong
            >
            as the Amount.
          </li>
          <li class="mb-3 work-sans-font">Enter your pin.</li>
          <li class="mb-3 work-sans-font">
            Enter confirmation code in the input field below and click
            'Confirm'.
          </li>
        </ol>

        <div class="feedback">
          <p class="text-red-600 text-sm italic avenir-font">{{ "message" }}</p>
        </div>

        <!-- Submission button. -->
        <button
          class="w-full mt-2 bg-teal-600 transition ease-out duration-700 text-white py-2 px-4 border rounded work-sans-font disabled:opacity-0"
        >
          Confirm
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isHidden: false
    }
  },

  methods: {
    toggleAccordion: function () {
      // Get the elements.
      const trigger = document.querySelector('#pay-tkash>.accordion-intro');
      const body = document.querySelector('#pay-tkash>.accordion-body');

      // Check the expanded status.
      this.isHidden = body.classList.contains('hidden');

      // Toggle the class appropriately.
      this.isHidden
        ? body.classList.remove('hidden')
        : body.classList.add('hidden')
    }
  },
}
</script>

<style lang="scss" scoped>
.bg-wheat {
  --bg-opacity: 0.8;
  background-color: wheat;
  background-color: rgba(wheat, var(--bg-opacity));
}
</style>