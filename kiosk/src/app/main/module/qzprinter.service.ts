import { Pipe, PipeTransform, Injectable } from '@angular/core';
import { from as fromPromise, Observable, throwError } from 'rxjs';
import { HttpErrorResponse } from '@angular/common/http';
import { catchError, map } from 'rxjs/operators';
import { ToastrService } from 'ngx-toastr';

import * as qz from 'qz-tray';
import { sha256 } from 'js-sha256';
import { KJUR, KEYUTIL, stob64, hextorstr } from 'jsrsasign';
import { Configuration } from './config';
import { HttpService } from './httpService';



@Injectable()
export class QzprinterService {

  constructor(private _toastrService: ToastrService,private _httpService: HttpService) {
    qz.security.setCertificatePromise((resolve, reject) => {
      fetch("assets/qz/digital-certificate.txt", {cache: 'no-store', headers: {'Content-Type': 'text/plain'}})
       .then(data => resolve(data.text()));
     });
    qz.security.setSignatureAlgorithm("SHA512"); // Since 2.1
    qz.security.setSignaturePromise(hash => {
    return (resolve, reject) => {
      fetch("assets/qz/key.pem", {cache: 'no-store', headers: {'Content-Type': 'text/plain'}})
      .then(wrapped => wrapped.text())
      .then(data => {
        var pk = KEYUTIL.getKey(data);
        var sig = new KJUR.crypto.Signature({"alg": "SHA512withRSA"}); // Use "SHA1withRSA" for QZ Tray 2.0 and older
        sig.init(pk);
        sig.updateString(hash);
        var hex = sig.sign();
        console.log("DEBUG: \n\n" + stob64(hextorstr(hex)));
        resolve(stob64(hextorstr(hex)));
      })
      .catch(err => console.error(err));
      };
    });

    qz.api.setSha256Type(data => sha256(data));
    qz.api.setPromiseType(resolver => new Promise(resolver));
  }

  // connect() {
  //   debugger;
  //   if (!qz.websocket.isActive()) {
  //     qz.websocket.connect().then((res: any) => console.log(res)).catch((err: any) => console.log(err));
  //   } else {
  //     console.log('Error');
  //   }
  // }

  connect() {
    return new Promise(function(resolve, reject) {
        if (qz.websocket.isActive()) {	// if already active, resolve immediately
            resolve;
        } else {
            // try to connect once before firing the mimetype launcher
            qz.websocket.connect().then(resolve, function retry() {
                // if a connect was not successful, launch the mimetime, try 3 more times
                window.location.assign("qz:launch");
                qz.websocket.connect({ retries: 2, delay: 1 }).then(resolve, reject);
            });
        }
    });
}

  disconnect(){

    if (qz.websocket.isActive()) {
      qz.websocket.disconnect().then((res) => console.log(res)).catch((err) => console.log(err));
    } else {
      console.log('Error');
    }
  }

  sendKeysAlttab(){
    qz.printers.sendKeysAlttab().then((res) => console.log(res)).catch((err) => console.log(err));
  }
  // Get the list of printers connected

