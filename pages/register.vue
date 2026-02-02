<template>
  <div class="register-page">
    <div class="card">
      <h2>สมัครสมาชิก</h2>

      <form @submit.prevent="register" enctype="multipart/form-data">
        <input v-model="name" placeholder="ชื่อผู้ใช้" required />
        <input v-model="email" type="email" placeholder="อีเมล" required />
        <input v-model="password" type="password" placeholder="รหัสผ่าน" required />

        <select v-model="role" required>
          <option value="">เลือกประเภทผู้ใช้</option>
          <option value="customer">ลูกค้า</option>
          <option value="shop">ร้านซักอบรีด</option>
        </select>

        <input type="file" @change="handleFile" />

        <button type="submit">สมัครสมาชิก</button>
      </form>

      <p v-if="error" class="error">{{ error }}</p>
      <p class="login-link">
        มีบัญชีแล้ว?
        <nuxt-link to="/login">เข้าสู่ระบบ</nuxt-link>
      </p>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      role: '',
      image: null,
      error: ''
    }
  },
  methods: {
    handleFile(e) {
      this.image = e.target.files[0]
    },
    async register() {
      this.error = ''
      const fd = new FormData()
      fd.append('name', this.name)
      fd.append('email', this.email)
      fd.append('password', this.password)
      fd.append('role', this.role)
      if (this.image) fd.append('image', this.image)

     try {
  const res = await axios.post(
    'http://localhost/api/register.php',
    fd
  )
  console.log('RESPONSE =', res.data)

  if (res.data.status === 'success') {
    this.$router.push('/login')
  } else {
    this.error = res.data.message
  }

} catch (err) {
  console.log('ERROR =', err)
  console.log('ERROR RESPONSE =', err.response)
  this.error = 'สมัครไม่สำเร็จ'
}

    }
  }
}
</script>

<style scoped>
.register-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #3498db, #2ecc71);
  display: flex;
  justify-content: center;
  align-items: center;
  font-family: 'Segoe UI', Tahoma, sans-serif;
}

.card {
  background: white;
  width: 360px;
  padding: 40px;
  border-radius: 18px;
  box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

h2 {
  text-align: center;
  margin-bottom: 30px;
}

input, select, button {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border-radius: 8px;
  border: 1px solid #ddd;
}

button {
  background: #3498db;
  color: white;
  border: none;
  font-weight: bold;
  cursor: pointer;
}

button:hover {
  background: #2980b9;
}

.error {
  color: red;
  text-align: center;
}

.login-link {
  text-align: center;
  margin-top: 15px;
}
</style>
