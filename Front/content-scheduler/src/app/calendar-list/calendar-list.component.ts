import { Component } from '@angular/core';
import { CalendarOptions, EventInput } from '@fullcalendar/core';
import { PostService } from '../shared/services/post.service';
import { PlatformService } from '../shared/services/platform.service';
import dayGridPlugin from '@fullcalendar/daygrid';
import listPlugin from '@fullcalendar/list';
@Component({
  selector: 'app-calendar-list',
  templateUrl: './calendar-list.component.html',
  styleUrls: ['./calendar-list.component.css']
})
export class CalendarListComponent {
  calendarEvents: EventInput[] = [];
  selectedEvent: any = null;
  showEventPopup: boolean = false;
  activeView: 'calendar' | 'list' = 'calendar';
  filterStatus: string = '';
  filterPlatform: string = '';
  availablePlatforms: string[] = [];
  allPosts: any[] = [];
  isLoading: boolean = false;


  calendarOptions: CalendarOptions = {
    initialView: 'dayGridMonth',
    plugins: [dayGridPlugin, listPlugin],
    eventColor: '#8A0064',
    eventTextColor: '#ffffff',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,dayGridWeek,dayGridDay'
    },
    eventClick: this.onEventClick.bind(this) // Add this line

  };
  
  
  constructor(
    private postService: PostService,
    private platformService: PlatformService
  ) { 
    this.platformService.getPlatforms().subscribe({
      next: (data) => {
        this.availablePlatforms = data.map((p: any) => p.name);
        console.log(this.availablePlatforms);
      },
      error: (err) => console.error('Error loading platforms:', err)
    });
  }

  ngOnInit(): void {
    this.loadCalendarEvents();
  }
  
  onEditEvent(event: any) {
    // Navigate to edit page or open an edit modal
    // Example: this.router.navigate(['/edit', event.id]);
    alert('Edit event: ' + event.title);
  }

  onPublishEvent(event: any) {
    // Call a service to publish the event
    // Example: this.postService.publishEvent(event.id).subscribe(...)
    alert('Publish event: ' + event.title);
  }

  protected filteredPosts(): any[] {
    console.log(this.allPosts);
    return this.allPosts.filter(post => {
      const matchesStatus = this.filterStatus === '' || post.status === this.filterStatus;
      const matchesPlatform = this.filterPlatform === '' || post.platforms.some((p: any) => p.name === this.filterPlatform);
      return matchesStatus && matchesPlatform;
    });
  }

  updateCalendarEvents() {
    this.calendarEvents = this.filteredPosts().map(post => ({
      title: post.title,
      start: post.scheduled_time,
      end: post.scheduled_time,
      extendedProps: {
        status: post.status,
        platforms: post.platforms
      }
    }));
  }

  onEventClick(arg: any) {
    // Create a new object that combines title, start, and all extendedProps
    this.selectedEvent = {
      ...arg.event.extendedProps,
      title: arg.event.title,
      start: arg.event.start
    };
    this.showEventPopup = true;
  }

  private loadCalendarEvents(): void {
    this.isLoading = true;
    this.postService.getPosts().subscribe({
      next: (posts) => {
        this.allPosts = posts.data;
        this.updateCalendarEvents();
        this.isLoading = false;
      },
      error: (err) => console.error('Error loading calendar events:', err)
    });


  }
}
    

