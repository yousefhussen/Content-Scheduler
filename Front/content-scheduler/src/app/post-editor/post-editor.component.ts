import { Component, Input, Output, EventEmitter, OnInit, OnChanges, SimpleChanges } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { PlatformService } from '../shared/services/platform.service';
import { PostService } from '../shared/services/post.service';
@Component({
  selector: 'app-post-editor',
  templateUrl: './post-editor.component.html',
  styleUrls: ['./post-editor.component.css']
})
export class PostEditorComponent implements OnInit, OnChanges {
  statusOptions: any[] = [
    { value: 'published', label: 'Published' },
    { value: 'scheduled', label: 'Scheduled' },
    { value: 'draft', label: 'Draft' }
  ];

  @Input() post: any = null; // post object for editing, or null for new
  @Output() save = new EventEmitter<any>();
  @Output() cancel = new EventEmitter<void>();

  availablePlatforms: { id: number, name: string }[] = [];
  postForm: FormGroup;
  selectedImages: File[] = [];
  imagePreviewUrls: string[] = [];
  serverImageUrls: string[] = [];
  originalScheduledTime: string | null = null;


  constructor(
    private fb: FormBuilder,
    private platformService: PlatformService,
    private postService: PostService
  ) {
    this.platformService.getUserActivePlatforms().subscribe((data: any) => {
      // Expecting each platform to have id and name
      this.availablePlatforms = data.map((platform: any) => ({
        id: platform.id,
        name: platform.name
      }));
    });

    this.postForm = this.fb.group({
      title: ['', Validators.required],
      message_content: ['', Validators.required],
      platforms: [[], Validators.required], // store array of platform IDs
      scheduled_time: [{ value: '', disabled: true }, Validators.required],
      status: ['published', Validators.required], // default to "Post Immediately"
      images: [null] // optional image
    });
  }

  ngOnInit() {
    if (this.post) {
      this.postForm.patchValue({
        ...this.post,
        platforms: Array.isArray(this.post.platforms)
          ? this.post.platforms.map((p: any) => typeof p === 'object' ? p.id : p)
          : []
      });
      if (this.post.images) {
        this.imagePreviewUrls = this.post.images;
      }
      if (this.post.image_url) {
        this.serverImageUrls = this.post.image_url.split(',').map((url: any) => url.trim());
      }
    }
  }

  ngOnChanges(changes: SimpleChanges) {
    if (changes['post'] && changes['post'].currentValue) {
      this.originalScheduledTime = this.post.scheduled_time || null;
      this.postForm.patchValue({
        ...this.post,
        platforms: Array.isArray(this.post.platforms)
          ? this.post.platforms.map((p: any) => typeof p === 'object' ? p.id : p)
          : []
      });
      if (this.post.images) {
        this.imagePreviewUrls = this.post.images;
      }
      if (this.post.image_url) {
        this.serverImageUrls = this.post.image_url.split(',').map((url: any) => url.trim());
      } else {
        this.serverImageUrls = [];
      }
    } else if (changes['post'] && !changes['post'].currentValue) {
      this.postForm.reset({
        title: '',
        message_content: '',
        platforms: [],
        scheduled_time: '',
        status: 'draft',
        images: []
      });
      this.selectedImages = [];
      this.imagePreviewUrls = [];
    }
  }

  onPlatformToggle(platformId: number, event: Event) {
    const checked = (event.target as HTMLInputElement).checked;
    const selected: number[] = this.postForm.value.platforms || [];
    if (checked) {
      if (!selected.includes(platformId)) {
        this.postForm.patchValue({ platforms: [...selected, platformId] });
      }
    } else {
      this.postForm.patchValue({ platforms: selected.filter((id: number) => id !== platformId) });
    }
  }

  onImagesSelected(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files.length > 0) {
      this.selectedImages = Array.from(input.files);
      this.postForm.patchValue({ images: this.selectedImages });

      // For preview
      this.imagePreviewUrls = [];
      for (const file of this.selectedImages) {
        const reader = new FileReader();
        reader.onload = (e: any) => {
          this.imagePreviewUrls.push(e.target.result);
        };
        reader.readAsDataURL(file);
      }
    } else {
      this.selectedImages = [];
      this.imagePreviewUrls = [];
      this.postForm.patchValue({ images: [] });
    }
  }

  onSave() {

    if (this.postForm.valid) {
      const formValue = this.postForm.value;
      if (this.post && this.post.id) {
        formValue.id = this.post.id;
      }

      const formData = new FormData();
      formData.append('title', formValue.title);
      formData.append('message_content', formValue.message_content);
      formData.append('status', formValue.status);

      let scheduledTime = '';
      if (
        this.originalScheduledTime &&
        (!formValue.scheduled_time || formValue.scheduled_time === '')
      ) {
        scheduledTime = this.originalScheduledTime;
      } else if (formValue.scheduled_time) {
        scheduledTime = formValue.scheduled_time.replace('T', ' ') + ':00';
      }

      if (scheduledTime.length > 0) {
        formData.append('scheduled_time', scheduledTime);
      }
      formValue.platforms.forEach((id: number) => {
        formData.append('platforms[]', id.toString());
      });
      // Append all images
      this.selectedImages.forEach((file, idx) => {
        formData.append('images[]', file, file.name);
      });

      // CHOOSE THE CORRECT SERVICE METHOD
      let request$;
      if (this.post && this.post.id) {
        // EDIT/UPDATE
        console.log('Updating post', this.post.id);
        request$ = this.postService.updatePost(this.post.id, formData);
      } else {
        // CREATE/ADD
        console.log('Creating post');
        request$ = this.postService.savePost(formData);
      }

      request$.subscribe({
        next: () => {
          this.postForm.reset();
          this.selectedImages = [];
          this.imagePreviewUrls = [];
          this.save.emit(formValue);
        },
      });
    } else {
      console.log(this.postForm.errors);
      this.postForm.markAllAsTouched();
    }

  }

  onStatusChange(status: string) {
    if (status !== 'scheduled') {
      this.postForm.get('scheduled_time')?.disable();
      this.postForm.patchValue({ scheduled_time: '' });
    } else {
      this.postForm.get('scheduled_time')?.enable();
    }
  }

  onCancel() {
    this.cancel.emit();
  }
}