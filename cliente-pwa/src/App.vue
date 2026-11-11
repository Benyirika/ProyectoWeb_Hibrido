<script setup>
import { ref, onMounted } from 'vue'
import { Network } from '@capacitor/network'
import { Camera, CameraResultType } from '@capacitor/camera'

const API = import.meta.env.VITE_API_URL
const juegos = ref([])
const estado = ref('Cargando catálogo...')
const actualizado = ref(null)

const enLinea = ref(true)
const foto = ref(null)

async function cargar() {
  try {
    const res = await fetch(`${API}/videojuegos`)
    if (!res.ok) throw new Error(`HTTP ${res.status}`)
    const json = await res.json()
    juegos.value = json.data ?? json
    actualizado.value = new Date().toLocaleString()
    estado.value = ''
  } catch (e) {
    estado.value = 'No se pudo conectar con la API de videojuegos'
  }
}

async function tomarFoto() {
  try {
    const img = await Camera.getPhoto({
      resultType: CameraResultType.Uri,
      quality: 80
    })
    foto.value = img.webPath
  } catch (e) {
    console.log('Cancelado o error al tomar foto', e)
  }
}

onMounted(async () => {
  cargar()

  enLinea.value = (await Network.getStatus()).connected
  Network.addListener('networkStatusChange', (s) => {
    enLinea.value = s.connected
  })
})
</script>

<template>
  <div style="max-width: 650px; margin: 40px auto; padding: 0 20px; font-family: system-ui, sans-serif;">
    <header style="margin-bottom: 20px; text-align: center;">
      <h1 style="font-size: 2rem; color: #0f172a; display: flex; align-items: center; justify-content: center; gap: 10px;">
        <span>🎮</span> Tienda de Videojuegos
      </h1>
      <p style="color: #64748b; margin-top: 5px;">Catálogo actualizado desde la API Backend</p>
    </header>

    <!-- Aviso de Conexión (Paso 9) -->
    <p v-if="!enLinea" style="background: #fef2f2; border-left: 5px solid #ef4444; color: #991b1b; padding: 12px; border-radius: 6px; font-weight: 600; text-align: center;">
      Sin conexión. Se muestran los últimos datos guardados.
    </p>

    <!-- Botón y Vista Previa de Cámara (Paso 9) -->
    <div style="text-align: center; margin: 20px 0;">
      <button @click="tomarFoto" style="background: #2563eb; color: white; border: none; padding: 10px 18px; font-size: 1rem; border-radius: 8px; cursor: pointer; font-weight: 600;">
        📷 Tomar foto
      </button>
      
      <div v-if="foto" style="margin-top: 15px;">
        <img :src="foto" width="240" alt="Foto tomada" style="border-radius: 10px; border: 3px solid #2563eb; box-shadow: 0 4px 6px rgba(0,0,0,0.1);" />
      </div>
    </div>

    <!-- Catálogo del Paso 4 -->
    <p v-if="estado" style="text-align: center; color: #64748b; background: white; padding: 15px; border-radius: 8px;">
      {{ estado }}
    </p>

    <div v-if="juegos.length > 0" style="display: flex; flex-direction: column; gap: 12px;">
      <div v-for="juego in juegos" :key="juego.id" style="background: white; padding: 16px 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; border-left: 5px solid #2563eb;">
        <div>
          <h3 style="margin: 0 0 4px 0; font-size: 1.1rem; color: #1e293b;">{{ juego.nombre }}</h3>
          <span style="font-size: 0.875rem; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 4px;">{{ juego.plataforma }}</span>
        </div>
        <div style="font-weight: 700; font-size: 1.2rem; color: #16a34a;">
          ${{ juego.precio }}
        </div>
      </div>
    </div>

    <small v-if="actualizado" style="display: block; text-align: center; margin-top: 25px; color: #94a3b8;">
      Última actualización: {{ actualizado }}
    </small>
  </div>
</template>