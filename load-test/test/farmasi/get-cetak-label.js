import { check } from 'k6'
import { generateToken, getCetakLabel } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getCetakLabel( { token: data.token })
  check(res, {
    'report/farmasi/cetak-apotik-label-kecil is status 200': r => r.status === 200
  })
}
