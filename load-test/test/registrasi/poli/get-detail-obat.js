import { check } from 'k6'
import { generateToken, getDetailObat } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDetailObat({ token: data.token })
  check(res, {
    'farmasi/get-produkdetail-ceklis is status 200': r => r.status === 200
  })
}
