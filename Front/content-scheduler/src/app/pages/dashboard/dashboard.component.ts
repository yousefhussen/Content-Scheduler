// dashboard.component.ts
import { Component, OnInit } from '@angular/core';
import { PostService } from 'src/app/shared/services/post.service';
import { PlatformService } from 'src/app/shared/services/platform.service';
import { CalendarOptions, EventInput } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import listPlugin from '@fullcalendar/list';
@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {
  platformStats: any[] = [];
  successRate: number = 0;
  scheduledCount: number = 0;
  publishedCount: number = 0;
  scheduledToday: number = 0;
  draftCount: number = 0;
 




  constructor(
    private postService: PostService,
    private platformService: PlatformService
  ) { 

  }

  ngOnInit(): void {
    this.loadDashboardData();
    
  }





  

  private loadDashboardData(): void {
    this.postService.getAnalytics().subscribe({

      next: (data) => {
        this.platformStats = Object.entries(data.posts_per_platform).map(([platform, count]) => ({
          name: platform,
          count: count
        }));
        
        this.successRate = data.publishing_success_rate;
        this.scheduledCount = data.scheduled_count;
        this.publishedCount = data.published_count;
        this.draftCount = data.draft_count;
        this.scheduledToday = data.scheduled_today;
        this.animateValue('successRate', 0, data.publishing_success_rate);
        this.animateValue('scheduledCount', 0, data.scheduled_count);
        this.animateValue('publishedCount', 0, data.published_count);
        this.animateValue('scheduledToday', 0, data.scheduled_today);
      },
      error: (err) => console.error('Error loading analytics:', err)
    });
  }

  
  animateValue(property: string, start: number, end: number, duration: number = 2000) {
    const startTime = performance.now();
    const change = end - start;

    const easeOutCubic = (t: number) => 1 - Math.pow(1 - t, 3);

    const step = (currentTime: number) => {
      const elapsed = currentTime - startTime;
      const t = Math.min(elapsed / duration, 1);
      const easedT = easeOutCubic(t);
      (this as any)[property] = Math.round(start + change * easedT);

      if (t < 1) {
        requestAnimationFrame(step);
      } else {
        (this as any)[property] = end;
      }
    };

    requestAnimationFrame(step);
  }


}