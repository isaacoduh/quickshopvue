<template>
    <div class="w-full">
        <div class="lg:w-2/3 w-full mx-auto mt-8 overflow-auto">
            <table class="table-auto w-full text-left whitespace-no-wrap">
                <thead>
                    <tr>
                        <th
                            class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200 rounded-tl rounded-bl"
                        >
                            Item
                        </th>
                        <th
                            class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200"
                        >
                            Quantity
                        </th>
                        <th
                            class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200"
                        >
                            Price
                        </th>
                        <th
                            class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-200"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in cart" :key="item.id">
                        <td class="p-4" v-text="item.name"></td>
                        <td class="p-4" v-text="item.quantity"></td>
                        <td class="p-4" v-text="cartLineTotal(item)"></td>
                        <td class="w-10 text-right">
                            <button
                                style="background-color: #801325 !important"
                                class="flex ml-auto text-sm text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded"
                                @click="$store.commit('removeFromCart', index)"
                            >
                                Remove
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-4 font-bold">Total Amount</td>
                        <td class="p-4 font-bold" v-text="cartQuantity"></td>
                        <td class="p-4 font-bold" v-text="cartTotal"></td>
                        <td class="w-10 text-right"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="lg:w-2/3 w-full mx-auto mt-8">
            <div class="p-2 w-full">
                <button
                    style="background-color: #801325 !important"
                    type="button"
                    class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
                    @click="showModal = true"
                    value="Pay with Bestaf"
                >
                    Checkout with Bestaf
                </button>
            </div>
        </div>
        <div v-if="showModal">
            <transition name="payModal">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button
                                        type="button"
                                        class="close"
                                        @click="showModal = false"
                                    >
                                        <button
                                            type="button"
                                            class="close"
                                            data-dismiss="modal"
                                            aria-label="Close"
                                        >
                                            <span aria-hidden="true"
                                                >&times;</span
                                            >
                                        </button>

                                        <h2 class="modal-title">
                                            Bestaf Payment Form
                                        </h2>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="cardNumberInput"
                                            >Card Number</label
                                        >
                                        <input
                                            v-model="bestaf.card_number"
                                            type="number"
                                            class="form-control"
                                            id="cardnumberinput"
                                            :disabled="paymentProcessing"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="cvvNumberInput">CVV</label>
                                        <input
                                            v-model="bestaf.cvv"
                                            type="number"
                                            class="form-control"
                                            id="cvvNumberInput"
                                            aria-describedby="emailHelp"
                                            :disabled="paymentProcessing"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="expiryMonthDate"
                                            >Expiry Month</label
                                        >
                                        <input
                                            v-model="bestaf.expiry_month"
                                            type="number"
                                            class="form-control"
                                            id="expiryMonthDate"
                                            :disabled="paymentProcessing"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="expiryYearDate"
                                            >Expiry Year</label
                                        >
                                        <input
                                            v-model="bestaf.expiry_year"
                                            type="number"
                                            class="form-control"
                                            id="expiryYearDate"
                                            :disabled="paymentProcessing"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <input
                                            v-model="bestaf.currency"
                                            type="hidden"
                                            value="NGN"
                                            name="currency"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <input
                                            type="hidden"
                                            value="totalPrice"
                                            class="form-control"
                                            id="amountInput"
                                            v-model="bestaf.amount"
                                            aria-describedby="amountHelp"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="fullNameInput"
                                            >Fullname</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="fullNameInput"
                                            v-model="bestaf.fullname"
                                            :disabled="paymentProcessing"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="emailInput"
                                            >Email address</label
                                        >
                                        <input
                                            type="email"
                                            class="form-control"
                                            id="emailInput"
                                            v-model="bestaf.email"
                                            :disabled="paymentProcessing"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <input
                                            type="hidden"
                                            class="form-control"
                                            id="txRefInput"
                                            v-model="bestaf.tx_ref"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <input
                                            type="hidden"
                                            name="redirect_url"
                                            value="https://webhook.site/3ed41e38-2c79-4c79-b455-97398730866c"
                                            v-model="bestaf.redirect_url"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <input
                                            type="hidden"
                                            name="mode"
                                            value="pin"
                                            v-model="bestaf.authorization.mode"
                                        />
                                        <input
                                            type="hidden"
                                            name="pin"
                                            value="3310"
                                            v-model="bestaf.authorization.pin"
                                        />
                                    </div>
                                    <br />
                                    <button
                                        @click="createBestafData"
                                        type="submit"
                                        class="btn btn-primary"
                                        :disabled="paymentProcessing"
                                        v-text="
                                            paymentProcessing
                                                ? 'Processing'
                                                : 'Pay Now'
                                        "
                                    ></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<style>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}
