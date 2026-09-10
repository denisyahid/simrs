import { check } from 'k6'
import { generateToken, getStokOpname } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getStokOpname({}, { token: data.token })
  check(res, {
    'get-stok-opname is status 200': r => r.status === 200
  })
}
