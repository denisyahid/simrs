import { generateToken } from '../../src/api.js'
import getBendaharaKeluar from './get-bendahara-keluar.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getBendaharaKeluar({ token: data.token })
}
