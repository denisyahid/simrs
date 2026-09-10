import { check } from 'k6'
import { generateToken, cetakLabelPasien } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = cetakLabelPasien({ token: data.token })
  check(res, {
    'dashboard/registrasi/cetak-label-pasien is status 200': r => r.status === 200
  })
}
