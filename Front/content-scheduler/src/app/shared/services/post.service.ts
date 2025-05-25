import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { DashboardAnalytics } from '../models/dashboard-analytics.interface';
import { Post } from '../models/post.interface';
import { environment } from 'src/app/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class PostService {

  constructor(private http: HttpClient) {}

  getAnalytics() {
    return this.http.get<DashboardAnalytics>(`${environment.apiUrl}/analytics/posts`);
  }

  getPosts() {
    return this.http.get<{data: Post[]}>( `${environment.apiUrl}/posts`);
  }
}
