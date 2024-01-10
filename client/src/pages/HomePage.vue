<template>
   <q-header elevated>
      <q-toolbar>
        <q-toolbar-title>
          Home
        </q-toolbar-title>
        <q-btn
          flat
          round
          dense
          icon="exit_to_app"
          @click="logout"
        />
      </q-toolbar>
    </q-header>
    <q-page>
      <div v-if="spents.length === 0" class="q-ma-md text-h6 text-center">
        Nenhuma despesa cadastrada
      </div>
      <q-list bordered separator>
        <q-item v-for="spent in spents" :key="spent.id" class="row items-center">
          <q-item-section>
            <q-item-label>
              <p class="text-body1">{{ spent.description }}</p>
            </q-item-label>
        </q-item-section>
        <q-item-section>
            <q-item-label caption>
              <div class="row items-center q-gutter-sm">
                <span class="text-body1">{{ formatCurrency(spent.value) }}</span>
              </div>
            </q-item-label>
            <q-item-label>
              <span class="text-caption"> {{ formatDate(spent.spentAt) }}</span>
            </q-item-label>
        </q-item-section>
        <q-item-section side>
          <div class="row items-center">
            <q-btn
              icon="edit"
              round
              color="primary"
              size="md"
              @click="editSpent(spent)"
              class="q-mr-sm"
            />
            <q-btn
              icon="delete"
              round
              color="negative"
              size="md"
              @click="deleteSpent(spent.id)"
            />
          </div>
          </q-item-section>
        </q-item>
      </q-list>
      <div class="q-mt-md">
        <q-dialog v-model="addSpentDialog">
          <q-card>
            <q-card-section>
              <q-form @submit.prevent="addSpent">
                <q-input v-model="newSpent.description" label="Descrição" outlined class="q-mb-lg" />
                <q-input v-model="newSpent.value" label="Valor" outlined class="q-mb-lg" :mask="'R$ ##,##'" />
                <q-input
                  v-model="newSpent.spentAt"
                  label="Data do gasto"
                  type="datetime-local"
                  outlined
                  class="q-mb-lg"
                />
                <q-btn type="submit" label="Adicionar" color="primary" class="login-btn" :ripple="{ center: true }" />
              </q-form>
            </q-card-section>
          </q-card>
        </q-dialog>
        <div class="absolute-bottom-right q-ma-md">
          <q-fab
            icon="add"
            color="primary"
            size="xl"
            @click="addSpentDialog = true"
          />
        </div>
      </div>
    </q-page>
</template>

<script lang="ts">
import axios from 'axios'
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'HomePage',
  data () {
    return {
      spents: [],
      addSpentDialog: false,
      newSpent: {
        description: '',
        value: '',
        spentAt: ''
      }
    }
  },
  created () {
    this.fetchSpents()
  },
  methods: {
    async fetchSpents () {
      try {
        const response = await axios.get('/api/spents', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        this.spents = response.data.data
      } catch (error) {
        if (error.request.status === 401) {
          localStorage.clear()
          this.$router.push('/')
          this.$q.notify({
            color: 'negative',
            position: 'top',
            message: 'Por favor! Faça o login!'
          })
        }
      }
    },
    formatDate (dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false, timeZone: 'America/Sao_Paulo' }
      return new Date(dateString).toLocaleDateString('pt-BR', options)
    },
    async addSpent () {
      try {
        const spent = this.newSpent

        spent.value = parseInt(spent.value.replace(/[^0-9]/g, ''))

        if (spent.spentAt) {
          spent.spentAt = spent.spentAt.replace('T', ' ')
        }

        await axios.post('/api/spents', spent, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        this.fetchSpents()
        this.addSpentDialog = false

        this.$q.notify({
          color: 'positive',
          position: 'top',
          message: 'Gasto criado com sucesso!'
        })

        this.newSpent = {
          description: '',
          value: '',
          spentAt: ''
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
    async editSpent (spent) {
      this.$q.notify({
        color: 'negative',
        position: 'top',
        message: 'Não implmentado ainda!'
      })
    },
    async deleteSpent (spentId) {
      try {
        await axios.delete(`/api/spents/${spentId}`, {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
          }
        })
        this.spents = this.spents.filter(spent => spent.id !== spentId)
        this.$q.notify({
          color: 'negative',
          position: 'top',
          message: 'Despesa excluída com sucesso!'
        })
      } catch (error) {
        const errorData = error.response.data
        this.$q.notify({
          color: 'negative',
          position: 'top',
          message: errorData.message ? errorData.message.split('(')[0] : errorData.error
        })
      }
    },
    formatCurrency (amount) {
      const dollars = amount / 100
      return `R$ ${dollars.toFixed(2).replace('.', ',')}`
    },
    logout () {
      localStorage.clear()
      this.$router.push('/')
    }
  }
})
</script>

<style>

</style>
