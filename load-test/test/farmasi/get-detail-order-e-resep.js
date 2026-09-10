import { check } from 'k6'
import {
  generateToken,
  listEResep,
  getDetailOrderEResep
} from '../../src/api.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}
export default async function (data) {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() - 1)
  let listPasien = await listEResep(
    { tglAwal: from, tglAkhir: to, jmlRow: 1 },
    { token: data.token }
  )
  listPasien = listPasien.status === 200 ? listPasien.json() : []
  const res = getDetailOrderEResep(
    {
      noorder: listPasien.length > 0 ? listPasien[0].noorder : '2306000001'
    },
    { token: data.token }
  )
  check(res, {
    'get-detail-order-e-resep is status 200': r => r.status === 200
  })
}
