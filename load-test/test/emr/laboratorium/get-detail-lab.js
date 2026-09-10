import { check } from 'k6'
import { generateToken, getDetailLab } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getDetailLab({ token: data.token })
  check(res, {
    'dashboard/so-lab is status 200': r => r.status === 200
  })
}
