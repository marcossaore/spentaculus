<template>
  <q-page class="row items-center justify-evenly">
    <div class="q-pa-md">
      <q-page class="q-page-login">
        <q-card class="q-card-login">
          <q-card-section class="q-pa-md">
            <div class="column items-center q-mb-lg">
              <p class="text-h1 text-secondary">Spentaculus</p>
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
                <circle cx="50" cy="50" r="45" fill="url(#grad)" />
                <rect x="25" y="25" width="50" height="50" fill="#2a9d8f" />
                <defs>
                  <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#2a9d8f;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#264653;stop-opacity:1" />
                  </linearGradient>
                </defs>
              </svg>
            </div>
            <q-form @submit.prevent="login">
              <q-input v-model="email" label="Email" outlined class="q-mb-lg" />
              <q-input v-model="password" label="Senha" type="password" outlined class="q-mb-lg"/>
              <div class="column items-center q-gutter-md q-pr-md">
                <q-btn type="submit" label="Entrar" color="primary" class="login-btn" :ripple="{ center: true }" />
                <q-btn outline label="Criar Conta" color="primary" class="login-btn" :ripple="{ center: true }"   @click="goToSignUp"/>
              </div>
            </q-form>
          </q-card-section>
        </q-card>
      </q-page>
  </div>
  </q-page>
</template>

<script lang="ts">
import axios from 'axios'
import { defineComponent } from 'vue'

export default defineComponent({
  data () {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    async login () {
      try {
        const response = await axios.post('/api/auth/signin', {
          email: this.email,
          password: this.password
        })

        if (response.request.status === 200) {
          localStorage.setItem('token', response.data.token)
          this.$router.push('/home')
        } else {
          const errorData = response.data
          this.$q.notify({
            color: 'negative',
            position: 'top',
            message: errorData.message ? errorData.message.split('(')[0] : errorData.error
          })
        }
      } catch (error) {
        const errorData = error.response.data
        this.$q.notify({
          color: 'negative',
          position: 'top',
          message: errorData.message ? errorData.message.split('(')[0] : errorData.error
        })
      }
    },
    goToSignUp () {
      this.$router.push('/signup')
    }
  },
  name: 'IndexPage',
  created () {
    const token = localStorage.getItem('token')
    if (token) {
      this.$router.push('/home')
    }
  }
})
</script>

<style scoped>
.q-card-login {
  max-width: 60rem;
  margin: auto;
}

.login-btn {
  width: 100%;
  height: 50px;
}

.q-page-login {
  display: flex;
  align-items: center;
  justify-content: center;
  height: vh;
}
</style>
