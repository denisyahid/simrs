import { check } from 'k6'
import { generateToken, cetakSEP } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = cetakSEP({ token: data.token })
  check(res, {
    'registrasi/pemakaian-asuransi/sep is status 200': r => r.status === 200
  })
}
