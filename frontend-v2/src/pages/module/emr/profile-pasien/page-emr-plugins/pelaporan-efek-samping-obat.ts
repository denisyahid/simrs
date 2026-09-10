export function jenisKelamin(): any {
  return [
    {
      "label": "Pria",
      "code": "PA",
    },
    {
      "label": "Wanita",
      "code": "WA",
    },
    {
      "label": "Wanita Hamil",
      "code": "WH",
    },
    {
      "label": "Wanita Tidak Hamil",
      "code": "WTH",
    },
    {
      "label": "Tidak Tahu",
      "code": "TT",
    },
  ]
}

export function kesudahanPenyakit(): any {
  return [
    {
      "label": "Sembuh",
      "code": "SH",
    },
    {
      "label": "Sembuh dengan gejala sisa",
      "code": "SDG",
    },
    {
      "label": "Belum sembuh",
      "code": "BS",
    },
    {
      "label": "Meninggal",
      "code": "ML",
    },
    {
      "label": "Tidak Tahu",
      "code": "TT",
    },
  ]
}

export function kondisiMenyertai(): any {
  return [
    {
      "label": "Gangguan Ginjal",
      "code": "GG",
    },
    {
      "label": "Gangguan Hati",
      "code": "GH",
    },
    {
      "label": "Alergi",
      "code": "AI",
    },
    {
      "label": "Kondisi Medis Lainnya",
      "code": "KML",
    },
    {
      "label": "Faktor industri, pertanian, kimia",
      "code": "FI",
    },
    {
      "label": "Lain-lain",
      "code": "LN",
    },
  ]
}

export function efekSampingObat(): any {
  return [
    {
      "label": "Bentuk / Manifestasi ESO yang terjadi / Keluhan Lain",
      "model": "bentukEfekSamping",
      "type": "textarea"
    },
    {
      "label": "Masalah pada Mutu / Kualitas Produk Obat",
      "model": "masalahMutu",
      "type": "textarea"
    },
    {
      "label": "Riwayat ESO yang Pernah Dialami",
      "model": "riwayatEso",
      "type": "textarea"
    },
  ]
}

export function kondisi(): any {
  return [
    {
      "label": "Sembuh",
      "code": "SH",
    },
    {
      "label": "Sembuh dengan gejala sisa",
      "code": "SG",
    },
    {
      "label": "Belum sembuh",
      "code": "BS",
    },
    {
      "label": "Meninggal",
      "code": "ML",
    },
    {
      "label": "Tidak tahu",
      "code": "TT",
    },
  ]
}

export function penjelasan(): any {
  return [
    {
      "no": 1,
      "desc": "Monitoring Efek Samping Obat (MESO) yang dilakukan di Indonesia bekerja sama dengan WHO-Uppsala Monitoring Centre (Collaborating Center for International Drug Monitoring) yang dimaksudkan untuk memonitor semua efek samping obat yang dijumpai pada penggunaan obat. Laporan Efek Samping Obat (ESO) dapat disampaikan secara elektronik melalui subsite e-meso (https://e-meso.pom.go.id/ADR) yang juga dapat diakses melalui laman Badan POM (https://pom.go.id/new/#) pada menu Layanan Online bagian Layanan Informasi atau melalui aplikasi E-MESO Mobile yang dapat diunduh di Play Store.",
    },
    {
      "no": 2,
      "desc": "Hasil evaluasi dari semua informasi yang terkumpul akan digunakan sebagai bahan untuk melakukan penilaian kembali obat yang beredar serta untuk melakukan tindakan pengamanan atau penyesuaian yang diperlukan."
    },
    {
      "no": 3,
      "desc": "Umpan balik akan dikirim kepada pelapor."
    },
  ]
}

