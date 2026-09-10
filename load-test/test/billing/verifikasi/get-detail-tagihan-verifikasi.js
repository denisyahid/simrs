import { check } from 'k6'
import { generateToken, getDaftarPasienPulang, getDetailTagihanVerifikasi } from '../../../src/api.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default  function (data) {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() - 1)
  let listPasien =  getDaftarPasienPulang(
    { tglAwal: from, tglAkhir: to, jmlRows: 1 },
    { token: data.token }
  )
  listPasien = listPasien.status === 200  ? listPasien.json() ['response']: []

  const res = getDetailTagihanVerifikasi(
    { noRegister: listPasien.length > 0
      ? listPasien[0].noRegistrasi : '2211000001' },
    { token: data.token }
  )
  check(res, {
    'get-detail-tagihan-verifikasi is status 200': r => r.status === 200
  })
}
