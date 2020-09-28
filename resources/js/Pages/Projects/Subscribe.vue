<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Subscribe {{ project.name }} to {{ service.name }}
      </h2>
    </template>

    <div class="container py-6">
      <!--  -->
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout"

// Set API Token on axios.
window.axios.defaults.headers.common["Api-Token"] = process.env.MIX_AMPREST_PAYMENT_API_TOKEN;

export default {
  components: { AppLayout },
  props: {
    project: { type: Object, default: () => { } },
    service: { type: Object, default: () => { } },
  },

  data() {
    return {
      isLoading: false,
      message: null,
      transaction: null,
      baseURI: `https://pay.amprest.co.ke`,
      subscription: {
        project_id: this.project.id,
        project: this.project,
      },
    }
  },

  methods: {
    /**
   * Add a subscription to a service.
   *
   * @returns {void}
   * @author Brian K. Kiragu <brian@amprest.co.ke>
   */
    onSubscribe(tier) {
      const today = new Date();
      this.subscription = {
        ...this.subscription,
        ...{
          project_id: this.project.id,
          tier_id: tier.id,
          usage_limit: tier.usage_limit,
          amount: tier.price,
          expires_at: new Date(today.setMonth(today.getMonth() + 1)),
          tier: tier,
        },
      };

      // Scroll to the submit button.
      document.getElementById("summary").scrollIntoView({
        behavior: "smooth",
      });
    },

    /**
     * Prepare a C2B Transaction.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {ITransaction | string}
     */
    async prepareTransaction(data) {
      return new Promise((resolve, reject) =>
        this.axios
          .post(`${this.baseURI}/mobile-money/mpesa/c2b/prepare`, data)
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * Check a C2B Transaction.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {ITransaction | string}
     */
    async checkTransaction(data) {
      return new Promise((resolve, reject) =>
        this.axios
          .post(`${this.baseURI}/mobile-money/mpesa/c2b/check`, data)
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * Mark a transaction as retrieved.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {ITransaction | string}
     */
    async retrievedTransaction(data) {
      return new Promise((resolve, reject) =>
        this.axios
          .post(`${this.baseURI}/mobile-money/mpesa/c2b/retrieve`, data)
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * Add the subscription to the project.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {ITransaction | string}
     */
    async addSubscription(data) {
      return new Promise((resolve, reject) =>
        this.axios
          .post(`/api/v1/projects/${this.project.uuid}/new-subscription`, data)
          .then(({ data }) => resolve(data))
          .catch(({ message }) => reject(message))
      );
    },

    /**
     * User submission.
     *
     * @returns {void}
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     */
    onSubmit() {
      // Set the form to loading and clear message.
      this.isLoading = true;
      this.message = null;

      this.prepareTransaction({
        business_short_code: 204440,
        bill_ref_number: this.project.uuid,
        transaction_amount: this.subscription.amount,
        transaction_desc: `New Subscription for ${this.project.name}`,
      })
        .then((res) => {
          // Set the transaction.
          this.transaction = { ...res };

          // Remove the loading state.
          this.isLoading = false;
        })
        .catch((err) => {
          this.message = err;
          this.isLoading = false;
        });
    },

    /**
     * Check if the transaction was completed.
     *
     * @author Brian K. Kiragu <brian@amprest.co.ke>
     * @returns {void}
     */
    onConfirm() {
      // Set the form to loading and clear message.
      this.isLoading = true;
      this.message = null;

      // Send the request.
      this.checkTransaction({
        business_short_code: this.transaction.business_short_code,
        bill_ref_number: this.transaction.bill_ref_number,
        transaction_amount: this.transaction.transaction_amount,
        transaction_type: "Pay Bill",
      })
        .then((res) => {
          this.transaction = { ...res };

          if (this.transaction.status === "COMPLETED") {
            // Retrieve the transaction.
            this.retrievedTransaction({
              transaction_id: this.transaction.transaction_id,
            })
              .then((data) =>
                // Add the new subscription.
                this.addSubscription(this.subscription)
                  .then((res) =>
                    window.location.replace(`/home/projects/${this.project.uuid}`)
                  )
                  .catch((err) => {
                    this.message = err;
                    this.isLoading = false;
                  })
              )
              .catch((message) => {
                this.message = message;
                this.isLoading = false;
              });
          } else {
            this.message = "This transaction has not yet been completed.";
            this.isLoading = false;
          }
        })
        .catch((err) => {
          this.message = err;
          this.isLoading = false;
        });
    }
  }
}
</script>

<style>
</style>