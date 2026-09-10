import { check } from 'k6'
import { generateToken, getDaftarOrder } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDaftarOrder({ isNotVerif: true }, { token: data.token })
  check(res, {
    'get-daftar-order is status 200': r => r.status === 200
  })
}
