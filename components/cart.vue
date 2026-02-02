<template>
  <div>
    {{ products }}
  </div>
</template>
<script>
import { mapState, mapGetters } from 'vuex'
export default {
  data() {
    return {
      products: [],
    }
  },
  methods:{
    productQuery(newItem){
        this.products.push(newItem)
    }
  },
  computed: {
    ...mapGetters({
      fullName: 'cart/fullName',
    }),
    ...mapState({
      productID: (store) => store.cart.productID,
    }),
  },
  watch: {
    productID: {
      handler: function (newItem) {
        console.log(newItem)
        this.productQuery(newItem)
      },
      deep: true,
    },
  },
}
</script>
