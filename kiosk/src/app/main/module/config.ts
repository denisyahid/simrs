/**
 * Konfigurasi alamat backend untuk aplikasi kiosk.
 *
 * Urutan penentuan alamat:
 *  1. `window.SIMRS_BASE_PATH` bila di-set dari index.html (opsional).
 *  2. Sub-path /simrs → bila diakses lewat http://localhost/simrs/kiosk/
 *     maka API memakai http://localhost/simrs/service/.
 *  3. Mode lama saat development: http://localhost:8701/service/.
 *  4. Mode produksi lama: /service/ (relatif terhadap origin).
 */
export class Configuration {
  /** Base path deployment, mis. '/simrs' — kosong bila di root. */
  static basePath(): string {
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
    const basePath = Configuration.basePath();
    if (basePath) {
      return {
        apiBackend: window.location.origin + basePath + '/service/',
      };
    }
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
  static profile() {
    return {
      nama: 'RSUD Gunung Jati',
      namaPPK: "RSUD Gunung Jati",
      alamat: 'Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134',
      link: 'https://drive.google.com/file/d/1GCx6WDTTykrGWSFIa6S6guVGRqZR9uQ1/view?usp=sharing',
      kdRekananBPJS: 2552,
      kdKelompokBPJS: 2,
    };
  }
}
