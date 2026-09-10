export function laporan(): any {
    return[
        {
            "title": "Jenis Operasi",
            "value": [
                {
                    "subTitle": "Cito",
                    "type": "checkBox",
                    "model": "lukaOperasi_",
                },
                {
                    "subTitle": "Elektif",
                    "type": "checkBox",
                    "model": "lukaOperasi_",
                },
            ]
        },    
        {
            "title": "Golongan Operasi",
            "value": [
                {
                    "subTitle": "Kecil",
                    "type": "checkBox",
                    "model": "golonganOperasi_",
                },
                {
                    "subTitle": "Sedang",
                    "type": "checkBox",
                    "model": "golonganOperasi_",
                },
                {
                    "subTitle": "Besar",
                    "type": "checkBox",
                    "model": "golonganOperasi_",
                },
                {
                    "subTitle": "Besar Khusus",
                    "type": "checkBox",
                    "model": "golonganOperasi_",
                },
            ]
        },    
        {
            "title": "Jenis Anestesi",
            "value": [
                {
                    "subTitle": "General",
                    "type": "checkBox",
                    "model": "anestesi_",
                },
                {
                    "subTitle": "Elektif",
                    "type": "checkBox",
                    "model": "anestesi_",
                },
            ]
        },    
        {
            "title": "Dikirim untuk pemeriksaan PA",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "pemeriksaanPA_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "pemeriksaanPA_",
                },
            ]
        },  
        {
            "title": "Perdarahan",
            "value": [
                {
                    "subTitle": "Ya",
                    "type": "checkBox",
                    "model": "perdarahan_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "Ketperdarahan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "perdarahan_",
                },
            ]
        }, 
        {
            "title": "Komplikasi",
            "value": [
                {
                    "subTitle": "Ada",
                    "type": "checkBox",
                    "model": "komplikasi_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "komplikasi_",
                },
            ]
        },    
        {
            "title": "Pemakaian Implan",
            "value": [
                {
                    "subTitle": "Ada",
                    "type": "checkBox",
                    "model": "pemakaianImplan_",
                },
                {
                    "subTitle": "",
                    "type": "textBox",
                    "model": "ketPemakaianImplan_",
                },
                {
                    "subTitle": "Tidak",
                    "type": "checkBox",
                    "model": "pemakaianImplan_",
                },
            ]
        },  
    ]
}