import { Component, OnInit } from '@angular/core';
import { PostService } from '../shared/services/post.service';

@Component({
  selector: 'app-logs',
  templateUrl: './logs.component.html',
  styleUrls: ['./logs.component.css']
})
export class LogsComponent implements OnInit {
  logs: any[] = [];
  isLoading = false;
  currentPage = 1;
  meta: any = {};

  constructor(private postService: PostService) {}

  ngOnInit() {
    this.loadLogs(this.currentPage);
  }

  loadLogs(page: number) {
    this.isLoading = true;
    this.postService.getLogs(page).subscribe({
      next: (res) => {
        this.logs = res.data;
        this.meta = res.meta;
        this.currentPage = page;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }

  goToPage(page: number) {
    if (page >= 1 && page <= this.meta.last_page && page !== this.currentPage) {
      this.loadLogs(page);
    }
  }
}