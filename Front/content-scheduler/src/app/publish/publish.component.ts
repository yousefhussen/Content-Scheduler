import { Component } from '@angular/core';

@Component({
  selector: 'app-publish',
  templateUrl: './publish.component.html',
  styleUrls: ['./publish.component.css']
})
export class PublishComponent {
  editorOpen = false;
  editingPost: any = null;

  openEditor(post: any = null) {
    this.editingPost = post;
    this.editorOpen = true;
  }

  closeEditor() {
    this.editorOpen = false;
    this.editingPost = null;
  }

  handleSave(postData: any) {
    // Save logic here (call your service, etc.)
    // For now, just log and close
    console.log('Post saved:', postData);
    this.closeEditor();
  }
}
