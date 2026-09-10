import { check } from 'k6'
import { generateToken, getKomponenHarga } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getKomponenHarga(
    {
      idJenisPelayanan: '1',
      idRuangan: '37',
      idKelas: '6',
      idProduk: '43022'
    },
    { token: data.token }
  )
  check(res, {
    'get-komponen-harga is status 200': r => r.status === 200
  })
}
