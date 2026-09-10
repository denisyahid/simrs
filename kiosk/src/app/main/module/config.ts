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
  static profile() {
    return {
      nama: 'RSUD Gunung Jati',
      namaPPK: "RSUD Gunung Jati",
      alamat: 'Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134',
      link: 'https://drive.google.com/file/d/1GCx6WDTTykrGWSFIa6S6guVGRqZR9uQ1/view?usp=sharing',
      kdRekananBPJS: 2552,
      kdKelompokBPJS: 2,
    }
  }
}
