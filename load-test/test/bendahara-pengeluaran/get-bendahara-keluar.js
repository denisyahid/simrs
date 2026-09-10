import { check } from 'k6'
import { generateToken, getBendaharaKeluar } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getBendaharaKeluar({}, { token: data.token })
  check(res, {
    'get-tagihan-supplier is status 200': r => r.status === 200
  })
}
