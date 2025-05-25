import { Injectable } from '@angular/core';
import { HttpInterceptor, HttpRequest, HttpHandler, HttpEvent, HttpClient, HttpErrorResponse, HttpResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError, switchMap, tap } from 'rxjs/operators';
import { environment } from 'src/app/environments/environment';
import { NotificationService } from 'src/app/shared/services/notification.service';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

  constructor(private http: HttpClient, private notificationService: NotificationService) {}

  intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
    // Skip the interceptor for the CSRF token request itself
    if (req.url.endsWith('/sanctum/csrf-cookie')) {
      return next.handle(req);
    }
    if (req.url.startsWith('https://lh3.googleusercontent.com') ) {
      return next.handle(req);
    }

    // Get the CSRF token from cookies
    const csrfToken = this.getCookie('XSRF-TOKEN');

    if (csrfToken) {

      // Clone the request to add the new headers
      let authReq = req.clone({
        withCredentials: true, // Include cookies
        setHeaders: {

          'Accept': 'application/json',
          'X-XSRF-TOKEN': decodeURIComponent(csrfToken)
        }
      });

      // Send the newly created request
      return next.handle(authReq).pipe(
        tap(event => {
          // Handle success responses

        }),
        catchError((error: HttpErrorResponse) => {
          //don't show unauthorized notifaction error
          if (error.status === 401) {
            // Redirect to login page
          }
          else{
            this.notificationService.showError(error.error.message);
          }

          return throwError(error);
        })
      );
    } else {
      // If CSRF token is not set, make a request to get it
      return this.http.get(environment.apiUrl + '/sanctum/csrf-cookie', { withCredentials: true }).pipe(
        switchMap(() => {
          // Retry the original request with the new CSRF token
          const newCsrfToken = this.getCookie('XSRF-TOKEN');
          if (!newCsrfToken) {
            return throwError('Failed to retrieve CSRF token.');
          }
          let authReq = req.clone({
            withCredentials: true, // Include cookies
            setHeaders: {

              'Accept': 'application/json',
              'X-XSRF-TOKEN': decodeURIComponent(newCsrfToken)
            }
          });
          return next.handle(authReq).pipe(
            tap(event => {
              // Handle success responses
              if (event instanceof HttpResponse) {
                this.notificationService.showSuccess(event.body.message);
              }
            }),
            catchError((error: HttpErrorResponse) => {
              // Handle error responses
              this.notificationService.showError(error.error.message);
              return throwError(error);
            })
          );
        }),
        catchError(error => {
          this.notificationService.showError('Error fetching CSRF token');
          return throwError(error);
        })
      );
    }
  }

  // Helper method to get a cookie by name
  private getCookie(name: string): string | null {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }
}
