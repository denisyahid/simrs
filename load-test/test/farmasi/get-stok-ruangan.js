import { check } from 'k6'
import { generateToken, getStokRuanganApt } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getStokRuanganApt( { token: data.token })
  check(res, {
    'logistik/stok-ruangan-grid is status 200': r => r.status === 200
  })
}
