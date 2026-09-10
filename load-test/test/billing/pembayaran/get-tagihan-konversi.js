import { check } from 'k6'
import { generateToken, getTagihanKonversi } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getTagihanKonversi({ token: data.token })
  check(res, {
    'kasir/billing/tagihan-konversi is status 200': r => r.status === 200
  })
}
