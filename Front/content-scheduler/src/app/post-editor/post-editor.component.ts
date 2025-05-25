import { Component, Input, Output, EventEmitter, OnInit, OnChanges, SimpleChanges } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

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
  onPlatformsInput(event: Event) {
    const input = event.target as HTMLInputElement | null;
    const value = input?.value ?? '';
    this.postForm.patchValue({
      platforms: value.split(',').map(s => s.trim()).filter(s => s)
    });
  }
  @Input() post: any = null; // post object for editing, or null for new
  @Output() save = new EventEmitter<any>();
  @Output() cancel = new EventEmitter<void>();

  availablePlatforms: string[] = ['Facebook', 'Twitter', 'Instagram', 'LinkedIn', 'TikTok']; // Replace with your real list


  postForm: FormGroup;

  constructor(private fb: FormBuilder) {
    this.postForm = this.fb.group({
      title: ['', Validators.required],
      content: ['', Validators.required],
      platforms: [[], Validators.required],
      scheduled_time: [{ value: '', disabled: true }, Validators.required],
      status: ['published', Validators.required] // default to "Post Immediately"
    });
  }

  ngOnInit() {
  
    if (this.post) {
      this.postForm.patchValue({
        ...this.post,
        platforms: Array.isArray(this.post.platforms) ? this.post.platforms : []
      });
    }
  }

  ngOnChanges(changes: SimpleChanges) {
    if (changes['post'] && changes['post'].currentValue) {
      this.postForm.patchValue(this.post);
    } else if (changes['post'] && !changes['post'].currentValue) {
      this.postForm.reset({
        title: '',
        content: '',
        platforms: [],
        scheduled_time: '',
        status: 'draft'
      });
    }
  }

  onPlatformToggle(platform: string, event: Event) {
    const checked = (event.target as HTMLInputElement).checked;
    const selected = this.postForm.value.platforms || [];
    if (checked) {
      if (!selected.includes(platform)) {
        this.postForm.patchValue({ platforms: [...selected, platform] });
      }
    } else {
      this.postForm.patchValue({ platforms: selected.filter((p: string) => p !== platform) });
    }
  }

  onSave() {
    if (this.postForm.valid) {
      const formValue = this.postForm.value;
      // If editing, preserve the id
      if (this.post && this.post.id) {
        formValue.id = this.post.id;
      }
      this.save.emit(formValue);
    } else {
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