import { Directive, ElementRef, Input, Output, EventEmitter, AfterViewInit } from '@angular/core';

@Directive({
  selector: '[appIntersectionObserver]'
})
export class IntersectionObserverDirective implements AfterViewInit {
  @Input() threshold: number = 0.2; // Default threshold
  @Output() visible = new EventEmitter<void>(); // Emits when the element becomes visible

  constructor(private element: ElementRef) {}

  ngAfterViewInit(): void {
    const observer = new IntersectionObserver(([entry]) => {
      if (entry.isIntersecting) {
        this.visible.emit(); // Emit the event when the element is visible
        observer.disconnect(); // Stop observing after the element is visible
      }
    }, { threshold: this.threshold });

    observer.observe(this.element.nativeElement);
  }
}