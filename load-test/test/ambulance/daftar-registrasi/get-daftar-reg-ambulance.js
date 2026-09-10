import { check } from 'k6'
import { generateToken, getDaftarRegAmbulance } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDaftarRegAmbulance({}, { token: data.token })
  check(res, {
    'get-reg-ambulance is status 200': r => r.status === 200
  })
}
