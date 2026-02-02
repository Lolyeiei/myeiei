<template>
  <div class="login-page">
    <div class="card">
      <h2>เข้าสู่ระบบ</h2>

      <form @submit.prevent="login">
        <input
          v-model="email"
          type="email"
          placeholder="อีเมล"
        />

        <input
          v-model="password"
          type="password"
          placeholder="รหัสผ่าน"
        />

        <button type="submit">เข้าสู่ระบบ</button>
      </form>

      <p v-if="error" class="error">{{ error }}</p>

      <p class="register-link">
        ยังไม่มีบัญชี?
        <nuxt-link to="/register">สมัครสมาชิก</nuxt-link>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: '',
      password: '',
      error: ''
    }
  },
  methods: {
    async login() {
      this.error = ''

      const fd = new FormData()
      fd.append('email', this.email)
      fd.append('password', this.password)

      const res = await this.$axios.post('login.php', fd)

      // 🔴 ตรงนี้คือหัวใจ (ไม่ซับซ้อน)
      if (res.data.status === 'success') {

        // เก็บ user
        localStorage.setItem(
          'user',
          JSON.stringify(res.data.user)
        )

        // ไปหน้า orders แน่นอน
        this.$router.push('/')

      } else {
        this.error = res.data.message
      }
    }
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #2ecc71, #3498db);
  display: flex;
  justify-content: center;
  align-items: center;
}

.card {
  background: white;
  width: 360px;
  padding: 40px;
  border-radius: 18px;
}

input, button {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
}

button {
  background: #3498db;
  color: white;
  border: none;
  cursor: pointer;
}

.error {
  color: red;
  text-align: center;
}

.register-link {
  text-align: center;
}
</style>