export function algoritmaNaranjo() :any{
  return [
    {
      "no" : 1,
      "pertanyaan" : "Apakah ada laporan efek samping obat yang serupa? (Are there previous conclusive reports on this reaction? )",
      "scaleYes" : {
        "label" : 1,
        "value" : {
          "desc" : "scaleYes",
          "poin" : 1,
        },
        "model" : "adaLaporan_Y",
      },
      "scaleNo" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleNo",
          "poin" : 0,
        },
        "model" : "adaLaporan_N",
      },
      "scaleUnknown" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleUnknown",
          "poin" : 0,
        },
        "model" : "adaLaporan_U",
      },
    },
    {
      "no" : 2,
      "pertanyaan" : "Apakah efek samping obat terjadi setelah pemberian obat yang dicurigai? (Did the ADR appear after the suspected drug was administered?",
      "scaleYes" : {
        "label" : 2,
        "value" : {
          "desc" : "scaleYes",
          "poin" : 2,
        },
        "model" : "terjadiESO_Y",
      },
      "scaleNo" : {
        "label" : -1,
        "value" : {
          "desc" : "scaleNo",
          "poin" : -1,
        },
        "model" : "terjadiESO_N",
      },
      "scaleUnknown" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleUnknown",
          "poin" : 0,
        },
        "model" : "terjadiESO_U",
      },
    },
    {
      "no" : 3,
      "pertanyaan" : "Apakah efek samping obat membaik setelah obat dihentikan atau obat antagonis khusus diberikan? (Did the ADR improve when the drug was discontinued or a specific antagonist was administered",
      "scaleYes" : {
        "label" : 1,
        "value" : {
          "desc" : "scaleYes",
          "poin" : 1,
        },
        "model" : "efekSampingMembaik_Y",
      },
      "scaleNo" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleNo",
          "poin" : 0 ,
        },
        "model" : "efekSampingMembaik_N",
      },
      "scaleUnknown" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleUnknown",
          "poin" : 0,
        },
        "model" : "efekSampingMembaik_U",
      },
    },
    {
      "no" : 4,
      "pertanyaan" : "Apakah Efek Samping Obat terjadi berulang setelah obat diberikan kembali? (Did the ADR recure when the drug was readministered?)",
      "scaleYes" : {
        "label" : 2,
        "value" : {
          "desc" : "scaleYes",
          "poin" : 2,
        },
        "model" : "efekSampingBerulang_Y",
      },
      "scaleNo" : {
        "label" : -1,
        "value" : {
          "desc" : "scaleNo",
          "poin" : -1,
        },
        "model" : "efekSampingBerulang_N",
      },
      "scaleUnknown" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleUnknown",
          "poin" : 0,
        },
        "model" : "efekSampingBerulang_U",
      },
    },
    {
      "no" : 5,
      "pertanyaan" : "Apakah ada alternative penyebab yang dapat menjelaskan kemungkinan terjadinya efek samping obat? (Are there alternative causes that could on their own have caused the reaction?)",
      "scaleYes" : {
        "label" : -1,
        "value" : {
          "desc" : "scaleYes",
          "poin" : -1,
        },
        "model" : "alternatifPenyebab_Y",
      },
      "scaleNo" : {
        "label" : 2,
        "value" : {
          "desc" : "scaleNo",
          "poin" : 2,
        },
        "model" : "alternatifPenyebab_N",
      },
      "scaleUnknown" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleUnknown",
          "poin" : 0,
        },
        "model" : "alternatifPenyebab_U",
      },
    },
    {
      "no" : 6,
      "pertanyaan" : "Apakah efek samping obat muncul kembali ketika plasebo diberikan? (Did the ADR reappear when a placebo was given?)",
      "scaleYes" : {
        "label" : -1,
        "value" : {
          "desc" : "scaleYes",
          "poin" : -1,
        },
        "model" : "munculKembaliESO_Y",
      },
      "scaleNo" : {
        "label" : 1,
        "value" : {
          "desc" : "scaleNo",
          "poin" : 1,
        },
        "model" : "munculKembaliESO_N",
      },
      "scaleUnknown" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleUnknown",
          "poin" : 0,
        },
        "model" : "munculKembaliESO_U",
      },
    },
    {
      "no" : 7,
      "pertanyaan" : "Apakah obat yang dicurigai terdeteksi di dalam darah atau cairan tubuh lainnya dengan konsentrasi yang toksik? (Was the drug detected in the blood (or other fluids) in concentrations known to be toxic?)",
      "scaleYes" : {
        "label" : 1,
        "value" : {
          "desc" : "scaleYes",
          "poin" : 1,
        },
        "model" : "terdeteksiDD_Y",
      },
      "scaleNo" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleNo",
          "poin" : 0,
        },
        "model" : "terdeteksiDD_N",
      },
      "scaleUnknown" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleUnknown",
          "poin" : 0,
        },
        "model" : "terdeteksiDD_U",
      },
    },
    {
      "no" : 8,
      "pertanyaan" : "Apakah efek samping obat bertambah parah ketika dosis obat ditingkatkan atau bertambah ringan ketika obat diturunkan dosisnya? (Was the ADR more severe when the dose was increased or less severe when the dose was decreased?)",
      "scaleYes" : {
        "label" : 1,
        "value" : {
          "desc" : "scaleYes",
          "poin" : 1,
        },
        "model" : "efekSampingBP_Y",
      },
      "scaleNo" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleNo",
          "poin" : 0,
        },
        "model" : "efekSampingBP_N",
      },
      "scaleUnknown" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleUnknown",
          "poin" : 0,
        },
        "model" : "efekSampingBP_U",
      },
    },
    {
      "no" : 9,
      "pertanyaan" : "Apakah pasien pernah mengalami efek samping obat yang sama atau dengan obat yang mirip sebelumnya? (Did the patient have a similar ADR to the same or similar drugs in any previous exposure?)",
      "scaleYes" : {
        "label" : 1,
        "value" : {
          "desc" : "scaleYes",
          "poin" : 1,
        },
        "model" : "pernahMengalami_Y",
      },
      "scaleNo" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleNo",
          "poin" : 0,
        },
        "model" : "pernahMengalami_N",
      },
      "scaleUnknown" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleUnknown",
          "poin" : 0,
        },
        "model" : "pernahMengalami_U",
      },
    },
    {
      "no" : 10,
      "pertanyaan" : "Apakah efek samping obat dapat dikonfirmasi dengan bukti yang obyektif? (Was the ADR confirmed by objective evidence? )",
      "scaleYes" : {
        "label" : 1,
        "value" : {
          "desc" : "scaleYes",
          "poin" : 1,
        },
        "model" : "efekSampingDK_Y",
      },
      "scaleNo" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleNo",
          "poin" : 0,
        },
        "model" : "efekSampingDK_N",
      },
      "scaleUnknown" : {
        "label" : 0,
        "value" : {
          "desc" : "scaleUnknown",
          "poin" : 0,
        },
        "model" : "efekSampingDK_U",
      },
    },
  ]
}

export function descScore():any{
  return [
    {
      "score" : "9+",
      "category" : "Highly probable"
    },
    {
      "score" : "5 - 8",
      "category" : "Probable"
    },
    {
      "score" : "1 - 4",
      "category" : "Possible"
    },
    {
      "score" : "0 - (-)",
      "category" : "Doubtful"
    },
  ]
}
