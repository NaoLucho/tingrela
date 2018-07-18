import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { AuthenticationService } from '../../authentication/authentication.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss']
})
export class AdminHomeComponent{

  constructor(private authenticationService: AuthenticationService, private router: Router) {}

    hasAuthToken() {
        return localStorage.getItem('token') !== null;
    }

    logout() {
         this.authenticationService.logout();
             this.router.navigate(['home']);
    }
}
