import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/app/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class PlatformService {

  constructor( private http: HttpClient) {
  
   }
   getPlatforms() {
    return this.http.get<any>(`${environment.apiUrl}/platforms`);
   }
}
