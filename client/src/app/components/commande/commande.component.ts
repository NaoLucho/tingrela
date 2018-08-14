import { Component, OnInit, AfterViewInit } from '@angular/core'
// import { DataService } from './../../services/data.service'
import { PaymentService } from './../../services/payment.service'
import { BasketService } from './../../services/basket.service'

import { NgModule } from '@angular/core';
// import { BrowserModule } from '@angular/platform-browser';
// import { FormsModule } from '@angular/forms';
import { Commande } from './../../objects/commande'
import { Router, NavigationExtras } from '@angular/router'

import { Observable } from 'rxjs/Rx'
import { Subscription } from 'rxjs/Subscription';

import { Fader } from './../../animations/fader.animation'

import { environment } from './../../../environments/environment';

// import * as $ from 'jquery'
// import {vicopo} from 'vicopo'
// import {vicopo, vicopoTargets, vicopoClean } from 'vicopo'
import { element } from 'protractor';
// declare var jquery:any;
// declare var vicopo:any;

@Component({
  selector: 'app-commande',
  templateUrl: 'commande.component.html',
  styleUrls: ['commande.style.scss'],
  animations: [Fader()]
})

export class CommandeComponent {
  loader = 'true';

  products;
  basketlist = []
  basket = [];
  totalHT = 0;
  handler: any
  tva = 0;
  totalTTC = 0;
  totalTva = 0;
  public commande: Commande = new Commande();
  // finishchanged;

  submitted = false;
  errorMessage: string = null;

  constructor(
    // private dataService: DataService,
    private paymentService: PaymentService,
    private basketService: BasketService,
    private router: Router
  ) {
    this.getBasket();
  }


  onSubmitCommande(value: Commande) {
    this.submitted = true;
    this.loader = 'true'
    // ici la Commande est enregistrée dans la variable commande via NgModel
    // Demander le service checkout pour de paiement:
    this.openCheckout();
  }

  openCheckout() {
    const _this = this; // Obligatoire pour avoir accès à notre class courante dans le handler.
    // let commandeinfo = this.commande;
    // let functP = this.sendPayment;


    this.handler = StripeCheckout.configure({
      key: environment.stripeapikey, // Clef définissant la connexion au compte Stripe, à changer pour la PROD
      locale: 'auto',
      currency: 'eur',
      allowRememberMe: false, // Désactivation de 'se souvenir de moi' puisque nous faisons payer le client en direct.
      name: 'Paiement de votre panier',
      email: this.commande.email,
      description: 'Sécurisé par Stripe.com',
      opened: function () {
        _this.loader = 'false'
      },
      token: function (token: any) {
        // You can access the token ID with `token.id`.
        // Get the token ID to your server-side code for use.

        // ICI Enregister la commande, puis rediriger vers le service de paiement.
        let resp: Observable<any>
        // _this.basketService.postBasket(10,3);

        resp = _this.paymentService.postPayment(token.id, _this.commande, _this.basket, _this.totalTTC)
        _this.loader = 'true'

        resp.subscribe(
          data => {
            if (data.dataValidated === true) {
              const ref = data.ref;
              const total = data.total;
              const navigationExtras: NavigationExtras = { queryParams: { reference: ref, total: total, email: _this.commande.email } };
              _this.router.navigate(['payment'], navigationExtras);
            } else {
              _this.errorMessage = 'Une erreur s\'est produite lors de la validation de votre achat référencé '
              + data.ref + '. Votre carte n\'a pas été débitée. Veuillez réessayer ou nous contacter si l\'erreur persiste';
            }
          },
          error => {
            if (error.status === 500) {
              _this.errorMessage = 'Une erreur s\'est produite. Votre carte n\'a pas été débitée. ' +
              'Veuillez ressayer ou nous contacter si le problème persiste.'
            } else {
              _this.errorMessage = 'Une erreur s\'est produite' +
              ' Veuillez vérifier votre connexion internet ou réessayer ultérieurement.'
            }
            _this.loader = 'false';
          },
          () => {
            _this.loader = 'false';
          }
        )

      }
    });

    this.handler.open({
      amount: this.totalTTC * 100// en centimes: 100 = 1€
    });
  }


  getBasket() {
    this.basketService
      .getBasketlistProducts() // this.basket)
      .subscribe(data => {
          if (data) {
            this.products = data;
            this.basket = this.basketService.getBasket();
            this.refreshTotal();
            this.loader = 'false';

            // Assign product qte from basket
            this.basket.forEach(basketItem => {
              this.products.forEach(product => {
                if (basketItem.id === product.id) {
                  product.qte = basketItem.qte
                };
              });
            });
          }
      }, err => /* console.log(err)); */alert('Il y a eu une erreur. Réferrez vous à l\'administrateur'))
  }

  refreshTotal() {
    this.totalTTC = this.basketService.getBasketPrice();
  }

  addclass(element, className) {

    element.addClass(className);

  }

}
