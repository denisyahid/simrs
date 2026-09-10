import { check } from 'k6'
import { generateToken, getDaftarAntrianRawatJalan, getDetailOrderResep } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default  function (data) {
  // const from = new Date()
  // const to = new Date()
  // to.setDate(to.getDate() - 1)
  // let listPasien = await getDaftarAntrianRawatJalan(
  //   { tglAwal: from, tglAkhir: to, jmlRow: 1 },
  //   { token: data.token }
  // )
  // listPasien = listPasien.status === 200  ? listPasien.json() : []
  const res = getDetailOrderResep(
    {
     
    },
    { token: data.token }
  );
  check(res, {
    'get-riwayat-order-resep is status 200': r => r.status === 200
  })
}
