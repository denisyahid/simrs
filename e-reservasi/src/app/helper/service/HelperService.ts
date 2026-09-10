import { Injectable } from '@angular/core';

@Injectable()
export class HelperService {

    constructor() { }

    // format currency view grid
    public formatCurrency(data) {
        data = data.replace(/.{3}$/, "").replace(/\d{1,3}(?=(\d{3})+(?!\d))/g, "$&.");
        if (data != null && data != "") {
            return data;
        } else {
            return '-';
        }

    }

    public async decryptBPJS(res: string): Promise<any>{
        const [response, hash] = atob(res).split('::');
    
        function verify(str: string): string {
            // cb bytes 32 with CRC32
            let crc = 0xffffffff; // Harus dimulai dengan 0xffffffff cenah
            for (let i = 0; i < str.length; i++) {
                crc = (crc >>> 8) ^ table[(crc ^ str.charCodeAt(i)) & 0xff];
            }
            return ((crc ^ 0xffffffff) >>> 0).toString();
        }

        const table = new Uint32Array(256).map((_, i) => {
            let c = i;
            for (let j = 0; j < 8; j++) {
                c = c & 1 ? 0xedb88320 ^ (c >>> 1) : c >>> 1;
            }
            return c;
        });

        const result = verify(response);
    
        if (result === hash) {
            return JSON.parse(response);
        } 
    }
    
    // public async decryptBPJS(res: string) {
    //     const crypto = window.crypto || window.msCrypto;
    //     const [jsonResponse, hash] = atob(res).split('::');
    //     const isvalid = crypto.subtle.digest('SHA-256', new TextEncoder().encode(jsonResponse))
    //         .then(buffer => {
    //             let jArray = Array.from(new Uint8Array(buffer));
    //             let base64 = btoa(String.fromCharCode.apply(null, jArray));
    //             return base64;
    //         });
    
    //     isvalid.then(result => {
    //         console.log("result is", result)
    //         console.log("hash is", hash)
    //         if (result === hash) {
    //             console.log('MAZUKK', JSON.parse(jsonResponse));
    //         } else {
    //             console.log('err')
    //             throw new Error('Invalid response data');
    //         }
    //     }).catch(err => {
    //         console.error('Err:', err);
    //     });
    // }

    // public formatCurrencyPengajuanAnggaran(data) {
    //     console.log(data);
    //     if(data !== null || data !== ""){
    //         if(/\./g.test(data) === true) {
    //             data = data.replace(/\./g,"").replace(/\d{1,3}(?=(\d{3})+(?!\d))/g,"$&.");
    //         } else {
    //             data = data.replace(/\d{1,3}(?=(\d{3})+(?!\d))/g,"$&.");
    //         }
    //         return data;
    //     }
    // }
}