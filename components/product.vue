<template>
  <div>
    <v-btn color="info" @click="productQuery">Load Product Data</v-btn>
    <v-btn color="info" @click="openInsertDialog">Insert Data</v-btn>
    <v-data-table :items="product" :headers="headers">
      <template v-slot:item.actions="{ item }">
        <v-btn small @click="openEditDialog(item)" color="warning">
          <v-icon>mdi-pencil</v-icon>
        </v-btn>
        <v-btn small @click="deleteProduct(item)" color="error">
          <v-icon>mdi-delete</v-icon>
        </v-btn> 
        <v-btn small color="primary" @click="addToCart(item)">
          Add to Cart
        </v-btn>
      </template>
    </v-data-table>


    <v-dialog
      v-model="productDialog"
      width="500"
    >
      <v-card>
        <v-card-title class="text-h5 grey lighten-2">
          Product
        </v-card-title>

        <v-card-text>
          <v-form ref="form">
            <v-text-field v-model="productForm.products_name" label="Name" required></v-text-field>
            <v-text-field v-model="productForm.products_price" label="Price" required type="number"></v-text-field>
            <v-textarea v-model="productForm.products_detail" label="Detail"></v-textarea>
          </v-form>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" text @click="productDialog = false">Cancel</v-btn>
          <v-btn color="primary" @click="saveProduct">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  data() {
    return {
      headers: [
        { text: 'Product ID', value: 'products_id' },
        { text: 'Name', value: 'products_name' },
        { text: 'Price', value: 'products_price' },
        { text: 'Detail', value: 'products_detail' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      product: [],
      productDialog:false,
      isEdit:false,
      productForm: {
        products_id: null,
        products_name: '',
        products_price: '',
        products_detail: '',
      },
    }
  },
  mounted(){
    this.productQuery()
  },
  methods: {
   async productQuery(){
      let result = await this.$axios.post('http://localhost:8080/myfirst_app_api/products_select.php')
      console.log(result.data)
      this.product = result.data
    },

    openInsertDialog(){
      this.isEdit = false
      this.productForm = { products_id: null, products_name: '', products_price: '', products_detail: '' }
      this.productDialog = true
    },

    openEditDialog(item){
      this.isEdit = true
      this.productForm = { ...item }
      this.productDialog = true
    },

    async saveProduct(){
      try{
        if(this.isEdit){
          await this.$axios.post('http://localhost:8080/myfirst_app_api/products_update.php', this.productForm)
        } else {
          await this.$axios.post('http://localhost:8080/myfirst_app_api/products_insert.php', this.productForm)
        }
        this.productDialog = false
        this.productQuery()
      }catch(e){
        console.error(e)
      }
    },

    async deleteProduct(item){
      if(!confirm('Delete this product?')) return
      try{
        await this.$axios.post('http://localhost:8080/myfirst_app_api/products_delete.php', { products_id: item.products_id })
        this.productQuery()
      }catch(e){
        console.error(e)
      }
    },

    ...mapActions({
      setName: 'cart/setName',
    }),
    addToCart(item) {
      this.setName({ products_id: item.products_id })
    },
  },
}
</script>
