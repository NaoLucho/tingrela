import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { BasketService } from './../../services/basket.service';

@Component({
  selector: 'app-payment',
  templateUrl: 'payment.component.html',
  styleUrls: ['payment.style.scss']

})

export class PaymentComponent {
  amount: number = null; // en centimes: 100 = 1€
  reference: string = null;
  email: string = null;

  public constructor(private route: ActivatedRoute, private basketService: BasketService) {
    this.route.queryParams.subscribe(params => {
      this.reference = params['reference']
      this.amount = params['total']
      this.email = params['email']
    });
    if (this.reference) {
      basketService.clearBasket();
    }
  }

}
