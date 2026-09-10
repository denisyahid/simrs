import { check } from 'k6'
import { generateToken, getDaftarPasienRegistrasi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDaftarPasienRegistrasi({}, { token: data.token })
  check(res, {
    'get-dashboard-registrasi is status 200': r => r.status === 200
  })
}
