import { check } from 'k6'
import {
  generateToken,
  getPenjaminPasienByKelompokPasien
} from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPenjaminPasienByKelompokPasien(
    {
      kdKelompokPasien: data.kdKelompokPasien || '1'
    },
    { token: data.token }
  )
  check(res, {
    'get-penjamin-by-kelompok-pasien is status 200': r => r.status === 200
  })
}
