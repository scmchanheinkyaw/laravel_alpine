<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel-Alpine</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    </head>
    <body>
        <div class="container-fluid mt-5" x-data="productCrud()">
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header text-light bg-dark">
                            Product Table
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead class="thead">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="product in products" :key="index">
                                        <tr>
                                            <td x-text="product.id"></td>
                                            <td x-text="product.name"></td>
                                            <td x-text="product.price"></td>
                                            <td>
                                                <button @click.prevent="editData(product)"
                                                    class="btn btn-info">Edit</button>
                                                <button @click.prevent="deleteData(product.id)"
                                                    class="btn btn-danger">Delete</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-header text-light bg-dark">
                            <span x-show="addMode">Create Product</span>
                            <span x-show="!addMode">Edit Product</span>
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="storeData" x-show="addMode">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input x-model="data.name" type="text" class="form-control" placeholder="Enter Name">
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input x-model="data.price" type="text" class="form-control" placeholder="Enter Price">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                            <form @submit.prevent="updateData" x-show="!addMode">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input x-model="data.name" type="text" class="form-control" placeholder="Enter Name">
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input x-model="data.price" type="text" class="form-control" placeholder="Enter Price">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-danger" @click.prevent="cancelEdit">Cancel Edit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function productCrud() {
                return {
                    addMode: true,
                    data: {
                        id: "",
                        name: "",
                        price: "",
                    },
                    products: [],

                    init() {
                        axios.get('/api/product')
                        .then((response) => {
                            this.products = response.data;
                        }).catch((err) => {
                           console.log(err);
                        });
                    },

                    storeData(){
                        axios.post('/api/product',this.data)
                        .then((response) => {
                            this.init();
                            this.data = { name: "" , price: ""};
                        });
                    },

                    editData(product) {
                        this.addMode = false
                        this.data.id = product.id
                        this.data.name = product.name
                        this.data.price = product.price
                    },
                    
                    updateData() {
                        axios.put(`/api/product/${this.data.id}`,this.data)
                        .then((response) => {
                            this.init();
                            this.data = { name: "" , price: ""};
                        })
                    },

                    deleteData(id) {
                        axios.delete(`/api/product/${id}`)
                        .then((response) => {
                            this.init();
                        })
                    },

                    cancelEdit(){
                        this.resetForm()
                    },

                    resetForm() {
                        this.addMode = true
                        this.data.id = ''
                        this.data.name = ''
                        this.data.price = ''
                    }
                }
            }
        </script>
    </body>
    <script defer src="https://unpkg.com/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
</html>