</style>

<script>
export default {
    data() {
        return {
            totalPrice: 0,
            showModal: false,
            showToast: true,
            stripe: {},
            bestaf: {
                card_number: "",
                cvv: "",
                expiry_month: "",
                expiry_year: "",
                currency: "",
                amount: "",
                fullname: "",
                email: "",
                tx_ref: "",
                redirect_url: "",
                authorization: {
                    mode: "",
                    pin: ""
                }
            },
            cardElement: {},
            customer: {
                first_name: "",
                last_name: "",
                email: "",
                address: "",
                city: "",
                state: "",
                zip_code: ""
            },
            paymentProcessing: false,
            paymentData: {
                tx_ref: this.generateReference(),
                amount: 100,
                currency: "NGN",
                payment_options: "card, ussd",
                redirect_url: "",
                meta: {
                    consumer_id: "7898",
                    consumer_mac: "kjs9s8ss7dd"
                },
                customer: {
                    name: "Mendez Rio",
                    email: "customer@gmail.com",
                    phone_number: "08193949494"
                },
                customizations: {
                    title: "Customization Title",
                    description: "Customization Description",
                    logo: "https://flutterwave.com/images/logo-colored.svg"
                },
                onclose: this.closedPaymentModal
            }
        };
    },
    async mounted() {
        this.totalPrice = this.getTotalPrice();
    },
    methods: {
        async createBestafData() {
            this.paymentProcessing = true;
            var data = {
                card_number: this.bestaf.card_number,
                cvv: this.bestaf.cvv,
                expiry_month: this.bestaf.expiry_month,
                expiry_year: this.bestaf.expiry_year,
                currency: "NGN",
                amount: this.getTotalPrice(),
                fullname: this.bestaf.fullname,
                email: this.bestaf.email,
                tx_ref: this.generateReference(),
                redirect_url:
                    "https://webhook.site/3ed41e38-2c79-4c79-b455-97398730866c",
                authorization: {
                    mode: "pin",
                    pin: "3310"
                }
            };
            console.log(data);
            // send a post request to the server
            // this.payWithBestaf(data);
            // return a response back from the server with a parameter
            const bestafPayment = await axios.post(
                "https://1unov19af9.execute-api.eu-west-2.amazonaws.com/dev/api/charges/card",
                data,
                {
                    headers: {
                        Authorization:
                            "Bearer BESSECK_TEST-d0ff22d9225446ee8cf85682f618bafb-X"
                    }
                }
            );
            console.log("Bestaf Payment Response", bestafPayment);

            if (bestafPayment.data.status == "success") {
                console.log("Success from inside response function");
                this.customer.payment_method_id = 2;
                this.customer.amount = this.$store.state.cart.reduce(
                    (acc, item) => acc + item.price * item.quantity,
                    0
                );

                axios
                    .post("/api/bestaf", {
                        payment_meta: bestafPayment.data,
                        payment_method_id: 2,
                        cart: JSON.stringify(this.$store.state.cart)
                    })
                    .then(response => {
                        this.paymentProcessing = false;
                        this.showModal = false;
                        console.log(response);
                        this.$store.commit("updateOrder", response.data);
                        this.$store.dispatch("clearCart");
                        this.$router.push({ name: "order.summary" });
                    })
                    .catch(error => {
                        this.paymentProcessing = false;
                        console.log(error);
                    });
            }
        },
        cartLineTotal(item) {
            let amount = item.price * item.quantity;
            amount = amount / 100;
            return amount.toLocaleString("en-US", {
                style: "currency",
                currency: "NGN"
            });
        },
        getTotalPrice() {
            return (
                this.$store.state.cart.reduce(
                    (acc, item) => acc + item.price * item.quantity,
                    0
                ) / 100
            );
        },

        generateReference() {
            let date = new Date();
            return date.getTime().toString();
        }
    },
    computed: {
        cart() {
            return this.$store.state.cart;
        },
        cartQuantity() {
            return this.$store.state.cart.reduce(
                (acc, item) => acc + item.quantity,
                0
            );
        },
        cartTotal() {
            let amount = this.$store.state.cart.reduce(
                (acc, item) => acc + item.price * item.quantity,
                0
            );
            amount = amount / 100;
            return amount.toLocaleString("en-US", {
                style: "currency",
                currency: "NGN"
            });
        }
    }
};
</script>
