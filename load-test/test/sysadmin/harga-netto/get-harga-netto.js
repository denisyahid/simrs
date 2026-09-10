import { check } from 'k6'
import { generateToken, getHargaNetto } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getHargaNetto({}, { token: data.token })
  check(res, {
    'get-harga-netto is status 200': r => r.status === 200
  })
}
