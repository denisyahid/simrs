export function kategoriUmum(): any{
  return[
    {
      "title" : "Negatif Less",
      "model" : "kategoriUmum_",
      "value" : {
        "code" : "NL",
        "description" : "Negatif Less"
      }
    },
    {
      "title" : "Abnormal",
      "model" : "kategoriUmum_",
      "value" : {
        "code" : "AB",
        "description" : "Abnormal"
      }
    },
    {
      "title" : "Neoplasma",
      "model" : "kategoriUmum_",
      "value" : {
        "code" : "NP",
        "description" : "Neoplasma"
      }
    },
    {
      "title" : "Lain-lain",
      "model" : "kategoriUmum_",
      "value" : {
        "code" : "LL",
        "description" : "Lain-lain"
      }
    },
  ]
}

export function adekuasiPemeriksaan(): any{
  return[
    {
      "title": "Memuaskan",
      "model": "adekuasiPemeriksaan",
      "value": {
        "code": "M",
        "description": "Memuaskan"
      }
    },
    {
      "title": "Tidak Memuaskan",
      "model": "adekuasiPemeriksaan",
      "value": {
        "code": "TM",
        "description": "Tidak Memuaskan"
      }
    },
  ]
}

export function infeksiOrganisme(): any{
  return[
    {
      "title" : "Trikomonas",
      "model" : "infeksiOrganisme_",
      "value" : {
        "code" : "TK",
        "description" : "Trikomonas"
      }
    },
    {
      "title" : "Kandida",
      "model" : "infeksiOrganisme_",
      "value" : {
        "code" : "KD",
        "description" : "Kandida"
      }
    },
    {
      "title" : "Kokobasili",
      "model" : "infeksiOrganisme_",
      "value" : {
        "code" : "KB",
        "description" : "Kokobasili"
      }
    },
    {
      "title" : "Aktinomises",
      "model" : "infeksiOrganisme_",
      "value" : {
        "code" : "AM",
        "description" : "Aktinomises"
      }
    },
    {
      "title" : "Virus Herpes",
      "model" : "infeksiOrganisme_",
      "value" : {
        "code" : "VH",
        "description" : "Virus Herpes"
      }
    },
  ]
}

export function nonNeoPlastik(): any{
  return[
    {
      "title" : "Perubahan Reaktif",
      "model" : "nonNeoPlastik_",
      "value" : {
        "code" : "PR",
        "description" : "Perubahan Reaktif"
      }
    },
    {
      "title" : "Sel Epitel",
      "model" : "nonNeoPlastik_",
      "value" : {
        "code" : "SE",
        "description" : "Sel Epitel"
      }
    },
    {
      "title" : "Atrofi",
      "model" : "nonNeoPlastik_",
      "value" : {
        "code" : "AF",
        "description" : "Atrofi"
      }
    },
  ]
}

export function nonNeoPlastikSelect():any{
  return[
    {
      "title" : "Peradangan",
      "model" : "nonNeoPlastik_4",
      "value" : {
        "code" : "RD",
        "description" : "Peradangan"
      }
    },
    {
      "title" : "Radiasi",
      "model" : "nonNeoPlastik_4",
      "value" : {
        "code" : "RAD",
        "description" : "Radiasi"
      }
    },
    {
      "title" : "IUD",
      "model" : "nonNeoPlastik_4",
      "value" : {
        "code" : "IUD",
        "description" : "IUD"
      }
    },
  ]
}

