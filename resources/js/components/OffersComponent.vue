<template>
<div class="container mt-4 pt-4">
    <div class="row justify-content-center mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Create Offer</div>

                <div class="card-body">
                    <form @submit.prevent="addOffer()">
                        <div class="row mb-3">
                            <div class="col-12">
                                <span v-for="e in errors" v-bind:key="e" class="alert alert-danger">{{ e }}</span>
                            </div>
                        </div>
                        <section v-if="currentStep==1">
                            <h3>Select Customer</h3>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <select id="customer_id" class="form-control" name="customer_id" v-model="form.customer_id" required>
                                        <option value="0" disabled>Select a customer</option>
                                        <option v-for="customer in customers" v-bind:key="customer.id" :value="customer.id">
                                            {{ customer.id }} - {{ customer.name }}
                                            {{ customer.surname }}
                                        </option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <button class="btn btn-primary float-right" @click.prevent="nextStep()">Continue</button>
                                </div>
                            </div>
                        </section>
                        <section v-if="currentStep==2">
                            <div class="row">
                                <div class="col-6">
                                    <h3>Select Vehicle</h3>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <select id="vehicles" class="form-control" name="vehicles" required @change="vehicleSelected()" v-model="selectedVehicle">
                                                <option value="0" disabled selected>Select a vehicle</option>
                                                <option v-for="vehicle in vehicles" v-bind:key="vehicle.id" :value="vehicle.id">
                                                    {{ vehicle.id }} - {{ vehicle.model }} - ${{vehicle.price}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-secondary" @click.prevent="prevStep()"> Go Back</button>
                                            <button type="submit" class="btn btn-primary float-right">
                                                {{ btnText }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h3>Vehicles Selected</h3>
                                    <ul class="p-3">
                                        <li class="mb-3" v-for="vehicle in form.vehicles" v-bind:key="vehicle.vehicle_id">{{vehicleMapper[vehicle.vehicle_id].model}} - {{vehicle.quantity}} X ${{vehicleMapper[vehicle.vehicle_id].price}} = ${{vehicle.quantity * vehicleMapper[vehicle.vehicle_id].price}}
                                            <button class="btn btn-danger btn-sm" @click.prevent="deleteVehicle(vehicle.vehicle_id)">
                                                x
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Offers</div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-4" v-for="offer in offers" v-bind:key="offer.id">
                            <div class="card">
                                <div class="card-header">
                                    Offer Id:
                                    <span class="btn btn-primary btn-sm">#{{ offer.id }}</span>
                                    <button class="btn btn-danger pull-right btn-sm" @click="deleteOffer(offer.id)">
                                        Delete
                                    </button>
                                </div>
                                <div class="card-body p-3">
                                    <p>Customer: {{ offer.customer.name }} {{ offer.customer.surname }}</p>
                                    <p>Email: {{ offer.customer.email }} </p>
                                    <p v-if="offer.pdf_url">PDF Link: <a v-bind:href="offer.pdf_url" target="_blank">Link</a></p>
                                    <p>Vehicles:
                                        <ul class="p-3">
                                            <li class="mb-2" v-for="vehicle in offer.vehicles" v-bind:key="vehicle.vehicle_id"> {{vehicle.vehicle.model}} - ${{vehicle.vehicle.price}} X {{vehicle.quantity}}</li>
                                        </ul>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            customers: [],
            vehicles: [],
            vehicleMapper: [],
            selectedVehicle: 0,
            selectedVehicles: [],
            offers: [],
            offer: {
                id: "",
                customer_id: 0,
                customer: [],
                vehicles: [],
                pdf_url: ""
            },
            offer_id: "",
            edit: false,
            btnText: "Submit",
            form: {
                customer_id: 0,
                vehicles: [],
            },
            totalSteps: 2,
            currentStep: 1,
            errors: [],
        };
    },

    created() {
        this.fetchOffers();
        this.fetchCustomers();
        this.fetchVehicles();
    },

    methods: {
        fetchCustomers() {
            fetch("api/customers")
                .then((res) => res.json())
                .then((data) => {
                    this.customers = data;
                })
                .catch((err) => console.log(err));
        },
        fetchVehicles() {
            fetch("api/vehicles")
                .then((res) => res.json())
                .then((data) => {
                    this.vehicles = data;

                    data.forEach(element => {
                        this.vehicleMapper[element.id] = element;
                    });
                })
                .catch((err) => console.log(err));
        },
        fetchOffers() {
            fetch("api/offers")
                .then((res) => res.json())
                .then((data) => {
                    this.offers = data;
                })
                .catch((err) => console.log(err));
        },
        deleteOffer(offerId) {
            if (confirm("Are you sure?")) {
                fetch(`api/offers/${offerId}`, {
                        method: "delete",
                    })
                    .then((res) => {
                        alert("Offer Deleted!");
                        this.fetchOffers();
                    })
                    .catch((err) => console.log(err));
            }
        },
        addOffer() {
            if (this.form.vehicles.length == 0) {
                this.errors.push("Please select a vehicle!");
                return false;
            }

            //create new offer
            fetch(`api/offers`, {
                    method: "post",
                    body: JSON.stringify(this.form),
                    headers: {
                        "content-type": "application/json",
                    },
                })
                .then((res) => res.json())
                .then((res) => {
                    alert("Offer Added!");
                    this.fetchOffers();
                })
                .catch((err) => console.log(err));
        },
        nextStep() {
            if (this.currentStep == 1 && !this.form.customer_id) {
                this.errors.push("Please select a customer first!");
                return false;
            }
            this.errors = [];
            this.currentStep++;
        },
        prevStep() {
            this.errors = [];
            this.currentStep--;
        },
        vehicleSelected() {
            if (this.selectedVehicles.includes(this.selectedVehicle)) {
                this.form.vehicles.forEach((element, index) => {
                    if (element.vehicle_id == this.selectedVehicle)
                        this.form.vehicles[index].quantity = this.form.vehicles[index].quantity + 1;
                });
            } else {
                this.selectedVehicles.push(this.selectedVehicle);
                this.form.vehicles.push({
                    vehicle_id: this.selectedVehicle,
                    quantity: 1,
                });
            }
            this.selectedVehicle = 0;
        },
        deleteVehicle(vehicle_id) {
            this.form.vehicles.forEach((element, index) => {
                if (element.vehicle_id == vehicle_id) {
                    this.form.vehicles.pop(index);
                    this.selectedVehicles.pop(vehicle_id);
                }
            });
        }
    },
};
</script>
