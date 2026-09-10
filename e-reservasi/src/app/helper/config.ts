export class Configuration {
  static get() {
    if (window.location.hostname.indexOf('localhost') > -1 || window.location.hostname.indexOf('127.0') > -1) {
      return {
        apiBackend: "http://localhost:8701/service/",

      };
    } else {
      return {
        apiBackend: "/service/",
      };
    }

  }
}
