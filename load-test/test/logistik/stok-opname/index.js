import { generateToken } from '../../../src/api.js'
import getStokOpname from './get-stok-opname.js'
import saveStokOpname from './save-stok-opname.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getStokOpname({ token: data.token })
  saveStokOpname({ token: data.token })
}
