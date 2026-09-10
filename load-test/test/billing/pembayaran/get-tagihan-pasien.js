import { check } from 'k6'
import { generateToken, getTagihanPasien } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getTagihanPasien({ token: data.token })
  check(res, {
    'dashboard/kasir/list-tagihan-pasien is status 200': r => r.status === 200
  })
}
