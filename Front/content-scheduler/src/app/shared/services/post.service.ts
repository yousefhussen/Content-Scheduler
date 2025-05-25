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

  publishPost(id: number) {
    return this.http.put<{data: Post }>(`${environment.apiUrl}/posts/${id}/publish`, {} );
  }


  savePost(post: FormData) {
    return this.http.post<{data: Post}>(`${environment.apiUrl}/posts`, post );
  }
  updatePost(id: number, post: FormData) {
    return this.http.post<{data: Post}>(`${environment.apiUrl}/posts/${id}`, post );
  }

  getLogs(page: number = 1) {
    return this.http.get<any>(`${environment.apiUrl}/activitylog?page=${page}`);
  }

  deletePost(id: number) {
    return this.http.delete<{message: string}>(`${environment.apiUrl}/posts/${id}`);
  }
}
