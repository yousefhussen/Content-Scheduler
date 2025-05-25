import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { AuthService } from '../shared/services/auth.service';
import { Observable } from 'rxjs';
import { map, filter, take, switchMap } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class GuestGuard implements CanActivate {

  constructor(private authService: AuthService, private router: Router) {}

  canActivate(): Observable<boolean> {
    return this.authService.initialCheckDone$.pipe(
      filter(done => done),
      take(1),
      switchMap(() =>
        this.authService.currentUser.pipe(
          map(user => {
            if (user) {
              this.router.navigate(['/']);
              return false;
            } else {
              return true;
            }
          })
        )
      )
    );
  }
}
