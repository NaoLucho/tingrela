import { Component } from '@angular/core'
import { Router } from '@angular/router'
import { trigger, state, style, transition, query, animate } from '@angular/animations'

import { AuthenticationService } from '../../authentication/authentication.service';

@Component({
	selector: 'my-header',
    templateUrl: 'header.component.html',
    animations: [
        trigger('headerAnimation', [
           state('true' , style({ opacity: 1 })), 
            state('false', style({ opacity: 0 })),
            transition('* => *', animate('300ms'))
		])
    ]
})

export class HeaderComponent {

    private headerImg = 'false'

    headerImgLoaded() {
        this.headerImg = 'true';
    }

  constructor(private authenticationService: AuthenticationService, private router: Router) {}

    hasAuthToken() {
        return localStorage.getItem('token') !== null;
    }

    logout() {
         this.authenticationService.logout();
             this.router.navigate(['home']);
    }
}
