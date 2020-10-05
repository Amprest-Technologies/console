<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Subscribe {{ project.name }} to {{ service.name }}
      </h2>
    </template>

    <div class="container mb-4 mx-auto px-4 py-6">
      <div class="flex flex-wrap lg:-mx-2">
        <div class="w-full lg:w-2/3 mb-4 px-2">
          <!-- Choose a tier. -->
          <div class="bg-white shadow rounded mb-4 px-5 py-4" v-if="!canPay">
            <!-- Heading. -->
            <h2 class="font-bold playfair-font text-3xl">Choose a tier</h2>

            <!-- Tiers. -->
            <div class="radio-group my-4">
              <div class="flex flex-col lg:flex-row mb-1">
                <div
                  class="tier flex-grow shadow-md rounded-md mb-3 lg:mr-4"
                  v-for="tier in service.tiers"
                  :key="`tier-${tier.id}`"
                >
                  <div
                    class="tier-header bg-white rounded-tl rounded-tr text-center avenir-font py-3"
                  >
                    <h3 class="name font-semibold text-xl">{{ tier.name }}</h3>
                    <h4
                      class="price font-bold text-3xl flex items-center mx-auto"
                    >
                      <span class="text-gray-400 text-lg ml-auto mr-2"
                        >KES</span
                      >
                      <span class="mr-auto">
                        {{ tier.price.toLocaleString() }}
                      </span>
                    </h4>
                  </div>

                  <div
                    class="tier-body bg-gray-200 p-5 rounded-bl-md rounded-br-md flex-fi"
                  >
                    <ul class="list-disc mb-3 px-5 work-sans-font font-medium">
                      <li>{{ tier.usage_limit.toLocaleString() }} requests</li>
                    </ul>

                    <button
                      class="avenir-font font-semibold mx-auto shadow-md rounded w-full bg-white py-2 focus:outline-none"
                      :class="{
                        'bg-blue-500 text-white':
                          subscription.tier_id === tier.id,
                      }"
                      @click.prevent="onSubscribe(tier)"
                    >
                      {{
                        subscription.tier_id === tier.id ? "Selected" : "Select"
                      }}
                    </button>
                  </div>
                </div>
                <!-- <div
                  class="flex items-center mr-8 mb-2"
                  v-for="tier in service.tiers"
                  :key="`tier-${tier.id}`"
                >
                  <input
                    type="radio"
                    name="tier"
                    :value="tier.id"
                    :id="`tier-${tier.id}`"
                    @change="onSubscribe(tier)"
                    v-model.number="$v.subscription.tier_id.$model"
                  />
                  <label
                    :for="`tier-${tier.id}`"
                    class="text-md italic work-sans-font ml-2"
                    >{{ tier.name }} (KES
                    {{ tier.price.toLocaleString() }})</label
                  >
                </div> -->
              </div>
              <div class="feedback" v-if="$v.subscription.tier_id.$error">
                <p class="text-red-500 text-sm">You must select a tier</p>
              </div>
            </div>
          </div>

          <!-- Make the payment. -->
          <ExpressCheckout
            v-if="canPay"
            :payment-options="paymentOptions"
            :payload="paymentPayload"
            @completed="onComplete"
          />
        </div>

        <div class="w-full lg:w-1/3 mb-4 px-2">
          <!-- Summary. -->
          <div class="bg-white shadow rounded p-3 px-5">
            <h2 id="summary" class="playfair-font font-bold text-3xl mb-4">
              Your Summary
            </h2>

            <!-- Summary Details. -->
            <div class="items flex flex-col" v-if="hasSubscription">
              <div class="item flex items-center mb-2">
                <div class="item-name avenir-font font-semibold">Tier</div>
                <div class="item-details ml-auto italic work-sans-font">
                  {{ subscription.tier.name }}
                </div>
              </div>
              <div class="item flex items-center mb-2">
                <div class="item-name avenir-font font-semibold">
                  Usage Limit
                </div>
                <div class="item-details ml-auto italic work-sans-font">
                  {{ subscription.usage_limit.toLocaleString() }} requests
                </div>
              </div>
              <div class="item flex items-center mb-2">
                <div class="item-name avenir-font font-semibold">Sub-Total</div>
                <div class="item-details ml-auto italic work-sans-font">
                  KES {{ (subscription.amount * 0.86).toLocaleString() }}
                </div>
              </div>
              <div class="item flex items-center mb-2">
                <div class="item-name avenir-font font-semibold">Tax</div>
                <div class="item-details ml-auto italic work-sans-font">
                  KES {{ (subscription.amount * 0.16).toLocaleString() }}
                </div>
              </div>
              <hr class="mb-2 mt-3" />
              <div class="item flex items-center mb-2">
                <div class="item-name avenir-font font-semibold">Total</div>
                <div
                  class="item-details ml-auto avenir-font font-bold text-3xl"
                >
                  KES {{ subscription.amount.toLocaleString() }}
                </div>
              </div>
            </div>

            <!-- Submission button. -->
            <button
              class="w-full mt-2 bg-blue-500 transition ease-out duration-700 text-white py-2 px-4 border border-gray-400 rounded shadow work-sans-font flex items-center"
              @click.prevent="onSubmit"
              v-if="hasSubscription && !canPay"
            >
              <img src="/img/loader.svg" v-show="isLoading" width="30" />
              <span class="mx-auto">Subscribe</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { required, minLength, between } from 'vuelidate/lib/validators'
