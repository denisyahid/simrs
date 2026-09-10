export function tandaVital(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "NIBP",
                    "model": "tekananDarah",
                    "type": "textfiled",
                    "satuan": "mmHg"
                },
                {
                    "subTitle": "HR",
                    "model": "nadi",
                    "type": "textfiled",
                    "satuan": "x/menit"
                },
        
                {
                    "subTitle": "RR",
                    "model": "pernapasan",
                    "type": "textfiled",
                    "satuan": "x/menit"
                },
                {
                    "subTitle": "Saturasi",
                    "model": "saturasi",
                    "type": "textfiled",
                    "satuan": "%"
                },
                {
                    "subTitle": "Irama EKG",
                    "model": "ekg",
                    "type": "textbox",
                    "satuan": "",
    
                },
            ],
        },
    ]
}
export function terapiOksigen(): any {
    return[
        {
            "title": "Terapi Oksigen",
            "value": [
                {
                    "subTitle": "Binasal",
                    "type": "checkBox",
                    "model": "terapiOksigen_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketTerapiOksigen",
                },
                {
                    "subTitle": "Simple Mask / NRM / RM",
                    "type": "checkBox",
                    "model": "terapiOksigen_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketTerapiOksigen",
                },
                {
                    "subTitle": "Ventilasi Mekanik",
                    "type": "checkBox",
                    "model": "terapiOksigen_",
                },
            ]
        },
    ]
}
export function LokasiPungsi(): any {
    return[
        {
            "title": "Lokasi Pungsi",
            "value": [
                {
                    "subTitle": "Femoral kanan",
                    "type": "checkBox",
                    "model": "LokasiPungsi_",
                },
                {
                    "subTitle": "Femoral kiri",
                    "type": "checkBox",
                    "model": "LokasiPungsi_",
                },
                {
                    "subTitle": "Radial kanan",
                    "type": "checkBox",
                    "model": "LokasiPungsi_",
                },
                {
                    "subTitle": "Radial kiri",
                    "type": "checkBox",
                    "model": "LokasiPungsi_",
                },
                {
                    "subTitle": "Brakial kanan",
                    "type": "checkBox",
                    "model": "LokasiPungsi_",
                },
                {
                    "subTitle": "Dista radial kiri (snuff box)",
                    "type": "checkBox",
                    "model": "LokasiPungsi_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketTerapiOksigen",
                },
            ]
        },
    ]
}
export function AlatPasang(): any {
    return [
        {
            "title": "Alat-alat yang dipasang",
            "value": [
                {
                    "subTitle": "Sheath No",
                    "type": "checkBox",
                    "model": "AlatPasang_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketAlatPasang_",
                },
                {
                    "subTitle": "Folley Catheter No",
                    "type": "checkBox",
                    "model": "AlatPasang_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketAlatPasang_",
                },
                {
                    "subTitle": "Drain Pericardium / Biliary",
                    "type": "checkBox",
                    "model": "AlatPasang_",
                },
            ]
        },   
    ]
}
export function tandaVital2(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "TPM, T.Output",
                    "model": "tpm",
                    "type": "textfiled",
                    "satuan": "mA"
                },
                {
                    "subTitle": "T.Sens",
                    "model": "sens",
                    "type": "textfiled",
                    "satuan": "mV"
                },
        
                {
                    "subTitle": "Setting:HR",
                    "model": "setingHR",
                    "type": "textfiled",
                    "satuan": "mA"
                },
                {
                    "subTitle": "Out Put",
                    "model": "output",
                    "type": "textfiled",
                    "satuan": "%"
                },
                {
                    "subTitle": "Sensitivity",
                    "model": "sensitivity",
                    "type": "textfiled",
                    "satuan": "mV"
                },
            ],
        },
    ]
}
export function ETT(): any {
    return [
        {
            "title": "",
            "value": [
                {
                    "subTitle": "ETT No",
                    "type": "checkBox",
                    "model": "eetno",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketEET",
                },
                {
                    "subTitle": "Batas Bibir",
                    "type": "checkBox",
                    "model": "batasBibir",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketBatasBibir",
                },
                {
                    "subTitle": "IABP, Volume Balon",
                    "type": "checkBox",
                    "model": "volumeBalon",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketVolume",
                },
                {
                    "subTitle": "Asist Ratio",
                    "type": "checkBox",
                    "model": "Asist",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketAsistRatio",
                },
                {
                    "subTitle": "Lain-Lain",
                    "type": "checkBox",
                    "model": "Lainnya",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketLainnya",
                },
            ]
        },  
    ]
}
export function Intake(): any {
    return [
        {
            "title": "Intake",
            "value": [
                {
                    "subTitle": "Minum",
                    "model": "minum",
                    "type": "textfiled",
                    "satuan": "ml"
                },
                {
                    "subTitle": "Cairan Infus",
                    "model": "cairanInfus_",
                    "type": "textfiled",
                    "satuan": "ml"
                },
        
                {
                    "subTitle": "Flusing NaCI 0,9%",
                    "model": "flusing",
                    "type": "textfiled",
                    "satuan": "ml"
                },
                {
                    "subTitle": "Zat Kontras",
                    "model": "kontras",
                    "type": "textfiled",
                    "satuan": "ml"
                },
                {
                    "subTitle": "Lain - Lain",
                    "model": "lainnya",
                    "type": "textfiled",
                    "satuan": "mV"
                },
            ],
        },
    ]
}
export function Output(): any {
    return [
        {
            "title": "Output",
            "value": [
                {
                    "subTitle": "Perdarahan",
                    "model": "perdarahan",
                    "type": "textfiled",
                    "satuan": "ml"
                },
                {
                    "subTitle": "Urine",
                    "model": "urine",
                    "type": "textfiled",
                    "satuan": "ml"
                },
        
                {
                    "subTitle": "Lain-Lainnya",
                    "model": "output_lainnya",
                    "type": "textfiled",
                    "satuan": "ml"
                },
     
            ],
        }, 
    ]
}