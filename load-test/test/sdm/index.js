import { generateToken } from '../../src/api.js'
import getDropdownPegawai from './get-dropdown-pegawai.js'
import getListPegawai from './get-list-pegawai.js'
import savePegawai from './save-pegawai.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDropdownPegawai({ token: data.token })
  getListPegawai({ token: data.token })
  savePegawai({ token: data.token })
}
