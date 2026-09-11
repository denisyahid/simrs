/**
 * Konfigurasi alamat backend & socket untuk aplikasi viewer (display antrian).
 *
 * Urutan penentuan alamat:
 *  1. `window.SIMRS_BASE_PATH` bila di-set dari index.html (opsional).
 *  2. Sub-path /simrs → bila aplikasi diakses lewat
 *     http://localhost/simrs/viewer/ maka API memakai
 *     http://localhost/simrs/  dan socket memakai origin + path /simrs/socket.io.
 *  3. Mode lama saat development: `http://localhost:8901/` (backend) & `:2530` (socket).
 *  4. Mode produksi lama: path relatif `/` dan socket di origin yang sama.
 */
export class Config {
    /** Base path deployment, mis. '/simrs' — kosong bila di root. */
    static getBasePath(): string {
        if (typeof window === 'undefined') {
            return '';
        }
        const fromGlobal = (window as any).SIMRS_BASE_PATH;
        if (fromGlobal) {
            return String(fromGlobal).replace(/\/+$/, '');
        }
        const match = /^(.*?\/simrs)(?:\/|$)/i.exec(window.location.pathname);
        return match ? match[1] : '';
    }

    static get() {
        const basePath = Config.getBasePath();
        if (basePath) {
            return {
                apiBackend: window.location.origin + basePath + '/',
                socketIO: window.location.origin,
                socketPath: basePath + '/socket.io',
            };
        }
        if (window.location.hostname.indexOf('localhost') > -1 || window.location.hostname.indexOf('127.0') > -1) {
            return {
                apiBackend: "http://localhost:8901/",
                socketIO: "http://localhost:2530",
                socketPath: "/socket.io",
            };
        } else {
            return {
                apiBackend: "/",
                socketIO: "",
                socketPath: "/socket.io",
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
