import { check } from 'k6'
import { generateToken, getProdukDetail } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getProdukDetail( { token: data.token })
  check(res, {
    'farmasi/get-produkdetail is status 200': r => r.status === 200
  })
}
