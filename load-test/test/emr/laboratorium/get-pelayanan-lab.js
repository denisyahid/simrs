import { check } from 'k6'
import { generateToken, getPelayananLab } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getPelayananLab({ token: data.token })
  check(res, {
    'dashboard/get-pelayanan-lab is status 200': r => r.status === 200
  })
}
