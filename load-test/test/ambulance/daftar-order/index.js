import { generateToken } from '../../../src/api.js'
import getDropdownAmb from './get-dropdown-amb.js'
import getOrderAmbulance from './get-order-ambulance.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDropdownAmb({ token: data.token })
  getOrderAmbulance({ token: data.token })
}
