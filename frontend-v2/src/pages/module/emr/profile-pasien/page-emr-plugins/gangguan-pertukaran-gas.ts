export function Hubungan(): any {
    return[
        {
            "title": "Ketidakseimbangan ventilasi - perfusi",
            "model": "berhubunganDengan_",
            "value" : {
                "code" : "KVP",
                "description" : "Ketidakseimbangan ventilasi - perfusi"
            }
        },
        {
            "title": "Perubahan membran alveolus - kapiler",
            "model": "berhubunganDengan_",
            "value" : {
                "code" : "KMK",
                "description" : "Perubahan membran alveolus - kapiler"
            }
        },
    ]
}

export function dataSubjektif(): any {
    return[
        {
            "title":"Dispanea",
            "model":"dataSubjektif_"

        },
        {
            "title":"Pusing",
            "model":"dataSubjektif_"

        },
        {
            "title":"Penglihatan Kabur",
            "model":"dataSubjektif_"

        },
    ]
}
export function dataObjektif(): any {
    return [
        {
            "title":"PCO2 Meingkat/Menurun",
            "model":"dataObjektif_",
            "value" : {
                "code" : "PCO2",
                "description" : "PCO2 Meingkat/Menurun"
            }

        },
        {
            "title":"PO2 Menurun",
            "model":"dataObjektif_",
            "value" : {
                "code" : "PO2",
                "description" : "PO2 Menurun"
            }

        },
        {
            "title":"Takikardi",
            "model":"dataObjektif_",
            "value" : {
                "code" : "TKD",
                "description" : "Takikardi"
            }

        },
        {
            "title":"PH Arteri Meingkat atau menurun",
            "model":"dataObjektif_",
            "value" : {
                "code" : "PAM",
                "description" : "PH Arteri Meingkat atau menurun"
            }

        },
        {
            "title":"Bunyi nafas tambahan",
            "model":"dataObjektif_",
            "value" : {
                "code" : "BNT",
                "description" : "Bunyi nafas tambahan"
            }

        },
        {
            "title":"Sianosis",
            "model":"dataObjektif_",
            "value" : {
                "code" : "SS",
                "description" : "Sianosis"
            }

        },
        {
            "title":"Diaphoresis",
            "model":"dataObjektif_",
            "value" : {
                "code" : "DS",
                "description" : "Diaphoresis"
            }

        },
        {
            "title":"Gelisah",
            "model":"dataObjektif_",
            "value" : {
                "code" : "GH",
                "description" : "Gelisah"
            }
        },
        {
            "title":"Nafas cuping hidung",
            "model":"dataObjektif_",
            "value" : {
                "code" : "NCH",
                "description" : "Nafas cuping hidung"
            }
        },
        {
            "title":"Pola nafas abnormal (cepat atau lambat,regulasi/irregural,dalam/dangkal)",
            "model":"dataObjektif_",
            "value" : {
                "code" : "PNA",
                "description" : "Pola nafas abnormal (cepat atau lambat,regulasi/irregural,dalam/dangkal)"
            }
        },
        {
            "title":"Warna kulit abnormal (pucat/kebiruan)",
            "model":"dataObjektif_",
            "value" : {
                "code" : "WKA",
                "description" : "Warna kulit abnormal (pucat/kebiruan)"
            }
        },
        {
            "title":"kesadaran Menurun",
            "model":"dataObjektif_",
            "value" : {
                "code" : "KM",
                "description" : "kesadaran Menurun"
            }
        },

    ]
}
export function Observasi(): any {
    return [
        {
            "title": "Monitor pola napas (frekuensi,kedalaman,usaha napas)",
            "model": "observasi_" ,
            "value" : {
                "code" : "MPS",
                "description" : "Monitor pola napas (frekuensi,kedalaman,usaha napas)"
            }
        }, 
        {
            "title": "Monitor pola napas (seperti bradipnea, takipnea, hiperventilasi, Kussmaul, Cheyne-Stokes, Biot, ataksik)",
            "model": "observasi_" ,
            "value" : {
                "code" : "MPN",
                "description" : "Monitor pola napas (seperti bradipnea, takipnea, hiperventilasi, Kussmaul, Cheyne-Stokes, Biot, ataksik)"
            }
        }, 
        {
            "title": "Monitor kemampuan batuk efektif",
            "model": "observasi_",
            "value" : {
                "code" : "MKBE",
                "value" : "Monitor kemampuan batuk efektif"
            }
        }, 
        {
            "title": "Monitor adanya produksi sputum",
            "model": "observasi_",
            "value" : {
                "code" : "MAPS",
                "value" : "Monitor adanya produksi sputum"
            }
        }, 
        {
            "title": "Monitor adanya sumbatan jalan napas",
            "model": "observasi_",
            "value" : {
                "code" : "MASN",
                "value" : "Monitor adanya sumbatan jalan napas"
            }
        }, 
        {
            "title": "Palpasi kesimetrisan ekspansi paru",
            "model": "observasi_",
            "value" : {
                "code" : "PKEP",
                "value" : "Palpasi kesimetrisan ekspansi paru"
            }
        }, 
        {
            "title": "Auskultasi bunyi napas",
            "model": "observasi_",
            "value" : {
                "code" : "ABN",
                "value" : "Auskultasi bunyi napas"
            }
        }, 
        {
            "title": "Monitor saturasi oksigen",
            "model": "observasi_",
            "value" : {
                "code" : "MSO",
                "value" : "Monitor saturasi oksigen"
            }
        }, 
        {
            "title": "Monitor nilai AGD",
            "model": "observasi_",
            "value" : {
                "code" : "MNA",
                "value" : "Monitor nilai AGD"
            }
        }, 
        {
            "title": "Monitor hasil x-ray toraks",
            "model": "observasi_",
            "value" : {
                "code" : "MHT",
                "value" : "Monitor hasil x-ray toraks"
            } 
        }, 
    ]
}
export function Terapeutik(): any{
    return[
        {
            "title": "Atur interval waktu pemantauan respirasi sesuai kondisi pasien",
            "model": "terapeutik_",
            "value" : {
                "code" : "PKP",
                "description" : "Atur interval waktu pemantauan respirasi sesuai kondisi pasien"
            }
        }, 
        {
            "title": "Dokumentasikan hasil pemantauan",
            "model": "terapeutik_",
            "value" : {
                "code" : "DHP",
                "description" : "Dokumentasikan hasil pemantauan"
            }
        }, 

    ]
}
export function Edukasi(): any {
    return[
        {
            "title": "Jelaskan tujuan dan prosedur pemantauan",
            "model": "edukasi_",
            "value" : {
                "code" : "JT",
                "description" : "Jelaskan tujuan dan prosedur pemantauan"
            } 
        }, 
        {
            "title": "Informasikan hasil pemantauan, jika perlu",
            "model": "edukasi_",
            "value" : {
                "code" : "HP",
                "description" : "Informasikan hasil pemantauan, jika perlu"
            } 
        }, 
    ]
}