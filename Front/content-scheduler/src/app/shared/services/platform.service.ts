import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { environment } from 'src/app/environments/environment';

@Injectable({ providedIn: 'root' })
export class PlatformService {
  

  constructor(private http: HttpClient) {}

  getAllPlatforms(): Observable<any[]> {
    return this.http.get<any[]>(`${environment.apiUrl}/platforms/`);
  }

  getUserActivePlatforms(): Observable<any[]> {
    return this.http.get<any[]>(`${environment.apiUrl}/platforms/user`);
  }

  setPlatformActive(platformId: number, active: boolean): Observable<any> {
    return this.http.post(`${environment.apiUrl}/platforms/SetStatus`, { platform_id: platformId, is_active: active });
  }
}