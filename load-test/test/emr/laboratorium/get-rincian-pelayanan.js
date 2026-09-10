import { check } from 'k6'
import { generateToken, getRincianPelayanan } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getRincianPelayanan(
    { noregistrasifk: 'e66aae40-4f91-11ed-95f9-9101028e' },
    { token: data.token }
  )
  check(res, {
    'get-rincian-pelayanan is status 200': r => r.status === 200
  })
}
