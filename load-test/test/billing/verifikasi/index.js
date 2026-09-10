import { generateToken } from '../../../src/api.js'
import getDetailTagihanVerifikasi from './get-detail-tagihan-verifikasi.js'
import getVerifikasiTagihan from './get-verifikasi-tagihan.js'
import simpanVerifikasiTagihanTatarekening from './simpan-verifikasi-tagihan-tatarekening.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  // getDetailTagihanVerifikasi({ token: data.token })
  getVerifikasiTagihan({ token: data.token })
  simpanVerifikasiTagihanTatarekening({ token: data.token })
}
