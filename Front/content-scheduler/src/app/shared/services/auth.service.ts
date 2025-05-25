import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable, of } from 'rxjs';
import { tap } from 'rxjs/operators';
import { environment } from 'src/app/environments/environment';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  resetPassword(payload: { token: string; email: string; password: string ; password_confirmation: string }): Observable<any> {
    return this.http.post(`${this.apiUrl}/reset-password`, payload);
  }
  getUserProfile(): Observable<any> {
    const cachedProfile = localStorage.getItem('userProfile');

    if (cachedProfile) {
      // Return the cached profile as an Observable
      return of(JSON.parse(cachedProfile));
    } else {
      // Fetch the profile from the API and store it in local storage
      return this.http.get<{ profile_picture: string }>(`${this.apiUrl}/user`).pipe(
        tap((profile) => {
          localStorage.setItem('userProfile', JSON.stringify(profile));
        })
      );
    }
  }

  private apiUrl: string = environment.apiUrl;
  public currentUserSubject = new BehaviorSubject<any>(null);
  public initialCheckDone = new BehaviorSubject<boolean>(false);
  public initialCheckDone$ = this.initialCheckDone.asObservable();

  constructor(private http: HttpClient, private router: Router) {
    this.checkAuthentication();
  }

  get currentUser(): Observable<any> {
    return this.currentUserSubject.asObservable();
  }

  private storeToken(tokenResponse: any): void {
    localStorage.setItem('access_token', tokenResponse.access_token);
    // Store expiration time
    const expiresIn = tokenResponse.expires_in || 3600;
    const expiresAt = Date.now() + expiresIn * 1000;
    localStorage.setItem('expires_at', expiresAt.toString());
  }

  private removeToken(): void {
    localStorage.removeItem('access_token');
    localStorage.removeItem('expires_at');
  }

  private isTokenExpired(): boolean {
    const expiresAt = localStorage.getItem('expires_at');
    return expiresAt ? Date.now() > parseInt(expiresAt, 10) : true;
  }

  googleLogin() {
    window.location.href = `${environment.apiUrl}/auth/google`;
  }

  callback(): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/auth/google/callback`, {}).pipe(
      tap((response) => {
        
        this.checkAuthentication();
      })
    );
  }

  exchangeToken(token: string): Observable<any> {
    return this.http
      .post<any>(`${this.apiUrl}/auth/exchange-token`, { token })
      .pipe(
        tap((response) => {
          
          this.checkAuthentication();
        })
      );
  }

  login(email: string, password: string): Observable<any> {
    return this.http
      .post<any>(`${this.apiUrl}/login`, { email, password })
      .pipe(
        tap((response) => {
          
          this.checkAuthentication();
        })
      );
  }

  register(
    name: string,
    email: string,
    password: string,
    confirmPassword: string,
    profile_picture: File | null = null
  ): Observable<any> {
    const formData = new FormData();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('password_confirmation', confirmPassword);

    if (profile_picture) {
      formData.append('profile_picture', profile_picture, profile_picture.name);
    }

    return this.http.post<any>(`${this.apiUrl}/register`, formData).pipe(
      tap((response) => {
        
        this.checkAuthentication();
      })
    );
  }

  forgotPassword(email: string) {
    return this.http
      .post<any>(`${this.apiUrl}/forgot-password`, { email })
      .pipe(
        tap((response) => {
          
          this.checkAuthentication();
        })
      );
  }

  logout(): Observable<any> {
    return this.http.post<any>(`${this.apiUrl}/logout`, {}).pipe(
      tap(() => {
        this.removeToken();
        this.currentUserSubject.next(null);
        //remove from local storage
        localStorage.removeItem('userProfile');
        this.router.navigate(['/login']);

      })
    );
  }

  isAuthenticated(): boolean {
    if (environment.token) {

      return !!localStorage.getItem('access_token') && !this.isTokenExpired();
    }
    else{
      return this.currentUserSubject.value !== null;
    }

  }

  public checkAuthentication(): void {
    console.log('Checking authentication...');

    if (environment.token && this.isTokenExpired()) {
      this.removeToken();
      this.currentUserSubject.next(null);
      this.initialCheckDone.next(true);
      return;
    }

    this.http.get<any>(`${this.apiUrl}/user`).subscribe(
      (user) => {
        console.log('User authenticated via session:', user);

        this.currentUserSubject.next(user);
        this.initialCheckDone.next(true);
      },
      () => {
        this.removeToken();
        this.currentUserSubject.next(null);
        this.initialCheckDone.next(true);
      }
    );
  }
}
