import { check } from 'k6'
import { generateToken, getOrderAmbulance } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getOrderAmbulance({}, { token: data.token })
  check(res, {
    'get-order-ambulance is status 200': r => r.status === 200
  })
}
