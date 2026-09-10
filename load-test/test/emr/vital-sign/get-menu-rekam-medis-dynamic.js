import { check } from 'k6'
import { generateToken, getMenuRekamMedisDynamic } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getMenuRekamMedisDynamic({}, { token: data.token })
  check(res, {
    'get-menu-rekam-medis-dynamic is status 200': r => r.status === 200
  })
}
