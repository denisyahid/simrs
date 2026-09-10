import { reactive } from 'vue'
import { io } from 'socket.io-client'

export const state = reactive({
  connected: false,
  fooEvents: [],
  barEvents: [],
})
const env = import.meta.env.VITE_SOCKET
const enabled =import.meta.env.VITE_SOCKET_ON
// "undefined" means the URL will be computed from the `window.location` object
const URL = enabled ? env : undefined
let mySocket = null

if (enabled == 'true') {
  mySocket = io(URL, {
    // 'websocket', '
    transports: ['polling'],
  })
} else {
  mySocket = {
    on: function () {},
    emit: function () {},
  }
}
console.log(mySocket)
export const socket = mySocket

socket.on('connect', () => {
  state.connected = true
})

socket.on('disconnect', () => {
  state.connected = false
})
