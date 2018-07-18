// authentication/authentication.service.ts
import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions, URLSearchParams } from '@angular/http';
import { tokenNotExpired } from 'angular2-jwt';

import 'rxjs/add/operator/map';

@Injectable()
export class AuthenticationService {

  constructor(private http: Http) {}

  authenticate(user: any) {
    let url     = 'http://localhost/GJchoc/server/web/app_dev.php/admin/login_check';
    let body     = new URLSearchParams();
    body.append('username', user.username);
    body.append('password', user.password);
    let headers = new Headers({'Content-Type': 'application/x-www-form-urlencoded'});
    let options = new RequestOptions({headers: headers});

    return this.http
    .post(url, body.toString(), options)
    .map((data: Response) => data.json());
  }

  logout() {
    localStorage.removeItem('token');
  }

  loggedIn() {
    /* console.log("canActivate: [AuthGuard] Needed, and authentication service loggedIn: " + tokenNotExpired()); */
    return tokenNotExpired();
  }
}
