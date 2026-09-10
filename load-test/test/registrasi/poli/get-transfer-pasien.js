import { check } from 'k6'
import { generateToken, getTransferPasien } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getTransferPasien({ token: data.token })
  check(res, {
    'emr/get-order-konsul is status 200': r => r.status === 200
  })
}