import AppLayout from "../../Layouts/AppLayout"
import ExpressCheckout from "../../Components/Checkout/ExpressCheckout"

// Set API Token on axios.
window.axios.defaults.headers.common["Api-Token"] = process.env.MIX_AMPREST_PAYMENT_API_TOKEN;

export default {
  components: { AppLayout, ExpressCheckout },
  props: {
    project: { type: Object, default: () => { } },
    service: { type: Object, default: () => { } },
  },

  data() {
    return {
      canPay: false,
      status: 'PENDING',
      message: null,
      isLoading: false,
      paymentOptions: ['mpesa'],
      baseURI: `https://pay.amprest.co.ke`,
      subscription: {
        tier_id: null,
        project: this.project,
        project_id: this.project.id,
      },
    }
  },

  computed: {
    hasSubscription: function () {
      return this.subscription.tier_id !== null
    },

    paymentPayload: function () {
      const amount = this.hasSubscription ? this.subscription.amount : 0
      const description = this.hasSubscription
        ? `Amprest Web Services subscription renewal for project: ${this.project.name} using service: ${this.subscription.tier.service.name} on tier: ${this.subscription.tier.name}`
        : null

      return {
        bill_ref_number: this.project.uuid,
        business_short_code: 204440,
        transaction_amount: amount,
        transaction_desc: description
      }
    }
  },

  validations: {
    subscription: { tier_id: { required } }
  },

  methods: {
    /**
     * Add the subscription to the project.
     *
     * @returns {ITransaction | string}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    addSubscription: async function (data) {
      return new Promise((resolve, reject) =>
        window.axios
          .post(`/api/v1/projects/${this.project.uuid}/new-subscription`, data)
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * Add a subscription to a service.
     *
     * @returns {void}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    onSubscribe: function (tier) {
      this.subscription = {
        ...this.subscription,
        ...{
          project_id: this.project.id,
          tier_id: tier.id,
          usage_limit: tier.usage_limit,
          amount: tier.price,
          tier: { ...tier, ...{ service: this.service } },
        },
      };

      // Touch vuelidate.
      this.$v.subscription.$touch();

      // Scroll to the submit button.
      document.getElementById("summary").scrollIntoView({
        behavior: "smooth",
      });
    },

    /**
     * User submission.
     *
     * @returns {void}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    onSubmit: function () {
      // Set the form to loading and clear message.
      this.canPay = true
    },

    /**
     * When the completed event is called after successful payment.
     *
     * @returns {void}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    onComplete: function (_data) {
      // Add the new subscription.
      this.addSubscription(this.subscription)
        .then((res) => window.location.replace(
          `/projects/${this.project.uuid}`
        ))
        .catch((err) => {
          this.message = err;
          this.isLoading = false;
        })
    }
  }
}
</script>

<style lang="scss" scoped>
</style>