import { Component, OnInit } from '@angular/core';
import { DataService } from './../../services/data.service'
import { NgModule } from '@angular/core';
// import { BrowserModule } from '@angular/platform-browser';
// import { FormsModule } from '@angular/forms';
import { Commande } from './../../objects/commande'
import { ActivatedRoute } from "@angular/router";

@Component({
  selector: 'my-payment',
  templateUrl: 'payment.component.html',
  styleUrls: ['payment.style.scss']

})

export class PaymentComponent {
  amount: number = 100; //en centimes: 100 = 1€
  reference: string = '';

  public constructor(private route: ActivatedRoute) {
    this.route.queryParams.subscribe(params => {
      this.reference = params["reference"];
    });
    if (this.reference) {
      /* console.log(this.reference) */
    }
  }

}
