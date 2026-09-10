import { generateToken } from '../../src/api.js'
import getDaftarKirimGizi from './get-daftar-kirim-gizi.js'
import getDaftarOrderGizi from './get-daftar-order-gizi.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDaftarOrderGizi({ token: data.token })
  getDaftarKirimGizi({ token: data.token })
}
