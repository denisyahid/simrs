import { check } from 'k6'
import { generateToken, getRegistrasiPasien } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getRegistrasiPasien({ token: data.token })
  check(res, {
    'laboratorium/get-regis-pasien is status 200': r => r.status === 200
  })
}
