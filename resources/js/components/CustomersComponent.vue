<template>
  <div class="container mt-4 pt-4">
    <div class="row justify-content-center mb-3">
      <div class="col-12">
        <div class="card">
          <div class="card-header">Create Customer</div>

          <div class="card-body">
            <form @submit.prevent="addCustomer()">
              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right"
                  >Name</label
                >
                <div class="col-md-6">
                  <input
                    id="name"
                    type="text"
                    class="form-control"
                    name="name"
                    v-model="customer.name"
                    required
                    autocomplete="name"
                    autofocus
                  />
                </div>
              </div>

              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right"
                  >Surname</label
                >
                <div class="col-md-6">
                  <input
                    id="surname"
                    type="text"
                    class="form-control"
                    name="surname"
                    v-model="customer.surname"
                    required
                    autofocus
                  />
                </div>
              </div>

              <div class="form-group row">
                <label for="email" class="col-md-4 col-form-label text-md-right"
                  >Email</label
                >
                <div class="col-md-6">
                  <input
                    id="email"
                    type="email"
                    class="form-control"
                    name="email"
                    v-model="customer.email"
                    required
                    autocomplete="email"
                  />
                </div>
              </div>

              <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                  <button type="submit" class="btn btn-primary">
                    {{ btnText }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">Customers</div>
          <div class="card-body p-3">
            <div class="row">
              <div
                class="col-4"
                v-for="customer in customers"
                v-bind:key="customer.id"
              >
                <div class="card">
                  <div class="card-header">Customer Id: <span class="btn btn-primary btn-sm">#{{ customer.id }}</span></div>
                  <div class="card-body p-3">
                    <p>Name: {{ customer.name }} {{ customer.surname }}</p>
                    <p>Email: {{ customer.email }}</p>
                    <button
                      class="btn btn-danger"
                      @click="deleteCustomer(customer.id)"
                    >
                      Delete
                    </button>
                    <button
                      class="btn btn-dark"
                      @click="editCustomer(customer)"
                    >
                      Edit
                    </button>
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
      customer: {
        id: "",
        name: "",
        surname: "",
        email: "",
      },
      customer_id: "",
      edit: false,
      btnText: "Create",
    };
  },

  created() {
    this.fetchCustomers();
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
    deleteCustomer(customerId) {
      if (confirm("Are you sure?")) {
        fetch(`api/customers/${customerId}`, { method: "delete" })
          .then((res) => {
            alert("Customer Deleted!");
            this.fetchCustomers();
          })
          .catch((err) => console.log(err));
      }
    },
    addCustomer() {
      if (this.edit) {
        //update customer
        fetch(`api/customers/${this.customer.id}`, {
          method: "put",
          body: JSON.stringify(this.customer),
          headers: {
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((res) => {
            this.customer.id = "";
            this.customer.name = "";
            this.customer.surname = "";
            this.customer.email = "";
            this.btnText = 'Create';
            alert("Customer Updated!");
            this.fetchCustomers();
          })
          .catch((err) => console.log(err));
      } else {
        //create new customer
        fetch(`api/customers`, {
          method: "post",
          body: JSON.stringify(this.customer),
          headers: {
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((res) => {
            this.customer.id = "";
            this.customer.name = "";
            this.customer.surname = "";
            this.customer.email = "";
            alert("Customer Added!");
            this.fetchCustomers();
          })
          .catch((err) => console.log(err));
      }
    },
    editCustomer(customer) {
      this.customer_id = customer.id;
      this.customer = customer;
      this.edit = true;
      this.btnText = "Update";
    },
  },
};
</script>
