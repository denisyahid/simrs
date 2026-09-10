/**
 *
 * @param {Date} date
 * @returns {string}
 */
export function formatDateUrlQuery (date) {
  return date
    .toISOString()
    .slice(0, 19)
    .replace('T', ' ')
}

/**
 * https://stackoverflow.com/a/73473413/1247558
 * @param {Object} data
 * @returns {Object}
 */
function flattenObj (data) {
  var result = {}
  function recurse (cur, prop) {
    if (Object(cur) !== cur) {
      result[prop] = cur
    } else if (Array.isArray(cur)) {
      for (var i = 0, l = cur.length; i < l; i++)
        recurse(cur[i], prop + '[' + i + ']')
      if (l == 0) result[prop] = []
    } else {
      var isEmpty = true
      for (var p in cur) {
        isEmpty = false
        recurse(cur[p], prop ? prop + '[' + p + ']' : p)
      }
      if (isEmpty && prop) result[prop] = {}
    }
  }
  recurse(data, '')
  return result
}

/**
 *
 * @param {Object} obj
 * @returns {string}
 */
export function objToQueryString (obj) {
  const output = flattenObj(obj)
  return Object.entries(output)
    .map(elem => elem[0] + '=' + elem[1])
    .join('&')
}
export function formatDateOnlyUrlQuery (date) {
  return date
    .toISOString()
    .slice(0, 10)
}