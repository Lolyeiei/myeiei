<template>
  <!-- รอให้มี user ก่อน -->
  <div v-if="user && user.role" class="orders-page">

    <!-- HEADER -->
    <div class="topbar">
      <h2>
        {{ user.role === 'customer'
          ? 'สั่งให้ร้านมารับผ้าที่บ้าน'
          : 'รายการรับผ้าจากลูกค้า'
        }}
      </h2>
      <button class="logout" @click="logout">ออกจากระบบ</button>
    </div>

    <!-- ================= ลูกค้า ================= -->
    <div v-if="user.role === 'customer'" class="customer-section">

      <!-- ฟอร์มเรียกรถมารับผ้า -->
      <div class="create-box">
        <h3>กรอกข้อมูลรับผ้า</h3>

        <input v-model="newType" placeholder="ประเภทผ้า (เช่น เสื้อผ้า)" />
        <input v-model="newQty" type="number" placeholder="จำนวน" />
        <input v-model="newAddress" placeholder="ที่อยู่รับผ้า" />
        <input v-model="newPhone" placeholder="เบอร์โทรศัพท์" />

        <button @click="createOrder">เรียกรถมารับผ้า</button>
      </div>

      <!-- รายการของลูกค้า -->
      <h3>รายการที่คุณสั่ง</h3>
    </div>

    <!-- ================= ร้าน ================= -->
    <div v-if="user.role === 'shop'" class="shop-section">
      <h3>รายการลูกค้าที่ต้องไปรับผ้า</h3>
    </div>

    <!-- ================= ตาราง Orders ================= -->
    <table>
      <thead>
        <tr>
          <th>ออเดอร์ที่</th>
          <th>ประเภทผ้า</th>
          <th>จำนวน</th>
          <th>ที่อยู่รับผ้า</th>
          <th>เบอร์โทร</th>
          <th>สถานะ</th>
          <th v-if="user.role === 'customer'">แก้ไข</th>
          <th v-if="user.role === 'shop'">อัปเดตสถานะ</th>
          <th v-if="user.role === 'shop'">ดูสลิป</th>
          <th v-if="user.role === 'customer'">ยกเลิก</th>
          <th v-if="user.role === 'customer'">ชำระเงิน</th>

        </tr>
      </thead>

      <tbody>
        <tr v-for="(o, index) in orders" :key="o.id">
          <td>{{ index + 1 }}</td>
          <td>{{ o.laundry_type }}</td>
          <td>{{ o.quantity }}</td>
          <td>{{ o.address }}</td>
          <td>{{ o.phone }}</td>

          <td>
            <span :class="'status ' + o.status">
              {{ o.status }}
            </span>
          </td>


          <!-- ร้านเปลี่ยนสถานะ -->
          <td v-if="user.role === 'shop'">
            <select v-model="o.status" @change="updateStatus(o)">
              <option value="pending">รอรับผ้า</option>
              <option value="picked_up">รับผ้าแล้ว</option>
              <option value="washing">กำลังซัก</option>
              <option value="done">เสร็จแล้ว</option>
            </select>
          </td>
<td v-if="user.role === 'shop'">
  <button
    v-if="o.payment_slip"
    class="view-slip-btn"
    @click="openSlip(o.payment_slip)"
  >
    ดูสลิป
  </button>
  <span v-else>-</span>
  <td v-if="user.role === 'customer'">
  <button
    v-if="o.status === 'pending'"
    class="edit-btn"
    @click="openEdit(o)"
  >
    แก้ไข
  </button>
  <span v-else>-</span>
</td>

</td>
          <!-- ลูกค้ายกเลิก -->
          <td v-if="user.role === 'customer'">
            <button class="danger" @click="deleteOrder(o.id)">
              ยกเลิก
            </button>
          </td>
          <td v-if="user.role === 'customer'">
  <nuxt-link
    v-if="o.payment_status === 'unpaid'"
    :to="`/payment?order_id=${o.id}`"
    class="pay-btn"
  >
    ไปชำระเงิน
  </nuxt-link>

  <span v-else class="paid-text">
    ชำระแล้ว
  </span>
</td>

        </tr>
      </tbody>
    </table>
<!-- ================= Popup ดูสลิป ================= -->
<div v-if="showSlipModal" class="modal-backdrop" @click.self="closeSlip">
  <div class="modal">
    <h3>สลิปการชำระเงิน</h3>

    <img
      :src="slipUrl(currentSlip)"
      alt="payment slip"
    />

    <button class="close-btn" @click="closeSlip">
      ปิด
    </button>
  </div>
</div>
<!-- ================= Popup แก้ไข Order ================= -->
<div v-if="showEditModal" class="modal-backdrop" @click.self="closeEdit">
  <div class="modal">
    <h3>แก้ไขข้อมูลรับผ้า</h3>

    <input v-model="editOrder.laundry_type" placeholder="ประเภทผ้า" />
    <input v-model="editOrder.quantity" type="number" placeholder="จำนวน" />
    <input v-model="editOrder.address" placeholder="ที่อยู่รับผ้า" />
    <input v-model="editOrder.phone" placeholder="เบอร์โทร" />

    <div class="modal-actions">
      <button class="save-btn" @click="saveEdit">บันทึก</button>
      <button class="close-btn" @click="closeEdit">ยกเลิก</button>
    </div>
  </div>