export function selSkuamosa(): any{
  return[
    {
      "title" : "Sel Skuamosa Aktipik",
      "model" : "selSkuamosa_",
      "value" : {
        "code" : "SKA",
        "description" : "Sel Skuamosa Aktipik"
      },
      "option" : [
        {
          "title" : "ACS US",
          "model" : "optionSKA",
          "value" : {
            "code" : "ACSUS",
            "description" : "ACS US"
          }
        },
        {
          "title" : "ACS H",
          "model" : "optionSKA",
          "value" : {
            "code" : "ACSH",
            "description" : "ACS H"
          }
        },
      ]
    },
    {
      "title" : "Lesi Intraepitelal (LSIL)",
      "model" : "selSkuamosa_",
      "value" : {
        "code" : "LSIL",
        "description" : "Lesi Intraepitelal (LSIL)"
      },
      "option" : [
        {
          "title" : "HPV",
          "model" : "optionLSIL",
          "value" : {
            "code" : "HPV",
            "description" : "HPV"
          }
        },
        {
          "title" : "CIN 1",
          "model" : "optionLSIL",
          "value" : {
            "code" : "CIN1",
            "description" : "CIN 1"
          }
        },
        {
          "title" : "CIN 1 dengan Infeksi",
          "model" : "optionLSIL",
          "value" : {
            "code" : "CIN1IN",
            "description" : "CIN 1 dengan Infeksi"
          }
        },
      ]
    },
    {
      "title" : "Lesi Intraepitelal (HSIL)",
      "model" : "selSkuamosa_",
      "value" : {
        "code" : "HSIL",
        "description" : "Lesi Intraepitelal (HSIL)"
      },
      "option" : [
        {
          "title" : "CIN 2",
          "model" : "optionHSIL",
          "value" : {
            "code" : "CIN2",
            "description" : "CIN 2"
          }
        },
        {
          "title" : "CIN 3",
          "model" : "optionHSIL",
          "value" : {
            "code" : "CIN3",
            "description" : "CIN 3"
          }
        },
        {
          "title" : "CIS",
          "model" : "optionHSIL",
          "value" : {
            "code" : "CIS",
            "description" : "CIS"
          }
        },
        {
          "title" : "Invasi",
          "model" : "optionHSIL",
          "value" : {
            "code" : "INV",
            "description" : "Invasi"
          }
        },
      ]
    },
  ]
}

export function selGlandular(): any{
  return[
    {
      "title" : "Antipik (NOS)",
      "model" : "selGlandular_",
      "value" : {
        "code" : "AN",
        "description" : "Antipik (NOS)"
      },
      "option" : [
        {
          "title" : "Endoserviks",
          "model" : "optionAN",
          "value" : {
            "code" : "ES",
            "description" : "Endoserviks"
          }
        },
        {
          "title" : "Endometrium",
          "model" : "optionAN",
          "value" : {
            "code" : "EM",
            "description" : "Endometrium"
          }
        },
        {
          "title" : "Glandular",
          "model" : "optionAN",
          "value" : {
            "code" : "GD",
            "description" : "Glandular"
          }
        },
      ]
    },
    {
      "title" : "Antipik (Favor Neoplastic)",
      "model" : "selGlandular_",
      "value" : {
        "code" : "AF",
        "description" : "Antipik (Favor Neoplastic)"
      },
      "option" : [
        {
          "title" : "Endoserviks",
          "model" : "optionFav",
          "value" : {
            "code" : "ES",
            "description" : "Endoserviks"
          }
        },
        {
          "title" : "Glandular",
          "model" : "optionFav",
          "value" : {
            "code" : "GD",
            "description" : "Glandular"
          }
        },
      ]
    },
    {
      "title" : "Adenokarsinoma is situ",
      "model" : "selGlandular_",
      "value" : {
        "code" : "AIS",
        "description" : "Adenokarsinoma is situ"
      },
      "option" : [
        {
          "title" : "Endoserviks",
          "model" : "optionAdenokarsinoma",
          "value" : {
            "code" : "ES",
            "description" : "Endoserviks"
          }
        },
        {
          "title" : "Endometrium",
          "model" : "optionAdenokarsinoma",
          "value" : {
            "code" : "EM",
            "description" : "Endometrium"
          }
        },
        {
          "title" : "Extra Uterine",
          "model" : "optionAdenokarsinoma",
          "value" : {
            "code" : "EU",
            "description" : "Extra Uterine"
          }
        },
        {
          "title" : "Tidak Dapat",
          "model" : "optionAdenokarsinoma",
          "value" : {
            "code" : "TD",
            "description" : "Tidak Dapat"
          }
        },
      ]
    },
    {
      "title" : "Adenokarsinoma",
      "model" : "selGlandular_",
      "value" : {
        "code" : "AK",
        "description" : "Adenokarsinoma"
      }
    },
  ]
}
