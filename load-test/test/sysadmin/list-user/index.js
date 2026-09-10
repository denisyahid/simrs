import { generateToken } from '../../../src/api.js'
import getDropdownUser from './get-dropdown-user.js'
import getListUser from './get-list-user.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDropdownUser({ token: data.token })
  getListUser({ token: data.token })
}
