import { check } from 'k6'
import { generateToken, getPenjaminKelompok } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPenjaminKelompok({ token: data.token })
  check(res, {
    'registrasi/penjamin-by-kelompokpasien is status 200': r => r.status === 200
  })
}
