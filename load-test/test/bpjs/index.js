import { generateToken } from '../../src/api.js'
import cekPeserta from './cek-peserta.js'
import monitoringHistory from './monitoring-history.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  monitoringHistory({ token: data.token })
  cekPeserta({ token: data.token })
}
