import { check } from 'k6'
import { generateToken, getPembayaranTagihan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPembayaranTagihan({ token: data.token })
  check(res, {
    'kasir/pembayaran-tagihan is status 200': r => r.status === 200
  })
}
