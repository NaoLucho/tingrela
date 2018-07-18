// authentication/authentication.component.ts
import { Component } from '@angular/core';
import { FormGroup, FormControl, Validators, FormBuilder }  from '@angular/forms';
import {FormsModule,ReactiveFormsModule} from '@angular/forms';
import { Router } from '@angular/router';

import { AuthenticationService } from './authentication.service';

@Component({
  selector: 'app-authentication',
  templateUrl: './authentication.component.html',
})
export class AuthenticationComponent {
    loginForm: FormGroup;
    error: string = '';

    constructor(
        private formBuilder: FormBuilder,
        private authenticationService: AuthenticationService,
        private router: Router
    ) {
        this.loginForm = formBuilder.group({
            'username': ['', Validators.required],
            'password': ['', Validators.required]
        });
    }

    onSubmit() {
        this.authenticationService
            .authenticate(this.loginForm.value)
            .subscribe(
                data => {
                    localStorage.setItem('token', data.token);
                    this.router.navigate(['accueil']);
                },
                error => this.error = error.message
            );
    }
}
