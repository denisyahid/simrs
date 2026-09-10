import { check } from 'k6'
import { generateToken, getDataDaftarSBM } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() - 1)
  const res = getDataDaftarSBM( { dateStartTglSbm: from, dateEndTglSbm: to }, { token: data.token })
  check(res, {
    'get-data-daftar-penerimaan-kasir is status 200': r => r.status === 200
  })
}
