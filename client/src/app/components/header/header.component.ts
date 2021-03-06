import { Component } from '@angular/core'
import { Router } from '@angular/router'
import { trigger, state, style, transition, animate } from '@angular/animations'

import { AuthenticationService } from '../../authentication/authentication.service';

@Component({
  selector: 'app-header',
  templateUrl: 'header.component.html',
  styleUrls: ['header.style.scss'],
  animations: [
    trigger('headerAnimation', [
      state('true', style({ opacity: 1 })),
      state('false', style({ opacity: 0 })),
      transition('* => *', animate('300ms'))
    ])
  ]
})
export class HeaderComponent {
  public headerImg = 'false';
  public menu = false;

  headerImgLoaded() {
    this.headerImg = 'true';
  }

  constructor(
    private authenticationService: AuthenticationService,
    private router: Router
  ) {}

  hasAuthToken() {
    return localStorage.getItem('token') !== null;
  }

  logout() {
    this.authenticationService.logout();
    this.router.navigate(['home']);
  }

  showMenu() {
    if(this.menu === false) {
        this.menu = true
    } else {
        this.menu = false
    }
  }
}
