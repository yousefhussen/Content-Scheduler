import { Component, OnInit } from '@angular/core';
import { PlatformService } from '../shared/services/platform.service';

interface Platform {
  id: number;
  name: string;
  iconUrl?: string;
}

@Component({
  selector: 'app-platforms',
  templateUrl: './platforms.component.html',
  styleUrls: ['./platforms.component.css']
})
export class PlatformsComponent implements OnInit {
  platforms: Platform[] = [];
  activePlatformIds: number[] = [];
  isLoading = true;
  error: string | null = null;

  constructor(private platformService: PlatformService) {}

  ngOnInit(): void {
    this.loadPlatforms();
  }

  loadPlatforms() {
    this.isLoading = true;
    this.platformService.getAllPlatforms().subscribe({
      next: (platforms) => {
        this.platforms = platforms;
        this.loadActivePlatforms();
      },
      error: (err) => {
        this.error = 'Failed to load platforms';
        this.isLoading = false;
      }
    });
  }

  loadActivePlatforms() {
    this.platformService.getUserActivePlatforms().subscribe({
      next: (platforms) => {
        // platforms is an array of platform objects with a pivot property
        this.activePlatformIds = platforms
          .filter((p: any) => p.pivot && p.pivot.is_active)
          .map((p: any) => p.id);
        this.isLoading = false;
      },
      error: () => {
        this.error = 'Failed to load user platforms';
        this.isLoading = false;
      }
    });
  }

  isActive(platformId: number): boolean {
    return this.activePlatformIds.includes(platformId);
  }

  togglePlatform(platformId: number) {
    const currentlyActive = this.isActive(platformId);
    this.platformService.setPlatformActive(platformId, !currentlyActive).subscribe({
      next: () => {
        if (currentlyActive) {
          this.activePlatformIds = this.activePlatformIds.filter(id => id !== platformId);
        } else {
          this.activePlatformIds.push(platformId);
        }
      },
      error: () => {
        this.error = 'Failed to update platform status';
      }
    });
  }
}