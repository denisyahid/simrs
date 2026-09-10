import { check } from 'k6'
import { generateToken, getDaftarPasienPulang, getDashboardRJ, getDetailTagihan } from '../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}


export default  function (data) {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() - 1)

  let  listPasien =  getDashboardRJ(
    { tglAwal: from, tglAkhir: to, jmlRows: 1 },
    { token: data.token }
  )
  listPasien = listPasien.status === 200  ? listPasien.json()['response']['data'] : []
  const res = getDetailTagihan(
    { norec_pd:  listPasien.length > 0
      ? listPasien[0].norec_pd
      : '36e91ab1-1ef8-49de-8fac-79ef68643eaa' },
    { token: data.token }
  )
  check(res, {
    'get-detail-tagihan is status 200': r => r.status === 200
  })
}
