import { Router } from '@angular/router';
import { AuthService } from 'src/app/shared/services/auth.service';
import { Component, ViewChild, ElementRef, HostListener } from '@angular/core';
import { trigger, state, style, transition, animate } from '@angular/animations';


@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css'],
  animations: [
    trigger('slideInOut', [
      state('open', style({
        transform: 'translateX(0%)'
      })),
      state('closed', style({
        transform: 'translateX(100%)'
      })),
      transition('open <=> closed', [
        animate('300ms ease-in-out')
      ])
    ]),

    trigger('expandSearch', [
      state('collapsed', style({
        width: '0',
        opacity: 0,
        height: 0,
      })),
      state('expanded', style({
        width: '100%',
        opacity: 1,
        height: '65px',
      })),
      transition('collapsed => expanded', [
        animate('300ms cubic-bezier(0.4, 0.0, 0.2, 1)')
      ]),
      transition('expanded => collapsed', [
        animate('250ms cubic-bezier(0.4, 0.0, 0.2, 1)')
      ])
    ]),

    trigger('fadeOut', [
      state('visible', style({
        opacity: 1,
      })),
      state('hidden', style({
        opacity: 0,
      })),
      transition('visible <=> hidden', [
        animate('300ms ease-in-out')
      ])
    ]),

    trigger('slideSubmenu', [
      transition(':enter', [
        style({ height: 0, opacity: 0 }),
        animate('200ms ease-out', style({ height: '*', opacity: 1 }))
      ]),
      transition(':leave', [
        animate('200ms ease-in', style({ height: 0, opacity: 0 }))
      ])
    ])
  ]
})
export class NavbarComponent {

logout() {
  this.authService.logout().subscribe(
    () => {
      // Handle successful logout
      console.log('Logged out successfully');
    },
    (error: any) => {
      // Handle error
      console.error('Logout error:', error);
    }
  );
}
  @ViewChild('searchInput') searchInput!: ElementRef;
  @ViewChild('leftSection') leftSection!: ElementRef;
  @ViewChild('rightSection') rightSection!: ElementRef;
  isSearchExpanded = false;
  isMenuOpen = false;
  isCategoriesOpen = false;
  isProfileSubmenuOpen = false;





  constructor(protected authService: AuthService,protected router: Router) {
    // Check if the user is logged in and set the profile picture URL
    this.authService.getUserProfile().subscribe((user) => {
      console.log('User profile:', user);

    });
  }




  toggleMenu() {
    this.isMenuOpen = !this.isMenuOpen;
    // Close categories when closing menu
    if (!this.isMenuOpen) this.isCategoriesOpen = false;
  }

  toggleCategories() {
    this.isCategoriesOpen = !this.isCategoriesOpen;
  }

  // Add this to close categories when clicking outside (optional)
  closeCategories() {
    if (this.isCategoriesOpen) this.isCategoriesOpen = false;
  }

  toggleSearch() {
    this.isSearchExpanded = !this.isSearchExpanded;
    // Close menu and categories when searching
    if (this.isMenuOpen) this.isMenuOpen = false;
    if (this.isCategoriesOpen) this.isCategoriesOpen = false;
  }


  onDocumentClick(event: MouseEvent) {
    const target = event.target as HTMLElement;
    if (!target.closest('.profile-menu')) {
      this.isProfileSubmenuOpen = false;
    }
  }
    // Toggle submenu visibility
    toggleSubmenu() {
      console.log('Submenu toggled:', this.isProfileSubmenuOpen);
      this.isProfileSubmenuOpen = !this.isProfileSubmenuOpen;
      console.log('Submenu state:', this.isProfileSubmenuOpen);



    }

}
