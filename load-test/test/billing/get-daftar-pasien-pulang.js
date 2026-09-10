import { check } from 'k6'
import { generateToken, getDaftarPasienPulang } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() - 1)
  const res = getDaftarPasienPulang( { tglAwal: from, tglAkhir: to, jmlRows: 10 }, { token: data.token })
  check(res, {
    'get-dashboard-kasir is status 200': r => r.status === 200
  })
}
