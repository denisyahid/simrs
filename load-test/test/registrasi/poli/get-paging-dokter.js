import { check } from 'k6'
import { generateToken, getPagingDokter } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPagingDokter({ token: data.token })
  check(res, {
    'get-paging-dokter is status 200': r => r.status === 200
  })
}
