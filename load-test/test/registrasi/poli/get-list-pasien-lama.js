import { check } from 'k6'
import { generateToken, getPasienLama } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPasienLama({ Rows: 10 }, { token: data.token })
  check(res, {
    'get-list-pasien is status 200': r => r.status === 200
  })
}
