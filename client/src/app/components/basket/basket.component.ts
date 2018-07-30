import { Component } from '@angular/core'
import { BasketService } from './../../services/basket.service'
import { NgModule } from '@angular/core';
// import { BrowserModule } from '@angular/platform-browser';
// import { FormsModule } from '@angular/forms';
import { Product } from '../../objects/product';

import { fadeInAnimation } from './../../animations/routerFader.component';
import { Fader } from './../../animations/fader.animation'
// import { locale } from '../../../../../server/vendor/sonata-project/core-bundle/Resources/public/vendor/moment/moment';
import { RouterModule } from '@angular/router'

@Component({
  selector: 'app-basket',
  templateUrl: 'basket.component.html',
  animations: [fadeInAnimation, Fader()],
  host: { '[@fadeInAnimation]': '' }
})

export class BasketComponent {
  loader = 'true';

  products: any[] = [];
  basket = [];
  // basketItems = [];
  counter = Array;
  totalHT = 0;
  tva = 0;
  totalTTC = 0;
  totalTva = 0;

  constructor(
    private basketService: BasketService
  ) {
    this.getTva()
  }

  getTva() {
    this.basketService.getTva().subscribe(data => {
      // console.log("getTva in comp "+data)
      if (data) {
        this.tva = data;
      }
    }, error => {
      // Log errors if any
      alert('Il y a eu une erreur. Réferrez vous à l\'administrateur')
    }, () => {
      this.getBasket()
    });
  }

  // Function to check storage validity (1day)
  checkValidity() {

    const lastclear = localStorage.getItem('lastclear'),
      time_now = (new Date()).getTime();

    // .getTime() returns milliseconds so 1000 * 60 * 60 * 24 = 24 days
    if ((time_now - Number(lastclear)) > (1000 * 60 * 60 * 24)) {

      localStorage.clear();

      localStorage.setItem('lastclear', time_now.toString());
    }
  }

  clearSession() {
    localStorage.clear();
  }


  addProduct(productId, qte) {
    this.basket = this.basketService.addProductBasket(productId, qte);
  }

  getBasket() {
    this.basketService
      .getBasketlistProducts() // this.basket)
      .subscribe(data => {
          if (data) {
            this.products = data;
            console.log(this.products)
            this.basket = this.basketService.getBasket();

            // Assign product qte from basket
            this.basket.forEach((basketItem) => {
              this.products.forEach(product => {
                if ( basketItem.id === product.id ) {
                  product.qte = basketItem.qte;
                }
              });
              this.refreshTotal();
            });
          }
        }, error => {
          // Log errors if any
          alert('Il y a eu une erreur. Réferrez vous à l\'administrateur.')
        }, () => {
          this.loader = 'false';
          console.log('complete')
        }
      );
  }


  quantityChange(product: Product, newQ) {
    this.addProduct(product.id, newQ);
    this.refreshTotal();
  }

  refreshTotal() {
    console.log('ok');
    this.totalHT = this.basketService.getBasketPrice();
    this.totalTva = this.totalHT * this.tva; // .00000001 apparait de temps en temps!?? c'est quoi ce délire
    // this.totalTva.toFixed(2) //Ne résoud pas le problème
    this.totalTva = +(Math.round(this.totalTva * 100)  / 100); // résoud le problème
    // console.log(this.totalTva)
    this.totalTTC = this.totalHT + this.totalTva;
  }

  deleteformbasket(productId) {
    if (this.basket) {
      this.basket = this.basket.filter(basketItem => basketItem.id !== productId);
      // delete this.basket[productId];
    }

    for (let i = this.products.length - 1; i >= 0; i--) {
      if (this.products[i].id === productId) {
        // console.log("products to delete:"+ this.products[i])
        this.products.splice(i, 1);
      }
    }
    // console.log("products after:"+this.products)
    localStorage.setItem('basketlist', JSON.stringify(this.basket))
    this.refreshTotal();
  }


  addQte(product) {
    if (!product.qte) {
      product.qte = 0
    }
    product.qte = product.qte + product.step
    // console.log("qte of produitid:" + product);
    // console.log(product.qte);
    this.basketService.addProductBasket(product.id, product.qte);
    this.refreshTotal();
  }

  deleteQte(product) {
    product.qte = product.qte - product.step
    // console.log("qte of produitid:" + product);
    // console.log(product.qte);
    this.basketService.addProductBasket(product.id, product.qte);
    this.refreshTotal();
  }
}
