import { check } from 'k6'
import { generateToken, getDaftarAntrianRawatJalan, getRiwayatBundleHais } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}
export default async function (data) {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() - 1)
  let listPasien = await getDaftarAntrianRawatJalan(
    { tglAwal: from, tglAkhir: to, jmlRow: 1 },
    { token: data.token }
  )
  listPasien = listPasien.status === 200  ? listPasien.json() : []
  const res = getRiwayatBundleHais(
    {
      norec_pd:
        listPasien.length > 0 ? listPasien[0].norec_pd : "d5f7a880-03a6-11ee-991c-a3ff7ded",
    },
    { token: data.token }
  );
  check(res, {
    'get-riwayat-bundle-hais is status 200': r => r.status === 200
  })
}
