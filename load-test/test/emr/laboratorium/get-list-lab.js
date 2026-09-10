import { check } from 'k6'
import { generateToken, getListLab } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getListLab({ token: data.token })
  check(res, {
    'dashboard/list-lab is status 200': r => r.status === 200
  })
}
