import { generateToken } from '../../../src/api.js'
import getMenu from './get-menu.js'
import getModulApp from './get-modul-app.js'
import getSubsistem from './get-subsistem.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getMenu({ token: data.token })
  getModulApp({ token: data.token })
  getSubsistem({ token: data.token })
}
