import { check } from 'k6'
import { generateToken, cetakAntrian } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = cetakAntrian({ token: data.token })
  check(res, {
    'report/cetak-antrian is status 200': r => r.status === 200
  })
}
