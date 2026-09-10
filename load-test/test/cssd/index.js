import { generateToken } from '../../src/api.js'
import barangAlatMedis from './barang-alat-medis.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  barangAlatMedis({ token: data.token })
}
