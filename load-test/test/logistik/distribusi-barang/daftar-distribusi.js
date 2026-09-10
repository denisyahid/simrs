import { check } from 'k6'
import { daftarDistribusi, generateToken } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = daftarDistribusi({}, { token: data.token })
  check(res, {
    'get-daftar-distribusi is status 200': r => r.status === 200
  })
}
