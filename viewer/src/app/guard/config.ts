export class Config {
    static get() {
        if (window.location.hostname.indexOf('localhost') > -1 || window.location.hostname.indexOf('127.0') > -1) {
            return {
                apiBackend: "http://localhost:8901/",
                socketIO: "http://localhost:2530",
            };
        } else {
            return {
                apiBackend: "/",
                socketIO: "",
            };
        }
    }
    static getProfile() {
        return {
            namaProfile: "RS Demo",
            logo: '',
            brand: 'TRANSMEDIC'
        };
    }
}
