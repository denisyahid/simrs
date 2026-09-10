import { check } from 'k6'
import { generateToken, getPetugasTindakan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPetugasTindakan({ token: data.token })
  check(res, {
    'kasir/billing/petugas-tindakan is status 200': r => r.status === 200
  })
}
