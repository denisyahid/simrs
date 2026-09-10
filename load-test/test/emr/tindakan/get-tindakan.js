import { check } from 'k6'
import { generateToken, getTindakan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getTindakan(
    {
      idJenisPelayanan: '1',
      idRuangan: '663',
      idKelas: '6',
      filter: {
        logic: 'and',
        filters: [
          {
            value: 'kons',
            field: 'namaproduk',
            operator: 'contains',
            ignoreCase: 'true'
          }
        ]
      }
    },
    { token: data.token }
  )
  check(res, {
    'get-tindakan is status 200': r => r.status === 200
  })
}
