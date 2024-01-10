<template>
  <q-page class="row items-center justify-evenly">
    <div class="q-pa-md">
      <q-page class="q-page-signup">
        <q-card class="q-card-signup">
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
            <q-form @submit.prevent="signup">
              <q-input v-model="name" label="Name" outlined class="q-mb-lg"/>
              <q-input v-model="email" label="Email" outlined class="q-mb-lg" />
              <q-input v-model="password" label="Senha" type="password" outlined class="q-mb-lg"/>
              <div class="column items-center q-gutter-md q-pr-md">
               <q-btn type="submit" label="Criar" color="primary" class="signup-btn" :ripple="{ center: true }" />
               <q-btn outline label="Voltar" color="primary" class="signup-btn" :ripple="{ center: true }"   @click="back"/>
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
      name: '',
      email: '',
      password: ''
    }
  },
  methods: {
    async signup () {
      try {
        const response = await axios.post('/api/auth/signup', {
          name: this.name,
          email: this.email,
          password: this.password
        })

        if (response.request.status === 200) {
          this.$q.notify({
            color: 'positive',
            position: 'top',
            message: 'Conta criada com sucesso, Agora você pode logar!'
          })

          this.$router.push('/')
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
    back () {
      this.$router.push('/')
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
.q-card-signup {
  max-width: 60rem;
  margin: auto;
}

.signup-btn {
  width: 100%;
  height: 50px;
}

.q-page-signup {
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
