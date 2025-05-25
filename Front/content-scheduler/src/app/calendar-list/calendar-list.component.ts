import { Component } from '@angular/core';
import { CalendarOptions, EventInput } from '@fullcalendar/core';
import { PostService } from '../shared/services/post.service';
import { PlatformService } from '../shared/services/platform.service';
import dayGridPlugin from '@fullcalendar/daygrid';
import listPlugin from '@fullcalendar/list';
import { Post } from '../shared/models/post.interface';
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
  editPost: any = null; // The post being edited
  showPostEditor: boolean = false; // Control the modal visibility  


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
    eventClick: this.onEventClick.bind(this),
    eventClassNames: () => ['cursor-pointer'],

  };


  constructor(
    private postService: PostService,
    private platformService: PlatformService
  ) {
    this.platformService.getAllPlatforms().subscribe({
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

  onEditEvent(post: any) {
    if (post.status === 'scheduled' || post.status === 'draft') {
      this.editPost = post;
      console.log(this.editPost);
      this.showPostEditor = true;
    }
  }

  onPostEditorSave(updatedPost: any) {
    this.showPostEditor = false;
    this.editPost = null;
    this.loadCalendarEvents();
  }
  onPostEditorCancel() {
    this.showPostEditor = false;
    this.editPost = null;
  }

  onPublishEvent(post: any) {
    if (post.status === 'scheduled' || post.status === 'draft') {
      this.postService.publishPost(post.id).subscribe({
        next: () => {
          // Refresh posts/calendar after publishing
          this.loadCalendarEvents();
        },
        error: (err) => alert('Failed to publish post: ' + err.message)
      });
    }
  }

  postToFormData(post: any): FormData {
    const formData = new FormData();
    formData.append('id', post.id);
    formData.append('title', post.title);
    formData.append('message_content', post.message_content);
    formData.append('status', post.status);
    formData.append('scheduled_time', post.scheduled_time);
    post.platforms.forEach((id: number) => {
      formData.append('platforms[]', id.toString());
    });
    // Optionally add images if needed
    return formData;
  }


  protected filteredPosts(): any[] {

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
        platforms: post.platforms,
        id: post.id,
        message_content: post.message_content,
        scheduled_time: post.scheduled_time,
      },
      color: this.getEventColor(post.status)
    }));
  }

  getEventColor(status: string): string {
    switch (status) {
      case 'published':
        return '#8A0064';
      case 'scheduled':
        return '#FFC0CB';
      case 'draft':
        return '#D3D3D3';
      default:
        return '#000000';
    }
  }

  onEventClick(arg: any) {
    // Use the ID to find the full post object from allPosts
    const post = this.allPosts.find(p => p.id === arg.event.extendedProps.id);
    if (post) {
      this.selectedEvent = post;
    } else {
      // fallback: use whatever is available
      this.selectedEvent = {
        ...arg.event.extendedProps,
        title: arg.event.title,
        start: arg.event.start,
      };
    }
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

  onDeleteEvent(post: any) {
    if (confirm('Are you sure you want to delete this post?')) {
      this.postService.deletePost(post.id).subscribe({
        next: () => {
          this.loadCalendarEvents();
          // Optionally, close any popup/modal
          this.showEventPopup = false;
        },
        error: (err) => alert('Failed to delete post: ' + err.message)
      });
    }
  }
}


