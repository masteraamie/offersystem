<template>
  <div class="container mt-4 pt-4">
    <div class="row justify-content-center mb-3">
      <div class="col-12">
        <div class="card">
          <div class="card-header">Create Vehicle</div>

          <div class="card-body">
            <form @submit.prevent="addVehicle()">
              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right"
                  >Model</label
                >
                <div class="col-md-6">
                  <input
                    id="model"
                    type="text"
                    class="form-control"
                    name="model"
                    v-model="vehicle.model"
                    required
                    autofocus
                  />
                </div>
              </div>

              <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right"
                  >Price</label
                >
                <div class="col-md-6">
                  <input
                    id="price"
                    type="text"
                    class="form-control"
                    name="price"
                    v-model="vehicle.price"
                    required
                    autofocus
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
          <div class="card-header">Vehicles</div>
          <div class="card-body p-3">
            <div class="row">
              <div
                class="col-4"
                v-for="vehicle in vehicles"
                v-bind:key="vehicle.id"
              >
                <div class="card">
                  <div class="card-header">Vehicle Id:  <span class="btn btn-primary btn-sm">#{{ vehicle.id }}</span></div>
                  <div class="card-body p-3">
                    <p>Model: {{ vehicle.model }} </p>
                    <p>Price: {{ vehicle.price }}</p>
                    <button
                      class="btn btn-danger"
                      @click="deleteVehicle(vehicle.id)"
                    >
                      Delete
                    </button>
                    <button
                      class="btn btn-dark"
                      @click="editVehicle(vehicle)"
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
      vehicles: [],
      vehicle: {
        id: "",
        model: "",
        price: "",
      },
      vehicle_id: "",
      edit: false,
      btnText: "Create",
    };
  },

  created() {
    this.fetchVehicles();
  },

  methods: {
    fetchVehicles() {
      fetch("api/vehicles")
        .then((res) => res.json())
        .then((data) => {
          this.vehicles = data;
        })
        .catch((err) => console.log(err));
    },
    deleteVehicle(vehicleId) {
      if (confirm("Are you sure?")) {
        fetch(`api/vehicles/${vehicleId}`, { method: "delete" })
          .then((res) => {
            alert("Vehicle Deleted!");
            this.fetchVehicles();
          })
          .catch((err) => console.log(err));
      }
    },
    addVehicle() {
      if (this.edit) {
        //update vehicle
        fetch(`api/vehicles/${this.vehicle.id}`, {
          method: "put",
          body: JSON.stringify(this.vehicle),
          headers: {
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((res) => {
            this.vehicle.id = "";
            this.vehicle.model = "";
            this.vehicle.price = "";
            this.btnText = 'Create';
            alert("Vehicle Updated!");
            this.edit = false;
            this.fetchVehicles();
          })
          .catch((err) => console.log(err));
      } else {
        //create new vehicle
        fetch(`api/vehicles`, {
          method: "post",
          body: JSON.stringify(this.vehicle),
          headers: {
            "content-type": "application/json",
          },
        })
          .then((res) => res.json())
          .then((res) => {
            this.vehicle.id = "";
            this.vehicle.model = "";
            this.vehicle.price = "";
            alert("Vehicle Added!");
            this.fetchVehicles();
          })
          .catch((err) => console.log(err));
      }
    },
    editVehicle(vehicle) {
      this.vehicle_id = vehicle.id;
      this.vehicle = vehicle;
      this.edit = true;
      this.btnText = "Update";
    },
  },
};
</script>
