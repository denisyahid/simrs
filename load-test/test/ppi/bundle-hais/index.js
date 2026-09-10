import { generateToken } from '../../../src/api.js'
import getDropdownBundle from './get-dropdown-bundle.js'
import getRiwayatBundleHais from './get-riwayat-bundle-hais.js'
import saveBundleHais from './save-bundle-hais.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getRiwayatBundleHais({ token: data.token })
  getDropdownBundle({ token: data.token })
  saveBundleHais({ token: data.token })
}