</div>

  </div>
</template>

<script>
export default {
  data() {
  return {
    user: {},
    orders: [],

    // ฟอร์มลูกค้า
    newType: '',
    newQty: '',
    newAddress: '',
    newPhone: '',

    // ⭐ popup ดูสลิป
    showSlipModal: false,
    currentSlip: null,
showEditModal: false,
editOrder: {
  id: null,
  laundry_type: '',
  quantity: '',
  address: '',
  phone: ''
}    
  
}
}
,

  mounted() {
    const u = localStorage.getItem('user')
    if (!u) {
      this.$router.push('/login')
      return
    }

    this.user = JSON.parse(u)
    this.loadOrders()
  },

  methods: {
    // โหลด orders
    async loadOrders() {
      const res = await this.$axios.get('orders_list.php', {
        params: {
          role: this.user.role,
          user_id: this.user.id
        }
      })
      this.orders = res.data
    },
    openSlip(filename) {
  this.currentSlip = filename
  this.showSlipModal = true
},

closeSlip() {
  this.showSlipModal = false
  this.currentSlip = null
},

slipUrl(name) {
  return 'http://localhost/api/uploads/' + name
},
openEdit(order) {
  this.editOrder = { ...order }
  this.showEditModal = true
},

closeEdit() {
  this.showEditModal = false
},

async saveEdit() {
  const fd = new FormData()
  fd.append('id', this.editOrder.id)
  fd.append('laundry_type', this.editOrder.laundry_type)
  fd.append('quantity', this.editOrder.quantity)
  fd.append('address', this.editOrder.address)
  fd.append('phone', this.editOrder.phone)

  await this.$axios.post('order_update_customer.php', fd)

  this.showEditModal = false
  this.loadOrders()
}

,


    // ลูกค้าสร้าง order
    async createOrder() {
      if (!this.newType || !this.newQty || !this.newAddress || !this.newPhone) {
        alert('กรุณากรอกข้อมูลให้ครบ')
        return
      }

      const fd = new FormData()
      fd.append('user_id', this.user.id)
      fd.append('laundry_type', this.newType)
      fd.append('quantity', this.newQty)
      fd.append('address', this.newAddress)
      fd.append('phone', this.newPhone)

      await this.$axios.post('order_create.php', fd)

      this.newType = ''
      this.newQty = ''
      this.newAddress = ''
      this.newPhone = ''

      this.loadOrders()
    },

    // ร้านอัปเดตสถานะ
    async updateStatus(order) {
      const fd = new FormData()
      fd.append('id', order.id)
      fd.append('status', order.status)

      await this.$axios.post('order_update.php', fd)
    },

    // ลูกค้ายกเลิก
    async deleteOrder(id) {
      if (!confirm('ต้องการยกเลิกรายการนี้หรือไม่?')) return

      const fd = new FormData()
      fd.append('id', id)

      await this.$axios.post('order_delete.php', fd)
      this.loadOrders()
    },
    

    logout() {
      localStorage.removeItem('user')
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
    /* ===== Popup ===== */
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.modal {
  background: white;
  padding: 20px;
  border-radius: 12px;
  max-width: 90%;
  max-height: 90%;
  text-align: center;
}

.modal img {
  max-width: 100%;
  max-height: 70vh;
  border-radius: 8px;
  margin-bottom: 15px;
}

.view-slip-btn {
  padding: 6px 12px;
  background: #3498db;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.close-btn {
  padding: 8px 16px;
  background: #e74c3c;
  color: white;
  border: none;
  border-radius: 6px;
}

.orders-page {
  padding: 40px;
  font-family: 'Segoe UI', Tahoma, sans-serif;
}

.topbar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 30px;
}

.logout {
  background: #e74c3c;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
}

.create-box {
  background: #f9f9f9;
  padding: 20px;
  margin-bottom: 30px;
  border-radius: 12px;
}

input {
  padding: 10px;
  margin: 5px 5px 10px 0;
  width: 100%;
  max-width: 400px;
}

button {
  padding: 10px 16px;
  border-radius: 8px;
  border: none;
  background: #3498db;
  color: white;
}

button.danger {
  background: #e74c3c;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 12px;
  border-bottom: 1px solid #ddd;
  text-align: center;    /* จัดกลางแนวนอน */
  vertical-align: middle;/* จัดกลางแนวตั้ง */
  word-wrap: break-word; /* กันข้อความดันตาราง */
}

.status {
  padding: 6px 12px;
  border-radius: 6px;
  color: white;
}
.pay-btn {
  display: inline-block;
  padding: 8px 14px;
  background: #27ae60;
  color: white;
  border-radius: 6px;
  text-decoration: none;
  font-size: 14px;
}

.pay-btn:hover {
  background: #1e8449;
}

.paid-text {
  color: green;
  font-weight: bold;
}
.edit-btn {
  padding: 6px 12px;
  background: #f39c12;
  color: white;
  border: none;
  border-radius: 6px;
}

.save-btn {
  background: #27ae60;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
}

.modal input {
  width: 100%;
  padding: 10px;
  margin-bottom: 10px;
}

.modal-actions {
  display: flex;
  justify-content: space-between;
}



.status.pending { background: gray; }
.status.picked_up { background: #2980b9; }
.status.washing { background: orange; }
.status.done { background: green; }
</style>
