<template>
  <div class="index-page">

    <!-- ================= ยังไม่ login ================= -->
    <div v-if="!user" class="guest">
      <h1>ระบบซักอบรีดออนไลน์</h1>
      <p>สั่งให้ร้านมารับผ้าที่บ้าน สะดวก รวดเร็ว</p>

      <div class="btn-group">
        <nuxt-link to="/login" class="btn primary">เข้าสู่ระบบ</nuxt-link>
        <nuxt-link to="/register" class="btn">สมัครสมาชิก</nuxt-link>
      </div>
    </div>

    <!-- ================= ลูกค้า ================= -->
    <div v-else-if="user.role === 'customer'" class="customer">
      <h1>ยินดีต้อนรับ ลูกค้า</h1>
      <p>คุณสามารถสั่งให้ร้านมารับผ้าที่บ้านได้</p>

      <div class="btn-group">
        <nuxt-link to="/orders" class="btn primary">
          สั่งให้มารับผ้า
        </nuxt-link>

        <nuxt-link to="/payment" class="btn">
          ชำระเงิน
        </nuxt-link>
      </div>
    </div>

    <!-- ================= ร้าน ================= -->
    <div v-else-if="user.role === 'shop'" class="shop">
      <h1>แดชบอร์ดร้านซักอบรีด</h1>
      <p>จัดการรายการรับผ้าจากลูกค้า</p>

      <div class="btn-group">
        <nuxt-link to="/orders" class="btn primary">
          ดูรายการรับผ้า
        </nuxt-link>

        <nuxt-link to="/payment" class="btn">
          ตรวจสอบการชำระเงิน
        </nuxt-link>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      user: null
    }
  },

  mounted() {
    const u = localStorage.getItem('user')
    if (u) {
      this.user = JSON.parse(u)
    }
  }
}
</script>

<style scoped>
.index-page {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  font-family: 'Segoe UI', Tahoma, sans-serif;
  background: linear-gradient(135deg, #3498db, #2ecc71);
  color: white;
  text-align: center;
}

h1 {
  font-size: 36px;
  margin-bottom: 10px;
}

p {
  margin-bottom: 30px;
  font-size: 18px;
}

.btn-group {
  display: flex;
  gap: 15px;
  justify-content: center;
}

.btn {
  padding: 14px 22px;
  border-radius: 10px;
  background: white;
  color: #333;
  text-decoration: none;
  font-weight: bold;
}

.btn.primary {
  background: #27ae60;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}
</style>
