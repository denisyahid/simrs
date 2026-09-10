import { check } from 'k6'
import { generateToken, getPasienFramasi } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPasienFramasi( { token: data.token })
  check(res, {
    'farmasi/daftar-pasien-farmasi-grid is status 200': r => r.status === 200
  })
}
