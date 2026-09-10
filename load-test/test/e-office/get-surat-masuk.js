import { check } from 'k6'
import { generateToken, getSuratMasuk } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getSuratMasuk({}, { token: data.token })
  check(res, {
    'get-surat-masuk is status 200': r => r.status === 200
  })
}
