import { Observable, throwError } from 'rxjs';
import { map, catchError, tap } from 'rxjs/operators';
import { Injectable } from '@angular/core';
import {
	HttpInterceptor,
	HttpRequest,
	HttpResponse,
	HttpHandler,
	HttpEvent,
	HttpErrorResponse,
	HttpHeaders,
	HttpClient
} from '@angular/common/http';
import { MessageService } from 'primeng/api';
// import { TranslatorService } from './translator.service';
import { AuthService } from './auth.service';

@Injectable({
	providedIn: 'root'
})
export class HeaderInterceptorService implements HttpInterceptor {
	constructor(public messageService: MessageService,
		// public translate: TranslatorService,

		private http: HttpClient,
		) {

	}
	intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
		let tokenPATOK = '  eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJzdXBlcmFkbWluIiwiZXhwIjoxNjk1MzEwODk1fQ.lFG1vpl330bfzzLe2_mMiAv3rbXbILB3c_AuW1Aax6KxmJHyzKkRUJWChU1qpdTi-N2nhaZO1khpgZacKZX94w.MQ=='
		// if (localStorage.getItem('xTKNZers')) {
		// 	var xtkn = JSON.parse(localStorage.getItem('xTKNZers'))
		// 	tkn = xtkn.tokenReservasi
		// }
		let dataLoginUser: any

		dataLoginUser = {
			['token']: tokenPATOK, kdProfile: 1
			, id: 1
		}
		// if (localStorage.getItem('storageKiosk')) {
		// 	dataLoginUser = JSON.parse(localStorage.getItem('storageKiosk'))
		// } else if (sessionStorage.getItem('storageKiosk')) {
		// 	dataLoginUser = JSON.parse(sessionStorage.getItem('storageKiosk'))
		// } else {
		// 	dataLoginUser = false
		// }
		// if (dataLoginUser) {
		req = req.clone(
			{
				headers: new HttpHeaders({
					'token': dataLoginUser["token"],
					'kdProfile': dataLoginUser.kdProfile,
					'KdUser': dataLoginUser.id,
					'Content-Type': 'application/json',
					'Accept': 'application/json',
                    'iskiosk': 'true'
				})
			});

		// }

		return next.handle(req).pipe(
			map((event: HttpEvent<any>) => {
				if (event instanceof HttpResponse) {
					// console.log('event--->>>', event);
				}
				return event;
			}),
			catchError((error: HttpErrorResponse) => {
				// debugger
				if (error.status == 0) {
					// this.messageService.add({ key: 't-notif', severity: 'error', summary: 'Kesalahan', detail: 'Koneksi Server Terputus' });
				} else if (error.status == 401) {
					this.messageService.add({ key: 't-notif', severity: 'error', summary: 'Kesalahan', detail: 'Sesi berakhir' });
				} else if (error.status == 404) {
					this.messageService.add({ key: 't-notif', severity: 'error', summary: 'Kesalahan', detail: error.message });
				} else if (error.status == 500) {
					if (error.error.message) {
						this.messageService.add({ key: 't-notif', sticky: true, severity: 'error', summary: 'Kesalahan', detail: error.error.message });
					} else {
						this.messageService.add({ key: 't-notif', severity: 'error', summary: 'Kesalahan', detail: 'Terjadi kesalahan pada server' });
					}
				} else if (error.status == 400) {
					this.messageService.add({ key: 't-notif', severity: 'error', summary: 'Kesalahan', detail: error.error.messages });
				} else {
					console.log('error--->>>', error);
					this.messageService.add({ key: 't-notif', severity: 'error', summary: 'Info', detail: 'Terjadi Kesalahan' });
				}

				return throwError(error);
			}))
	}
	getJSON(): Observable<any> {
        return this.http.get<any>("assets/config.json")
			.pipe(
                tap((res: any) => {
				}),

            );
    }

}
