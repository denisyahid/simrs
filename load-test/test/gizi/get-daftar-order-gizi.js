import { check } from 'k6'
import { generateToken, getDaftarOrderGizi } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDaftarOrderGizi({}, { token: data.token })
  check(res, {
    'get-daftar-order-gizi is status 200': r => r.status === 200
  })
}
