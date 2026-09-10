import { check } from 'k6'
import { generateToken, getDepositPasien } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDepositPasien({ token: data.token })
  check(res, {
    'kasir/daftar-deposit-pasien is status 200': r => r.status === 200
  })
}
