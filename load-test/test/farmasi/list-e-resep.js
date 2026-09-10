import { check } from 'k6'
import { generateToken, listEResep } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = listEResep({}, { token: data.token })
  check(res, {
    'get-daftar-order-resep is status 200': r => r.status === 200
  })
}