  // Print data to chosen printer
  printData(printer: string,data: any, copies: number = 1 , papertipe: string = "A4" ): Observable<any> {
    let options: { size: { width: number; height: number; } | { width: number; height: number; } | { width: number; height: number; } | { width: number; height: number; } | { width: number; height: number; }; units: string; orientation: string; margins: { top: number; right: number; bottom: number; left: number; } | { top: number; right: number; bottom: number; left: number; } | { top: number; right: number; bottom: number; left: number; } | { top: number; right: number; bottom: number; left: number; } | { top: number; right: number; bottom: number; left: number; }; copies: number; };
    let printername = "";
    if( printer== 'ETIKET-PUTIH' || printer == 'ETIKET-BIRU'){
      printername = printer;
      options =  {  size: {width: 65, height: 40}, units: 'mm', orientation: 'portrait', margins: { top: 1, right: 1, bottom: 1, left: 1 } , copies : copies  };
    }
    else if (printer== 'STRUK-RESEP'){
      printername = printer;
      options =  {  size: {width: 100, height: 140}, units: 'mm', orientation: 'portrait', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };
    }
    else if (printer == 'STRUK-KASIR'){
      printername = printer;
      if(papertipe == '210280'){
        options =  {  size: {width: 210, height: 280}, units: 'mm', orientation: 'portrait', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };
      }
      else if (papertipe == '210140'){
        options =  {  size: {width: 210, height: 140}, units: 'mm', orientation: 'landscape', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };
      }
    }
    else if (printer == 'SEP'){
      printername = printer;
      options =  {  size: {width: 210, height: 100}, units: 'mm', orientation: 'portrait', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };
    }
    else if (printer == 'STRUK-ANTRIAN'){
      printername = printer;
      if (papertipe == "7242" ){
        options =  {  size: {width: 72, height: 42}, units: 'mm', orientation: 'portrait', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };

      }else if(papertipe == "7282"){
        options =  {  size: {width: 72, height: 82}, units: 'mm', orientation: 'portrait', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };
      }
    }
    else if (printer == 'OFFICE'){
      printername = printer;
      if (papertipe == "F4" ){
        options =  {  size: {width: 215, height: 330}, units: 'mm', orientation: 'portrait', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };

      }else if(papertipe == "A5"){
        options =  {  size: {width: 210, height: 148}, units: 'mm', orientation: 'landscape', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };
      }
      else if(papertipe == "SEP"){
        options =  {  size: {width: 210, height: 100}, units: 'mm', orientation: 'landscape', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };
      }
    }
    else{
      printername = "OFFICE";
      if (papertipe == "F4" ){
        options =  {  size: {width: 215, height: 330}, units: 'mm', orientation: 'portrait', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };

      }else if(papertipe == "A5"){
        options =  {  size: {width: 210, height: 148}, units: 'mm', orientation: 'landscape', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };
      }
      else if(papertipe == "SEP"){
        options =  {  size: {width: 210, height: 100}, units: 'mm', orientation: 'landscape', margins: { top: 3, right: 1, bottom: 1, left: 5 } , copies : copies  };
      }

    }
    const config = qz.configs.create(printer, options);
   
    return fromPromise(qz.print(config, data))
    map((anything: any) => anything)
      , catchError(this.errorHandler);
  }
  async prinBlade(url: any, papertipe: string = "A4", copies: number = 1){
    const config = await this.setConfig(copies, papertipe)
    fetch(Configuration.get().apiBackend +  url, { /*method: 'POST'*/method: 'GET', headers: { 'token': this._httpService.token ,'iskiosk' : 'true'} })
      // .then((response) => response.arrayBuffer())
      .then((response) => response.blob())
      .then(async (blob) => {
  
        // debugger
        // ARRAY BUFFER
        // let binary = '';
        // const bytes = new Uint8Array(blob);
        // const len = bytes.byteLength;
        // for (let i = 0; i < len; i++) {
        //   binary += String.fromCharCode(bytes[i]);
        // }
        // const base64String = window.btoa(binary);
        //   let datas = [{
        //     type: 'pixel',
        //     format: 'pdf',
        //     flavor: 'base64',
        //     data: base64String
        // }];
  
  
        async function blobToBase64(blob: any) {
          return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = reject;
            reader.readAsDataURL(blob);
          });
        }
        let base64String :any= await blobToBase64(blob);
        base64String = base64String.split(',')[1]
        let datas: any = []
        if (blob.type == 'application/pdf') {
          datas = [{
            type: 'pixel',
            format: 'pdf',
            flavor: 'base64', // or 'plain' if the data is raw HTML
            data: base64String
          }];
        } else {
          datas = [{
            type: 'pixel',
            format: 'html',
            flavor: 'base64', // or 'plain' if the data is raw HTML
            data: base64String
          }];
        }
  
  
        // console.log(config)
        // console.log(base64String)
        // const printer = 'Microsoft Print to PDF'
        // const options = { size: { width: 210, height: 100 }, units: 'mm', orientation: 'landscape', margins: { top: 3, right: 1, bottom: 1, left: 5 }, copies: 1 };
        // const config = qz.configs.create(printer, options);
        try {
          qz.print(config, datas).catch((err: any) => {
            console.log(err)
            this._toastrService.error('QZ-Tray',err.message, {
              toastClass: 'toast ngx-toastr',
              closeButton: true,
              positionClass: 'toast-bottom-center'
            });
            const _url = window.URL.createObjectURL(blob);
            window.open(_url, '_blank').focus();
    
          });
        } catch (error) {
          const _url = window.URL.createObjectURL(blob);
          window.open(_url, '_blank').focus();

        }
      }).catch((err) => {
        this._toastrService.error('QZ-Tray',err.message, {
          toastClass: 'toast ngx-toastr',
          closeButton: true,
          positionClass: 'toast-bottom-center'
        });
     
        console.log(err);
      });
  }
  
  async getDeviceName(){
    try {
      let res = await qz.networking.devices()
      for(let i = 0; i < res.length; i++) {
        let info = res[i];
        return info.hostname 
      }
    } catch (error) {
      console.log(error);
      return ''
    }
   
  }
  async  setConfig   (copies: any, papertipe: any) {
    let printer = ''
    let options: { size: { width: number; height: number; } | { width: number; height: number; } | { width: number; height: number; } | { width: number; height: number; } | { width: number; height: number; }; units: string; orientation: string; margins: { top: number; right: number; bottom: number; left: number; } | { top: number; right: number; bottom: number; left: number; } | { top: number; right: number; bottom: number; left: number; } | { top: number; right: number; bottom: number; left: number; } | { top: number; right: number; bottom: number; left: number; }; copies: number; };
    let deviceNames = await this.getDeviceName()
    let response = await fetch(Configuration.get().apiBackend + `general/printer?device=${deviceNames}&namaexternal=${papertipe}`, { method: 'GET', headers: { 'token': this._httpService.token ,'iskiosk' : 'true'} })
  
    if (!response.ok) {
      this._toastrService.error('QZ-Tray', 'Gagal Mencari settingan Printer' , {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-bottom-center'
      });
      return
    }
  
    const con = await response.json();
  
    if (con.response.data.length == 0) {
      this._toastrService.error('QZ-Tray', 'Master Printer Belum disetting' , {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-bottom-center'
      });
    
      return
    }
    let configur = con.response.data[0]
    options = {
      size:
      {
        width: configur.width != null ? configur.width : 210,
        height: configur.height != null ? configur.height : 297,
      },
      units: 'mm',
      orientation: configur.orientation ? configur.orientation:'portrait',
      margins: { top: 1, right: 1, bottom: 1, left: 1 },
      copies: copies
    };
    if(configur.printerdefault){
      printer = configur.printerdefault
    }
    const config = qz.configs.create(printer, options);
    return config
    
  }
  private errorHandler(error: HttpErrorResponse) {
    if (error.error instanceof ErrorEvent) {
      console.log(error.error);
      console.log('An error occurred:', error.status);
      this._toastrService.error('', error.message , {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-bottom-center'
      });
      return throwError(error.error);
    } else {
      console.log('An error occurred:', error.status);
      console.log(error.error);
      this._toastrService.error('', error.message , {
        toastClass: 'toast ngx-toastr',
        closeButton: true,
        positionClass: 'toast-bottom-center'
      });
      return throwError(error.error);
    }
  };
}
