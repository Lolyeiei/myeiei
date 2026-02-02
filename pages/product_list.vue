<template>
  <div>
    <v-row>
      <v-col cols="12" md="9">
        <Product />
      </v-col>
      <v-col cols="12" md="3">
        ตะกร้าสินค้าของคุณ
        <Cart />
      </v-col>
    </v-row>
  </div>
</template>
<script>
import Cart from '~/components/cart.vue'
import Product from '~/components/product.vue'

export default {
  components: {
    Product,    
    Cart,
  },
  data() {
    return {}
  },
  methods: {},
}
</script>
<style></style>
