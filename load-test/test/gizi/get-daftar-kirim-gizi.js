import { check } from 'k6'
import { generateToken, getDaftarKirimGizi } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDaftarKirimGizi({}, { token: data.token })
  check(res, {
    'get-daftar-kirim-gizi is status 200': r => r.status === 200
  })
}
