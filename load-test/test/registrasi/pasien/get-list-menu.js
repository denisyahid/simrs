import { check } from 'k6'
import { generateToken, getListMenu } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getListMenu({ token: data.token })
  check(res, {
    'general/menu/list-menu is status 200': r => r.status === 200
  })
}
