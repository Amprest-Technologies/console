<template>
  <div
    id="pay-airtel-money"
    class="accordion container bg-white rounded shadow"
  >
    <button
      class="accordion-intro flex items-center w-full p-2 lg:p-3 focus:outline-none"
      @click.prevent="toggleAccordion"
    >
      <img
        src="/img/payments/airtel-money.png"
        class="mr-4 w-20"
      />
      <h2 class="font-semibold font-avenir text-lg">
        Pay with Airtel Money
      </h2>
      <img
        src="/img/loader.svg"
        class="ml-auto mr-4"
        width="40"
      />
    </button>

    <div class="accordion-body hidden transition-all duration-500 ease-in-out">
      <div class="bg-wheat mb-4 px-5 py-4">
        <ol class="list-decimal px-5">
          <li class="mb-3 font-work-sans">
            Go to your Airtel Money Menu.
          </li>
          <li class="mb-3 font-work-sans">
            Go to Lipa na Airtel Money.
          </li>
          <li class="mb-3 font-work-sans">
            Select Pay Bill.
          </li>
          <li class="mb-3 font-work-sans">
            Enter
            <strong class="font-avenir text-md">{{ 204440 }}</strong>
            as the Business Number.
          </li>
          <li class="mb-3 font-work-sans">
            Enter
            <strong class="font-avenir text-md">{{ 501042345 }}</strong>
            as the Account Number.
          </li>
          <li class="mb-3 font-work-sans">
            Enter
            <strong
              class="font-avenir text-md"
            >KES {{ "25000".toLocaleString() }}</strong>
            as the Amount.
          </li>
          <li class="mb-3 font-work-sans">
            Enter your pin.
          </li>
          <li class="mb-3 font-work-sans">
            Enter confirmation code in the input field below and click
            'Confirm'.
          </li>
        </ol>

        <div class="feedback">
          <p class="text-red-600 text-sm italic font-avenir">
            {{ "message" }}
          </p>
        </div>

        <!-- Submission button. -->
        <button
          class="w-full mt-2 bg-teal-600 transition ease-out duration-700 text-white
          py-2 px-4 border rounded font-work-sans disabled:opacity-0"
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
      isHidden: false,
    };
  },

  methods: {
    toggleAccordion() {
      // Get the elements.
      const body = document.querySelector('#pay-airtel-money>.accordion-body');

      // Check the expanded status.
      this.isHidden = body.classList.contains('hidden');

      // Toggle the class appropriately.
      if (this.isHidden) {
        body.classList.remove('hidden');
      } else {
        body.classList.add('hidden');
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.bg-wheat {
  --bg-opacity: 0.8;
  background-color: wheat;
  background-color: rgba(wheat, var(--bg-opacity));
}
</style>
