<template>
  <div v-if="user && order" class="payment-page">

    <div class="topbar">
      <h2>ชำระเงินสำหรับ Order #{{ order.id }}</h2>
      <button @click="$router.push('/orders')">กลับหน้า Orders</button>
    </div>

    <div class="order-box">
      <p><b>ประเภทผ้า:</b> {{ order.laundry_type }}</p>
      <p><b>จำนวน:</b> {{ order.quantity }}</p>
      <p><b>ที่อยู่รับผ้า:</b> {{ order.address }}</p>
      <p><b>เบอร์โทร:</b> {{ order.phone }}</p>
      <p>
        <b>สถานะการชำระเงิน:</b>
        <span :class="'pay ' + order.payment_status">
          {{ order.payment_status }}
        </span>
      </p>
    </div>

    <!-- ลูกค้าอัปโหลดสลิป -->
    <div v-if="user.role === 'customer' && order.payment_status === 'unpaid'">
      <input type="file" @change="uploadSlip" />
    </div>

    <!-- ดูสลิป -->
    <div v-if="order.payment_slip">
      <a :href="slipUrl(order.payment_slip)" target="_blank">
        ดูสลิปที่อัปโหลด
      </a>
    </div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {},
      order: null,
      orderId: null
    }
  },

  mounted() {
    const u = localStorage.getItem('user')
    if (!u) {
      this.$router.push('/login')
      return
    }
    this.user = JSON.parse(u)

    // 🔴 อ่าน order_id จาก URL
    this.orderId = this.$route.query.order_id
    if (!this.orderId) {
      alert('ไม่พบ order ที่ต้องชำระเงิน')
      this.$router.push('/orders')
      return
    }

    this.loadOrder()
  },

  methods: {
    async loadOrder() {
      const res = await this.$axios.get('payment_list.php', {
        params: {
          order_id: this.orderId,
          user_id: this.user.id
        }
      })
      this.order = res.data
    },

    async uploadSlip(e) {
      const file = e.target.files[0]
      if (!file) return

      const fd = new FormData()
      fd.append('order_id', this.orderId)
      fd.append('slip', file)

      await this.$axios.post('payment_upload.php', fd)
      alert('อัปโหลดสลิปเรียบร้อย')

      this.loadOrder()
    },

    slipUrl(name) {
      return 'http://localhost/api/uploads/' + name
    }
  }
}
</script>

<style scoped>
.payment-page {
  padding: 40px;
  font-family: 'Segoe UI', Tahoma, sans-serif;
}

.topbar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

.order-box {
  background: #f9f9f9;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 20px;
}

.pay.unpaid { color: red; }
.pay.paid { color: green; }
</style>
