import { check } from 'k6'
import { generateToken, getDropdownHarga } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDropdownHarga({}, { token: data.token })
  check(res, {
    'get-dropwodn-harga is status 200': r => r.status === 200
  })
}
